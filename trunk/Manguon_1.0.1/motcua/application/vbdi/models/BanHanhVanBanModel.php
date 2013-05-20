<?php

class BanHanhVanBanModel {
	static function getAllListBanHanhVanBan($year){
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "
		select hscv.`ID_HSCV` , ID_DUTHAO , TENDUTHAO , NGUOIKY , NGUOISOAN , loai.ID_LOAIHSCV ,loai.NAME as TENCV from 
		`hscv_duthao_".$year."` duthao 
		inner join `hscv_hosocongviec_".$year."` hscv on duthao.`ID_HSCV` = hscv.`ID_HSCV`
		inner join `hscv_loaihosocongviec` loai on loai.`ID_LOAIHSCV` = hscv.`ID_LOAIHSCV` 
		where duthao.`TRANGTHAI`=1
		";
		$qery = $dbAdapter->query($sql);
		return $qery->fetchAll();
	}
	
	
	static function countDuthao($year,$serch_ten,$theo_loai){
		$serch_ten="'%".$serch_ten."%'";
		$arr_where = array();
		$where = "";
		//echo $serch_ten."------".$theo_loai;
		if($serch_ten != ""){	
			$where_ten = 'TENDUTHAO LIKE '.$serch_ten;
			array_push($arr_where,$where_ten);   
		}
		array_push($arr_where, 'TRANGTHAI = 1');
		
		if($theo_loai != 0){
			if($theo_loai <3)
			array_push($arr_where, "loai.`ID_LOAIHSCV`=$theo_loai");
			else
			array_push($arr_where, "loai.`ID_LOAIHSCV`>=$theo_loai");
		}
		if(count($arr_where) > 0 ) { //ton tai it nhat mot dieu kien can tim kiem
			$where = "  and ".implode(' and ',$arr_where)." ";
			
		}
		//var_dump($arr_where) ;
		//echo $where;
		//$where = "";
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "
		select count(*) as DEM 
		 from 
		`hscv_duthao_".$year."` duthao 
		inner join `hscv_hosocongviec_".$year."` hscv on duthao.`ID_HSCV` = hscv.`ID_HSCV`
		inner join `hscv_loaihosocongviec` loai on loai.`ID_LOAIHSCV` = hscv.`ID_LOAIHSCV` 
		where 1=1 ".$where."ORDER BY TRANGTHAI ";
		
		$query = $dbAdapter->query($sql);
		$return  = $query->fetch();
		return $return["DEM"];
	}
	
	static function getAllListBanHanhVanBanMixed($year,$serch_ten,$theo_loai,$offset,$limit){
		$serch_ten="'%".$serch_ten."%'";
		$arr_where = array();
		$where = "";
		//echo $serch_ten."------".$theo_loai;
		if($serch_ten != ""){	
			$where_ten = 'TENDUTHAO LIKE '.$serch_ten;
			array_push($arr_where,$where_ten);   
		}
		array_push($arr_where, 'TRANGTHAI = 1');
		
		if($theo_loai != 0){
		
			array_push($arr_where, "loai.`ID_LOAIHSCV`=$theo_loai");
		}
		if(count($arr_where) > 0 ) { //ton tai it nhat mot dieu kien can tim kiem
			$where = "  and ".implode(' and ',$arr_where)." ";
			
		}
		//var_dump($arr_where) ;
		//echo $where;
		//$where = "";
		$strlimit =" ";
		if($limit>0){
			$strlimit = " LIMIT $offset,$limit";
		}
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "
		select hscv.`ID_HSCV` , ID_DUTHAO , TRANGTHAI,TENDUTHAO , NGUOIKY , NGUOISOAN , loai.ID_LOAIHSCV ,loai.NAME as TENCV from 
		`hscv_duthao_".$year."` duthao 
		inner join `hscv_hosocongviec_".$year."` hscv on duthao.`ID_HSCV` = hscv.`ID_HSCV`
		inner join `hscv_loaihosocongviec` loai on loai.`ID_LOAIHSCV` = hscv.`ID_LOAIHSCV` 
		where 1=1 ".$where."ORDER BY TRANGTHAI ".$strlimit;
		
		$qery = $dbAdapter->query($sql);
		return $qery->fetchAll();
	}
	
	
	
	static function getDetailDuThao($year,$id_loai,$idDuthao){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		if($id_loai == 1)
		{
			//xu ly van ban den
			$sql = "select  NAME , vbd.`MASOVANBAN` as MASO, vbd.`TRICHYEU` , duthao.`NGUOISOAN`,duthao.`NGUOIKY`,
			ID_LOAIHSCV,vbd.`SOKYHIEU`, vbd.`NGAYBANHANH`,vbd.`COQUANBANHANH_TEXT`
			from `hscv_duthao_".$year."` duthao 
			inner join `hscv_hosocongviec_".$year."` hscv on duthao.`ID_HSCV` = hscv.`ID_HSCV` and duthao.`ID_DUTHAO` = ?
			inner join `vbd_fk_vbden_hscvs_$year` fk on fk.`ID_HSCV` = hscv.`ID_HSCV` 
			inner join `vbd_vanbanden_".$year."` vbd on vbd.`ID_VBD`= fk.`ID_VBDEN` 
			
			";
			
		}
		if($id_loai == 2)
		{
		//soan thao van ban
		$sql = "select  hscv.NAME as NAME, cv.`NAME` as TRICHYEU, duthao.`NGUOISOAN`,duthao.`NGUOIKY`
			
			from `hscv_duthao_".$year."` duthao 
			inner join `hscv_hosocongviec_".$year."` hscv on duthao.`ID_HSCV` = hscv.`ID_HSCV` and duthao.`ID_DUTHAO` = ?
			inner join `hscv_congviecsoanthao_".$year."` cv on cv.`ID_HSCV`= hscv.`ID_HSCV` 
			 
			";
		}
		if($id_loai >=3){
			$sql = "select  hscv.NAME as NAME, cv.`TRICHYEU`, duthao.`NGUOISOAN`,duthao.`NGUOIKY`
			
			from `hscv_duthao_".$year."` duthao 
			inner join `hscv_hosocongviec_".$year."` hscv on duthao.`ID_HSCV` = hscv.`ID_HSCV` and duthao.`ID_DUTHAO` = ?
			inner join `motcua_hoso_".$year."` cv on cv.`ID_HSCV`= hscv.`ID_HSCV` 
			 
			";
		}
		
		$qery = $dbAdapter->query($sql,array($idDuthao));
		return $qery->fetch();
	}
	function GetLastPhienBan($idDuthao){
		global $db;
		$arrdata = array($idDuthao);
		$query = $db->query('
		select * from
  		`gen_filedinhkem_'.QLVBDHCommon::getYear().'` fdk  inner join (select*  from `hscv_phienbanduthao_'.QLVBDHCommon::getYear().'` where
  		`ID_DUTHAO`=? ) pb
			where pb.`ID_PB_DUTHAO` = fdk.`ID_OBJECT` and fdk.`TYPE`=2
		order by pb.`ID_PB_DUTHAO` DESC
		',$arrdata);
		return $query->fetchAll();
	}
static function getkyhieu($name){
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "select KYHIEU  from ".QLVBDHCommon::Table("vb_loaivanban")." where NAME= $name
		
		";
		$qery = $dbAdapter->query($sql);
		$return= $qery->fetch();
		return $return["KYHIEU"];
	}
}

?>
