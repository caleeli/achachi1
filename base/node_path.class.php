<?php
class node_path extends node {
	
	public $template  = '' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$curDir  = getcwd ();
		$path  = $e ->getAttribute ("path" );
		if  ($path == null)$path  = $e ->getAttribute ("name" );
		if  (!file_exists ($path )) {
			createDir ($path );
		}
		chdir ($path );
		/*@{$name}*/
		global $PARAMS ;
		if  (isset  ($PARAMS ["run" ]) && $PARAMS ["run" ])if  ($e ->getAttribute ("main" ))print  ("<script>window.location.href=("  . json_encode ($e ->getAttribute ("main" )) . ")</script>" );
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$template  = isset  ($this ->template)?$this ->doTemplate ($this ->template,$values ):"" ;
		chdir ($curDir );
		return $this ->encodeEmpty ();
	}
}
