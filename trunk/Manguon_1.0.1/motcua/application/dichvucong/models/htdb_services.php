<?php

	class htdb_services extends Zend_Db_Table_Abstract {
		//var $_name = 'htdb_services';
		
		function __construct(){
			$this->_name = "htdb_services";
			parent::__construct(array());
		}
		
		function selectById($id){
			$sql = "
				select * from  htdb_services 
				where ID_SERVICE = ?
			";
			$db = Zend_Db_Table::getDefaultAdapter();
			$qr = $db->query($sql,(int)$id);
			return $qr->fetch();

		}

		function selectAll(){
			$sql = "
				select * from htdb_services
			";
			$db = Zend_Db_Table::getDefaultAdapter();
			$qr = $db->query($sql,(int)$id);
			return $qr->fetchAll();
		}

		function selectAllForList(){
			$sql = "
				select ser.ID_SERVICE , ser.TENDICHVU, ser.CODE , ser.ID_LOAIHOSO, loai.TENLOAI
				
				from htdb_services ser
				inner join motcua_loai_hoso loai on ser.ID_LOAIHOSO = loai.ID_LOAIHOSO
			";
			$db = Zend_Db_Table::getDefaultAdapter();
			$qr = $db->query($sql,(int)$id);
			return $qr->fetchAll();
		}
		

		
	}
