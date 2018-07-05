<?php
if(isset($_POST["n"])){
  if($_POST["n"]=="extjs"){
    $d=json_decode($_POST["d"]);
    @$f=$_SERVER["DOCUMENT_ROOT"]."/ext/docs/output/".$d->class.".html";
    @include($f);
  }
}