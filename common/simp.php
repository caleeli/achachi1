<?PHP
// Clases utilizadas
require_once("implicant.php");
require_once("listOfImplicant.php");
require_once("template.class.php");
$page = new template("temp_simp.php");
$testttt= new Implicant();
$len= 0;
// largo global. Cantidad de variables usadas
// futura clase
function parsear($cadena) {
	global $len;
	$expr = explode(" ",$cadena);			// Se explota el string con la aparición de 1 espacio
	$len  = strlen($expr[0]);				// luego el tamaño de los implicantes (cantidad de variables será el largo de una de esas cadenas
	$temp = new Implicant();					// Se crea un implicante para uso temporal
	$implicantes = array();					// Se crea un array para uso temporal
	
	foreach ($expr as $imp) {
		$temp-> setImplicant($imp);			// se setea el implicante con los valores
		array_push($implicantes,$temp);		// Se agrega una copia del implicante
	}
	return $implicantes;	
}

function &agregar($implicantes) {		// crea las Bolsas con las Listas de Implicantes
	$temp = new Implicant;					// Se crea un implicante para uso temporal
	$vector = array();
	$peso = 0;

	while ((sizeof($implicantes) > 0)) {	 // mientras no este vacio...
		$temp = array_pop($implicantes); 	 // extraigo el siguiente implicante a tratar
		$peso = $temp->peso();    			 // calculo el peso del implicante
		$claves = array_keys($vector);		 // Obtengo los grupos actualmente formados
		if (!in_array($peso,$claves)) {		 // Si no existe el grupo, 
			$vector[$peso] = new listOfImplicant;			 // lo creo...
		}
											 // aca estamos seguro de que exite
		$vector[$peso]->push($temp);
	}
	ksort($vector);							 // Ordeno los grupos por peso, de menor a mayor
	return $vector;							 // Devuelvo una Bolsa
}

function &simplificar($bolsa,&$listo) {
	global $contador,$page;
	$simp = false;					// Suponemos que no hay nada por simplificar
	$contador++;
	$page->newLine("Pasada $contador","titulo");
	$retorno = new listOfImplicant;	// Vector de retorno que debe ser reordenado y agrupado
	$i=0;							// indice para contar la cantidad de implicantes analizados de un grupo
	$j=0;							// indice para contar la cantidad de implicantes analizados de un grupo							//
	$imp_key1 = new Implicant;		// Implicante del grupo de peso $key1
	$imp_key2 = new Implicant;		// Implicante del grupo de peso $key2
	reset($bolsa);					// ponemos el indice al comienzo de la Bolsa
	$ListaKeys = array_keys($bolsa);// Obtenemos un vector con los indices de las Bolsas
	reset($ListaKeys);				// Ponemos el indice de de dicho vector al comienzo
	$nomarcados = array();			// aca se guardaran los no simplificados;
	
	$key1 = current($ListaKeys);	// obtengo el peso del Primer grupo
	$key2 = next($ListaKeys);		// obtengo el peso del Segundo grupo, si no hay mas grupos $k2 = false
	while ($key2) {							// mientras haya grupos para comparar
		if (($key2-$key1) == 1) {			// si son grupos adyacentes...
			$bolsa[$key1]->reset();			// vuelvo sus punteros al inicio
			$bolsa[$key2]->reset();			// vuelvo sus punteros al inicio
			while ($imp_key1 = & $bolsa[$key1]->each()) {			// Por cada implicante del grupo de peso $key1 ...
				while ($imp_key2 = & $bolsa[$key2]->each()) {		// hacer con cada Implicante del grupo de peso $key2 ...
					if ($simplificado = $imp_key1->do_simplify($imp_key2)) {	// se simplifican? si es asi
						$retorno->push($simplificado);							// se agrega en el retorno
						$ver = $simplificado->getCubiertos();
						$simp = true;
					}
				}
				$bolsa[$key2]->reset();
			}
			$bolsa[$key1]->reset();
		}
		while ($imp = $bolsa[$key1]->each()) {
			if (!$imp->isMarked()) {
				$retorno->push($imp);
			}
		}
		$key1 = $key2;				// ahora haremos la comparacion del grupo de peso $key2
		$key2 = next($ListaKeys);	// con el siguiente... si hay por supuesto (ver while)
	}
	$bolsa[$key1]->reset();
	while ($imp = $bolsa[$key1]->each()) {
		if (!$imp->isMarked()) {
			$retorno->push($imp);
		}
	}

	$listo = !$simp;
	return $retorno;
}





//temporales
function mostrarPasada($bolsa){
	global $page;
	$cont = 0;
	foreach ($bolsa as $peso => $mostrar) { // se muestran los implicantes agrupados
		$mostrar->reset();
		$cont++;
		while ($imp = $mostrar->each()) {
			$str =  "[$peso] "
					.$imp->asBoolean()
					." ";
			$str .= ($imp->isMarked())? "true":"false";
			$page->newLine($str,"listing".($cont % 2));
		}
	}
}

function mostrarResultadoPasada($simp) {
	global $page;
	while ($imp = $simp->each()) {			// se muestra el resultado de la Pasada
		$page->newLine($imp->asBoolean(),"listing");
	}
}


function obtener_no_ipes($imp,$res) {
	function esta($imp,$list) {
		$ret = false;
		while(!$ret && $imp_c=array_pop($list)){
			$ret = $imp->igual($imp_c);
		} // while
		return $ret;
	}

	$ret = array();
	while($imp1 = array_pop($imp)){
		if (!esta($imp1,$res))
			array_push($ret,$imp1);
	}
	return $ret;
}

	// Obtiene los imp. Primos esenciales y no esenciales
		
function obtener_IPEs($terms,$imps) {
	$ret = new listOfImplicant();
	$ret2 = array();
	$cont = 0;
	foreach ($terms as $t){
		foreach($imps as $imp){
			if ($imp->cubre($t)) {
			    $cont++;
				$ins = $imp;
			}
		}
		if (($cont == 1))
			$ret->push($ins);
		$cont = 0;		
	}
	$ret1 = $ret->asArray();
	$ret2 = obtener_no_ipes($imps,$ret1);
	return array(0=>$ret1,1=>$ret2);
}




function seleccionar_imp_mayor_cobertura($vector) {
	global $len;
	$check = $len;

	while ($implicante = array_pop($vector)) {
		if (($implicante->cantVars()) <= $check) {
		    $imp = $implicante;
			$check = $imp->cantVars();
		}
	}
		return $imp;
}

function obtener_no_cubiertos($imp, $terminos) {
	$vect = array();
	while($term = array_pop($terminos)){
		if (!$imp->cubre($term)) {
		    array_push($vect, $term);
		}
	} // while
	
	return $vect;
}
	
function obtener_no_cubiertosXLista($imps, $terminos) {
	$vect = array();
	while($t = array_pop($terminos)){
		while(!$cub && ($imp = array_pop($imps))){
			$cub = $imp->cubre($t);
		} // while
		if (!$cub)
			array_push($vect, $t);
		} 
	
	return $vect;
}

	
function seleccionar_imp_que_cubra($term,$listaImps) {
	$imp = array_pop($listaImps);
	while(!$imp->cubre($term)){
		$imp = array_pop($listaImps);
	} // while
	return $imp;
}







function QM($cad,$vars) {
	global $len,$page;
	
	$page->newLine("Función Original","wideTitulo");
	$page->newLine("F(".$GLOBALS['vars'].")=".$GLOBALS['old_cad'],"wideListing");
	$page->newLine("Transformada a representacion Binaria...","wideTitulo");
	$page->newLine($cad,"wideListing");

	$listo = false;
	$vect = parsear($cad);			// se transforma en array de imlicantes la entrada string
	$bolsa = & agregar($vect);		// se generan los grupos y se meten en una bolsa
	$terminos = $vect;
	$i=0;	
	while (!$listo) {
		$simp = & simplificar($bolsa,$listo);	// Una pasada completa de simplificacion
		mostrarPasada($bolsa);
		mostrarResultadoPasada($simp);
		$bolsa = & agregar($simp->asArray());		// se prepara para realizar una nueva pasada (si aun !$listo)
	}

	$page->blankLine();
	$page->blankLine();


// funcion1 para buscar IPES

	$final = new listOfImplicant();
	$page->blankLine();
	$page->newLine("Buscando Implicantes Primos Esenciales...","advisory");	
	list($ipes,$noipes) = obtener_IPEs($terminos,$simp->asArray());
	$terms = obtener_no_cubiertosXLista($ipes, $terminos);
	$page->newLine("Eliminando implicantes redundantes...","advisory");	
	while(!empty($terms)){
		$imp = seleccionar_imp_que_cubra(current($terms),$noipes);
		$terms = obtener_no_cubiertos($imp, $terms);
		array_push($ipes,$imp);
	} 
	
	
	$page->newLine("Expresión simplificada","advisory");

	$expr = "F(".$GLOBALS['vars'].")=";
	foreach ($ipes as $imp) { // se muestran los implicantes agrupados
			$expr .= $imp->asVars($vars)." ";
	}
	$expr = trim($expr);
	$expr = str_replace(" "," + ",$expr);
	$page->newLine($expr,"listing1");
	$page->show();
}	


?>