<?php
class node__cdata extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		return $this ->encode ($values ["#" ]);
	}
}
