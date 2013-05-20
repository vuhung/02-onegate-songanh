<?php
	class htdb_danhmuc {
	
		function getAllByList(){
			$db = Zend_Db_Table::getDefaultAdapter();
			$sql = "
				SELECT * FROM HTDB_DANHMUC 
			";
			try{
				$query = $db->query($sql);
				return $query->fetchAll();
			}catch(Exception $ex){
				return array();
			}
			
		}

		function getDetail($id){
			$db = Zend_Db_Table::getDefaultAdapter();
			$sql = "
				select * from htdb_danhmuc where ID_DANHMUC = ?
			";
			$query = $db->query($sql,(int)$id);
			return $query->fetch();

		}

		function export2xml($code){
			$db = Zend_Db_Table::getDefaultAdapter();
			$sql = "
				select * from htdb_danhmuc where CODE = ?
			";
			$stm = $db->query($sql,array($code));
			//build sql
			$datadefine = $stm->fetch();
			$sqlbuilder = " SELECT ".$datadefine["FIELDLIST"]."  FROM ".$datadefine["TABLENAME"];
			//echo $sqlbuilder;exit;
			$stm = $db->query($sqlbuilder);
			$data = $stm->fetchAll();
			//var_dump($data);
			return XML_Utils::arrdatadbtoxml($data);
		}
	}