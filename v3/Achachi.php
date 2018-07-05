<?php
require_once("AchachiExpression2.php");

class Achachi {
	static $FILENAME='';
	static $PARAMS=array();
	static $DEFINITION=array();
	function feval($expression,$params=array()){
		global $_evaluateFile;
		$prev=$_evaluateFile;
		$_evaluateFile='Achachi:feval()';
		ACHACHI::$FILENAME='Achachi:feval()';
		ACHACHI::$PARAMS=$params;
		$r=AchachiExpression::evaluateS($expression, (!is_array(@$params)?array():$params) );
		$_evaluateFile=$prev;
		return $r;
	}
	function finclude($file,$params=array()){
		global $ACHACHI;
		ACHACHI::$PARAMS=$params;
		foreach($params as $k=>&$v){
			$ACHACHI[$k]=$v;
		}
		return include($file);
	}
	function compile($file,$params=array()){
		return achachi($file,$params);
	}
	function definition($file, $tag){
		$A = new Achachi($_GET["f"]);
		$def = $A->compile_definition($file, array());
		if(!isset($def->editor)) $def->editor='default.editor';
		if(!isset($def->action)) $def->action='default.editor';
		if(!isset($def->name)) $def->name=basename($tag->filename);
		if(!isset($def->icon)) $def->icon="icons/empty.png";
		if(!isset($def->params)) $def->params=array();
		foreach($def as $k=>&$v) $tag->$k=$v;
		return $tag;
	}
	function compile_definition($file, $params=array()){
		global $_evaluateFile;
		$_evaluateFile=$file;
		ACHACHI::$FILENAME=$file;
		if(file_exists($_evaluateFile)) {
			$content = file_get_contents($_evaluateFile);
			if(substr($content,0,12)=="%{definition") {
				$X=new AchachiExpression();
				$X->breaked=true;
				$X->evaluate($content,$params);
				return ACHACHI::$DEFINITION;
			} else {
				return new StdClass();
			}
		} else {
			throw new Exception("File '$file' not found");
		}
	}
}

function findFile($file){
	if(file_exists($file)) return $file;
	$file1 = dirname($file).'/.achachi/'.basename($file);
	if(file_exists($file1)) return $file1;
	$file2 = dirname(__FILE__).'/.achachi/'.basename($file);
	if(file_exists($file2)) return $file2;
	return $file;
}

function achachi($file,$params=array()){
	global $_evaluateFile;
	$file = findFile($file);
	ACHACHI::$PARAMS=$params;
	$prev=$_evaluateFile;
	$_evaluateFile=$file;
	ACHACHI::$FILENAME=$file;
	$r=AchachiExpression::evaluateS(file_get_contents($file), (!is_array(@$params)?array():$params) );
	$_evaluateFile=$prev;
	return $r;
}

function definition($d){
	if (is_array($d)) {
		ACHACHI::$DEFINITION = (object) $d;
	}
	else {
		ACHACHI::$DEFINITION = $d;
	}
//	return ACHACHI::$DEFINITION;
}

function xefinition($definition){
	global $__this;
	$__this[count($__this)-1]->breaked=true;
	return definition($definition);
}
