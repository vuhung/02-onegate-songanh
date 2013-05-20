<?
class loaihosoModel{
	
	static function save($id,$params){
		$parameters  = array();
		$parameters[] = $params["TEN"];
		$parameters[] = $params["ID_LINHVUC"];
		$parameters[] = $params["ACTIVE"];
		$parameters[] = $params["LEPHI"];
		$parameters[] = $params["SONGAYXULY"];
		$parameters[] = $params["GHICHU"];
		$parameters[] = $params["CHITIET_HOSO"];
		//echo $params["CHITIET_HOSO"]; exit;
		$sql = "";
		if($id > 0){
			//cap nhat
			$sql = " update loaihoso set TEN = ? , ID_LINHVUC=? , ACTIVE = ? , LEPHI = ? , SONGAYXULY = ? , GHICHU = ? ,CHITIET_HOSO =? where ID_LOAIHOSO = ?  ";
			
			$parameters[] = $id;

		}else{
			//them moi
			$sql = " insert into loaihoso (TEN,ID_LINHVUC,ACTIVE,LEPHI,SONGAYXULY,GHICHU,CHITIET_HOSO) values (?,?,?,?,?,?,?) ";
		}
		
		//try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->prepare($sql);
			$stm->execute($parameters);
			return 1;
		//}catch(Exception $ex){
			//return 0;
		//}

	}

	static function getAll(){
	
		$sql = "select loaihs.*, lv.TEN as TEN_LINHVUC  from loaihoso loaihs
		inner join linhvuc lv on loaihs.ID_LINHVUC = lv.ID_LINHVUC
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql);
			return $qr->fetchAll();
		}catch(Exception $ex){
			return array();
		}
	}
	static function getAllByLV($id_linhvuc){
	
		$where = "";
		if($id_linhvuc > 0)
			$where = " where loaihs.ID_LINHVUC = ? ";
		$sql = "select loaihs.*, lv.TEN as TEN_LINHVUC  from loaihoso loaihs
		inner join linhvuc lv on loaihs.ID_LINHVUC = lv.ID_LINHVUC
		$where 
		";
		//try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($id_linhvuc));
			return $qr->fetchAll();
		//}catch(Exception $ex){
			return array();
		//}
	}
	static function select_last_id(){
		$sql = "  select max(ID_LOAIHOSO) as ID_LAST from loaihoso  ";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql);
			$re =  $qr->fetch();
			return $re["ID_LAST"];
		}catch(Exception $ex){
			return -1;
		}
	}
	
	static function update_file_quitrinh($id,$name){
		$sql = "  update  loaihoso set IMAGE_QUITRINH = ? where ID_LOAIHOSO = ? ";

		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->prepare($sql);
			$stm->execute(array($name,$id));
			return 1;
		}catch(Exception $ex){
			return 0;
		}
	}


	static function getById($id){
		$sql = "select * from loaihoso where ID_LOAIHOSO=? ";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($id));
			return $qr->fetch();
		}catch(Exception $ex){
			return array();
		}
	}

	static function deleteById($id){
		$sql = " delete from loaihoso where ID_LOAIHOSO=?  ";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->prepare($sql);
			$stm->execute(array($id));
			return 1;
		}catch(Exception $ex){
			return 0;
		}
	}

	static function toCombo($id_sel){
		$data_lv = loaihosoModel::getAll();
		echo "<option value=0> -- Chọn một loại hồ sơ -- </option>";
		foreach($data_lv as $item){
			echo "<option value='".$item["ID_LOAIHOSO"]."' " . ($item["ID_LOAIHOSO"]==$id_sel?"selected":"")  .  ">".$item["TEN"]."</option>";	
		}
	}
}