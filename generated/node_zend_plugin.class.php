<?php

class node_zend_plugin extends node {
	
	public $template  = '<?php
class @{$name} extends Zend_Controller_Plugin_Abstract
{
@{node::join("",$_)}
}

' ;
	
	public $register  = 'require_once("../library/@{$name}.php");
$frontController->throwExceptions(true)->registerPlugin(new @{$name}());


' ;
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $this ->groupNodes ($_r );
		$template  = $this ->doTemplate ($this ->template,$values );
		$register  = $this ->doTemplate ($this ->register,$values );
		file_put_contents ("library/"  . $values ["name" ] . ".php" ,$template );
		return $this ->encode ($register );
	}
}
