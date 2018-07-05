<?php
$id=uniqid('navbar');
?>
<nav class="navbar navbar-inverse <?=@$DATA['cssClass']?>">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#<?=$id?>" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div id="<?=$id?>" class="navbar-collapse collapse">
      <?php 
        if(!empty($DATA['html'])){
          echo $DATA['html'];
        }
        Router::resolveRoute('html/content.php', $DATA, $CONTENT); ?>
      <ul class="nav navbar-nav navbar-right"><?php 
        foreach($DATA['menu'] as $m){
          ?><li><a href="#<?=$m['id']?>"><?=$m['label']?></a></li><?php
        }
        ?>
      </ul>
      <form class="navbar-form navbar-right">
        <input type="text" class="form-control" placeholder="Search...">
      </form>
    </div>
  </div>
</nav>