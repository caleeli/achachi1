<?php
class node_call extends node {
	
	public $template  = '  /**
   * @{@$descripcion}
   * @author @{@$author}
   */
  public function @{$name}Action()
  {
    foreach($this->getRequest()->getParams() as $_ => $__) if(substr($_,0,1)!="_") $$_=$__;
    $this->_helper->viewRenderer->setNoRender();
    $response=new json_response();
    $response->success=true;
    @{@node::join("\\n",$_)}
    print($response->toJSONString());
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
