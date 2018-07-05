<?php
function getTree($path, &$tree){
    foreach(glob("$path/*", GLOB_ONLYDIR) as $f){
        $name=basename($f);
        $tree[$name]=array();
        getTree($f, $tree[$name]);
    }
}
$tree=array();
getTree(".", $tree);
echo '$this.doClasses(',json_encode($tree),');';