<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="pure-min.css">
</head>
<body>
<?php
require_once("uDB.php");
$misiones=new uDB('db/misiones');
set_time_limit(1);

$array=array();
foreach( $misiones as $row =>$cells){
  $r=array();
  foreach($cells as $k=>$v){
    $r[$k]=$v;
  }
  $array[$row]=$r;
}
file_put_contents('mobile/data/misiones.js', 'var DATA_misiones='.json_encode($array) );
foreach(glob('img/*') as $f){
  copy($f,'mobile/'.$f);
}
