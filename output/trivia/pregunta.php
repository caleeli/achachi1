<?php
global $preguntas;
global $usuarios;
require_once("uDB.php");
//require_once("preguntas.php");
//require_once("usuarios.php");
$preguntas=new uDB('db/preguntas');
$usuarios=new uDB('db/usuarios');
$p=rand(0,count($preguntas)-1);
$pregunta=$preguntas[$p]->getValue();
$url=dirname(curPageURL());
$pregunta['imagen'] = $url . "/" . $pregunta['imagen'];
$id=@$_REQUEST['id'];
if(@$_REQUEST['puntuar']=="1" && isset($usuarios[$id])){
  if(!isset($usuarios[$id]['puntaje']))$usuarios[$id]['puntaje']=0;
//  $usuarios[$id]['puntaje']++;
  $usuarios[$id]['puntaje']=$usuarios[$id]['puntaje']+1;
  //uDB::save("usuarios", $usuarios);
}
function curPageURL() {
 $pageURL = 'http';
 if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

ob_start();
?>
var res=<?php echo json_encode($pregunta); ?>;
$pregunta.html(res.pregunta);
$respuestas.html("\n");
var ind=["A","B","C","D","E","F","G"];
window.correcta=res.respuestas[res.correcta];
window.data=res;
var sres=shuffle(res.respuestas);
for(var r in sres){
  $respuestas.append("<div class='respuesta' onclick='responder(this);return false;'><span> "+ind[r]+" </span><a>"+res.respuestas[r]+"</a></div>");
}
$imagen.css("background-image","url("+res.imagen+")");
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
echo $_REQUEST['callback'],'(',json_encode($code),');';