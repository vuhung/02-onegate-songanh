<?php

/**
 * TransitionModel
 *  
 * @author nguyennd
 * @version 1.0
 */

require_once 'Zend/Db/Table/Abstract.php';

class TransitionModel extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	var $_name = 'wf_transitions';
	
	public $_id_p=0;
	/**
	 * Select toàn bộ dữ liệu
	 */
	public function SelectAll(){
		return  $this->getDefaultAdapter()->fetchAll("
		select tr.* from 
		".$this->_name." tr
		where 
		tr.ID_P=? order by tr.ISFIRST DESC,tr.ORDERS,tr.ID_A_BEGIN,tr.ID_A_END,tr.NAME",array($this->_id_p));
	}
	static function ToComboEnd($data,$sel){
		$html="";
		foreach($data as $row){
			$html .= "<option value='".$row["ID_T"]."' ".($row["ID_T"]==$sel?"selected":"").">".$row["NAME"]."</option>";
		}
		return $html;
	}
}
