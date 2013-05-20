<?php

/**
 * nguoidungModel
 *  
 * @author nguyennd
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class nguoidungModel extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'qtht_users';
	var $_search = "";
	var $_sel_dep = 0;
	/**
	 * Select toàn bộ dữ liệu
	 */
	public function SelectAll($offset,$limit,$order){
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and LASTNAME like ?";
		}
		
		if($this->_sel_dep > 0 ){
			$arrwhere[] = $this->_sel_dep;
			$strwhere .= " and dep.ID_DEP = ?";
		}
		//Build phần limit
		$strlimit = "";
		if($limit>0){
			$strlimit = " LIMIT $offset,$limit";
		}
		
		//Build order
		$strorder = "";
		if($order!=""){
			$strorder = " ORDER BY $order";
		}
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT
				u.*,emp.FIRSTNAME,emp.LASTNAME,dep.NAME as DEPNAME
			FROM
				$this->_name u
				inner join QTHT_EMPLOYEES emp on emp.ID_EMP = u.ID_EMP
				inner join QTHT_DEPARTMENTS dep on dep.ID_DEP = emp.ID_DEP
			WHERE
				$strwhere
			$strorder
			$strlimit
		",$arrwhere);
		return $result->fetchAll();
	}
	public function FindById($id_u){
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT
				u.*,emp.FIRSTNAME,emp.LASTNAME,dep.NAME as DEPNAME,dep.ID_DEP,emp.ID_EMP
			FROM
				$this->_name u
				inner join QTHT_EMPLOYEES emp on emp.ID_EMP = u.ID_EMP
				inner join QTHT_DEPARTMENTS dep on dep.ID_DEP = emp.ID_DEP
			WHERE
				u.ID_U = ?
		",$id_u);
		return $result->fetch();
	}
	/**
	 * Chuyển dữ liệu tới combobox
	 */
	public function Count(){
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and LASTNAME like ?";
		}
		if($this->_sel_dep > 0 ){
			$arrwhere[] = $this->_sel_dep;
			$strwhere .= " and ID_DEP = ?";
		}
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT
				count(*) as C
			FROM
				$this->_name u
				inner join QTHT_EMPLOYEES emp on emp.ID_EMP = u.ID_EMP
			WHERE
				$strwhere
		",$arrwhere)->fetch();
		return $result["C"];
	}
	/**
	 * Lấy phòng ban
	 */
	function GetDeparment(){
		$tree = array();
		QLVBDHCommon::GetTree(&$tree,"QTHT_DEPARTMENTS","ID_DEP","ID_DEP_PARENT",1,1);
		return $tree;
	}
	static function ToDepCombo($data,$sel){
		$html="";
		foreach($data as $row){
			$html .= "<option value='".$row["ID_DEP"]."' ".($row["ID_DEP"]==$sel?"selected":"").">".(str_repeat("--",$row['LEVEL']).$row["NAME"])."</option>";
			
		}
		return $html;
	}
}
