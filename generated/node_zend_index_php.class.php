<?php

class node_zend_index_php extends node {
	
	public $template  = '<?php
ini_set("display_errors","on");
@include_once("../bootstrap.php");
@include_once("config.php");
require_once("Zend/Controller/Front.php");
require_once("../library/json.php");
require_once("../library/json_response.php");
require_once(\'Zend/Session.php\');
Zend_Session::start();

$frontController = Zend_Controller_Front::getInstance();
$frontController->throwExceptions(true)
  ->setControllerDirectory("../controllers")
  ->returnResponse(true);
@{node::join("",$_)}

try {
  $response = $frontController->dispatch();
  $response->sendResponse();
} catch (Exception $e) {
  echo $e->getMessage();
}

' ;
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $this ->groupNodes ($_r );
		$template  = $this ->doTemplate ($this ->template,$values );
		file_put_contents ("html/index.php" ,$template );
		return $this ->encodeEmpty ();
	}
}
