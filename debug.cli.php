<?php
//CLIENT

class debug {
  var $server="http://localhost/achachi/debug.php";
  var $ss=null;
  function __destruct(){
    $this->callServer("sessionClose",array($this->ss));
  }
  function callServer($f,$p=array()){
    $p=json_encode($p);
    @$r=file_get_contents($this->server."?f=".urlencode($f)."&p=".urlencode($p));
    return json_decode($r);
  }
  function sessionStart() {
    $this->ss=$this->callServer("sessionStart");
  }
  function singleton() {
    global $debugInstance;
    if(!isset($debugInstance)) {
      $debugInstance = new debug;
      $debugInstance->sessionStart();
    }
    return $debugInstance;
  }
  function point($f='',$l=-1) {
    $singleton=self::singleton();
    $trace=debug_backtrace();
//    var_dump($trace);
    foreach($trace as $k=>$t){
      //if($t["file"]==$f && $t["line"]==$l) break;
      if(file_exists($t["file"])) break;
      unset($trace[$k]);
    }
    $singleton->callServer("setBpoint",array($singleton->ss,array("content"=>ob_get_contents(),"trace"=>$trace)));
    //$r="return;";
    set_time_limit(0);
    $tt=microtime(true)+90;
    while(!($r=$singleton->callServer("getResponse",array($singleton->ss)))) 
    {
      sleep(1);
      if($tt<microtime(true)) {$r="return;";break;}
    }
    set_time_limit(30);
    return $r.";eval(debug::point(".var_export($f,true).",$l));";
  }
}
