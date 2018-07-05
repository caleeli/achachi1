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

//require_once("expressionEvaluator.class.php");
class node extends expressionEvaluator{
  public $values=array();
  public $node=null;
  public function __construct($node) {
    $this->core=$this;
    $this->values=self::xml2arr($node);
    $this->node=$node;
  }
  public static function factory($_ch) {
    global $definedClasses;
    $_class = (isset($definedClasses[$_ch->nodeName])) ? $definedClasses[$_ch->nodeName] : "node";
    if(ACH_DEBUG_SHOW_INSTANCING)print("Instanced: $_class for ".$_ch->nodeName."\n");
    return new $_class($_ch);
  }
  public static function getClassName($tag) {
    $_n="";
    $tag = preg_replace('/[^\w\d_]/','_',$tag);
    $_class = 'node_'.$tag;
    while(class_exists($_class.$_n)) $_n++;
    return $_class . $_n;
  }
  public function findParent($nodeName){
    $n=$this->node->parentNode;
    while(isset($n) && $n->nodeName!=$nodeName){
      $n=$n->parentNode;
    }
    return $n;
  }
  public static function createTagClass($_tag,$_class,$_code) {
    global $definedClasses,$generationType,$generatedPath;
    if(ACH_DEBUG_SHOW_CREATED_TAG) print("created tag [$_tag] class: ".$_class."\n");
    $definedClasses[$_tag]=$_class;
    if(defined('ACH_DEBUG_SHOW_CODE') && ACH_DEBUG_SHOW_CODE) {print("</pre>");highlight_string("<?php\n".$_code);print("<pre>");}
    if($generationType==ACH_GENERATE_TYPE_GENERATE) {
      file_put_contents("{$generatedPath}/{$_class}.class.php","<?php\n".$_code);
      require("{$generatedPath}/{$_class}.class.php");
    } else {
      eval($_code);
    }
  }
  /**
   *  Retorna un array con los siguientes elementos:
   *  #: array con los resultados de todos los hijos.
   *  childNodeName: array con los resultados de todos de los hijos de tipo childNodeName.
   */
  public function run() {
    $_res=array();
    if($this->node->hasChildNodes()) {
      foreach($this->node->childNodes as $_ch) {
        $_r = $this->runChild($_ch);
        foreach($_r as $r)
        {
          $_res[]=$r;
        }
      }
    }
    return $_res;
  }
  public function runChild($_ch) {
    $_res=array();
    $_a=node::factory($_ch);
    $_r = $_a->run();
    if(!is_array($_r)) var_dump($_ch->nodeName." RESULT IS NOT VALID: ",$_r);
    return $_r;
  }
  public function groupNodes($arrRes){
    $grpRes=array();
    foreach($arrRes as $aRes) {
      $grpRes[$aRes[0]][]=$aRes;
    }
    return $grpRes;
  }
  public function encode($res) {
    return array(array($this->node->nodeName,$res));
  }
  public function encodeEmpty() {
    return array();
  }
  /**
   */
  public function create($arr,$dest0) {
    $dest = $dest0;
    $document = $dest->ownerDocument;
    $t="";
    $d=array();
    $modeCreate=!(count($arr)==1 && is_string($arr[0]));
    for($i=0,$l=count($arr);$i<$l;$i++){
      $t=getType($arr[$i]);
      if($t=="object") $tclass=get_class($arr[$i]); else $tclass=false;
      if($t=="string" && $modeCreate){
        if($arr[$i]=="#text") {
          if(getType($arr[$i+1])=="string") $dest=$document->createTextNode($arr[$i=$i+1]);
          if(getType($arr[$i+2])=="string") $dest=$document->createTextNode($arr[$i=$i+2]);
          $d[]=$dest;
        }
        else if($arr[$i]=="#comment") {
          if(getType($arr[$i+1])=="string") $dest=$document->createComment($arr[$i=$i+1]);
          if(getType($arr[$i+2])=="string") $dest=$document->createComment($arr[$i=$i+2]);
          $d[]=$dest;
        }
        else {
          //The next String will be a TextNode
          $modeCreate=false;
          $dest=$document->createElement($arr[$i]);
//          if($arr[$i]=="style") $dest->setAttribute("type", "text/css");
          $d[]=$dest;
        }
      }
      else if($t=="string" && !$modeCreate) {
        $dest=$dest->appendChild($document->createTextNode($arr[$i]));
        $modeCreate=true;
      }
      else if($t=="object" && substr($tclass,0,3)=="DOM" && $tclass!="DOMNodeList") {
        //If Node is owned by a different Document it will be imported.
        if($document===$arr[$i]->ownerDocument) $o=$arr[$i]; 
        //else $o=$this->create($this->reverse($arr[$i]),$dest);
        if($modeCreate) $d[]=$o; else $dest->appendChild($o);
        $dest=$o;
        $modeCreate=true;
      }
      else if($tclass=="DOMNodeList") {
        $dest1=array();
        for($j=0,$k=$arr[$i]->length;$j<$k;$j++) {
          //If Node is owned by a different Document it will be imported.
          if($document===$arr[$i]->item($j)->ownerDocument) $o=$arr[$i]->item($j); 
          //else $o=$this->create($this->reverse($arr[$i].item($j)),$dest);
          if($modeCreate) $d[]=$o; else $dest->appendChild($o);
          $dest1[]=$o;
        }
        $dest=$dest1;
        $dest1=null;
        $modeCreate=true;
      }
      else if($t=="object" || ($t=="array" && (array_keys($arr[$i]) !== range(0, count($arr[$i]) - 1)))) {
        //this.setAttributes(dest,arr[i]);
        foreach((array) $arr[$i] as $a => $v){
        //var_dump($a,$v);
          $dest->setAttribute($a,$v);
        }
      }
      else if($t=="array") {
        /*VAR_DUMP($i,"ENTRO array",$arr[$i],$modeCreate);
        if($modeCreate) for($dest=$this->create($arr[$i],$dest0),$j=0,$k=count($dest);$j<$k;$j++) $d[]=$dest[$j];
        else $dest=$this->create(array($dest,$arr[$i]),$dest);
        $modeCreate=true;*/
        //for($dest=$this->create($arr[$i],$dest0),$j=0,$k=count($dest);$j<$k;$j++) $d[]=$dest[$j];
        $dest1=$this->create($arr[$i],$dest0);
        for($j=0,$k=count($dest1);$j<$k;$j++) $dest->appendChild($dest1[$j]);
      }
    }
    return $d;
  }
  public function yaml($text,$dest) {
    require_once(ACH_COMMON_PATH."/spyc.php");
    $arr=Spyc::YAMLLoadString($text);
    $document = $dest->ownerDocument;
    $d=array();
    foreach($arr as $ee){
      $e=$dest->appendChild(node::yamlnode($ee,$dest));
      $c = node::factory($e);
      $r = $c->run();
      foreach($r as $r1) $d[]=$r1;
    }
    return $d;
  }
  public function yamlnode($ee,$dest){
    $document = $dest->ownerDocument;
    if(is_array($ee)){
      $e=$document->createElement(key($ee));
      foreach($ee[key($ee)] as $a => $v){
        if(is_numeric($a)) $e->appendChild(node::yamlnode($v, $e));
        else $e->setAttribute($a, $v);
      }
      return $e;
    } else {
      $e=$document->createTextNode($ee);
      return $e;
    }
  }
  public function insert($arrDraw3) {
    $_e = $this->node;
    $_d = $_e->ownerDocument;
    $_p1 = $_d->createElement("temp");
//    $_e1 = $_d->createElement("fecha");
    //$_e1->setAttribute("name", '$|$fecha|');
    //$_p1->appendChild($_e1);
    //$_c = node::factory($_e1);
    $this->create(array($arrDraw3),$_p1);
    $_e1 = $_p1->firstChild;
    var_dump($_p1);
    $_c = node::factory($_e1);
    return node::content($_c->run());
  }
  public function xml2arr($node) {
    $arr=array();
//    if($node->hasChildNodes()) $arr=self::nodes2array($node->childNodes);
    if($node->hasAttributes()) {
      foreach($node->attributes as $a)
      {
        $arr[$a->nodeName]=$a->nodeValue;
      }
    }
    $arr["_attributes"]=$arr;
    $arr["#"]=$node->nodeValue;
    return $arr;
  }
  public function nodes2array($childNodes,$type='')
  {
    $arr=array();
    foreach($childNodes as $ch)
    {
      $arr[]=self::xml2arr($ch);
    }
    return $arr;
  }
  public function _nodes($childNodes,$nodeName='')
  {
    $arr=array();
    foreach($childNodes as $ch)
    {
      if($nodeName=='') $arr[]=array($ch->nodeName, self::xml2arr($ch));
      elseif($nodeName==$ch->nodeName) $arr[]=array($ch->nodeName, self::xml2arr($ch));
    }
    return $arr;
  }
  /**
   * Une en una cadena todos los nodos tipo string array proporcionado.
   */
  public function implode($glue,$arr) {
    $res="";
    foreach($arr as $a) {
      //if(isset($a["_"][0]) && is_string($a["_"][0]))$res.=$a["_"][0];
      if(is_string($a))$res.=$a;
    }
    return $res;
  }
  /**
   * Une en una cadena todos los nodos tipo string array proporcionado.
   */
  public function join($glue,$arr) {
    $res="";$first=true;
    foreach($arr as $a) {
      $res.=($first?"":$glue).$a[1];
      $first=false;
    }
    return $res;
  }
  public function content($arr,$glue=""){
    return node::join($glue,$arr);
  }
  /** FUNCIONES PARA EL MANEJO DE LA TEMPLETA */
  function doTemplate($template, $vars)
  {
    return $this->evaluate($template, $vars);
  }

/*POR COMPATIBILIDAD*/
  function convertResult($_r,$_a){
    if(isset($_a->transparent)) {
      $res = array();
      foreach($_r["_"] as $r)
      {
        $res[$r[0]][]=$r[1];
        $res["_"][]=$r[1];
      }
    } else {
      $res = $_r;
    }
    return $res;
  }
  function makeFromNode($ch) {
    $_a = node::factory($ch);
    $_r = $_a->run();
    return $_r;//$this->convertResult($_r,$_a);
  }
  function getNumericItems($nodes) {
    $res = array();
    foreach($nodes as $k => $n) {
      if(is_numeric($k))$res[$k]=&$n;
    }
    return $res;
  }
  function evalNode($n,$vars)
  {
    if($n->nodeName=="#text") {$n->nodeValue=$this->evaluate($n->nodeValue,$vars); return;}
    if($n->nodeName=="#comment") {if(substr($n->nodeValue,0,1)!="#")$n->nodeValue=$this->evaluate($n->nodeValue,$vars); return;}
    foreach($n->attributes as $a)
    {
      $res = $this->evaluate($a->nodeValue,$vars);
      $a->nodeValue = $res;
      if($a->nodeValue!=$res){
        //Trying to correct PHP Bug #36795
        $a->nodeValue = str_replace('&','&amp;',$res);
      }
      if($a->nodeValue!=$res){
        print('PHP Bug #36795 	Inappropriate "unterminated entity reference" in DOMElement->setAttribute');
      }
    }
    /* Tags that has its own context (_hasContext) */
    if($n->nodeName=="foreach") return;
    if($n->nodeName=="component") return;
    if($n->getAttribute("_hasContext")==="true") return;
    $this->evalChildNodes($n,$vars);
  }
  function evalChildNodes($n,$vars)
  {
    foreach($n->childNodes as $ch)
    {
      self::evalNode($ch,$vars);
    }
  }
  static function autoCast($e){
    if(is_numeric($e))return (float) $e;
    if($e=="true") return true;
    if($e=="false") return false;
    return $e;
  }
}
