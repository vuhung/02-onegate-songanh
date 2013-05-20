<?php  
	class Ad_XulyvanbandenModel {
	
		static function getReportData($type,$todate,$fromdate,$arrparam,$offset,$limit){
		/*Xu ly tham so*/
		
		$where_arr =  array();
		$arr_where_all = array();
		$where_all = " where 1=1 ";
		$type_ngay = "";
		if($arrparam["type_date"] == 0){
			$type_ngay = "vbd.NGAYDEN";
		}else{
			$type_ngay = "vbd.NGAYBANHANH";
		}

		if($fromdate || $fromdate != ""){
			 $fromdate = implode("-",array_reverse(explode("/",$fromdate."/".QLVBDHCommon::getYear())))." 00:00:00";
			 $where_fromdate = " AND $type_ngay >= '".$fromdate."'" ; 
			 array_push($arr_where_all,$where_fromdate);
			 
			}
			
			if($todate || $todate != ""){
			 $todate = implode("-",array_reverse(explode("/",$todate."/".QLVBDHCommon::getYear())))." 23:59:59";
			$where_todate = " AND $type_ngay <= '".$todate."'" ; 
			
			array_push($arr_where_all,$where_todate);
		}
		

		
		$where_hscv ="";
		if(count($where_arr) > 0)
			$where_hscv = " and" . implode(' and ',$where_arr)." ";
		
			$wheretype = "=$type";
			if($type ==3){
				$wheretype = ">2";
			//$type = 2;
		}

		if(count($arrparam["sel_lvb"]) > 0){
			if(array_search("0",$arrparam["sel_lvb"]) == FALSE && $arrparam["sel_lvb"][0] != "0"){
				$operation = $arrparam["op_lvb"]==0?" AND ":" OR ";
				$where_lvb = " $operation vbd.ID_LVB in (".implode(',',$arrparam["sel_lvb"]).")" ; 
				array_push($arr_where_all,$where_lvb);	
			}
			
		}

		if(count($arrparam["sel_cqbh"]) > 0){
			if(array_search("0",$arrparam["sel_cqbh"]) == FALSE && $arrparam["sel_cqbh"][0] != "0"){
				$operation = $arrparam["op_cqbh"]==0?" AND ":" OR ";
				$where_cqbh = " $operation vbd.ID_CQ in (".implode(',',$arrparam["sel_cqbh"]).")" ; 
				array_push($arr_where_all,$where_cqbh);	
			}
			
		}
		
		$where_trangthai = "";
		$operation = $arrparam["op_trangthai"]==0?" AND ":" OR ";
		switch($arrparam["sel_trangthai"]){
			case 0 : //tat ca trang thai
			
			break;
			case 1 : // tat ca  van ban da xu ly
				$where_trangthai = " (hscv_after.IS_FINISH = 1 OR hscv_after.IS_THEODOI = 1 OR vbd.IS_KHONGXULY = 1) ";
			break;
			case 2: // da xu ly khong ra van ban di
				$where_trangthai = " ( hscv_after.ID_VBDI is NULL ) ";
			break;
			case 3: // da xu ly ra van ban di
				$where_trangthai = " ( hscv_after.ID_VBDI > 0 ) ";
			break;
			case 4: // dang xu ly
				$where_trangthai = " NOT ( hscv_after.IS_FINISH = 1 OR hscv_after.IS_THEODOI = 1 OR vbd.IS_KHONGXULY = 1) ";
			break;
			default:
				break;
		}
		
		if($where_trangthai != "")
			$where_trangthai = "  ".$operation."  ".$where_trangthai;
			array_push($arr_where_all,$where_trangthai);	
		
		if(count($where_all) > 0){
			$where_all .= " ". implode('  ' , $arr_where_all )."  ";
		}
		

		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$limitstr="";
		if($limit>0)
		$limitstr =" LIMIT $offset,$limit ";
		
		$sql = "
		select * from 
		vbd_vanbanden_2009 vbd left join vbd_fk_vbden_hscvs_2009 fk_v_h on vbd.ID_VBD = fk_v_h.ID_VBDEN  
		left  join 
		( select  rs1.`ID_HSCV` , rs1.`ID_U_RECEIVE`  , rs1.`ID_T` ,
rs2.`NAME_DEP` as NAME_DEP   , NAME_U , rs1.`ID_PL`,rs1.ID_PI, HANXULY, DATESEND,rs1.IS_FINISH,rs1.IS_THEODOI,vdbi.ID_VBDI as ID_VBDI,
rs1.IS_TRE as IS_TRE_KT , KQ_DEXUAT
from
( select hscv.`ID_HSCV` , wfl.`ID_U_RECEIVE`  , DATESEND,hscv.IS_THEODOI,
wfl.`ID_T` ,  wfl.ID_PI   , wfl.`ID_PL`  , HANXULY , wfi.IS_FINISH,wfi.IS_TRE , hsltd.COMMENT as KQ_DEXUAT from
(select ID_HSCV , ID_PI , IS_THEODOI from `".QLVBDHCommon::Table("hscv_hosocongviec")."` hscv1  where  hscv1.`ID_LOAIHSCV`$wheretype".$where_hscv.") hscv
left join `".QLVBDHCommon::Table("hscv_hosoluutheodoi")."` hsltd on hscv.ID_HSCV = hsltd.ID_HSCV 
inner join `".QLVBDHCommon::Table("wf_processlogs")."` wfl
on hscv.`ID_PI` = wfl.`ID_PI`
inner join `".QLVBDHCommon::Table("wf_processitems")."` wfi
on wfi.`ID_PI` = wfl.`ID_PI`
where wfl.`ID_U_RECEIVE`!=0 and wfl.`TRE` is NULL 
) rs1
inner join
(
 select u.`ID_U` , concat(emp.`FIRSTNAME`, ' ',emp.`LASTNAME`) AS NAME_U , de.`ID_DEP` , de.`NAME` as NAME_DEP from
`qtht_users` u inner join `qtht_employees` emp on emp.`ID_EMP` = u.`ID_EMP`
 inner join `qtht_departments` de on de.`ID_DEP` = emp.`ID_DEP`

)rs2
on rs2.`ID_U` = rs1.ID_U_RECEIVE
left join " .QLVBDHCommon::Table("vbdi_vanbandi") . "  vdbi on rs1.ID_HSCV = vdbi.ID_HSCV 

group by ID_HSCV ORDER BY ID_HSCV DESC ) hscv_after 
on fk_v_h.ID_HSCV=hscv_after.ID_HSCV  
 
$where_all
".$limitstr;
		//echo $sql; exit;
		try{
			$query = $dbAdapter->query($sql);
			$re = $query->fetchAll();
		}catch(Exception $ex){
			return array(); 
		}
		
		//Thong ke 
		
		/*$dem_ktxl = 0;
		$dem_ktxl_tre = 0;
		$dem_dangxl = 0;
		$dem_dangxl_tre = 0;
		foreach ($re as $item){
			if($item["IS_FINISH"]){
				$dem_ktxl++;
				
			}else{
				if($item["IS_LUUTHEODOI"])
					$dem_ktxl++;
					if($item["IS_TRE_KT"])
						$dem_ktxl_tre++;
				else{
					$dem_dangxl++;
					$dis_hour = QLVBDHCommon::getTreHan($item["DATESEND"],$item["HANXULY"]);
					if($dis_hour > 0){
						$dem_dangxl_tre++;
					}
				}
					
			}

			
		}
		$arr_thongke["dem_ktxl"] = $dem_ktxl;
		$arr_thongke["dem_ktxl_tre"] = $dem_ktxl_tre;
		$arr_thongke["dem_dangxl"] = $dem_dangxl;
		$arr_thongke["dem_dangxl_tre"] = $dem_dangxl_tre;
		*/
		return $re;
	}
	
	

	static function thongke($type,$todate,$fromdate,$arrparam){
		/*Xu ly tham so*/
		
		$where_arr =  array();
		$arr_where_all = array();
		$where_all = " where 1=1 ";
		$type_ngay = "";
		if($arrparam["type_date"] == 0){
			$type_ngay = "vbd.NGAYDEN";
		}else{
			$type_ngay = "vbd.NGAYBANHANH";
		}

		if($fromdate || $fromdate != ""){
			 $fromdate = implode("-",array_reverse(explode("/",$fromdate."/".QLVBDHCommon::getYear())))." 00:00:00";
			 $where_fromdate = " AND $type_ngay >= '".$fromdate."'" ; 
			 array_push($arr_where_all,$where_fromdate);
			 
			}
			
			if($todate || $todate != ""){
			 $todate = implode("-",array_reverse(explode("/",$todate."/".QLVBDHCommon::getYear())))." 23:59:59";
			$where_todate = " AND $type_ngay <= '".$todate."'" ; 
			
			array_push($arr_where_all,$where_todate);
		}
		

		
		$where_hscv ="";
		if(count($where_arr) > 0)
			$where_hscv = " and" . implode(' and ',$where_arr)." ";
		
			$wheretype = "=$type";
			if($type ==3){
				$wheretype = ">2";
			//$type = 2;
		}

		if(count($arrparam["sel_lvb"]) > 0){
			if(array_search("0",$arrparam["sel_lvb"]) == FALSE && $arrparam["sel_lvb"][0] != "0"){
				$operation = $arrparam["op_lvb"]==0?" AND ":" OR ";
				$where_lvb = " $operation vbd.ID_LVB in (".implode(',',$arrparam["sel_lvb"]).")" ; 
				array_push($arr_where_all,$where_lvb);	
			}
			
		}

		if(count($arrparam["sel_cqbh"]) > 0){
			if(array_search("0",$arrparam["sel_cqbh"]) == FALSE && $arrparam["sel_cqbh"][0] != "0"){
				$operation = $arrparam["op_cqbh"]==0?" AND ":" OR ";
				$where_cqbh = " $operation vbd.ID_CQ in (".implode(',',$arrparam["sel_cqbh"]).")" ; 
				array_push($arr_where_all,$where_cqbh);	
			}
			
		}
		
		$where_trangthai = "";
		$operation = $arrparam["op_trangthai"]==0?" AND ":" OR ";
		switch($arrparam["sel_trangthai"]){
			case 0 : //tat ca trang thai
			
			break;
			case 1 : // tat ca  van ban da xu ly
				$where_trangthai = " (hscv_after.IS_FINISH = 1 OR hscv_after.IS_THEODOI = 1 OR vbd.IS_KHONGXULY = 1) ";
			break;
			case 2: // da xu ly khong ra van ban di
				$where_trangthai = " ( hscv_after.ID_VBDI is NULL ) ";
			break;
			case 3: // da xu ly ra van ban di
				$where_trangthai = " ( hscv_after.ID_VBDI > 0 ) ";
			break;
			case 4: // dang xu ly
				$where_trangthai = " NOT ( hscv_after.IS_FINISH = 1 OR hscv_after.IS_THEODOI = 1 OR vbd.IS_KHONGXULY = 1) ";
			break;
			default:
				break;
		}
		
		if($where_trangthai != "")
			$where_trangthai = "  ".$operation."  ".$where_trangthai;
			array_push($arr_where_all,$where_trangthai);	
		
		if(count($where_all) > 0){
			$where_all .= " ". implode('  ' , $arr_where_all )."  ";
		}
		

		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$limitstr="";
		if($limit>0)
		$limitstr =" LIMIT $offset,$limit ";
		
		$sql = "
		select * from 
		vbd_vanbanden_2009 vbd left join vbd_fk_vbden_hscvs_2009 fk_v_h on vbd.ID_VBD = fk_v_h.ID_VBDEN  
		left  join 
		( select  rs1.`ID_HSCV` , rs1.`ID_U_RECEIVE`  , rs1.`ID_T` ,
rs2.`NAME_DEP` as NAME_DEP   , NAME_U , rs1.`ID_PL`,rs1.ID_PI, HANXULY, DATESEND,rs1.IS_FINISH,rs1.IS_THEODOI,
rs1.IS_TRE as IS_TRE_KT
from
( select hscv.`ID_HSCV` , wfl.`ID_U_RECEIVE`  , DATESEND,hscv.IS_THEODOI,
wfl.`ID_T` ,  wfl.ID_PI   , wfl.`ID_PL`  , HANXULY , wfi.IS_FINISH,wfi.IS_TRE from
(select ID_HSCV , ID_PI , IS_THEODOI from `".QLVBDHCommon::Table("hscv_hosocongviec")."` hscv1  where  hscv1.`ID_LOAIHSCV`$wheretype".$where_hscv.") hscv
inner join `".QLVBDHCommon::Table("wf_processlogs")."` wfl
on hscv.`ID_PI` = wfl.`ID_PI`
inner join `".QLVBDHCommon::Table("wf_processitems")."` wfi
on wfi.`ID_PI` = wfl.`ID_PI`
where wfl.`ID_U_RECEIVE`!=0 and wfl.`TRE` is NULL 
) rs1
inner join
(
 select u.`ID_U` , concat(emp.`FIRSTNAME`, ' ',emp.`LASTNAME`) AS NAME_U , de.`ID_DEP` , de.`NAME` as NAME_DEP from
`qtht_users` u inner join `qtht_employees` emp on emp.`ID_EMP` = u.`ID_EMP`
 inner join `qtht_departments` de on de.`ID_DEP` = emp.`ID_DEP`

)rs2
on rs2.`ID_U` = rs1.ID_U_RECEIVE
group by ID_HSCV ORDER BY ID_HSCV DESC ) hscv_after 
on fk_v_h.ID_HSCV=hscv_after.ID_HSCV  
 $where_all
";
		
		$re = array();
		//try{
			$query = $dbAdapter->query($sql);
			$re = $query->fetchAll();
		//}catch(Exception $ex){
			//return array(); 
		//}
		
		$arr_thongke = array();
		$dem = 0;
		$dem_ktxl_tre = 0;
		$dem_ktxl = 0;
		$dem_dangxl = 0;
		$dem_dangxl_tre = 0;
		$dem_ktxlluutheodoi = 0;
		$dem_khongxuly = 0;
		$ktxuly_ravbdi = 0;
		$dangxuly_ravbdi =0;
		foreach($re as $item){

			if($item["IS_KHONGXULY"]){
				$dem_khongxuly++;
				$dem_ktxl++;
			}else
			if($item["IS_FINISH"]){
				$dem_ktxl++;
				if($item["IS_TRE_KT"])
					$dem_ktxl_tre++;
				
			}else{
				if($item["IS_THEODOI"]){
					$dem_ktxl++;
					$dem_ktxlluutheodoi++;
				}else{
					$dem_dangxl++;
					$dis_hour = QLVBDHCommon::getTreHan($item["DATESEND"],$item["HANXULY"]);
					if($dis_hour > 0){
						$dem_dangxl_tre++;
					}
				}
					
			}
			if($item["ID_HSCV"] != "")	{
				$list_vbdi = Ad_XulyvanbandenModel::getListVanbandiByIdHSCV($item["ID_HSCV"]);
				if(count($list_vbdi) > 0){
					if($item["IS_FINISH"] == 1)
						$ktxuly_ravbdi++;
					else
						$dangxuly_ravbdi++;
				}
				foreach($list_vbdi as $vbdi){
					//echo $vbdi["SOKYHIEU"]."<br/>";
				}
			}
			$dem++;
		}
		$arr_thongke["dem_ktxl"] = $dem_ktxl;
		$arr_thongke["dem_ktxl_tre"] = $dem_ktxl_tre;
		$arr_thongke["dem_dangxl"] = $dem_dangxl;
		$arr_thongke["dem_dangxl_tre"] = $dem_dangxl_tre;
		$arr_thongke["dem_ktxlluutheodoi"] = $dem_ktxlluutheodoi;
		$arr_thongke["dem_khongxuly"] = $dem_khongxuly;
		$arr_thongke["ktxuly_ravbdi"] = $ktxuly_ravbdi;
		$arr_thongke["dangxuly_ravbdi"] = $dangxuly_ravbdi;
		$arr_thongke["dem"] = $dem;
		return $arr_thongke;
	}


	
	static function getCountReportData($type,$todate,$fromdate,$arrparam){
			
		/*Xu ly tham so*/
		
		$where_arr =  array();
		$arr_where_all = array();
		$where_all = " where 1=1 ";
		$type_ngay = "";
		if($arrparam["type_date"] == 0){
			$type_ngay = "vbd.NGAYDEN";
		}else{
			$type_ngay = "vbd.NGAYBANHANH";
		}

		if($fromdate || $fromdate != ""){
			 $fromdate = implode("-",array_reverse(explode("/",$fromdate."/".QLVBDHCommon::getYear())))." 00:00:00";
			 $where_fromdate = " AND $type_ngay >= '".$fromdate."'" ; 
			 array_push($arr_where_all,$where_fromdate);
			 
			}
			
			if($todate || $todate != ""){
			 $todate = implode("-",array_reverse(explode("/",$todate."/".QLVBDHCommon::getYear())))." 23:59:59";
			$where_todate = " AND $type_ngay <= '".$todate."'" ; 
			
			array_push($arr_where_all,$where_todate);
		}
		

		
		$where_hscv ="";
		if(count($where_arr) > 0)
			$where_hscv = " and" . implode(' and ',$where_arr)." ";
		
			$wheretype = "=$type";
			if($type ==3){
				$wheretype = ">2";
			//$type = 2;
		}

		if(count($arrparam["sel_lvb"]) > 0){
			if(array_search("0",$arrparam["sel_lvb"]) == FALSE && $arrparam["sel_lvb"][0] != "0"){
				$operation = $arrparam["op_lvb"]==0?" AND ":" OR ";
				$where_lvb = " $operation vbd.ID_LVB in (".implode(',',$arrparam["sel_lvb"]).")" ; 
				array_push($arr_where_all,$where_lvb);	
			}
			
		}

		if(count($arrparam["sel_cqbh"]) > 0){
			if(array_search("0",$arrparam["sel_cqbh"]) == FALSE && $arrparam["sel_cqbh"][0] != "0"){
				$operation = $arrparam["op_cqbh"]==0?" AND ":" OR ";
				$where_cqbh = " $operation vbd.ID_CQ in (".implode(',',$arrparam["sel_cqbh"]).")" ; 
				array_push($arr_where_all,$where_cqbh);	
			}
			
		}
		
		$where_trangthai = "";
		$operation = $arrparam["op_trangthai"]==0?" AND ":" OR ";
		switch($arrparam["sel_trangthai"]){
			case 0 : //tat ca trang thai
			
			break;
			case 1 : // tat ca  van ban da xu ly
				$where_trangthai = " (hscv_after.IS_FINISH = 1 OR hscv_after.IS_THEODOI = 1 OR vbd.IS_KHONGXULY = 1) ";
			break;
			case 2: // da xu ly khong ra van ban di
				$where_trangthai = " ( hscv_after.ID_VBDI is NULL ) ";
			break;
			case 3: // da xu ly ra van ban di
				$where_trangthai = " ( hscv_after.ID_VBDI > 0 ) ";
			break;
			case 4: // dang xu ly
				$where_trangthai = " NOT ( hscv_after.IS_FINISH = 1 OR hscv_after.IS_THEODOI = 1 OR vbd.IS_KHONGXULY = 1) ";
			break;
			default:
				break;
		}
		
		if($where_trangthai != "")
			$where_trangthai = "  ".$operation."  ".$where_trangthai;
			array_push($arr_where_all,$where_trangthai);	
		
		if(count($where_all) > 0){
			$where_all .= " ". implode('  ' , $arr_where_all )."  ";
		}
		

		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "
		select count(*) as DEM from 
		vbd_vanbanden_2009 vbd left join vbd_fk_vbden_hscvs_2009 fk_v_h on vbd.ID_VBD = fk_v_h.ID_VBDEN  
		left  join 
		( select  rs1.`ID_HSCV` , rs1.`ID_U_RECEIVE`  , rs1.`ID_T` ,
rs2.`NAME_DEP` as NAME_DEP   , NAME_U , rs1.`ID_PL`,rs1.ID_PI, HANXULY, DATESEND,rs1.IS_FINISH,rs1.IS_THEODOI,vdbi.ID_VBDI as ID_VBDI,
rs1.IS_TRE as IS_TRE_KT
from
( select hscv.`ID_HSCV` , wfl.`ID_U_RECEIVE`  , DATESEND,hscv.IS_THEODOI,
wfl.`ID_T` ,  wfl.ID_PI   , wfl.`ID_PL`  , HANXULY , wfi.IS_FINISH,wfi.IS_TRE from
(select ID_HSCV , ID_PI , IS_THEODOI from `".QLVBDHCommon::Table("hscv_hosocongviec")."` hscv1  where  hscv1.`ID_LOAIHSCV`$wheretype".$where_hscv.") hscv
inner join `".QLVBDHCommon::Table("wf_processlogs")."` wfl
on hscv.`ID_PI` = wfl.`ID_PI`
inner join `".QLVBDHCommon::Table("wf_processitems")."` wfi
on wfi.`ID_PI` = wfl.`ID_PI`
where wfl.`ID_U_RECEIVE`!=0 and wfl.`TRE` is NULL 
) rs1
inner join
(
 select u.`ID_U` , concat(emp.`FIRSTNAME`, ' ',emp.`LASTNAME`) AS NAME_U , de.`ID_DEP` , de.`NAME` as NAME_DEP from
`qtht_users` u inner join `qtht_employees` emp on emp.`ID_EMP` = u.`ID_EMP`
 inner join `qtht_departments` de on de.`ID_DEP` = emp.`ID_DEP`

)rs2
on rs2.`ID_U` = rs1.ID_U_RECEIVE
left join vbdi_vanbandi_2009 vdbi on rs1.ID_HSCV = vdbi.ID_HSCV 

group by ID_HSCV ORDER BY ID_HSCV DESC ) hscv_after 
on fk_v_h.ID_HSCV=hscv_after.ID_HSCV  

$where_all
";
		//echo $sql; 
		try{
			$query = $dbAdapter->query($sql);
			$re = $query->fetch();
			return $re["DEM"];
		}catch(Exception $ex){
			return 0;
		}
	}
	
	static function getListVanbandiByIdHSCV($id_hscv){
    	$dbAdapter = Zend_Db_Table::getDefaultAdapter();
    	$sql = "select vbdi.* from 
    		(select * from ".QLVBDHCommon::Table('hscv_hosocongviec')." hscv where hscv.ID_HSCV = ?) hscv_sel
			inner join ".
			QLVBDHCommon::Table("vbdi_vanbandi"). " vbdi  on hscv_sel.ID_HSCV = vbdi.ID_HSCV
    	";
    	
    	try{
    		$stm = $dbAdapter->query($sql,array($id_hscv));
    		$re = $stm->fetchAll();
    		return $re;
    	}catch (Exception $ex){
    		return array();
    	}
    	
    	
    }


	}
?>