<?php 
class HosoluutheodoiModel {
	static function Count($parameter){
		global $db;
		$param = Array();
		$sql = "
			SELECT count(*) as CNT FROM 
				".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv 
				inner join ".QLVBDHCommon::Table("HSCV_HOSOLUUTHEODOI")." luu on hscv.ID_HSCV = luu.ID_HSCV
			where
				hscv.IS_THEODOI = 1
				and luu.U_OWN = ?
		";
		$param[] = $parameter['ID_U'];
		//echo $parameter['ID_U'];
		try{
			//echo $sql;
			$r = $db->query($sql,$param);
			$cnt = $r->fetch();
			
		}catch(Exception $ex){
			echo $ex->__toString();
			return 0;
		}
		return $cnt["CNT"];
	}
	static function SelectAll($parameter,$offset,$limit,$order){
		global $db;
		$param = Array();
		$strlimit = "";
		if($limit>0){
			$strlimit = " LIMIT $offset,$limit";
		}
		$sql = "
			SELECT hscv.*,luu.ID_LUUTD FROM 
			".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv 
			inner join ".QLVBDHCommon::Table("HSCV_HOSOLUUTHEODOI")." luu on hscv.ID_HSCV = luu.ID_HSCV
			where
				hscv.IS_THEODOI = 1
				and luu.U_OWN = ?
			ORDER BY DATE_CREATE DESC
			$strlimit
		";
		$param[] = $parameter['ID_U'];
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
	static function addNew($year,$id_hscv,$commnent,$id_u){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		
		$sql="
		Insert into `hscv_hosoluutheodoi_$year` (`U_OWN`,`ID_HSCV`,`COMMENT`,`DATE_CREATE`) values (?,?,?,'".date("Y-m-d H:i:s")."') 
		";
		try{
			$stm = $dbAdapter->prepare($sql);
			$stm->execute(array($id_u,$id_hscv,$commnent));
			return $dbAdapter->lastInsertId("hscv_hosoluutheodoi_$year",'ID_LUUTD');
		}catch(Exception $ex){
			return 0;
		}
	}
	
	static function luuTheodoi($year,$id_hscv,$comment,$id_u){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		//cap nhat co luu theo hoi cua ho so cong viec
		//kiem tra ho so cong viec co ton tai hay khong
		$sql = "
		select count(*) as DEM  from hscv_hosocongviec_$year hscv where ID_HSCV=?
		";
		
		$re = array();
		try{
			$query = $dbAdapter->query($sql,array($id_hscv));
			$re = $query->fetch();
		}catch(Exception $ex){
			//loi co so du lieu
			return -3;
		}
		$c_hscv = $re["DEM"];
		
		
		if($c_hscv == 0){
			//ho so cong viec khong ton tai
			return -1;
		}
		
		$sql = "
		select IS_THEODOI  from hscv_hosocongviec_$year hscv where ID_HSCV=?
		";
		
		$re = array();
		try{
			$query = $dbAdapter->query($sql,array($id_hscv));
			$re = $query->fetch();
		}catch(Exception $ex){
			//loi co so du lieu
			return -3;
		}
		
		
		$is_theodoi = $re["IS_THEODOI"];
		
		if($is_theodoi == 1){
			//ho so cong viec da o trang thai dang theo doi
			return -2;
		}
		
		$sql ="
			Update  hscv_hosocongviec_$year set `IS_THEODOI`=1 where ID_HSCV=?
		";
		try{
			$stm = $dbAdapter->prepare($sql);
			$stm->execute(array($id_hscv));
			
		}catch(Exception $ex){
			//loi co so du lieu
			return -3;
		}
		
		//them moi vao bang ho so luu theo doi
		return HosoluutheodoiModel::addNew($year,$id_hscv,$comment,$id_u);
	}
	
	static function phuchoiluuTheodoi($id_luutd,$idu){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		//lay thong tin ve ho so luu theo doi
		$sql ="
			select hs.* from ".QLVBDHCommon::Table("HSCV_HOSOLUUTHEODOI")." hs where ID_LUUTD=? and U_OWN=?
		";
		$re = array();
		try{
			$query = $dbAdapter->query($sql,array($id_luutd,$idu));
			$re = $query->fetch();
			
		}catch (Exception $ex){
			//loi sql 
			return -3;
		}
		if(count($re) == 0){
			//khong tim thay ho so luu theo doi
			return -1;
		}
		if(!($re["ID_HSCV"] > 0)){
			//khong tim thay ho so cong viec tuong ung
			return -2;
		}
		try{
			$dbAdapter->update(QLVBDHCommon::Table("HSCV_HOSOCONGVIEC"),array("IS_THEODOI"=>0),"ID_HSCV=".$re["ID_HSCV"]);
			
		}catch(Exception $ex){
			//echo $ex;
			return -3;
		}
		
		//xoa ho so luu theo doi trong danh sach
		try{
			$dbAdapter->delete(QLVBDHCommon::Table("HSCV_HOSOLUUTHEODOI"),"ID_LUUTD=".$re["ID_LUUTD"]);
		}catch(Exception $ex){
			//loi co so du lieu
			return -3;
		}
	}
}
