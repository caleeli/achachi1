<?php
global $usuarios;
require_once("uDB.php");
//require_once("usuarios.php");
$usuarios=new uDB('db/usuarios');
$id=$_REQUEST['id'];
if(isset($usuarios[$id])){
  ob_start();
  ?>
    $("#name").val(<?php echo json_encode($usuarios[$id]["nombre"]) ?>);
    puntaje=<?php echo json_encode(@$usuarios[$id]["puntaje"]*1) ?>;
    $(".points").html(puntaje);
  <?php
  $code=ob_get_contents();
  ob_end_clean();
  echo $_REQUEST['callback'],'(',json_encode($code),');';
} else {
  ob_start();
  ?>
  <?php
  $code=ob_get_contents();
  ob_end_clean();
  echo $_REQUEST['callback'],'(',json_encode($code),');';
}