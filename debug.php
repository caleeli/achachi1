<?php
ini_set("display_errors","on");
error_reporting(E_ALL);
require_once("mydebug.php");die;

class debug {
  function doRequest(){
    if(!isset($_GET["f"])) return self::gui();
    $f=$_GET["f"];
    if(isset($_GET["p"]) && ($p=json_decode($_GET["p"]))) {
      $sp='$p['.implode('],$p[',array_keys($p)).']';
    } else {
      $sp='';
    }
    eval('$r=self::'.$f.'('.$sp.');');
    print(json_encode($r));
  }
  function writeTo($ss){
    global $_S;
    file_put_contents("$ss.debug.tmp",serialize($_S));
  }
  function load($ss){
    global $_S;
    //eval('$_S='.file_get_contents("$ss.debug.tmp").';');
    $_S=unserialize(file_get_contents("$ss.debug.tmp"));
  }
  function isOpened($ss){
    return file_exists("$ss.debug.tmp");
  }
  function sessionStart() {
    global $_S;
    $_S=array('bp'=>null);
    $ss=str_replace(".","", microtime(true));
    self::writeTo($ss);
    return $ss;
  }
  function sessionClose($ss){
    unlink("$ss.debug.tmp");
  }
  function getSessions(){
    $res=array();
    foreach(glob("*.debug.tmp") as $f) $res[]=basename($f,".debug.tmp");
    return $res;
  }
  function getBpoint($ss){
    global $_S;
    self::load($ss);
    return $_S['bp'];
  }
  function setBpoint($ss,$bp){
    global $_S;
    self::load($ss);
    $_S['bp']=$bp;
    $_S['res']="";
    self::writeTo($ss);
  }
  function setResponse($ss,$res){
    global $_S;
    self::load($ss);
    $_S["res"]=$res;
    $_S["bp"]=null;
    self::writeTo($ss);
  }
  function getResponse($ss){
    global $_S;
    self::load($ss);
    return $_S["res"];
  }
/**/
  function gui($ss=null){
    if(!$ss || !self::isOpened($ss)){
      $sess=self::getSessions();
      if($sess) {
        $ss=$sess[count($sess)-1];
        print("<script>window.location.href=" . json_encode("?f=gui&p=".urlencode(json_encode(array($ss)))) . ";</script>");
        die;
      }
      print("No existen sesiones abiertas.");
    } else {
      if(isset($_POST["res"])) {
        self::setResponse($ss, $_POST["res"]);
        print("<script>window.location.href=" . json_encode("?f=gui&p=".urlencode(json_encode(array($ss)))) . ";</script>");
      } elseif($bp=self::getBpoint($ss)) {
        print("<pre>".htmlentities($bp->content)."</pre>");
        print('<form action="?f=gui&p='.urlencode(json_encode(array($ss))).'" method="POST">'.
          //'<textarea name="res" cols=80></textarea><input type="submit" value="submit" />'.
          '<input name="res" size=80 /><input type="submit" value="submit" />'.
          '<input type="submit" value="|> continue" onclick="this.form.res.value=\'return;\';" />'.
          '</form>');
        printTrace($bp->trace);
      } else {
        print("<script>window.location.href=" . json_encode("?f=gui&p=".urlencode(json_encode(array($ss)))) . ";</script>");
      }
    }
    return "";
  }
}
function printTrace($trace){
  print("<pre>");
  foreach($trace as $t) {
    $t=(array)$t;
    userErrorTrace(isset($t["file"])?$t["file"]:'',isset($t["line"])?$t["line"]:0,(isset($t["object"])?(get_class($t["object"]).$t["type"]):(isset($t['class'])?($t['class'].$t["type"]):'')).$t["function"],isset($t["args"])?$t["args"]:array());
  }
  print("</pre>");
}
function userErrorTrace($filename,$linenum,$function=null,$vars=null) {
  $varsTypes=array();
  if(isset($vars)) foreach($vars as $v) $varsTypes[]=gettype($v);
  if(file_exists($filename)) @$fc=file($filename);
  else $fc=array($linenum-1=>"$function(".implode(",",$varsTypes).")\n");
  //href='file:///".htmlentities(str_replace("\\","/",$filename))."'
  print("<div style='background-color:lightyellow;'><a >$filename </a></div>");
  print("<b>$function</b>(".printVariables($vars).")\n");
/*  if(isset($fc[$linenum-3]))print("<span style='background-color:lightgrey;'>".($linenum-2).": </span><span style='background-color:white;'>".htmlentities($fc[$linenum-3])."</span>");
  if(isset($fc[$linenum-2]))print("<span style='background-color:lightgrey;'>".($linenum-1).": </span><span style='background-color:white;'>".htmlentities($fc[$linenum-2])."</span>");
  if(isset($fc[$linenum-1]))print("<span style='background-color:lightgrey;'>$linenum: </span><span style='background-color:LightSkyBlue;'>".htmlentities($fc[$linenum-1])."</span>");
  if(isset($fc[$linenum]))print("<span style='background-color:lightgrey;'>".($linenum+1).": </span><span style='background-color:white;'>".htmlentities($fc[$linenum])."</span>");
  if(isset($fc[$linenum+1]))print("<span style='background-color:lightgrey;'>".($linenum+2).": </span><span style='background-color:white;'>".htmlentities($fc[$linenum+1])."</span>");*/
  print("</pre>");printCode(implode("",$fc),true,$linenum);print("<pre>");
  //print("</pre>");highlight_string(implode("",$fc));print("<pre>");
}
function printVariables($vars){
  $res="";
  if(isset($vars))
  foreach($vars as $v) {
    if($res!="")$res.=",";
    $res.=("<select>");
      $val=explode("\n",print_r($v,true));
      foreach($val as $val_) $res.=("<option>".htmlentities($val_)."</option>");
    $res.=("</select>");
  }
  return $res;
}


debug::doRequest();


 function printCode($code, $lines_number = 0,$breakpoint)    {
              
         if (!is_array($code)) $codeE = explode("\n", $code);
        $count_lines = count($codeE);
?>
<style type="text/css">
.linenum{
    text-align:right;
    line-height:14px;
    background:#FDECE1;
    border:1px solid #cc6666;
    padding:0px 1px 0px 1px;
    font-family:Courier New, Courier;
    float:left;
    width:17px;
    margin:0px 0px 30px 0px;
    }

.code    {/* safari/konq hack */
    font-family:Courier New, Courier;
    line-height:14px;
    position:relative;
    height:150px;
    overflow:auto;
}

.linetext{
/*    width:700px;
    text-align:left;
    line-height:14px;
    background:transparent;
    border:1px solid #cc6666;
    border-left:0px;
    padding:0px 1px 0px 8px;
    font-family:Courier New, Courier;
    float:left;
    margin:3px 0px 30px 0px;*/
    
    }

br.clear    {
    clear:both;
}

.breakpoint{
    line-height:14px;
    position:absolute;
    background-color:lightblue;
    width:100%;
    z-index:-1;
}

</style> 
<?php
        $r1 = "";

         if ($lines_number){           
                $r1 .= "<div class=\"linenum\">";
                foreach($codeE as $line =>$c) {    
                    if($count_lines=='1')
                        $r1 .= "1<br>";
                    else
                      if($line+1==$breakpoint) $r1 .= ($line+1).'<span class="breakpoint">&nbsp;</span><br />';
                      else $r1 .= ($line == ($count_lines - 1)) ? "" :  ($line+1)."<br />";
                 }
                 $r1 .= "</div>";
         }

         $r2 = "<div class=\"linetext\">";
         $r2 .= highlight_string($code,1);
         $r2 .= "</div>";

        $r = $r1.$r2;
        $id=microtime(true);
        echo "<div class=\"code\" id='$id'>".$r."</div><script>document.getElementById('$id').scrollTop=".(14*($breakpoint-2))."</script>";
    }
