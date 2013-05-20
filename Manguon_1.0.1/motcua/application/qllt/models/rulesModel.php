<?php
class Qllt_rulesModel extends Zend_Db_Table
{
    protected $_name = 'gen_temp';
	public function getId($type,$year)
	{        
        $table = "gen_filedinhkem_".$year;
		$result = $this->getDefaultAdapter()->query("
			select ID_OBJECT ID from ".$table." where TYPE = $type
		")->fetch();
		return $result['ID'];
	}	 
}