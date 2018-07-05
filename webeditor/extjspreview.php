<html>
<head>
  <link rel="stylesheet" type="text/css" href="/ext/resources/css/ext-all.css">
  <script type="text/javascript" src="/ext/adapter/ext/ext-base.js"></script>
  <script type="text/javascript" src="/ext/ext-all.js"></script>
  <script>
    Ext.QuickTips.init();
    function extLoad(sClass,base,_construct)
    {
      var obj;
      eval("obj=new "+sClass+"(base);");
      if(_construct)eval(_construct);
      return obj;
    }
    function main()
    {
      try{
<?php
ini_set("display_errors","on");
global $definedClasses,$generationType,$generatedPath;
chdir("..");

require_once("common/formatcode.php");
require_once("common/util.php");
loadClasses("core");
require_once("config.php");
strip_mq();

/**
 * Loads default sample
 */
$file = isset($_GET["filename"]) ? $_GET["filename"] : "samples/test.xml";
define("PROJECT_PATH", realpath(dirname($file)) );

$sys = new DomDocument();
$sys->loadXml("<root>".$_GET["c"]."</root>");
//print('alert("'.$sys->firstChild->firstChild->nodeName.'")');

if(($sys->firstChild->firstChild->firstChild->nodeName!="layout")||($sys->firstChild->firstChild->firstChild->nodeName!="window")){
  $cnt0=$sys->firstChild->firstChild->appendChild($sys->createElement("layout"));
  $cnt0->setAttribute("layout","border");
  $cnt=$cnt0->appendChild($sys->createElement("extjs"));
  $cnt->setAttribute("class","Ext.Panel");
  $cnt->setAttribute("region","center");
  while($sys->firstChild->firstChild->firstChild!==$cnt0){
    $cnt->appendChild($sys->firstChild->firstChild->firstChild);
  }
}
$lib=$sys->firstChild->insertBefore($sys->createElement("include"),$sys->firstChild->firstChild);
$lib->setAttribute("src",'$library/extjs.xml');

ob_start();
global $core;
$core=new node($sys->firstChild);
$ext=$core->run();
ob_end_clean();


$code=$ext[0][1];
function replaceBlocks($ma){
  return str_repeat("_", strlen($ma[0]));
}
$code0=preg_replace_callback(
  '/(?:\\/\\*[\\w\\W]*?\\*\\/)|(?:\\/\\/.*)|(?:"(?:[^"\\n\\\\]+|\\\\"|\\\\.)*?")|(?:\'(?:[^\'\\n\\\\]+|\\\\\'|\\\\.)*?\')/', 
  "replaceBlocks", $code);
preg_match_all('/<\?[\w\W]*?\?>/', $code0, $ma , PREG_PATTERN_ORDER  | PREG_OFFSET_CAPTURE);
for($r=count($ma[0])-1;$r>=0;$r--){
  $code=substr($code,0,$ma[0][$r][1]).str_repeat(" ",strlen($ma[0][$r][0])).substr($code,$ma[0][$r][1]+strlen($ma[0][$r][0]));
}
print($code);
//print($ext[0][1]);

?>;
      }catch(e){
        e=e;
      }
    }
    Ext.onReady(function(){ main(); });
  </script>
</head>
<body>
</body>
</html>
