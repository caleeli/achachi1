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
header("Content-type: text/plain;");
//include_once("../config.php");

$server=isset($_GET["server"])?$_GET["server"]:"localhost";
$source=isset($_GET["source"])?$_GET["source"]:"localhost";
$destination=isset($_GET["destination"])?$_GET["destination"]:"localhost";
$url="http://$server/achachi/deployer.php?destination=$destination";
print("Deploying to: $url\n");
$target = "$source";
$version = 1;
$file="../output/$target.tar.gz";


$tt=microtime(true);
require_once("archive.php");


$t0=microtime(true);

// Assume the following script is executing in /var/www/htdocs/test
// Create a new gzip file test.tgz in htdocs/test
$test = new gzip_file("$target.tar.gz");

// Set basedir to "../.."
// Overwrite tar.gz file if it already exists
// Set compression level to 1 (lowest)
$test->set_options(array('basedir' => "../output", 'overwrite' => 1, 'level' => 9));

// Add entire target folder directory and all subdirectories
// Add all php files in htsdocs and its subdirectories
$test->add_files(array($target));

// Exclude all .svn directories
$test->exclude_files(array("$target/*.svn","$target/*.svn/*"));

// Create tar.gz file
$test->create_archive();

// Check for errors (you can check for errors at any point)
if (isset($test->errors) && count($test->errors) > 0)
	print ("Errors occurred."); // Process errors here

print "Packing time: ".(microtime(true)-$t0)."\n";
$t0=microtime(true);


//Upload file to deploy server
try {

$file=realpath($file);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_PROXY, "http://192.168.72.36");
    curl_setopt($ch, CURLOPT_PROXYPORT, 8080);

    // same as <input type="file" name="file_box">
    $post = array(
        "uploadedfile"=>"@{$file}",
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
    $response = curl_exec($ch);

print($response);

print "Upload and Deployment  time: ".(microtime(true)-$t0)."\n";
$t0=microtime(true);

} catch (Exception $ex) {
var_dump ($ex);
}

print "Deployment finished.\n";
print "Total time: ".(microtime(true)-$tt)."\n";