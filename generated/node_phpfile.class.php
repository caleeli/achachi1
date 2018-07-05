<?php
class node_phpfile extends node {
	
	public $template  = '@{@node::join("\\n",$_)}' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$template  = isset  ($this ->template)?$this ->doTemplate ($this ->template,$values ):"" ;
		createFile ("library/"  . $e ->getAttribute ("name" ),$template );
		return $this ->encodeEmpty ();
	}
}
