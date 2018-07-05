toolbar=[
  {"name":" ",tab:"Project"},
  [
    {"name":"new extjs2 zend project","action":function(){newProject("templates/ext2ZendApplication.xml");},"image":"images/32/new.gif"},
    {"name":"new extjs zend project","action":function(){newProject("templates/extZendApplication.xml");},"image":"images/32/new.gif"},
    {"name":"new zend project","action":function(){newProject("templates/zendApplication.xml");},"image":"images/32/new.gif"},
    {"name":"new working directory","action":function(){newProject("templates/workingDirectory.xml");},"image":"images/32/new.gif"},
    {"name":"new empty project","action":function(){newProject("templates/empty.xml");},"image":"images/32/new.gif"}
  ],
  [
    {"name":"open","action":function(){openFileExplorer();},"image":"images/32/open.gif"},
    {"name":"import","action":function(){openPathExplorer();},"image":"images/32/open.gif"}
  ],
  {"name":"save","action":function(){saveProject();},"image":"images/32/save.gif"},
  {"name":" ",tab:"Run"},
  {"name":"go","action":function(){run()},"image":"images/32/run.gif"},
  {"name":"compile","action":function(){compile()},"image":"images/32/compile.gif"},
  {"name":"deploy","action":function(){deploy()},"image":"images/32/deployment.gif"},
  {"name":" ",tab:"Edit"},
  {"name":"delete","action":function(){removeCurrentChildTag()},"image":"images/32/delete.gif"},
  {"name":"duplicate","action":function(){duplicateCurrentNode()},"image":"images/32/duplicate.gif"},
  {"name":"custom","action":function(){
      if(typeof(Z)=="object" && typeof(Z.popupList)=="function"){
        var list=[];
        for(var i in componentsIndex) list.push({label:i,value:i});
        Z.popupList(list,"Custom Tag","Tag name:",function(tag){appendChildTag(tag);});
      } else {
        var tag=prompt("Tag name:");if(tag) appendChildTag(tag);
      }
    },"image":"images/32/customnode.gif"},
  {"name":"change","action":function(){changeCurrentNodeName(prompt("Node Name",currentNode.nodeName))},"image":"images/32/changenode.gif","help":"Change node's tag name."},
  {"name":"copy","action":function(){if(Z && Z.copyToClipboard)Z.copyToClipboard();},"image":"images/32/duplicate.gif","help":"Copy node to clipboard."},
  {"name":"paste","action":function(){if(Z && Z.pasteFromClipboard)Z.pasteFromClipboard();},"image":"images/32/duplicate.gif","help":"Paste node from clipboard."},
  {"name":" ",tab:"Basic Nodes"},
  [
    {"name":"#text","action":function(){appendChildTag("#text");},"image":"images/32/_text.gif"},
    {"name":"#comment","action":function(){appendChildTag("#comment",{},"#editor.plugin\n");},"image":"images/32/_text.gif"}
  ],
  [
    {"name":"file","action":function(){appendChildTag("file");},"image":"images/32/view.gif"},
    {"name":"jqueryui","action":function(){appendChildTag("jqueryui");},"image":"images/32/view.gif"},
    {"name":"path","action":function(){appendChildTag("path");},"image":"images/32/open.gif"},
    {"name":"package","action":function(){appendChildTag("package");},"image":"images/32/component.gif"},
    {"name":"resource","action":function(){appendChildTag("resource");},"image":"images/32/default.gif"}
  ],
  [
    {"name":"component","action":function(){appendChildTag("component");},"image":"images/32/component.gif","help":"Packages a group of components to make it reusable. Use @{} ${} #{} tags to define parameters."},
    {"name":"include","action":function(){appendChildTag("include");},"image":"images/32/library.png"},
    {"name":"foreach","action":function(){appendChildTag("foreach");},"image":"images/32/default.gif","help":"Create a FOREACH tag"},
    {"name":"if","action":function(){appendChildTag("if");},"image":"images/32/default.gif","help":"Create an IF tag"},
    {"name":"def","action":function(){appendChildTag("def");},"image":"images/32/default.gif","help":"Create a basic tag definition"},
    {"name":"function","action":function(){appendChildTag("function");},"image":"images/32/function.jpg","help":"Add a function to a tag definition."},
    {"name":"clone","action":function(){appendChildTag("clone");},"image":"images/32/clone.png"}
  ]
];
var file;
function newProject(docBase) {
  if(document.location.protocol=="file:") {
    //LOCAL
    file="";
    structure(document.getElementById("structure"),docBase);
  } else {
    //PHPWEB APPLICATION
    file="";
    structure(document.getElementById("structure"),docBase);
  }
}
function openProject(filePath) {
  if(document.location.protocol=="file:") {
    //LOCAL
    file=window.selectedFile.split("\\").join("/");//
    //file=prompt("Abrir Archivo","../samples/test.xml");
    if(file) structure(document.getElementById("structure"),file);
  } else {
    //PHPWEB APPLICATION
    file=filePath;//prompt("Abrir Archivo","../samples/test.xml");
    if(file)structure(document.getElementById("structure"),"fileIO.php?r="+encodeURIComponent(file));
  }
}
function importProject(path) {
  file="";
  if(path)structure(document.getElementById("structure"),"fileIO.php?im="+encodeURIComponent(path));
}
function saveProject() {
  updateChanges();
  /*Save file*/
  if(file=="") file=prompt("Abrir Archivo",dirBase+"newProject.xml");
  if(!file) return;
  if(document.location.protocol=="file:") {
    //LOCAL
    SaveToFile(file,getXml(currentNode.ownerDocument));
  } else {
    ajax_call("fileIO.php","f="+encodeURIComponent(file)+"&c="+encodeURIComponent(getXml(currentNode.ownerDocument)))
  }
}
function run() {
  saveProject();
  var pfile;
  if(document.location.protocol=="file:") {
    //LOCAL
    pfile=file;
  } else {
    if(!((file.substr(0,1)=="/")||(file.match(/\w\:/)))) pfile=dirBase+file;
    else pfile=file;
  }
  window.open("/achachi/achachi.php?filename="+encodeURIComponent(pfile)+"&run=1");
}
function compile() {
  saveProject();
  var pfile;
  if(document.location.protocol=="file:") {
    //LOCAL
    pfile=file;
  } else {
    if(!((file.substr(0,1)=="/")||(file.match(/\w\:/)))) pfile=dirBase+file;
    else pfile=file;
  }
  var res=ajax_call("/achachi/achachi.php?filename="+encodeURIComponent(pfile),null,"POST",true);
  if(typeof(Z)=="object" && Z.console){
    Z.consoleClear();
    Z.consoleHTML(res);
  }
  res="";
}
function deploy() {
  if(!(currentNode && currentNode.nodeName=="application" && currentNode.getAttribute("name")!="")) {
    alert("You must select a Application node with name.");
    return;
  }
  window.server=prompt("Server name/ip",window.server);
  if(!window.server) return;
  saveProject();
  var pfile;
  if(document.location.protocol=="file:") {
    //LOCAL
    pfile=file;
  } else {
    if(!((file.substr(0,1)=="/")||(file.match(/\w\:/)))) pfile=dirBase+file;
    else pfile=file;
  }
  ajax_call("/achachi/achachi.php?filename="+encodeURIComponent(pfile));
  var res;
  res=ajax_call("deploy.php?server="+encodeURIComponent(window.server)
    +"&source="+encodeURIComponent(currentNode.getAttribute("name"))
    +"&destination="+encodeURIComponent(currentNode.getAttribute("name"))
  ,null,"POST",true);
  if(res==="0") {
    res=ajax_call("deploy.php?server="+encodeURIComponent(window.server)
      +"&source="+encodeURIComponent(currentNode.getAttribute("name"))
      +"&destination="+encodeURIComponent(currentNode.getAttribute("name"))
      +"&password="+encodeURIComponent(prompt("Password",""))
    ,null,"POST",true);
  }
  alert(res);
}