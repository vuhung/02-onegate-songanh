<?php

/**
 * hosocongviec
 *  
 * @author nguyennd
 * @version 1.0
 * @deprecated add 14/10/2009 
 */

require_once 'Zend/Db/Table/Abstract.php';
require_once 'vbden/models/vbdenModel.php';
require_once 'hscv/models/filedinhkemModel.php';
require_once 'hscv/models/VanBanDuThaoModel.php';
require_once 'hscv/models/VanBanLienQuanModel.php';
require_once 'auth/models/ResourceUserModel.php';

class bosunghosoModel extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'hscv_hosocongviec_2009';
	function __construct($year=null){
		if($year=="")$year=QLVBDHCommon::getYear();
		$this->_name = 'hscv_hosocongviec_'.$year;
		$config = array();
		parent::__construct($config);
	}
	/**
	 * Đếm bản ghi các hồ sơ công việc dựa trên tham số đầu vào.
	 * Danh sách tham số đầu vào
	 * ID_THUMUC
	 * ID_LOAIHSCV
	 * NGAY_BD
	 * NGAY_KT
	 * NAME
	 * TRANGTHAI
	 * ID_P
	 * ID_A
	 * ID_U
	 * @param array $parameter
	 */
	function Count($parameter){
		global $db;
		$where = "  ";
		$param = array();
		if($parameter['TENTOCHUCCANHAN']!=""){
			$wheretemp = "";
			Common_DBUtils::repairTableBeforeFulltextSearch(QLVBDHCommon::Table('MOTCUA_HOSO'));
			$wheretemp = " match(mc.TENTOCHUCCANHAN) against (? IN BOOLEAN MODE)";
			$order = " match(mc.TENTOCHUCCANHAN) against ('".str_replace("'","''",$parameter['TENTOCHUCCANHAN'])."') DESC";
			$param[] = $parameter['TENTOCHUCCANHAN'];
			$where .= " and (".$wheretemp.")";
		}
		
	//check phuong
		if($parameter['ID_PHUONG']!=0){
			if($parameter['ID_PHUONG']==-1){
				$where .= " and mc.PHUONG is null";
			}else{
				$param[] = $parameter['ID_PHUONG'];
				$where .= " and mc.PHUONG = ?";
			}
		}
	//
	if($parameter['MAHOSO']!=""){
			$wheretemp = "";
		    $wheretemp = " mc.MAHOSO = ? ";
			$param[] = $parameter['MAHOSO'];
			$where .= " and (".$wheretemp.")";
		}
		
		//$param = Array();
		$sql = "
			SELECT count(*) as DEM  FROM 
				".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv 
			inner join ".  QLVBDHCommon::Table("MOTCUA_HOSO") ." mc on hscv.ID_HSCV =  mc.ID_HSCV
			where  hscv.IS_BOSUNG = 1
			$where
		";
		//$param[] = $parameter['ID_U'];
		try{
			//echo $sql;
			$r = $db->query($sql,$param);
			$result = $r->fetch();

		}catch(Exception $ex){
			//echo $ex->__toString();;
			return 0;
		}
		return $result["DEM"];
	}
	/**
	 * Lấy danh sách các hồ sơ công việc dựa trên tham số đầu vào.
	 * Danh sách tham số đầu vào
	 * ID_THUMUC
	 * ID_LOAIHSCV
	 * NGAY_BD
	 * NGAY_KT
	 * NAME
	 * TRANGTHAI
	 * ID_P
	 * ID_A
	 * ID_U
	 * @param array $parameter
	 */
	function SelectAll($parameter,$offset,$limit,$order){
		
		global $db;
		$user = Zend_Registry::get('auth')->getIdentity();
		
		$where = "  ";
		$param = array();
		$param[] = $user->ID_U;
		if($parameter['TENTOCHUCCANHAN']!=""){
			$wheretemp = "";
			Common_DBUtils::repairTableBeforeFulltextSearch(QLVBDHCommon::Table('MOTCUA_HOSO'));
			$wheretemp = " match(mc.TENTOCHUCCANHAN) against (? IN BOOLEAN MODE)";
			$order = " match(mc.TENTOCHUCCANHAN) against ('".str_replace("'","''",$parameter['TENTOCHUCCANHAN'])."') DESC";
			$param[] = $parameter['TENTOCHUCCANHAN'];
			$where .= " and (".$wheretemp.")";
		}
		
	//check phuong
		if($parameter['ID_PHUONG']!=0){
			if($parameter['ID_PHUONG']==-1){
				$where .= " and mc.PHUONG is null";
			}else{
				$param[] = $parameter['ID_PHUONG'];
				$where .= " and mc.PHUONG = ?";
			}
		}
	//
	if($parameter['MAHOSO']!=""){
			$wheretemp = "";
		    $wheretemp = " mc.MAHOSO = ? ";
			$param[] = $parameter['MAHOSO'];
			$where .= " and (".$wheretemp.")";
		}
		
	

	//$param = Array();
	$sql = "
			SELECT hscv.*, mc.PHUONG , mc.TENTOCHUCCANHAN, mc.MAHOSO,  max(bs.ID_YEUCAU) as ID_YEUCAU FROM 
				".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv 
			inner join ".  QLVBDHCommon::Table("MOTCUA_HOSO") ." mc on hscv.ID_HSCV =  mc.ID_HSCV
			inner join motcua_loai_hoso loaihs on mc.ID_LOAIHOSO = loaihs.ID_LOAIHOSO 
			inner join ( select lv.* from motcua_linhvuc lv inner join motcua_linhvuc_quyen pq on lv.ID_LV_MC = pq.ID_LV_MC   where ID_U = ?) 
			linhvuchs on loaihs.ID_LV_MC = linhvuchs.ID_LV_MC
			inner join ".  QLVBDHCommon::Table("HSCV_PHIEU_YEUCAU_BOSUNG") ." bs on hscv.ID_HSCV = bs.ID_HSCV
			GROUP BY hscv.ID_HSCV having hscv.IS_BOSUNG = 1
			$where
		";
		//
		try{
			//echo $sql;
			$r = $db->query($sql,$param);
			$result = $r->fetchAll();
			//var_dump($param);
		}catch(Exception $ex){
			echo $ex->__toString();;
			return null;
		}
		return $result;
	}
	

	static function getHscvVuaBS(){
		$user = Zend_Registry::get('auth')->getIdentity();
		
		$sql = " select pyc.* , hscv.NAME , concat(emp.FIRSTNAME,' ',emp.LASTNAME) as NAME_NGUOIBOSUNG from  ". QLVBDHCommon::Table("hscv_phieu_yeucau_bosung") ." pyc 
		inner join ". QLVBDHCommon::Table("hscv_hosocongviec") . " hscv on pyc.ID_HSCV = hscv.ID_HSCV
		left join qtht_users user on pyc.NGUOIBOSUNG = user.ID_U
		left join qtht_employees emp on user.ID_EMP = emp.ID_EMP
		where pyc.NGUOIYEUCAU=? and pyc.NGUOIBOSUNG > 0 and  (  pyc.DA_XEM_HDBS is NULL or pyc.DA_XEM_HDBS  = 0 )
		GROUP BY pyc.ID_HSCV
		";
		
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($user->ID_U));
			return $qr->fetchAll();
		}catch(Exception $ex){
			return array();
		}
	}

	


	static function isVuaBosung($idhscv){
		$sql = "
			select yc.ID_YEUCAU from ". QLVBDHCommon::Table("hscv_hosocongviec") ." hscv
			inner join ". QLVBDHCommon::Table("hscv_phieu_yeucau_bosung") ." yc on hscv.ID_HSCV = yc.ID_HSCV
			where hscv.ID_HSCV = ? and yc.NGUOIBOSUNG > 0 and ( DA_XEM_HDBS = 0  or DA_XEM_HDBS is NULL)
			ORDER BY yc.ID_YEUCAU DESC
		";
	
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($idhscv));
			$re = $qr->fetch();
			return $re["ID_YEUCAU"];
		}catch(Exception $ex){
			return 0;
		}
	}

	static function CapnhatVuabosungDaxem($id_yeucau){
		
		$sql = "
			update ". QLVBDHCommon::Table("hscv_phieu_yeucau_bosung") ." 
			set DA_XEM_HDBS = 1 where ID_YEUCAU = ?
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->prepare($sql);
			$stm->execute(array($id_yeucau));
			return 1;
		}catch(Exception $ex){
			return 0;
		}

	}

	
	
}

	 		
		 