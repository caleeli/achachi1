<?php

class node_expression extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		$cnt  = ext:: toExpression (node:: content ($_r ));
		return Array  (Array  ("expression" ,Array  ($e ->getAttribute ("name" ),$cnt )));
	}
}
