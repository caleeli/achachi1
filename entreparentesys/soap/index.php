<?php
function CC($path, $data, $content=""){
  ob_start();
  Router::resolveRoute($path, $data, $content);
  $res=ob_get_contents();
  ob_end_clean();
  return $res;
}
function AA($DATA){
  $attributes=[];
  foreach($DATA as $v) $attributes[$v->nodeName]=$v->nodeValue;
  return join(' ', array_map(function($key) use ($attributes)
  {
     if(is_bool($attributes[$key]))
     {
        return $attributes[$key]?$key:'';
     }
     return $key.'="'.htmlentities($attributes[$key], ENT_QUOTES, 'utf-8').'"';
  }, array_keys($attributes)));
}
function Process($xhtml){
  $dom=new DomDocument();
//echo $xhtml;
  $dom->loadXml($xhtml);
//echo $dom->saveXml($dom->childNodes[0]);
  $content="";
  foreach($dom->childNodes as $ch){
    $content.=ProcessNode($ch);
  }
  return $content;
}
function ProcessNode($e){
  if($e->nodeName=="#text") return $e->nodeValue;
  if($e->nodeName=="#comment") return '<!-- '.$e->nodeValue.' -->';
  $content="";
  foreach($e->childNodes as $ch){
    $content.=ProcessNode($ch);
  }
  if($f=findObject($e->nodeName)){
    return CC($f,$e->attributes,$content);
  } else {
    return '<'.$e->nodeName.' '.AA($e->attributes).'>'.
            $content.'</'.$e->nodeName.'>';
  }
}
function findObject($name){
  $filename='oleose/xhtml/'.$name.'.php';
  return file_exists($filename)?$filename:'';
}
Router::resolveRoute('oleose/page.php', [
  "title"=>"Hello world",
  "header"=>CC('oleose/header.php',[
    "navbar"=>CC('oleose/navbar.php',["about"],""),
    "container"=>CC('oleose/slider.php',[
      CC('oleose/slide.php',[
        "image"=>\Router::url('oleose/img/freeze/Slides/hand-freeze.png'),
        "image_xs"=>\Router::url('oleose/img/freeze/iphone-freeze.png'),
        "content"=>Process('<easeOut data-x="550" data-start="1200" >Mora:</easeOut>')
      ])
    ])
  ], ""),
], "");
