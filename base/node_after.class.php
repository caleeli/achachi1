<?php
class node_after extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		return $this ->encode (node:: join ("" ,$_r ));
	}
}
