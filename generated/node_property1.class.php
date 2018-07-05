<?php
class node_property1 extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		if  (!isset  ($values ["access" ]))$values ["access" ] = "public" ;
		$res  = $values ["access" ] . ' $'  . $values ["name" ] . "="  . var_export ($values ["#" ],true) . ";" ;
		return $this ->encode ($res );
	}
}
