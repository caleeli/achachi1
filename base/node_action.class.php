<?php
class node_action extends node {
	
	public $template  = '  /**
   * @{@$descripcion}
   */
  public function @{$name}Action()
  {
    @{(isset($loadParams) && ($loadParams=="true"))?\'foreach($\'.\'this->getRequest()->getParams() as $_ => $__) if(substr($_,0,1)!="_") $$_=$__;\':\'\'}
    @{(isset($noRender) && ($noRender=="true"))?\'$\'.\'this->_helper->viewRenderer->setNoRender();\':\'\'}@{@node::join("\\n",$_)}
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
		//@{$noRender}
		print  ("<a href='"  . ACH_URI_BASE . "/"  . $e ->parentNode->parentNode->getAttribute ("name" ) . "/html/index.php/"  . $e ->parentNode->getAttribute ("name" ) . "/"  . $e ->getAttribute ("name" ) . "'>"  . ACH_URI_BASE . "/"  . $e ->parentNode->parentNode->getAttribute ("name" ) . "/html/index.php/"  . $e ->parentNode->getAttribute ("name" ) . "/"  . $e ->getAttribute ("name" ) . "</a>\n" );
		return $this ->encode ($template );
		return $this ->encodeEmpty ();
	}
}
