<?php
require_once("Zend/Db.php");
require_once("Zend/Db/Table.php");
session_start();
if(!isset($_SESSION["connection"])) $_SESSION["connection"] = array();
/* Loads stored connections */
$connections=array();
if(file_exists("database_connections.php")) {
  eval("?>". file_get_contents("database_connections.php") );
}
/* Display | Select a stored connection */
if(isset($_POST["x"])){
  if($_POST["x"]==""){
    print(json_encode($connections));
    exit;
  } else {
    if(isset($connections[$_POST["x"]]) && is_array($connections[$_POST["x"]]) ) {
      $_SESSION["connection"] = $connections[$_POST["x"]];
    } else {
      print("invalid connection.\n");
      exit;
    }
  }
}
if(isset($_POST["driver"]))$_SESSION["connection"]["driver"]=$_POST["driver"];
if(isset($_POST["host"]))$_SESSION["connection"]["host"]=$_POST["host"];
if(isset($_POST["username"]))$_SESSION["connection"]["username"]=$_POST["username"];
if(isset($_POST["password"]))$_SESSION["connection"]["password"]=$_POST["password"];
if(isset($_POST["dbname"]))$_SESSION["connection"]["dbname"]=$_POST["dbname"];

if(isset($_SESSION["connection"]["host"])) {
  $conn = $_SESSION["connection"];
  unset($conn["driver"]);
  if(strtoupper( $_SESSION["connection"]["driver"] ) == "PDO_SQLITE") {
    //$conn["sqlite2"]=true;
  }
  $db = Zend_Db::factory($_SESSION["connection"]["driver"]/*Mysqli|Oracle|Db2|PDO_MYSQL|PDO_PGSQL|PDO_OCI|PDO_MSSQL|PDO_SQLITE|PDO_IBM*/, $conn );
  if(array_search($_SESSION["connection"], $connections)===false){
    $connections[]=$_SESSION["connection"];
    file_put_contents("database_connections.php", "<?php\n  \$connections=".var_export($connections,true).";");
  }
}
if(isset($_SESSION["connection"]["sqlite2"])&&($_SESSION["connection"]["sqlite2"]!="")) $db["sqlite2"]=$_SESSION["connection"]["sqlite2"];

Zend_Db_Table_Abstract::setDefaultAdapter($db);

if(isset($_POST["c"])){
  $stmt = $db->query($_POST["c"]);
  $maxrows=15;
  $continue=true;
  for($i=0;$i<$maxrows;$i++){
    $row = $stmt->fetch();
    if($row)$result[]=$row; else {$continue=false;break;}
  }
  printGrid($result);
  if($continue) print("...\n");
}

/* AUXILIAR FUNCTIONS */
function printGrid( $array )
{
  print( '<table border="1"><tbody>' );
  reset( $array );
  print( '<tr><th>[]</th><th>' );
  print( implode( '</th><th>', array_keys( current( $array ) ) ) );
  print( '</th></tr>' );
  foreach( $array as $row => $cells )
  {
    print( "<tr><th>$row</th><td>" );
    print( implode( '</td><td>', $cells ) );
    print( '</td></tr>' );
  }
  print( '</tbody></table>' );
}
