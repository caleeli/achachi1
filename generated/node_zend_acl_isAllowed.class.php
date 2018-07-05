<?php

class node_zend_acl_isAllowed extends node {
	
	public $register  = '
  global $acl;
  if($acl->isAllowed(@{$user_rol/*$_SESSION[\'user\']*/},@{$resource/*"resource"*/})){
@{node::join("",$_)}
  }
' ;
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $this ->groupNodes ($_r );
		$register  = $this ->doTemplate ($this ->register,$values );
		return $this ->encode ($register );
	}
}
