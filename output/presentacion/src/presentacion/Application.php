<?php
namespace presentacion;

use Symfony\Component\Console\Helper\Helper as HelperBase;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Hoathis\SymfonyConsoleBridge\Helper as Helper;

class Application extends BaseApplication
{
    protected function getDefaultHelperSet()
    {
        $set = parent::getDefaultHelperSet();

        $set->set(new Helper\WindowHelper());
        $set->set(new Helper\CursorHelper());
        $set->set(new Helper\ReadlineHelper());
        $set->set(new Helper\PagerHelper());
        $set->set(new Helper\TputHelper());
        $set->set(new SvgHelper());

        return $set;
    }

}

