<?php

/**
 * ActivityPoolModel
 *  
 * @author nguyennd
 * @version 1.0
 */

require_once 'Zend/Db/Table/Abstract.php';

class CameraModel extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	var $_name = 'motcua_cameras';
	var $_search = "";
	/**
	 * Select toàn bộ dữ liệu
	 */
	public function SelectAll($offset,$limit,$order){
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and NAME like ?";
		}
		
		//Build phần limit
		$strlimit = "";
		if($limit>0){
			$strlimit = " LIMIT $offset,$limit";
		}
		
		//Build order
		$strorder = "";
		if($order>0){
			$strorder = " ORDER BY $order";
		}
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT
				*
			FROM
				$this->_name
			WHERE
				$strwhere
			$strorder
			$strlimit
		",$arrwhere);
		return $result->fetchAll();
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
			$strwhere .= " and NAME like ?";
		}
		
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT
				count(*) as C
			FROM
				$this->_name
			WHERE
				$strwhere
		",$arrwhere)->fetch();
		return $result["C"];
	}

	function GetAllUser($idlv){
		$r = $this->getDefaultAdapter()->query("
			SELECT
     			MCLV.SEL,U.`ID_U`,concat(EMP.FIRSTNAME,' ',EMP.LASTNAME) AS NAME,u.ORDERS
			FROM
				`QTHT_USERS` U
				INNER JOIN QTHT_EMPLOYEES EMP ON U.ID_EMP = EMP.ID_EMP
				LEFT JOIN (
				     SELECT
				           *, 1 AS SEL
				     FROM
				         `MOTCUA_CAMERAS_PHANQUYEN`
				     WHERE
						 ID_CAMERA=?
				     ) MCLV ON MCLV.`ID_U` = U.`ID_U`
			ORDER BY ORDERS
		",array($idlv));
		return $r->fetchAll();
	}
}
