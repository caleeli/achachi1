<?php

class node_select_table1 extends node {
	
	public $template  = '        try {
          require_once("../model/@{$model}.php");
          $_table=new @{current(explode(".",$model))}();
          $select=$_table->select();
          @$_limit = @{isset($limit)&&($limit!="")?(\'"\'.$limit.\'"\'):"null"};
          @$_offset = @{isset($offset)&&($offset!="")?(\'"\'.$offset.\'"\'):"null"};
          @$_order = @{isset($order)&&($order!="")?(\'"\'.$order.\'"\'):"null"};
          $select->limit($_limit,$_offset);
          if(isset($_order) && (trim($_order)!="")) $select->order($_order);
          $select->from($_table);
          @{isset($columns)&&($columns!="")?implode("\\n          ",$columns):\'\'}
          @{implode("\\n          ",$_where)}
          @{isset($var)?$var:(\'$\'.\'rows\')} = $_table->fetchAll($select);
          @{@node::join("\\n          ",$_)}
        } catch (Exception $e) {
          exit("{success:false,errors:[{msg:".json_encode($e->getMessage())."}]}");
        }
' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		/*@{$criteria}*/
		$criteria  = $e ->getAttribute ("criteria" );
		$values ["_where" ] = zparser:: criteria ($criteria );
		/*Procesa las columnas*/
		if  (isset  ($values ["columns" ]) &&  ($values ["columns" ])) {
			$values ["columns" ] = zparser:: columns ($values ["columns" ]);
		}
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
