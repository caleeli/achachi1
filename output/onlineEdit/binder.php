<?php

function bind($binds){
  $call=debug_backtrace();
  echo ' online-dev="',htmlentities(json_encode($call[0]), ENT_QUOTES, 'utf-8'),'"';
}

function restResponse(){
  $file=$_REQUEST['file'];
  $from=$_REQUEST['from'];
  $to=$_REQUEST['to'];
  $f=fopen($file, 'r');
  $l=0;
  $res=[];
  while($line=fgets($f)){
    $l++;
    if($l>$to) break;
    if($l>=$from) $res[]=$line;
  }
  fclose($f);
  header('Content-Type: application/json');
  echo json_encode($res);
}
