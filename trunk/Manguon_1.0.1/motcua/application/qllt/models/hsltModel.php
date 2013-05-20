<?php

class HsltModel extends Zend_Db_Table
{
    protected $_name = 'qllt_hoso';
    public $_id_p = 0;
	public $parameter;
	/**
     * Count all items in QTHT_DEPARTMENTS table
     * @return integer
     */
	public function Count()
	{
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->parameter["SAVEYEAR"] != ""){
			$arrwhere[] = $this->parameter["SAVEYEAR"];
			$strwhere .= " and hs.NAMLUUTRU = ?";
		}
		
		
		if($this->parameter["idthumuccha"] != ""){
			$arrnoiluutru = array();
			QLVBDHCommon::getAllNoiLuuTruChild(&$arrnoiluutru,$this->parameter["idthumuccha"]);
			$arrnoiluutru[] = $this->parameter["idthumuccha"];
			//$arrwhere[] = $this->parameter["idthumuccha"];
			$strwhere .= " and hs.ID_NOILUUTRU IN (".implode(",",$arrnoiluutru).")";
		}
		if($this->parameter["TENHOSO"] != ""){
			Common_DBUtils::repairTableBeforeFulltextSearch("qllt_hoso");
			$wheretemp = " match(hs.TENHOSO) against (? IN BOOLEAN MODE)";
			$order = " match(hs.TENHOSO) against ('".str_replace("'","''",$this->parameter['TENHOSO'])."') DESC";
			$arrwhere[] = $this->parameter['TENHOSO'];
			$strwhere .= " and $wheretemp";
		}
		if($this->parameter["LOAIHOSO"] != 0){
			$arrwhere[] = $this->parameter["LOAIHOSO"];
			$strwhere .= " and hs.ID_LOAIHOSO = ?";
		}
		if($this->parameter["MASO"] != ""){
			$arrwhere[] = $this->parameter["MASO"];
			$strwhere .= " and hs.MASO = ?";
		}
		
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT
				count(*) as C
			FROM
				$this->_name as hs
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
     			*
			FROM
				qllt_loaihoso");
		return $r->fetchAll();
	}
	
	static function getAll()
	{
		$r = Zend_Db_Table::getDefaultAdapter()->query("
			SELECT
     			*
			FROM
				qllt_hoso
				");
		return $r->fetchAll();
	}	

	static function GetHosoByCondition($tenhs, $maso, $namluutru)
	{
		$sql = "SELECT
     			*
			FROM
				`qllt_hoso` WHERE `TENHOSO`='$tenhs' AND `MASO`='$maso' AND `NAMLUUTRU`='$namluutru'";
		$r = Zend_Db_Table::getDefaultAdapter()->query($sql);
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
		if($this->parameter["SAVEYEAR"] != ""){
			$arrwhere[] = $this->parameter["SAVEYEAR"];
			$strwhere .= " and hs.NAMLUUTRU = ?";
		}
		
		
		if($this->parameter["idthumuccha"] != ""){
			$arrnoiluutru = array();
			QLVBDHCommon::getAllNoiLuuTruChild(&$arrnoiluutru,$this->parameter["idthumuccha"]);
			$arrnoiluutru[] = $this->parameter["idthumuccha"];
			//$arrwhere[] = $this->parameter["idthumuccha"];
			$strwhere .= " and hs.ID_NOILUUTRU IN (".implode(",",$arrnoiluutru).")";
		}
		if($this->parameter["TENHOSO"] != ""){
			Common_DBUtils::repairTableBeforeFulltextSearch("qllt_hoso");
			$wheretemp = " match(hs.TENHOSO) against (? IN BOOLEAN MODE)";
			$order = " match(hs.TENHOSO) against ('".str_replace("'","''",$this->parameter['TENHOSO'])."') DESC";
			$arrwhere[] = $this->parameter['TENHOSO'];
			$strwhere .= " and $wheretemp";
		}
		if($this->parameter["LOAIHOSO"] != 0){
			$arrwhere[] = $this->parameter["LOAIHOSO"];
			$strwhere .= " and hs.ID_LOAIHOSO = ?";
		}
		if($this->parameter["MASO"] != ""){
			$arrwhere[] = $this->parameter["MASO"];
			$strwhere .= " and hs.MASO = ?";
		}
		//var_dump($this->parameter);
		//$strwhere .= " and qllt_loaihoso.ID_LOAIHOSO <> 1"; 
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
		$sql="SELECT
					HS.`ID_HOSO`,HS.`TENHOSO`,HS.`MASO`, NLT.`TENTHUMUC`,
					LHS.`TENLOAI` AS TENLOAIHOSO,
					HS.`NGAYBATDAU`,HS.`NAMLUUTRU`,
					HS.`NGAYKETTHUC`,HS.`THOIHANLUUTRU`
				FROM `qllt_hoso` as HS 
					LEFT JOIN `qllt_loaihoso` AS LHS ON LHS.`ID_LOAIHOSO` = HS.`ID_LOAIHOSO`
					LEFT JOIN `qllt_noiluutru` AS NLT ON NLT.`ID_NOILUUTRU` = HS.`ID_NOILUUTRU`
				WHERE $strwhere $strorder $strlimit";
//		echo $sql;
		$result = $this->getDefaultAdapter()->query($sql,$arrwhere);
		return $result->fetchAll();
	}
	

	public function Newhslt($id, $array)
	{
		if($id == 0)
		{
			//insert
			$db = Zend_Db_Table::getDefaultAdapter();
			$db->insert("qllt_hoso",$array);
		}
		else 
		{			
			//update
			$db = Zend_Db_Table::getDefaultAdapter();
			$db->update("qllt_hoso",$array,"ID_HOSO=".(int)$id);			
		}		
	}

	public function Deletehslt($array)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->delete('qllt_hoso', $array);
	}

	public function getHsltById($id)
	{
		$sql = "SELECT
				HS.`ID_HOSO`,HS.`TENHOSO`,HS.`MASO`, NLT.`TENTHUMUC`,
					LHS.`TENLOAI` AS TENLOAIHOSO,
					HS.`NGAYBATDAU`,HS.`NAMLUUTRU`,
					HS.`NGAYKETTHUC`,HS.`THOIHANLUUTRU`,
					NLT.`ID_NOILUUTRU`,
					NLT.LOAI,
					NLT.THUOCKHO,
					NLTKHO.TENTHUMUC AS TENKHO
				FROM `qllt_hoso` as HS 
					LEFT JOIN `qllt_loaihoso` AS LHS ON LHS.`ID_LOAIHOSO` = HS.`ID_LOAIHOSO`
					LEFT JOIN `qllt_noiluutru` AS NLT ON NLT.`ID_NOILUUTRU` = HS.`ID_NOILUUTRU`
					LEFT JOIN `qllt_noiluutru` AS NLTKHO ON NLT.`THUOCKHO` = NLTKHO.`ID_NOILUUTRU`
				WHERE HS.ID_HOSO=".$id;
		$result = $this->getDefaultAdapter()->query($sql);
		return $result->fetch();
	}

	//lưu giấy tờ
	static function saveGiayTo($array)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->insert("qllt_giayto", $array);
		return $array;
	}

	//lấy loại hồ sơ theo id hồ sơ
	public static function getLoaihsByIdhoso($id)
	{
		$sql = "select
					hs.`ID_LOAIHOSO` as ID_LOAIHS,lhs.`TENLOAI` as TEN_LOAI
				from `qllt_hoso` as hs
				left join `qllt_loaihoso` as lhs on lhs.`ID_LOAIHOSO`=hs.`ID_LOAIHOSO` 
				where hs.`ID_HOSO`=".$id;
		$db = Zend_Db_Table::getDefaultAdapter();
		$result = $db->query($sql);
		return $result->fetch();
	}
	public static function getIdHopsoByName($name,$idkho)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = "SELECT THUOCKHO FROM `qllt_noiluutru` WHERE ID_NOILUUTRU = ?";
		$result = $db->query($sql,$idkho)->fetch();
		$thuockho = $result['THUOCKHO'];
		if($thuockho == 0)
		{
			$thuockho = $idkho;
		}
		$sql = "select
					`ID_NOILUUTRU`
				from `qllt_noiluutru`
				where `THUOCKHO`= ? AND TENTHUMUC = ?";
//				echo $sql;exit;
		$result = $db->query($sql, array($thuockho, $name))->fetch();
		return $result["ID_NOILUUTRU"];
	}
}