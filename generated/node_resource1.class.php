<?php
class node_resource1 extends node {
	
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
		if  ($e ->getAttribute ("name" )) {
			$file  = str_replace ('\\' ,'/' ,dirname ($file ));
			if  (substr ($file ,-1 ,1 ) != "/" )$file .= "/" ;
			$file .= $e ->getAttribute ("name" );
		}
		//folder that will contain the resource; or the controller name.
		$folder  = $e ->getAttribute ("folder" )?$e ->getAttribute ("folder" ):$e ->parentNode->parentNode->getAttribute ("name" );
		createFile ($folder  . "/"  . $file ,$content );
		return $this ->encodeEmpty ();
	}
}
