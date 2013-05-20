<?
class linhvucModel{
	
	static function save($id,$params){
		$parameters  = array();
		$parameters[] = $params["TEN"];
		$parameters[] = $params["ACTIVE"];
		$sql = "";
		if($id > 0){
			//cap nhat
			$sql = " update linhvuc set TEN = ? , ACTIVE = ?  where ID_LINHVUC = ? ";
			
			$parameters[] = $id;

		}else{
			//them moi
			$sql = " insert into linhvuc (TEN,ACTIVE) values (?,?) ";
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
	
		$sql = "select * from linhvuc ";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql);
			return $qr->fetchAll();
		}catch(Exception $ex){
			return array();
		}
	}

	static function getById($id){
		$sql = "select * from linhvuc where ID_LINHVUC=? ";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($id));
			return $qr->fetch();
		}catch(Exception $ex){
			return array();
		}
	}

	static function deleteById($id){
		$sql = " delete from linhvuc where ID_LINHVUC=?  ";
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
		$data_lv = linhvucModel::getAll();
		echo "<option value=0> -- Chọn một lĩnh vực -- </option>";
		foreach($data_lv as $item){
			echo "<option value='".$item["ID_LINHVUC"]."' " . ($item["ID_LINHVUC"]==$id_sel?"selected":"")  .  ">".$item["TEN"]."</option>";	
		}
	}
}