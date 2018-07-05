<?php
global $preguntas;
global $usuarios;
require_once("uDB.php");
$preguntas=new uDB('db/preguntas');
$usuarios=new uDB('db/usuarios');
$p=rand(0,count($preguntas)-1);
for($p=0;$p<count($preguntas);$p++){

$pregunta=$preguntas[$p]->getValue();
//$url=dirname(curPageURL());
$pregunta['imagen'] = $pregunta['imagen'];
/*$id=@$_REQUEST['id'];
if(@$_REQUEST['puntuar']=="1" && isset($usuarios[$id])){
  if(!isset($usuarios[$id]['puntaje']))$usuarios[$id]['puntaje']=0;
  $usuarios[$id]['puntaje']=$usuarios[$id]['puntaje']+1;
}*/
/*
function curPageURL() {
 return "images";
 $pageURL = 'http';
 if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}*/

ob_start();
?>
var res=<?php echo json_encode($pregunta); ?>;
dibujaPregunta($pregunta,$respuestas,$imagen,res);
<?php
if(@$_REQUEST['puntuar']=="1" && isset($usuarios[$id])){
  if(rand(0,5)==0){
    ?>
    var valor=parseInt($(".bomb").html());
    valor++;
    $(".bomb").html(valor);
    <?php
  }
  if(rand(0,5)==0){
    ?>
    var valor=parseInt($(".glove").html());
    valor++;
    $(".glove").html(valor);
    <?php
  }
}
?>

<?php
$code=ob_get_contents();
ob_end_clean();
//echo json_encode($code);
file_put_contents("mobile/preguntas/$p.txt", $code);
copy($pregunta['imagen'],'mobile/'.$pregunta['imagen']);
}
