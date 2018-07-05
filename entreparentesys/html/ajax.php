<div id="<?=$id=uniqid('af')?>" class="<?=@$DATA['cssClass']?>"></div><script>
(function(){
  APP.ajaxLoad("#<?=$id?>", <?=json_encode($DATA['url'])?>);
})();
</script>