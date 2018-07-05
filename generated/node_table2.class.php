<?php

class node_table2 extends node {
	
	public $template  = '<?php
require_once(#{
$_connection=node::findParent("connection");
return var_export(isset($_connection)?
  (($_a=$_connection->getAttribute("name"))?$_a:"connection.php"):"connection.php", true);
});
class @{$name} extends Zend_Db_Table_Abstract
{
    protected $_name = \'@{isset($table)?$table:$name}\';#{
      if(isset($primaryKey) && $primaryKey!="")
        if(strpos($primaryKey,",")!==false) 
          return "\\n    protected $\\_primary = ".var_export(explode(",",$primaryKey),true).";";
        else
          return "\\n    protected \\$_primary = ".var_export($primaryKey,true).";";
    }
    protected $_dependentTables = ${isset($dependentTables)&&($dependentTables!="")?explode(",",$dependentTables):null};
    protected $_referenceMap    = array(
    @{@node::join("\\n",$_nodes["table_reference"])}
    );
}
' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_nodes ["#parent" ] = $e ->parentNode->getAttribute ("name" );
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$template  = isset  ($this ->template)?$this ->doTemplate ($this ->template,$values ):"" ;
		createFile ("application/model/"  . $e ->getAttribute ("name" ) . ".php" ,$template );
		return $this ->encodeEmpty ();
	}
}
