<?php
class node_table_reference extends node {
	
	public $template  = '  ${$name} => array(
      \'columns\'           => ${explode(",",$columns)},
      \'refTableClass\'     => ${$refTableClass},
      \'refColumns\'        => ${explode(",",$refColumns)}
  ),
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
		return $this ->encode ($template );
		return $this ->encodeEmpty ();
	}
}
