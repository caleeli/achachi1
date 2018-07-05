<?php


class Rol_Permisos extends \model\Model {
  public static function getQuery(){
    return 'select * from adrolperm left join adrol on(adrolperm.adrolcodi=adrol.adrolcodi) where adrolperm.adrolcodi=:rol';
  }
}