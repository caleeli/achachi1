<?php
session_start();
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>V A</title>
  <link type="text/css" href="/jquery/css/face/jquery-ui.custom.css" rel="stylesheet" />	
  <link type="text/css" href="visual.css" rel="stylesheet" />	
  <script type="text/javascript" src="/jquery/js/jquery.min.js"></script>
  <script type="text/javascript" src="/jquery/js/jquery-ui.custom.min.js"></script>
  <script type="text/javascript" src="visual.js"></script>
  <script type="text/javascript" src="ve.js"></script>
<?php
  require_once("../library/phpQuery-onefile.php");
  if(isset($_POST["html"])){
    $_SESSION["jqueryui"] = $_POST["html"];
    exit;
  }
  if(!isset($_SESSION["jqueryui"]) || !$_SESSION["jqueryui"]){
    $_SESSION["jqueryui"] = file_get_contents("venew.php");
  }
  $dom = phpQuery::newDocumentPHP('<!DOCTYPE root [
<!ENTITY nbsp "&#160;">
]>'.$_SESSION["jqueryui"], "application/xhtml+xml;charset=utf-8");
  $head = $dom->find("head");
  $body = $dom->find("body");
  $hScripts = $head->find("script");
  foreach($hScripts as $scr){
    $srcPath = pq($scr)->attr("src");
    $srcId = pq($scr)->attr("id");
    $srcName = basename($srcPath);
    if(preg_match('/^jquery.*\.min\.js$/', $srcName)){}
    elseif("visual.js"==$srcName){}
    elseif("jsloader"==$srcId){}
    elseif($srcPath){
      print "<script type='text/javascript' src='{$srcPath}'></script>\n";
    } else {
      print "<script>".pq($scr)->html()."</script>\n";
    }
  }
  $hScripts = $head->find("link");
  foreach($hScripts as $scr){
    $srcPath = pq($scr)->attr("href");
    $srcName = basename($srcPath);
    if(preg_match('/^jquery\-ui.+\.css$/', $srcName)){}
    elseif("visual.css"==$srcName){}
    elseif($srcPath){
      print "<link type='text/css' href='{$srcPath}' rel='stylesheet' />\n";
    } else {
    }
  }
  $hStyle = $head->find("style");
  foreach($hStyle as $s){
    print "<style>".pq($s)->html()."</style>\n";
  }
?>
  <script>
      ve.loadJsBase=function(){
<?php
  $js = $head->find("#jsloader");
  $code = $js->html();//$js->elements[0]->nodeValue;
  /*if(substr($code,0,4)=="<!--") $code=substr($code,4,-3);*/
  print "ve.fileName=".json_encode(@$fileName).";\n";
  print $code;
?>
        jsBase = <?= json_encode($code) ?>;
      };
  </script>
</head>
<body><div id="workspace" style="position:relative;overflow:scroll;width:100%;height:90%;"><?php 
print $body->html();
?></div><div id="toolbar"></div>
<div id="codeEditor" style="display:none;padding:0px;" title="Edit"><div id="codeEditorTabs">
  <ul>
  <li><a href="#editCode">HTML</a></li>
  <li><a href="#editCodeJs">Javascript</a> <select class="selectJsCode" style="margin-right:4px;margin-top:4px;"><option value=""></option><option value="load">load...</option></select></li>
  </ul>
  <div id="editCode" style="padding:0px;"><textarea style="width:100%;height:80%"></textarea></div>
  <div id="editCodeJs" style="padding:0px;"><textarea style="width:100%;height:80%"></textarea></div>
</div></div>
<div id="codeJsBaseEditor" style="display:none;padding:0px;" title="Edit JavaScript Base Code">
  <div id="editCodeJsBase" style="padding:0px;"><textarea style="width:100%;height:80%"></textarea></div>
</div>
</body>
</html>