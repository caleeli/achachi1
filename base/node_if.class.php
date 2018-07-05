<?php
class node_if extends node {
	
	public $template  = '' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		/** @{$condition}
   */ $vars  = array  ();
		@eval  ('$_condition='  .  ($e ->getAttribute ("condition" )?$e ->getAttribute ("condition" ):"false" ) . ';' );
		//VAR_DUMP($e->getAttribute("condition"));print('$_condition='.($e->getAttribute("condition")?$e->getAttribute("condition"):"false").';');
		if  (isset  ($_condition ) &&  ($_condition )) {
			foreach  ($e ->childNodes as $ch ) {
				$ch2  = $ch ->cloneNode (true);
				$e ->parentNode->insertBefore ($ch2 ,$e );
			}
		}
		$e ->parentNode->removeChild ($e );
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
