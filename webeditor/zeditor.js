var componentsIndex={};
var Z={
_console:null,
console:function(txt){
  this._console.appendChild(document.createElement("pre")).appendChild(document.createTextNode(txt));
  this._console.scrollTop=this._console.scrollHeight;
},
consoleClear:function(){
  while(Z._console.firstChild)Z._console.removeChild(Z._console.firstChild);
},
consoleHTML:function(html){
  this._console.appendChild(document.createElement("pre")).innerHTML=html;
},
serverConsole:function(txt){
  var pp=this._serverconsole.appendChild(document.createElement("pre")).appendChild(document.createTextNode(txt));
  this._serverconsole.scrollTop=this._serverconsole.scrollHeight;
},
serverConsoleClear:function(){
  while(Z._serverconsole.firstChild)Z._serverconsole.removeChild(Z._serverconsole.firstChild);
},
serverConsoleHTML:function(html){
  this._serverconsole.appendChild(document.createElement("pre")).innerHTML=html;
},
databaseClear:function(){
  while(Z._database.firstChild)Z._database.removeChild(Z._database.firstChild);
},
databaseConsole:function(data){
  var p=this._database.appendChild(document.createElement("pre"));
  p.style.overflow="hidden";
  p.innerHTML=data;
  this._database.scrollTop=this._database.scrollHeight;
},
databaseConnectTo:function(){
  var connections,list=[];
  var driver;
  var host;
  var username;
  var password;
  var dbname;
  eval("connections="+ajax_call("database.php","x=","POST",true));
  list.push({value:"",label:"<nuevo>"});
  for(var i=0;i<connections.length;i++){
    list.push({value:i,label:JSON.stringify(connections[i])});
  }
  Z.popupList(list,"Select/Create connection","",function(x){
    if(x==""){
      driver=prompt("driver(Mysqli|Oracle|Db2|PDO_MYSQL|PDO_PGSQL|PDO_OCI|PDO_MSSQL|PDO_SQLITE|PDO_IBM):");
      if(driver) host=prompt("host:");
      if(host!=null) username=prompt("username:");
      if(username!=null) password=prompt("password:");
      if(password!=null) dbname=prompt("dbname:");
      if(dbname!=null) {
        Z.databaseConsole(ajax_call("database.php","driver="+driver+"&host="+host+"&username="+username+"&password="+password+"&dbname="+dbname,null,true));
      }
    } else {
      Z.databaseConsole(ajax_call("database.php","x="+x,null,true));
    }
  });
},
popupList:function(list,title,description,action){
  if(document.getElementById("popupListDiv")) document.getElementById("popupListDiv").parentNode.removeChild(document.getElementById("popupListDiv"));
  draw3(document.body,[
    "div",{id:"popupListDiv",style:"position:absolute;left:40%;top:150;width:20%;height:70%;background:menu;border:2px outset menu;"},[
      "div",{style:"border:1px inset menu"},["#text",{},title,"button",{style:"position:absolute;right:1px;font-size:10;",onclick:function(){this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)}},"X"],
      "div",{style:"padding:4px;"},[
        "div",{},description,
        "input",{
          id:"popupListInput",
          style:"width:100%",
          onkeyup:function(event){
            var k=window.event?window.event.keyCode:event.which;
            if(k==13){this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);action(this.value)}
            Z._filterList(this.nextSibling,this.value);
            if(k==40){this.nextSibling.focus();this.nextSibling.onchange();}
          }
        },[],
        "select",{
          style:"width:100%;height:80%;",size:10,
          onkeydown:function(event){
            var k=window.event?window.event.keyCode:event.which;
            if(k==8){return false;}
            if(k==13){this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);action(this.value)}
            if(k==38 && this.selectedIndex==0){this.previousSibling.focus();}
          },
          onchange:function(){this.previousSibling.value=this.value;},
          ondblclick:function(event){this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);action(this.value)}
        },
          function(){var res=[];for(i=0;i<list.length;i++)res.push("option",{value:list[i].value},list[i].label);return res;}()
      ]
    ]
  ]);
  document.getElementById("popupListInput").focus();
  Z._filterList(document.getElementById("popupListInput").nextSibling,"*");
},
_filterList:function(select,criteria){
  var ops=[];
  var e=select.firstChild;
  while(e){
    if(e.value.indexOf(criteria)==-1){
      ops.push(e);
      e=e.nextSibling;
      select.removeChild(ops[ops.length-1]);
      continue;
    }
    e=e.nextSibling;
  }
  for(var i=0,l=ops.length;i<l;i++){
    for(var j=i+1,k=ops.length;j<k;j++){
      if(ops[j].value<ops[i].value){
        var s=ops[j];
        ops[j]=ops[i];
        ops[i]=s;
      }
    }
  }
  for(var i=0,l=ops.length;i<l;i++){
    select.appendChild(ops[i]);
  }
  select.value=select.firstChild.value;
},
_fixSize:function(tdDiv){
  var d=[]
  for(var i=0,l=tdDiv.childNodes.length;i<l;i++)
    d.push(tdDiv.childNodes[i].style.display);
  var h0=tdDiv.offsetHeight;
  tdDiv.style.height=h0+"px";
  var dh=h0-tdDiv.offsetHeight;
  tdDiv.style.height=(h0+dh)+"px";
  var w0=tdDiv.offsetWidth;
  tdDiv.style.width=w0+"px";
  var dw=w0-tdDiv.offsetWidth;
  tdDiv.style.width=(w0+dw)+"px";
  for(var i=0,l=tdDiv.childNodes.length;i<l;i++)
    tdDiv.childNodes[i].style.display=d[i];
},
_fixAll:function(){
var p;
while(p=Z._toFixSize.pop()){
//  if(!document.getElementById(p)) console.debug(p);
  Z._fixSize(document.getElementById(p));
}
},
_toFixSize:[],
getUID:function(){
return "_"+((new Date()).getTime()+Math.random());
},
_panel:function(title,content,idContent){
var id=Z.getUID(),id1=Z.getUID(),id2=Z.getUID();
if(!idContent)idContent=Z.getUID();
Z._toFixSize.push(idContent);
Z._toFixSize.push(id2);
Z._toFixSize.push(id1);
Z._toFixSize.push(id);
return ["table",{id:id,border:"0",cellPadding:0,cellSpacing:0,width:"100%",height:"100%","class":"panel"},["tbody",{},
  [
   "tr",{"style":"height:24px;"},["th",{id:id2},["div",{style:"overflow:hidden;height:100%;"},title]],
   "tr",{},["td",{id:id1},["div",{id:idContent,"style":"height:100%;overflow:auto"},content]]
  ]
 ]];
},
_imgbutton:function(fn,src){
  return ["a",{"class":"imgbutton",href:"javascript:void(0)",onclick:fn},["img",{src:src},[]]];
},
_loadToolbar:function(){
  var i;
  var b,im;
  var res=[];
  var parentToolbar=res;
  for(i=0;i<toolbar.length;i++) if(typeof(toolbar[i])!="undefined"){
    if(typeof(toolbar[i].name)=="undefined"){
      res.push(
        "h",{"style":"height:42px"},
          function(){
            var res=[];
            for(var j=0,jl=toolbar[i].length;j<jl;j++) if(typeof(toolbar[i][j])!="undefined"){
              if(toolbar[i][j].name==" "){
                res.push("div",{style:"height:4px;border:1px inset activeBorder"},"&nbsp;");
              } else {
                res.push("a",{href:"javascript: void(0)",title:(toolbar[i][j].help?toolbar[i][j].help:toolbar[i][j].name),onclick:toolbar[i][j].action},[
                  "img",{src:toolbar[i][j].image},[],
                  "br",{},[],
                  "#text",{},toolbar[i][j].name
                ]
                );
              }
            }
            return res;
          }(),
        "img",{},[],
        "a",{href:"javascript: void(0)","class":"toolbarDropdown",onclick:function(){
          if(this.previousSibling.previousSibling.style.height==""){
            this.previousSibling.previousSibling.style.height="42px"
            this.previousSibling.previousSibling.style.border="none";
          } else {
            this.previousSibling.previousSibling.style.height="";
            this.previousSibling.previousSibling.style.border="1px outset activeborder";
          }
        },
        onblur:function(){
          var a=this;
          setTimeout(function(){
            a.previousSibling.previousSibling.style.height="42px";
            a.previousSibling.previousSibling.style.border="none";
          },500);
        }},["img",{src:"images/32/a-down.png"},[]]
      );
    } else if(toolbar[i].name==" " && typeof(toolbar[i].tab)==="undefined") {
      res.push("a",{"class":"hSpacer"},["img",{"class":"imgW8"},[]]);
    } else if(toolbar[i].name==" " && typeof(toolbar[i].tab)==="string") {
      if(parentToolbar===res) {res=["#text",{},toolbar[i].tab,"br",{},[]].concat(res);parentToolbar=["span",{"class":"toolbarBlock"},res];}
      else {res=["#text",{},toolbar[i].tab,"br",{},[]];parentToolbar.push("span",{"class":"toolbarBlock"},res);}
    } else {
      res.push("h",{},[
        "a",{onclick:toolbar[i].action,title:(toolbar[i].help?toolbar[i].help:toolbar[i].name),href:"javascript: void(0)"},[
          "img",{src:toolbar[i].image},[],
          "br",{},[],
          "#text",{},toolbar[i].name
        ]
      ],"img",{},[]);
    }
  }
  return parentToolbar;
},
emptyNode:function(n){
  while(n.firstChild) n.removeChild(n.firstChild);
},
loadProperties:function(n){
 this.emptyNode(this.divProperties);
 draw3(this.divProperties,zgridprop.createFromNode(n));
},
_loadWorkspace:function(){
file="";
var id1=Z.getUID(),id2=Z.getUID(),id3=Z.getUID(),id4=Z.getUID();
Z._toFixSize.push(id3);
Z._toFixSize.push(id4);
return ["tr",{id:id1},[
 "td",{width:"25%",rowSpan:2},Z._panel("Structure",[],"structure"),
 "td",{},Z._panel("Content",[],"content")
],"tr",{height:"30%"},[
 "td",{id:id2},Z._panel( 
    ztab.create(
      [
        ["#text",{},"Properties "].concat(Z._imgbutton(function(){appendProperty()},"images/16/add.gif")),
        ["#text",{},"Console "].concat(Z._imgbutton(function(){Z.consoleClear()},"images/16/clear.gif")),
        ["#text",{},"Server Console "].concat(Z._imgbutton(function(){Z.serverConsoleClear()},"images/16/clear.gif")),
        ["#text",{},"Database"].concat(Z._imgbutton(function(){Z.databaseClear()},"images/16/clear.gif"),Z._imgbutton(function(){Z.databaseConnectTo()},"images/16/connection.png")),
        ["#text",{},"Help "].concat(Z._imgbutton(function()
          {
            if(!currentNode) return;
            var d={};
            if(currentNode.attributes)
              for(var i=0,l=currentNode.attributes.length;i<l;i++)d[currentNode.attributes[i].nodeName]=currentNode.attributes[i].nodeValue;
            document.getElementById("help").innerHTML=ajax_call("help.php","n="+encodeURIComponent(currentNode.nodeName)+"&d="+encodeURIComponent(JSON.stringify(d)),null,true)
          },"images/16/_go.gif"))
      ],0,"log", minimizeTab(function(){var c=document.getElementById("content"),p=c;while(p.nodeName.toLowerCase()!="table")p=p.parentNode;resizePanel(p)}) ),
      [
        //Properties Panel
        "div",{id:"properties",style:"display:none"},[],
        //Console Panel
        "div",{id:"console",style:"display:none"},[],
        //Server Console Panel
        "div",{style:"display:none"},["table",{width:"100%",height:"100%"},["tbody",{},[
          "tr",{},["td",{id:id3},["div",{id:"serverconsole",style:"height:100%;overflow:auto"},"_"]],
          "tr",{height:"1%"},["td",{height:"1%"},
            ["#text",{},"code:","input",{style:"width:100%",
              onkeypress:function(event){
                event=window.event?window.event:event;
                var key=event.keyCode?event.keyCode:event.which;
                if(key==13) {
                  Z.serverConsole(ajax_call("serverconsole.php","c="+encodeURIComponent(this.value),"POST",true));
                  return false;
                }
              }},[]
            ]]
        ]]],
        //Database Panel
        "div",{style:"display:none"},["table",{width:"100%",height:"100%"},["tbody",{},[
          "tr",{},["td",{id:id4},["div",{id:"database",style:"height:100%;overflow:auto"},"_"]],
          "tr",{height:"1%"},["td",{height:"1%"},
            ["#text",{},"code:","input",{style:"width:100%",
              onkeypress:function(event){
                event=window.event?window.event:event;
                var key=event.keyCode?event.keyCode:event.which;
                if(key==13) {
                  Z.databaseConsole(ajax_call("database.php","c="+encodeURIComponent(this.value),"POST",true));
                  return false;
                }
              }},[]
            ]]
        ]]],
        "div",{id:"help",style:"display:none"},[]
      ],"log")
]];
},
/* LOADING ...*/
loadComponents:function(){
  for(var i=0;i<components.length;i++) {
    if(typeof(componentsIndex[components[i].name])==="undefined") {
      componentsIndex[components[i].name]=components[i];
    } else {
      for(var oa in componentsIndex[components[i].name]){
        components[i][oa]=componentsIndex[components[i].name][oa];
      }
      componentsIndex[components[i].name]=components[i];
    }
  }
},
onload:function(){
  draw3(document.body,["div",{id:"now loading",style:"z-index:100;text-align:center;background:white;border:1px outset black;position:absolute;width:256;height:128;left:"+((document.body.offsetWidth-256)/2)+";top:50%"},[
    "img",{src:"images/loading.gif"},[],
    "br",{},[],
    "#text",{},"xFreppe",
    "br",{},[],
    "br",{},[],
    "#text",{},"loading..."
  ]]);
  var id1=Z.getUID();
  this.loadComponents();
  var ws=Z._loadWorkspace();
  draw3(document.body,[
   "table",{id:"zeditorbody",border:"1",cellPadding:0,cellSpacing:0,width:"100%",height:"100%"},["tbody",{},[
    "tr",{height:"46px"},["td",{id:id1,colSpan:2},["div",{"id":"toolbar"},Z._loadToolbar()]],
    ws[0],ws[1],ws[2],ws[3],ws[4],ws[5]
   ]]
  ]);
  //Fix editor size
  Z._toFixSize.push(id1);
  Z._toFixSize.push("zeditorbody");
  Z._fixAll();
  
  //Loads current path
  if(document.location.protocol=="file:") {
    dirBase=window.location.href.split("/");
    dirBase.pop();
    dirBase=dirBase.join("/").substr(8)+"/";
  } else {
    dirBase=ajax_call("fileIO.php",null,null,true);
  }
  
  this.divProperties=document.getElementById("properties");
  this._console=document.getElementById("console");
  this._serverconsole=document.getElementById("serverconsole");
  this._database=document.getElementById("database");
  this.divProperties.style.display="block";
  //Loads zeditors properties
  loadDefaultEditors();
  
  //Load Default Library
  loadLibrary("$library/base.xml");
  changeEditor("default");

  //Load aditional dictionary for zpad
  zpad.loadDictionary2(ajax_call("php.dic",null,null,true));

  var nl=document.getElementById("now loading");
  nl.parentNode.removeChild(nl);
  nl=null;
},
loadToolbar:function(){
  Z.emptyNode(document.getElementById("toolbar"));
  draw3(document.getElementById("toolbar"), Z._loadToolbar());
},
addToolbarButton:function(def,isArray,region,position){
  var i0=toolbar.length;
  var nextRegion=false;
  if(typeof(position)!=="undefined")position=1;
  if(typeof(region)!=="undefined"){
    for(var i=0,l=toolbar.length;i<l;i++){
      if(typeof(toolbar[i].name)==="string" && toolbar[i].name==" " && typeof(toolbar[i].tab)==="string"){
        if(nextRegion){
          i0=i;break;
        }
        else if(toolbar[i].tab==region){
          if(position==0){i0=i+1;break;}
          else nextRegion=true;
        }
      }
    }
  }
  if(isArray) toolbar=toolbar.slice(0,i0).concat(def).concat(toolbar.slice(i0));
  else toolbar=toolbar.slice(0,i0).concat([def]).concat(toolbar.slice(i0));
  getToolbar();
},
resetToolbar:function(){
  toolbar=originalToolbar.slice(0);
  getToolbar();
},
pasteFromClipboard:function(){
  var xml = ajax_call("clipboard.php","paste=xml").getElementsByTagName("root")[0].firstChild;
  ztree._joinTextNodes(xml);
  var aNode = Z.xml2array(xml);
  appendChildTag(aNode[0],aNode[1],aNode[2]);
},
copyToClipboard:function(){
  var c="<root>"+getXml(currentNode)+"</root>";
  ajax_call("clipboard.php","copy="+encodeURIComponent(c));
},
xml2array:function(xml){
  /*Convert xml to array*/
  var arr=[],atts={},chs=[];
  if(xml.attributes){
    for(var i=0,l=xml.attributes.length;i<l;i++){
      atts[xml.attributes[i].nodeName]=xml.attributes[i].nodeValue;
    }
  }
  if(xml.childNodes){
    for(var i=0,l=xml.childNodes.length;i<l;i++){
      var ch=Z.xml2array(xml.childNodes[i]);
      chs.push(ch[0],ch[1],ch[2]);
    }
  }
  if(typeof(xml.nodeValue)=="string")chs=xml.nodeValue;
  arr.push(xml.nodeName,atts,chs);
  return arr;
}
}
/***********************************/
var loadComponents=Z.loadComponents;
var loadToolbar=Z.loadToolbar;
var originalToolbar=toolbar.slice(0);
function getToolbar(){
  toolbar.reset=Z.resetToolbar;
  toolbar.load=Z.loadToolbar;
  toolbar.add=Z.addToolbarButton;
  return toolbar;
};
getToolbar();
function getXPath(node){
  return getXPath_(node).join("/");
}
function getXPath_(node, path) {
  path = path || [];
  if(node.parentNode) {
    path = getXPath_(node.parentNode, path);
  }

  var ns=node;
  while(ns.previousSibling!=null) ns=ns.previousSibling;
  var index=0,count=0;
  while(ns){
    if(ns.nodeName==node.nodeName) count++;
    if(ns===node)index=count;
    ns=ns.nextSibling;
  }
  path.push(node.nodeName.toLowerCase() + (node.id ? "[@id='"+node.id+"']" : count > 1 ? "["+index+"]" : ''));
  
  return path;
};
/**
 * Loads an application
 */
function structure(panel,src){
  while(panel.firstChild)panel.removeChild(panel.firstChild);
  toolbar.reset();
  draw3(panel,ztree.createFromSrc(src));
  ztree.loadDragAnDropFunctionality();
  var title=panel.parentNode.parentNode.previousSibling.firstChild.firstChild;
  while(title.firstChild)title.removeChild(title.firstChild);
  var name=file.split("\\").join("/").split("/");
  title.appendChild(document.createTextNode(name[name.length-1]));
  getToolbar().load();
  /*Change node*/
  if((initNodes=currentNode.getElementsByTagName("extdocument")) && typeof(initNodes)!="undefined" && initNodes[0]){
    currentNode=initNodes[0];
  } else if((initNodes=currentNode.getElementsByTagName("phtml")) && typeof(initNodes)!="undefined" && initNodes[0]){
    if(initNodes[0].firstChild) currentNode=initNodes[0].firstChild; 
    else currentNode=initNodes[0];
  } else if((initNodes=currentNode.getElementsByTagName("application")) && typeof(initNodes)!="undefined" && initNodes[0]){
    currentNode=initNodes[0];
  }
  /*Load Node Editor*/
  loadNodeEditor();
}
/***/
function appendChildTag(name,attributes,content) {
  var owner=currentNode;
  function create(owner,name,attributes,content){
    var n,i,j;
    if(name=="#text") return owner.appendChild(owner.ownerDocument.createTextNode(content?content:name));
    else if(name=="#comment") return owner.appendChild(owner.ownerDocument.createComment(content?content:name));
    else if(name=="#cdata-section") return owner.appendChild(owner.ownerDocument.createCDATASection(content?content:name));
    else n=owner.ownerDocument.createElement(name);
    owner.appendChild(n);
    for(i=0;i<components.length;i++){
      if(components[i].name==name) {
        if(components[i].attributes) {
          for(j=0;j<components[i].attributes.length;j++) {
            if(typeof(components[i].attributes[j].value)!="undefined") {
              n.setAttribute(components[i].attributes[j].name,components[i].attributes[j].value);
            }
          }
        }
        break;
      }
    }
    if(attributes)
    for(i in attributes)if(typeof(attributes[i])=="string" || typeof(attributes[i])=="number"){
      n.setAttribute(i,attributes[i]);
    }
    if(typeof(content)=="object"){
      for(i=0;i<content.length;i+=3){
        create(n,content[i],content[i+1],content[i+2]);
      }
    } else if(typeof(content)=="string"){
      n.appendChild(n.ownerDocument.createTextNode(content));
    }
    return n;
  }
  var n=create(owner,name,attributes,content);
  ztree.openNode(owner);
  ztree.redrawNode(owner);
  return n;
}
function duplicateCurrentNode(){
  var n,i,j;
  n=currentNode.cloneNode(true);
  currentNode.parentNode.appendChild(n);
  ztree.redrawNode(currentNode.parentNode);
}
function changeCurrentNodeName(nodeName){
  if(!nodeName) return;
  if(nodeName.substr(0,1)=="#") return;
  var n,i,j;
  n=currentNode.ownerDocument.createElement(nodeName);
  if(currentNode.childNodes) {
    for(i=0,l=currentNode.childNodes.length;i<l;i++){
      n.appendChild(currentNode.childNodes[i].cloneNode(true));
    }
  }
  if(currentNode.attributes) {
    for(i=0,l=currentNode.attributes.length;i<l;i++){
      n.setAttribute(currentNode.attributes[i].nodeName, currentNode.attributes[i].nodeValue);
    }
  }
  currentNode.parentNode.insertBefore(n,currentNode);
  currentNode.parentNode.removeChild(currentNode);
  currentNode=n;
  ztree.redrawNode(currentNode.parentNode);
}
function removeCurrentChildTag() {
  removeChildTag(currentNode);
}
function removeChildTag(node) {
  if(node.nodeType==1) {
    while(node.childNodes.length) {
      removeChildTag(node.firstChild);
    }
  }
  ztree.removeNode(node);
  node.parentNode.removeChild(node);
}

var winExplorer;
function openFileExplorer(path) {
  if(!winExplorer)winExplorer=document.createElement("div");
  else while(winExplorer.firstChild) winExplorer.removeChild(winExplorer.firstChild);
  if(path && path.substr(0,1)==":") {
    winExplorer.style.display="none";
    openProject(path.substr(1));
    return;
  }
  winExplorer.style.display="block";
  winExplorer.className="explorer";
  winExplorer.style.position="absolute";
  winExplorer.style.left="16px";
  winExplorer.style.top="64px";
  document.body.appendChild(winExplorer);
  var div=document.createElement("div");
  div.innerHTML=ajax_call("explorer.php",(path?("p="+encodeURIComponent(path)):""),"GET",true);
  var ns=div.firstChild;
  while(ns) {
    winExplorer.appendChild(ns);
    ns=ns.nextSibling;
  }
  document.getElementById("explorer_select").focus();
}
function openPathExplorer(path) {
  if(!winExplorer)winExplorer=document.createElement("div");
  else while(winExplorer.firstChild) winExplorer.removeChild(winExplorer.firstChild);
  winExplorer.style.display="block";
  winExplorer.className="explorer";
  winExplorer.style.position="absolute";
  winExplorer.style.left="16px";
  winExplorer.style.top="64px";
  document.body.appendChild(winExplorer);
  var div=document.createElement("div");
  div.innerHTML=ajax_call("explorer.php",(path?("p="+encodeURIComponent(path)):"")+"&type=p","GET",true);
  var ns=div.firstChild;
  while(ns) {
    winExplorer.appendChild(ns);
    ns=ns.nextSibling;
  }
}
function loadProperties(node){
  return Z.loadProperties(node);
}
function appendProperty() {
  var propName=prompt("Property name:");
  if(propName) {
    currentNode.setAttribute(propName,"");
    loadProperties(currentNode);
  }
}
/**
 * Load library definitions
 */
  function loadLibrary(src){
    var p=((typeof(file)!="undefined")&&(file))?file:"";
    var dom=ajax_call("fileIO.php","l="+encodeURIComponent(src)+"&p="+encodeURIComponent(p)+"&tsmp="+new Date().getTime(),"GET");
    if(dom.firstChild.nodeName=="xml") _loadLibrary(dom.firstChild.nextSibling);
    else _loadLibrary(dom.firstChild);
  }
  function _loadLibrary(n){
    if((n.nodeName=="component")||(n.nodeName=="bcomponent")||(n.nodeName=="def")){
      if(typeof(componentsIndex[n.getAttribute("name")])=="undefined"){
        components.push({
          "name":n.getAttribute("name"),
          "attributes":structure_node_properties(n)
        });
        loadComponents();
      } else {
        if(n.getAttribute("name")=="layout"){
          a=6;
        }
        var atts=structure_node_properties(n,[],n.getAttribute("name"));
        if(componentsIndex[n.getAttribute("name")].attributes){
          for(var i=0,l=atts.length;i<l;i++){
            var exi=false;
            for(var j=0,k=componentsIndex[n.getAttribute("name")].attributes.length;j<k;j++){
              exi=exi || componentsIndex[n.getAttribute("name")].attributes[j].name==atts[i].name;
            }
            if(!exi)componentsIndex[n.getAttribute("name")].attributes.push(atts[i]);
          }
        } else {
          componentsIndex[n.getAttribute("name")].attributes=atts;
        }
      }
      if(n.hasChildNodes()) for(var i=0;i<n.childNodes.length;i++) if(n.childNodes[i].nodeName=="#comment") {loadPlugin(n.childNodes[i]);}
    } else if(n.nodeName=="include"){
      loadLibrary(n.getAttribute("src"));
    } else if(n.nodeName=="#comment"){
      loadPlugin(n);
    } else {
      if(n.hasChildNodes()) for(var i=0;i<n.childNodes.length;i++) _loadLibrary(n.childNodes[i]);
    }
  }
  function structure_node_component_add(n){
    if(typeof(componentsIndex[n.getAttribute("name")])=="undefined"){
      components.push({
        "name":n.getAttribute("name"),
        "attributes":structure_node_properties(n)
      });
      loadComponents();
    } else {
      componentsIndex[n.getAttribute("name")].attributes=structure_node_properties(n,[],n.getAttribute("name"));
    }
  }
  function structure_node_properties(n,res,base) {
    var i;
    if(!res) res=[];
    /*if(n.nodeName=="component") res=structure_node_component_properties(n,res,base);
    if(n.nodeName=="bcomponent") res=structure_node_component_properties(n,res,base);
    if(n.nodeName=="def") res=structure_node_component_properties(n,res,base);*/
    res=structure_node_component_properties(n,res,base);
    return res;
  }
  function structure_node_component_properties(n,res,base) {
    var i;
    if(!res) res=[];
    if(n.attributes) for(i=0;i<n.attributes.length;i++) res=structure_node_component_get_properties(n.attributes[i].nodeValue,res,base);
    if(n.hasChildNodes()) for(i=0;i<n.childNodes.length;i++) res=structure_node_component_properties(n.childNodes[i],res,base);
    if(n.nodeValue) res=structure_node_component_get_properties(n.nodeValue,res,base);
    return res;
  }
  function structure_node_bcomponent_properties(n,res,base) {
    var i;
    if(!res) res=[];
    if(n.attributes) for(i=0;i<n.attributes.length;i++) res=structure_node_component_get_properties(n.attributes[i].nodeValue,res,base);
    if(n.hasChildNodes()) 
      for(i=0;i<n.childNodes.length;i++) 
        if((n.childNodes[i].nodeName=="after")||(n.childNodes[i].nodeName=="before"))
          res=structure_node_bcomponent_properties(n.childNodes[i],res,base);
        else
          res=structure_node_component_properties(n.childNodes[i],res,base);
    if(n.nodeValue) res=structure_node_component_get_properties(n.nodeValue,res,base);
    return res;
  }
  function structure_node_component_get_properties(val,res,base){
    var m=val.match(/[#@\$]\{[^\}]+\}/g);
    if(m){
      for(var i=0;i<m.length;i++){
        m[i]=m[i].replace(/"([^"\\]|\\"|\\)*"/g,"");
        m[i]=m[i].replace(/'([^'\\]|\\'|\\)*'/g,'');
        var mm=m[i].match(/\$[a-zA-Z][\w\d_]*(\/\*(.*?)\*\/)?/gm);
        if(mm) for(var j=0;j<mm.length;j++)if(mm[j]!="$this"){
          var mmm=mm[j].match(/\$([a-zA-Z][\w\d_]*)(?:\/\*(.*?)\*\/)?/m);
          var domain=(base?base.split(".").join("_"):"")+"_"+mmm[1];
          var defaultValue="";
          if(mmm[2]){
/*            domain="window.domain_"+(base?base.split(".").join("_"):"")+"_"+mmm[1];
            eval(""+domain+"="+JSON.stringify(mmm[2].split('|')));
            eval("defaultValue="+domain+"[0]");*/
            var domainName="domain_"+(base?base.split(".").join("_"):"")+"_"+mmm[1];
            domain="window.domain_"+(base?base.split(".").join("_"):"")+"_"+mmm[1];
            window[domainName]=mmm[2].split('|');
            defaultValue=window[domainName][0];
            var att=null;
            for(var q=0,w=res.length;q<w;q++) if(res[q].name==mmm[1]) {att=res[q];break;}
            if(!att)res.push({"name":mmm[1],"value":defaultValue,"domain":domain});
            else {att.value=defaultValue;att.domain=domain}
          } else {
            var att=null;
            for(var q=0,w=res.length;q<w;q++) if(res[q].name==mmm[1]) {att=res[q];break;}
            if(!att)res.push({"name":mmm[1],"value":defaultValue,"domain":domain});
          }
        }
      }
    }
    return res;
  }
  function structure_node_get_code_properties(val,res,base){
    var m=val.match(/\$values\s*\[\s*["'][\w\d_]+["']\s*\]/g);
    if(m){
      for(var i=0;i<m.length;i++){
        var mm=m[i].match(/\$values\s*\[\s*["']([\w\d_]+)["']\s*\]/);
        var domain="string";
        res.push({"name":mmm[1],"value":"","domain":domain});
      }
    }
    return res;
  }
  function loadPlugin(n){
    if(n.nodeValue.substr(0,14)=="#editor.plugin") {
     try {
        var node=$this=n;
        //console.log(n.nodeValue.substr(14));
        eval(n.nodeValue.substr(14));
      } catch(e) {
        //alert("#editor.plugin("+n.parentNode.getAttribute("name")+"): "+e.message);
        Z.console("=============");
        Z.console(e.message);
        Z.console(n.nodeValue.substr(14));
        //if has firebug
        if(console && console.debug) console.debug(e);
        Z.console("-------------");
      }
    }
  }