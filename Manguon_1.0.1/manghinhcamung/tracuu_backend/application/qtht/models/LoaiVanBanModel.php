<?php

require_once 'Zend/Db/Table.php';
class LoaiVanBanModel extends Zend_Db_Table {
	
	var $_name = "vb_loaivanban";
	function findByMixed($page,$limit,$name,$active){
		$name='%'.$name.'%';
		$arr = array($name,$active);
		$wherename = 'NAME LIKE ?';
		$whereactive = 'ACTIVE = ?';
		$select = $this->select();
		$select->where($wherename,$name);
		if($page !=0 && $limit !=0){
			$select->limitPage($page,$limit);	
		}
		if($active>=2||$active<0||is_null($active)){
			
		}else{
			$select->where($whereactive,$active);
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
	
	function count($name,$active){
		
		$name='%'.$name.'%';
		$arr = array($name,$active);
		$wherename = 'NAME LIKE ?';
		$whereactive = 'ACTIVE = ?';
		$select = $this->select(); 
        $select->from($this->_name,'COUNT(*) AS num'); 
		$select->where($wherename,$name);
        if($active>=2||$active<0||is_null($active)){
			
		}
		else{
			$select->where($whereactive,$active);
		}
        $row=$this->fetchRow($select); 
        return $row->num; 
	}
	  
	
	
	static function toComboFilter($filter_object){
		if($filter_object>=2||$filter_object<0||is_null($filter_object) )
			echo '<option value=2  selected>Chọn tất cả</option>';
		else 
			echo '<option value=2 >Chọn tất cả</option>';
		
		if($filter_object==1)
			echo '<option value=1 selected>Đang được sử dụng</option>';
		else 
			echo '<option value=1>Đang được sử dụng</option>';
		
		if($filter_object==0 && !is_null($filter_object))
			echo '<option value=0 selected>Chưa được sử dụng</option>';
		else
			echo '<option value=0>Chưa được sử dụng</option>';
	
	}
	/**
     * Get all items in VB_LOAIVANBAN table with pairs : ID_LVB and NAME
     * @return array
     */
	function GetAllLoaiVanBans()
	{
		$r = $this->getDefaultAdapter()->query("
			SELECT
     			ID_LVB,NAME
			FROM
				VB_LOAIVB");
		return $r->fetchAll();
	}
	
	static function toComboName(){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "select `CODE`,`NAME`,`ID_LVB` from `vb_loaivanban`";
		$query = $dbAdapter->query($sql);
		$re = $query->fetchAll();
		foreach ($re as $row){
			echo "<option id='".$row['CODE']."' value=".$row['ID_LVB'].">".$row['NAME']."</option>";
		}
	}
	
	static function getNameById($id){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "select `NAME` from `vb_loaivanban` 
		where `ID_LVB` = ?
		";
		$query = $dbAdapter->query($sql,array($id));
		$re = $query->fetch();
		return $re["NAME"];
	}
	
}


