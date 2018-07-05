<?php
/**
 *
 *  Achachi XML Builder - Framework and applications builder tool
 *  Copyright (C) 2010-2011, Llankay Achachi
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
?><pre><?php
ini_set("display_errors","on");
global $definedClasses,$generationType,$generatedPath;

require_once("common/formatcode.php");
require_once("common/util.php");
loadClasses("core");
require_once("config.php");

/**
 * Loads default sample
 */
$file = isset($_GET["filename"]) ? $_GET["filename"] : "samples/test.xml";
define("PROJECT_PATH", realpath(dirname($file)) );

$sys = new DomDocument();
$sys->load($file);
global $core;
global $PARAMS;
$PARAMS=array();
if(isset($_GET["run"]))$PARAMS["run"]=$_GET["run"];
$core=new node($sys->firstChild);
$core->run();


