<?
class quitrinhModel{
	
	static function save($id,$params){
		$parameters  = array();
		$parameters[] = $params["TEN"];
		$parameters[] = $params["ID_RESOURCE"];
		$parameters[] = $params["GHICHU"];
		$parameters[] = $params["ACTIVE"];
		$sql = "";
		if($id > 0){
			//cap nhat
			$sql = " update quitrinh set TEN = ? ,ID_RESOURCE=?,GHICHU = ?, ACTIVE = ?  where ID_QUITRINH = ? ";
			
			$parameters[] = $id;

		}else{
			//them moi
			$sql = " insert into quitrinh (TEN,ID_RESOURCE,GHICHU,ACTIVE) values (?,?,?,?) ";
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

	static function saveloaihoso($id,$id_loaihoso,$id_loaihoso_old){
		
		$parameters  = array();
		
		$sql = "";
		if($id_loaihoso_old > 0){
			//cap nhat
			$sql = " update fk_quitrinhs_loaihoso set ID_QUITRINH = ? , ID_LOAIHOSO = ?  where ID_QUITRINH = ? and  ID_LOAIHOSO = ?";
			
			$parameters[] = $id;
			$parameters[] = $id_loaihoso;
			$parameters[] = $id;
			$parameters[] = $id_loaihoso_old;

		}else{
			//them moi
			$sql = " insert into fk_quitrinhs_loaihoso (ID_QUITRINH,ID_LOAIHOSO) values (?,?) ";
			$parameters[] = $id;
			$parameters[] = $id_loaihoso;
		}
		
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->prepare($sql);
			$stm->execute($parameters);
			return 1;
		}catch(Exception $ex){
			return 0;
		}

	}

	static function getAll(){
	
		$sql = "select qt.* , lhs.TEN as TENLOAIHOSO , fk.ID_LOAIHOSO from quitrinh qt
		inner join fk_quitrinhs_loaihoso fk on  qt.ID_QUITRINH = fk.ID_QUITRINH
		inner join loaihoso lhs on fk.ID_LOAIHOSO = lhs.ID_LOAIHOSO
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql);
			return $qr->fetchAll();
		}catch(Exception $ex){
			return array();
		}
	}
	static function getAllWithFilter($params){
	
		$parameter = array();
		$where = " where (1=1) ";
		if($params["ID_LOAIHOSO"]){
			$where .= "   and fk.ID_LOAIHOSO = ? ";
			$parameter[] = $params["ID_LOAIHOSO"];
		}
		$sql = "select qt.* , lhs.TEN as TENLOAIHOSO , fk.ID_LOAIHOSO from quitrinh qt
		inner join fk_quitrinhs_loaihoso fk on  qt.ID_QUITRINH = fk.ID_QUITRINH
		inner join loaihoso lhs on fk.ID_LOAIHOSO = lhs.ID_LOAIHOSO
		$where
		";
		//try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,$parameter);
			return $qr->fetchAll();
		//}catch(Exception $ex){
			return array();
		//}
	}
	
	static function getLastInsertId(){
		$sql = " select max(ID_QUITRINH) as last_id from quitrinh ";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql);
			$re = $qr->fetch();
			return $re["last_id"];
		}catch(Exception $ex){
			return -1;
		}
	
	}

	static function getById($id){
		$sql = "select qt.*, fk.ID_LOAIHOSO from quitrinh qt
		inner join fk_quitrinhs_loaihoso fk on  qt.ID_QUITRINH = fk.ID_QUITRINH
		where qt.ID_QUITRINH=? ";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($id));
			return $qr->fetch();
		}catch(Exception $ex){
			return array();
		}
	}
	
	static function deleteById($id){
		$sql = " delete from quitrinh where ID_QUITRINH=?  ";
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
		$data_lv = quitrinhModel::getAll();
		echo "<option value=0> -- Chọn một lĩnh vực -- </option>";
		foreach($data_lv as $item){
			echo "<option value='".$item["ID_QUITRINH"]."' " . ($item["ID_QUITRINH"]==$id_sel?"selected":"")  .  ">".$item["TEN"]."</option>";	
		}
	}
}