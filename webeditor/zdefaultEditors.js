/* ZEditor Global Variables */
var file,currentNode,dirBase,editorClass={};

function installEditor(id,div,edClass){
 div.id=id;
 div.style.display="none";
 var d;
 while(d=document.getElementById(id)) d.parentNode.removeChild(d);
 document.getElementById("content").appendChild(div);
 editorClass[id]=edClass;
}

function changeEditor(id){
 var titleBar = $("#default").parents("table:first").find("th:first div:first")[0];
 titleBar.innerHTML='Content';
 var td=document.getElementById("content");
 for(var i=0,l=td.childNodes.length;i<l;i++){
  td.childNodes[i].style.display="none";
 }
 var editor;
 if(typeof(id)=="number") {
  editor=td.childNodes[id];
  editor.style.display="block";
 } else {
  editor=document.getElementById(id);
  editor.style.display="block";
 }
 return editor;
}

/* INSTALL PLUGIN */
function installPlugin(def){
  Z.loadComponents();
  var div = def.create();
  installEditor(def.name, div, def);
  if(def.nodesEditor){
    for(var i=0,l=def.nodesEditor.length;i<l;i++){
      if(typeof(componentsIndex[def.nodesEditor[i]])!="undefined"){
        componentsIndex[def.nodesEditor[i]].editor=def.launch;
      }
    }
  }
}

/* Update changes in currentNode */
function updateChanges(){
 if(currentNode){
  if(componentsIndex[currentNode.nodeName] && componentsIndex[currentNode.nodeName].saver) {
    componentsIndex[currentNode.nodeName].saver(currentNode);
  } else if(currentNode.nodeType==1) {
  } else {
   currentNode.nodeValue=zpad.getCode("default");
  }
 }
}

var DEFAULT_ELEMENT_EDITOR="defaultElement";
/* Load editor and properties of currentNode */
function loadNodeEditor(){
 var titleBar = $("#default").parents("table:first").find("th:first div:first")[0];
 titleBar.innerHTML='Content';
 if(componentsIndex[currentNode.nodeName] && componentsIndex[currentNode.nodeName].editor) {
  res=componentsIndex[currentNode.nodeName].editor(currentNode);
 } else if(currentNode.nodeType==1) {
  changeEditor(DEFAULT_ELEMENT_EDITOR);
  editorClass[DEFAULT_ELEMENT_EDITOR].setNode(currentNode);
 } else {
  //installEditor to reset Ctrl+Z buffer
  installEditor("default",zpad.create(),zpad);
  changeEditor("default");
  zpad.setCode("default",currentNode.nodeValue);
 }
 Z.loadProperties(currentNode);
}

/* Load default Editors */
function loadDefaultEditors(){
 /* text default editor */
    installEditor("default",zpad.create(),zpad);
 /* element default editor */
    installPlugin(zelem);
 /* component default editor */
    installPlugin(zcomponent);
 /* jqueryui editor */
    installPlugin(zjqueryui);
}

/************************************
  PLUGINS: DEFAULT EDITORS 
 ************************************/

/* element default editor */
var zelem={
  "name": "defaultElement",

  create:function(){
    var div=document.createElement("div");
    return div;
  },
  
  setNode:function(n){
    var div=document.getElementById("defaultElement");
    Z.emptyNode(div);
    var res=[];
    var content="";
    res.push("tr",{},["th",{},"Node","th",{},"Values","th",{},"Id"]);
    for(var i=0,l=n.childNodes.length;i<l;i++){
      content = ztree.getNodeLabel(n.childNodes[i]);
      if(n.childNodes[i].nodeName=="#text") content=["pre",{},n.childNodes[i].nodeValue];
      var ideDiagramId="";
      if(n.childNodes[i].attributes) ideDiagramId=n.childNodes[i].getAttribute("__ideDiagramId");
      res.push("tr",{},["td",{},n.childNodes[i].nodeName,"td",{},content,"td",{},ideDiagramId]);
    }
    draw3(div,["table",{border:"1",cellpadding:"0",cellspacing:"0"},["tbody",{},res]]);
  }
}

/* component default editor */
var zcomponent = {
  "name":"componentEditor",
  "nodesEditor":["component","bcomponent","def"],
  "launch":function(node)
    {
      changeEditor("componentEditor");
      return currentNode;
    },
  "create":function()
    {
      var div=document.createElement("div");
      draw3(div,[
        "button",{onclick:function(){
          for(var i=0,l=currentNode.childNodes.length;i<l;i++)
            if(currentNode.childNodes[i].nodeName=="#comment") loadPlugin(currentNode.childNodes[i]);
        }},"Reload Plugin"
      ]);
      return div;
    }
};

/* jquery Editor */
var zjqueryui = {
  "name":"jqueryuiEditor",
  "nodesEditor":["jqueryui"],
  "launch":function(node)
    {
      if(node.firstChild) { 
        ajax_call("visual.php","html="+encodeURIComponent(node.firstChild.nodeValue),"POST");
      } else {
        ajax_call("visual.php","html="+encodeURIComponent(""),"POST");
      }
      document.getElementById("jqueryuiEditor").firstChild.src="visual.php?t="+new Date().getTime();
      changeEditor("jqueryuiEditor");
      return currentNode;
    },
  "create":function()
    {
      var div=document.createElement("div");
      draw3(div,[
        "iframe",{src:"visual.php?t="+new Date().getTime(),style:"border:none;width:100%;height:100%;"},[]
      ]);
      return div;
    },
  "save":function(html){
      Z.emptyNode(currentNode);
      draw3(currentNode,html);
//      currentNode.ownerDocument.createTextNode(html);
      ztree.redrawNode(currentNode);
    }
};

/* PopupMenu */

function popupMenu(event,content){
  //event.preventDefault();
  var pos = getEventPosition(event);
  draw3(document.body,["div",{style:"z-index:1000;position:absolute;border:2px outer black;background-color:menu;left:"+pos.x+";top:"+pos.y},content]);
  var div = document.body.lastChild;
  draw3(div,["a",{href:"javascript:void(0)",
    "onblur":function(div){return function(){
      //alert("blur");
      document.body.removeChild(div)
    }}(div)
  },"_"]);
  div.lastChild.focus();
}