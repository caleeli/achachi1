<?php

class extjs {
  public static function create($node){
    global $core;
    $cnf = array();
    $items = array();
    $children = array();
    foreach($node->attributes as $a) if($a->nodeName!="class" && substr($a->nodeName,0,1)!="_"){
      $cnf[] = '"'.$a->nodeName.'":'.extjs::encode($a->nodeValue);
    }
    foreach($node->childNodes as $ch) {
      $res=$core->makeFromNode($ch);
      foreach($res as $re){
        //if($re[0]=="ext") $items[]=$re[1];
        if($re[0]=="ext.attribute") $cnf[]=$re[1];
        else $items[]=$re[1];
        $children[]=$re[1];
      }
    }
    if($node->nodeName=="ext.attribute") {
      if($node->getAttribute("type")=="array") $items='['.implode(',', $items).']';
      elseif($node->getAttribute("type")=="object") $items='{'.implode(',', $items).'}';
      elseif($node->getAttribute("type")=="string") $items=extjs::encode(implode('', $children));
      else $items=implode('', $children);
      return array(array($node->nodeName, extjs::encode($node->getAttribute("name")).':'. $items));
    } else {  //ext
      if($items) {
        $items='['.implode(',', $items).']';
        $cnf[]='"items":'.$items;
      }
      $cnf = implode(",", $cnf);
      return array(array($node->nodeName, "new ".$node->getAttribute("class")."({".$cnf."})"));
    }
  }
  public static function encode($value){
    if($value==="true") $value=true;
    elseif($value==="false") $value=false;
    elseif(is_numeric($value)) $value=0+$value;
    return json_encode($value);
  }
}
