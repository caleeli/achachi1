<?php
class node_component extends node {
	
	public $template  = '/*@{$name}*/
class @{$_class} extends node_xmlcomponent {
  public $transparent=${$transparent};
  public $precode=${@$precode};
}' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		/*@{$name}*/
		global $baseNode ;
		$baseNode [$e ->getAttribute ("name" )] = $e ;
		$_tag  = $e ->getAttribute ("name" );
		$_class  = node:: getClassName ($_tag );
		$values ["_class" ] = $_class ;
		if  (!isset  ($values ["transparent" ]))$values ["transparent" ] = false;
		if  (strcasecmp ($values ["transparent" ],"false" ) == 0 )$values ["transparent" ] = false;
		$template  = $this ->doTemplate ($this ->template,$values );
		node:: createTagClass ($_tag ,$_class ,$template );
		print  ("Se crea componente: "  . $e ->getAttribute ("name" ) . "<br />" );
		return $this ->encodeEmpty ();
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$template  = isset  ($this ->template)?$this ->doTemplate ($this ->template,$values ):"" ;
		return $this ->encodeEmpty ();
	}
}
