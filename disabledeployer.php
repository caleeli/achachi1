<?php
ini_set("display_errors","off");
if(!isset($_POST["password"])){
  require("servervalidation.php");
} else {
  eval("?>".file_get_contents("serverpassword.php"));
  if(md5($_POST["password"])==$server_password){
    $deployer_disabled=true;
    server_save_config();
    print("<script>window.location.href='index.php?password=".urlencode($server_password)."'</script>");
  } else {
    require("servervalidation.php");
  }
}