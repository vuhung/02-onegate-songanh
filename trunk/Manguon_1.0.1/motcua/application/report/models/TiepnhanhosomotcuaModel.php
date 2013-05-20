<?php
class TiepnhanhosomotcuaModel{
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
		SELECT motcua.*
		from `".QLVBDHCommon::Table("motcua_hoso")."` motcua 
		".$where." ORDER BY SO ASC
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
		$re = TiepnhanhosomotcuaModel::getMotcuaUser();
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

	static function reportHoso($trangthai,$loai, $fromdate, $todate, $fromdategt, $todategt, $ubndorphong="0,1",$phuong=-1,$phongban,$chkLienThong){
		$joinpb = "";
		if(is_array($phongban)){
		$joinpb = "inner join (
					SELECT hscv_temp.ID_HSCV FROM
					".QLVBDHCommon::table("HSCV_HOSOCONGVIEC")." hscv_temp
					INNER JOIN ".QLVBDHCommon::table("WF_PROCESSITEMS")." pr on hscv_temp.ID_HSCV = pr.ID_O
					INNER JOIN ".QLVBDHCommon::table("WF_PROCESSLOGS")." pl on pl.ID_PI = pr.ID_PI
					INNER JOIN QTHT_USERS u on u.ID_U=pl.ID_U_RECEIVE
					INNER JOIN QTHT_EMPLOYEES emp on emp.ID_EMP = u.ID_EMP
					INNER JOIN QTHT_DEPARTMENTS dep on dep.ID_DEP = emp.ID_DEP and dep.ID_DEP in (".implode(",",$phongban).")
					WHERE hscv_temp.IS_THEODOI<>1
					GROUP BY hscv_temp.ID_HSCV
				) hscv on mc.ID_HSCV=hscv.ID_HSCV";
		}
		if($fromdate == "")
			$fromdate = "1/1";
		
		if($todate == "")
			$todate = "31/12";
		$fromdate = $fromdate."/".QLVBDHCommon::getYear();
		$fromdate = implode("-",array_reverse(explode("/",$fromdate)))." 00:00:00";
		$todate = $todate."/".QLVBDHCommon::getYear();
		$todate = implode("-",array_reverse(explode("/",$todate)))." 23:59:59";
		if($fromdategt == "")
			$fromdategt = "1/1";
		if(strlen($fromdategt)<=5){
		$fromdategt = $fromdategt."/".QLVBDHCommon::getYear();
		}
		
		if($todategt == "")
			$todategt = "31/12";
		if(strlen($todategt) <=5){
		$todategt = $todategt."/".QLVBDHCommon::getYear();
		}

		//$fromdategt = $fromdategt."/".QLVBDHCommon::getYear();
		$fromdategt = implode("-",array_reverse(explode("/",$fromdategt)))." 00:00:00";
		//$todategt = $todategt."/".QLVBDHCommon::getYear();
		$todategt = implode("-",array_reverse(explode("/",$todategt)))." 23:59:59";

		switch($trangthai){
			
			//da tiếp nhận
			case "1":
				$sql = "
				select  mc.*, phuong.TENPHUONG from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc 
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? 
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				";
				break;
			//chưa giao trả
			case "2":
				$sql = "
				select   mc.* , phuong.TENPHUONG from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND mc.TRA_NGAY is null AND mc.PCMTRA_NGAY is null
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				";
				break;
			//sắp trễ hạn
			case "3":
				$sql = "
				select  mc.* , phuong.TENPHUONG from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND mc.TRA_NGAY is null AND mc.PCMTRA_NGAY is null AND
				mc.NHANLAI_NGAY >= '".date("Y-m-d")."' AND  mc.NHANLAI_NGAY  - INTERVAL loai.SAPTRE DAY <= '".date("Y-m-d")."'
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				";
				break;
			//trễ
			case "4":
				$sql = "
				select mc.*, phuong.TENPHUONG  from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ?  
				AND mc.TRA_NGAY is null
				AND mc.NHANLAI_NGAY < '".date("Y-m-d")."'
				AND mc.PCMTRA_NGAY is null
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				";
				break;
			//đã rút
			case "5":
				$sql = "
				select   mc.*, phuong.TENPHUONG  from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ?  AND (not mc.TRA_NGAY is null)
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				";
				break;
			//đã giao trả
			case "6":
				$sql = "
				select   mc.*, phuong.TENPHUONG  from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ?  AND mc.TRA_NGAY is null
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				";
				break;
			//da xu ly
			case 7:
				$sql = "
				select   mc.*, phuong.TENPHUONG  from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ?  AND mc.PCMTRA_NGAY is not null AND mc.TRA_NGAY is null
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				";
				break;
			//tra ma con tre
			case 8:
				$sql = "
				select   mc.*, phuong.TENPHUONG  from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ?
				AND	mc.PCMTRA_NGAY > mc.NHANLAI_NGAY
				AND mc.PCMTRA_NGAY is not null
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				";
				break;
			case 9:
				$sql = "
				select   mc.*, phuong.TENPHUONG  from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ?
				AND	mc.PCMTRA_NGAY <= mc.NHANLAI_NGAY
				AND mc.PCMTRA_NGAY is not null
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				";
				break;
			case 10:
				$sql = "
				select   mc.*, phuong.TENPHUONG  from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ?
				AND KHONGXULY=1
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				";
				break;
			case 11:
				$sql = "
				select   mc.*, phuong.TENPHUONG  from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ?
				AND	mc.PCMTRA_NGAY <= mc.NHANLAI_NGAY
				AND mc.PCMTRA_NGAY is not null
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				";
				break;
			case 12:
				$sql = "
				select  mc.*, phuong.TENPHUONG,pyc.* from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc 
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				inner join ". QLVBDHCommon::Table("hscv_hosocongviec") . " hscv on mc.ID_HSCV = hscv.ID_HSCV
				inner join ". QLVBDHCommon::Table("hscv_phieu_yeucau_bosung") . " pyc on pyc.ID_HSCV = hscv.ID_HSCV
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ?
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				";
				break;
			case 13:
				$sql = "
				select  mc.*, phuong.TENPHUONG,pyc.* from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc 
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				inner join ". QLVBDHCommon::Table("hscv_hosocongviec") . " hscv on mc.ID_HSCV = hscv.ID_HSCV
				inner join ". QLVBDHCommon::Table("hscv_phieu_yeucau_bosung") . " pyc on pyc.ID_HSCV = hscv.ID_HSCV
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? 
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				and NGAY_BOSUNG is null
				";
				break;
			default:
				return array();
				break;
		}
		//echo $sql.$loai;
		if($chkLienThong == 1)
		{
			$sql .= " AND ID_DKQUAMANG IS NOT NULL";
		}
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql,array($loai));
		$re = $query->fetchAll();
		//return $re["CNT"];
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
			//trễ
			case "4":
				$sql = "
				select  count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND mc.PHUONG = ?
				AND mc.TRA_NGAY is null
				AND mc.NHANLAI_NGAY < '".date("Y-m-d")."'
				AND mc.PCMTRA_NGAY is null
				";
				break;
			case "5":
				$sql = "
				select  count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND mc.PHUONG = ?
				AND (not mc.TRA_NGAY is null)
				";
				break;
			case "6":
				$sql = "
				select  count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND mc.PHUONG = ?
				AND mc.TRA_NGAY is null
				";
				break;
			case "7":
				$sql = "
				select  count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND mc.PHUONG = ?
				AND mc.PCMTRA_NGAY is not null AND mc.TRA_NGAY is null 
				";
				break;
			case "8":
				$sql = "
				select  count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND mc.PHUONG = ?
				AND	mc.PCMTRA_NGAY > mc.NHANLAI_NGAY
				AND mc.PCMTRA_NGAY is not null
				";
				break;
			//đã rút
			case "9":
				$sql = "
				select  count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND mc.PHUONG = ? AND	mc.PCMTRA_NGAY <= mc.NHANLAI_NGAY AND mc.PCMTRA_NGAY is not null";
				break;
			//đã giao trả
			case "10":
				$sql = "
				select  count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND mc.PHUONG = ? AND KHONGXULY=1";
				break;
			case 12:
				$sql = "
				select  count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc 
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				inner join ". QLVBDHCommon::Table("hscv_hosocongviec") . " hscv on mc.ID_HSCV = hscv.ID_HSCV
				inner join ". QLVBDHCommon::Table("hscv_phieu_yeucau_bosung") . " pyc on pyc.ID_HSCV = hscv.ID_HSCV
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND loai.ISUBND in (".$ubndorphong.")
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")."
				";
				break;
			case 13:
				$sql = "
				select  count(*) as CNT from
				".QLVBDHCommon::table("MOTCUA_HOSO")." mc 
				inner join MOTCUA_LOAI_HOSO loai on mc.ID_LOAIHOSO = loai.ID_LOAIHOSO
				inner join ". QLVBDHCommon::Table("hscv_hosocongviec") . " hscv on mc.ID_HSCV = hscv.ID_HSCV
				inner join ". QLVBDHCommon::Table("hscv_phieu_yeucau_bosung") . " pyc on pyc.ID_HSCV = hscv.ID_HSCV
				left join MOTCUA_PHUONG phuong on mc.PHUONG = phuong.ID_PHUONG
				$joinpb
				WHERE 
				NHAN_NGAY >= '".$fromdate."' AND NHAN_NGAY <= '".$todate."' AND
				NHANLAI_NGAY >= '".$fromdategt."' AND NHANLAI_NGAY <= '".$todategt."' AND
				loai.ID_LOAIHOSO = ? AND loai.ISUBND in (".$ubndorphong.")
				".($phuong!=-1?"AND mc.PHUONG=$phuong":"")." 
				AND NGAY_BOSUNG is null
				";				
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