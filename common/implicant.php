<?php
require_once("listOfImplicant.php");
class Implicant {

	var $x;			// Implicante almacenado en un string de la forma "01-"
	var $mark;		// valor boolean que determina si el implicante fue simplificado o no
	var $cubiertos;

	/* Constructor de la clase */
	function Implicant(){
	/*Constructor
	* Inicialza los atributos:
	* $x 			contiene la representacion interna del implicante
	* $mark			booleano que determina si el implicante es representado por
	* 				alguna simplificacion
	* $cubiertos	Es una lista de los implicantes que son representados por este.
	*  */
		$this->x="";
		$this->mark=false;
		$this->cubiertos=array();
	}

	/* Métodos públicos */
	function setImplicant($y) {
	/* Metodo que recibe un string con formato booleano y lo guarda en la
	* representacion interna del implicante.
	* * */
		$this->x=$y;
	}

	function getImplicant() {
	/* Devuelve el implicante en si mismo */
		return $this;
	}

	function mark() {
	/* Este implicante ya fue representado por algun otro con menor
	* número de variables. Por este motivo es "marcado"
	* * */
		$this->mark = true;
	}

	function isMarked() {
	/* Ha sido representado por otro? 
	* */
		return $this->mark;
	}	

	function igual($y) {
	/* Retorna verdadero si dos implicantes son exactamente iguales
	* de lo contrario devuelve falso */
		return $this->x == $y->asBoolean();
	}	
	
	function peso() {
	/* peso del implicante, es decir cuantos "1" tiene (variables no negadas)
	* * */
		return substr_count($this->x,"1");
	}


	function do_simplify(&$y) {
	/* Simplifica si es posible al implicante con el ingresado como parametro.
	* Retorna el nuevo implciante simplificado
	* De no ser posible la simplificación retorna false
	* * */
		// $len es global, es el numero de variables booleanas
		global $len;
		if ($this->simplify($y)) {
			$imp1 = $this->x;
			$imp2 = $y->asBoolean();
			$ret = new Implicant;				// nuevo implicante para el retorno
			$vret = $imp1;
			$i = 0;
			while (($vret[$i] == $imp2[$i])&&($i<$len)) {
					$i++; 
			}
			if ($i < $len) {
			 	$vret[$i] = "-";
			}					// este es distinto
			$ret->setImplicant($vret);
			$ret->addCubiertos($this);	// almacenamiento recursivo de los cubiertos
			$ret->addCubiertos($y);
			// Se marcan porque fueron simplificados
			$y->mark();
			$this->mark();
		} else {
			$ret = false;
		}
		return $ret;
	}

	function cubre($y) {
	/* Retorna verdadero si el implicante cubre al parametro */
		// $len es global, es el numero de variables booleanas
		$list_cubiertos = $this->getCubiertos();
		return $list_cubiertos->find($y);
	}
	
	function cantVars() {
	/* Devuelve la cantidad de variables utilizadas en el implicante.
	* Esta funcion es utiul para detemrinar que implicante es optimo, ya que
	* queremos lograr la máxima representación
	* * */
		global $len;
		$tst = $this->x;
		$cont = 0;
		for ($i=0;$i<$len;$i++){
			if ($tst[$i] != "-") {
			    $cont++;
			}
		} // while
		return $cont;
	}
	
	function addCubiertos($cubierto) {
	/* Agrega el parámetro a la lista de cubiertos
	* * */
		array_push($this->cubiertos,$cubierto);
	}
	
	function getCubiertos() {
	/* Devuelve una listOfImplcants con los implciantes cubiertos.
	* * */
		$retorno = new listOfImplicant();
		if (sizeof($this->cubiertos) > 0) {
			foreach ($this->cubiertos as $cubierto) {
				$retorno->push($cubierto);
				$retorno->merge($cubierto->getCubiertos());
			}
		}
		return $retorno;
	}
	
	

	/* Metodos de representación en pantalla */
	function asBoolean() {
	/* Retorna el implicante en representación booleana
	* * */
		return $this->x;
	}

	function asVars($v) {
	/* Dado un string con las variables utilizadas, devuelve un string con 
	* la representación del implicante
	* */	
		global $len;
		$ret = "";
		$cad = $this->x;
		for ($i=0;$i<$len;$i++) {
			switch($cad[$i]){
				case "-":
					$ret .= "";		break;
				case "0": 
					$ret .= "~";
				case "1": 
					$ret .= $v[$i];	break;
			}
		}
		return $ret;
	}
	
	
	/* Metodos privados */
	function simplify($y) {
	/* Verifica si pueden ser simplificados
	* Es decir si difieren sólo de un bit
	* * */
		global $len;					// $len es global, es el numero de variables booleanas
		$dist = $len;					// se supone que todas las variables osn distintas
		$imp1 = $this->x;
		$imp2 = $y->asBoolean();
		$i = 0;
		while ($i<$len) {
			if ($imp1[$i]==$imp2[$i]) {	// si son iguales, luego hay 1 menos que es distinta
				$dist--;
			}
			$i++;
		}
		return ($dist <= 1);			// si entre los dos difieren de 1 o de ninguna retorna True
	}
}

?>