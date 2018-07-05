<?php
class node_controller extends node {
	
	public $template  = '<?php
/** Zend_Controller_Action */
require_once \'Zend/Controller/Action.php\';
require_once \'Zend/Loader.php\';
/**
 * @{@$descripcion}
 * @author @{@$author}
 */
class @{UpperCamelCase($name)}Controller extends Zend_Controller_Action
{
@{@node::join("\\n",$_nodes[\'method\'])}
@{@node::join("\\n",$_nodes[\'action\'])}
@{@node::join("\\n",$_nodes[\'rule\'])}
@{@node::join("\\n",$_nodes[\'call\'])}
}
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
		createFile ("controllers/"  . UpperCamelCase ($e ->getAttribute ("name" )) . "Controller.php" ,$template );
		return $this ->encodeEmpty ();
	}
}
