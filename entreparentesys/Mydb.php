<?php

class Mydb extends \model\Connection {
  public function __construct(){
    $this->engine = 'mysql';
    $this->host = 'localhost';
    $this->database = 'entre';
    $this->user = 'root';
    $this->pass = 'root';
    $dns = $this->engine.':dbname='.$this->database.";host=".$this->host;
    parent::__construct( $dns, $this->user, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8;') );
  }
}
