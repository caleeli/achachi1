<?php

class node_file1 extends node {
	
	public $template  = '@{node::join("",$_)}' ;
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		//@{$name}
		$_r  = node:: run ();
		$values ["_" ] = $_r ;
		$template  = $this ->doTemplate ($this ->template,$values );
		//@{$preprocess}
		if  (isset  ($values ["preprocess" ]) && trim ($values ["preprocess" ]) != "" ) {
			$np  = $e ->ownerDocument->createElement ($values ["preprocess" ]);
			$np ->appendChild ($e ->ownerDocument->createTextNode ($template ));
			$e ->appendChild ($np );
			$nnp  = node:: factory ($np );
			$template  = node:: join ("" ,$nnp ->run ());
			$e ->removeChild ($np );
			unset  ($np );
			unset  ($nnp );
		}
		createFile ($values ["name" ],$template );
		return $this ->encodeEmpty ();
	}
}
