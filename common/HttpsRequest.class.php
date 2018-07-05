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

/**************************************************************************

Send files and custom headers in https POST requests

Requires	:	Curl extension fro PHP5, 
				user and pass base64_encoded passed to contructor 

Author 		: 	Jakub Pezacki
email		:	pezacki@hotmail.com

***************************************************************************/

class HttpsRequest{

var $user;
var $pass;

function send_file($url,$file){
 
		$header = array();
	   	$header[] = "Content-Type: text/xml";
	   
       	$ch = curl_init();       
       	curl_setopt($ch, CURLOPT_URL,$url);
       	
       	//send the file
       	$fp = fopen($file, "r");
       	curl_setopt($ch, CURLOPT_PUT, true);
       	curl_setopt($ch, CURLOPT_UPLOAD, true);
		curl_setopt($ch, CURLOPT_INFILE, $fp); 
       	curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file)); 
       	
       	//headers
       	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
       	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		       	
       	//verification	//login:pass ~ gets base64 encoded by curl
       	curl_setopt($ch, CURLOPT_USERPWD, $this->user.":".$this->pass);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
       	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
       	
       	//errors reporting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       	curl_setopt($ch, CURLOPT_VERBOSE, true);
       	curl_setopt($ch, CURLOPT_PROGRESS, true);
       	curl_setopt($ch, CURLOPT_MUTE, 0);
       	
		
		$data = curl_exec($ch);
		      	 
       	//display errors
       	$cErr = curl_errno($ch);
         
       	  if ($cErr != '') {
        	$err = 'cURL ERROR: '.curl_errno($ch).': '.$cErr.'<br>';
			foreach(curl_getinfo($ch) as $k => $v){
				$err .= "$k: $v<br>";
			}
			echo("Error $err");
			curl_close($ch);
	    	return false;
		}
       	
       	curl_close($ch);
		return $data;
}
	
function send_custom_headers($url,$header){
 
	   	$header[] = "Content-Type: text/xml";
	    $header[] = "Content-Length: 0";
	   	
       	$ch = curl_init();       
       	curl_setopt($ch, CURLOPT_URL,$url);
       	     	
       	//headers
       	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
       	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		       	
       	//verification	//login:pass ~ gets base64 encoded by curl
       	curl_setopt($ch, CURLOPT_USERPWD, $this->user.":".$this->pass);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
       	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
       	
       	//errors reporting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       	curl_setopt($ch, CURLOPT_VERBOSE, true);
       	curl_setopt($ch, CURLOPT_PROGRESS, true);
       	curl_setopt($ch, CURLOPT_MUTE, 0);
       	
		
		$data = curl_exec($ch);
		      	 
       	//display errors
       	$cErr = curl_errno($ch);
         
       	  if ($cErr != '') {
        	$err = 'cURL ERROR: '.curl_errno($ch).': '.$cErr.'<br>';
			foreach(curl_getinfo($ch) as $k => $v){
				$err .= "$k: $v<br>";
			}
			echo("Error $err");
			curl_close($ch);
	    	return false;
		}
       	
       	curl_close($ch);
		return $data;
}


function HttpsRequest($user,$pass){

	$this->user = base64_decode($user);
	$this->pass = base64_decode($pass);
	}

}
?>