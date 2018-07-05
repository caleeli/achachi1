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

ini_set("display_errors","on");
@include_once("config.php");
require_once("Zend/Controller/Front.php");
require_once("../library/json.php");
require_once("../library/json_response.php");
//define('URL_BASE','/sistema/html/');
define('URL_BASE','');
session_start();
//Zend_Controller_Front::run("../controllers");

$frontController = Zend_Controller_Front::getInstance();
$frontController->throwExceptions(true)
//->registerPlugin(new My_Controller_Plugin_Auth())
  ->setControllerDirectory("../controllers")
  ->returnResponse(true);

try {
  $response = $frontController->dispatch();
  $response->sendResponse();
} catch (Exception $e) {
  echo $e->getMessage();
}

//session_commit();