<?php
class node_bcomponent1 extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_tag  = $values ["name" ];
		$_class  = node:: getClassName ($_tag );
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$_code  = 'class '  . $_class  . ' extends node { ' ;
		$_code .= "\n"  .  (isset  ($_nodes ["property" ])?node:: join ("\n" ,$_nodes ["property" ]):'' );
		$_code .= "\nfunction run() {" ;
		$_code .= "\n"  . '$e=&$this->node;$values=&$this->values;' ;
		$_code .= "\n"  .  (isset  ($_nodes ["before" ])?node:: join ("" ,$_nodes ["before" ]):'' );
		$_code .= "\n"  . '$_r = node::run();' ;
		$_code .= "\n"  . '$_nodes = $this->groupNodes($_r);' ;
		$_code .= "\n"  . '$values["_"] = $_r;' ;
		$_code .= "\n"  . '$values["_e"] = $e;' ;
		$_code .= "\n"  . '$values["_nodes"] = $_nodes;' ;
		$_code .= "\n"  . '$template=isset($this->template)?$this->doTemplate($this->template,$values):"";' ;
		$_code .= "\n"  .  (isset  ($_nodes ["after" ])?node:: join ("" ,$_nodes ["after" ]):'' );
		$_code .= "\n"  . 'return $this->encodeEmpty();' ;
		$_code .= "\n"  . '}' ;
		$_code .= "\n"  . '}' ;
		$_code  = format_code ($_code );
		node:: createTagClass ($_tag ,$_class ,$_code );
		return $this ->encodeEmpty ();
	}
	
	public static 
	function createBComponent ($_tag ,$before ,$template  = '' ,$after  = '' ) {
		$_class  = node:: getClassName ($_tag );
		$_code  = 'class '  . $_class  . ' extends node { ' ;
		$_code .= "\n"  . 'public $transparent=true;' ;
		$_code .= "\n"  . 'public $template='  . var_export ($template ,true) . ';' ;
		$_code .= "\n"  . 'function run() {
      //print("<table border=1><tr><td><pre>");
      $r=$this->run0();
      //@var_dump("===".get_class($this)."====>".previousCall(),array_keys($r));
      //print("</pre></td></tr></table>");
      return $r;
      }
    ' ;
		$_code .= "\nfunction run0() {" ;
		$_code .= "\n"  . '$e=&$this->node;$values=&$this->values;' ;
		$_code .= "\n"  . @$before ;
		$_code .= "\n"  . '$_r = node::run();' ;
		$_code .= "\n"  . '$_nodes = $this->groupNodes($_r);' ;
		$_code .= "\n"  . '$values["_"] = $_r;' ;
		$_code .= "\n"  . '$values["_e"] = $e;' ;
		$_code .= "\n"  . '$values["_nodes"] = $_nodes;' ;
		$_code .= "\n"  . '$template=isset($this->template)?$this->doTemplate($this->template,$values):"";' ;
		$_code .= "\n"  . @$after ;
		$_code .= "\n"  . 'return $this->encodeEmpty();' ;
		$_code .= "\n"  . '}' ;
		$_code .= "\n"  . '}' ;
		$_code  =format_code ($_code );
		node:: createTagClass ($_tag ,$_class ,$_code );
	}
}
