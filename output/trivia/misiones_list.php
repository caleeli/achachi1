<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="pure-min.css">
</head>
<body>
<?php
global $misiones;
//require_once("misiones.php");
require_once("uDB.php");
$misiones=new uDB('db/misiones');
//set_time_limit(1);
function printGrid( $array )
{
  print( '<div><a class="pure-button" type="button" href="misiones_form.php">Nuevo</a><a class="pure-button" type="button" href="misiones_compile.php">Compile</a></div>' );
  print( '<table class="pure-table" border="1"><thead>' );
  foreach( $array as $row => $cells )
  {
    print( '<tr><th>[]</th>' );
    foreach($cells as $k=>$c){
      print( '<th>'.$k.'</th>' );
    }
    print( '</th></tr>' );
    print( '</thead><tbody>' );
    break;
  }
  foreach( $array as $row => $cells )
  {
    $cellsH=array();
    foreach($cells as $ck=>$c) if(is_array($c)) $cellsH[$ck]=implode('<br />',$c); else $cellsH[$ck]=$c;
    print( "<tr><th>$row</th><td>" );
    print( implode( '</td><td>', $cellsH ) );
    print( '</td><td><a class="pure-button" href="misiones_form.php?id='.$row.'">Editar</a></td></tr>' );
  }
  print( '</tbody></table>' );
}
function arrayResult( &$result )
{
  $array=array();
  while( $row = $result->Read() )
  {
    $array[] = $row;
  }
  return $array;
}

printGrid( $misiones );