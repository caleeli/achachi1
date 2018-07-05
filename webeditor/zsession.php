<?php
session_start();
if(isset($_GET["ft"])){
  /*First Time*/
  print(isset($_SESSION["started"])?"0":"1");
  $_SESSION["started"]=true;
}
