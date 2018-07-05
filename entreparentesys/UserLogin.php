<?php


class UserLogin extends \model\Model {
  public static function getQuery(){
    return 'select * from user where name=:user and password=md5(:pass)';
  }
}