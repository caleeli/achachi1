<?php
/**
 *
 *  Achachi XML Builder - Framework and applications builder tool
 *  Copyright (C) 2010-2011, Llankay Achachi
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

class expressionEvaluator {
  protected $vars;
  public $engine="achachi";
  /** achachi_1 **/
  function replace_all($__match__)
  {
    if($__match__[1]=='@') return $this->replace(array(1=>$__match__[2]));
    if($__match__[1]=='$') return $this->replace_exp(array(1=>$__match__[2]));
    if($__match__[1]=='#') return $this->replace_exec(array(1=>$__match__[2]));
  }
  function replace($__match__)
  {
    global $PARAMS;
    foreach($this->vars as $___ => &$____) $$___ = $____;
    return eval("return {$__match__[1]};");
  }
  function replace_exp($__match__)
  {
    global $PARAMS;
    foreach($this->vars as $___ => &$____) $$___ = $____;
    return var_export(eval("return {$__match__[1]};"),true);
  }
  function replace_exec($__match__)
  {
    global $PARAMS;
    foreach($this->vars as $___ => &$____) $$___ = $____;
    return eval($__match__[1]);
  }
  function evaluateAchachi($exp,$vars=null)
  {
    if($vars) {$prevars = $this->vars; $this->vars = $vars;} else {$prevars = false;}
    //Bug Fix: No se pueden usar $\{}
    $res = preg_replace_callback('/(@|\$|#)\{([^\}]+)\}/', array($this,'replace_all'), $exp);
    $res = preg_replace('/#\\\{/', '#{', $res);
    $res = preg_replace('/@\\\{/', '@{', $res);
    $res = preg_replace('/\$\\\{/', '${', $res);
    if($prevars) $this->vars = $prevars;
    return $res;
  }
  /** php **/
  function replacePhp($__exp){
    foreach($this->vars as $___ => &$____) $$___ = $____;
    ob_start();
    eval('?>'.$__exp);
    $__res=ob_get_contents();
    ob_end_clean();
    return $__res;
  }
  function evaluatePhp($exp,$vars=null){
    if($vars) {$prevars = $this->vars; $this->vars = $vars;} else {$prevars = false;}
    $res=$this->replacePhp($exp);
    if($prevars) $this->vars = $prevars;
    return $res;
  }
  /** js **/
  function replaceJs($__exp){
    ob_start();
    $v8 = new V8Js("PHP");
    $init="";
    foreach($this->vars as $___ => &$____) if(substr($___,0,1)!=="#") {
      $v8->$___ = $____;
      $init.="$___=PHP.$___;";
    }
    $v8->executeString($init.$__exp, get_class($this)."->evaluateJs()" );
    $__res=ob_get_contents();
    ob_end_clean();
    return $__res;
  }
  function evaluateJs($exp,$vars=null){
    if($vars) {$prevars = $this->vars; $this->vars = $vars;} else {$prevars = false;}
    $res=$this->replaceJs($exp);
    if($prevars) $this->vars = $prevars;
    return $res;
  }
  /** cmd **/
  function replaceCmd($__exp){
    foreach($this->vars as $___ => &$____) $$___ = $____;
    ob_start();
    eval('$__cmd=<<<EOF'."\n".$__exp."\nEOF;\n");
    //echo($__cmd);
    passthru($__cmd);
    $__res=ob_get_contents();
    ob_end_clean();
    return $__res;
  }
  function evaluateCmd($exp,$vars=null){
    if($vars) {$prevars = $this->vars; $this->vars = $vars;} else {$prevars = false;}
    $res=$this->replaceCmd($exp);
    if($prevars) $this->vars = $prevars;
    return $res;
  }
  /************/
  function evaluate($exp,$vars=null)
  {
    $res = "";
    if($this->engine==="achachi"){
      $res = $this->evaluateAchachi($exp,$vars);
    } elseif($this->engine==="php"){
      $res = $this->evaluatePhp($exp,$vars);
    } elseif($this->engine==="js"){
      $res = $this->evaluateJs($exp,$vars);
    } elseif($this->engine==="cmd"){
      $res = $this->evaluateCmd($exp,$vars);
    }
    //var_dump($res);
    return $res;
  }
  static function evaluateS($exp,$vars){
    $ee = new expressionEvaluator();
    return $ee->evaluate($exp,$vars);
  }
}

/**
INSTALL V8JS 
sudo apt-get install php-pear     //install pecl in ubuntu
sudo apt-get install libv8-dev
sudo pecl install v8js-0.1.3       //it depends of the libv8 version
php -i | grep php.ini              //to get the php.ini path (for php client)
sudo nano /usr/local/lib/php.ini   //editar php.ini y agregar extension=v8js.so

add-apt-repository ppa:pinepain/libv8-5.2
apt-get install libv8-5.2

  apt-get install php7.1
  apt-get install php7.1-mysql
  apt-get install php7.1-fpm
  apt-get install php7.1-curl
  apt-get install php7.1-gd
  apt-get install php7.1-mcrypt
  apt-get install php7.1-sqlite3
  apt-get install php7.1-mbstring
  apt-get install php7.1-dom
  apt-get install php7.1-memcached
  apt-get install php7.1-pear
  688  apt-get install php7-pear
  702  history | grep php7

apt-get install php7.1-dev
pecl install -f v8js-1.3.3

install node:
apt-get install npm
apt-get install build-essential libssl-dev
curl https://raw.githubusercontent.com/creationix/nvm/v0.16.1/install.sh | sh
source ~/.profile
nvm install 6.8.1
node -v
/tmp/pear/temp/v8js/configure --with-php-config=/usr/bin/php-config --with-v8js
*/
