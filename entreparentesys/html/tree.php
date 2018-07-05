<?php
$openIcon='glyphicon-folder-open';
$closeIcon='glyphicon-folder-close';
$leafIcon='glyphicon-circle-arrow-right';
$draw=function($model,$class='',$id='') use(&$draw, $openIcon, $closeIcon, $leafIcon) {
?>
<ul <?=$id?"id='$id' ":""?>class="<?=$class?>">
<?php
if($model instanceof \model\ModelInterface) {
  $rows=$model->fetchAll();
  foreach($rows as $row){
    $model->bindParam('parent',$row->id);
    $model->execute();
    ?><li><span><i class="glyphicon <?=$row->leaf=='1'?$leafIcon:$openIcon?>"></i> </span> <a href="#<?=$row->id ?>"><?=$row->text ?></a><?php $draw($model, '');?></li><?php
  }
}
?>
</ul>
<?php
};
$id=uniqid('tree');
$draw($DATA['model'], "tree ".@$DATA['cssClass'], $id);
?>
<script>
(function () {
    $('#<?=$id?> li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
    $('#<?=$id?> li.parent_li > span').on('click', function (e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")) {
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').addClass('<?=$closeIcon?>').removeClass('<?=$openIcon?>');
        } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this branch').find(' > i').addClass('<?=$openIcon?>').removeClass('<?=$closeIcon?>');
        }
        e.stopPropagation();
    });
})();
</script>