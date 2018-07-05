<?php

class node_rule1 extends node {
	
	public $template  = '  /**
   * @{@$descripcion}
   * @author @{@$author}
   */
  public function @{isset($name)?$name:"exit"}Action()
  {
    foreach($this->getRequest()->getParams() as $_ => $__) if(substr($_,0,1)!="_") $$_=$__;
    $this->_helper->viewRenderer->setNoRender();
    $destination=@{@var_export("$destination",true)};
    $response=new StdClass();$response->success=true;@{@node::join("\\n",$_)}
    $json_response=json_encode($response);
    $json_response=substr($json_response,0,-1).
      ",afterAction:function(){window.location.href=".json_encode("../".$destination).";}" . "}";
    if(isset($__ajax))
    {
      print($json_response);
    }
    else 
    {
      if($response->success)
      {
        list($act,$ctl)=explode("/",$destination);
        $this->_helper->redirector($act,$ctl);
      }
      else
      {
        $s=$this->getRequest()->getServer("HTTP_REFERER");
        $this->getResponse()->setRedirect($s);
        return false;
      }
    }
  }
' ;
	
	function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		$_r  = node:: run ();
		$_nodes  = $this ->groupNodes ($_r );
		$values ["_" ] = $_r ;
		$values ["_e" ] = $e ;
		$values ["_nodes" ] = $_nodes ;
		$template  = isset  ($this ->template)?$this ->doTemplate ($this ->template,$values ):"" ;
		return $this ->encode ($template );
		return $this ->encodeEmpty ();
	}
}
