<?php
class node__comment extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		return $this ->encodeEmpty ();
	}
}
