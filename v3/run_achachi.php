<?php
global $_evaluateFile;
require_once("Achachi.php");
$__call = debug_backtrace();
$_evaluateFile=$__call[0]['file'];
ACHACHI::$FILENAME=$__call[0]['file'];
$__content = file($__call[0]['file']);
for($__i=0;$__i<$__call[0]['line'];$__i++){
  $__content[$__i]='';
}
return AchachiExpression::evaluateS(implode('', $__content), (!is_array(@$params)?array():$params) );