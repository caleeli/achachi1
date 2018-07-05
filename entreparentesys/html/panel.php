<div class="panel <?=@$DATA['cssClass']?>">
<div class="panel-heading">
<?php if(!empty($DATA['title'])) { ?><h2 class="form-signin-heading"><?=$DATA['title'] ?></h2><?php } ?>
<?php if(!empty($DATA['tbar'])) { ?>
<div class="btn-group pull-right">
<?php Router::resolveRoute('html/content.php', $DATA, $DATA['tbar']); ?>
</div>
<?php } ?>
</div>
<?php 
if(!empty($DATA['html'])){
  echo $DATA['html'];
}
if(!empty($DATA['layout'])){
  Router::resolveRoute('html/layout/'.$DATA['layout'].'.php', $DATA, $CONTENT);
} else {
  Router::resolveRoute('html/content.php', $DATA, $CONTENT);
}
?>

</div>