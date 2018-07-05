var basicEditor;
function useBasicEditor(nodeName,tagNode,tagsLibrary,oninit){
  componentsIndex[nodeName].editor=function(node){
    window.loadingBasicForm=true;
    basicEditor.editElement(node,tagNode,tagsLibrary);
    oninit();
    changeEditor("basic.form");
    basicEditor.div.parentNode.style.position="relative";
    basicEditor.div.style.width=basicEditor.div.parentNode.offsetWidth;
    basicEditor.wsTop.style.width=basicEditor.wsTop.parentNode.offsetWidth-12;
    basicEditor.div.style.height=basicEditor.div.parentNode.offsetHeight-basicEditor.div.offsetTop;
    basicEditor.wsTop.style.height=basicEditor.wsTop.parentNode.offsetHeight-basicEditor.wsTop.offsetTop-12;
    basicEditor.div.parentNode.style.position="";
    basicEditor.select(node);
    window.loadingBasicForm=false;
    return currentNode;
  };

  componentsIndex[nodeName].saver=function(node){
    if(window.loadingBasicForm) return false;
    return currentNode;
  };
}
function installBasicEditor(){
  var sty=document.createElement("style");
  var tmSty =
    ".basicFormEditor{margin:2px;}";
  try{
    sty.innerHTML=tmSty;
  } catch (e) {
    sty.setAttribute("type", "text/css"); 
    sty.styleSheet.cssText=tmSty;
  }
  document.body.appendChild(sty);
  var div=document.createElement("div");
  divHtmlEditor=div;
  //div.style.position="relative";
  div.style.height="100%";
  div.className="basicFormEditor";
  draw3(div,[
    "style",{},".htmlEditor_toolbar{background-color:menu;border:1px outset menu;padding:1px;} .htmlEditor_toolbar a{text-decoration:none;border:1px solid menu;padding:0px 2px;} .htmlEditor_toolbar a:hover{border:1px outset menu;}"+
      ".basicEditorBox{position:absolute;border:1px dotted black;} .basicEditorCorner{position:absolute;border:1px solid black;background-color:white;width:4px;height:4px;}",
    "div",{"class":"htmlEditor_toolbar",unselectable:"on",onmousedown:function(){return false;}},[
      "a",{href:"javascript:void(1)",onclick:function(){
        try{
          basicEditor.selectedNode.setAttribute("left",(-basicEditor.selectedElement.offsetWidth+basicEditor.getElementNode(basicEditor.selectedNode.parentNode).offsetWidth)/2 );
          basicEditor.redraw(basicEditor.selectedNode);
          basicEditor.select(basicEditor.selectedNode);
        }catch(e){}
      }},"C",
      "a",{href:"javascript:void(1)",onclick:function(){document.execCommand('Underline',false,null);return false;}},"U",
      "a",{href:"javascript:void(1)",onclick:function(){document.execCommand('Italic',false,null);return false;}},"I",
      "a",{href:"javascript:void(1)",onclick:function(){document.execCommand('StrikeThrough',false,null);return false;}},"ST",
      "a",{href:"javascript:void(1)",onclick:function(){document.execCommand('FontName',false,prompt("Font Name","Arial"));return false;}},"Font",
      "a",{href:"javascript:void(1)",onclick:function(){document.execCommand('FontSize',false,prompt("Font Size","1"));return false;}},"Size"
    ],
    "div",{id:"basicEditorWs",style:"position:absolute;overflow:hidden;"},[],
    "div",{id:"basicEditorWsTop",style:"position:absolute;overflow:hidden;"},[]
  ]);
  installEditor("basic.form",div);
  div.childNodes[3].onmousedown=function(event){
    event=window.event?window.event:event;
    var xe=event.layerX+basicEditor.getRelativeLeft(event.originalTarget);
    var ye=event.layerY+basicEditor.getRelativeTop(event.originalTarget);
    for(var i=1,l=basicEditor.elements.length;i<l;i++){
    var x=basicEditor.getRelativeLeft(basicEditor.elements[i][1]);
    var y=basicEditor.getRelativeTop(basicEditor.elements[i][1]);
    var w=basicEditor.elements[i][1].offsetWidth;
    var h=basicEditor.elements[i][1].offsetHeight;
      if((xe>=x)&&(ye>=y)&&(xe<=(x+w))&&(ye<=(y+h))){
        basicEditor.select(basicEditor.elements[i][0]);
        return;
      }
    }
    //basicEditor.select(basicEditor.form);
  };
  basicEditor={
    "node":null/*n*/,
    "nodes":null/*n.parentNode.parentNode.childNodes*/,
    "div":div.childNodes[2],/*document.getElementById("basicEditorWs"),*/
    "wsTop":div.childNodes[3],/*document.getElementById("basicEditorWs"),*/
    "htmlElements":[],
    "elements":[],
    "elementsCount":0,
    "selectedNode":null,
    "selectedElement":null,
    "createElement":function(nodeName){
      return this.node.ownerDocument.createElement(nodeName);
    },
/*    "editNode":function(editNode){
      this.editNode=editNode;
      return this.node.ownerDocument.createElement(nodeName);
    },*/
    "getValues":function(e){
      var res=[];
      if(e.attributes) for(var i=0,l=e.attributes.length;i<l;i++){
        res.push(["$"+e.attributes[i].nodeName,e.attributes[i].nodeValue]);
      }
      res.push(["$e",e]);
      res.push(["$_nodeValue",e.nodeValue]);
      var chs=[];
      if(e.childNodes) for(var i=0,l=e.childNodes.length;i<l;i++){
        chs.push([e.childNodes[i].nodeName,this.createElementFromNode(e.childNodes[i],true)]);
      }
      res.push(["$_",chs]);
      return res;
    },
    "convert":function(code){
      code=code.split("node::").join("node.");
      code=code.split("!isset").join("'undefined'==typeof");
      code=code.split("isset").join("'undefined'!=typeof");
      return code;
    },
    "evaluate":function(__s,__v){
      var __ma,__f;
      for(var _i=0,_l=__v.length;_i<_l;_i++) eval("var "+__v[_i][0]+"=__v[_i][1];");
      var node={
        "join":function(g,a){var r="",f=true;for(var i=0;i<a.length;i++){r=r+(f?"":g)+a[i][1];f=false;};return r;}
      };
      while(__ma=__s.match(/#\{([^\}]+)\}/)){
        try{eval("__f=function(){"+this.convert(__ma[1])+"}");} catch(__ex){if(console && console.debug)console.debug(__ex);__f=function(){return "";};} 
        __s=__s.substr(0,__s.indexOf(__ma[0]))+__f()+__s.substr(__s.indexOf(__ma[0])+__ma[0].length);
      }
      while(__ma=__s.match(/@\{([^\}]+)\}/)){
        try{eval("__f="+this.convert(__ma[1])+";");} catch(__ex){if(console && console.debug)console.debug(__ex);__f="";} 
        __s=__s.substr(0,__s.indexOf(__ma[0]))+__f+__s.substr(__s.indexOf(__ma[0])+__ma[0].length);
      }
      while(__ma=__s.match(/\$\{([^\}]+)\}/)){
        try{eval("__f="+this.convert(__ma[1])+";");} catch(__ex){if(console && console.debug)console.debug(__ex);__f="";}
        __f=JSON.stringify(__f);
        __s=__s.substr(0,__s.indexOf(__ma[0]))+__f+__s.substr(__s.indexOf(__ma[0])+__ma[0].length);
      }
      return __s;
    },
    "getComponent":function(name){
      //if(name=="#text") return "@{$_nodeValue}";
      for(var i=0,l=this.nodes.length;i<l;i++){
        if(this.nodes[i].hasAttributes() && (this.nodes[i].getAttribute("name")==name)){
          for(var j=0,k=this.nodes[i].childNodes.length;j<k;j++){
            if((this.nodes[i].childNodes[j].nodeName=="#comment")&&(this.nodes[i].childNodes[j].nodeValue.substr(0,8)=="#preview")){
              var fp;
              eval("fp=function($n){"+this.nodes[i].childNodes[j].nodeValue.substr(8)+"};");
              return fp(this.nodes[i].childNodes[j]);
            }
          }
          return this.nodes[i].textContent;
        }
      }
    },
    "insertHTML":function(html,owner,before){
      var d=document.createElement("div");
      d.innerHTML=html;
      d.firstChild.id="be.cmp."+this.elementsCount;
      this.htmlElements.push("be.cmp."+this.elementsCount);
      this.elementsCount++;
      if(!owner) owner=this.div;
      var h=d.firstChild;
      if(before) owner.insertBefore(h,before);
      else owner.appendChild(h);
      return h;
    },
    "registerChildNodes":function(e){
      var h;
      if(e.childNodes)for(var i=0,l=e.childNodes.length;i<l;i++){
        if(h=document.getElementById("$be"+getXPath(e.childNodes[i]))){
          this.registerElement(e.childNodes[i],h);
        }
        this.registerChildNodes(e.childNodes[i]);
      }
    },
    "registerElement":function(e,h){
        for(var i=0,l=this.elements.length;i<l;i++){
          if(this.elements[i][0]===e){
            this.elements[i][1]=h;
            return;
          }
        }
      this.elements.push([e,h]);
    },
    "insertComponent":function(name,owner){
      return this.insertHTML(this.getComponent(name),owner);
    },
    "createElementFromNode":function(e,preregister){
      var d=document.createElement("div");
      h=this.evaluate(this.getComponent(e.nodeName),this.getValues(e));
      d.innerHTML=h;
      if(d.firstChild.nodeType==1)d.firstChild.id="$be"+getXPath(e);
      return d.innerHTML;
    },
    "insertElement":function(e,owner){
      var h=this.insertHTML(this.createElementFromNode(e),owner);
      this.elements.push([e,h]);
      return h;
    },
    "redraw":function(e){
      var h=this.getElementNode(e);
      var h1=this.insertHTML(this.createElementFromNode(e),h.parentNode,h);
      h.parentNode.removeChild(h);
        for(var i=0,l=this.elements.length;i<l;i++){
          if(this.elements[i][0]===e){
            this.elements[i][1]=h1;
          }
        }
      this.registerChildNodes(e);
    },
    "editElement":function(e,tagNode,tagsLibrary){
      this.node=tagNode;
      this.nodes=tagsLibrary;
      while(this.div.firstChild){this.div.removeChild(this.div.firstChild)};
  //    delete this.elements;
      this.htmlElements=[];
      this.elements=[];
      this.elementsCount=0;
      basicEditor.form=e;
      basicEditor.insertElement(basicEditor.form);
      basicEditor.registerChildNodes(basicEditor.form);
    },
    "getElementNode":function(e){
        for(var i=0,l=this.elements.length;i<l;i++){
          if(this.elements[i][0]===e){
            return this.elements[i][1];
          }
        }
    },
    "getRelativeLeft":function(e){
      var r=0;
      while((e!=this.div)&&(e!=this.wsTop)){
        r+=e.offsetLeft;
        if(e.offsetParent)e=e.offsetParent; else e=e.parentNode;
      }
      return r;
    },
    "getRelativeTop":function(e){
      var r=0;
      while((e!=this.div)&&(e!=this.wsTop)){
        r+=e.offsetTop;
        if(e.offsetParent)e=e.offsetParent; else e=e.parentNode;
      }
      return r;
    },
    "init":function(){
      var wst=document.getElementById("basicEditorWsTop");
      var pos=[[0,0],[0.5,0],[0,0.5],[1,0],[0,1],[1,0.5],[0.5,1],[1,1]];
      var selectedX,selectedY,left,width,top,height,selected;
      wst.innerHTML="";
      draw3(wst,[
        "div",{"class":"basicEditorBox"},[],
        "div",{"class":"basicEditorCorner"},[],
        "div",{"class":"basicEditorCorner"},[],
        "div",{"class":"basicEditorCorner"},[],
        "div",{"class":"basicEditorCorner"},[],
        "div",{"class":"basicEditorCorner"},[],
        "div",{"class":"basicEditorCorner"},[],
        "div",{"class":"basicEditorCorner"},[],
        "div",{"class":"basicEditorCorner"},[]
      ]);
      var ff=function(i){
        return function(event){
            event=window.event?window.event:event;
            selectedX=event.layerX;
            selectedY=event.layerY;
            if(pos[i][0]==0)left=true;
            if(pos[i][0]==1)width=true;
            if(pos[i][1]==0)top=true;
            if(pos[i][1]==1)height=true;
            selected=this;
            return false;
          }
      };
      for(var i=0,l=wst.childNodes.length-1;i<l;i++){
        wst.childNodes[i+1].onmousedown=ff(i);
      }
      wst.childNodes[0].onmousedown=ff(0);
     
      function resize(){
        for(var i=0,l=pos.length;i<l;i++){
          wst.childNodes[i+1].style.left=parseInt(wst.childNodes[0].style.left)-(1-pos[i][0])*4+pos[i][0]*parseInt(wst.childNodes[0].style.width);
          wst.childNodes[i+1].style.top=parseInt(wst.childNodes[0].style.top)-(1-pos[i][1])*4+pos[i][1]*parseInt(wst.childNodes[0].style.height);
        }
      }
      wst.onmousemove=function(event){
        event=window.event?window.event:event;
        var x=event.clientX-wst.offsetLeft-selectedX;
        var y=event.clientY-wst.offsetTop-selectedY;
        if(selected){
          if(left) wst.childNodes[0].style.left=x+"px";
          if(top) wst.childNodes[0].style.top=y+"px";
          if(width) wst.childNodes[0].style.width=(x-parseInt(wst.childNodes[0].style.left))+"px";
          if(height) wst.childNodes[0].style.height=(y-parseInt(wst.childNodes[0].style.top))+"px";
          resize();
        }
      }
      wst.onmouseup=function(){
        selected=null;
        left=false;
        top=false;
        width=false;
        height=false;
        basicEditor.selectedNode.setAttribute("left",parseInt(wst.childNodes[0].style.left)-basicEditor.getRelativeLeft(basicEditor.selectedElement.offsetParent));
        basicEditor.selectedNode.setAttribute("top",parseInt(wst.childNodes[0].style.top)-basicEditor.getRelativeTop(basicEditor.selectedElement.offsetParent));
        basicEditor.selectedNode.setAttribute("width",parseInt(wst.childNodes[0].style.width));
        basicEditor.selectedNode.setAttribute("height",parseInt(wst.childNodes[0].style.height));
        basicEditor.redraw(basicEditor.selectedNode);
        basicEditor.select(basicEditor.selectedNode);
      }
      this.selectHtml=function(div){
        wst.childNodes[0].style.left=this.getRelativeLeft(div)+"px";
        wst.childNodes[0].style.top=this.getRelativeTop(div)+"px";
        wst.childNodes[0].style.width=div.offsetWidth+"px";
        wst.childNodes[0].style.height=div.offsetHeight+"px";
        resize();
      }
      this.select=function(e){
        for(var i=0,l=this.elements.length;i<l;i++){
          if(this.elements[i][0]===e){
            this.selectedNode=e;
            this.selectedElement=this.elements[i][1];
            this.selectHtml(this.elements[i][1]);
            loadProperties(e);
          }
        }
      }
    }
  };
/*  basicEditor.form=basicEditor.createElement("basic.form");
  basicEditor.form.setAttribute("title","New form");
  basicEditor.editElement(basicEditor.form);*/
  basicEditor.init();
}
