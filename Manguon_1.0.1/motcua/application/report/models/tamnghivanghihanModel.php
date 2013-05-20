<?php 
class tamnghivanghihanModel{
	static function getReportDataUB($fromdate,$todate){
	
	    $where_arr =  array();
		if($fromdate || $fromdate != ""){
		 $fromdate = implode("-",array_reverse(explode("/",$fromdate."/".QLVBDHCommon::getYear())))." 00:00:00";
		 $where_fromdate = "("."`TAMNGHI_BATDAU` >= '".$fromdate."'"."||". "`NGAYNGHIHAN` >= '".$fromdate."'".")"; 
		 array_push($where_arr,$where_fromdate);
		 
		}
		if($todate || $todate != ""){
		$todate = implode("-",array_reverse(explode("/",$todate."/".QLVBDHCommon::getYear())))." 23:59:59";
		$where_todate = "("."`TAMNGHI_BATDAU` <= '".$todate."'"."||". "`NGAYNGHIHAN` <= '".$todate."'".")" ;
		 array_push($where_arr,$where_todate);
		}		
		
		$where ="";
		if(count($where_arr) > 0)
			$where = " where " . implode(' and ',$where_arr)."and ID_LOAIHOSO in (select ID_LOAIHOSO from motcua_loai_hoso where CODE= 24 || CODE =25 )";
		
		$sql = "
		Select * from `".QLVBDHCommon::Table("motcua_hoso")."`"
		.$where.
		" 
		";
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		//echo $sql;exit;
		$query = $dbAdapter->query($sql);
		$re = $query->fetchAll();
		return $re;
	}
static function getReportDataTCKH($fromdate,$todate){
	
	    $where_arr =  array();
		if($fromdate || $fromdate != ""){
		 $fromdate = implode("-",array_reverse(explode("/",$fromdate."/".QLVBDHCommon::getYear())))." 00:00:00";
		 $where_fromdate = "("."`TAMNGHI_BATDAU` >= '".$fromdate."'"."||". "`NGAYNGHIHAN` >= '".$fromdate."'".")"; 
		 array_push($where_arr,$where_fromdate);
		 
		}
		if($todate || $todate != ""){
		$todate = implode("-",array_reverse(explode("/",$todate."/".QLVBDHCommon::getYear())))." 23:59:59";
		$where_todate = "("."`TAMNGHI_BATDAU` <= '".$todate."'"."||". "`NGAYNGHIHAN` <= '".$todate."'".")" ;
		 array_push($where_arr,$where_todate);
		}		
		
		$where ="";
		if(count($where_arr) > 0)
			$where = " where " . implode(' and ',$where_arr)."and ID_LOAIHOSO in (select ID_LOAIHOSO from motcua_loai_hoso where CODE= 29 || CODE =30 )";
		
		$sql = "
		Select * from `".QLVBDHCommon::Table("motcua_hoso")."`"
		.$where.
		" 
		";
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		//echo $sql;exit;
		$query = $dbAdapter->query($sql);
		$re = $query->fetchAll();
		return $re;
	}
	 static function getSKH($id_hscv){
	 $sql="Select * from `".QLVBDHCommon::Table("motcua_ketqua")."` where ID_HSCV=?";
	 $dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql,$id_hscv);
		$re = $query->fetchAll();		
		return $re;		
 }     
}
?>