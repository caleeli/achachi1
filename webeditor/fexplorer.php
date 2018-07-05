<?php
@$path=$_REQUEST["path"];
@$pattern=$_REQUEST["pattern"];
if(!$path) $path="";
if(!$pattern) $pattern="*.xml";
$path=str_replace("\\", "/", $path);
if(substr($path,-1,1)!="/") $path.="/";
$dirs = array();
foreach(glob($path."*",GLOB_ONLYDIR) as $f) if(!is_dir($f))$files[]=array("path"=>$f,"name"=>basename($f));
$files=array();
foreach(glob($path.$pattern) as $f) if(!is_dir($f))$files[]=array("path"=>$f,"name"=>basename($f));

print json_encode(array("dirs"=>$dirs,"files"=>$files));
