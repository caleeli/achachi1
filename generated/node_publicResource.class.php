<?php

class node_publicResource extends node {
	
	public $template  = '' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$template  = isset  ($this ->template)?$this ->doTemplate ($this ->template,$values ):"" ;
		$curDir  = getcwd ();
		chdir (PROJECT_PATH);
		$path  = $e ->getAttribute ("path" );
		$file  = basename ($path );
		$content  = file_get_contents ($path );
		chdir ($curDir );
		//folder in html that will contain the resource; or the controller name.
		$folder  = $e ->getAttribute ("folder" )?$e ->getAttribute ("folder" ):$e ->parentNode->parentNode->getAttribute ("name" );
		createFile ("html/"  . $folder  . "/"  . $file ,$content );
		return $this ->encodeEmpty ();
	}
}
