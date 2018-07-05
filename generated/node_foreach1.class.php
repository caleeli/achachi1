<?php
class node_foreach1 extends node {
	
	public $template  = '<?php
' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		/**
  /**
   * Reemplaza el foreach por los componentes que contiene.
   * Se lo utiliza junto con component.
   * El contenido del bucle se asigma a través del atributo values (Expresion PHP)
   * El contexto de variables para la expresion es la misma que para la instancia del componente.
   * Se usa el componente "field" (que se convierte en array) para definir los valores que pueden ser usados por foreach. Ej.
   * <component name="xComponent"><foreach values="${$_nodes['field']}"><table name="@{$name}" /></foreach></component>
   * <xComponent ><field name="table1"/><field name="table2"/></xComponent>
   *
   * Se puede usuar ademas de field cualquier otro nodo que no este definido, ya que por defecto un nodo
   * no definido se convierte en un array.
   */ $vars  = array  ();
		@eval  ('$vars='  . $e ->getAttribute ("values" ) . ';' );
		//var_dump($vars);
		//krumo($vars);
		$_first  =true;
		if  (isset  ($vars ) && !empty  ($vars ))foreach  ($vars as $k => $vfield ) {
			$encoded  = is_array ($vfield ) && count ($vfield ) == 2 ;
			if  ($encoded )$v  = $vfield [1 ]; else $v  = array  ();
			$v ["_first" ] = $_first ;
			if  ($encoded )$v ["_nodeName" ] = $vfield [0 ]; else $v ["_nodeName" ] = $k ;
			if  ($encoded )$v ["_value" ] = $v ; else $v ["_value" ] = $vfield ;
			$v ["_nodeValue" ] = $vfield ;
			$v ["_e" ] = $e ;
			$e2  = $e ->cloneNode (true);
			//var_dump($v);
			$this ->core->evalChildNodes ($e2 ,$v );
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
			foreach  ($e2 ->childNodes as $ch ) {
				//Se clona $ch otra vez para insertarlo en el documento final si perder la referencia en $e2
				$ch2  = $ch ->cloneNode (true);
				$e ->parentNode->insertBefore ($ch2 ,$e );
			}
			$_first  = false;
		}
		$e ->parentNode->removeChild ($e );
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
