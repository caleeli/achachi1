<table id="<?=$id=uniqid('table')?>" class="display table table-bordered <?=$DATA['cssClass']?>" cellspacing="0">
<thead>
<tr><?php
foreach($DATA['columns'] as $c){
  ?><th><?=$c['text']?></th><?php
}
?>
</tr>
</thead>
<tfoot>
<tr><?php
foreach($DATA['columns'] as $c){
  ?><th><?=$c['text']?></th><?php
}
?>
</tr>
</tfoot>
</table>
<script>
(function(){
$('#<?=$id?>').dataTable( {
  "ajax": {
            "url": <?=json_encode(Html::url("html/datatable", $DATA['model'], $DATA['name']))?>,
            "dataSrc":""
        },
  "columns": [
    <?php
foreach($DATA['columns'] as $c){
  ?>{"data":<?=json_encode($c['name'])?>},<?php
}
?>

  ]
} );
var table=$('#<?=$id?>').DataTable();
$('#<?=$id?> tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
    }
    else {
        table.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
        $.ajax({
          method:'get',
          url:<?=json_encode(Html::listener("html/listener", $DATA['listeners']['onselectrow'], $DATA['name'].'onselectrow'))?>,
          data:{row:table.row( this ).data()},
        });
    }
} );
})();
</script>