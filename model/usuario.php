<?php
require_once("connection.php");
class usuario extends Zend_Db_Table_Abstract
{
    protected $_name = 'usuario';
    protected $_dependentTables = NULL;
    protected $_referenceMap    = array(
    
    );
}
