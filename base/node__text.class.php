<?php
class node__text extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		return $this ->encode ($values ["#" ]);
	}
}
