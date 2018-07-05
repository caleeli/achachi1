<?php

class node_insert_row1 extends node {
	
	public $template  = '        try {
          require_once("../model/@{$model}.php");
          $table=new @{$model}();
          @{isset($var/*$values*/)?$var:\'$values\'}=array();
          @{implode("\\n          ",$values)}
          @{@node::join("\\n          ",$_)}
          $table->insert(@{isset($var/*$values*/)?$var:\'$values\'});
        } catch (Exception $e) {
          exit("{success:false,errors:[{msg:".json_encode($e->getMessage())."}]}");
        }
' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		if  (!isset  ($values ["data" ]) || !$values ["data" ])$values ["data" ] = '$data' ;
		$var  = $e ->getAttribute ("var" );
		if  (!$var )$var  = '$values' ;
		$data  = $e ->getAttribute ("values" );
		$data  = explode (";" ,$data );
		$dataSet  = array  ();
		foreach  ($data as $c ) {
			list  ($field ,$value ) = explode ("=" ,$c );
			if  ( ($field != "" ) &&  ($value != "" ))$dataSet [] = $var  . '["'  . $field  . '"]='  . $value  . ';' ;
		}
		/* agrega una variable al template */
		$values ["values" ] = $dataSet ;
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$template  = isset  ($this ->template)?$this ->doTemplate ($this ->template,$values ):"" ;
		return $this ->encode ($template );
		return $this ->encodeEmpty ();
	}
}
