#!/usr/local/bin/php
<?php
require('vendor/autoload.php');
use Symfony\Component\Console\Helper\Helper as HelperBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Hoathis\SymfonyConsoleBridge\Helper as Helper;
use presentacion\Application;

$app = new Application();

$app
    ->register('start')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        /* @var $cursor Helper\CursorHelper*/
        /* @var $window Helper\WindowHelper*/
        /* @var $svg SvgHelper*/
        /* @var $tput Helper\TputHelper*/
        /* @var $readline Helper\ReadlineHelper*/
        $cursor = $app->getHelperSet()->get('cursor');
        $svg = $app->getHelperSet()->get('svg');
        $window = $app->getHelperSet()->get('window');
        $tput = $app->getHelperSet()->get('tput');
        $readline = $app->getHelperSet()->get('readline');
        
        $cursor->clear($output, Helper\CursorHelper::CLEAR_SCREEN);
        $file = 'drawing1.svg';
        $svg->draw($cursor, $output, $file, 100*2, 10000, 'PM');
        
        $cursor->moveTo($output, 40, 20);
        $output->write('1. PROPEL CHANGES');
        
        $cursor->moveTo($output, 40, 21);
        $output->write('2. COMPOSER CHANGES');
        
        $cursor->moveTo($output, 40, 22);
        $output->write('3. SESSION CHANGES');
        echo "\n\n";
        try{@$readline->read($output, '');}catch(\Exception $e){}
    });
$app
    ->register('propel1')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        /* @var $cursor Helper\CursorHelper*/
        /* @var $window Helper\WindowHelper*/
        /* @var $svg SvgHelper*/
        /* @var $tput Helper\TputHelper*/
        /* @var $readline Helper\ReadlineHelper*/
        $cursor = $app->getHelperSet()->get('cursor');
        $svg = $app->getHelperSet()->get('svg');
        $window = $app->getHelperSet()->get('window');
        $tput = $app->getHelperSet()->get('tput');
        $readline = $app->getHelperSet()->get('readline');
        
        $cursor->clear($output, Helper\CursorHelper::CLEAR_SCREEN);

        $cursor->moveTo($output, 1, 2);
        $output->write('1. PROPEL');
        echo '
--------------------------------------------';
        type('

  Actualizacion de version, de 1.2.1 a 1.7.1

    a) Clases Query');
        waitUser($readline, $output);
    });
$app
    ->register('propel2')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        /* @var $cursor Helper\CursorHelper*/
        /* @var $window Helper\WindowHelper*/
        /* @var $svg SvgHelper*/
        /* @var $tput Helper\TputHelper*/
        /* @var $readline Helper\ReadlineHelper*/
        $cursor = $app->getHelperSet()->get('cursor');
        $svg = $app->getHelperSet()->get('svg');
        $window = $app->getHelperSet()->get('window');
        $tput = $app->getHelperSet()->get('tput');
        $readline = $app->getHelperSet()->get('readline');
        
        type('
    b) Uso de PDO en lugar de Creole');
        waitUser($readline, $output);
    });

$app
    ->register('propel3')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        /* @var $cursor Helper\CursorHelper*/
        /* @var $window Helper\WindowHelper*/
        /* @var $svg SvgHelper*/
        /* @var $tput Helper\TputHelper*/
        /* @var $readline Helper\ReadlineHelper*/
        $cursor = $app->getHelperSet()->get('cursor');
        $svg = $app->getHelperSet()->get('svg');
        $window = $app->getHelperSet()->get('window');
        $tput = $app->getHelperSet()->get('tput');
        $readline = $app->getHelperSet()->get('readline');
        
        type('
    c) Joins');
        waitUser($readline, $output);
    });

$app
    ->register('propel4')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        /* @var $cursor Helper\CursorHelper*/
        /* @var $window Helper\WindowHelper*/
        /* @var $svg SvgHelper*/
        /* @var $tput Helper\TputHelper*/
        /* @var $readline Helper\ReadlineHelper*/
        $cursor = $app->getHelperSet()->get('cursor');
        $svg = $app->getHelperSet()->get('svg');
        $window = $app->getHelperSet()->get('window');
        $tput = $app->getHelperSet()->get('tput');
        $readline = $app->getHelperSet()->get('readline');
        
        type('
    d) Multiple Joins');
        waitUser($readline, $output);
    });
$app
    ->register('propel5')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        /* @var $cursor Helper\CursorHelper*/
        /* @var $window Helper\WindowHelper*/
        /* @var $svg SvgHelper*/
        /* @var $tput Helper\TputHelper*/
        /* @var $readline Helper\ReadlineHelper*/
        $cursor = $app->getHelperSet()->get('cursor');
        $svg = $app->getHelperSet()->get('svg');
        $window = $app->getHelperSet()->get('window');
        $tput = $app->getHelperSet()->get('tput');
        $readline = $app->getHelperSet()->get('readline');
        
        type('
    e) Cache');
        waitUser($readline, $output);
    });
$app
    ->register('composer1')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        /* @var $cursor Helper\CursorHelper*/
        /* @var $window Helper\WindowHelper*/
        /* @var $svg SvgHelper*/
        /* @var $tput Helper\TputHelper*/
        /* @var $readline Helper\ReadlineHelper*/
        $cursor = $app->getHelperSet()->get('cursor');
        $svg = $app->getHelperSet()->get('svg');
        $window = $app->getHelperSet()->get('window');
        $tput = $app->getHelperSet()->get('tput');
        $readline = $app->getHelperSet()->get('readline');
        
        $cursor->clear($output, Helper\CursorHelper::CLEAR_SCREEN);

        $cursor->moveTo($output, 1, 2);
        $output->write('2. COMPOSER');
        echo '
--------------------------------------------';
        type('

  Se implemento Composer como cargador de clases.

    a) Quedan obsoletos los metodos G::loadClass() G::loadSystem() G::loadRbacClass()');
        waitUser($readline, $output);
    });
$app
    ->register('composer2')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        /* @var $cursor Helper\CursorHelper*/
        /* @var $window Helper\WindowHelper*/
        /* @var $svg SvgHelper*/
        /* @var $tput Helper\TputHelper*/
        /* @var $readline Helper\ReadlineHelper*/
        $cursor = $app->getHelperSet()->get('cursor');
        $svg = $app->getHelperSet()->get('svg');
        $window = $app->getHelperSet()->get('window');
        $tput = $app->getHelperSet()->get('tput');
        $readline = $app->getHelperSet()->get('readline');
        
        type('
    b) De preferencia no usar require o require_once');
        waitUser($readline, $output);
    });
$app
    ->register('composer3')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        /* @var $cursor Helper\CursorHelper*/
        /* @var $window Helper\WindowHelper*/
        /* @var $svg SvgHelper*/
        /* @var $tput Helper\TputHelper*/
        /* @var $readline Helper\ReadlineHelper*/
        $cursor = $app->getHelperSet()->get('cursor');
        $svg = $app->getHelperSet()->get('svg');
        $window = $app->getHelperSet()->get('window');
        $tput = $app->getHelperSet()->get('tput');
        $readline = $app->getHelperSet()->get('readline');
        
        type('
    c) Paquetes de thrid party preferentemente deben cambiarse a composer');
        waitUser($readline, $output);
    });
$app
    ->register('composer4')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        /* @var $cursor Helper\CursorHelper*/
        /* @var $window Helper\WindowHelper*/
        /* @var $svg SvgHelper*/
        /* @var $tput Helper\TputHelper*/
        /* @var $readline Helper\ReadlineHelper*/
        $cursor = $app->getHelperSet()->get('cursor');
        $svg = $app->getHelperSet()->get('svg');
        $window = $app->getHelperSet()->get('window');
        $tput = $app->getHelperSet()->get('tput');
        $readline = $app->getHelperSet()->get('readline');
        
        type('
    d) Configuracion y uso de composer');
        waitUser($readline, $output);
    });
$app
    ->register('session1')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        /* @var $cursor Helper\CursorHelper*/
        /* @var $window Helper\WindowHelper*/
        /* @var $svg SvgHelper*/
        /* @var $tput Helper\TputHelper*/
        /* @var $readline Helper\ReadlineHelper*/
        $cursor = $app->getHelperSet()->get('cursor');
        $svg = $app->getHelperSet()->get('svg');
        $window = $app->getHelperSet()->get('window');
        $tput = $app->getHelperSet()->get('tput');
        $readline = $app->getHelperSet()->get('readline');
        
        $cursor->clear($output, Helper\CursorHelper::CLEAR_SCREEN);

        $cursor->moveTo($output, 1, 2);
        $output->write('3. SESSION');
        echo '
--------------------------------------------';
        type('

  Se implemento RequestManager y SessionManager de Laravel.

    a) Como acceder al request');
        waitUser($readline, $output);
    });
$app
    ->register('session2')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        /* @var $cursor Helper\CursorHelper*/
        /* @var $window Helper\WindowHelper*/
        /* @var $svg SvgHelper*/
        /* @var $tput Helper\TputHelper*/
        /* @var $readline Helper\ReadlineHelper*/
        $cursor = $app->getHelperSet()->get('cursor');
        $svg = $app->getHelperSet()->get('svg');
        $window = $app->getHelperSet()->get('window');
        $tput = $app->getHelperSet()->get('tput');
        $readline = $app->getHelperSet()->get('readline');
        
        type('
    b) Como acceder a la session');
        waitUser($readline, $output);
    });

$app
    ->register('end')
    ->setCode(function(InputInterface $input, OutputInterface $output) use ($app) {
        /* @var $cursor Helper\CursorHelper*/
        /* @var $window Helper\WindowHelper*/
        /* @var $svg SvgHelper*/
        /* @var $tput Helper\TputHelper*/
        /* @var $readline Helper\ReadlineHelper*/
        $cursor = $app->getHelperSet()->get('cursor');
        $svg = $app->getHelperSet()->get('svg');
        $window = $app->getHelperSet()->get('window');
        $tput = $app->getHelperSet()->get('tput');
        $readline = $app->getHelperSet()->get('readline');
        
        $cursor->clear($output, Helper\CursorHelper::CLEAR_SCREEN);
        //$file = 'drawing2.svg';
        //$svg->draw($cursor, $output, $file, 100*2, 10000, 'O0O');
        
        $cursor->moveTo($output, 1, 1);
        echo '
        
                    _ ¶¶¶¶¶¶¶¶ 
                 ¶¶¶¶¶        ¶¶¶¶¶ 
               ¶¶¶                 ¶¶¶ 
             ¶¶¶                     ¶¶¶ 
            ¶¶                         ¶¶ 
           ¶       ¶¶¶     ¶¶¶          ¶¶ 
          ¶          ¶¶      ¶¶          ¶¶ 
         ¶¶           ¶¶      ¶¶         ¶¶ 
         ¶             ¶¶      ¶¶   ¶¶¶   ¶¶ 
        ¶¶      ¶¶     ¶¶      ¶¶     ¶¶  ¶¶ 
        ¶¶    ¶¶¶      ¶¶      ¶¶      ¶¶ ¶¶ 
        ¶¶   ¶¶¶¶¶                  ¶¶ ¶¶ ¶¶ 
         ¶   ¶¶  ¶¶                 ¶¶    ¶¶ 
         ¶¶       ¶¶              ¶¶¶    ¶¶ 
          ¶¶       ¶¶            ¶¶¶     ¶¶     GRACIAS POR SU ATENCION
           ¶¶        ¶¶¶¶     ¶¶¶¶      ¶¶ 
            ¶¶          ¶¶¶¶¶¶¶        ¶¶ 
              ¶¶                     ¶¶ 
                ¶¶¶               ¶¶¶ 
                   ¶¶¶¶¶¶¶¶¶¶¶¶¶¶¶
    
        ';
        
        echo "\n\n";
        try{@$readline->read($output, '');}catch(\Exception $e){}
    });

$app->run();


function type($text, $usleep=20000){
    for($i=0,$l=strlen($text);$i<$l;$i++){
        echo $text[$i];
        usleep($usleep);
    }
}
function waitUser($readline, $output){
    try{@$readline->read($output, '');}catch(\Exception $e){}
}