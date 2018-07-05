<div id="explorer_content" style="font-size:12px">
<div style="text-align:right">
<input type="button" value="X" onclick="var p=document.getElementById('explorer_content').parentNode;p.parentNode.removeChild(p);" style="font-size:10px" />
</div>
<style>
.explorer_folder {
  background-image:url('folder.gif');
  background-repeat:no-repeat;
  padding-left: 16px;
}
.explorer_file {
  background-image:url('images/16/_text.gif');
  background-repeat:no-repeat;
  padding-left: 16px;
}
</style>
<?php
$filter=isset($_GET["f"])?$_GET["f"]:"*";
$path=isset($_GET["p"])?$_GET["p"]:".";
print(realpath($path)."<br />");
$jsFnManager = (isset($_GET["type"]) && $_GET["type"]=="p") ? "openPathExplorer" : "openFileExplorer";
?>
<select id="explorer_select" size="10" onkeypress="if(event.keyCode==13) if(<?php print $jsFnManager ?>) <?php print $jsFnManager ?>(this.value); else window.location.href='?p='+this.value;" ondblclick="if(<?php print $jsFnManager ?>) <?php print $jsFnManager ?>(this.value); else window.location.href='?p='+this.value;" style="width:100%;heigth:90%;">
<?php
print("<option value='{$path}/..'>..</option>");
$fs=glob($path."/".$filter, GLOB_ONLYDIR );
$x=usort($fs,"strcasecmp");
foreach($fs as $f) {
  print("<option value='".$f."' class='explorer_folder'>".basename($f)."/</option>");
}
if(!(isset($_GET["type"]) && $_GET["type"]=="p")) {
  $filter=isset($_GET["f"])?$_GET["f"]:"*.x*";
  $fs=glob($path."/".$filter );
  usort($fs,"strcasecmp");
  foreach($fs as $f) if(is_file($f)) {

    print("<option value=':".realpath($f)."' class='explorer_file'>".basename($f)."</option>");
  }
}
?>
</select><?php
if(isset($_GET["type"]) && $_GET["type"]=="p") {
  ?><br /><button onclick="winExplorer.style.display='none';importProject(this.previousSibling.previousSibling.value);">Open</button><?php
}
?>
</div>