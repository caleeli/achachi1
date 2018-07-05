<?php

class node_update_row extends node {
	
	public $template  = '        try {
          require_once("../model/@{$model}.php");
          $table=new @{$model}();
          $select=$table->select();
          @{implode("\\n          ",$_where)}
          @{isset($var/*$row*/)?$var:(\'$\'.\'row\')}=$table->fetchRow($select);
          @{implode("\\n          ",$values)}
          @{@node::join("\\n          ",$_)}
          @{isset($var)?$var:(\'$\'.\'row\')}->save();
        } catch (Exception $e) {
          exit("{success:false,errors:[{msg:".json_encode($e->getMessage())."}]}");
        }
' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		/*@{$criteria}*/
		$criteria  = $e ->getAttribute ("criteria" );
		$criteria  = explode (";" ,$criteria );
		$where  = array  ();
		foreach  ($criteria as $c )if  ($c && strpos ($c ,"=" )>0 ) {
			list  ($field ,$var ) = explode ("=" ,$c );
			if  ( ($field != "" ) &&  ($var != "" ))$where [] = '$select->where("'  . $field  . '=?",'  . $var  . ');' ;
		}
		/* agrega una variable al template */
		$values ["_where" ] = $where ;
		/*Prepare the DATA SET*/
		if  (!isset  ($values ["var" ]) || !$values ["var" ])$var  = '$row' ; else $var  = $values ["var" ];
		$data  = $e ->getAttribute ("values" );
		$data  = explode (";" ,$data );
		$dataSet  = array  ();
		foreach  ($data as $c ) {
			list  ($field ,$val ) = explode ("=" ,$c );
			if  ( ($field != "" ) &&  ($val != "" ))$dataSet [] = $var  . '->'  . $field  . '='  . $val  . ';' ;
		}
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
