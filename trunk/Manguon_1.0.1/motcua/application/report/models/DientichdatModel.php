<?php
class DientichdatModel{
	function getReportData($fromdate,$todate,$sel_tinhtrang,$sel_lhss){
		
		$where_arr =  array();
		if($fromdate || $fromdate != ""){
		 $fromdate = implode("-",array_reverse(explode("/",$fromdate."/".QLVBDHCommon::getYear())))." 00:00:00";
		 $where_fromdate = "`NGAYNHAN` >= '".$fromdate."'" ; 
		 array_push($where_arr,$where_fromdate);
		 
		}
		if($todate || $todate != ""){
		$todate = implode("-",array_reverse(explode("/",$todate."/".QLVBDHCommon::getYear())))." 23:59:59";
		$where_todate = "`NGAYNHAN` <= '".$todate."'" ; array_push($where_arr,$where_todate);
		}
		if($sel_tinhtrang > 0)
		{
			$where_trangthai = "`TRANGTHAI`= '".$sel_tinhtrang."'" ; 
			array_push($where_arr,$where_trangthai);
		}
		if(count($sel_lhss) > 0)
		{
			if(array_search("0",$sel_lhss) == FALSE && $sel_lhss[0] != "0"){
			$where_lhs = "`ID_LOAIHOSO` in (".implode(',',$sel_lhss).")" ; 
			array_push($where_arr,$where_lhs);
			}	
			
		}
		$where ="";
		if(count($where_arr) > 0)
			$where = " where " . implode(' and ',$where_arr)."";
		
		$sql = "
		SELECT motcua.`ID_HOSO` , motcua.`TENTOCHUCCANHAN`,motcua.`NGUOITAO`,motcua.`DIACHI`
		,motcua.`NGAYNHAN` , motcua.`TRANGTHAI`,motcua.`TRICHYEU`,motcua.`NHAN_NGAY`,motcua.`NHANLAI_NGAY`,motcua.`PCMTRA_NGAY`,motcua.`TRA_NGAY`,motcua.`SO`
		from `".QLVBDHCommon::Table("motcua_hoso")."` motcua 
		".$where." ORDER BY SO ASC
		";
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql);
		$re = $query->fetchAll();
		return $re;
	}
	
	static function getMotcuaUser(){
		//code cua mot cua la NMC
		$sql = "
		select  g_u.ID_U , CONCAT(emp.FIRSTNAME,emp.LASTNAME) as FULLNAME 
		from
		(SELECT fk_u_g.ID_U
		from `qtht_groups` gr  
		inner join `fk_users_groups` fk_u_g  on gr.ID_G = fk_u_g.ID_G
		where gr.CODE='NMC'
		) g_u
		inner join qtht_users u  on u.ID_U = g_u.ID_U
		inner join qtht_employees emp on emp.ID_EMP = u.ID_EMP
		";
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql);
		$re = $query->fetchAll();
		return $re;
	}
	
	static function toComboUserMotCua(){
		$re = TiepnhanhosomotcuaModel::getMotcuaUser();
		foreach ($re as $row){
			echo "<option id='comboMCUser".$row['ID_U']."' value=".$row['ID_U'].">".$row['FULLNAME']."</option>";
		}
	}

	static function CountDientich ($loai,$phuong, $fromdate, $todate)
	
	{
						
	
				$sql="select SUM(DIENTICHTHUADAT)as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				WHERE 
				mc.ID_LOAIHOSO = ? and mc.PHUONG=?";
				
				$dbAdapter = Zend_Db_Table::getDefaultAdapter();
				$query = $dbAdapter->query($sql,array($loai,$phuong));
				$re = $query->fetch();
				return $re["CNT"];
				
	}

}
?>