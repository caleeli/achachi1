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
 * Version: 1.0
  **/
ini_set("display_errors","on");
if($config=file_get_contents("serverpassword.php")){
  eval("?>".$config);
  if($deployer_disabled) die("Server Deployer has been disabled.\n");
  //Note: $_GET["password"] must be first transformed with md5
  if($server_password!=md5("")){
    if(!isset($_GET["password"])){
      die("Password required.\n");
    } elseif($_GET["password"]!=$server_password){
      die("Incorrect password.\n");
    }
  }
}
require_once("common/archive.php");

$target = $_GET["destination"];
createDir("upload");
$target_path = './upload/';
$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    chdir("../");
    $filename="achachi/upload/".basename($target_path);
    print "Deployment file: ".  $filename . "\n";
    $deploy = new gzip_file($filename);

    if(!file_exists($target)) { 
      print "Created directory: $target\n";
      createDir($target);
    }
    print "Target: ".realpath($target)."\n";

    $deploy->set_options(array("overwrite"=>1));
    $deploy->extract_files();
    if($deploy->error) {
      print_r($deploy->error);
    }
} else{
    
    echo "There was an error uploading the file, please try again!";
}

function createDir($path,$base="./")
{
  $path=str_replace("\\","/",$path);
  $p=explode("/",$path);
  $dir = $base.$p[0];
  //var_dump($dir);
  if(!file_exists($dir)) { mkdir($dir, 0755); chmod($dir,0755); }
  unset($p[0]);
  if(count($p)>0) createDir(implode("/",$p),$dir."/");
}
