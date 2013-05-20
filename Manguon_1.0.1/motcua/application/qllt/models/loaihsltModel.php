<?php

class LoaihsltModel extends Zend_Db_Table
{
    protected $_name = 'qllt_loaihoso';
    public $_id_p = 0;
	public $_search = "";
	/**
     * Count all items in QTHT_DEPARTMENTS table
     * @return integer
     */
	public function Count()
	{
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and qllt_loaihoso.TENLOAI like ?";
			//$strwhere .= " and qllt_loaihoso.MASO = ?";
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
	/**
     * Get all items in QTHT_MENUS table with pairs : ID_MNU and NAME
     * @return array
     */
	static function GetAllLoaihoso()
	{
		$r = Zend_Db_Table::getDefaultAdapter()->query("
			SELECT
     			ID_LOAIHOSO, KYHIEU, TENLOAI, TENPHONGBAN, MOTA, ACTIVE, THUOCKHO
			FROM
				qllt_loaihoso");
		return $r->fetchAll();
	}
	
	static function GetLoaihosoByCondition($tenloai, $kyhieu)
	{
		$sql = "SELECT
     			`ID_LOAIHOSO`, `KYHIEU`, `TENLOAI`, `TENPHONGBAN`, `MOTA`, `ACTIVE`, `THUOCKHO`
			FROM
				`qllt_loaihoso` WHERE `KYHIEU`='$kyhieu' OR `TENLOAI`='$tenloai'";
		$r = Zend_Db_Table::getDefaultAdapter()->query($sql);
		return $r->fetchAll();
	}

	static function getAll()
	{
		$r = Zend_Db_Table::getDefaultAdapter()->query("
			SELECT
     			*
			FROM
				qllt_loaihoso
			WHERE ID_LOAIHOSO != 1
				");
		return $r->fetchAll();
	}	

/**
     * Select all from $offset to $limit with $order arrange
     *
     * @param  integer $offset
     * @param  integer $limit
     * @param  String $order
     * @return boolean
     */
	public function SelectAll($offset,$limit,$order){
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and TENLOAI like ?";
		}
		//$strwhere .= " and qllt_loaihoso.ID_LOAIHOSO <> 1"; 
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
		$sql="SELECT 
				q1.ID_LOAIHOSO,q1.KYHIEU,q1.TENLOAI,q2.NAME as TENPHONGBAN,
				q1.MOTA,q1.ACTIVE,nlt.`TENTHUMUC`
			FROM `qllt_loaihoso` as q1 
			LEFT JOIN `qtht_departments` as q2 on q1.`TENPHONGBAN`=q2.`ID_DEP` 
			LEFT JOIN `qllt_noiluutru` AS nlt on q1.`THUOCKHO`= nlt.`ID_NOILUUTRU` 
			WHERE $strwhere $strorder $strlimit";
		//echo $sql;
		$result = $this->getDefaultAdapter()->query($sql,$arrwhere);
		return $result->fetchAll();
	}
	

	public function NewLoaihslt($id, $array)
	{
		if($id == 0)
		{
			//insert
			$db = Zend_Db_Table::getDefaultAdapter();
			$db->insert("qllt_loaihoso",$array);
		}
		else 
		{			
			//update
			$db = Zend_Db_Table::getDefaultAdapter();
			$db->update("qllt_loaihoso",$array,"ID_LOAIHOSO=".(int)$id);			
		}		
	}

	public function GetLoaihsltById($id)
	{
		$sql ="SELECT * FROM qllt_loaihoso WHERE ID_LOAIHOSO=".$id;
		$result = $this->getDefaultAdapter()->query($sql);
		return $result->fetch();
	}

	public function GetTenThuMuc()
	{
		$sql ="SELECT ID_NOILUUTRU,TENTHUMUC FROM qllt_noiluutru WHERE LOAI = 1";
		$result = $this->getDefaultAdapter()->query($sql);
		return $result->fetchAll();
	}

	//get Kho by idloaihoso
	public function GetKhoByIDLoaihoso()
	{
		$sql ="select
				nlt.`ID_NOILUUTRU`,nlt.`TENTHUMUC`
				from `qllt_loaihoso` as lhs
				left join `qllt_noiluutru` as nlt on nlt.`ID_NOILUUTRU`=lhs.`THUOCKHO` 
			WHERE ID_LOAIHOSO".$id;
		$result = $this->getDefaultAdapter()->query($sql);
		return $result->fetch();
	}

	//get phongban by idloaihoso
	public function GetDepartByIdLoaihoso($id)
	{
		$sql ="select
				  lhs.`ACTIVE`,lhs.`ID_LOAIHOSO`,lhs.`KYHIEU`,
				  lhs.`MOTA`,lhs.`TENLOAI`,lhs.`THUOCKHO`,d.`NAME`,d.`ID_DEP`
				from `qllt_loaihoso` as lhs
				left join `qtht_departments` as d on d.`ID_DEP`=lhs.`TENPHONGBAN`
				WHERE lhs.`ID_LOAIHOSO`=".$id;
		$result = $this->getDefaultAdapter()->query($sql);
		return $result->fetch();
	}

}