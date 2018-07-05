<?php
class listOfImplicant {
	// lista LIFO (Last In - First Out, concepto de Pila)
	var $lista;		// lista
	var $pointer; 	// $puntero a la lista;
	var $size;		// tamaño

	function listOfImplicant() {
		$this->lista = array();
		$this->pointer = 0;
		$this->size = 0;
	}
	function merge($lista){
		$this->lista = array_merge($this->lista,$lista->lista);
		$this->size += $lista->size();
	}

	function find($x) {
		$this->reset();
		$esta = false;
		if (!$this->isEmpty()) { 
			$ref = &$this->lista;
			for ($i=0; $i < $this->size; $i++) {
				$esta = $x->igual($ref[$i]);
				if ($esta) { break; }
			} 
		}
		return $esta;
	}
	
	function push(&$x) {
		if (!$this->find($x)) {
			array_push($this->lista,$x);
			$this->size++;
		}
	}
	
	function &top() {
		return $this->lista[$this->size--];
	}
	
	function &each() {
		if ($this->pointer >= $this->size) {
			$temp = false;
		} else {
			$temp =& $this->lista[$this->pointer];
			$this->pointer++;
		}
		return $temp;
	}
	
	function pop() {
		$this->size--;
		return array_pop($this->lista); 
	}
	
	function isempty() {
		return ($this->size == 0);
	}
	
	function &act() {
		return $this->lista[$this->pointer];
	}
	
	function sig() {
		return $this->pointer++;
	}	
	
	function reset() {
		reset($this->lista);
		$this->pointer = 0;		
		return;
	}
	function size() {
		return $this->size;
	}
	function &asArray() {
		return $this->lista;
	}
}
?>