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

class node_def extends node{
  function run() {
    $_tag = $this->node->getAttribute("name");
    $_class = node::getClassName($_tag);
    $_code = 'class '.$_class.' extends node { ';
    $_r = node::run();
    //var_dump($_r);
    $_code.= node::join("",$_r);
    $_code.=' }';
    $_code=format_code($_code);
    node::createTagClass($_tag,$_class,$_code);
    return array();
  }
}
