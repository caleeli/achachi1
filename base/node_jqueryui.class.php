<?php
class node_jqueryui extends node {
	
	public $template  = '@{node::join("",$_)}' ;
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		//@{$name}
		$_r  = node:: run ();
		$values ["_" ] = $_r ;
		$template  = $this ->doTemplate ($this ->template,$values );
		return $this ->encode ($template );
	}
}
