<?php
if($code=@$_POST["c"]) {
  ini_set("display_errors","on");
  require_once("extjslib2.class.php");
  global $definedClasses,$generationType,$generatedPath;
  chdir("..");
  global $core;
  global $PARAMS;
  $PARAMS=array();
  
  require_once("common/formatcode.php");
  require_once("common/util.php");
  loadClasses("core");
  require_once("config.php");
  strip_mq();
  $dom = new DomDocument();
  if(substr($code,0,13)=="<ext.fragment"){
    $code=str_replace("ext.document","ext.fragment",$code);
    $dom->loadXML('<root><include src="$library/extjslib2.xml"/>'.$code.'</root>');
    $e=$dom->createElement("ext");
    $e->setAttribute("class","Ext.Viewport");
    $frag=$dom->getElementsByTagName("ext.fragment")->item(0);
    foreach($frag->childNodes as $ch){
      $e->appendChild($ch);
    }
    $frag->appendChild($e);
  } else {
    $code=str_replace("ext.document","ext.fragment",$code);
    $dom->loadXML('<root><include src="$library/extjslib2.xml"/>'.$code.'</root>');
  }

  ob_start();
  global $core;
  $core=new node($dom->firstChild);
  $ext=$core->run();
  ob_end_clean();
  die($core->content($ext));
}
?><html>
<head>
  <link rel="stylesheet" type="text/css" href="/ext/resources/css/ext-all.css" />
  <script type="text/javascript" src="/ext/bootstrap.js"></script>
  <script>
    Ext.onReady(function()
    {
      Ext.Loader.setConfig({enabled:true});
      ;
    });
  </script>
</head>
<body><script>
var readyForAction=false;
Ext.onReady(function(){
  readyForAction=true;
  openCurrentNode();
});
function findNode(node,nodeName){
  while(node && node.nodeName!=nodeName) {
    node=node.parentNode;
  }
  if(!node) return null;
  return node;
}
function openCurrentNode(){
  if(!readyForAction) return setTimeout(openCurrentNode,100);
  var node = findNode(window.parent.currentNode,"ext.document");
  if(!node)node = findNode(window.parent.currentNode,"ext.fragment");
  if(node){
    Ext.Ajax.request({
      url:'?t='+new Date().getTime(),
      method:'POST',
      params:{c:window.parent.getXml(node)},
      success:function(transport){ try{eval(transport.responseText);} catch(e){console.debug(e)}}
    });
  }
}
function extjs_refresh(){
  window.location.reload(true);
}
</script><a style=""><style>
.floattoolbar{
  position:absolute;
  border:1px outset white;
  display:inline-block;
  background-color:transparent;
  right:4px;
  top:8px;
  z-index:20000;
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
<div id="floattoolbar" class="floattoolbar"><div class="floattitle"></div><div class="toolbarbg"></div><span style="display:inline-block;position:relative"></span><a class="floatbutton" href="javascript:extjs_refresh()"><img src="images/16/refresh.gif" /></a></div></body>
</html>