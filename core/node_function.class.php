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

class node_function extends node{
  function run() {
    $access=$this->node->getAttribute("access");
    if($access) $access.=" ";
    /*$_r = node::run();
    var_dump($_r,$this->values,$this->node->nodeValue); die;*/
    $res = 'public '.$access.'function '.$this->node->getAttribute("name").'('.$this->node->getAttribute("parameters").'){'.
    (($this->node->getAttribute("empty") && $this->node->getAttribute("empty")=="true") ? '' : (
      '$e=&$this->node;$values=&$this->values;'
//      '$values["inner"]=&$this->values["_"];'.
//      '$values["nodes"]=node::getNumericItems($this->values);'
    )). 
    $this->node->nodeValue.
    '}';
    return array(array("function",$res));
  }
}
