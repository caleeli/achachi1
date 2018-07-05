<?php
class node_include extends node {
	
	public $template  = '' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		/**
   * Incluye un archivo XML externo @{$src}
   */ $dom  = new DomDocument ();
		$src  = $e ->getAttribute ("src" );
		if  (substr ($src ,0 ,9 ) == '$library/' )$src  = ACH_LIBRARY_PATH . "/"  . substr ($src ,9 ); else $src  = PROJECT_PATH . "/"  . $src ;
		$dom ->load ($src );
		foreach  ($dom ->childNodes->item (0 )->childNodes as $ch ) {
			$ch2  = importNode ($ch ,$e ->ownerDocument);
			$e ->parentNode->insertBefore ($ch2 ,$e );
			$this ->makeFromNode ($ch2 );
		}
		return $this ->encodeEmpty ();
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$template  = isset  ($this ->template)?$this ->doTemplate ($this ->template,$values ):"" ;
		return $this ->encodeEmpty ();
	}
}
