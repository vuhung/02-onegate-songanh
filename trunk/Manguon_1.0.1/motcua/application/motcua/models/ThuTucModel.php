<?php
/**
 * LoaisModel
 *  
 * @author truongvc
 * @version 1.0
 */
class ThuTucModel extends Zend_Db_Table
{
    protected $_name = 'motcua_thutuc_canco';
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
		if($this->_id_loai > 0){
            $arrwhere[] = $this->_id_loai;
            $strwhere .= " and motcua_thutuc_canco.ID_LOAIHOSO = ?";
        }	
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and motcua_thutuc_canco.TENTHUTUC like ?";
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
	public function SelectAll($offset,$limit,$order){
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_id_loai > 0){
            $arrwhere[] = $this->_id_loai;
            $strwhere .= " and motcua_thutuc_canco.ID_LOAIHOSO = ?";
        }
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and (motcua_thutuc_canco.TENTHUTUC like ?) ";
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
			SELECT motcua_thutuc_canco.*,motcua_loai_hoso.TENLOAI AS LOAI
			FROM motcua_thutuc_canco
			LEFT JOIN motcua_loai_hoso
			ON motcua_loai_hoso.ID_LOAIHOSO=motcua_thutuc_canco.ID_LOAIHOSO				
			WHERE
				$strwhere
			$strorder
			$strlimit
		",$arrwhere);
		return $result->fetchAll();
	}
	function GetAllThuTucs()
	{
		try
		{
			$r = Zend_Db_Table::getDefaultAdapter()->query("
				SELECT
					*
				FROM
					motcua_thutuc_canco
				order by TENTHUTUC
				");
			return $r->fetchAll();
		}
		catch(Exception $e){}				
	}
	/**
    * Chuyển dữ liệu tới combobox
    */
    public function ToCombo($data,$sel){
        $html="";
        $html .= "<option value='0'>--Chọn loại thủ tục--</option>";
        foreach($data as $row){
            $html .= "<option value='".$row["ID_THUTUC"]."' ".($row["ID_THUTUC"]==$sel?"selected":"").">".$row["TENTHUTUC"]."</option>";
        }
        return $html;
    }	
}