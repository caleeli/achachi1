<?php
ini_set("display_errors","on");
require_once("debug.cli.php");

function abc(){
  $a="Hola Mundo!!";
  eval(debug::point(__FILE__,__LINE__));
  print("$a FINISHED!!");
}
abc();
