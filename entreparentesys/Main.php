<?php

class Main extends \Controller {
  public $title="MANAGER";
  function loadContext(){
    $this->db=new Mydb();
  }
  function indexAction($data, $content){

  }
  function authAction($data, $content){
    $userModel = $this->db->instanceModel('\UserLogin', $data);
    $user = $userModel->fetch();
    if($user){
      @session_start();
      $_SESSION['user']=$user;
      echo 'window.location.href="index.php"';
    } else {
      echo 'APP.error("Usuario o contraseña incorrectos.")';
    }
  }
  function openFileAction($data){
    header("Content-Type: application/xml; charset=utf-8");
    readfile($data["r"]);
    exit;
  }
  function saveFileAction($data){
    file_put_contents($data["f"], $data["c"]);
    exit;
  }
}