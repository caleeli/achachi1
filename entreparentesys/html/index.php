<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<link rel="stylesheet" href="/lib/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="/lib/bootstrap/css/bootstrap-theme.min.css">
<!-- link rel="stylesheet" href="<?=\Router::url('../bootstrap/bootstrap-notify-master/css/bootstrap-notify.css')?>" -->
<link rel="stylesheet" href="/lib/bootstrap-notify-master/css/bootstrap-notify.css">
<link rel="stylesheet" href="/lib/datatables/media/css/jquery.dataTables.min.css" />
<?php 
if(!empty($CONTENT['stylesheets'])) {
  $styles = is_array($CONTENT['stylesheets']) ? $CONTENT['stylesheets'] : [$CONTENT['stylesheets']];
  foreach($styles as $style){
    ?><link href="<?=htmlentities($style, ENT_QUOTES, 'utf-8')?>" rel="stylesheet"><?php 
  }
}
?>
<script src="/lib/jquery.min.js"></script>
<script src="/lib/bootstrap/js/bootstrap.min.js"></script>
<script src="<?='/lib/draw3.js'?>"></script>
<script src="<?=\Router::url('html/driver.js')?>"></script>
<!-- script src="<?=\Router::url('../bootstrap/bootstrap-notify-master/js/bootstrap-notify.js')?>"></script -->
<script src="/lib/bootstrap-notify-master/js/bootstrap-notify.js"></script>
<script src="/lib/datatables/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script>
var APP={
  alert:function(msg){
    $('.top-left').notify({
        type: 'info',
        message: {
          text: msg
        }
      }).show();
  },
  message:function(msg){
    $('.bottom-right').notify({
        type: 'info',
        message: {
          text: msg
        }
      }).show();
  },
  success:function(msg){
    $('.top-right').notify({
        type: 'success',
        message: {
          text: msg
        }
      }).show();
  },
  warning:function(msg){
    $('.top-right').notify({
        type: 'warning',
        message: {
          text: msg
        }
      }).show();
  },
  error:function(msg){
    $('.top-right').notify({
        type: 'danger',
        message: {
          text: msg 
        }
      }).show();
  },
  ajaxLoad:function(selector, url){
    $div=$(selector);
    $div.addClass('ajax-loading');
    $div.html('<div class="ajax-loader"></div>');
    $.ajax({
      url:url,
      success:function(res){
        $div.html(res);
        $div.removeClass('ajax-loading');
      }
    });
  },
  observers:[],
  attachObserver:function(observer){
    this.observers.push(observer);
  },
  detachObserver:function(observer){
    var i=this.observers.indexOf(observer);
    if(i>=0){
      this.observers.splice(i,1);
    }
  },
  trigger:function(event){
    for(var i=0,l=this.observers.length;i<l;i++){
      this.observers[i].notify();
    }
  }
}
$(function(){
  $(window).hashchange(function(){
    APP.trigger(location.hash);
  });
});
</script>
<?php 
if(!empty($CONTENT['scripts'])) {
  $scripts = is_array($CONTENT['scripts']) ? $CONTENT['scripts'] : [$CONTENT['scripts']];
  foreach($scripts as $script){
    ?><script src="<?=htmlentities($script, ENT_QUOTES, 'utf-8')?>"></script><?php 
  }
}
?>
<style>
.tree {
    min-height:20px;
    /*padding:19px;*/
    margin-bottom:20px;
    /*background-color:#fbfbfb;*/
    /*border:1px solid #999;*/
    -webkit-border-radius:4px;
    -moz-border-radius:4px;
    /*border-radius:4px;*/
    -webkit-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    -moz-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05)
}
.tree li {
    list-style-type:none;
    margin:0;
    padding:10px 5px 0 5px;
    position:relative
}
.tree li::before, .tree li::after {
    content:'';
    left:-20px;
    position:absolute;
    right:auto
}
.tree li::before {
    border-left:1px solid #999;
    bottom:50px;
    height:100%;
    top:0;
    width:1px
}
.tree li::after {
    border-top:1px solid #999;
    height:20px;
    top:25px;
    width:25px
}
.tree li span {
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border:1px solid #999;
    border-radius:5px;
    display:inline-block;
    padding:3px 8px;
    text-decoration:none
}
.tree li.parent_li>span {
    cursor:pointer
}
.tree>ul>li::before, .tree>ul>li::after {
    border:0
}
.tree li:last-child::before {
    height:30px
}
.tree li.parent_li>span:hover, .tree li.parent_li>span:hover+ul li span {
    background:#eee;
    border:1px solid #94a0b4;
    color:#000
}
.ajax-loading{
  position:relative;
  overflow:hidden;
  width:100%;
  height:100%;
}
.ajax-loading .ajax-loader{
  box-shadow:0 0 1000px 500px rgba(0, 0, 0, 0.1);
  left: 50%;
  position: absolute;
  top: 50%;
  animation: spin 1s linear infinite alternate;
  -webkit-animation: spin 1s linear infinite alternate;
}
@keyframes spin
{
  from { box-shadow:0 0 1000px 700px rgba(0, 0, 0, 0.1); }
  50%  { box-shadow:0 0 1000px 650px rgba(0, 0, 0, 0.1); }
  to   { box-shadow:0 0 1000px 600px rgba(0, 0, 0, 0.1); }
}
@-webkit-keyframes spin
{
  from { box-shadow:0 0 1000px 700px rgba(0, 0, 0, 0.1); }
  50%  { box-shadow:0 0 1000px 650px rgba(0, 0, 0, 0.1); }
  to   { box-shadow:0 0 1000px 600px rgba(0, 0, 0, 0.1); }
}
</style>
</head>
<body>
<div class='notifications top-left'></div>
<div class='notifications top-right'></div>
<div class='notifications bottom-right'></div>
<?php \Router::resolveRoute('html/content.php', $DATA, $CONTENT) ?>

</body>
</html>