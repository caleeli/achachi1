<?php
class node_file extends node {
	
	public $template  = '@{node::join("",$_)}' ;
	
	public function run () {
        //echo "FILE======================================\n";
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
		$cs  = get_defined_constants (true);
		if  (isset  ($cs ["user" ]))$cs  = $cs ["user" ];
		$name  = $values ["name" ];
		foreach  ($cs as $c => $v )$name  = preg_replace ('/\$'  . $c  . '\b/' ,$v ,$name );
		createFile ($name ,$template );
		return $this ->encodeEmpty ();
	}
}
