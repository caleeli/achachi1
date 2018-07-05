<?php

class node_method1 extends node {
	
	public $template  = '  /**
   * @{@$descripcion}
   * @author @{@$author}
   */
  public function @{$name}(@{@$params})
  {
    @{node::join("",$_)}
  }
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
