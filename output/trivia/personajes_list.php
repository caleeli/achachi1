<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="pure-min.css">
</head>
<body>
<?php
global $personajes;
//require_once("personajes.php");
require_once("uDB.php");
$personajes=new uDB('db/personajes');
//set_time_limit(1);
function printGrid( $array )
{
  print( '<div><a class="pure-button" type="button" href="personajes_form.php">Nuevo</a><a class="pure-button" type="button" href="personajes_compile.php">Compile</a></div>' );
  print( '<table class="pure-table" border="1"><thead>' );
  //bug: 49369
//  reset( $array );
//  print( '<tr><th>[]</th><th>' );
//  print( implode( '</th><th>', array_keys( current( $array ) ) ) );
//  print( '</th></tr>' );
//  print( '</thead><tbody>' );
  $header=array();
  foreach( $array as $row => $cells )
  {
    print( '<tr><th>[]</th>' );
    foreach($cells as $k=>$c){
      print( '<th>'.$k.'</th>' );
      $header[]=$k;
    }
    print( '</th></tr>' );
    print( '</thead><tbody>' );
    break;
  }
  foreach( $array as $row => $cells )
  {
    $cellsH=array();
    foreach($cells as $ck=>$c) if(is_array($c)) $cellsH[$ck]=implode('<br />',$c); else $cellsH[$ck]=$c;
    /*print( "<tr><th>$row</th><td>" );
    print( implode( '</td><td>', $cellsH ) );
    print( '</td><td><a class="pure-button" href="personajes_form.php?id='.$row.'">Editar</a></td></tr>' );*/
    print( "<tr><th>$row</th>" );
    foreach($header as $h){
      print( '<td>'.@$cellsH[$h].'</td>' );
    }
    print( '<td><a class="pure-button" href="personajes_form.php?id='.$row.'">Editar</a></td></tr>' );
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

printGrid( $personajes );