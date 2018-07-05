<?php

class Menu extends \model\Model {
  public static function getQuery(){
    return 'select menu.id, text, leaf from 
              menu left join permission on (menu.permission_id=permission.id) 
            where permission.rol_id=:rol and menu.parent=:parent';
  }
}