<?php
global $ACHACHI;
if(!isset($ACHACHI)){
	$ACHACHI=array();
}
$TPLS=array();
function template($tpl){
	global $TPLS;
	$i=strpos($tpl,"\r\n");
	if($i!==false){
		$id=substr($tpl,1,$i-1);
		$i+=2;
	} else {
		$i=strpos($tpl,"\n");
		$id=substr($tpl,1,$i-1);
		$i+=1;
	}
	@list($id,$name)=explode(":", $id,2);
	//global $ACHACHI;
	//echo AchachiExpression::evaluateS($name,$ACHACHI);
	$tag=$id;
	$id=preg_replace('/[^\w]+/','',$id);
	$TPLS[$id]=array("tag"=>$tag,"tpl"=>substr($tpl,$i,-1),"name"=>$name);
	return $tag;
}
$pending=array();
$runTemplateBlocked=false;
function runTemplate($tpl, $code0, &$base, $dataBase=array()){
	$code=$code0;
	global $TPLS;
	global $pending;
	global $runTemplateBlocked;
//	echo 'TEMPLATE: ',$tpl,' - ';VAR_DUMP($dataBase);
	if($runTemplateBlocked) {
		$pending[]=array("tpl"=>$tpl,"code"=>$code);
//		echo " READ\n";
		return "";
	}
//	echo " EXEC\n";
	$pending=array();
	$runTemplateBlocked=true;
	$code = AchachiExpression::evaluateS($code, $dataBase);
	//var_dump($tpl,$code);
	$pending1=$pending;
	$runTemplateBlocked=false;
	$data=$dataBase;
	foreach(explode("\n",$code) as $l) if(strpos($l,":")!==false){
		list($var,$value)=explode(":",$l,2);
		$data[trim($var)]=trim($value);
		//$ACHACHI[trim($var)]=trim($value);
	}
	$data['__templateCode']=$code0;
	/*if(empty($TPLS[$tpl]["tpl"])){
		$base0="";
		//var_dump($pending1);
		foreach($pending1 as $p){
			runTemplate($p['tpl'], $p['code'], $base0, $data);
		}
		//var_dump($base0);
		if($base){
			$base = str_replace($TPLS[$tpl]["tag"], $base0 . $TPLS[$tpl]["tag"],$base);
		} else {
			$base = $base0;
		}
		//var_dump($base0);
		return "";
	} else {*/
		global $ACHACHI;
		$fileName = AchachiExpression::evaluateS($TPLS[$tpl]["name"], $data);
		@$templateId=$ACHACHI["templateId"];
		$base0 = AchachiExpression::evaluateS($TPLS[$tpl]["tpl"], $data);
		if($fileName && file_exists($fileName)) {
			$base0 = file_get_contents($fileName);
		}
		foreach($pending1 as $p){
			runTemplate($p['tpl'], $p['code'], $base0, $data);
		}
		if($base){
			$tagId = $TPLS[$tpl]["tag"] . ":" . md5(serialize($templateId))."\n";
			$u=strpos($base, $tagId);
			if($u===false) {
				$base = str_replace($TPLS[$tpl]["tag"].">", $tagId . $base0 . $TPLS[$tpl]["tag"].">",$base);
			} else {
				$v=strpos($base, $TPLS[$tpl]["tag"], $u+1);
				//var_dump(":o",$u,$v,substr($base, $u, $v-$u), $base0);
				$base = substr($base,0,$u) .  $tagId . $base0 . substr($base, $v);
				//$base = str_replace($tagId, $tagId . $base0 . $TPLS[$tpl]["tag"].">",$base);
			}
		} else {
			$base = $base0;
		}
		if($fileName) {
			file_put_contents($fileName, $base);
		}
		return $base;
	/*}*/
}
function library($lib){
	$pathBase=".";
	var_dump("$pathBase/.achachi/$lib");
	foreach(glob("$pathBase/.achachi/$lib") as $f){
		if(is_dir($f)){
			$code=createTplFromDir($f);
			AchachiExpression::evaluateS($code);
		}
	}
}
function createTplFromDir($f){
	$fName=basename($f);
	$res='$template{//=='.$fName."==\n";
	foreach(glob($f.'/*') as $f1){
		$res.=createFromFile($f1)."\n";
	}
	$res.='}';
	return $res;
}
function saveAs($code){
	global $ACHACHI;
    $res = AchachiExpression::evaluateS($code);
//	echo $res;
//	file_put_contents($ACHACHI['fileName'], $res);
	return "";
}
function createFromFile($f){
	$fName=basename($f);
	$tName=preg_replace('/[^\w]+/','',$fName).'CODE';
//	return '$template{//=='.$tName."==\n".file_get_contents($f).'}'. '!saveAs{%{$fileName=!phpEncode{'.$fName.'};}#'.$tName.'@{$__templateCode}}';
	return '$template{//=='.$tName."==:$fName\n".file_get_contents($f).'}'. '!saveAs{#'.$tName.'@{$__templateCode}}';
}
function phpEncode($code){
	return var_export($code,true);
}

class AchachiExpression{
  var $breaked=false;
  var $map=array(
      '@'=>'sReturn',
      //'$'=>'sEncode',
      '$'=>'sCode',
      '%'=>'sExecute',
      '!'=>'sEvaluate',
      '#'=>'sDoTemplate',
    );
  function evaluateS($exp,$vars=array()){
    $X=new AchachiExpression();
    return implode("",$X->evaluate($exp,$vars));
  }
  function getVariables($exp){
    $tokens=@token_get_all('<?php '.$exp);
	$vars=array();
	foreach($tokens as $i=>$t){
		if(is_array($t) && $t[0]==T_VARIABLE && !isset($vars[substr($t[1],1)])){
			if(substr($t[1],1,1)!="_" && strlen(substr($t[1],1))>1){
				if(@$tokens[$i+1][0]==T_COMMENT){
					$type=substr($tokens[$i+1][1],2,-2);
				} else {
					$type='textfield';
				}
				$vars[substr($t[1],1)]=substr($t[1],1).'['.$type.']';
			}
		}
	}
	return array_values($vars);
  }
  function tokenize($exp){
    $tokens=@token_get_all('<?php '.$exp);
	//var_dump($tokens);
    $newTokens=array($tokens[0]);
    for($i=1,$l=count($tokens);$i<$l;$i++){
	  $e=is_array($tokens[$i])?$tokens[$i][1]:$tokens[$i];
      if(is_array($tokens[$i]) && (
          $tokens[$i][0]==311
          //|| $tokens[$i][0]==T_STRING  //Se deshabilito para permitir @function{}
          || $tokens[$i][0]==314 //T_ENCAPSED_AND_WHITESPACED
          || $tokens[$i][0]==T_DOLLAR_OPEN_CURLY_BRACES
          || $tokens[$i][0]==T_CONSTANT_ENCAPSED_STRING
          || $tokens[$i][0]==367  // comentarios /* ... */
          || $tokens[$i][0]==366  // comentarios //
        )){
        $newTokens[]=substr($tokens[$i][1],0,1);
        $nt = $this->tokenize(substr($tokens[$i][1],1));
        foreach($nt as $j=>&$t)if($j>0){
          $e=is_array($t)?$t[1]:$t;
          $char=substr($e,0,1);
          if(strlen($e)>1 && isset($this->map[$char])){
            $newTokens[]=$char;
            $newTokens[]=substr($e,1);
          } else {
            $newTokens[]=$e;//$t;
          }
        }
      } else {
		//$tokens[$i]=$e;
        //$e=is_array($tokens[$i])?$tokens[$i][1]:$tokens[$i];
        $char=substr($e,0,1);
        if(strlen($e)>1 && isset($this->map[$char])){
          $newTokens[]=$char;
          $newTokens[]=substr($e,1);
        } else {
          $newTokens[]=$e;//$tokens[$i];
        }
      }
    }
    return $newTokens;
  }
  function evaluate($exp, $vars){
    $this->vars=$vars;
    $this->tokens=$tokens=$this->tokenize($exp);
    //print_r($this->tokens);
    $res = $this->evaluateCode(1);
	return $res;
  }
  function evaluateCode($i0,$l=null){
    $res = array();
    if(!isset($l))$l=count($this->tokens);
    for($i=$i0;$i<$l;$i++){
      //var_dump($i);
      $e=is_array($this->tokens[$i])?$this->tokens[$i][1]:$this->tokens[$i];
      $char=substr($e,0,1);
      if(isset($this->map[$char]) && $this->tokens[$i+1]==="{"){
        list($i,$res0)=$this->{$this->map[$char]}($i);
        if($this->breaked) break(1);
		$res[]=$res0;
      } elseif(isset($this->map[$char]) && $this->tokens[$i+1]!=="{" && $this->tokens[$i+2]==="{"){
		$fn=$this->tokens[$i+1];
        list($i,$res0)=$this->{$this->map[$char]}($i+1);
		if($char=="#"){
			$resTpl="";
			$res0=runTemplate($fn, $res0, $resTpl, $this->vars);
		} else {
			if(strcasecmp($fn,'print')==0) {
				$res0=print($res0);
			} else {
				$res0=$fn($res0);
			}
		}
        if($this->breaked) break(1);
		$res[]=$res0;
      } else {
		$res[]=$e;
	  }
    }
    return $res;
  }
  //%
  function sExecute($i){
    list($l,$code)=$this->getCode($i+1);
    $code = $this->evaluateCode($i+2,$l);
	//vaR_dump($code);
	$code = implode("",$code);
    return array($l, $this->evalCode($code));
  }
  //!
  function sEvaluate($i){
    list($l,$code)=$this->getCode($i+1);
    $code = $this->evaluateCode($i+2,$l);
    return array($l, implode('',$code));
  }
  //@
  function sReturn($i){
    list($l,$code)=$this->getCode($i+1);
    $code = $this->evaluateCode($i+2,$l);
    return array($l, $this->evalCode('return '.implode("",$code).';'));
  }
  //$
  function sEncode($i){
    list($l,$code)=$this->getCode($i+1);
    $code = $this->evaluateCode($i+2,$l);
    return array($l, json_encode($this->evalCode('return '.implode("",$code).';')));
  }
  //$
  function sCode($i){
    list($l,$code)=$this->getCode($i+1);
    //$code = $this->evaluateCode($i+2,$l);
    return array($l, $code);
  }
  //#
  function sDoTemplate($i){
    list($l,$code)=$this->getCode($i+1);
    //$code = $this->evaluateCode($i+2,$l);
    return array($l, $code);
  }
  function getCode($i0){
    $tokens=$this->tokens;
    $res=array();$llave=0;
    //debug_print_backtrace();echo"======================\n";
    for($i=$i0,$l=count($tokens);$i<$l;$i++){
      $e=is_array($tokens[$i])?$tokens[$i][1]:$tokens[$i];
    //var_dump($e);
      switch($e){
        case '{':$llave++;$res[]=$e;break;
        case '}':$llave--;$res[]=$e;if($llave==0) break 2; else break;
        default:
          $res[]=$e;
      }
    }
    return array($i,implode("",$res));
  }
  function evalCode($__code){
	global $_evaluateFile;
	global $ACHACHI;
    foreach($this->vars as $___ => &$____) $ACHACHI[$___] = $____;
    foreach($ACHACHI as $___ => &$____) $$___ = &$____;
    global $__this;
    if(!isset($__this)) $__this=array();
    $__this[]=$this;
    $_exit=create_function('','global $__this;$__this[count($__this)-1]->breaked=true;');
    $__params = $this->vars;
    global $_evaluateCode;
    $_evaluateCode=$__code;
    ob_start('error_handler');
    $__res = eval("\n".$__code);
    ob_end_flush();
    array_pop($__this);
	$__vars=get_defined_vars();
	foreach($__vars as $___ => &$____){
		switch($___){
		case '__vars':
		case '__code':
		case 'ACHACHI':
		case 'this':
		case '__this':
		case '_exit':
		case '__params':
		case '_evaluateCode':
		case '__res':
		break;
		default:
			$ACHACHI[$___]=&$____;
		}
	}
    return $__res;
  }
}

function error_handler($output)
{
    $error = error_get_last();
    global $_evaluateCode;
    global $_evaluateFile;
    if($error && $error["type"]!=2048){
      $output = "[$_evaluateFile] ".$error["message"]." en:\n".$_evaluateCode."\n";
    }
    return $output;
}

