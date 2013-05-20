<?php 
class vanbandireportModel{
	static function getReportData($fromdate,$todate,$id_svb){
	    $where_arr =  array();
		if($fromdate || $fromdate != ""){
		 $fromdate = implode("-",array_reverse(explode("/",$fromdate."/".QLVBDHCommon::getYear())))." 00:00:00";
		 $where_fromdate = "`NGAYBANHANH` >= '".$fromdate."'" ; 
		 array_push($where_arr,$where_fromdate);
		 
		}
		if($todate || $todate != ""){
		$todate = implode("-",array_reverse(explode("/",$todate."/".QLVBDHCommon::getYear())))." 23:59:59";
		$where_todate = "`NGAYBANHANH` <= '".$todate."'" ; array_push($where_arr,$where_todate);
		}
		if($id_svb > 0)
		{
			$where_svb = "`ID_SVB`= '".$id_svb."'" ; 
			array_push($where_arr,$where_svb);
		}
		
		$where ="";
		if(count($where_arr) > 0)
			$where = " where " . implode(' and ',$where_arr)." ";
		
		$sql = "
		Select * from `".QLVBDHCommon::Table("vbdi_vanbandi")."`"
		.$where.
		"order by `SODI_IN` 
		";
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql);
		$re = $query->fetchAll();
		return $re;
	}
}
?>