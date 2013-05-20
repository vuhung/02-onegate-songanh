<?php 
class baocaoluutruhosoreportModel{
	static function getReportData($fromdate,$todate,$id_pb,$id_lhs){	
		$user = Zend_Registry::get('auth')->getIdentity();
		$where_arr =  array();
		if($fromdate || $fromdate != ""){
		 $fromdate = implode("-",array_reverse(explode("/",$fromdate)))." 00:00:00";
		 $where_fromdate = "`NGAYLUUHOSO` >= '".$fromdate."'" ; 
		 array_push($where_arr,$where_fromdate);
		 
		}
		if($todate || $todate != ""){
		$todate = implode("-",array_reverse(explode("/",$todate)))." 23:59:59";
		$where_todate = "`NGAYLUUHOSO` <= '".$todate."'" ; array_push($where_arr,$where_todate);
		}
		if($id_lhs > 0)
		{
			$where_lhs = " lhs.ID_LOAIHOSO= '".$id_lhs."'" ; 
			array_push($where_arr,$where_lhs);
		}
		if($id_pb > 0)
		{
			$where_pb = " lhs.TENPHONGBAN= '".$id_pb."'" ; 
			array_push($where_arr,$where_pb);
		}
		$where ="";
		if(count($where_arr) > 0)
			$where = " where " . implode(' and ',$where_arr)." ";			
		$sql = "		 
    	select * from `qllt_hoso` hs
            inner join `qllt_noiluutru` nlt on hs.`ID_NOILUUTRU` = nlt.`ID_NOILUUTRU`
            inner join `qllt_loaihoso` lhs on hs.`ID_LOAIHOSO` = lhs.`ID_LOAIHOSO`
			$where
		";
		//echo $sql;exit;
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql);
		$re = $query->fetchAll();
		return $re;
	}

	static function getReportDatathongkehienthi($fromdate,$todate,$id_pb,$id_lhs,$trangthai){	
		$user = Zend_Registry::get('auth')->getIdentity();
		$where_arr =  array();
		if($fromdate || $fromdate != ""){
		 $fromdate = implode("-",array_reverse(explode("/",$fromdate)))." 00:00:00";
		 $where_fromdate = "`NGAYLUUHOSO` >= '".$fromdate."'" ; 
		 array_push($where_arr,$where_fromdate);
		 
		}
		if($todate || $todate != ""){
		$todate = implode("-",array_reverse(explode("/",$todate)))." 23:59:59";
		$where_todate = "`NGAYLUUHOSO` <= '".$todate."'" ; array_push($where_arr,$where_todate);
		}
		if($id_lhs > 0)
		{
			$where_lhs = " lhs.ID_LOAIHOSO= '".$id_lhs."'" ; 
			array_push($where_arr,$where_lhs);
		}
		if($id_pb > 0)
		{
			$where_pb = " lhs.TENPHONGBAN= '".$id_pb."'" ; 
			array_push($where_arr,$where_pb);
		}
		$where ="";
		if(count($where_arr) > 0)
			$where = " where " . implode(' and ',$where_arr)." ";		
		if($trangthai ==2){ 
		$sql = "		 
    	select * from `qllt_hoso` hs
            inner join `qllt_noiluutru` nlt on hs.`ID_NOILUUTRU` = nlt.`ID_NOILUUTRU`
            inner join `qllt_loaihoso` lhs on hs.`ID_LOAIHOSO` = lhs.`ID_LOAIHOSO` and hs.`IS_MUONTRA` !=1
			$where
		";
		}elseif($trangthai ==3){ 
		$sql = "		 
    	select * from `qllt_hoso` hs
            inner join `qllt_noiluutru` nlt on hs.`ID_NOILUUTRU` = nlt.`ID_NOILUUTRU`
            inner join `qllt_loaihoso` lhs on hs.`ID_LOAIHOSO` = lhs.`ID_LOAIHOSO` and hs.`IS_MUONTRA` =1
			$where
		";
		}else{
		$sql = "		 
    	select * from `qllt_hoso` hs
            inner join `qllt_noiluutru` nlt on hs.`ID_NOILUUTRU` = nlt.`ID_NOILUUTRU`
            inner join `qllt_loaihoso` lhs on hs.`ID_LOAIHOSO` = lhs.`ID_LOAIHOSO` 
			$where
		";
		}
	   // echo $sql;exit;
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql);
		$re = $query->fetchAll();
		return $re;
	}



static function getNguoiXulyFromVban($id_hscv){
	
	$sql = "
		select   *    from ( select  ID_HSCV , ID_PI from `".QLVBDHCommon::Table("hscv_hosocongviec")."` hscv1 ) hscv
		inner join " . QLVBDHCommon::Table("wf_processlogs") . " wfl on hscv.ID_PI = wfl.ID_PI 
		inner join
		(
			select u.`ID_U` , concat(emp.`FIRSTNAME`, ' ',emp.`LASTNAME`) AS NAME_U , de.`ID_DEP` , de.`NAME` as NAME_DEP from
			`qtht_users` u inner join `qtht_employees` emp on emp.`ID_EMP` = u.`ID_EMP`
			inner join `qtht_departments` de on de.`ID_DEP` = emp.`ID_DEP`

		)rs2
		on rs2.`ID_U` = wfl.ID_U_RECEIVE 
		where hscv.ID_HSCV =?
		";

	$name_u = "";
	$dbAdapter = Zend_Db_Table::getDefaultAdapter();
	$query = $dbAdapter->query($sql,array($id_hscv));
	$re = $query->fetchAll();
	$stt = 1;
	foreach ($re as $row){
		if($stt == 2){
			$name_u = $row["NAME_U"]."<br/>";
			break;	
		}
		$stt ++;
	}
	return $name_u;
	
}

	static function getHscvsByIdVbd($id_vbd){
		$sql = " select hs.ID_HSCV from `".QLVBDHCommon::Table("vbd_vanbanden")."` vbd
			inner join `".QLVBDHCommon::Table("vbd_fk_vbden_hscvs")."` fk_vbd_hs on fk_vbd_hs.ID_VBDEN = vbd.ID_VBD
			inner join `".QLVBDHCommon::Table("hscv_hosocongviec")."` hs on hs.`ID_HSCV` = fk_vbd_hs.`ID_HSCV`
			where ID_VBD = ?
			";
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql,array($id_vbd));
		$re = $query->fetchAll();
		return $re;
	}


	function counttkehslt($fromdate,$todate,$sel_pb,$sel_lhs){	
		$user = Zend_Registry::get('auth')->getIdentity();
		$where_arr =  array();
		if($fromdate || $fromdate != ""){
		 $fromdate = implode("-",array_reverse(explode("/",$fromdate)))." 00:00:00";
		 $where_fromdate = "`NGAYLUUHOSO` >= '".$fromdate."'" ; 
		 array_push($where_arr,$where_fromdate);
		 
		}
		if($todate || $todate != ""){
		$todate = implode("-",array_reverse(explode("/",$todate)))." 23:59:59";
		$where_todate = "`NGAYLUUHOSO` <= '".$todate."'" ; array_push($where_arr,$where_todate);
		}

       if($sel_lhs > 0)
		{
			$where_lhs = " lhs.ID_LOAIHOSO= '".$sel_lhs."'" ; 
			array_push($where_arr,$where_lhs);
		}
		if($sel_pb > 0)
		{
			$where_pb = " lhs.TENPHONGBAN= '".$sel_pb."'" ; 
			array_push($where_arr,$where_pb);
		}
		$where ="";
		if(count($where_arr) > 0)
			$where = " where " . implode(' and ',$where_arr)." ";		



		$dbAdapter = Zend_Db_Table::getDefaultAdapter();	
		$sql="	select count(hs.`ID_HOSO`) as TONG
					from `qllt_hoso` hs
						 inner join `qllt_loaihoso` lhs on hs.`ID_LOAIHOSO` = lhs.`ID_LOAIHOSO`
					$where													
			";	
			//echo $sql;
		$query = $dbAdapter->query($sql);
		$re = $query->fetch();		
		return $re['TONG'];
	}


   function counttkehsltconlai($fromdate,$todate,$sel_pb,$sel_lhs){	
		$user = Zend_Registry::get('auth')->getIdentity();
		$where_arr =  array();
		if($fromdate || $fromdate != ""){
		 $fromdate = implode("-",array_reverse(explode("/",$fromdate)))." 00:00:00";
		 $where_fromdate = "`NGAYLUUHOSO` >= '".$fromdate."'" ; 
		 array_push($where_arr,$where_fromdate);
		 
		}
		if($todate || $todate != ""){
		$todate = implode("-",array_reverse(explode("/",$todate)))." 23:59:59";
		$where_todate = "`NGAYLUUHOSO` <= '".$todate."'" ; array_push($where_arr,$where_todate);
		}

       if($sel_lhs > 0)
		{
			$where_lhs = " lhs.ID_LOAIHOSO= '".$sel_lhs."'" ; 
			array_push($where_arr,$where_lhs);
		}
		if($sel_pb > 0)
		{
			$where_pb = " lhs.TENPHONGBAN= '".$sel_pb."'" ; 
			array_push($where_arr,$where_pb);
		}
		$where ="";
		if(count($where_arr) > 0)
			$where = " where " . implode(' and ',$where_arr)." ";		



		$dbAdapter = Zend_Db_Table::getDefaultAdapter();	
		$sql="	select count(hs.`ID_HOSO`) as TONG
					from `qllt_hoso` hs
						 inner join `qllt_loaihoso` lhs on hs.`ID_LOAIHOSO` = lhs.`ID_LOAIHOSO` and hs.`IS_MUONTRA` !=1
					$where
												
			";
			//echo $sql;
		$query = $dbAdapter->query($sql);
		$re = $query->fetch();		
		return $re['TONG'];
	}

    function counttkehsltchomuon($fromdate,$todate,$sel_pb,$sel_lhs){	
		$user = Zend_Registry::get('auth')->getIdentity();
		$where_arr =  array();
		if($fromdate || $fromdate != ""){
		 $fromdate = implode("-",array_reverse(explode("/",$fromdate)))." 00:00:00";
		 $where_fromdate = "`NGAYLUUHOSO` >= '".$fromdate."'" ; 
		 array_push($where_arr,$where_fromdate);
		 
		}
		if($todate || $todate != ""){
		$todate = implode("-",array_reverse(explode("/",$todate)))." 23:59:59";
		$where_todate = "`NGAYLUUHOSO` <= '".$todate."'" ; array_push($where_arr,$where_todate);
		}

       if($sel_lhs > 0)
		{
			$where_lhs = " lhs.ID_LOAIHOSO= '".$sel_lhs."'" ; 
			array_push($where_arr,$where_lhs);
		}
		if($sel_pb > 0)
		{
			$where_pb = " lhs.TENPHONGBAN= '".$sel_pb."'" ; 
			array_push($where_arr,$where_pb);
		}
		$where ="";
		if(count($where_arr) > 0)
			$where = " where " . implode(' and ',$where_arr)." ";		



		$dbAdapter = Zend_Db_Table::getDefaultAdapter();	
		$sql="	select count(hs.`ID_HOSO`) as TONG
					from `qllt_hoso` hs
						 inner join `qllt_loaihoso` lhs on hs.`ID_LOAIHOSO` = lhs.`ID_LOAIHOSO` and hs.`IS_MUONTRA`=1
					$where												
			";
			//echo $sql;
		$query = $dbAdapter->query($sql);
		$re = $query->fetch();		
		return $re['TONG'];
	}

}
?>