<?php
function getZendVersion(){
  $exists=include("Zend/Version.php");
  if(!$exists) return "none";
  else return Zend_Version::VERSION;
}
function getExtJsVersion(){
  $path=$_SERVER["DOCUMENT_ROOT"]."/ext";
  if(file_exists($path)) return $path;
  else return "none";
}
function getAchachiVersion(){
  $path=$_SERVER["DOCUMENT_ROOT"]."/achachi/version.txt";
  if(file_exists($path)) return file_get_contents($path);
  else return "none";
}
function getAchachiDevVersion(){
  $path=$_SERVER["DOCUMENT_ROOT"]."/achachi/webeditor/version.txt";
  if(file_exists($path)) return file_get_contents($path);
  else return "none";
}
?>
<table border="1">
<tr>
<th>Achachi Framework Installed:</td><td><?php print(getAchachiVersion()) ?></th><?php if (getAchachiVersion()=="none") {?><td>Achachi folder must be at DOCUMENT_ROOT:<?php print($_SERVER["DOCUMENT_ROOT"]) ?></td><?php } ?>
</tr>
<tr>
<th>Achachi Developer Installed:</td><td><?php print(getAchachiDevVersion()) ?></th><?php if (getAchachiDevVersion()=="none") {?><td>Achachi folder must be at DOCUMENT_ROOT:<?php print($_SERVER["DOCUMENT_ROOT"]) ?></td><?php } ?>
</tr>
<tr>
<th>Zend Framework Installed:</td><td><?php print(getZendVersion()) ?></th><?php if (getZendVersion()=="none") {?><td><input type="button" value="Install" /></td><?php } ?>
</tr>
<tr>
<th>ExtJs Installed:</td><td><?php print(getExtJsVersion()) ?></th><?php if (getExtJsVersion()=="none") {?><td><input type="button" value="Install" /></td><?php } ?>
</tr>
</table>