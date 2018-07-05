<?php
namespace presentacion;

use Symfony\Component\Console\Helper\Helper as HelperBase;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Hoathis\SymfonyConsoleBridge\Helper as Helper;
use DomDocument;

class SvgHelper extends HelperBase {
    const NAME = 'svg';
    public $offsetY=0;
    public $scaleY=0.7;
    public $char='㋡';
    public function getName()
    {
        return self::NAME;
    }
    protected function cubicbezier($cursor, $output, $x0, $y0, $x1, $y1, $x2, $y2, $x3, $y3, $n = 20) {
        $pts = array();

        for($i = 0; $i <= $n; $i++) {
            $t = $i / $n;
            $t1 = 1 - $t;
            $a = pow($t1, 3);
            $b = 3 * $t * pow($t1, 2);
            $c = 3 * pow($t, 2) * $t1;
            $d = pow($t, 3);

            $x = round($a * $x0 + $b * $x1 + $c * $x2 + $d * $x3);
            $y = round($a * $y0 + $b * $y1 + $c * $y2 + $d * $y3);
            $pts[$i] = array($x, $y);
        }

        for($i = 0; $i < $n; $i++) {
            //imageline($img, $pts[$i][0], $pts[$i][1], $pts[$i+1][0], $pts[$i+1][1], $col);
            $this->line($cursor, $output, $pts[$i][0], $pts[$i][1], $pts[$i+1][0], $pts[$i+1][1]);
        }
    }
    function line($cursor, $output, $x0,$y0,$x1,$y1){
//        $y0*=$this->scaleY;$y1*=$this->scaleY;
//        $y0+=$this->offsetY;$y1+=$this->offsetY;
        $x=1+floor($x0*$this->zoom);
        $y=1+floor($y0*$this->zoom);
        $x2=1+floor($x1*$this->zoom);
        $y2=1+floor($y1*$this->zoom);
        if(($x2==$x) && ($y2==$y)) {
        } elseif(abs($x2-$x)>abs($y2-$y)) {
            $diff=$x2>$x?1:-1;
            $delta=($y2-$y)/abs($x2-$x);
            $y1=$y;
            for(;(($diff>0) && ($x<=$x2)) || (($diff<0) && ($x>=$x2));$x+=$diff){
                $y=floor($y1);
                $cursor->moveTo($output, max($x,0), max($y,0));
                $output->write($this->char);
                $y1+=$delta;
            }
        } else {
            $diff=$y2>$y?1:-1;
            $delta=($x2-$x)/abs($y2-$y);
            $x1=$x;
            $cursor->moveTo($output, 10, 10);
            for(;(($diff>0) && ($y<=$y2)) || (($diff<0) && ($y>=$y2));$y+=$diff){
                $x=floor($x1);
                $cursor->moveTo($output, max($x,0), max($y,0));
                $output->write($this->char);
                $x1+=$delta;
            }
        }
    }
    public function draw(Helper\CursorHelper $cursor, OutputInterface $output, $file, $width, $usleep, $char='㋡') {
        $this->char=$char;
        $dom = new DOMDocument();
        $dom->load($file);
        $x=0;
        $y=0;
        $maxX=$dom->getElementsByTagName('svg')->item(0)->getAttribute('width')*1;
        $maxY=$dom->getElementsByTagName('svg')->item(0)->getAttribute('height')*1;
        $this->zoom = $width/$maxX;
        foreach($dom->getElementsByTagName('path') as $path) {
            $x=0;
            $y=0;
            $d = $path->getAttribute('d');
            //$d = str_replace(' ', '', $d);
            $d = str_replace(',', ' ', $d);
            /*$style=$path->getAttribute('style');
            $maStyle=[];
            preg_match('/fill\:([^;]+); stroke\:([^;]+);/', $style, $maStyle);
            list($style, $fill, $stroke)=$maStyle;
            if($fill==='#ffffff') {
                //$cursor->colorize($output, 'fg(0) bg(0)');
                //continue;
            } else {
                $cursor->colorize($output, 'fg(default) bg(default)');
            }*/
            $matches=[];
            if(preg_match_all('/([^\d\. -])([\d\. -]*)/', $d, $matches, PREG_SET_ORDER)) {
                foreach($matches as $ma){
                    $cmd=$ma[1];
                    $par=trim($ma[2]);
                    $pars=explode(" ",$par);
                    switch($cmd){
                        case 'M':
                            $x1=$pars[0]*1;
                            $y1=$pars[1]*1;
                            $x=$x1;
                            $y=$y1;
                            $x0=$x;
                            $y0=$y;
                            break;
                        case 'm':
                            $x1 = array_shift($pars)*1;
                            $y1 = array_shift($pars)*1;
                            $x+=$x1;
                            $y+=$y1;
                            $x0=$x;
                            $y0=$y;
                            while(count($pars)>=2){
                                $x1 = array_shift($pars)*1;
                                $y1 = array_shift($pars)*1;
                                $this->line($cursor, $output, $x, $y, $x+$x1,$y+$y1);
                                $x+=$x1;
                                $y+=$y1;
                            }
                            break;
                        case 'C':
                            $x1=$pars[0]*1;
                            $y1=$pars[1]*1;
                            $x2=$pars[2]*1;
                            $y2=$pars[3]*1;
                            $x3=$pars[4]*1;
                            $y3=$pars[5]*1;
                            $this->cubicbezier($cursor, $output, $x, $y, $x1, $y1, $x2, $y2, $x3, $y3);
                            $x=$x1;
                            $y=$y1;
                            break;
                        case 'c':
                            $xA=$x;$yA=$y;
                            while(count($pars)>=2){
                                $x1 = array_shift($pars)*1;
                                $y1 = array_shift($pars)*1;
                                $x2 = array_shift($pars)*1;
                                $y2 = array_shift($pars)*1;
                                $x3=$x2;
                                $y3=$y2;
                               // $this->cubicbezier($cursor, $output, $x, $y, $x+$x1,$y+$y1, $x+$x2, $y+$y2, $x+$x3, $y+$y3);
                                $this->line($cursor, $output, $x, $y, $x+$x1,$y+$y1);
                                $x+=$x1;
                                $y+=$y1;
                            }
//                            echo $x-$xA;
                            break;
                        case 'L':
                            $x1=$pars[0]*1;
                            $y1=$pars[1]*1;
                            $this->line($cursor, $output, $x, $y, $x1,$y1);
                            $x=$x1;
                            $y=$y1;
                            break;
                        case 'l':
                            $x1=$pars[0]*1;
                            $y1=$pars[1]*1;
                            $this->line($cursor, $output, $x, $y, $x+$x1,$y+$y1);
                            $x+=$x1;
                            $y+=$y1;
                            break;
                        case 'z':
                            if($x0!==false) {
                                //$this->line($cursor, $output, $x, $y, $x0,$y0);
                                $x=$x0;
                                $y=$y0;
                            }
                            break;
                    }
                    usleep($usleep);
                }
            }
        }
        $cursor->moveTo($output, 0, floor($maxY*$this->zoom)+2);
    }
}
