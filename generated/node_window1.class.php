<?php

class node_window1 extends node {
	
	public $transparent  = 'true' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		require_once  (ACH_PATH . "/common/ext.class.php" );
		return ext:: dom2array ($e );
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$template  = isset  ($this ->template)?$this ->doTemplate ($this ->template,$values ):"" ;
		return $this ->encodeEmpty ();
	}
}
