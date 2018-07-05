<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="pure-min.css">
</head>
<body>
<?php
require_once("uDB.php");
$preguntas=new uDB('db/preguntas');
set_time_limit(-1);

$array=array();
foreach( $preguntas as $row =>$cells){
  $r=array();
  foreach($cells as $k=>$v){
    $r[$k]=$v;
  }
  if(!empty($r["mision"])){
    $r["mision"]=$r["mision"]=="genesis"?"genesis": "especial";
  }
  $array[$row]=$r;
}
file_put_contents('mobile/data/preguntas.js', 'var DATA_preguntas='.json_encode(array_values($array)) );
foreach(glob('img/*') as $f){
  copy($f,'mobile/'.$f);
}
