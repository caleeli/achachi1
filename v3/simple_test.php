<?php
function into($a){
	return $a;
}
function imprime($res){
	global $TPLS;
	if(!empty($TPLS[@$_GET["template"]])){
		echo AchachiExpression::evaluateS($TPLS[@$_GET["template"]]["tpl"], $_GET);
	} else {
		print($res);
	}
}
 ini_set('display_errors', 'on');
 $params=$_GET;
 return imprime(require_once("run_achachi.php")); ?>
//LOADING LIBRARIES...
$template{//xample
//buaajajajaja
}
$template{//==Class==
@{"<"."?php"}
/**
 * Class @{$name}
 */
class @{$name} {
$template{//==NewFunction==
	/**
	 * Function @{$name}
	 */
	function @{$name}(){
$template{//==render==
			//Codigo para renderizar.
			$this->renderer->clear();
			$this->renderer->result=this;
		}
	}
}
}
}
//EXECUTING...
#Class{
name:abc
#NewFunction{
name:123
#render{}
}
}
echo "listo!!";
