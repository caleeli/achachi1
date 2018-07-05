<?php
class node__cdata_section extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		return $this ->encode ($values ["#" ]);
	}
}
