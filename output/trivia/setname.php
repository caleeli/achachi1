<?php
global $usuarios;
require_once("uDB.php");
//require_once("usuarios.php");
$usuarios=new uDB('db/usuarios');
$id=$_REQUEST['id'];
if(isset($usuarios[$id])){
  $usuarios[$id]["nombre"]=$_REQUEST['nombre'];
  //uDB::save("usuarios", $usuarios);
} else {
  $usuarios[$id]=array("nombre"=>$_REQUEST['nombre']);
  //uDB::save("usuarios", $usuarios);
}