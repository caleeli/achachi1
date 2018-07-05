<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="pure-min.css">
<style>
form{
  background-color:rgba(230,230,230,0.7);
  font-family:sans-serif;
}
form div{
  display:inline-block;
  width:100%;
}
form div label{
  display:inline-block;
  vertical-align:middle;
  width:30%;
}
form div span{
  display:inline-block;
  vertical-align:middle;
  width:65%;
}
form input {
  width:100%;
  font:inherit;
}
form textarea {
  width:100%;
  font:inherit;
}
</style>
</head>
<body>
<form class="pure-form pure-form-aligned" action="?id=<?php echo @$_GET['id'] ?>" method="post" enctype="multipart/form-data">
<fieldset>
<?php
$struct=array(
  "nombre"=>array("label"=>"Nombre","type"=>"input"),
  "descripcion"=>array("label"=>"Descripcion","type"=>"input"),
  "imagen"=>array("label"=>"Imagen","type"=>"input")
);
global $personajes;
require_once("uDB.php");
//require_once("personajes.php");
require_once("uDB.php");
$personajes=new uDB('db/personajes');

if(isset($personajes[@$_GET['id']])){
  $rec=$personajes[$_GET['id']];
}
define('UPLOAD_BASE', 'img/');
if(count($_POST)){

  /** UPLOADS **/
  foreach($_FILES as $fieldName => $ff){
    if ($ff["error"] > 0) {
      echo "Return Code: " . $ff["error"] . "<br>";
      unset($_POST[$fieldName]);
    } elseif(!empty($ff["name"])) {
      $fname=$ff["name"];
      $index=0;
      while(file_exists(UPLOAD_BASE . $fname)) {
        $index++;
        $fname=$index . $ff["name"];
      }
      move_uploaded_file($ff["tmp_name"],
        UPLOAD_BASE . $fname);
      $_POST[$fieldName]=UPLOAD_BASE . $fname;
    } else {
      unset($_POST[$fieldName]);
    }
  }
  if(!isset($rec)){
    if(empty($_GET['id'])){
      $_GET['id']=count($personajes);
    }
    $personajes[$_GET['id']]=array();
    //$rec=&$personajes[$_GET['id']];
  }
var_dump($_POST,$struct);
  foreach($_POST as $k=>$v)if(!empty($v) || ($struct[$k]['type']!='file')){
    if($struct[$k]['type']=='array'){
      $v=str_replace("\r\n","\n",$v);
      $v=str_replace("\r","\n",$v);
      $v=explode("\n",$v);
    }
    $personajes[$_GET['id']][$k]=$v;
  }
  //uDB::save("personajes", $personajes);
  header("Location: personajes_list.php");
  exit;
}
foreach($struct as $f=>$s){
  switch($s["type"]){
    case "input":echo '<div class="pure-control-group"><label for="',$f,'">', $s['label'],':</label><span><input name="',$f,'" value="',@$rec[$f],'"></span></div>';
      break;
    case "array":echo '<div class="pure-control-group"><label>', $s['label'],':</label><span><textarea name="',$f,'">',@implode("\n", $rec[$f]),'</textarea></span></div>';
      break;
    case "file":echo '<div class="pure-control-group"><label>', $s['label'],':</label><span><input name="',$f,'" type="file"></span></div>';
      echo '<div class="pure-control-group"><label></label><span><iframe src="',@$rec[$f],'" style="width:100%"></iframe></span></div>';
      break;
  }
}
?>
        <div class="pure-controls">
            <label for="cb" class="pure-checkbox">
            </label>
            <button type="submit" class="pure-button pure-button-primary">Enviar</button>
            <button type="button" class="pure-button" onclick="window.location.href='personajes_list.php'">Volver</button>
        </div>
</fieldset>
</form>