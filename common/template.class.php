<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/
class template {

	/* atributes of futer class
	var $tags;*/
	var $content;
	/*
	* var $file;
	*/


	function template($temp_file){
		$fd = fopen($temp_file,"r");
		$this->content = fread($fd,999999);
		fclose($fd);
	}	
	
	function newLine($text,$class) {
		$this->content = str_replace("<!-- insert -->","<div class=\"$class\">".htmlentities($text)."</div><!-- insert -->",$this->content);
	}
	
	function blankLine() {
		$this->content = str_replace("<!-- insert -->","<div class=\"blank\"></div><!-- insert -->",$this->content);
	}
	
	function show(){
		echo $this->content;
	}

}


?>