<?php
class HosochuatiepnhanModel{
	function getReportData($fromdate,$todate,$sel_tinhtrang,$sel_lhss){
		$where_arr =  array();
		if($fromdate || $fromdate != ""){
		 $fromdate = implode("-",array_reverse(explode("/",$fromdate."/".QLVBDHCommon::getYear())))." 00:00:00";
		 $where_fromdate = "`GUI_LUC` >= '".$fromdate."'" ; 
		 array_push($where_arr,$where_fromdate);
		 
		}
		if($todate || $todate != ""){
		$todate = implode("-",array_reverse(explode("/",$todate."/".QLVBDHCommon::getYear())))." 23:59:59";
		$where_todate = "`GUI_LUC` <= '".$todate."'" ; array_push($where_arr,$where_todate);
		}
		if($sel_tinhtrang)
		{	if ($sel_tinhtrang==0) { $where_trangthai = "(`IS_TIEPNHAN` is null or IS_TIEPNHAN = 0)";}
			else if ($sel_tinhtrang==1) {$where_trangthai = "`IS_TIEPNHAN`= 1";}
			else if ($sel_tinhtrang==2) {$where_trangthai = "(`IS_TIEPNHAN` = 2 or IS_TIEPNHAN = 3)";}
			array_push($where_arr,$where_trangthai);
		}		
		if(count($sel_lhss) > 0)
		{
			if(array_search("0",$sel_lhss) == FALSE && $sel_lhss[0] != "0"){
			$where_lhs = "loai.ID_LOAIHOSO in (".implode(',',$sel_lhss).")" ;			
			array_push($where_arr,$where_lhs);
			}	
			
		}
		$where ="";
		if(count($where_arr) > 0)
			$where = " where " . implode(' and ',$where_arr)."";
		
		$sql = "
		SELECT mc.*
		from `dvc_motcua_hoso_web` mc 
		left join MOTCUA_LOAI_HOSO loai on mc.MALOAIHOSO = loai.CODE 
		".$where." ORDER BY GUI_LUC DESC
		";		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql);
		$re = $query->fetchAll();
		return $re;
	}
	

	function getReportDataTiepnhantrongngay($sel_lv,$date){
		
		$where_arr =  array();
		
		if(!$date){
			$fromdate = date("Y-m-d 00:00:00");
			$todate = date("Y-m-d 23:59:59");
			
		}else{
			$fromdate = implode("-",array_reverse(explode("/",$date."/".QLVBDHCommon::getYear())))." 00:00:00";
			$todate = implode("-",array_reverse(explode("/",$date."/".QLVBDHCommon::getYear())))." 23:59:59";
			
		}
		
		if($fromdate || $fromdate != ""){
		 //$fromdate = implode("-",array_reverse(explode("/",$fromdate."/".QLVBDHCommon::getYear())))." 00:00:00";
		 $where_fromdate = "`NGAYNHAN` >= '".$fromdate."'" ; 
		 array_push($where_arr,$where_fromdate);
		 
		}
		if($todate || $todate != ""){
		//$todate = implode("-",array_reverse(explode("/",$todate."/".QLVBDHCommon::getYear())))." 23:59:59";
		$where_todate = "`NGAYNHAN` <= '".$todate."'" ; array_push($where_arr,$where_todate);
		}
		
		$where_lv = "";
		$param_lv = array();
		if($sel_lv > 0)
		{
			$where_linhvuc = "loaihs.ID_LV_MC = ?" ; 
			array_push($where_arr,$where_linhvuc);
			$param_lv[] = $sel_lv;
		}
		
		$where ="";
		if(count($where_arr) > 0)
			$where = " where " . implode(' and ',$where_arr)."";
		
		$sql = "
		SELECT motcua.*,phuong.TENPHUONG,loaihs.TENLOAI
		from `".QLVBDHCommon::Table("motcua_hoso")."` motcua 
		left join motcua_phuong phuong on motcua.PHUONG = phuong.ID_PHUONG
		left join motcua_loai_hoso loaihs on loaihs.ID_LOAIHOSO = motcua.ID_LOAIHOSO
		
		".$where." 
		
		";
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql,$param_lv);
		$re = $query->fetchAll();
		return $re;
	}

	function getReportDataTratrongngay($sel_lv,$fromdate){
		
		$where_arr =  array();

		if(!$fromdate){
			$fromdate = date("Y-m-d ");
			
		}else{
			$fromdate = implode("-",array_reverse(explode("/",$fromdate."/".QLVBDHCommon::getYear())))." ";
			
		}
		
		if($fromdate || $fromdate != ""){
		 //$fromdate = implode("-",array_reverse(explode("/",$fromdate."/".QLVBDHCommon::getYear())))." 00:00:00";
		 $where_fromdate = "`TRA_NGAY` = '".$fromdate."'" ; 
		 array_push($where_arr,$where_fromdate);
		 
		}
		$where_lv = "";
		$param_lv = array();
		if($sel_lv > 0)
		{
			$where_linhvuc = "loaihs.ID_LV_MC = ?" ; 
			array_push($where_arr,$where_linhvuc);
			$param_lv[] = $sel_lv;
		}
		$where ="";
		if(count($where_arr) > 0)
			$where = " where " . implode(' and ',$where_arr)."";
		
		$sql = "
		SELECT motcua.*,phuong.TENPHUONG,loaihs.TENLOAI,ketqua.SOKYHIEU as VB_TL, ketqua.NGAYKY
		from `".QLVBDHCommon::Table("motcua_hoso")."` motcua 
		left join motcua_phuong phuong on motcua.PHUONG = phuong.ID_PHUONG
		left join motcua_loai_hoso loaihs on loaihs.ID_LOAIHOSO = motcua.ID_LOAIHOSO
		left join ".QLVBDHCommon::Table("motcua_ketqua")." ketqua on motcua.ID_HSCV = ketqua.ID_HSCV 
		".$where." 
		GROUP BY ID_HOSO
		ORDER BY SO DESC
		";
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql,$param_lv);
		$re = $query->fetchAll();
		return $re;
	}


	
	static function getLoaiHs(){
		$sql = " select * from motcua_loai_hoso  ";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql);
			return $qr->fetchAll();
		}catch(Exception $ex){
			return array();
		}
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
		$re = HosochuatiepnhanModel::getMotcuaUser();
		foreach ($re as $row){
			echo "<option id='comboMCUser".$row['ID_U']."' value=".$row['ID_U'].">".$row['FULLNAME']."</option>";
		}
	}
	
	static function getTenLoai($idloai){
		if($idloai >0){
			$sql = " select TENLOAI from motcua_loai_hoso where ID_LOAIHOSO=?";
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($idloai));
			$re = $qr->fetch();
			return $re["TENLOAI"];
		}else{
			return "";
		}
	}
	
	static function getIDLOAIByLV($id_lv){
		if($id_lv >0){
			$sql = " select ID_LOAIHOSO from motcua_loai_hoso where ID_LV_MC=?";
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($id_lv));
			$re = $qr->fetchAll();
			$arr = array();
			foreach($re as $it_re){
				$arr[] = $it_re[ID_LOAIHOSO];
			}
			return $arr;
		}else if($id_lv==-1){
			$sql = " select ID_LOAIHOSO from motcua_loai_hoso";
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql);
			$re = $qr->fetchAll();
			$arr = array();
			foreach($re as $it_re){
				$arr[] = $it_re[ID_LOAIHOSO];
			}
			return $arr;
		}else{
			return array();
		}
	}

	static function reportHoso($trangthai,$loai, $fromdate, $todate){		
		if($fromdate == "")
			$fromdate = "1/1";
		
		if($todate == "")
			$todate = "31/12";
		$fromdate = $fromdate."/".QLVBDHCommon::getYear();
		$fromdate = implode("-",array_reverse(explode("/",$fromdate)))." 00:00:00";
		$todate = $todate."/".QLVBDHCommon::getYear();
		$todate = implode("-",array_reverse(explode("/",$todate)))." 23:59:59";		

		switch($trangthai){
			
			//chưa tiếp nhận
			case "0":
				$sql = "
				select  mc.* from
				dvc_motcua_hoso_web mc 
				inner join MOTCUA_LOAI_HOSO loai on mc.MALOAIHOSO = loai.CODE
				WHERE 
				GUI_LUC >= '".$fromdate."' AND GUI_LUC <= '".$todate."' 
				AND (mc.IS_TIEPNHAN = 0 or mc.IS_TIEPNHAN is null ) 
				AND loai.ID_LOAIHOSO = ?				
				";
				break;
			//Đã tiếp nhận
			case "1":
				$sql = "
				select  mc.* from
				dvc_motcua_hoso_web mc 
				inner join MOTCUA_LOAI_HOSO loai on mc.MALOAIHOSO = loai.CODE				
				WHERE 
				GUI_LUC >= '".$fromdate."' AND GUI_LUC <= '".$todate."' 
				AND mc.IS_TIEPNHAN = 1
				AND loai.ID_LOAIHOSO = ?				
				";
				break;
			//không tiếp nhận ( bao gồm không hợp lệ và không tiếp nhận)
			case "2":
				$sql = "
				select  mc.* from
				dvc_motcua_hoso_web mc 
				inner join MOTCUA_LOAI_HOSO loai on mc.MALOAIHOSO = loai.CODE				
				WHERE 
				GUI_LUC >= '".$fromdate."' AND GUI_LUC <= '".$todate."' 
				AND (mc.IS_TIEPNHAN = 2 or mc.IS_TIEPNHAN = 3 ) 
				AND loai.ID_LOAIHOSO = ?				
				";
				break;
			default:
				return array();
				break;
		}

		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql,array($loai));
		$re = $query->fetchAll();		
		return $re;
	}
	static function CountHoso($trangthai,$loai,$phuong, $fromdate, $todate, $fromdategt, $todategt){
		switch($trangthai){
			//da tiếp nhận
			case "1":
				$sql = "
				select  count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND mc.PHUONG = ?
				";
				break;
			//chưa giao trả
			case "2":
				$sql = "
				select  count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND mc.PHUONG = ? AND mc.TRA_NGAY is null AND mc.PCMTRA_NGAY is null";
				break;
			//sắp trễ hạn
			case "3":
				$sql = "
				select count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND mc.PHUONG = ? AND mc.TRA_NGAY is null AND mc.PCMTRA_NGAY is null AND
				mc.NHANLAI_NGAY >= '".date("Y-m-d")."' AND  mc.NHANLAI_NGAY  - INTERVAL loai.SAPTRE DAY <= '".date("Y-m-d")."'";
				break;			
			default:
				return 0;
				break;
		}
		//echo $sql;
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql,array($loai,$phuong));
		$re = $query->fetch();
		return $re["CNT"];
	}







	static function CountHosoByLinhVuc($trangthai,$linhvuc,$fromdate, $todate){
		switch($trangthai){
			//da tiếp nhận
			case "1":
				$sql = "
				select  loai.ISUBND, count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				loai.ID_LV_MC = ?
				GROUP BY loai.ISUBND
				";
				break;
			//chưa giao trả
			case "2":
				$sql = "
				select  loai.ISUBND,count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				loai.ID_LV_MC = ? AND mc.TRA_NGAY is null AND mc.PCMTRA_NGAY is null
				GROUP BY loai.ISUBND
				";
				break;
			//sắp trễ hạn
			case "3":
				$sql = "
				select loai.ISUBND,count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				loai.ID_LV_MC = ? AND mc.TRA_NGAY is null AND mc.PCMTRA_NGAY is null AND
				mc.NHANLAI_NGAY >= '".date("Y-m-d")."' AND  mc.NHANLAI_NGAY  - INTERVAL loai.SAPTRE DAY <= '".date("Y-m-d")."'
				GROUP BY loai.ISUBND
				";
				break;
			//trễ
			case "4":
				$sql = "
				select  loai.ISUBND,count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				loai.ID_LV_MC = ?
				AND mc.TRA_NGAY is null
				AND mc.NHANLAI_NGAY < '".date("Y-m-d")."'
				AND mc.PCMTRA_NGAY is null
				GROUP BY loai.ISUBND
				";
				break;
			case "5":
				$sql = "
				select  loai.ISUBND, count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				loai.ID_LV_MC = ?
				AND (not mc.TRA_NGAY is null)
				GROUP BY loai.ISUBND
				";
				break;
			case "6":
				$sql = "
				select  loai.ISUBND, count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				loai.ID_LV_MC = ?
				AND mc.TRA_NGAY is null
				GROUP BY loai.ISUBND
				";
				break;
			case "7":
				$sql = "
				select  loai.ISUBND, count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				loai.ID_LV_MC = ?
				AND mc.PCMTRA_NGAY is not null AND mc.TRA_NGAY is null 
				GROUP BY loai.ISUBND
				";
				break;
			case "8":
				$sql = "
				select  loai.ISUBND,count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				loai.ID_LV_MC = ?
				AND	mc.PCMTRA_NGAY > mc.NHANLAI_NGAY
				AND mc.PCMTRA_NGAY is not null
				GROUP BY loai.ISUBND
				";
				break;
			//đã rút
			case "9":
				$sql = "
				select  loai.ISUBND,count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				loai.ID_LV_MC = ? AND mc.PCMTRA_NGAY <= mc.NHANLAI_NGAY
				AND mc.PCMTRA_NGAY is not null
				GROUP BY loai.ISUBND
				";
				break;
			//đã giao trả
			case "10":
				$sql = "
				select  loai.ISUBND,count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				loai.ID_LV_MC = ? AND KHONGXULY=1
				GROUP BY loai.ISUBND
				";
				break;
			default:
				return 0;
				break;
		}
		//echo $sql;
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		//echo $sql.$linhvuc;
		$query = $dbAdapter->query($sql,array($linhvuc));
		$re = $query->fetchAll();
		$cntall = 0;
		$cntunbd = 0;
		$cntphong = 0;
		foreach($re as $item){
			if($item['ISUBND']==1){
				$cntubnd += $item['CNT'];
			}else{
				$cntphong += $item['CNT'];
			}
			$cntall += $item['CNT'];
		}
		return array($cntall,$cntubnd,$cntphong);
	}
}
?>