<?php

class node_delete_row extends node {
	
	public $template  = '        try {
          require_once("../model/@{$model}.php");
          $table=new @{$model}();
          $select=$table->select();
          @{implode("\\n          ",$_where)}
          @{isset($var/*$row*/)?$var:(\'$\'.\'row\')}=$table->fetchRow($select);
          @{@node::join("\\n          ",$_)}
          @{isset($var)?$var:(\'$\'.\'row\')}->delete();
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
