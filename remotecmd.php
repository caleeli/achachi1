
=======================================
  ACHACHI REMOTE COMMAND LINE SERVICE
=======================================
<?php
set_time_limit(-1);
$path = dirname(__FILE__).'/remotecmd';
$pathOut = dirname(__FILE__).'/remoteout';
$pathRun = dirname(__FILE__).'/remoterun';
while(true){
  echo Date("Y-m-d H:i:s"),":\n";
  foreach(glob($path.'/*') as $f){
    $f= realpath($f);
    rename($f, $pathRun.'/'.basename($f));
    $f = realpath($pathRun.'/'.basename($f));
    
    $output = $pathOut.'/'.basename($f).'.out';
    //file_put_contents($pathOut.'/'.basename($f), passthru("$f 2>&1"));
    $cmd = "start /B " . escapeshellarg( escapeshellarg( $f ) . " > " . escapeshellarg($output) . ' 2>&1');
    $cmd = 'start '.escapecmd(basename($f)).' /B ' . escapecmd( $f ) . " > " . escapeshellarg($output) . ' '. '2>&1';
    $cmd = 'start '.escapecmd(basename($f)).' ' . escapecmd( $f ) . ' > null 2>&1';
    $cmd = 'start '.escapecmd(basename($f)).' ' . escapecmd( $f ); //con pclose popen
    echo "$cmd\n";
    pclose(popen($cmd, "r"));
    //passthru($cmd);
    //exec($cmd);
  }
  sleep(1);
}

function escapecmd($param){
  return '"'.str_replace('"','^"',$param).'"';
}