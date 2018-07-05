<?php

$pi = atan(1)*4;
//Ancho de la hoja
$w = 200;
//Espacio entre las hojas
$e = 50;
//Alto de la hoja
$h = 300;

/**
 * Calcula constantes para Y=A*X^3+B*X^2+C*X;
 * donde Y = posicion
 * y     X = angulo
 */
function formula1($q,$k){
  global $pi,$w,$e;
  $a = ($e*$q*($k*$k - $k*$q + $pi*($q - $pi)) + $w*($k*$k*(2*$q - $pi) + $k*($pi*$pi - 2*$q*$q) + $pi*$q*($q - $pi)))/($pi*$k*$q*($k*$k - $k*($q + $pi) + $pi*$q)*($q - $pi));
  $b = ($e*$q*($k*$k*$k - $k*$q*$q + $pi*($q + $pi)*($q - $pi)) + $w*($k*$k*$k*(2*$q - $pi) + $k*($pi*$pi*$pi - 2*$q*$q*$q) + $pi*$q*($q + $pi)*($q - $pi)))/($pi*$k*$q*($pi - $q)*($k*$k - $k*($q + $pi) + $pi*$q));
  $c = ($e*$q*$q*($k*$k*$k - $k*$k*$q + $pi*$pi*($q - $pi)) + $w*($k*$k*$k*(2*$q*$q - $pi*$pi) + $k*$k*($pi*$pi*$pi - 2*$q*$q*$q) + $pi*$pi*$q*$q*($q - $pi)))/($pi*$k*$q*($k*$k - $k*($q + $pi) + $pi*$q)*($q - $pi));
  return Array($a,$b,$c);
}

/**
 * Calcula constantes para Y=A*X^3+B*X^2+C*X;
 * donde Y = angulo
 * y     X = posicion
 */
function formula2($q,$k){
  global $pi,$w,$e;
  //var_dump($pi,$w,$e);
  $a = -($e*($k - $q - $pi) + 2*$w*($k - $q))/($e*$w*($e + $w)*($e + 2*$w));
  $b = ($e*($k - 2*$q - $pi) + 3*$w*($k - $q))/($e*$w*($e + $w));
  $c = ($e*$e*$e *$q - $e*$e *$w*($k - 5*$q - $pi) - $e*$w*$w *(4*$k - 8*$q - $pi) - 4*$w*$w*$w *($k - $q))/($e*$w*($e + $w)*($e + 2*$w));
  return Array($a,$b,$c);
}

function posicion($angle,$q,$k){
  list($A,$B,$C) = formula1($q,$k);
  //var_dump($A,$B,$C);die;
  $x=$angle;
  return $A * $x*$x*$x + $B * $x*$x + $C * $x;
}

function angulo($x,$q,$k){
  list($A,$B,$C) = formula2($q,$k);
  $x=$x;
  return $A * $x*$x*$x + $B * $x*$x + $C * $x;
}


  $_image = @imagecreate(2*$w+$e+200, $h);
  $blanco = imagecolorallocate($_image, 255, 255, 255);
  $negro = imagecolorallocate($_image, 0, 0, 0);
  $degrade = array();
  for($_j=0;$_j<100;$_j++):
    $_co=127+ceil(128/100*$_j);
    $degrade[$_j]=imagecolorallocate($_image, $_co, $_co, $_co);
  endfor;
  imagecolortransparent($_image, $negro);
  imagefill($_image, 0, 0, $negro);
  
//Bucle para la esq inferior derecha de la hoja
$l=$w*2+$e;
for($x=0;$x<=$l;$x=$x+10){
  $angle = $x/$l*$pi;
  $x1 = posicion($angle,6/8*$pi,6.3/8*$pi);
  $_x = 2*$w+$e-$x;
  $_x1 = 2*$w+$e-$x1;
  imageline($_image, $_x, $h, $_x1, 0, $blanco);
}

header("Content-type: image/gif");
imagegif($_image);

