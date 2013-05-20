<?php
/**
 * LoaisModel
 *  
 * @author truongvc
 * @version 1.0
 */
class LoaiModel extends Zend_Db_Table
{
    protected $_name = 'motcua_loai_hoso';
    public $_id_p = 0;
	/**
     * Count all items in motcua_loai_hoso table
     * @return integer
     */
	public function Count()
	{
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and MOTCUA_LOAI_HOSO.TENLOAI like ?";
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
     * Select all menus from $offset to $limit with $order arrange
     *
     * @param  integer $offset
     * @param  integer $limit
     * @param  String $order
     * @return boolean
     */
	public function SelectAll($offset,$limit,$order)
	{
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and (MOTCUA_LOAI_HOSO.TENLOAI like ?) ";
		}		 
		if($this->_linhvuc > 0){
			$arrwhere[] = $this->_linhvuc;
			$strwhere .= " and (MOTCUA_LOAI_HOSO.ID_LV_MC = ?) ";
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
			SELECT motcua_loai_hoso.*,HSCV_LOAIHOSOCONGVIEC.*, (SELECT count(*) FROM MOTCUA_THUTUC_CANCO ttcc WHERE ttcc.ID_LOAIHOSO=motcua_loai_hoso.ID_LOAIHOSO) as CNT
			FROM motcua_loai_hoso	
			LEFT JOIN HSCV_LOAIHOSOCONGVIEC ON HSCV_LOAIHOSOCONGVIEC.ID_LOAIHSCV=motcua_loai_hoso.ID_LOAIHSCV			
			WHERE
				$strwhere
			$strorder
			$strlimit
		",$arrwhere);
		return $result->fetchAll();
	}	
	public function SelectAllByDTD($offset,$limit,$order)
	{
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and (MOTCUA_LOAI_HOSO.TENLOAI like ?) ";
		}		 
		if($this->_linhvuc > 0){
			$arrwhere[] = $this->_linhvuc;
			$strwhere .= " and (MOTCUA_LOAI_HOSO.ID_LV_MC = ?) ";
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
			SELECT distinct motcua_loai_hoso.*,HSCV_LOAIHOSOCONGVIEC.*, (SELECT count(*) FROM MOTCUA_THUTUC_CANCO ttcc WHERE ttcc.ID_LOAIHOSO=motcua_loai_hoso.ID_LOAIHOSO) as CNT
			FROM motcua_loai_hoso	
			LEFT JOIN HSCV_LOAIHOSOCONGVIEC ON HSCV_LOAIHOSOCONGVIEC.ID_LOAIHSCV=motcua_loai_hoso.ID_LOAIHSCV
			INNER JOIN MOTCUA_CUSTOM_FIELD mcf on mcf.LOAIHOSO_CODE = motcua_loai_hoso.CODE
			WHERE
				$strwhere
			$strorder
			$strlimit
		",$arrwhere);
		return $result->fetchAll();
	}	
	/**
     * Get all items in MOTCUA_LOAI_HOSO table with pairs : ID_LOAIHOSO and TENHOSO
     * @return array
     */
	function GetAllLoais($idlvmc=null)
	{
	
		if($idlvmc==""){
		$r = Zend_Db_Table::getDefaultAdapter()->query("
				SELECT
					ID_LOAIHOSO,TENLOAI
				FROM
					MOTCUA_LOAI_HOSO
				order by TENLOAI
				");
			return $r->fetchAll();
		}else{
			$r = $this->getDefaultAdapter()->query("
				SELECT
					ID_LOAIHOSO,TENLOAI
				FROM
					MOTCUA_LOAI_HOSO
				WHERE ID_LV_MC = ?
				order by TENLOAI
				",$idlvmc);
			return $r->fetchAll();
		}
		
	}
	/**
	 * Get 
	 *
	 */
	public function GetAllLoaiHscv()
	{
		$r = $this->getDefaultAdapter()->query("
			SELECT
     			ID_LOAIHSCV,NAME,MASOQUYTRINH
			FROM
				HSCV_LOAIHOSOCONGVIEC
			WHERE ID_LOAIHSCV > 3");
		return $r->fetchAll();
	}
	/**
    * Chuyển dữ liệu tới combobox
    */
    public function ToCombo($data,$sel){
        $html="";
        $html .= "<option value='0'>--Chọn loại thủ tục--</option>";
        foreach($data as $row){
            $html .= "<option value='".$row["ID_LOAIHOSO"]."' ".($row["ID_LOAIHOSO"]==$sel?"selected":"").">".$row["TENLOAI"]."</option>";
        }
        return $html;
    }

	public function toComboName(){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "select `ID_LOAIHOSO`,`TENLOAI` , `CODE` from `motcua_loai_hoso` 
		";
		$query = $dbAdapter->query($sql);
		$re = $query->fetchAll();
		foreach ($re as $row){
			echo "<option id='".$row['CODE']."' value=".$row['ID_LOAIHOSO'].">".$row['TENLOAI']."</option>";
		}
	}
	/**
	 * Get ID_LOAIHSCV FROM MOTCUA_LOAI_HOSO BY ID_LOAI_HOSO
	 *
	 * @param unknown_type $id_loai
	 */
	public function getIdLoaiHscvByIdLoai($id_loai)
	{
		$r = $this->getDefaultAdapter()->query("
			SELECT
     			ID_LOAIHSCV AS ID
			FROM
				MOTCUA_LOAI_HOSO
			WHERE ID_LOAIHOSO =?",array($id_loai));
		$result = $r->fetchAll();
		if(count($result)>0)
		{
			return $result[0]["ID"];
		}
		else return -1;
	}
	//Get LePhi From MOTCUA_LOAI_HOSO BY ID_LOAI_HOSO
	public function getLePhiByIdLoai($id_loai)
	{
		$r = $this->getDefaultAdapter()->query("
			SELECT
     			LEPHI
			FROM
				MOTCUA_LOAI_HOSO
			WHERE ID_LOAIHOSO =?",array($id_loai));
		$result = $r->fetchAll();
		if(count($result)>0)
		{
			return $result[0]["LEPHI"];
		}
		else return 0;
	}
//Get LePhi From MOTCUA_LOAI_HOSO BY ID_LOAI_HOSO
	public static function getNameById($id_loai)
	{
		global $db;
		
		$r = $db->query("
			SELECT
     			TENLOAI
			FROM
				MOTCUA_LOAI_HOSO
			WHERE ID_LOAIHOSO =?",array($id_loai));
		$result = $r->fetchAll();
		if(count($result)>0)
		{
			return $result[0]["TENLOAI"];
		}
		else return "";
	}
	
	function getAll(){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();		
		$r = $dbAdapter->query("SELECT * FROM MOTCUA_LOAI_HOSO");
		return $r;
	}
	
	public function getLoaiHSByBieuMau($id)
	{
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = 'SELECT lhs.ID_LOAIHOSO, bm.TEN_BIEUMAU
					FROM motcua_bieumau bm
					LEFT JOIN motcua_thutuc_canco cc ON cc.ID_THUTUC=bm.ID_THUTUC
					LEFT JOIN `motcua_loai_hoso` lhs ON cc.`ID_LOAIHOSO` = lhs.`ID_LOAIHOSO`
					WHERE bm.`ID_BIEUMAU` = ?';
		$r = $dbAdapter->query($sql, $id);
		$result = $r->fetch();
		return $result;
	}
}