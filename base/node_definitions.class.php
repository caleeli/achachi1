<?php
class node_definitions extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		if  (isset  ($values ["disabled" ]) and $values ["disabled" ] == "true" )return $this ->encodeEmpty ();
		return node:: run ();
	}
}
