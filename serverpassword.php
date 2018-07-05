<?php
function server_save_config(){
  global $deployer_disabled;
  global $server_password;
  $c=file_get_contents(dirname(__FILE__)."/serverpassword.php");
  $tag='//CONFIG';
  $c=substr($c,0,strpos($c, $tag, strpos($c, $tag)+1)).$tag."\n";
  $c.='$deployer_disabled='.var_export($deployer_disabled,true).";\n";
  $c.='$server_password='.var_export($server_password,true).";\n";
  file_put_contents(dirname(__FILE__)."/serverpassword.php",$c);
}

//CONFIG
$deployer_disabled=false;
$server_password=md5("");
