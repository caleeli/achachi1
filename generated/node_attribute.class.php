<?php

class node_attribute extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		if  (isset  ($values ["isArray" ]) && $values ["isArray" ] == "true" ) {
			$cnt  = array  ();
			foreach  ($_r as $_i )$cnt [] = $_i [1 ];
		} else if  (isset  ($values ["isString" ]) && $values ["isString" ] == "true" ) {
			$cnt  = ext:: toExpression (ext:: toJson (node:: autoCast (node:: content ($_r ))));
		} else {
			foreach  ($_r as $i => $v )if  ($v [0 ] != 'extjs' )$_r [$i ][1 ] = ext:: toExpression ($_r [$i ][1 ]);
			$cnt  = node:: content ($_r );
		}
		return Array  (Array  ("attribute" ,Array  ($e ->getAttribute ("name" ),$cnt )));
	}
}
