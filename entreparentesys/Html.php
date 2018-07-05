<?php


class Html extends \Controller {
  function indexAction($data, $content){
  }
  function stylesheetAction($data, $content){
    header('Content-type:text/css');
    echo $content;
  }
  function contentAction($data, $content){
    if(!empty($content) && is_array($content)){
      foreach($content as $k=>$node) if(is_array($node) && is_numeric($k)){
        Router::resolveRoute('html/'.strtolower($node['class']).'.php', $node, @$node['items']);
      }
    }
  }
  public static function url($base, $model, $id){
    @session_start();
    if(empty($id)) $id=uniqid('d');
    $_SESSION[$id]=$model->serializeMe();
    return Router::url($base . '?d=' . $id);
  }
  function datatableAction($data, $content){
    @session_start();
    $id=$data['d'];
    //var_dump($id,$_SESSION[$id]);
    $conn=$_SESSION[$id]['connection'];
    //TODO: $db must not be private variable (Singleton)
    $db=new $conn();
    echo json_encode($db->deserializeModel($_SESSION[$id])->fetchAll());
  }
  public static function listener($base, $callbackDI, $id){
    @session_start();
    if(empty($id)) $id=uniqid('c');
    $callback=array_pop($callbackDI);
    if($callback instanceof \Closure){
      $serializer = new \SuperClosure\Serializer();
      $serialized = $serializer->serialize($callback);
      $callbackDI[]=$serialized;
      $_SESSION[$id]=$callbackDI;
    } else {
      $callbackDI[]=$callback;
      $_SESSION[$id]=$callbackDI;
    }
    return Router::url($base . '?c=' . $id);
  }
  function listenerAction($data, $content){
    @session_start();
    $callback_id=$data['c'];
    $callbackDI=$_SESSION[$callback_id];
    $callback=array_pop($callbackDI);
    $params=[];
    foreach($callbackDI as $p){
      $params[]=@$data[$p];
    }
var_dump($callback,$params);
    if(is_string($callback) && substr($callback,0,2)=="C:"){
      $serializer = new \SuperClosure\Serializer();
      $callback = $serializer->unserialize($callback);
      call_user_func_array($callback, $params);
    } else {
      call_user_func_array($callback, $params);
//      call_user_func($callback, $data);
    }
  }
}