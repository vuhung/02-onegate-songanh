<?php
require_once ('Zend/Db/Table/Abstract.php');
require_once 'Common/ConvertFileUtils.php';
require_once 'hscv/models/FileTransfer.php';
require_once 'Common/Convert.php';

class Vanban {
	var $_id_dk;
	var $_folder;
	var $_id_object;
	var $_maso;
	var $_nam;
	var $_thang;
	var $_mime;
	var $_filename;
	var $_type;
	var $_content;
	var $_user;
	var $_id_identify;
	var $_time_update;
	var $_pathFile;
}

class VanbanModel extends Zend_Db_Table
{
    protected $_name = 'qllt_vanban';
    public $_id_p = 0;
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
			$strwhere .= " and qllt_vanban.TENVANBAN like ?";
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
	function GetAllLoaihoso()
	{
		$r = $this->getDefaultAdapter()->query("
			SELECT
     			*
			FROM
				qllt_vanban");
		return $r->fetchAll();
	}
	
	static function getAll()
	{
		$r = Zend_Db_Table::getDefaultAdapter()->query("
			SELECT
     			*
			FROM
				qllt_vanban
			WHERE ID_VANBAN != 1
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
			$strwhere .= " and TENVANBAN like ?";
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
				  VB.`ID_VANBAN`,VB.`FILECODE`,VB.`FILEMIME`,VB.`FILENAME`,
				  VB.`GHICHU`,VB.`ID_HOSO`,VB.`KYHIEUVANBAN`,VB.`NGAYTHANGVANBAN`,
				  VB.`SOTO`,VB.`TACGIAVANBAN`,VB.`TENVANBAN`,VB.`TRICHYEU`
			FROM `qllt_vanban` AS VB
			LEFT JOIN `qllt_hoso` AS HS ON VB.`ID_HOSO` = HS.`ID_HOSO` 
			WHERE $strwhere $strorder $strlimit";
		$result = $this->getDefaultAdapter()->query($sql,$arrwhere);
		return $result->fetchAll();
	}
	

//	public function NewLoaihslt($id, $array)
//	{
//		if($id == 0)
//		{
//			//insert
//			$db = Zend_Db_Table::getDefaultAdapter();
//			$db->insert("qllt_loaihoso",$array);
//		}
//		else 
//		{			
//			//update
//			$db = Zend_Db_Table::getDefaultAdapter();
//			$db->update("qllt_loaihoso",$array,"ID_LOAIHOSO=".(int)$id);			
//		}		
//	}

	static function insertFileVanban($oldmaso){
		//Lay cac bien toan cuc
		$con = Zend_Registry::get('config');
		$au = Zend_Registry::get('auth');
		$year = QLVBDHCommon::getYear();
		$model = new filedinhkemModel($year);
		$max_size = $con->file->maxsize;
		$temp_path = $model->getTempPath();
		$filepath = $temp_path.DIRECTORY_SEPARATOR.$_FILES['uploadedfile']['name'];
		//var_dump($filepath);exit;
		if (!move_uploaded_file($_FILES['uploadedfile']['tmp_name'],$filepath)){
			return array("","","");
		}else{
			if($oldmaso!="" && $_FILES['uploadedfile']['name']!=""){
				unlink($con->file->root_dir.DIRECTORY_SEPARATOR."vanban".DIRECTORY_SEPARATOR.$oldmaso);
			}
			$filename = $_FILES['uploadedfile']['name'];
			$mime = $_FILES['uploadedfile']['type'];
			$maso = $filename.date("Y-m-d H:i:s");
			$maso = md5($maso);
			//var_dump($con->file->root_dir.DIRECTORY_SEPARATOR."iso".DIRECTORY_SEPARATOR);exit;
			$newlocation = $con->file->root_dir.DIRECTORY_SEPARATOR."vanban".DIRECTORY_SEPARATOR.$maso;			
			rename($filepath,$newlocation);
			return array($maso,$mime,$filename);
		}
	}

	public function GetVanbanById($id)
	{
		$sql ="SELECT * FROM qllt_vanban WHERE ID_VANBAN=".$id;
		$result = $this->getDefaultAdapter()->query($sql);
		return $result->fetch();
	}

	public function GetVanbanByIdhoso($id)
	{
		$sql ="SELECT * FROM qllt_vanban WHERE ID_HOSO=".$id;
		$result = $this->getDefaultAdapter()->query($sql);
		return $result->fetchAll();
	}
	
	function getTempPath(){
		$con = Zend_Registry::get('config');
		$root = $con->file->root_dir;
		$temp_path = $con->file->temp_path;
		if(!file_exists($root))
			mkdir($root);
		if(!file_exists($temp_path))
			mkdir($temp_path);
		return $temp_path; 
	}
}