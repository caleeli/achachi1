var ztree={
/*tags*/
PARENT_TAG:"div",
CHILDREN_TAG:"div",
/*Elements*/
cache:[],
/*Nodes*/
cacheIndex:[],
/**/
xml:null,
/*loading resource*/
createFromSrc:function(src){
  var x=this.loadXml(src);
  return this.createFromXml(x);
},
createFromXml:function(xml){
  currentNode=this.xml=xml;
  return ztree.draw(xml);
},
loadXml:function(src){
  var i,root;
  var dom=typeof(src)=="object"?src:ajax_call(src,"tsmp="+new Date().getTime());
  if(dom.firstChild.nodeName=="xml") root=dom.firstChild.nextSibling;
  else root=dom.firstChild;
  /**/
  this._joinTextNodes(root);
  return root;
},
_joinTextNodes:function(n){
  if(n.nodeType!=1) return;
  var t=n.firstChild;
  while(t && t.nextSibling){
    if(t.nodeName=="#text" && t.nextSibling.nodeName=="#text"){
      t.nodeValue=t.nodeValue+t.nextSibling.nodeValue;
      n.removeChild(t.nextSibling);
    } else {
      t=t.nextSibling;
    }
  }
  for(var i=0,l=n.childNodes.length;i<l;i++){
    ztree._joinTextNodes(n.childNodes[i]);
  }
},
/** LOAD NODE**/
loadNode:function(n){
  /*Load editor's node properties*/
  if((n.nodeName=="component")||(n.nodeName=="bcomponent")||(n.nodeName=="def")) {
    /*Agregates component definition*/
    structure_node_component_add(n);
  }
  if(n.nodeName=="#comment") {
    /*Agregates component editor plugin*/
    loadPlugin(n);
  }
  if(n.nodeName=="include") {
    /*Loads a library*/
    loadLibrary(n.getAttribute("src"));
  }
},
/*ztre core*/
  draw:function(node){
    /*CREATE TREE*/
    var tree=document.createElement(this.PARENT_TAG);
    this._drawNode(node,tree);
    /*RETURN TREE*/
    return tree;
  },
  _drawNode:function(node,container,beforeOf){
    var childContainer = this._draw(node,container,beforeOf);
    this.loadNode(node);
    if(node.childNodes) {
      for(var i=0,l=node.childNodes.length;i<l;i++){
        this._drawNode(node.childNodes[i], childContainer);
      }
    }
  },
  _draw:function(node,container,beforeOf){
    var e,i;
    i=this.cacheIndex.indexOf(node);
    if(i!=-1){
      if(beforeOf) e=container.insertBefore(this.cache[i], beforeOf); else e=container.appendChild(this.cache[i]);
      while(e.childNodes[2].firstChild) e.childNodes[2].removeChild(e.childNodes[2].firstChild);
    } else {
      if(beforeOf) e=container.insertBefore(this.createCache(node), beforeOf); else e=container.appendChild(this.createCache(node));
    }
    this.updateElement(e, node);
    return e.childNodes[2];
  },
  insertAfter:function(parent,newnode,ref){
    if(ref.nextSibling) parent.insertBefore(newnode,ref.nextSibling);
    else parent.appendChild(newnode);
  },
  createCache:function(node){
    var i=this.cacheIndex.indexOf(null);
    if(i!=-1){
      this.cacheIndex[i]=node;
      return this.cache[i];
    }
    var c=draw3.create([
      this.CHILDREN_TAG,{"class":"treenode"},[
        "div",{"class":"treenodedragzone"}," ",
        "a",{href:"#"},["img",{src:"/achachi/webeditor/images/16/library.png"},[],"span",{},node.nodeName],
        this.PARENT_TAG,{"class":"treenodecontent"},[],
        "div",{"class":"treenodedragzone"}," "
      ]])[0];
    this.cache.push(c);
    this.cacheIndex.push(node);
    c.childNodes[1].onclick=this.onclick;
    c.childNodes[1].ondblclick=this.ondblclick;
    $(c.childNodes[1]).keydown(this.onkeydown);
    return c;
  },
  recicleCache:function(node){
    var i=this.cacheIndex.indexOf(node);
    if(i!=-1){
      this.cacheIndex[i]=null;
    }
  },
  updateElement:function(elem,node){
    /*icon*/
    elem.childNodes[1].childNodes[0].src=this.getNodeIcon(node);
    /*label*/
    elem.childNodes[1].childNodes[1].firstChild.nodeValue=this.getNodeLabel(node);
    if(!node.attributes || (node.getAttribute("__ideNodeOpen")=="false")) elem.childNodes[2].style.display="none";
    /*reset drag&drop*/
    elem.style.left="";
    elem.style.top="";
  },
/*shared function*/
openNode:function(n){
  n.setAttribute("__ideNodeOpen","true");
  $(this.findElementOf(n).childNodes[2]).show();
},
getNodeLabel:function(n){
  var name="";
  if(n.attributes) name=(n.getAttribute("name")?n.getAttribute("name"):(n.getAttribute("src")?n.getAttribute("src"):""))+(n.getAttribute("class")?"("+n.getAttribute("class")+")":"");
  if(name=="" && n.attributes) {
    for(var i=0,l=n.attributes.length;i<l;i++) if(n.attributes[0].nodeName.substr(0,2)!="__") {
      name = "["+n.attributes[0].nodeName+"="+n.attributes[0].nodeValue+"]";
      if(n.attributes[0].nodeValue) break;
    }
  }
  return "("+n.nodeName+")"+name;
},
getNodeIcon:function(n){
  return img=(componentsIndex[n.nodeName] && componentsIndex[n.nodeName].image)?componentsIndex[n.nodeName].image:"images/16/default.gif";
},
refresh:function(){
  Z.emptyNode(document.getElementById("structure"));
  draw3(document.getElementById("structure"),ztree.draw(ztree.xml));
  ztree.loadDragAnDropFunctionality();
},
/*private events*/
onclick:function(){
  /*Focus selection*/
  $(".treenode a").removeClass("treenodeselected");
  $(this).addClass("treenodeselected");
  this.focus();
  /*Update Changes*/
  updateChanges();
  /*Change node*/
  currentNode=ztree.findNodeOf(this.parentNode);
  /*Load Node Editor*/
  loadNodeEditor();
},
ondblclick:function(){
  $(this.parentNode.childNodes[2]).toggle();
  ztree.findNodeOf(this.parentNode).setAttribute(
    "__ideNodeOpen",
    $(this.parentNode.childNodes[2]).is(":visible")?"true":"false"
  );
},
onkeydown:function(event){
  var nxt;
  var k=event.which;
  //37=LEFT
  if(k==37) {
    $(".treenodeselected").parent().parent().parent().find("a").first().dblclick();
    $(".treenodeselected").parent().parent().parent().find("a").first().click();
  }
  //38=UP
  if(k==38) {
    var nodes = $(".treenode:visible");
    var i = nodes.index($(".treenodeselected").parent());
    if(i>0) $(nodes[i-1]).find("a").first().click();
  }
  //39=RIGHT
  if(k==39) {
    if(!$(".treenodeselected").next().is(":empty") && $(".treenodeselected").next().is(":visible"))k=40;
    $(".treenodeselected").next().show();
  }
  //40=DOWN
  if(k==40) {
    var nodes = $(".treenode:visible");
    var i = nodes.index($(".treenodeselected").parent()[0]);
    if(i<nodes.length-1) $(nodes[i+1]).find("a").first().click();
  }
  return k<37 || k>40;
},
/*auxiliar functions*/
loadDragAnDropFunctionality:function(){
    /*DRAG AND DROP ORDERING*/
    $(".treenode").draggable({axis:'y',revert:true,drag:function(){}});
    function ztdrop(action){
      return function(e,u){
        var s=ztree.cache.indexOf(u.draggable[0]);
        var d=ztree.cache.indexOf(this.parentNode);
        $(this).removeClass("dragoverTop");
        $(this).removeClass("dragoverIn");
        $(this).removeClass("dragoverBottom");
        try{
          action(ztree.cacheIndex[s], ztree.cacheIndex[d]);
        } catch(e) {
        }
        ztree.refresh();
      };
    }
    $(".treenode").each(function(){
      var nodos=this.childNodes;
      $(nodos[0]).droppable({tolerance:"pointer",over:function(){$(this).addClass("dragoverTop");},out:function(){$(this).removeClass("dragoverTop")},drop:ztdrop(function(s,d){d.parentNode.insertBefore(s,d)})});
      $(nodos[1]).droppable({tolerance:"pointer",over:function(){$(this).addClass("dragoverIn");},out:function(){$(this).removeClass("dragoverIn")},drop:ztdrop(function(s,d){d.appendChild(s)})});
      $(nodos[3]).droppable({tolerance:"pointer",over:function(){$(this).addClass("dragoverBottom")},out:function(){$(this).removeClass("dragoverBottom")},drop:ztdrop(function(s,d){ztree.insertAfter(d.parentNode,s,d)})});
    });
    $(".treenode").find(".ui-droppable").last().droppable("destroy");
    $(".treenode.ui-draggable").first().draggable("destroy");
},
findIndex:function(n){
  return this.cacheIndex.indexOf(n);
},
findElementOf:function(n){
  var idx=this.findIndex(n);
  if(idx>=0) return this.cache[idx];
},
findNodeOf:function(e){
  return ztree.cacheIndex[ztree.cache.indexOf(e)];
},
redrawNode:function(n){
  var e=this.findElementOf(n);
  var p=e.parentNode;
  //p.removeChild(e);
  this._drawNode(n,p,e.nextSibling);
  e=null;
  ztree.loadDragAnDropFunctionality();
},
removeNode:function(n){
  var e=this.findElementOf(n);
  this.recicleCache(n);
  if(e) e.parentNode.removeChild(e);
  //this.redrawNode(n.parentNode);
}
};
var zgridprop={
createFromNode:function(n){
var rows=[],tdDomain;
if(n.attributes)
for(var i=0,l=n.attributes.length;i<l;i++) if(n.attributes[i].nodeName.substr(0,2)!="__"){
    tdDomain=["td",{"width":"1%"},""];
    if(componentsIndex[n.nodeName] && componentsIndex[n.nodeName].attributes) {
      var i0;
      for(i0=0;i0<componentsIndex[n.nodeName].attributes.length;i0++) {
        if(componentsIndex[n.nodeName].attributes[i0].name==n.attributes[i].nodeName) {
          if(eval("typeof("+componentsIndex[n.nodeName].attributes[i0].domain+")")!="undefined") {
            var domain;
            eval("domain="+componentsIndex[n.nodeName].attributes[i0].domain+";");
            tdDomain=["td",{width:"1%"},["select",{onchange:this.onselect(n,n.attributes[i])},function(){
              var j,op;
              op=["option",{value:""},""];
              for(j=0;j<domain.length;j++) {
                op.push("option",{value:domain[j]},domain[j]);
              }
              return op;
            }()]];
            break;
          }
        }
      }
    }
  /*Create the attribute value*/
  rows.push("tr",{},[
    "td",{},n.attributes[i].nodeName,
    "td",{},["input",{value:n.attributes[i].nodeValue,style:"width:100%;",onchange:zgridprop.onchange(n,n.attributes[i]),onkeypress:zgridprop.onkey13(n,n.attributes[i])},[]],
    tdDomain[0],tdDomain[1],tdDomain[2],
    "td",{},["a",{"class":"imgbutton",href:"javascript:void(0)",onclick:this.deleteAttribute(n,n.attributes[i])},["img",{src:"images/16/minus.gif"},[]]]
  ]);
}
return [
 "table",{border:"1",cellspacing:"0",cellpadding:"0",width:"100%"},["tbody",{},rows]
];
},
onchange:function(n,p){
return function(event){
 event=window.event?window.event:event;
 var e=event.target?event.target:event.srcElement;
 p.nodeValue=e.value;
 ztree.redrawNode(n);
}
},
onkey13:function(n,p){
return function(event){
 event=window.event?window.event:event;
 var e=event.target?event.target:event.srcElement;
 if((event.keyCode==13)||(event.which==13)){
  p.nodeValue=e.value;
  ztree.redrawNode(n);
 }
}
},
deleteAttribute:function(n,prop){
  return function(){
    n.attributes.removeNamedItem(prop.nodeName);
    prop=null;
    this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
  }
},
onselect:function(n,prop){
  return function(){
    var d=this.parentNode.previousSibling.firstChild;
    d.value=this.value;
    prop.nodeValue=d.value;
    ztree.redrawNode(n);
  };
}
};

var zpad={
autoStart:0,autoEnd:0,autoComplete:"",
autoComplete1:"",autoType:1,autoSel:{},autoIndex:0,autoWait:false,
prevTxtA:"",
searchReg:/\w+(?:\s*\([^()]*\))?/g,
dic:[],
delay:500,
highLightBlocks:true,
highLightString:false,
highLightComment:true,
create:function(){
var div=document.createElement("div");
div.style.position="relative";
draw3(div,[
  "div",{"class":"suggest",style:"display:none;"},[],
  "textarea",{"class":"zpadTextArea",
  onkeypress:this.resize,onkeyup:zpad.onkeyup,onclick:zpad.onkeyup,onkeydown:zpad.onkeydown,
  oncontextmenu:this.oncontextmenu,
  "wrap":"OFF"},""
]);
return div;
},
setCode:function(id,code){
 var div=document.getElementById(id);
 div.childNodes[1].value=code;
 div.childNodes[1].onclick();
 zpad.refreshDecoration(div.childNodes[1]);
},
getCode:function(id){
 var div=document.getElementById(id);
 return div.childNodes[1].value;
},
loadDictionary:function(src){
  var ws=src.match(this.searchReg),dup;
  for(var i=0,l=ws.length;i<l;i++){
    /*dup=false;
    for(var j=0,k=this.dic.length-1;j<k;j++){
      if(dup=(this.dic[j]==ws[i]))break;
    }*/
    dup=this.dic.indexOf(ws[i])!=-1;
    if(!dup)this.dic.push(ws[i]);
  }
},
loadDictionary2:function(src){
  eval("this.dic=this.dic.concat("+src+");");
},
resize:function(){
 var ls=this.value.split("\n");
 var h=(ls.length+4)*16;
 if(h<this.parentNode.offsetHeight-2)h=this.parentNode.offsetHeight-2;
 this.style.height=h;
 var w=0;
 for(var i=0,l=ls.length;i<l;i++) if(w<ls[i].length) w=ls[i].length;
 w=32+Math.ceil((w+10)*8.2);
 if(w<this.parentNode.offsetWidth-16)w=this.parentNode.offsetWidth-16;
 this.style.width=w;
},
oncontextmenu:function(event){
  //console.debug(":p");
  //popupMenu(event,"HOLA");
  return false;
},
onkeyup:function( event )
{
  this.onkeypress();
  zpad.autoWait=true;
  event=window.event?window.event:event;
  var sel=zpad.getInputSelection(this);
  zpad.autoSel=sel;
  /**/
  var lines=this.value.substr(0,sel.start).split("\n");
  var row=lines.length;
  var col=lines[row-1].length+1;
  var y=(row-1)*16+14;
  var x=(col-1)*8+32;
  this.previousSibling.style.top=y-16;
  this.previousSibling.style.left=x;
  zpad.autoSel.row=row;
  zpad.autoSel.col=col;
  //
  var w1=this.value.substr(0,sel.start);
  var w2=this.value.substr(sel.start);
  var ww1=w1.match(/\w+$/);
  var ww2=w2.match(/^\w*(?:\s*\([^()]*\))?/);
  zpad.autoStart=sel.start-(ww1?ww1[0].length:0);
  zpad.autoEnd=sel.start+(ww2?ww2[0].length:0);
  var w1=ww1?ww1[0]:"";
  var w=ww1?((ww1?ww1[0]:"")+(ww2?ww2[0]:"")):"";
  zpad.autoComplete="";
  this.previousSibling.style.display="none";
  this.previousSibling.innerHTML="";
  if(w){
    zpad.autoType=1;
    var ws=(
    "for(var i=0,l=10;i<l;i++) function(a,b) function fn(a,b) return if else length width height for(a in obj) if(typeof(obj)!=\"function\") window.document.getElementById"+
    this.value).match(zpad.searchReg);
    var idxm=0;
    if(ws){
      for(var i=0,l=ws.length;i<l;i++){
        if((ws[i].substr(0,w1.length)==w1)&&(w!=ws[i])){
          zpad.autoComplete=ws[i];
          this.previousSibling.innerHTML="";
          this.previousSibling.appendChild(document.createTextNode(zpad.autoComplete));
          this.previousSibling.style.display="block";
          idxm++;if(idxm>=zpad.autoIndex)break;
        }
      }
    }
    if(!(idxm>=zpad.autoIndex)){
      for(var i=0,l=zpad.dic.length;i<l;i++){
        if((zpad.dic[i].substr(0,w1.length)==w1)&&(w!=zpad.dic[i])){
          zpad.autoComplete=zpad.dic[i];
          this.previousSibling.innerHTML="";
          this.previousSibling.appendChild(document.createTextNode(zpad.autoComplete));
          this.previousSibling.style.display="block";
          idxm++;if(idxm>=zpad.autoIndex)break;
        }
      }
    }
    if(!(idxm>=zpad.autoIndex)) zpad.autoIndex=0; else zpad.autoIndex++;
  }else{
    if(sel.start==sel.end){ /*Just one line*/
      zpad.autoType=2;
      var line=w1.match(/.*$/);
      zpad.autoEnd=zpad.autoStart;
      zpad.autoComplete=Array( (2-(line[0].length % 2))+1 ).join(" ");
    }else{
      zpad.autoType=3;
      var txt=this.value.substr(sel.start,sel.end-sel.start);
      var lines=txt.split("\n");
      var lines1=[];
      for(var i=0,l=lines.length-1;i<l;i++){
        var sp0=lines[i].match(/\s*/)[0].length;sp=sp0<2?0:(sp0-2);
        lines1[i]=Array(sp+1).join(" ")+lines[i].substr(sp0);
        lines[i]="  "+lines[i];
      }
      lines1[lines.length-1]=lines[lines.length-1];
      zpad.autoStart=sel.start;
      zpad.autoEnd=sel.end;
      zpad.autoComplete=lines.join("\n");
      zpad.autoComplete1=lines1.join("\n");
    }
  }
  zpad.autoWait=false;
  zpad.nextRefresh=(new Date).getTime()+zpad.delay;
  zpad.refreshDecoration(this);
},
onkeydown:function( event ){
  event=window.event?window.event:event;
  var key=event.keyCode?event.keyCode:event.which;
  if(key==9){
    if(zpad.autoWait) return;
    if(zpad.autoComplete){
      var replace=((event.shiftKey && zpad.autoType==3)?zpad.autoComplete1:zpad.autoComplete);
      this.value=this.value.substr(0,zpad.autoStart)+replace+this.value.substr(zpad.autoEnd);
      if(zpad.autoType==3)
        zpad.setSelectionRange(this,zpad.autoStart,zpad.autoStart+replace.length);
      else if(zpad.autoType==2)
        zpad.setSelectionRange(this,zpad.autoStart+zpad.autoComplete.length,zpad.autoStart+zpad.autoComplete.length);
      else
        zpad.setSelectionRange(this,zpad.autoSel.start,zpad.autoStart+zpad.autoComplete.length);
    }
    return false;
  }

  //PgDw
  if(key==34){
    var row=this.value.substr(0,zpad.autoSel.end).split("\n").length;
    var nr=row-1+(Math.round(document.getElementById("content").offsetHeight/16)-2);
    var s=zpad.autoSel.end;
    lines=this.value.split("\n");
    if(nr>=lines.length)nr=lines.length-1;
    var s0=0;
    for(var i=0,l=row-1;i<l;i++)s0+=lines[i].length+1;
    var col=s-s0;
    for(var i=row-1,l=nr;i<l;i++)s0+=lines[i].length+1;
    s=s0+(col<lines[nr].length?col:lines[nr].length);
    zpad.setSelectionRange(this,(event.shiftKey?zpad.autoSel.start:s),s);
    zpad.autoSel.start=(event.shiftKey?zpad.autoSel.start:s);
    zpad.autoSel.end=s;
    document.getElementById("content").scrollTop=nr*16;
    return false;
  }
  //PgUp
  if(key==33){
    var row=this.value.substr(0,zpad.autoSel.end).split("\n").length;
    var nr=row-1-(Math.round(document.getElementById("content").offsetHeight/16)-2);
    if(nr<0)nr=0;
    var s=zpad.autoSel.end;
    lines=this.value.split("\n");
    var s0=0;
    for(var i=0,l=row-1;i<l;i++)s0+=lines[i].length+1;
    var col=s-s0;
    for(var i=row-2;i>=nr;i--)s0-=lines[i].length+1;
    s=s0+(col<lines[nr].length?col:lines[nr].length);
    zpad.setSelectionRange(this,(event.shiftKey?zpad.autoSel.start:s),s);
    zpad.autoSel.start=(event.shiftKey?zpad.autoSel.start:s);
    zpad.autoSel.end=s;
    document.getElementById("content").scrollTop=nr*16;
    return false;
  }
  //End
  if(key==35 && event.ctrlKey){
    zpad.setSelectionRange(this, (event.shiftKey?zpad.autoSel.start:this.value.length), this.value.length);
    document.getElementById("content").scrollTop=(this.value.split("\n").length*16)-document.getElementById("content").offsetHeight*0.5;
    return false
  }
  //Home
  if(key==36 && event.ctrlKey){
    zpad.setSelectionRange(this,0,(event.shiftKey?zpad.autoSel.end:0));
    document.getElementById("content").scrollTop=0;
    return false
  }
  //F2
  if(event.shiftKey && key==113) {
    var replace=this.value.substr(zpad.autoStart,zpad.autoEnd-zpad.autoStart);
    replace=replace.split("\n");
    for(var i in replace) replace[i]=JSON.stringify(replace[i]+"\n");
    replace = replace.join("+\n");
    this.value=this.value.substr(0,zpad.autoStart)+replace+this.value.substr(zpad.autoEnd);
    zpad.setSelectionRange(this,zpad.autoSel.start,zpad.autoSel.start+replace.length);
    return false;
  }
  //Z.console(key);
  //F1=112 ... F12=123
},
getInputSelection:function(el) {
  var start = 0, end = 0, normalizedValue, range,
  textInputRange, len, endRange;
  if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
    start = el.selectionStart;
    end = el.selectionEnd;
  } else {
    range = document.selection.createRange();
    if (range && range.parentElement() == el) {
      len = el.value.length;
      normalizedValue = el.value.replace(/\r\n/g, "\n");
      textInputRange = el.createTextRange();
      textInputRange.moveToBookmark(range.getBookmark());
      endRange = el.createTextRange();
      endRange.collapse(false);
      if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
        start = end = len;
      } else {
        start = -textInputRange.moveStart("character", -len);
        start += normalizedValue.slice(0, start).split("\n").length - 1;
        if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
          end = len;
        } else {
          end = -textInputRange.moveEnd("character", -len);
          end += normalizedValue.slice(0, end).split("\n").length - 1;
        }
      }
    }
  }
  return {start: start,end: end};
},
setSelectionRange:function(input,selectionStart, selectionEnd) {
  if (input.createTextRange) {
    var range = input.createTextRange();
    range.collapse(true);
    if(typeof(selectionEnd)!="undefined" && selectionEnd!=null) range.moveEnd('character', selectionEnd);
    if(typeof(selectionStart)!="undefined" && selectionStart!=null) range.moveStart('character', selectionStart);
    range.select();
  }
  else if (input.setSelectionRange) {
    input.focus();
    input.setSelectionRange(selectionStart, selectionEnd);
  }
},
refreshDecoration:function(txtA){
  var delay=1000;
  var t=(new Date).getTime();
  if(this.prevTxtA!=txtA.value){
    if(t<this.nextRefresh) {
      //console.debug("reprog");
      setTimeout(function(){zpad.refreshDecoration(txtA)},zpad.delay);
      return;
    }
    this.nextRefresh=t+delay;
    if(this.highlighting) return;
    this.highlighting=true;
    //console.debug("Refreshed",t,this.nextRefresh);
    this.addBlocks(txtA);
    this.prevTxtA=txtA.value;
    this.highlighting=false;
    var ndelay=(new Date).getTime()-t;
    if(10*ndelay<zpad.delay)zpad.delay=10*ndelay;
    if(5*ndelay>zpad.delay)zpad.delay=10*ndelay;
  }
},
cleanBlocks:function(txtA){
  var div=txtA.parentNode;
  while(div.childNodes[2])div.removeChild(div.childNodes[2]);
},
addDecoration:function(txtA,style,start,length){
  var div=txtA.parentNode;
  var pos=this.getPosition(txtA.value,start);
  draw3(div,["div",{"class":"cdDecoration",style:"position:absolute;z-index:-1;"+
    "left:"+pos.x+";top:"+pos.y},txtA.value.substr(start,length)], div.childNodes.length>2?div.childNodes[2]:null );
},
//classBlock:{"/":"cdComment",'"':"cdString","'":"cdString","{":"cdBlock"},
addBlock:function(blocks,txt,cla,start,length,txtWidth){
  var lines=txt.substr(0,start).split("\n");
  var row=lines.length;
  var y=((row-1)*16)+14;
  
  var lines2=txt.substr(start,length).split("\n");
  var row2=lines2.length;
 
  if(row2==1){
    var col=lines[row-1].length+1;
    var x=((col-1)*8)+32;
    var x2=((col+lines2[0].length-1)*8)+32;
    blocks.push([],{"class":cla,
      style:["position:absolute;z-index:-1;",
      "left:",x,";top:",y,";width:",(x2-x),";height:16"].join("")},"div");
  } else {
    var tab=lines[row-1].match(/^\s*/)[0].length;
    var xtab=(tab<<3)+32;
    var y2=((row2-1+row-1)<<4)+14;
    blocks.push([],{"class":cla,
      style:["position:absolute;z-index:-1;",
      "left:",xtab,";top:",y,";width:",(txtWidth-xtab-4),";height:",(y2-y+16)].join("")},"div");
  }
},
//subBlocksRX:/(?:\/\*[\w\W]*?\*\/)|(?:\/\/.*)|(?:"(?:[^"\n\\]+|\\"|\\.)*?")|(?:'(?:[^'\n\\]+|\\'|\\.)*?')/g,
//subBlocksRX:/(?:\/\*.*\*\/)|(?:\/\/.*)|(?:"(?:[^"\n\\]+|\\"|\\.)*?")/g,
subBlocksRX:/(?:\/\*.*\*\/)|(?:\/\/.*)/g,
blockRX:/(?:\{[^{}]*\})/g,
spacerRX:/./g,
addBlocks:function(txtA){
  var c=txtA.value,txtWidth=txtA.offsetWidth;
  var ex=true;
  var blocks=[];
  var clas={"/":"cdComment",'"':"cdString","'":"cdString","{":"cdBlock"};
  var fnblock=function(match,pos,txt){
    ex=true;
    if((zpad.highLightString && (match[0]=="'"||match[0]=='"')) || match[0]=="{" ||(zpad.highLightComment && match[0]=="/")){
      window.zpad.addBlock(blocks,txt,clas[match[0]],pos,match.length,txtWidth);
    }
    return match.replace(window.zpad.spacerRX,"_");
  }
  zpad.cleanBlocks(txtA);
  try{ 
    var t=(new Date).getTime()+1000;
    c=c.replace(window.zpad.subBlocksRX,fnblock);
    ex=zpad.highLightBlocks;
    while(ex){
      ex=false;
      c=c.replace(window.zpad.blockRX,fnblock);
      if((new Date).getTime()>t) {break;}
    }
  } catch(e){
    //Limitations of the regexp engine
    Z.console(e);
  }
  draw3(txtA.parentNode,blocks.reverse());
},
getPosition:function(txt,pos){
  var lines=txt.substr(0,pos).split("\n");
  var row=lines.length;
  var col=lines[row-1].length+1;
  var y=(row-1)*16+14;
  var x=(col-1)*8+32;
  var tab=lines[row-1].match(/^\s*/)[0].length;
  var xtab=tab*8+32;
  return {row:row,col:col,x:x,y:y,tab:tab,xtab:xtab,line:lines[row-1],lines:lines};
}
}

var ztab={
create:function(tabs,index,contentId,buttons){
  var res=[];
  for(var i=0,l=tabs.length;i<l;i++){
    if(i==index) res.push("span",{"class":"ztab"},tabs[i]);
    else res.push("button",{"class":"ztab",onclick:this.onclick(i,contentId)},tabs[i]);
  }
  if(buttons) res=res.concat(buttons);
  return res;
},
select:function(div,index,contentId){
  for(var i=0,l=div.childNodes.length;i<l;i++) if(div.childNodes[i].className!="panelButton"){
    var t=(i==index)?"span":"button";
    if(div.childNodes[i].nodeName!=t){
      var s=ztab.changeTag(div.childNodes[i],t);
      div.insertBefore(s,div.childNodes[i]);
      if(t=="button") s.onclick=this.onclick(i,contentId);
      div.removeChild(div.childNodes[i+1]);
    }
  }
},
changeTag_:function(div,tagName){
  var s=document.createElement(tagName);
  while(div.firstChild){
    s.appendChild(div.firstChild);
  }
  return s;
},
changeTag:function(div,tagName){
  var s=ztab.changeTag_(div,tagName);
  s.className="ztab";
  return s;
},
onclick:function(index,contentId){
  return function(){
    ztab.select(this.parentNode,index,contentId);
    var div=document.getElementById(contentId);
    for(var i=0,l=div.childNodes.length;i<l;i++){
      div.childNodes[i].style.display=(i==index)?"block":"none";
    }
  };
}
}

function minimizeTab(afterAction){
  return [
    "span",
    {
      "class":"panelButton",
      "onclick":function(){
        var tb=this.parentNode.parentNode.parentNode.parentNode.parentNode;
        if(!tb.getAttribute("pheight")) tb.setAttribute("pheight", tb.style.height);
        if(!tb.rows[1].firstChild.getAttribute("pheight")) tb.rows[1].firstChild.setAttribute("pheight", tb.rows[1].firstChild.style.height);
        if(tb.style.height=="") {
          tb.style.height = tb.getAttribute("pheight");
          tb.rows[1].firstChild.style.height=tb.rows[1].firstChild.getAttribute("height");
          tb.rows[1].firstChild.firstChild.style.display="";
          this.innerHTML="_";
          if(afterAction) afterAction(tb);
        } else {
          tb.rows[1].firstChild.firstChild.style.display="none";
          tb.rows[1].firstChild.style.height="";
          tb.style.height = "";
          this.innerHTML="¬";
          if(afterAction) afterAction(tb);
        }
      }
    },
    "_"
  ];
}

function resizePanel(tabTable){
  tabTable.style.height="100%";
  tabTable.rows[1].firstChild.style.height="";
  var d=tabTable.rows[1].firstChild.firstChild.style.display;
  tabTable.rows[1].firstChild.firstChild.style.display="none";

  Z._fixSize(tabTable);
  Z._fixSize(tabTable.rows[1].firstChild);
  tabTable.rows[1].firstChild.firstChild.style.display=d;
  tabTable.rows[1].firstChild.firstChild.style.height="100%";
  Z._fixSize(tabTable.rows[1].firstChild.firstChild);
}


function popup(content){
  var r=draw3(document.body,["div",{style:"position:absolute;z-index:50"},content]);
  $(r[0]).center(true);
  $(".dialog_title").draggable({appendTo:"parent"});
//  $(r[0]).dialog();
}
jQuery.fn.center = function (absolute) {
  return this.each(function () {
    var t = jQuery(this);

    t.css({
      position:    absolute ? 'absolute' : 'fixed', 
      left:        '50%', 
      top:        '50%', 
      zIndex:        '99'
    }).css({
      marginLeft:    '-' + (t.outerWidth() / 2) + 'px', 
      marginTop:    '-' + (t.outerHeight() / 2) + 'px'
    });

    if (absolute) {
      t.css({
        marginTop:    parseInt(t.css('marginTop'), 10) + jQuery(window).scrollTop(), 
        marginLeft:    parseInt(t.css('marginLeft'), 10) + jQuery(window).scrollLeft()
      });
    }
  });
};
function dialog(title,content){
  return ["div",{"class":"dialog_title"},title,"div",{style:"position:relative"},content];
}
