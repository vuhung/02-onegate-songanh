<?php 
class VanbandenreportModel{
	static function getReportData($fromdate,$todate,$id_svb,$id_lvbs){
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
		if(count($id_lvbs) > 0)
		{
			if(array_search("0",$id_lvbs) == FALSE && $id_lvbs[0] != "0"){
				$where_lvb = "`ID_LVB` in (".implode(',',$id_lvbs).")" ; 
				array_push($where_arr,$where_lvb);	
			}
		}
		$where ="";
		if(count($where_arr) > 0)
			$where = " where " . implode(' and ',$where_arr)." ";
		
		//$year = QLVBDHCommon::getYear();
		//$sql = "Select * from vbd_vanbanden_$year 
		//$where
		//";
		$where_kxl = "";
		if($where == ""){
			$where_kxl = "where `IS_KHONGXULY` =1";
		}
		else{
			$where_kxl = $where." and `IS_KHONGXULY` =1";
		}
		$where_old = "";
		if($where == ""){
			$where_old = "where ID_HSCV=-1";
		}
		else{
			$where_old = $where." and ID_HSCV=-1";
		}
		$sql = "
		 
	(select vbd.`ID_VBD` , hs.`ID_HSCV` , vbd.`MASOVANBAN` , vbd.`SODEN` as SD, vbd.`SODEN`, vbd.`SODEN_IN`, 
		vbd.`ID_CQ`,vbd.`ID_LVB` ,vbd.`TRICHYEU` ,vbd.`NGAYDEN` , vbd.`SOKYHIEU`,vbd.`NGUOITAO`,vbd.`NGAYBANHANH`,
		vbd.`SOBAN`,vbd.`IS_KHONGXULY` , vbd.`COQUANBANHANH_TEXT` , hsltd.COMMENT as KQ_DEXUAT
from `".QLVBDHCommon::Table("vbd_vanbanden")."` vbd
inner join `".QLVBDHCommon::Table("vbd_fk_vbden_hscvs")."` fk_vbd_hs on fk_vbd_hs.ID_VBDEN = vbd.ID_VBD

inner join `".QLVBDHCommon::Table("hscv_hosocongviec")."` hs on hs.`ID_HSCV` = fk_vbd_hs.`ID_HSCV`
left join `".QLVBDHCommon::Table("hscv_hosoluutheodoi")."` hsltd on hs.ID_HSCV = hsltd.ID_HSCV
".$where."
group by vbd.`ID_VBD`
)
union (
	
	select vbdse.`ID_VBD` , vbdse.`ID_HSCV` , vbdse.`MASOVANBAN` , vbdse.SODEN as `SD`, vbdse.`SODEN`, vbdse.`SODEN_IN`,vbdse.`ID_LVB`,
		vbdse.`ID_CQ`,vbdse.`TRICHYEU` ,vbdse.`NGAYDEN` , vbdse.`SOKYHIEU`,vbdse.`NGUOITAO`, vbdse.`NGAYBANHANH`,vbdse.SOBAN, vbdse.`IS_KHONGXULY`, vbdse.`COQUANBANHANH_TEXT` , NULL as KQ_DEXUAT
		from
		 ".QLVBDHCommon::Table("vbd_vanbanden")." vbdse
		 $where_kxl	
		 
	
)
union (
	select  vbdse.`ID_VBD` , vbdse.`ID_HSCV` , vbdse.`MASOVANBAN` , vbdse.SODEN as `SD`, vbdse.`SODEN`, vbdse.`SODEN_IN`,vbdse.`ID_LVB`,
		vbdse.`ID_CQ`,vbdse.`TRICHYEU` ,vbdse.`NGAYDEN` , vbdse.`SOKYHIEU`,vbdse.`NGUOITAO`, vbdse.`NGAYBANHANH`,vbdse.SOBAN, vbdse.`IS_KHONGXULY`, vbdse.`COQUANBANHANH_TEXT` ,NULL as KQ_DEXUAT
		from
		 ".QLVBDHCommon::Table("vbd_vanbanden")." vbdse
		$where_old
		 
	
)
order by SODEN_IN  
		";
//echo $where;
	//order by vbdse.`SD` DESC , dk.`ID_PL`	
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$query = $dbAdapter->query($sql);
		$re = $query->fetchAll();
		return $re;
	}



static function getNguoiXulyFromVban($id_hscv){
	
	$sql = "
		select   *    from ( select  ID_HSCV , ID_PI from `".QLVBDHCommon::Table("hscv_hosocongviec")."` hscv1 where  hscv1.`ID_LOAIHSCV` =1) hscv
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

}
?>