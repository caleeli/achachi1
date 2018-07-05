<?php
class node_css extends node {
	
	public $template  = '@{node::join("\\n",$_)}
' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$template  = isset  ($this ->template)?$this ->doTemplate ($this ->template,$values ):"" ;
		createFile ("html/"  . $e ->parentNode->parentNode->getAttribute ("name" ) . "/"  . $e ->getAttribute ("name" ) . ".css" ,$template );
		return $this ->encode ('  <link rel="stylesheet" type="text/css" href="'  . htmlentities ('../../'  . $e ->parentNode->parentNode->getAttribute ("name" ) . "/"  . $e ->getAttribute ("name" ) . ".css" ,ENT_QUOTES,'utf-8' ) . '" />' );
		return $this ->encodeEmpty ();
	}
}
