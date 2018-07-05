<?php
if(isset($_GET["action"])){
  $base = dirname($_GET["file"]);
  $path = $_GET["path"];
  chdir($path);
  if($_GET["action"]=="delete"){
    unlink($_GET["file"]);
    print "window.location.reload(true);";
  }
  if($_GET["action"]=="rename"){
    chdir(dirname($_GET["file"]));
    rename(basename($_GET["file"]), $_GET["name"]);
    print "window.location.reload(true);";
  }
  die;
}
if(isset($_GET["img"])){
  header( "Content-type: " . mime_content_type($_GET["img"]));
  readfile($_GET["img"]);
  die;
}
$base = dirname($_GET["file"]);
$path = $_GET["path"];
if(!(substr($path,0,1)=="/" || substr($path,1,1)==":")) $path=$base."/".$path;
if(!file_exists($path)) mkdir($path, 0777, true);
if(isset($_GET["download"])){
  chdir($path);
  $name = basename($_GET["url"]);
  file_put_contents($name, req($_GET["url"]));
  print "window.location.reload(true);";
  die;
}
?>
<html>
<head>
<script src="jquery/js/jquery-1.5.1.min.js"></script>
<style>
img{
  border:3px double black;
  margin:1;
}
img:hover{
  border:3px solid black;
  margin:1;
}
tr:hover{
  background-color:lightyellow;
}
td:{
  background-color:transparent;
}
.selected{
  background-color:lightblue;
}
</style>
<script>
var name;
function selectImg(img){
  $.ajax({
    url:"googleimage.php",
    type:"GET",
    dataType:'text',
    data:{
      "url":img.src,
      "download":"true",
      "file":window.parent.file,
      "path":window.parent.currentNode.getAttribute('path'),
      "folder":window.parent.currentNode.getAttribute('folder'),
      "t":(new Date().getTime())
    },
    success:function(e){
      eval(e);
    }
  });
}
function delimg(file){
  $.ajax({
    url:"googleimage.php",
    type:"GET",
    dataType:'text',
    data:{
      "action":"delete",
      "file":file,
      "path":window.parent.currentNode.getAttribute('path'),
      "folder":window.parent.currentNode.getAttribute('folder'),
      "t":(new Date().getTime())
    },
    success:function(e){
      eval(e);
    }
  });
}
function chgname(file,name){
  $.ajax({
    url:"googleimage.php",
    type:"GET",
    dataType:'text',
    data:{
      "action":"rename",
      "file":file,
      "name":name,
      "path":window.parent.currentNode.getAttribute('path'),
      "folder":window.parent.currentNode.getAttribute('folder'),
      "t":(new Date().getTime())
    },
    success:function(e){
      eval(e);
    }
  });
}
</script>
</head>
<body>
<?php
global $online;
$online=array();
function checkProxy($url, $port){
  global $online;
  if(isset($online[$url])) return $online[$url];
  $resURL = curl_init(); 
  curl_setopt($resURL, CURLOPT_URL, $url); 
  curl_setopt($resURL, CURLOPT_BINARYTRANSFER, 1); 
  curl_setopt($resURL, CURLOPT_HEADERFUNCTION, 'curlHeaderCallback'); 
  curl_setopt($resURL, CURLOPT_FAILONERROR, 1); 
  curl_setopt($ch, CURLOPT_PROXYPORT, $port);
  curl_exec ($resURL); 
  $intReturnCode = curl_getinfo($resURL, CURLINFO_HTTP_CODE); 
  curl_close ($resURL); 
  if ($intReturnCode != 200 && $intReturnCode != 302 && $intReturnCode != 304) { 
      return $online[$url]=false;
  }
  else return $online[$url]=true;
}
function req($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
    curl_setopt($ch, CURLOPT_URL, $url);
/*    if(checkProxy("http://192.168.72.36",8080)) {
      curl_setopt($ch, CURLOPT_PROXY, "http://192.168.72.36");
      curl_setopt($ch, CURLOPT_PROXYPORT, 8080);
    }*/
    $response = curl_exec($ch);
    return $response;
}

function domreq($url){
    $response = req($url);
    $dom = new DomDocument();
    $dom->loadHTML($response);
    return $dom;
}

function searchImage($q,$w,$h,$start=0){
  
//  $q=urlencode("imagesize:{$w}x{$h} $q");
//  $url="http://www.google.com/search?q=".$q."&tbm=isch&start=$start";  //&hl=es&cr=&safe=images&tbs=isz:ex,iszw:32,iszh:32
  $q=urlencode("$q");
  $url="http://www.google.com/search?tbm=isch&start=20&q=".$q."&tbs=isz:ex,iszw:".$w.",iszh:".$h;
  $doc = domreq($url);
  $imgs = $doc->getElementsByTagName("img");
  foreach($imgs as $img) if(substr($img->getAttribute("src"),0,5)=="http:"){
    print("<span style='display:inline-block'>");
    print '<img title="'.$img->getAttribute("src").'" src="'.$img->getAttribute("src").'" width="'.$w.'" height="'.$h.'" onclick="selectImg(this)"/><br />';
    $u = $img->parentNode->getAttribute("href");
    parse_str($u,$p);
    print '<img title="'.htmlentities($p["/imgres?imgurl"]).'" src="'.htmlentities($p["/imgres?imgurl"]).'" width="'.$w.'" height="'.$h.'" onclick="selectImg(this)"/>';
    print("</span>");
  }
}

$name = isset($_GET["name"])?$_GET["name"]:"";
$img = isset($_GET["img"])?$_GET["img"]:"";
$size = isset($_GET["size"])?$_GET["size"]:"16x16";
?>
<div style="overflow:auto;height:70%;">
<?php
foreach(glob($path."/*") as $f){
?>
<span style="display:inline-block;text-align:center;position:relative;">
<button style="position:absolute;right:0px;color:red;" onclick="delimg(<?php print htmlentities(json_encode($f), ENT_QUOTES,'utf-8'); ?>)">x</button>
<img src="?img=<?php print htmlentities(urlencode(realpath($f))); ?>" style="max-width:64px" /><br/>
<input value="<?php print htmlentities( basename($f), ENT_QUOTES, 'utf-8' ); ?>" onchange="chgname(<?php print htmlentities(json_encode($f), ENT_QUOTES,'utf-8'); ?>, this.value)" />
</span>
<?php
}
?>
</div>
<form method="GET">
  Search: <input id="q" name="q" value="<?=@htmlentities($_GET["q"]) ?>"/>Size:<input name="size" value="<?=@htmlentities($size) ?>" /><br />
  <input id="name" name="name" type="hidden"/>
  <input id="file" name="file" type="hidden" value="<?php print $_GET['file']; ?>"/>
  <input id="path" name="path" type="hidden" value="<?php print $_GET['path']; ?>"/>
  <button type="submit">Buscar</button>
</form>
<?php
if(isset($_GET["q"])){
  if($name){
    ?>
    <script>var value=$("#q")[0].value;$("#<?=str_replace(".",'\\\\.',$name)?>")[0].onclick();$("#q")[0].value=value;</script>
    <?php
  }
  $size=explode("x",$size);
  searchImage( $_GET["q"], $size[0], $size[1] ,0);
  searchImage( $_GET["q"], $size[0], $size[1] ,20);
}
?>
</body>
</html>