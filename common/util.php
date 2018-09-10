<?php
/**
 *
 *  Achachi XML Builder - Framework and applications builder tool
 *  Copyright (C) 2010-2011, Llankay Achachi
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

require_once("json.php");
ini_set("display_errors","on");
/**
 * Loads all classes of a folder
 */
function loadClasses($path) {
  global $definedClasses;
  foreach(glob("$path/*.php") as $f) {
    $_class = basename($f,".class.php");
    $_name = substr( preg_replace('/\d+$/','',$_class), 5);
    if($_name) {
      if($_name=="_text") $_name="#text";
      if($_name=="_comment") $_name="#comment";
      if($_name=="_cdata") $_name="#cdata";
      if($_name=="_cdata_section") $_name="#cdata-section";
      $definedClasses[$_name]=$_class;
    }
    require_once($f);
  }
}
/**
 * FUNCIONES AUXILIARES
 */
function createDir($path,$base="./")
{
  $path=str_replace("\\","/",$path);
  if(substr($path,0,1)=="/") {$base="/";$path=substr($path,1);} //Linux root
  if(substr($path,1,1)==":") {$base="/";$path=substr($path,3);} //Windows drive
  $p=explode("/",$path);
  $dir = $base.$p[0];
  //var_dump($path,$base,$dir);
  if(!file_exists($dir)) { mkdir($dir, 0777); chmod($dir,0777); }
  unset($p[0]);
  if(count($p)>0) createDir(implode("/",$p),$dir."/");
}
function copyDir($from,$to)
{
  //print("COPY: $from --> $to \n");
  $list = glob($from."/*");
  foreach(glob($from."/.*") as $f) if(!((basename($f)=="..")||(substr(basename($f),0,1)=="."))) $list[]=$f;
  foreach($list as $f)
  {
//    var_dump($f);die;
    $f1=substr($f,strlen($from."/"));
    if(is_file($f))
    {
      $d = "{$to}/{$f1}";
      file_put_contents($d, file_get_contents($f));
      @chmod($d,0777);
    }
    else
    {
      createDir($f1,$to."/");
      copyDir($f,$to."/".basename($f));
    }
  }
}
function createFile($f,$c)
{
  $d=dirname($f);
  if($d!="")createDir($d);
  file_put_contents($f, $c);
  @chmod($f,0777);
}
function UpperCamelCase($name)
{
  return preg_replace_callback('/[^_]+/',create_function('$match','return strtoupper(substr($match[0],0,1)).strtolower(substr($match[0],1));'),$name);
}
function dom2array($node)
{
  if($node->nodeName=="#text") return;
  if($node->nodeName=="#comment") return;
  $arr=array();
  foreach($node->attributes as $a)
  {
    $arr[$a->nodeName]=$a->nodeValue;
  }
  $arr['childNodes']=nodes2array($node->childNodes);
  return $arr;
}
function nodes2array($childNodes,$type='')
{
  $arr=array();
  foreach($childNodes as $ch)if(($type=='')||($type==$ch->nodeName))
  {
    $arr[]=dom2array($ch);
  }
  return $arr;
}
function importNode($ch,$doc)
{
  if($ch->nodeName=="#text") $ch2=$doc->createTextNode($ch->nodeValue);
  elseif($ch->nodeName=="#cdata") $ch2=$doc->createCDATASection($ch->nodeValue);
  elseif($ch->nodeName=="#cdata-section") $ch2=$doc->createCDATASection($ch->nodeValue);
  elseif($ch->nodeName=="#comment") $ch2=$doc->createComment($ch->nodeValue);
  else {
    $ch2=$doc->createElement($ch->nodeName);
    foreach($ch->attributes as $a)
    {
      $ch2->setAttribute($a->nodeName,$a->nodeValue);
    }
    foreach($ch->childNodes as $c)
    {
      $ch2->appendChild(importNode($c,$doc));
    }
  }
  return $ch2;
}

function previousCall() {
  $d=debug_backtrace();
  //unset($d[0]);
  $res="<select>";
  foreach($d as $k=>$a) {
    $n=isset($d[$k+1])?$d[$k+1]["function"]:"";
    $res.="<option>".$a["file"].".$n"."[".$a["line"]."]".$a["function"]."</option>";
  }
  $res.="</select>";
  return $res;
}

/** ERROR HANDLING **/

// we will do our own error handling

// user defined error handling function
function userErrorHandler($errno, $errmsg, $filename, $linenum, $vars) 
{
  if(error_reporting()==0) return null;
  print("<p style='white-space: pre; font-family: monospace;display: block;'>");
  print("ERROR $errno: $errmsg\n");
  userErrorTrace($filename,$linenum);
  print("<div style='height:1em;overflow:hidden;border:3px double black' ondblclick='this.style.height=this.style.height==\"1em\"?\"\":\"1em\"'>Trace:\n");
  $trace=debug_backtrace();unset($trace[0]);
  //var_dump($trace);
  $ttl=3;
  foreach($trace as $t) {
    userErrorTrace(isset($t["file"])?$t["file"]:'',isset($t["line"])?$t["line"]:0,(isset($t["object"])?(get_class($t["object"]).$t["type"]):(isset($t['class'])?($t['class'].$t["type"]):'')).$t["function"],isset($t["args"])?$t["args"]:array());
	$ttl--;
	if($ttl<1) break;
  }
  print("</div>");
  print("</p>");
  return null;
}
function printTrace(){
  print("<pre>");
  $trace=debug_backtrace();unset($trace[0]);
  $ttl=3;
  foreach($trace as $t) {
    userErrorTrace(isset($t["file"])?$t["file"]:'',isset($t["line"])?$t["line"]:0,(isset($t["object"])?(get_class($t["object"]).$t["type"]):(isset($t['class'])?($t['class'].$t["type"]):'')).$t["function"],isset($t["args"])?$t["args"]:array());
	$ttl--;
	if($ttl<1) break;
  }
  print("</pre>");
}
function userErrorTrace($filename,$linenum,$function=null,$vars=null) {
  $varsTypes=array();
  if(isset($vars)) foreach($vars as $v) $varsTypes[]=gettype($v);
  if(file_exists($filename)) @$fc=file($filename);
  else $fc=array($linenum-1=>"$function(".implode(",",$varsTypes).")\n");
  //href='file:///".htmlentities(str_replace("\\","/",$filename))."'
  print("<div style='background-color:lightyellow;'><a >$filename </a></div>");
  print("<b>$function</b>(".printVariables($vars).")\n");
  if(isset($fc[$linenum-2]))print("<span style='background-color:lightgrey;'>".($linenum-1).": </span><span style='background-color:white;'>".htmlentities($fc[$linenum-2])."</span>");
  if(isset($fc[$linenum-1]))print("<span style='background-color:lightgrey;'>$linenum: </span><span style='background-color:LightSkyBlue;'>".htmlentities($fc[$linenum-1])."</span>");
  if(isset($fc[$linenum]))print("<span style='background-color:lightgrey;'>".($linenum+1).": </span><span style='background-color:white;'>".htmlentities($fc[$linenum])."</span>");
}
function printVariables($vars){
  $res="";
  if(isset($vars))
  foreach($vars as $v) {
    if($res!="")$res.=",";
    $res.=("<select>");
      $val=explode("\n",print_r($v,true));
      foreach($val as $val_) $res.=("<option>".substr(htmlentities($val_),0,100)."</option>");
    $res.=("</select>");
  }
  return $res;
}
$old_error_handler = set_error_handler("userErrorHandler");

register_shutdown_function('error_alert');

function error_alert()
{
  if(function_exists("error_get_last")) {
	  $e=error_get_last();
	  if($e) userErrorHandler($e["type"],$e["message"],$e["file"],$e["line"],null);
	}
} 

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
function deleteDir($dirPath)
{
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}
