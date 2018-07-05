<?php
//Using Naming conventions for code released by PHP-FIG [https://github.com/php-fig/fig-standards/blob/master/bylaws/002-psr-naming-conventions.md]
//LOAD AUTOLOADER
require_once('../../lib/dvf/ClassPaths.php');
ClassPaths::add('../../lib/dvf');
ClassPaths::add('../../lib/DvModel');
ClassPaths::add('../../lib/super_closure-master');
ClassPaths::add('../../lib/php-parser');
function __autoload($nombre_clase) {
  $path=ClassPaths::resolve($nombre_clase, '.php');
  if($path){
    //[http://www.php-fig.org/psr/psr-4/]
    include_once($path);
  }
}

//ClassPaths::add('./classes');
//todo relative class path
ClassPaths::add('./');
//PagePaths::add('./pages');

if(file_exists('config.php')){
  include('config.php');
}

Router::resolveRequest();
