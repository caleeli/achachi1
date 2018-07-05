<div class="row <?=@$DATA['cssClass']?>"><?php
  $c=0;
  foreach($CONTENT as $k=>$node) if(is_array($node) && is_numeric($k)) {
    $c++;
  }
  foreach($CONTENT as $k=>$node) if(is_array($node) && is_numeric($k)) {
    ?><div class="col-md-<?=floor(12/$c)?>"><?php
    Router::resolveRoute('html/'.strtolower($node['class']).'.php', $node, @$node['items']);
    ?></div><?php
  }
?>
</div>