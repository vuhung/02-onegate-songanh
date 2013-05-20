<?php
/**
 * LoaisModel
 *  
 * @author truongvc
 * @version 1.0
 */
class nonworkingdateModel extends Zend_Db_Table
{
    protected $_name = 'GEN_NONWORKINGDATES';
    public $_id_p = 0;
	
	function getAll(){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();		
		$r = $dbAdapter->query("SELECT * FROM GEN_NONWORKINGDATES");
		return $r;
	}
}