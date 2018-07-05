<?php

class node_zend_acl_config extends node {
	
	public $template  = '<?php
  require_once("Zend/Acl.php");
  require_once("Zend/Acl/Role.php");
  require_once("Zend/Acl/Resource.php");
  $acl = new Zend_Acl();
@{node::join("",$_)}' ;
	
	public $register  = '  require_once("../library/acl.php");
' ;
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $this ->groupNodes ($_r );
		$template  = $this ->doTemplate ($this ->template,$values );
		file_put_contents ("library/acl.php" ,$template );
		$register  = $this ->doTemplate ($this ->register,$values );
		return $this ->encode ($register );
	}
}
