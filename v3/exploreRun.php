<?php
require_once("Achachi.php");
$params=$_GET;
echo AchachiExpression::evaluateS($_POST["code"], $params );
