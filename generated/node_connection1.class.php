<?php

class node_connection1 extends node {
	
	public $template  = '<?php
require_once("Zend/Db.php");
require_once("Zend/Db/Table.php");
$db = Zend_Db::factory("@{$driver/*Mysqli|Oracle|Db2|PDO_MYSQL|PDO_PGSQL|PDO_OCI|PDO_MSSQL|PDO_SQLITE|PDO_IBM*/}", #{
  $_atts = array();
  foreach($_e->attributes as $_a)
    if($_a->nodeName!="name")
    if($_a->nodeName!="driver")
    if($_a->nodeValue!="")
      $_atts[$_a->nodeName] = $_a->nodeValue;
  return var_export($_atts, true);
});
Zend_Db_Table_Abstract::setDefaultAdapter($db);
' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$template  = isset  ($this ->template)?$this ->doTemplate ($this ->template,$values ):"" ;
		createFile ("model/"  .  ($e ->getAttribute ("name" )?$e ->getAttribute ("name" ):"connection.php" ),$template );
		return $this ->encodeEmpty ();
	}
}
