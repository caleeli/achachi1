<div class="modal fade" id="<?=$DATA['id']?>" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?=$DATA['title']?></h4>
      </div>
      <div class="modal-body">
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
      <div class="modal-footer">
<?php
if(!empty($DATA['buttons'])){
  Router::resolveRoute('html/content.php', $DATA, $DATA['buttons']);
}
?>
      </div>
    </div>
  </div>
</div>