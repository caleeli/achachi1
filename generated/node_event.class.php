<?php

class node_event extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		$cnt  = ext:: toExpression (node:: content ($_r ));
		return Array  (Array  ("event" ,Array  ($e ->getAttribute ("name" ),$cnt )));
	}
}
