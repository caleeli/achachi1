<?php
function cls(){
  echo(chr(27)."[2J");
}
//Converting to old QBasic colors :')
function convertB($color){
  $colorB=$color & 1;
  $colorR=$color & 4;
  $color=$color-$colorB-$colorR+($colorB?4:0)+($colorR?1:0);
  return $color;
}
function setColor($text,$background){
  $text=convertB($text);
  $background=convertB($background);
  $fore=30+($text%8);
  $attr=(($text>7)?1:0)+($background>7?4:0);
  $back=40+($background%8);
  echo(chr(27)."[0;30;40m");
  if($attr==5){
    echo(chr(27)."[".(($text>7)?1:0).";{$fore}m");
    echo(chr(27)."[".(($background>7)?4:0).";{$back}m");
  } else {
    echo(chr(27)."[$attr;$fore;{$back}m");
  }
}
function locate($row,$col){
  echo(chr(27)."[$row;{$col}H");
}
function box_($r1,$c1,$r2,$c2,$char=" "){
  for($r=$r1;$r<=$r2;$r++){
    locate($r,$c1);
    print(str_repeat($char, $c2-$c1+1));
  }
}
//Don't use spaces inside ~(fore,back) tags.
function printbox_($r1,$c1,$rows,$cols,$text){
  $ws=explode(" ",$text);
  $c=$c1;$r=$r1;
  foreach($ws as $w){
    $w.=" ";
    if($c+_lenf($w)>($c1+$cols)){$c=$c1;$r++;}
    locate($r,$c);
    _printf($w);
    $c+=_lenf($w);
  }
}

function inputln()
{
  return trim(fgets(STDIN));
}
function getKey(){
  return fgetc(STDIN);
}
function input($arr){
  setColor($arr[0],$arr[1]);
  locate($arr[2],$arr[3]);
  print($arr[4]);
  setColor(7,0);
  print(str_repeat(" ", $arr[5]));
  setColor($arr[0],$arr[1]);
  if($arr[6]!="")print("(".$arr[6].")");
  locate($arr[2],$arr[3]+strlen($arr[4]));
  setColor(7,0);
  $res=inputln();
  if(!$res)$res=$arr[6];
  return $res;
}

function _printf($txt){
  $ff=explode("~",$txt);
  foreach($ff as $i=>$f){
    if($i>0){
      preg_match('/^(?:\(\s*(\d+)\s*,\s*(\d+)\s*\))?([^~]*)/',$f,$m);
      setColor($m[1],$m[2]);
      print($m[3]);
    } else {
      print($f);
    }
  }
}
function _lenf($txt){
  $len=0;
  $ff=explode("~",$txt);
  foreach($ff as $i=>$f){
    if($i>0){
      preg_match('/^(?:\(\s*(\d+)\s*,\s*(\d+)\s*\))?([^~]*)/',$f,$m);
      setColor($m[1],$m[2]);
      $len+=strlen($m[3]);
    } else {
      $len+=strlen($f);
    }
  }
  return $len;
}
function text($arr){
  setColor($arr[0],$arr[1]);
  locate($arr[2],$arr[3]);
  _printf($arr[4]);
}
function printbox($arr){
  setColor($arr[0],$arr[1]);
  printbox_($arr[2],$arr[3],$arr[4],$arr[5],$arr[6]);
}
function box($arr){
  setColor($arr[0],$arr[1]);
  @box_($arr[2],$arr[3],$arr[4],$arr[5],$arr[6]?$arr[6]:" ");
}

function button($r,$c,$text,$disabled=true){
$s=_lenf($text);
$c7=($disabled?8:7);
$c15=($disabled?7:15);
return array(
  "box",array(0,$c7,  $r  ,$c  ,$r+2,$c),
  "box",array($c15,8, $r  ,$c+1,$r+2,$c+$s+2),
  "text",array($c15,8,$r+1,$c+2,$text),
);
}
function toolbar($options){
  $options=array_reverse($options);
  $res=array();
  $r=22;$c=75+2;
  $j=0;
  foreach($options as $op){
    //if(substr($op,0,1)!="-") 
      $j++;
  }
  foreach($options as $op){
    $d=substr($op,0,1)=="-";
    if($d)$op=substr($op,1);
    if(!$d){$op="$j. ~(7,8)".$op;$j--;}
//    else {$op=$op;}
    else {$op="$j. ~(7,8)".$op;$j--;}

    $c-=_lenf($op)+2+2;
    $res=array_merge($res,button($r,$c,$op,$d));
  }
  return $res;
}
function printlist($arr){
  for($i=0,$l=count($arr[6]);$i<$l;$i++){
    printbox(array($arr[0],$arr[1],$arr[2]+$i,$arr[3],$arr[4],$arr[5],$arr[6][$i]));
  }
}

$back=3;
$white=15;
function draw2($content){
  for($r=0,$l=count($content);$r<=$l;$r+=2){
    eval($content[$r].'($content[$r+1]);');
  }
}

function drawScreen($content){
  global $back;
  global $white;
  setColor(7,$back);
  cls();
  for($r=0,$l=count($content);$r<=$l;$r+=2){
    eval($content[$r].'($content[$r+1]);');
  }
  setColor(7,0);
  box_(23,77,23,78);
  locate(23,77);
  return trim(inputln());
}
function callExternalPHP($file){
  $res=require($file);
  print("\nPress ENTER to continue.");inputln();
  return $res;
}
function callExternal($cmd){
  $path = getcwd();
  chdir("xampplite");
  $res=system($cmd);
  chdir($path);
  print("\nPress ENTER to continue.");inputln();
  return $res;
}
function exitInstall(){
  setColor(7,0);
  cls();
  EXIT(1);
}

function changeConfig($arr){
  list($property,$value)=$arr;
  $file=isset($arr[2])?$arr[2]:"xampplite/apache/conf/httpd.conf";
  $value=eval("return $value;");
  $httpd=file_get_contents($file);
  $httpd=preg_replace('/^\s*'.$property.'\s*.+$/m', $property.' '.$value, $httpd);
  file_put_contents($file, $httpd);
}

function configDocumentRoot($path){
  changeConfig(array("DocumentRoot",$path));
}

function execute($flow,$index){
  $res=drawScreen($GLOBALS[$flow[$index][0]]);
  if($flow[$index][$res]==-1) exitInstall();
  if(($res>0) && isset($flow[$index][$res])) execute($flow,$flow[$index][$res]);
  else execute($flow,$index);
}
include("xampplite/install/.version");

$intro=array(

  "text",array(9,$back,2,2,"ACHACHI ~(7,$back)DEVELOPER"),
  "box",array(0,$back,3,1,3,80,"-"),
  "box",array(0,$back,21,1,21,80,"-"),
  "box",array(0,$white,4,20,20,75),
  "box",array(0,7,4,20,20,20),
  "text",array(0,$white,5,22,"Achachi Developer Installer"),
  "printbox",array(0,$white,7,24,15,50,"This installer will guide you install Achachi Developer in your computer. To continue press 2 and ENTER."),

  "draw2",toolbar(array("-Back","Next","Cancell")),
);

$toInstall=array(

  "text",array(9,$back,2,2,"ACHACHI ~(7,$back)DEVELOPER"),
  "box",array(0,$back,3,1,3,80,"-"),
  "box",array(0,$back,21,1,21,80,"-"),
  "box",array(0,$white,4,20,20,75),
  "box",array(0,7,4,20,20,20),
  "text",array(0,$white,5,22,"Achachi Developer Installer"),
  "printlist",array(0,$white,7,24,15,50,
    array(
      "The following components will be installed:",
      "+ XAMPP Lite $xamppversion",
      "  + Apache 2.2.14 (IPV6 enabled)",
      "  + MySQL 5.1.41 (Community Server) with PBXT engine 1.0.09-rc",
      "  + PHP 5.3.1 (PEAR)",
      "+ Achachi Framework 1.1.0",
      "+ Achachi Developer 1.1.0",
      "+ Zend Framework 1.11.1",
      "+ Ext JS 3.2.1",
    )),

  "draw2",toolbar(array("Back","Next","Cancell")),
);

$setupXampp=array(
  "text",array(7,0,1,1,""),
  "cls","",
  "box",array(7,$back,1,1,1,80),
  "text",array(7,$back,1,1,"Setup XAMMP Lite..."),
  "text",array(7,0,2,1,""),
  "callExternal","setup_xampp.bat",
  "text",array(9,$back,2,2,""),
  "cls","",

  "text",array(9,$back,2,2,"ACHACHI ~(7,$back)DEVELOPER"),
  "box",array(0,$back,3,1,3,80,"-"),
  "box",array(0,$back,21,1,21,80,"-"),
  "box",array(0,$white,4,20,20,75),
  "box",array(0,7,4,20,20,20),
  "text",array(0,$white,5,22,"Achachi Developer Installer"),
  "printlist",array(0,$white,7,24,15,50,
    array(
      "Setup XAMMP Lite:",
    )),
  '$GLOBALS["port"]=input',array(0,$white,9,26,"Listen Port:",5,"8090"),
  "changeConfig",array( "Listen" , '$GLOBALS["port"]' ),
  "changeConfig",array( "ServerName" , 'var_export("*:".$GLOBALS["port"],true)' ),
  "changeConfig",array( "include_path" , '"=".var_export(get_include_path() . PATH_SEPARATOR . getcwd() . "/xampplite/ZendFramework/library"  ,true)', 'xampplite/apache/bin/php.ini'),

  "draw2",toolbar(array("Back","Next","Cancell")),
);

$congratulations=array(

  "text",array(9,$back,2,2,"ACHACHI ~(7,$back)DEVELOPER"),
  "box",array(0,$back,3,1,3,80,"-"),
  "box",array(0,$back,21,1,21,80,"-"),
  "box",array(0,$white,4,20,20,75),
  "box",array(0,7,4,20,20,20),
  "text",array(0,$white,5,22,"Achachi Developer Installer"),
  "printlist",array(0,$white,7,24,15,50,
    array(
      "Installation is complete.",
    )),

  "draw2",toolbar(array("Back","-Next","Finish")),
);



$flow=array (
  0 => 
  array (
    0 => 'intro',
    2 => 1,
    3 => -1,
  ),
  1 => 
  array (
    0 => 'toInstall',
    1 => 0,
    2 => 2,
    3 => -1,
  ),
  2 => 
  array (
    0 => 'setupXampp',
    1 => 1,
    2 => 3,
    3 => -1,
  ),
  3 => 
  array (
    0 => 'congratulations',
    1 => 2,
    2 => -1,
    3 => -1,
  ),
);

execute($flow,0);