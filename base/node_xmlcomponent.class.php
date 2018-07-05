<?php
class node_xmlcomponent extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		print  ("Building component ("  . $e ->tagName . ") "  . @$e ->getAttribute ("name" ) . ":<br />" );
		if  (!empty  ($this ->precode)) {
			foreach  ($values as $_ => $__ )$$_  = $__ ;
			eval  ($this ->precode);
			$values  = &$this ->values;
		}
		//print("<pre>");var_dump($values);print("</pre>");
		$_r  =node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_e" ] = $e ;
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$values ["_content" ] = node:: join ("" ,$_r );
		$values ["_values" ] = &$values ;
		global $baseNode ;
		//clone and import $baseNode[$e->tagName]
		$e2  = importNode ($baseNode [$e ->tagName],$e ->ownerDocument);
		if($e2->getAttribute('engine')){
			$this ->core->engine = $e2->getAttribute('engine');
		}
		$this ->core->evalChildNodes ($e2 ,$values );
		$fors  = $e2 ->getElementsByTagName ("foreach" );
		while  ($fors ->length>0 ) {
			$ch  = $fors ->item (0 );
			$this ->makeFromNode ($ch );
			$fors  = $e2 ->getElementsByTagName ("foreach" );
		}
		$ifs  = $e2 ->getElementsByTagName ("if" );
		while  ($ifs ->length>0 ) {
			$ch  = $ifs ->item (0 );
			$this ->makeFromNode ($ch );
			$ifs  = $e2 ->getElementsByTagName ("if" );
		}
		$result  = array  ();
		foreach  ($e2 ->childNodes as $ch ) {
			//Se clona $ch otra vez para insertarlo en el documento final si perder la referencia en $e2
			$ch2  = $ch ->cloneNode (true);
			$e ->parentNode->insertBefore ($ch2 ,$e );
			$_a  = node:: factory ($ch2 );
			$_r  = $_a ->run ();
			foreach  ($_r as $_r_ ) {
				if  (!$this ->transparent)$_r_ [0 ] = $e ->nodeName;
				$result [] = $_r_ ;
			}
			$e ->parentNode->removeChild ($ch2 );
		}
		//var_dump(previousCall());
		//var_dump($result);
		return $result ;
	}
}
