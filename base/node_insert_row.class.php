<?php
class node_insert_row extends node {
	
	public $template  = '        try {
          require_once("../model/@{$model}.php");
          $table=new @{$model}();
          @{$data/*$data*/}=array();
          @{implode("\\n          ",$dataSet)}
          @{@node::join("\\n          ",$_)}
          $table->insert(@{$data/*$data*/});
        } catch (Exception $e) {
          exit("{success:false,errors:[{msg:".json_encode($e->getMessage())."}]}");
        }
' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		if  (!isset  ($values ["data" ]) || !$values ["data" ])$values ["data" ] = '$data' ;
		$data  = $e ->getAttribute ("dataSet" );
		$data  = explode (";" ,$data );
		$dataSet  = array  ();
		foreach  ($data as $c ) {
			list  ($field ,$var ) = explode ("=" ,$c );
			if  ( ($field != "" ) &&  ($var != "" ))$dataSet [] = '$data["'  . $field  . '"]='  . $var  . ';' ;
		}
		/* agrega una variable al template */
		$values ["dataSet" ] = $dataSet ;
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
