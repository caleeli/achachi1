<?php
class node_package extends node {
	
	public $template  = '<?php
@{$name}@{$type}
' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		foreach  ($e ->childNodes as $ch ) {
			$ch2  = $ch ->cloneNode (true);
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
