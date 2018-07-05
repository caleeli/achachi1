<?php
/*transformer*/
class node_transformer extends node_xmlcomponent {
  public $transparent='';
  public $precode='tools::hook($this,$e);';
}