<?php
class node_select_table extends node {
	
	public $template  = '        try {
          require_once("../model/@{$model}.php");
          $_table=new @{current(explode(".",$model))}();
          $select=$_table->select();
          @$_limit = @{isset($limit)&&($limit!="")?(\'"\'.$limit.\'"\'):"null"};
          @$_offset = @{isset($offset)&&($offset!="")?(\'"\'.$offset.\'"\'):"null"};
          @$_order = @{isset($order)&&($order!="")?(\'"\'.$order.\'"\'):"null"};
          $select->limit($_limit,$_offset);
          if(isset($_order) && (trim($_order)!="")) $select->order($_order);
          @{isset($columns)&&($columns!="")?\'$\'.\'select->from($_table,\'.$columns.\');\':\'\'}
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
		$criteria  = explode (";" ,$criteria );
		$_where  = array  ();
		foreach  ($criteria as $c )if  ($c != "" ) {
			list  ($field ,$var ) = explode ("=" ,$c );
			if  ( ($field != "" ) &&  ($var != "" ))$_where [] = '$select->where("'  . $field  . '=?",'  . $var  . ');' ;
		}
		/* agrega una variable al template */
		$values ["_where" ] = $_where ;
		/*Procesa las columnas*/
		if  (isset  ($values ["columns" ]) &&  ($values ["columns" ])) {
			$columns  = 'array(' ;
			$columns0  = $values ["columns" ];
			//Reduce ""
			while  (preg_match ('/"([^"]+|\\\\"|"")*"/' ,$columns0 )) {
				$columns0  = preg_replace_callback ('/"([^"]+|\\\\"|"")*"/' ,create_function ('$m' ,'return str_repeat(" ",strlen($m[0]));' ),$columns0 );
			}
			//Reduce ''
			while  (preg_match ('/\'([^\']+|\\\\\'|\'\')*\'/' ,$columns0 )) {
				$columns0  = preg_replace_callback ('/\'([^\']+|\\\\\'|\'\')*\'/' ,create_function ('$m' ,'return str_repeat(" ",strlen($m[0]));' ),$columns0 );
			}
			//Reduce ()
			while  (preg_match ('/\([^()]*\)/' ,$columns0 )) {
				$columns0  = preg_replace_callback ('/\([^()]*\)/' ,create_function ('$m' ,'return str_repeat(" ",strlen($m[0]));' ),$columns0 );
			}
			//Match columns
			if  (preg_match_all ('/[^,]+/' ,$columns0 ,$ma ,PREG_PATTERN_ORDER|PREG_OFFSET_CAPTURE)) {
				foreach  ($ma [0 ] as $m ) {
					$x  = trim (substr ($values ["columns" ],$m [1 ],strlen ($m [0 ])));
					//var_dump($x);
					if  (preg_match ('/\w+$/' ,$x ,$c )) {
						$c  = $c [0 ];
						if  ($x == $c )$columns .= var_export ($c ,true) . ',' ; else $columns .= var_export ($c ,true) . '=>'  . var_export (substr ($x ,0 ,-strlen ($c )),true) . ',' ;
					} else {
						$columns .= var_export ($x ,true) . ',' ;
					}
				}
			}
			$columns .= ")" ;
			$values ["columns" ] = $columns ;
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
