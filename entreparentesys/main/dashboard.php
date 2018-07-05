<?php
@session_start();
$_SESSION['padre']='root';
return array(
  [
    'class'=>'panel',
    'title'=>'Dashboard',
    'layout'=>'column',
    'items'=>array(
      ['class'=>'panel','html'=>'panel'],
      ['class'=>'panel','html'=>'panel'],
      ['class'=>'panel','html'=>'panel'],
    ),
  ],
  [
    'class'=>'table',
    'title'=>'Table',
    'name'=>'menus',
    'model'=>$this->db->instanceModel('\Menu',['rol'=>$_SESSION['user']->rol_id,'parent'=>'root']),
    'columns'=>array(
      ['name'=>'id','text'=>'Id'],
      ['name'=>'text','text'=>'Text'],
    ),
  ]
);