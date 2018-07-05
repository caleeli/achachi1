<?php
ini_set("display_errors","on");
global $definedClasses,$generationType,$generatedPath;
global $core;
global $PARAMS;

require_once("common/formatcode.php");
require_once("common/util.php");
loadClasses("core");
require_once("config.php");

$file = "../test.i.php";
ob_start();
include($file);
//$code = Spyc::YAMLLoadString(ob_get_contents());
$code = ob_get_contents();
ob_end_clean();
//print_r( $code);

define("PROJECT_PATH", realpath(dirname($file)) );
//chdir(PROJECT_PATH);

$sys = new DomDocument();
$sys->loadXml("<root></root>");

$core = new node($sys->firstChild);
$core->yaml($code, $sys->firstChild);

