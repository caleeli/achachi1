<!doctype html>
<!--[if lt IE 7]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<head>
    <meta charset="UTF-8">
    <title><?=$DATA['title']?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link rel="shortcut icon" href="<?=\Router::url('favicon.png')?>">

    <link rel="stylesheet" href="<?=\Router::url('oleose/css/bootstrap.css')?>">
    
    <link rel="stylesheet" href="<?=\Router::url('oleose/css/animate.css')?>">
    <link rel="stylesheet" href="<?=\Router::url('oleose/css/font-awesome.min.css')?>">
    <link rel="stylesheet" href="<?=\Router::url('oleose/css/slick.css')?>">
    <link rel="stylesheet" href="<?=\Router::url('oleose/js/rs-plugin/css/settings.css')?>">

    <link rel="stylesheet" href="<?=\Router::url('oleose/css/freeze.css')?>">

    <script type="text/javascript" src="<?=\Router::url('oleose/js/modernizr.custom.32033.js')?>"></script>
    
</head>

<body>

    <div class="pre-loader">
        <div class="load-con">
            <img src="<?=\Router::url('oleose/img/freeze/logo.png')?>" class="animated fadeInDown" alt="">
            <div class="spinner">
              <div class="bounce1"></div>
              <div class="bounce2"></div>
              <div class="bounce3"></div>
            </div>
        </div>
    </div>

<?=$DATA['header']?>

    <script src="<?=\Router::url('oleose/js/jquery-1.11.1.min.js')?>"></script>
    <script src="<?=\Router::url('oleose/js/bootstrap.min.js')?>"></script>
    <script src="<?=\Router::url('oleose/js/slick.min.js')?>"></script>
    <script src="<?=\Router::url('oleose/js/placeholdem.min.js')?>"></script>
    <script src="<?=\Router::url('oleose/js/rs-plugin/js/jquery.themepunch.plugins.min.js')?>"></script>
    <script src="<?=\Router::url('oleose/js/rs-plugin/js/jquery.themepunch.revolution.min.js')?>"></script>
    <script src="<?=\Router::url('oleose/js/waypoints.min.js')?>"></script>
    <script src="<?=\Router::url('oleose/js/scripts.js')?>"></script>
    <script src="<?=\Router::url('oleose/js/ob.js')?>"></script>
    <script>
        $(document).ready(function() {
            appMaster.preLoader();
        });
    </script>

</body>
</html>