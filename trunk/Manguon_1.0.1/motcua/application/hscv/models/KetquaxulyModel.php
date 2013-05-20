<?php

require_once ('Zend/Db/Table/Abstract.php');
require_once 'hscv/models/filedinhkemModel.php';
/**
 * Lop du thao , cau truc du lieu chua thong ve du thao
 *
 */
class Ketqua {
	var $_id_kq_mc;
	var $_id_hscv;
	var $_ngayky;
	var $_nguoiky;
	var $_sokyhieu;
}

class KetquaxulyModel extends Zend_Db_Table_Abstract {

	function __construct($year){
		if($year=="")$year=QLVBDHCommon::getYear();
		$this->_name = 'motcua_ketqua_'.$year;
		$config = array();
		parent::__construct($config);
	}
	
	function getAllList(){
		return $this->fetchAll();
	}
	
	function getAllm($idttt){
		
		$tablename = 'motcua_ketqua_'.QLVBDHCommon::getYear();
		$sql = "
			SELECT kq.*
			FROM motcua_ketqua_".QLVBDHCommon::getYear()." kq 
			inner join
				hscv_hosocongviec_".QLVBDHCommon::getYear()." hscongviec on kq.ID_HSCV=hscongviec.ID_HSCV
			
			WHERE hscongviec.ID_HSCV=?" ;
		$r = $this->getDefaultAdapter()->query($sql,array($idttt));
		return $r->fetchAll();
	}   
	
	static function getIDHSByIdHSCV($hoso){
		$sql = "  SELECT ID_LOAIHOSO FROM  motcua_hoso_".QLVBDHCommon::getYear()." hoso inner join hscv_hosocongviec_".QLVBDHCommon::getYear()." hscv on hoso.ID_HSCV=hscv.ID_HSCV WHERE hscv.ID_HSCV=?;
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($hoso));
			$re = $qr->fetch();
			return $re["ID_LOAIHOSO"];
		}catch(Exception $ex){
			return 0;
		}
	}
	function getListByIdHSCV($idHscv){
		$sql = "
			 SELECT hoso.* FROM  motcua_hoso_".QLVBDHCommon::getYear()." hoso inner join hscv_hosocongviec_".QLVBDHCommon::getYear()." hscv on hoso.ID_HSCV=hscv.ID_HSCV WHERE hscv.ID_HSCV=?" ;
		$r = $this->getDefaultAdapter()->query($sql,array($idHscv));
		return $r->fetch();
	}
	
	function getDataByIdKetqua($idKetqua){
		$se = $this->select()->where('ID_KQ_MC=?',$idKetqua);
		return $this->fetchRow($se);
	}
	/**
	 * Them moi mot du thao vao ho so cong viec
	 *
	 * @param unknown_type $idHscv
	 * @return id cua du thao moi them vao
	 */
	function insertOne($kq){
		$data = array(
		'ID_HSCV'=>$kq->_id_hscv,
		'NGAYKY'=>$kq->_ngayky,
		'NGUOIKY'=>$kq->_nguoiky,
		'SOKYHIEU'=>$kq->_sokyhieu
		
		);
		return $this->insert($data);
	}
	function updateByIdKetquaNoHSCV($idKetqua,$arrdata){
		$where = 'ID_KQ_MC='.$idKetqua;
		//var_dump((array)$arrdata);
		
		$this->update((array)$arrdata,$where);
	}
	function deleteByIdHSCV($id_hscv,$year){
		//lay danh sach van ban du thao tuong ung voi ho so cong viec
		$qr = $this->getDefaultAdapter()->query(
		"
			select ID_KQ_MC from motcua_ketqua_$year where ID_HSCV=?
		"
		,array($id_hscv));
		$data_dt = $qr->fetchAll();
		foreach ($data_dt as $item){
			$this->deleteOne($item["ID_KQ_MC"],$year);
		}
	}

	function deleteOne($idKetqua,$year){
		
		$sql ='DELETE from motcua_ketqua_'.$year.' where ID_KQ_MC='.$idKetqua;
		$query = $this->getDefaultAdapter()->query($sql);
		$query->execute();
		
		$this->delete('ID_KQ_MC='.$idKetqua);
		
		//Xoa danh sach cac phien ban du thao tuong ung voi du thao
		/*$sql = 'DELETE from `hscv_phienbanduthao_'.$year.'` where `hscv_phienbanduthao_'.$year.'`.`ID_DUTHAO`=?';
		$query = $this->getDefaultAdapter()->query($query,$data);
		$query->execute();
		
		$this->delete('ID_DUTHAO='.$idDuthao);*/
		
		//lay danh sach cac file dinh kem tuong unng voi danh sach phien ban
		//xoa 
		
	}
	/**
     * Select all menus from $offset to $limit with $order arrange
     *
     * @param  integer $offset
     * @param  integer $limit
     * @param  String $order
     * @return boolean
     */
	public function SelectAll($limit,$order){
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
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
			SELECT ".$this->_name.".*
			FROM ".$this->_name."				
			LEFT OUTER JOIN qtht_users ON (".$this->_name.".NGUOIKY = qtht_users.ID_U)
			WHERE
				$strwhere
			$strorder
			$strlimit
		",$arrwhere);
		return $result->fetchAll();
	}
	/**
	 * cap nhat nguoi ky theo id ho so cong viec
	 */
	function updateNguoiKyByIdHSCV($idHSCV,$nguoiky){
		$where = 'ID_HSCV='.$idHSCV;
		$data = array('NGUOIKY'=>$nguoiky);
		$this->update($data,$where);
	}
		
}

?>
