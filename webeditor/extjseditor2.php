<html>
<head>
<link rel="stylesheet" type="text/css" href="/ext/resources/css/ext-all.css" />
<script type="text/javascript" src="/ext/ext-all.js"></script>  
<script type="text/javascript" src="zutil.js?k=<?=microtime(true)?>"></script>  
<script type="text/javascript" src="achachi.js?k=<?=microtime(true)?>"></script>
<script type="text/javascript" src="draw3.js"></script>
<script type="text/javascript" src="php.expression.min.js"></script>  
<script>
function create3(d,content){
  if(typeof(content)=="string") d.appendChild(d.ownerDocument.createTextNode(content));
  else {
    if(Object.prototype.toString.call(content)=="[object Array]") {
      if((content.length % 3)==0 && typeof(content[0])=="string"){
        for(var i=0,l=content.length;i<l;i+=3){
          d.appendChild(this.ecreate3(d.ownerDocument,content[i],content[i+1],content[i+2]));
        }
      } else {
        for(var i=0,l=content.length;i<l;i++){
          d.appendChild(content[i]);
        }
      }
    } else {
      d.appendChild(content);
    }
  }
}

function ecreate3(doc,nodeName,atts,content){
  if(nodeName=="#text") return doc.createTextNode(content);
  var d=doc.createElement(nodeName);
  for(var a in atts) d.setAttribute(a,atts[a]);
  create3(d,content);
  return d;
}
function insert(node,tool){
  var att0=typeof(tool.region)=="undefined"?"items[]":tool.region;
  var obj=typeof(tool.obj)=="undefined"?["extjs",{"class":tool["class"]},[]]:tool.obj;
  //if(typeof(obj[1]["class"])=="undefined") obj[1]["class"]=tool["class"];
  var isArray=false;
  if(att0.substr(att0.length-2,2)=="[]") {att=att0.substr(0,att0.length-2);isArray=true;}
  else att=att0;
  console.debug(obj);
  if(att=="items"){
    create3(node,obj) ;
    window.parent.ztree.redrawNode(node);
    window.parent.currentNode=node.lastChild;
    window.parent.loadNodeEditor();
    extjs_refresh();
    return;
  }
  for(var i=0,l=node.childNodes.length;i<l;i++){
    if(node.childNodes[i].nodeName=="attribute" && node.childNodes[i].getAttribute("name")==att){
      create3(node.childNodes[i],obj);
      window.parent.ztree.redrawNode(node.childNodes[i]);
      window.parent.currentNode=node.childNodes[i].lastChild;
      window.parent.loadNodeEditor();
      extjs_refresh();
      return;
    }
  }
  var a=node.appendChild(node.ownerDocument.createElement("attribute"));
  a.setAttribute("name",att);
  if(isArray) a.setAttribute("isArray","true");
  create3(a,obj);
  window.parent.ztree.redrawNode(node);
  window.parent.currentNode=node.lastChild.lastChild;
  window.parent.loadNodeEditor();
  extjs_refresh();
}
</script>
<script type="text/javascript" src="extjseditor2/toolbar.js?k=<?=microtime(true)?>"></script>
<script>
var ready=false;
var extjsmain=null;
var extjsnode=null;
function extjs_clear(){
  try{
    //if(extjsmain)extjsmain.destroy();
    while(Ext.ComponentMgr.all.items.length){
      Ext.ComponentMgr.all.items[0].destroy();
    }
    extjsmain=null;
    extjsnode=null;
  }catch(e){}
}
var nodeIndex=[];
function loadEditor(){
  var obj,node=window.parent.oExtJSEditor.currentDocument;
  if(!node) return;
  if(node.nodeName!="layout" && node.nodeName!="window"){
    new Ext.Viewport({"layout":"column","renderTo":Ext.getBody()});
    var p=node.parentNode;
    var p1=node.ownerDocument.createElement("layout");
    p1.setAttribute("layout","column");
    p.removeChild(node);
    p1.appendChild(node);
    try{
      var r=achachi.create(p1)[0][1];
    }catch(e){
        console.log(e);
    }
    p1.removeChild(node);
    p.appendChild(node);
    p1=null;
  } else {
      var r=achachi.create(node)[0][1];
  }
  extjsnode=node;
  try{
    eval("extjsmain="+r);
  }catch(e){
      console.log(e);
  }
  if(Ext.ComponentMgr.all.items) {
    for(var i =Ext.ComponentMgr.all.items.length-1;i>=0;i--){
      try{
        makeDrop(Ext.ComponentMgr.all.items[i]);
      } catch(e){
          console.log(e);
      }
    }
  } else {
    Ext.ComponentMgr.all.each(function(i,e){
      try{
        makeDrop(e);
      } catch(e){
          console.log(e);
      }
    });
  }
  return extjsmain;
}
function extjs_refresh(){
  delete nodeIndex;
  window.location.reload(true);
  //extjs_draw(extjsnode);
}

achachi.components["layout"]=function(node){
  return extjsCreate(node,"Ext.Viewport",{"renderTo":"Ext.getBody()"});
}
achachi.components["window"]=function(node){
/*  var cfg=xml2cfg(node);
  xmlChildNodes2extjs(node.childNodes,cfg);
  return [["extjs","(function(obj){obj.show();return obj;})(new Ext.Window("+toObj(cfg)+"))"]];*/
  return extjsCreate(node,"Ext.Window",{"_construct":"obj.show();"});
}
achachi.components["extjs"]=function(node){
  return xml2extjs(node);
}
achachi.components["attribute"]=function(node){
  var cnt;
  if(node.getAttribute("isArray")=="true") {
    cnt=toArr(xmlChildNodes2extjs(node.childNodes));
  } else if(node.getAttribute("isString")=="true") {
    cnt=JSON.stringify(achachi.autoCast(achachi.content(achachi.getValues(node,true)["_"])));
  } else {
    try{
      cnt=achachi.content(achachi.getValues(node,true)["_"]);
    }catch(e){console.log(e);}
  }
  return [["attribute",[node.getAttribute("name"),cnt]]];
}
achachi.components["event"]=function(node){
  var cnt;
  try{
    cnt=achachi.content(achachi.getValues(node,true)["_"]);
  }catch(e){console.log(e);}
  return [["event",[node.getAttribute("name"),cnt]]];
}
achachi.components["expression"]=function(node){
  var cnt;
  try{
    cnt=achachi.content(achachi.getValues(node,true)["_"]);
  }catch(e){console.log(e);}
  return [["expression",[node.getAttribute("name"),cnt]]];
}
achachi.components["component"]=function(base){
  achachi.components[base.getAttribute("name")]=function(node){
    var e=achachi.getValues(
        achachi.evalNode(
          base.cloneNode(true),
          achachi.getValues(node,true),
          true
        ),
        true
      )["_"];
    if(base.getAttribute("transparent")!="true")for(var i=0,l=e.length;i<l;i++) e[i][0]=base.getAttribute("name");
    return e;
  }
  return [];
}
achachi.components["#text"]=function(node){
  return [["#text",node.nodeValue]];
};
achachi.components["root"]=function(node){
  achachi.getValues(node,true);
  return [];
};
achachi.components["definitions"]=function(node){
  achachi.getValues(node,true);
  return [];
};
achachi.components["if"]=function($e){
  var $vars=[],$_condition;
  eval('$_condition='+($e.getAttribute("condition")?$e.getAttribute("condition"):"false")+';');
  var res=[];
  if(isset($_condition) && ($_condition))
  {
    res=achachi.getValues(
        $e,
        true
      )["_"];
  }
  return res;
}
achachi.components["foreach"]=function(base){
  var values=[];
  eval("values="+achachi.getValues(base,false)["values"]);
  var res=[];
  var $_first=true;
  for(var i in values){
    var $vfield=values[i];
    var $encoded = is_array($vfield) && count($vfield)==2;
    if($encoded) $v=$vfield[1]; else $v={};
    $v["_first"]=$_first;
    if($encoded) $v["_nodeName"]=$vfield[0]; else $v["_nodeName"]=i;
    if($encoded) $v["_value"]=$v; else $v["_value"]=$vfield;
    $v["_nodeValue"]=$vfield;
    var e=achachi.getValues(
        achachi.evalNode(
          base.cloneNode(true),
          $v,
          true
        ),
        true
      )["_"];
    for(var j=0,k=e.length;j<k;j++) res.push(e[j]);
    $_first=false;
  }
  return res;
};
achachi.components["clone"]=function(node){
  return eval(node.getAttribute("value"));
}
Ext.QuickTips.init();
Ext.onReady(function(){
  Ext.Ajax.request({
    url:"fileio.php",
    method:"GET",
    params:{l:"$library/extjs.xml"},
    async:false,
    success:function(response){
      achachi.create(response.responseXML.getElementsByTagName("root")[0]);
      ready=true;
      loadEditor();
      var ft=document.getElementById("floattoolbar");
      var first=true,k=0;
      if(!window.parent.oExtJSEditor.currentCategory) window.parent.oExtJSEditor.currentCategory=0;
      //console.debug($extjsCategories);
      for(var c in $extjsCategories){
        draw3(ft.firstChild,["a",{"class":"floattab","href":"javascript:openCategory("+k+")","style":"background-color:"+(window.parent.oExtJSEditor.currentCategory==k?"white":"transparent")},c]);
        draw3(ft.childNodes[2],["div",{style:"display:"+(window.parent.oExtJSEditor.currentCategory==k?"block":"none")},(function(){
          var r=[];
//          if(first) r.push("a",{"class":"floatbutton","href":"javascript:extjs_refresh()"},["img",{"src":"images/16/refresh.gif"},[]]);
          for(var i=0,l=$extjsCategories[c].items.length;i<l;i++) {
            r.push("a",{"class":"floatbutton","href":"javascript:void(0)"},["img",{"src":"extjseditor2/"+$extjsComponents[$extjsCategories[c].items[i]].img},[]]);
          }
          return r;
        })()])
        var j=0;
        for(var i=0,l=ft.childNodes[2].lastChild.childNodes.length;i<l;i++){
          if(ft.childNodes[2].lastChild.childNodes[i].getAttribute("href")=="javascript:void(0)"){
            var def=$extjsComponents[$extjsCategories[c].items[j]];
            def["class"]=$extjsCategories[c].items[j];
            makeDrag(ft.childNodes[2].lastChild.childNodes[i], def);
            def=null;
            j++;
          }
        }
        first=false;
        k++;
      }
    }
  });
});
function toObj(obj){
  var arr=[];
  for(var a in obj){
    arr.push(JSON.stringify(a)+":"+obj[a]);
  }
  return "{"+arr.join(",")+"}";
}
function toArr(arr){
  return "["+arr.join(",")+"]";
}
function xml2extjs(node){
  if(node.nodeName!="extjs"){
    return achachi.create(node);
  }
  return extjsCreate(node,node.getAttribute("class"));
}
function extjsCreate(node,extClass,cfg0){
  if(nodeIndex.indexOf(node)==-1) {
    node.setAttribute("__extid",nodeIndex.length);
    nodeIndex.push(node);
  }
  var extjs;
  if(extClass==null) return [];
  var cfg = xml2cfg(node);
  if(cfg0)for(var a in cfg0) if(typeof(cfg[a])=="undefined") if (a.substr(0,1)!="_") cfg[a]=cfg0[a];
  xmlChildNodes2extjs(node.childNodes,cfg);
  var extjs="new "+extClass+"("+toObj(cfg)+")";
  var _c="";
  if((_c=node.getAttribute("_construct")) || (cfg0 && (_c=cfg0["_construct"]))){
    extjs="(function(obj){"+_c+";return obj})("+extjs+")";
  }
  return [["extjs",removePHP(extjs)]];
}
function removePHP(code){
  return code.replace(/<\?(php|=)[\w\W]*?\?>/,"");
}
function xml2cfg(node){
  var cfg={};
  if(node.attributes){
    for(var i=0,l=node.attributes.length;i<l;i++) {
      if(node.attributes[i].nodeName!="class" && (node.attributes[i].nodeName.substr(0,1)!="_" || node.attributes[i].nodeName=="__extid")){
        cfg[node.attributes[i].nodeName]=JSON.stringify(achachi.autoCast(node.attributes[i].nodeValue));
      }
    }
  }
  return cfg;
}
function xmlChildNodes2extjs(childNodes,cfg){
  if(typeof(cfg)!="object") cfg={};
  if(childNodes){
    var items=[];
    for(var i=0,l=childNodes.length;i<l;i++){
      var e=xml2extjs(childNodes[i]);
      if(e){
        for(var j=0,k=e.length;j<k;j++){
          if(e[j][0]=="attribute" || e[j][0]=="ext.attribute" || e[j][0]=="event" || e[j][0]=="expression"){
            cfg[e[j][1][0]]=e[j][1][1];
          } else {
            items.push(e[j][1]);
          }
        }
      }
      e=null;
      if(items.length>0)cfg["items"]=toArr(items);
    }
    return items;
  }
  return [];
}
function makeDrag(v,tool) {
    v.dragZone = new Ext.dd.DragZone(v, {

//      On receipt of a mousedown event, see if it is within a draggable element.
//      Return a drag data object if so. The data object can contain arbitrary application
//      data, but it should also contain a DOM element in the ddel property to provide
//      a proxy to drag.
        getDragData: function(e) {
            var sourceEl = v;
            if (sourceEl) {
                d = sourceEl.cloneNode(true);
                d.id = Ext.id();
                return v.dragData = {
                    sourceEl: sourceEl,
                    repairXY: Ext.fly(sourceEl).getXY(),
                    ddel: d,
                    tool:tool
                }
            }
        },

//      Provide coordinates for the proxy to slide back to on failed drag.
//      This is the original XY coordinates of the draggable element.
        getRepairXY: function() {
            return this.dragData.repairXY;
        }
    });
}
function makeDrop(g0) {
if(typeof(g0.initialConfig.__extid)=="undefined") return g;
    var g=g0.getEl();
    g.dropZone = new Ext.dd.DropZone(g, {

//      If the mouse is over a target node, return that node. This is
//      provided as the "target" parameter in all "onNodeXXXX" node event handling functions
        getTargetFromEvent: function(e) {
          if(typeof(g0.initialConfig.__extid)!="undefined"){
            //console.debug(g0.initialConfig.__extid,e.getTarget());
            return e.getTarget();
          }
        },

//      On entry into a target node, highlight that node.
        onNodeEnter : function(target, dd, e, data){
            Ext.fly(target).addClass('hover');
        },

//      On exit from a target node, unhighlight that node.
        onNodeOut : function(target, dd, e, data){
            Ext.fly(target).removeClass('hover');
        },

//      While over a target node, return the default drop allowed class which
//      places a "tick" icon into the drag proxy.
        onNodeOver : function(target, dd, e, data){
            return Ext.dd.DropZone.prototype.dropAllowed;
        },

//      On node drop, we can interrogate the target node to find the underlying
//      application object that is the real target of the dragged data.
//      In this case, it is a Record in the GridPanel's Store.
//      We can use the data set up by the DragZone's getDragData method to read
//      any data we decided to attach.
        onNodeDrop : function(target, dd, e, data){
//            var rowIndex = g.getView().findRowIndex(target);
//            var h = g.getStore().getAt(rowIndex);
//            var targetEl = Ext.get(target);
//            targetEl.update(data.patientData.name+', '+targetEl.dom.innerHTML);
            //Ext.Msg.alert('Drop gesture', 'Dropped in: '+g0.initialConfig.__extid + " region:"+data.region+" obj:"+JSON.stringify(data.obj));
            if(window.stopDrop) return;
            window.stopDrop=true;
            insert(nodeIndex[g0.initialConfig.__extid],data.tool);
            return true;
        }
    });
  return g;
}
function openCategory(i){
  var ft=document.getElementById("floattoolbar");
  for(var j=0,l=ft.childNodes[0].childNodes.length;j<l;j++)
    ft.childNodes[0].childNodes[j].style.backgroundColor="transparent";
  for(var j=0,l=ft.childNodes[2].childNodes.length;j<l;j++)
    ft.childNodes[2].childNodes[j].style.display="none";
  ft.childNodes[0].childNodes[i].style.backgroundColor="white";
  ft.childNodes[2].childNodes[i].style.display="block";
  window.parent.oExtJSEditor.currentCategory=i;
}
</script>
<style>
.floattoolbar{
  position:absolute;
  border:1px outset white;
  display:inline-block;
  background-color:transparent;
  right:4px;
  top:8px;
  z-index:2;
}
.floattitle{
  filter:alpha(opacity=70);
  -moz-opacity:0.7;
  -khtml-opacity: 0.7;
  opacity: 0.7;
  background-color:lightblue;
}
.floattab{
  display:inline-block;
  padding:2px;
  cursor:pointer;
}
.floattab:hover{
  background-color:white;
}
.floatbutton{
  display:inline-block;
  filter:alpha(opacity=70);
  -moz-opacity:0.7;
  -khtml-opacity: 0.7;
  opacity: 0.7;
  padding:2px;
}
.floatbutton:hover{
  background-color:white;
  filter:alpha(opacity=100);
  -moz-opacity:1;
  -khtml-opacity: 1;
  opacity: 1;
  position:relative;
}
.hover{
  filter:alpha(opacity=70);
  -moz-opacity:0.7;
  -khtml-opacity: 0.7;
  opacity: 0.7;
}
.toolbarbg{
  position:absolute;
  width:100%;
  height:20px;
  background:white;
  filter:alpha(opacity=70);
  -moz-opacity:0.7;
  -khtml-opacity: 0.7;
  opacity: 0.7;
}
</style>
</head>
<body>
<div id="floattoolbar" class="floattoolbar"><div class="floattitle"></div><div class="toolbarbg"></div><span style="display:inline-block;position:relative"></span><a class="floatbutton" href="javascript:extjs_refresh()"><img src="images/16/refresh.gif" /></a></div>
</body>
</html>