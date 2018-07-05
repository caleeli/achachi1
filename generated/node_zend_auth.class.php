<?php

class node_zend_auth extends node {
	
	public $template  = '<?php
global @{$zend_auth_adapther/*$zend_auth_adapther*/},$db;
  require_once("Zend/Auth/Adapter/DbTable.php");
  require_once("../model/connection.php");
  @{$zend_auth_adapther} = new Zend_Auth_Adapter_DbTable(
    $db,
    ${$table},
    ${$username},
    ${$password},
    ${@$passwordTreatment/*md5(?)*/}
  );
' ;
	
	public $register  = '
  global @{$zend_auth/*$zend_auth*/},@{$zend_auth_adapther/*$zend_auth_adapther*/};
  require_once("../library/auth_adapter.php");
  require_once("Zend/Auth.php");
  @{$zend_auth_adapther}->setIdentity($@{$username/*username*/});
  @{$zend_auth_adapther}->setCredential($@{$password/*password*/});
  @{$zend_auth} = Zend_Auth::getInstance();
  @{$zend_auth}_result = $zend_auth->authenticate(@{$zend_auth_adapther});
' ;
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $this ->groupNodes ($_r );
		$template  = $this ->doTemplate ($this ->template,$values );
		$register  = $this ->doTemplate ($this ->register,$values );
		file_put_contents ("library/auth_adapter.php" ,$template );
		return $this ->encode ($register );
	}
}
