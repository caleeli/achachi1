<?php
class node_clone extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		/*var_dump($values["value"]);*/
		//print("clone========================".htmlentities(var_export($values["value"],1)));
		eval  ('$_values= '  . $values ["value" ] . ';' );
		return $_values ;
	}
}
