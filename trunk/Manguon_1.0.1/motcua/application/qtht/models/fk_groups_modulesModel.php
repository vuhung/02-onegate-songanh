<?php
/**
 * ClassModel
 *  
 * @author hieuvt
 * @version 1.0
 */
require_once 'Zend/Db/Table/Abstract.php';
require_once ('Zend/Db/Table.php');

class fk_groups_modulesModel extends Zend_Db_Table_Abstract {
    /**
     * The default table name 
     */
    var $_name = 'fk_groups_modules';    
}
