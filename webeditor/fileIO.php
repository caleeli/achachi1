<?php
strip_mq();
/* Open a XML FILE */
if(isset($_GET["r"])) {
  header("Content-Type: application/xml; charset=utf-8");
  readfile($_GET["r"]);
  die();
}
/* Import a working Directory */
if(isset($_GET["im"])){
ini_set("display_errors","on");
  header("Content-Type: application/xml; charset=utf-8");
  $imPath = $_GET["im"];
  $dom = new DOMDocument('1.0','utf-8');
  $dom->loadXML('<?xml version="1.0" encoding="UTF-8"?><root></root>');
  $root = $dom->firstChild;
  $node = $root->appendChild($dom->createElement("path"));
  $node->setAttribute("path",$imPath);
  chdir($imPath);
  function importPath($p,$node){
    $fs = glob("{$p}*");
    //if() prevents .* files and folders like .svn folders
    foreach($fs as $f) if(basename($f)!="") {
      if(is_file($f)) {
        $file = $node->appendChild($node->ownerDocument->createElement("file"));
        $file->setAttribute("name",$f);
        $file->appendChild($node->ownerDocument->createTextNode(file_get_contents($f)));
      }
      if(is_dir($f)){
        importPath("{$f}/",$node);
      }
    }
  }
  importPath("",$node);
  print($dom->saveXml());
  die();
}
if(isset($_GET["i"])) {
//  header("Content-Type: application/xml");
  readfile($_GET["i"]);
  die();
}
if(isset($_GET["l"])) {
//Load a XML library
  header("Content-Type: application/xml");
  //project path
  if(isset($_GET["p"]))@chdir(dirname($_GET["p"]));
  //library file
  $l=str_replace('$library',realpath(dirname(__FILE__)).'/../library',$_GET["l"]);
  readfile($l);
  die();
}
if(!isset($_POST["f"])) die(dirname(__FILE__) .  DIRECTORY_SEPARATOR );
$f=fopen($_POST["f"],"w");
fwrite($f,$_POST["c"]);
fclose($f);


/**
 * Strip magic quotes from request data.
 */
function strip_mq(){
  if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
      // Create lamba style unescaping function (for portability)
      $quotes_sybase = strtolower(ini_get('magic_quotes_sybase'));
      $unescape_function = (empty($quotes_sybase) || $quotes_sybase === 'off') ? 'stripslashes($value)' : 'str_replace("\'\'","\'",$value)';
      $stripslashes_deep = create_function('&$value, $fn', '
          if (is_string($value)) {
              $value = ' . $unescape_function . ';
          } else if (is_array($value)) {
              foreach ($value as &$v) $fn($v, $fn);
          }
      ');
     
      // Unescape data
      $stripslashes_deep($_POST, $stripslashes_deep);
      $stripslashes_deep($_GET, $stripslashes_deep);
      $stripslashes_deep($_COOKIE, $stripslashes_deep);
      $stripslashes_deep($_REQUEST, $stripslashes_deep);
  }
}