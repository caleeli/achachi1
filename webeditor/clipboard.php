<?php
ini_set("display_errors","off");
if(isset($_POST["copy"])){
  session_start();
  $_SESSION["clipboard"]=$_POST["copy"];
}
elseif(isset($_POST["paste"]) && $_POST["paste"]=="xml"){
  header("Content-type: application/xml;");
  session_start();
  print $_SESSION["clipboard"];
}