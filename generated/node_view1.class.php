<?php

class node_view1 extends node {
	
	public $template  = '<html>
<head>
  #{
    if(!isset($stylesheet)||$stylesheet=="")$stylesheet="/ext/resources/css/ext-all.css";
    $_res="";
    foreach(explode(";",$stylesheet) as $_l)
      $_res.=\'<link rel="stylesheet" type="text/css" href="\'.$_l.\'">\';
    return $_res;
  }
  #{
    if(!isset($library) || $library=="")$library="/ext/adapter/ext/ext-base.js;/ext/ext-all.js";
    $__r="";
    foreach(explode(";",$library) as $__lib)
      $__r.=\'<script type="text/javascript" src="\'.$__lib.\'"></script>\';
    return $__r;
  }
  <style>
        html, body {
            font: normal 12px verdana;
            margin: 0;
            padding: 0;
            border: 0 none;
            overflow: hidden;
            height: 100%;
        }
  </style>
@{@node::join("\\n",$_nodes[\'css\'])}
  <script>
    Ext.QuickTips.init();
    function extLoad(sClass,base,_construct)
    {
      var obj;
      eval("obj=new "+sClass+"(base);");
      if(_construct)eval(_construct);
      return obj;
    }
    function main()
    {
      @{@node::join("\\n      ",$_nodes[\'extdocument\'])};
    }
    Ext.onReady(function(){ main(); });
  </script>
</head>
<body>#{
  $_nodes2=array();
  foreach($_ as $_n):
    if($_n[0]!="css" && $_n[0]!="extdocument")$_nodes2[]=$_n;
  endforeach;
  return node::content($_nodes2);
}</body>
</html>
' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_nodes ["#parent" ] = $e ->parentNode->getAttribute ("name" );
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$template  = isset  ($this ->template)?$this ->doTemplate ($this ->template,$values ):"" ;
		createFile ("views/scripts/"  . $e ->parentNode->getAttribute ("name" ) . "/"  . $e ->getAttribute ("name" ) . ".phtml" ,$template );
		return $this ->encodeEmpty ();
	}
}
