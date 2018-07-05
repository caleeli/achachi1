<?php
header("Content-Type: text/html; charset=UTF-8");
ini_set("display_errors","on");
eval("?>".file_get_contents("serverpassword.php"));
if(md5("")!=$server_password){
  if(!isset($_POST["password"])&&!isset($_GET["password"])){
    require("servervalidation.php");
    exit();
  } else {
    if(isset($_POST["password"])) $p=md5($_POST["password"]);
    if(isset($_GET["password"])) $p=$_GET["password"];
    if($p==$server_password){
    } else {
      require("servervalidation.php");
      exit();
    }
  }
}
?>
<html><head><style>html{
  background-color:SlateGray;
}
body{
  text-align:center;
}
button{
  border: 2px groove menu;
  border-radius:8px;
  -moz-border-radius:8px;
  background-color:LightGrey;
}
.box{
  border:1px inset SlateGray;
  border-radius:8px;
  -moz-border-radius:8px;
  background-color:snow;
  width:500px;
  display:inline-block;
  margin:8px;
  vertical-align:top;
  text-align:justify;
  padding:0px;
}
.box p{
  padding:0px 8px 0px 8px;
  font-size:small;
}
.boxTitle{
  border:1px outset SlateGray;
  border-radius:8px 8px 0px 0px;
  -moz-border-radius:8px 8px 0px 0px;
  background-color:SteelBlue;
  color:snow;
  font-weight:bold;
  vertical-align:middle;
  text-align:center;
}
.boxContent{
  border:1px outset SlateGray;
  border-radius:0px 0px 8px 8px;
  -moz-border-radius:0px 0px 8px 8px;
  background-color:Azure;
  padding-bottom:8px;
}
.listTable{
  empty-cells:show;
}
.listTable th{
  border-bottom:3px double SlateBlue;
  padding-top:8px;
  font-weight:normal;
  text-align:left;
}
.listTable td{
  padding-top:8px;
}
.ok{
  color:green;
  font-weight:bold;
  cursor:help;
}
.nook{
  color:red;
  font-weight:bold;
  cursor:help;
}
.note{
  font-size:small;
  color:DarkRed;
}
</style></head><body>
<div>
<span class="box" style="width:300"><p>Frameworks instalados actualmente en el servidor.</p></span><span class="box"><div class="boxTitle">FRAMEWORKS</div><div class="boxContent">
<center><table class="listTable" width="80%" cellpadding="0" cellspacing="0">
<tr><th width="70%">Zend Framework</th><th><?php print ZendVersion() ? Installed("Version: ".ZendVersion()): Download("http://framework.zend.com/download/latest", "Zend Framework was not found in the include_path.") ?></th></tr>

<tr><th>ExtJs</th><th><?php print ($extVer=ExtJsVersion()) ? Installed( "Version: $extVer" ): Download("http://www.sencha.com/products/extjs/download/", "It must be installed at ".$_SERVER["DOCUMENT_ROOT"]."/ext") ?></th></tr>

</table></center></div></span>
</div>
<br />
<div>
<span class="box" style="width:300"><p><img src="developer.jpg" /><br /><b>Web Developer</b>, le permitirá preparar un entorno de desarrollo acorde a sus necesidades, y desarrollar aplicaciones utilizando los frameworks que tenga disponible. Haga click en "Iniciar" para ingresar al entorno de desarrollo.</p></span><span class="box"><div class="boxTitle">WEB DEVELOPER</div><div class="boxContent">
<center><table class="listTable" width="80%" cellpadding="0" cellspacing="0">
<tr><th width="70%">Web Developer</th><th><?php 

function DeveloperVersion(){
  if(file_exists("webeditor/")){
    return file_get_contents("webeditor/version.txt");
  }
  return false;
}

print ($ver=DeveloperVersion()) ? Installed("Version: ".$ver): NotInstalled("Web Developer has been removed.") 

?></th></tr>

<?php if($ver): ?>
  <tr><td width="70%"></td><td>
    <button type="button" style="background-color:white;font-weight:bold" onclick="window.location.href='webeditor/zeditor.html'">
      <img src="developer.jpg" />Start
    </button>
  </td></tr>
  <tr><td width="70%" class="note">If you will not use this server for development you could remove the Web Developer.</td><td><button type="button" onclick="if(confirm('Are you sure to remove Web Developer?'))window.location.href='webeditor/removewebeditor.php'">Remove</button></td></tr>
<?php endif; ?>
</table></center></div></span>
</div>
<br />
<div>
<span class="box" style="width:300"><p><img src="server.jpg" /><br /><b>Web Server</b>, it will permits you to deploy applications created by Web Developer in this server.</p></span><span class="box"><div class="boxTitle">WEB SERVER</div><div class="boxContent">
<center><table class="listTable" width="80%" cellpadding="0" cellspacing="0">
<tr><th width="70%">Web Application Deployer</th><th><?php 

function ServerVersion(){
  if(file_exists("deployer.php")){
    $dep = file_get_contents("deployer.php");
    preg_match('/Version:\s*([\d\.]+)/', $dep, $ma);
    return $ma[1];
  }
  return false;
}

print ($ver=ServerVersion()) ? ($deployer_disabled? Disabled("Deployer was disabled. Version: ".$ver):Installed("Version: ".$ver)): NotInstalled("Web Developer has been removed.") 

?></th></tr>

<?php if($ver): ?>
<?php if(isset($deployer_disabled) && $deployer_disabled): ?>
  <tr><td width="70%" class="note">
    If you will deploy applications in this server you should enable this feature.
    </td><td><button type="button" onclick="window.location.href='enabledeployer.php'">Enable</button></td></tr>
<?php else: ?>
  <tr><td width="70%" class="note">
    If you will not deploy applications in this server you could disable this feature.
    </td><td><button type="button" onclick="window.location.href='disabledeployer.php'">Disable</button></td></tr>
<?php endif; ?>
  <tr><td width="70%">Change Password
  <?php
    if($server_password==md5("")){
    ?>
    <p class="note">Currently it is not defined a password for this server.</p>
    <?php
    }
  ?>
  </td><td>
    <form action="changepassword.php" method="POST">
      Old Password: <input name="password" type="password"/>
      New Password: <input name="newpassword" type="password"/>
      <button type="submit">Change Password</button>
    </form>
  </td></tr>
<?php endif; ?>
</table></center></div></span>
</div>
<br />

</body></html><?php

function ZendVersion(){
  @include_once("Zend/Version.php");
  if(class_exists("Zend_Version")) return Zend_Version::VERSION;
  return false;
}

function ExtJsVersion(){
  if(file_exists( $_SERVER["DOCUMENT_ROOT"]."/ext/ext-all.js" )){
    $ext=file_get_contents($_SERVER["DOCUMENT_ROOT"]."/ext/ext-all.js");
    preg_match('/Ext JS Library ([\d\.]+)/', $ext, $ma);
    return $ma[1];
  }
  return false;
}

function Installed($note=""){
?><span class="ok" title="<?php print htmlentities($note, ENT_QUOTES, "utf-8") ?>">Installed</span><?php
}

function Download($url,$note=""){
?><span class="nook" title="<?php print htmlentities($note, ENT_QUOTES, "utf-8") ?>">Not Found (?)</span> <a href="<?php print htmlentities($url, ENT_QUOTES, "utf-8") ?>" target="_blank">Download</a><?php
}

function notInstalled($note=""){
?><span class="nook" title="<?php print htmlentities($note, ENT_QUOTES, "utf-8") ?>">Not Installed (?)</span><?php
}

function Disabled($note=""){
?><span class="nook" title="<?php print htmlentities($note, ENT_QUOTES, "utf-8") ?>">Disabled</span><?php
}

function rrmdir($dir) {
 if (is_dir($dir)) {
   $objects = scandir($dir);
   foreach ($objects as $object) {
     if ($object != "." && $object != "..") {
       if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
     }
   }
   reset($objects);
   rmdir($dir);
 }
}