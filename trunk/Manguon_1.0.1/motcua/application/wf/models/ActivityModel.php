<?php

/**
 * ActivityModel
 *  
 * @author nguyennd
 * @version 1.0
 */

require_once 'Zend/Db/Table/Abstract.php';

class ActivityModel extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	var $_name = 'wf_activities';
	public $_id_p = 0;
	/**
	 * Select toàn bộ dữ liệu
	 */
	public function SelectAll(){
		return  $this->getDefaultAdapter()->fetchAll("select *,(select count(*) from WF_ACTIVITYACCESSES where ID_A = WF_ACTIVITIES.ID_A) as ACCOUNT from ".$this->_name." where ID_P=?",array($this->_id_p));
	}
	/**
	 * Đếm số bản ghi có trong table
	 */
	public function Count(){
		$r = $this->getDefaultAdapter()->query("select count(*) as C from ".$this->_name." where ID_P=?",array($this->_id_p))->fetch();
		return $r["C"];
	}
	/**
	 * Chuyển dữ liệu tới combobox
	 */
	static function ToCombo($data,$sel){
		$html="";
		foreach($data as $row){
			$html .= "<option value='".$row["ID_A"]."' ".($row["ID_A"]==$sel?"selected":"").">".$row["NAME"]."</option>";
		}
		return $html;
	}
}
