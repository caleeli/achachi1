<?php

class node_ext_attribute extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		require_once  (ACH_LIBRARY_PATH . "/extjslib2.class.php" );
		return extjs:: create ($e );
	}
}
