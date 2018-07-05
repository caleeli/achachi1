<?php
class DV_RemoteCall{
  function __construct($id){
    $dir=dirname(__FILE__);
    $this->file=$dir.'/remotecmd/'.$id.'.bat';
    $this->output=$dir.'/remoteout/'.$id.'.bat.out';
  }
  /*function isReady(){
    return file_exists($this->output);
  }
  function getOutput(){
    while(!$this->isReady()){
      sleep(1);
    }
    return file_get_contents($this->output);
  }*/
}
class DV_RemoteCmd{
  static function call($shellScript, $id=null){
    if(!$id) $id=uniqid();
    $call=new DV_RemoteCall($id);
    $file=$call->file;
    $fileOut=$call->output;
    if(file_exists($fileOut)) unlink($fileOut);
    file_put_contents($file, "\r\n".$shellScript."\r\nexit\r\n");
    return $call;
  }
}
