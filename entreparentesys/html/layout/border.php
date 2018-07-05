<?php
  foreach($CONTENT as $k=>$node) if(is_array($node) && is_numeric($k)) {
    if($node['region']=='north') {
      ?><div class="row"><?php
      Router::resolveRoute('html/'.strtolower($node['class']).'.php', $node, @$node['items']);
      ?></div><?php
    }
  }
?>
<div class="row">
<?php
  foreach($CONTENT as $k=>$node) if(is_array($node) && is_numeric($k)) {
    if($node['region']=='west') {
      ?><div class="col-md-<?=@$node['width']?$node['width']:'3'?>"><?php
      Router::resolveRoute('html/'.strtolower($node['class']).'.php', $node, @$node['items']);
      ?></div><?php
    }
  }
?>
<?php
  foreach($CONTENT as $k=>$node) if(is_array($node) && is_numeric($k)) {
    if($node['region']=='center') {
      ?><div class="col-md-<?=@$node['width']?$node['width']:'6'?>"><?php
      Router::resolveRoute('html/'.strtolower($node['class']).'.php', $node, @$node['items']);
      ?></div><?php
    }
  }
?>
<?php
  foreach($CONTENT as $k=>$node) if(is_array($node) && is_numeric($k)) {
    if($node['region']=='east') {
      ?><div class="col-md-<?=@$node['width']?$node['width']:'3'?>"><?php
      Router::resolveRoute('html/'.strtolower($node['class']).'.php', $node, @$node['items']);
      ?></div><?php
    }
  }
?>
</div>
<?php
  foreach($CONTENT as $k=>$node) if(is_array($node) && is_numeric($k)) {
    if($node['region']=='south') {
      ?><div class="container-fluid"><?php
      Router::resolveRoute('html/'.strtolower($node['class']).'.php', $node, @$node['items']);
      ?></div><?php
    }
  }
?>