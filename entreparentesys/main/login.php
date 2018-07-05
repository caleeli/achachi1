<?php
return array(
  'stylesheets'=>[
    \Router::url('html/stylesheet/main/login.css'),
/*    Router::url('../bootstrap/bootstrap-notify-master/css/bootstrap-notify.css'),*/
  ],
  'scripts'=>[
/*    Router::url('../bootstrap/bootstrap-notify-master/js/bootstrap-notify.js'),*/
  ],
  [
    'class'=>'panel',
    'cssClass'=>'container-fluid login-panel',
    'title'=>'Ingresar',
    'items'=>array(
      ['class'=>'form',
        'action'=>Router::url('main/auth.php'),
        'items'=>array(
          ['class'=>'textfield',    'name'=>'user', 'label'=>'Usuario'],
          ['class'=>'passwordfield','name'=>'pass', 'label'=>'Contraseña'],
          ['class'=>'checkbox','boxLabel'=>'Recuerdame'],
          ['class'=>'button','text'=>'Ingresar',
            'handler'=>'$(this.form).ajaxSubmit(function(res){eval(res)})',
          ],
        )
      ]
    )
  ]
);