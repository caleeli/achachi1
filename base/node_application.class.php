<?php
class node_application extends node {
	
	public $template  = '' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		chdir (ACH_PATH);
		createDir (ACH_OUTPUT_PATH . '/'  . $e ->getAttribute ("name" ));
		copyDir (ACH_PATH . "/structure/zend" ,ACH_OUTPUT_PATH . '/'  . $e ->getAttribute ("name" ));
		chdir (ACH_OUTPUT_PATH . '/'  . $e ->getAttribute ("name" ));
		/*@{$main}*/
		global $PARAMS ;
		if  (isset  ($PARAMS ["run" ]) && $PARAMS ["run" ])if  ($e ->getAttribute ("main" ))print  ("<script>window.location.href=("  . json_encode (ACH_URI_BASE . '/'  . $e ->getAttribute ("name" ) . '/html/index.php/'  . $e ->getAttribute ("main" )) . ")</script>" );
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$template  = isset  ($this ->template)?$this ->doTemplate ($this ->template,$values ):"" ;
		return $this ->encodeEmpty ();
	}
}
