<?php
error_reporting(E_ALL);
require("simp.php");
function parseVariables(&$cadena,&$vars) {

	$cant_vars = strlen($vars);
	
	$cadena = str_replace(" ","",$cadena);	// saco espacios en blanco
	$cadena = strtoupper($cadena);			// paso a mayusculas
	
	$reg_neg = "([~|¬][".$vars."])";			// Expresion regular para cambiar las negadas
	$reg_no_neg = "([".$vars."])";			// Expresion regular para cambiar las no negadas
	
	// Reemplazos	
	$cadena = ereg_replace($reg_neg, "0", $cadena);

	$cadena = ereg_replace($reg_no_neg, "1", $cadena);
	$cadena = str_replace("+"," ",$cadena);
	// all done

}
$cad='A';
$old_cad = $cad;
parseVariables($cad, $vars);
QM($cad,$vars);




?>