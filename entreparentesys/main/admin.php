<?php
@session_start();
$_SESSION['rol']='';
$_SESSION['permiso']='';
return array(
  [
    'class'=>'panel',
    'title'=>'Administracion de roles',
    'layout'=>'column',
    'items'=>array(
      [
        'class'=>'table',
        'name'=>'menus',
        'model'=>$this->db->instanceModel('\Rol',[]),
        'columns'=>array(
          ['name'=>'id','text'=>'Código'],
          ['name'=>'name','text'=>'Nombre'],
        ),
        'listeners'=>[
          'onselectrow'=>['row',function($row){
            @session_start();
            $_SESSION['menus']=$row;
            $_SESSION['rol']=$row->rol_id;
          }]
        ]
      ]
    ),
  ],
);