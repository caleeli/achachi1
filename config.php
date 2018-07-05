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

/**
 * INITIALIZE CONSTANTS
 */
define('ACH_DEBUG_SHOW_CODE',false);
define('ACH_DEBUG_SHOW_INSTANCING',false);
define('ACH_DEBUG_SHOW_CREATED_TAG',true);
define('ACH_PATH',dirname(realpath(__FILE__)));
define('ACH_CORE_PATH',ACH_PATH.'/core');
define('ACH_COMMON_PATH',ACH_PATH.'/common');
define('ACH_BASE_PATH',ACH_PATH.'/base');
define('ACH_LIBRARY_PATH',ACH_PATH.'/library');
define('ACH_GENERATED_PATH',ACH_PATH.'/generated');
/**
 * eval: Generate code and evaluate classes
 * require: Generate and save code then "require" classes
 */
define('ACH_GENERATE_TYPE_EVAL','eval');
define('ACH_GENERATE_TYPE_GENERATE','generate');
define('ACH_GENERATE_TYPE_DEFAULT',ACH_GENERATE_TYPE_GENERATE);
/**
 * Verify base.xml to generate base classes
 */
if(file_exists(ACH_LIBRARY_PATH."/base.xml")) {
  $defxml = file_get_contents(ACH_LIBRARY_PATH."/base.xml");
  $defxmlmd5 = md5($defxml);
  $curdefxmlmd5 = file_exists(ACH_BASE_PATH."/md5.txt") ? file_get_contents(ACH_BASE_PATH."/md5.txt") : "";
  define('ACH_GENERATE_BASE',$defxmlmd5!=$curdefxmlmd5);
} else {
  define('ACH_GENERATE_BASE',false);
}
define('ACH_URI_BASE',dirname($_SERVER["SCRIPT_NAME"]).'/output');
/* ACH_OUTPUT_PATH = relative path from current directory */
define('ACH_OUTPUT_PATH','output');

/**
 * Generates base classes
 */
if(ACH_GENERATE_BASE) {
  chdir(ACH_PATH);
  foreach(glob(ACH_BASE_PATH."/*.class.php") as $f) unlink($f);
  $def = new DomDocument();
  $def->load(ACH_LIBRARY_PATH."/base.xml");
  file_put_contents(ACH_BASE_PATH."/md5.txt",$defxmlmd5);
  $generatedPath = ACH_BASE_PATH;
  $generationType = ACH_GENERATE_TYPE_GENERATE;
  $ndef = new node($def->firstChild);
  $ndef->run();
} else {
  loadClasses("base");
}

$generationType=ACH_GENERATE_TYPE_DEFAULT;
$generatedPath = ACH_GENERATED_PATH;

