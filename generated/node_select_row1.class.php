<?php

class node_select_row1 extends node {
	
	public $template  = '        try {
          require_once("../model/@{$model}.php");
          $table=new @{$model}();
          $select=$table->select();
          @{implode("\\n          ",$_where)}
          @{isset($var)?$var:(\'$\'.\'row\')}=$table->fetchRow($select);
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
