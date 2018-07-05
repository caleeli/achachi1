<?php
ob_start();
  require_once("../library/phpQuery-onefile.php");
  if(isset($_POST["fileContent"]) && !empty($_POST["fileContent"]) && $_POST["fileContent"]!="null") $code = $_POST["fileContent"];
  else $code = file_get_contents("venew.php");
  $dom = phpQuery::newDocumentPHP('<!DOCTYPE root [
<!ENTITY nbsp "&#160;">
]>'.$code, "application/xhtml+xml;charset=utf-8");
  $head = $dom->find("head");
  $jsloader = $head->find("#jsloader");
  $jsloader->empty();
  $jsloader->append($_POST["jsloader"]);
  $body = $dom->find("body");
  $body->empty();
  $body->append($_POST["body"]);

  $code = $dom->php();
  $code = preg_replace('/^<\?xml[^>]+>\s*/', '', $code);
  $code = strstr($code, '<html');
//  file_put_contents($_POST["fileName"], $code);
$err=ob_get_contents();
ob_end_clean();
if($err) {
  print "alert(".json_encode($err).");";
} else {
  print "parent.zjqueryui.save(".json_encode($code).");";
}
