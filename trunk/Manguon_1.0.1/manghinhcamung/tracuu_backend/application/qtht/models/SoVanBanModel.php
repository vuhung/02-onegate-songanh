<?php

require_once 'Zend/Db/Table/Abstract.php';
class SoVanBanModel extends Zend_Db_Table_Abstract {

	var $_name = "vb_sovanban";
	function findByMixed($page,$limit,$name,$type){
		$name='%'.$name.'%';
		$arr = array($name,$type);
		$wherename = 'NAME LIKE ?';
		$whereactive = 'TYPE = ?';
		$select = $this->select();
		$select->where($wherename,$name);
		if($page !=0 && $limit !=0){
			$select->limitPage($page,$limit);	
		}
		if($type>3||$type<=0||is_null($type)){
			
		}else{
			$select->where($whereactive,$type);
		}
		
		return $this->fetchAll($select);
	}
	
	function findByName($name){
		$name='%'.$name.'%';
		$where = 'NAME LIKE ?';
		return $this->fetchAll($this->select()->where($where,array($name)));
		
	}
	
	function findByActive($active){
		if((!$active)||$active>=2||$active<0){
			return $this->fetchAll();
		}
		$where = 'ACTIVE=?';
		return $this->fetchAll($this->select()->where($where,array($active)));
	}
	
	static function toComboName($is_di){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		
		$type = 1;
		if($is_di == 1)
			$type = 2;
		$sql = "select `NAME`,`ID_SVB` from `vb_sovanban` where TYPE=? AND YEAR=".QLVBDHCommon::getYear();
		$query = $dbAdapter->query($sql,array($type));
		$re = $query->fetchAll();
		echo 1;
		foreach ($re as $row){
			echo "<option id='svbcombo".$row['ID_SVB']."' value=".$row['ID_SVB'].">".$row['NAME']."</option>";
		}
	}
	
	static function  toComboFilter($filter_object){
		
		if($filter_object>2||$filter_object<=0||is_null($filter_object) )
			echo '<option value=0  selected>Chọn tất cả</option>';
		else 
			echo '<option value=0 >Chọn tất cả</option>';
		
		if($filter_object==1)
			echo '<option value=1 selected>Văn bản đến</option>';
		else 
			echo '<option value=1>Văn bản đến</option>';
		
		if($filter_object==2 )
			echo '<option value=2 selected>Văn bản đi</option>';
		else
			echo '<option value=2>Văn bản đi</option>';
		
		if($filter_object==2 )
			echo '<option value=3 selected>Hồ sơ một cửa</option>';
		else
			echo '<option value=3>Hồ sơ một cửa</option>';
		
	}
	
	function count($name,$type){
		
		$name='%'.$name.'%';
		$arr = array($name,$active);
		$wherename = 'NAME LIKE ?';
		$whereactive = 'TYPE = ?';
		$select = $this->select(); 
        $select->from($this->_name,'COUNT(*) AS num'); 
		$select->where($wherename,$name);
        if($type>2||$type<=0||is_null($type)){
			
		}
		else{
			$select->where($whereactive,$type);
		}
        $row=$this->fetchRow($select); 
        return $row->num; 
	}
	static function getNameById($id_svb){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql ="
			select NAME from `vb_sovanban` where `ID_SVB`=?
		";
		try{
			$stm = $dbAdapter->query($sql,array($id_svb));
			$re = $stm->fetch();
			return $re["NAME"];
		}catch (Exception  $ex) {
			return "";
		}
	}

	static function getData($year,$is_di){
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql ="
			select * from `vb_sovanban` 
			where YEAR=? and TYPE=?
		";
		$type = 1;
		if($is_di == 1)
			$type = 2;
		try{
			$stm = $dbAdapter->query($sql,array($year,$type));
			$re = $stm->fetchAll();
			return $re;
		}catch (Exception  $ex) {
			return array();
		}
	}
	static function getDataByCQ($year,$id_cq,$is_di){
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql ="
			select ID_SVB , NAME from `vb_sovanban` 
			where YEAR=? and TYPE=? and (ID_CQ = ? or ID_CQ is NULL or ID_CQ=0 )
		";
		$type = 1;
		if($is_di == 1)
			$type = 2;
		try{
			$stm = $dbAdapter->query($sql,array($year,$type,$id_cq));
			$re = $stm->fetchAll();
			return $re;
		}catch (Exception  $ex) {
			return array();
		}
	}
	static function getSoMotCua($year){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql ="
			select ID_SVB , NAME from `vb_sovanban` 
			where YEAR=? and TYPE=? and ACTIVE = 1 
		";
		$type = 3;
		
		try{
			$stm = $dbAdapter->query($sql,array($year,$type));
			$re = $stm->fetchAll();
			return $re;
		}catch (Exception  $ex) {
			return array();
		}
	}
	
	static function getSoMotCuaTheoLV($year,$id_lv_mc){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql ="
			select ID_SVB , NAME from `vb_sovanban` 
			where YEAR=? and TYPE=? and ID_LV_MC=? and ACTIVE = 1 
		";
		$type = 3;
		
		try{
			$stm = $dbAdapter->query($sql,array($year,$type,$id_lv_mc));
			$re = $stm->fetchAll();
			return $re;
		}catch (Exception  $ex) {
			return array();
		}
	}
	
	static function getSoMotCuaTheoLoaiHS($year,$id_loaihoso){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql ="
			select svb.ID_SVB , svb.NAME from `vb_sovanban` svb
			inner join motcua_loai_hoso loaihs on svb.ID_LV_MC = loaihs.ID_LV_MC
			where YEAR=? and TYPE=? and ID_LOAIHOSO=? and ACTIVE = 1 
		";
		$type = 3;
		
		//try{
			$stm = $dbAdapter->query($sql,array($year,$type,$id_loaihoso));
			$re = $stm->fetchAll();
			return $re;
		//}catch (Exception  $ex) {
			//return array();
		//}
	}
	static function getNamLV_MCById($id_lv_mc){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql ="
			select ID_LV_MC , NAME from `motcua_linhvuc` 
			where   ACTIVE = 1 and ID_LV_MC = ?
		";
		$type = 3;
		
		try{
			$stm = $dbAdapter->query($sql,array($id_lv_mc));
			$re = $stm->fetch();
			return $re["NAME"];
		}catch (Exception  $ex) {
			return "";
		}
	}
	
	
}

?>
