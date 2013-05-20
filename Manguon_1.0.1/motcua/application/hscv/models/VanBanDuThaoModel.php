<?php

require_once ('Zend/Db/Table/Abstract.php');
require_once 'hscv/models/filedinhkemModel.php';
/**
 * Lop du thao , cau truc du lieu chua thong ve du thao
 *
 */
class DuThao {
	var $_id_duthao;
	var $_id_hscv;
	var $_tenduthao;
	var $_nguoiky;
	var $_nguoisoan;
	var $_trangthai;
}

class VanBanDuThaoModel extends Zend_Db_Table_Abstract {

	function __construct($year){
		$this->_name ='hscv_duthao'.'_'.$year;
		$arr = array();
		parent::__construct($arr);
	}
	
	function getAllList(){
		return $this->fetchAll();
	}
	
	function getListByIdHSCV($idHscv){
		//neu id hscv = -1 => Lay tat ca cac du thao duoc luu tam
		//if($idHscv ==-1)
		//	return $this->fetchAll();
		$se = $this->select()->where('ID_HSCV=?',$idHscv);
		return $this->fetchAll($se);
	}
	
	function getDataByIdDuthao($idDuthao){
		$se = $this->select()->where('ID_DUTHAO=?',$idDuthao);
		return $this->fetchRow($se);
	}
	/**
	 * Them moi mot du thao vao ho so cong viec
	 *
	 * @param unknown_type $idHscv
	 * @return id cua du thao moi them vao
	 */
	function insertOne($duthao){
		$data = array(
		'ID_HSCV'=>$duthao->_id_hscv,
		'TENDUTHAO'=>$duthao->_tenduthao,
		'NGUOIKY'=>$duthao->_nguoiky,
		'NGUOISOAN'=>$duthao->_nguoisoan,
		'TRANGTHAI'=>$duthao->_trangthai,
		'ID_LVB'=>$duthao->_idlvb
		);
		return $this->insert($data);
	}
	function updateByIdDuthaoNoHSCV($idDuthao,$arrdata){
		$where = 'ID_DUTHAO='.$idDuthao;
		//var_dump((array)$arrdata);
		
		$this->update((array)$arrdata,$where);
	}
	
	function deleteByIdHSCV($id_hscv,$year){
		//lay danh sach van ban du thao tuong ung voi ho so cong viec
		$qr = $this->getDefaultAdapter()->query(
		"
			select ID_DUTHAO from hscv_duthao_$year where ID_HSCV=?
		"
		,array($id_hscv));
		$data_dt = $qr->fetchAll();
		foreach ($data_dt as $item){
			$this->deleteOne($item["ID_DUTHAO"],$year);
		}
	}

	function deleteOne($idDuthao,$year){
		
		$sql = 'select `ID_DK` from `gen_filedinhkem_'.$year.'`
			where `gen_filedinhkem_'.$year.'`.`ID_OBJECT`
			in (select `ID_PB_DUTHAO` from `hscv_phienbanduthao_'.$year.'`
			where `hscv_phienbanduthao_'.$year.'`.`ID_DUTHAO`=?
			and `gen_filedinhkem_'.$year.'`.`TYPE`=2)';
		$data = array($idDuthao);
		$query = $this->getDefaultAdapter()->query($sql,$data);
		$arrid_dk = $query->fetchAll();
		$fdkModel = new filedinhkemModel($year);
	
		foreach($arrid_dk as $id_dk){
			$fdkModel->deleteFile($id_dk['ID_DK']);
		}
		
		$sql = 'DELETE from `hscv_phienbanduthao_'.$year.'` where `hscv_phienbanduthao_'.$year.'`.`ID_DUTHAO`='.$idDuthao;
		
		$query = $this->getDefaultAdapter()->query($sql);
		$query->execute();
		
		$this->delete('ID_DUTHAO='.$idDuthao);
		
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
	public function SelectAll($offset,$limit,$order){
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and (".$this->_name.".TRANGTHAI = ?) ";
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
     * Count all items in qtht_employees table
     * @return integer
     */
	public function Count()
	{
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and (".$this->_name.".TRANGTHAI = ?) ";
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
	
	function updateTrangthaiByIdHSCV($idHSCV,$trangthai){
		$where = 'ID_HSCV='.$idHSCV;
		$data = array('TRANGTHAI'=>$trangthai);
		$this->update($data,$where);
	}
	
	function updateTrangthaiByIdDuthao($idDuthao,$trangthai){
		$where = 'ID_DUTHAO='.$idDuthao;
		$data = array('TRANGTHAI'=>$trangthai);
		$this->update($data,$where);
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
