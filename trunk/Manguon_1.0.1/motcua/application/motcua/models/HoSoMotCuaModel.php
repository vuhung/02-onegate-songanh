<?php

require_once ('Zend/Db/Table/Abstract.php');
class HoSoMotCuaModel extends Zend_Db_Table_Abstract 
{
	protected $_name = 'motcua_hoso_2009';
    public $_id_phoihop = 0;
    protected $_year='2009';
    function getName()
    {
    	return $this->_name;
    }
    function setYear($year)
    {
    	$this->_year=$year;
    }
    function getYear()
    {
    	return $this->_year;
    }
    /**
     * Khoi tao model cho Ho So Mot Cua
     *
     * @param int $year
     */
	function __construct($year)
	{
		if($year!=null)
    	{
    		if((int)$year>2000 && (int)$year<3000)
    		{
    			$this->_name='motcua_hoso'.'_'.$year;
    			$this->setYear($year);
    		}
    		else $this->setYear('2009');
    	}
    	$arr = array();
		parent::__construct($arr);		
	}
	/**
	 * Enter description here...
	 *
	 * @param int $idHSCV
	 * @param unknown_type $ngay_tra
	 * @param unknown_type $luc_tra
	 * @param unknown_type $is_khongxuly
	 */
	function updateAfterTraHoSo(int $idHSCV,$ngay_tra,$luc_tra,$is_khongxuly){
		//cap nhat ngay tra , luc tra , co xu ly va trang thai da tra ho so
		$where = 'ID_HSCV='.$idHSCV;
		$data = array(
		'NHANLAI_NGAY'=>$ngay_tra,
		'NHANLAI_LUC'=>$luc_tra,
		'KHONGXULY'=>$is_khongxuly,
		'TRANGTHAI'=> 2
		);
		$this->update($data,$where);
	}
	/**
     * Count all items in hscv_phoihop_2009 table
     * @return integer
     */
    public function Count()
	{
		//Build phan where
		$arrwhere = array();
		$strwhere = "(1=1)";
		if($this->_search != "")
		{
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and (".$this->getName().".MAHOSO like ?";
		}		
		
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT
				count(*) as C
			FROM
				".$this->getName()."
		 	WHERE
				$strwhere
		 ",$arrwhere)->fetch();
		return $result["C"];
	}
	/**
	 * Danh sách Hồ sơ một cửa
	 * @param  integer $offset
     * @param  integer $limit
     * @param  String $order
     * @return boolean
	 */
	function SelectAll($offset,$limit,$search,$filter_object,$order){
		
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and (".$this->getName().".MAHOSO like ?) ";
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
		$result = $this->getDefaultAdapter()->query("
			SELECT ".$this->getName().".*,motcua_loai_hoso.TENLOAI AS TENLOAI,
			qtht_users.USERNAME AS NGUOINHAN
			FROM ".$this->getName()."				
			LEFT JOIN motcua_loai_hoso ON motcua_loai_hoso.ID_LOAIHOSO=".$this->getName().".ID_LOAIHOSO
			LEFT JOIN qtht_users ON qtht_users.ID_U=".$this->getName().".NGUOINHAN
			WHERE
			$strwhere			
			$strorder
			$strlimit
		",$arrwhere);
		return $result->fetchAll();
	}
	/**
     *  get All detail MOTCUA_HOSO_2010 table by ID_HSCV
     * @return array
     */
    public function getDetailhsmc($idhscv)
	{
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT
				mc.*,mclhs.TENLOAI
			FROM
				".QLVBDHCommon::Table("MOTCUA_HOSO")." mc
			INNER JOIN ".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv ON hscv.ID_HSCV = mc.ID_HSCV 
			INNER JOIN motcua_loai_hoso mclhs ON mclhs.ID_LOAIHOSO = mc.ID_LOAIHOSO
		 	WHERE
				hscv.ID_HSCV=?
		 ",array($idhscv));

		return $result->fetchAll();
	}
}

?>
