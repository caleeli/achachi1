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

  class ext
  {
    public static function dom2array($node)
    {
      if($node->nodeName=="#text") return;
      if($node->nodeName=="#comment") return;
      $arr=array();
      foreach($node->attributes as $a)
      {
        if(substr($a->nodeName,0,2)!="__") $arr[$a->nodeName]=$a->nodeValue;
      }
      list($arr['items'],$att)=self::nodes2array($node->childNodes);
      foreach($att as $a => $v) $arr[$a]=$v;
      foreach($arr as $a =>$v)
      {
        $arr[$a]=node::autoCast($v);
      }
      @$class=$node->getAttribute("class");
      $onload=isset($arr["_onload"])?$arr["_onload"]:null;
      if(isset($arr["_construct"]))$onload=$arr["_construct"];
      unset($arr["class"]);
      unset($arr["_onload"]);
      unset($arr["_construct"]);
      if($node->nodeName=="layout") {$class="Ext.Viewport";$arr["renderTo"]=self::toExpression("Ext.getBody()");}
      if($node->nodeName=="window") {$class="Ext.Window";$onload="obj.show();";}
      if($node->nodeName=="#text") return array();
      if($node->nodeName=="extdocument")
      {
        $res="";
        foreach($arr['items'] as $item)
        {
          if($res!="")$res.="\n";
          $res.=self::toJson($item,true);
        }
        return $res;
      }
      else
      {
        //return self::toExpression('extLoad("claa",'.self::toJson(self::toExpression('extLoad("\"")')).')');
        //For Firefox 2.0 When "items:[]" cannot create a "Ext.tree.TreePanel"
        if(count($arr["items"])==0) unset($arr["items"]);
        $res = self::toExpression("extLoad(".self::toJson($class).",".self::toJson($arr).",".self::toJson($onload).")");
        return array(array($node->nodeName, $res));
      }
    }
    public static function nodes2array($childNodes)
    {
      $arr=array();
      $att=array();
      foreach($childNodes as $ch)
      {
        $nodeName=$ch->nodeName;
        if($nodeName=="extjs")
        {
          $res=self::dom2array($ch);
          $res=$res[0][1];
          if(isset($res))$arr[]=$res;
        }
        elseif($nodeName=="#text"){}
        elseif($nodeName=="#comment"){}
        else
        {
          global $core;
          $res=$core->makeFromNode($ch);
          foreach($res as $r) {
            if($r[0]=="attribute" || $r[0]=="ext.attribute" || $r[0]=="event" || $r[0]=="expression"){
              $att[$r[1][0]]=$r[1][1];
            } else {
              $arr[]=$r[1];
            }
          }
        }
      }
      return array($arr,$att);
    }
    public static function toExpression($exp)
    {
      return "*".(strlen(self::toJson($exp))-2)."#".$exp;
      return array("eval"=>$exp);
    }
    public static function toJson($o,$pri=false)
    {
      $j=json_encode($o);
      while(preg_match('/"\*(\d+)#/',$j,$match,PREG_OFFSET_CAPTURE))
      {
        $size=$match[1][0];
        $pos=$match[0][1];
        $pos1=$pos+3+strlen($match[1][0]);
        $size2=$size+4+strlen($match[1][0]);
        $j=substr($j,0,$pos). json_decode('"'.substr($j,$pos1,$size).'"') .substr($j,$pos+$size2);
      }
      return $j;
    }
  }
