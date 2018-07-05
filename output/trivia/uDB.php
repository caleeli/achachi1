<?php
//class UDB implements ArrayAccess, Iterator {
class UDB implements ArrayAccess, Iterator, Countable {
  private $pointer = 0;
  public $path='';
  public $isFile=false;

  public function __construct($path){
    $this->path = $path;
    $this->isFile = is_file($path);
  }
  public function offsetExists ( $offset ){
    $value = @$this->offsetGet ( $offset );
    return isset($value);
  }
  public function count(){
    if($this->isFile){
      $valueS = file_get_contents($this->path);
      $value = @unserialize($valueS);
      if($value===false && $valueS!="b:0;"){
        //error
        return 0;
      }
      return count($value);
    } else {
      return count(glob($this->path.'/*'));
    }
  }
  public function getValue(){
    if($this->isFile){
      $valueS = file_get_contents($this->path);
      $value = @unserialize($valueS);
      if($value===false && $valueS!="b:0;"){
        //error
        return null;
      }
      return $value;
    } else {
      die('TODO getValue of directory');
    }
  }
  public function offsetGet ( $offset ){
    if($this->isFile){
      $valueS = file_get_contents($this->path);
      $value = @unserialize($valueS);
      if($value===false && $valueS!="b:0;"){
        //error
        return null;
      }
      if(is_object($value)){
        return @$value->$offset;
      } elseif(is_array($value)){
        return @$value[$offset];
      } else {
        return @$value[$offset];
      }
    } else {
      if(!isset($offset) || $offset===''){
        return null;
      } elseif(file_exists($this->path . '/' . $offset)){
        return new uDB($this->path . '/' . $offset);
      } else {
        return null;
      }
    }
  }
  public function offsetSet ( $offset , $newValue ){
    if($this->isFile){
      $valueS = file_get_contents($this->path);
      $value = @unserialize($valueS);
      if($value===false && $valueS!="b:0;"){
        //error
        return;
      }
      if(is_object($value)){
        $value->$offset=$newValue;
      } elseif(is_array($value)){
        $value[$offset]=$newValue;
      } else {
        $value[$offset]=$newValue;
      }
      file_put_contents($this->path , serialize($value));
    } else {
      if(isset($offset)){
        file_put_contents($this->path . '/' . $offset , serialize($newValue));
      }
    }
  }
  public function offsetUnset ( $offset ){
    if($this->isFile){
      $valueS = file_get_contents($this->path);
      $value = @unserialize($valueS);
      if($value===false && $valueS!="b:0;"){
        //error
        return;
      }
      if(is_object($value)){
        unsset($value->$offset);
      } elseif(is_array($value)){
        unset($value[$offset]);
      } else {
        unset($value[$offset]);
      }
      file_put_contents($this->path , serialize($value));
    } else {
      if(file_exists($this->path . '/' . $offset)){
        if(is_file($this->path . '/' . $offset)){
          unlink($this->path . '/' . $offset);
        } else {
          uDB::rrmdir($this->path . '/' . $offset);
        }
      }
    }
  }

  static function rrmdir($dir) {
    if (is_dir($dir)) {
      $objects = scandir($dir);
      foreach ($objects as $object) {
        if ($object != "." && $object != "..") {
          if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
        }
      }
      reset($objects);
      rmdir($dir);
    }
  }

  public function loadKeys(){
    if($this->isFile){
      $valueS = file_get_contents($this->path);
      $value = @unserialize($valueS);
      if($value===false && $valueS!="b:0;"){
        //error
        return;
      }
      if(is_object($value)){
        $this->keys = get_object_vars($value);
      } elseif(is_array($value)){
        $this->keys = array_keys($value);
      } else {
        $this->keys = array();
      }
    } else {
      $keys=array();
      foreach(glob($this->path.'/*') as $f) $keys[]=basename($f);
      $this->keys=$keys;
    }
//    echo __LINE__;var_dump($this->keys);die;
  }

  public function key() {
    $this->loadKeys();
    return @$this->keys[$this->pointer];
  }

  public function current() {
    $key = $this->key();
    $value=$this->offsetGet($key);
    return $value;
  }

  public function next() {
    $this->pointer++;
  }

  public function rewind() {
    $this->pointer = 0;
  }

  public function seek($position) {
    $this->pointer = $position;
  }

  public function valid() {
    $current=$this->current();
    return isset($current);
  }

  /*static function save($name, $data){
    file_put_contents($name.'.php', '<?php'."\n\$$name=".var_export($data,true).';');
  }*/
}