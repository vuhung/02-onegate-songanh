<?php

class dkwebModel {
	function getHosoFromWebsite($params){
		
		$sql = "
			select hs.*,mc_lhs.TENLOAI,mc_lhs.ID_LOAIHSCV,mc_lhs.ID_LOAIHOSO  from dvc_motcua_hoso_web hs
			left join motcua_loai_hoso mc_lhs on hs.MALOAIHOSO = mc_lhs.CODE
			where ( IS_TIEPNHAN = 0 or IS_TIEPNHAN is NULL )
			ORDER BY hs.ID_MC_HSW DESC
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,$parameters);
			return $qr->fetchAll(); 

		}catch(Exception $ex){
			return array();
		}
	}
	function deleteHosomotcua($id)
	{
		$sql = sprintf("update dvc_motcua_hoso_web set IS_TIEPNHAN = 1 where ID_MC_HSW = %s",$id);

		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$qr 	= $dbAdapter->query($sql);
	}

	function updateDKQuaMang($idDKQuaMang, $id)
	{
		$sql = sprintf("update ".QLVBDHCommon::Table('motcua_hoso')." set ID_DKQUAMANG = $idDKQuaMang where ID_HOSO = %s",$id);

		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$qr 	= $dbAdapter->query($sql);
	}

	function getDetail($id){
		
		$sql = "
			select distinct hsqm.*, loai.TENLOAI , loai.ID_LOAIHOSO, loai.ID_LV_MC from ". "dvc_motcua_hoso_web" ." hsqm
			left join motcua_loai_hoso loai on hsqm.MALOAIHOSO = loai.CODE  
			where hsqm.ID_MC_HSW = ?
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($id));
			return $qr->fetch(); 
		}catch(Exception $ex){
			return array();
		}
	}

	function getInsertFileDinhKem($idObject,$id,$user,$year,$month){		
		$sql = sprintf("select MAHOSO,IS_GETFILE from dvc_motcua_hoso_web where ID_MC_HSW = %s",$id);		
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr 	= $dbAdapter->query($sql);
			$row1 	= $qr->fetch();
			if((int)$row1["IS_GETFILE"] == 0 ){
				$config = Zend_Registry::get('config');
				$sql 	= sprintf("select * from dvc_motcua_taptin_web where MAHOSO = '%s'",$row1["MAHOSO"]);
				$qr1 	= $dbAdapter->query($sql);
				$row2 	= $qr1->fetchAll();
				$table 		= "gen_filedinhkem_".$year;			
				$fpath 		= $config->dvc_uploadpath;
				$date = getdate();
				$model = new filedinhkemModel($year);
				$rootpath 		= $config->file->root_dir;
				foreach($row2 as $item){
					$file = new FileDinhKem();
					$file->_time_update = $date['year'].'-'.$date['mon'].'-'.$date['mday'].' '.$date['hours'].':'.$date['minutes'].':'.$date['seconds'];
					$file->_nam = $year;				
					$file->_thang = $month;				
					$file->_folder = $rootpath.'\\'.$year.'\\'.$month;
					$file->_id_object = $idObject;
					$file->_user = $user;
					$file->_filename = $item["FILENAME"];
					$file->_mime = $item["MIME"];
					$file->_maso = $item["MASO"];	
					$file->_type = 1;
					
					$pathFolderCopy = $rootpath.'\\'.$fpath;
					$newFolder =  $rootpath."\\".$year."\\".$month;
					
					$pathfilecopy = $pathFolderCopy.'\\'.$item["MASO"];
					$newfile =  $newFolder."\\".$item["MASO"];
					
					if (!file_exists($pathFolderCopy)) {  
						mkdir($pathFolderCopy, 0777); 
					} 
					if (!file_exists($newFolder)) {  
						mkdir($rootpath."\\".$year, 0777); 
						mkdir($newFolder, 0777); 
					} 					

					copy($pathfilecopy, $newfile);

					$id_file = $model->insertFileWithIdObject($file);
					$model->updateMaSo($id_file,$item["MASO"]);
				}
				$sql = sprintf("update dvc_motcua_hoso_web set IS_GETFILE = '%s' where ID_MC_HSW = %s",$idObject,$id);
				$dbAdapter->query($sql);
			}
		}catch(Exception $ex){
			return array();
		}
	}
	
	function getFileDetail($mahoso){
		$sql = "
			select MAHOSO,FILENAME,MIME,SIZE,TENTAILIEU,MASO from dvc_motcua_taptin_web where MAHOSO = ?
			
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($mahoso));
			return $qr->fetchAll(); 
		}catch(Exception $ex){
			return array();
		}
	}

	function getFileByMaso($maso){		
		$sql = "
			select MAHOSO,FILENAME,MIME,SIZE,TENTAILIEU,MASO,LINK from dvc_motcua_taptin_web where MASO = ?			
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($maso));
			return $qr->fetch(); 
		}catch(Exception $ex){
			return array();
		}
	}

	function updateTrangthai($id,$trangthai){
		$sql = "
			update dvc_motcua_hoso_web
			set TRANGTHAI=?
			where ID_MC_HSW = ?
			
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($trangthai,$id));
			return $qr->fetch(); 
		}catch(Exception $ex){
			return array();
		}
	}

	function updateTiepnhan($id,$is_tiepnhan){ 
	// 0||Null => chưa tn
	// 1 => đã tiếp nhận
	// 2 => không hợp lệ
	// 3 => không tiếp nhận
		$sql = "
			update dvc_motcua_hoso_web
			set IS_TIEPNHAN=?
			where ID_MC_HSW = ?
			
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			return $qr = $dbAdapter->query($sql,array($is_tiepnhan,$id));
		}catch(Exception $ex){
			return array();
		}
	}

	function GetMasodkquamangByIDHSCV($id_hscv){
		
		$sql = "
			select mc.MAHOSO  from 
			". QLVBDHCommon::Table("motcua_hoso")." mc
			
			where ID_HSCV = ? and ID_DKQUAMANG > 0
			
		";
		//echo $sql.$id_hscv;
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($id_hscv));
			$re =  $qr->fetch(); 
			
			return $re["MAHOSO"];
		}catch(Exception $ex){
			return array();
		}
	}

	function delete($id){
		$sql ="
			delete from dvc_motcua_hoso_web where ID_MC_HSW = ?
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->prepare($sql);
			$stm->execute(array($id));
		}catch(Exception $ex){
		
		}
	}
	

	function updateDaTiepnhan($id){
		$sql ="
			update  dvc_motcua_hoso_web set IS_TIEPNHAN = 1 where ID_MC_HSW = ?
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->prepare($sql);
			$stm->execute(array($id));
		}catch(Exception $ex){
		
		}

		$sql ="
			delete from  dvc_motcua_hoso_web  where ID_MC_HSW = ?
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->prepare($sql);
			$stm->execute(array($id));
		}catch(Exception $ex){
		
		}

	}
	
	

	function updateAfterTiepnhan($id_dkquamang,$params){
		//HOTEN
		//DIACHI
		//EMAIL
		//ISTIEPNHAN
		//ngay tiep nhan
		//ngay hen tra
		//cap nhat trang thai dang xu ly
		//cap nhat nguoi dang xu ly
		$parameters = array();
		$parameters[] = $params["HOTEN"];
		$parameters[] = $params["DIACHI"];
		$parameters[] = $params["EMAIL"];
		$parameters[] = $params["DIENTHOAI"];
		//$parameters[] = $params["MASOBIENNHAN"];
		$parameters[] = $params["NGAYTIEPNHAN"];
		$parameters[] = $params["NGAYHENTRA"];
		$parameters[] = $params["NGUOIDANGXULY"];
		$parameters[] = $id_dkquamang;

		
		
		
		$sql = " update  " . QLVBDHCommon::Table("services_motcua_dkquamang") ." 
		set 
		HOTEN = ?,
		DIACHI = ?,
		EMAIL = ?,
		DIENTHOAI = ?,
		NGAYTIEPNHAN = ? ,
		NGAYHENTRA = ? ,  
		NGUOIDANGXULY = ?,
		TRANGTHAI = 1 ,
		IS_UPDATE = 1,
		IS_TIEPNHAN = 1
		where ID_DKQUAMANG = ?" ;
		//try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->prepare($sql);
			$stm->execute($parameters);
			return 1;
		//}catch(Exception $ex){
			return 0;
		//}
	}

	//gopy

	
	function getGopy($id){
		$sql = "
			select gy.* from  web_motcua_gopy gy
			inner join dvc_motcua_hoso_web hs on gy.MAHOSO = hs.MAHOSO
			where hs.ID_MC_HSW = ?
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($id));
			return $qr->fetchAll(); 
		}catch(Exception $ex){
			return array();
		}
	}
	
	function insertGopy($params){
		
		$param = array();
		$param[] = $params["NOIDUNG"];
		$param[] = $params["NAME_U"];
		$param[] = $params["DATESEND"];
		$param[] = $params["MAHOSO"];
		
		$sql = "
			insert into web_motcua_gopy (NOIDUNG,NAME_U,DATESEND,MAHOSO) 
			values ( 
				?, ?, ? , ?
			)
		";
		//try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,$param);
			//return $qr->fetch(); 
		//}catch(Exception $ex){
			return array();
		//}
	}

	function deleteGopy($params){
		
	}
	

	

	function getDetailSpe($id_hoso, $loai){
	   $dbAdapter = Zend_Db_Table::getDefaultAdapter();
       $sql = "
			select hs.MAHOSO, MALOAIHOSO, loai.LOAIDON from 
			dvc_motcua_hoso_web hs 
			inner join motcua_loai_hoso loai on hs.MALOAIHOSO = loai.CODE 
			where ID_MC_HSW  = ?

	   ";
		$loaidon = 0;
		
		$mahoso = "";
		
		try{
			
			$stm = $dbAdapter->query($sql,array($id_hoso));
			$re = $stm->fetch();
			$loaidon = (int)$re["LOAIDON"];
			$mahoso = $re["MAHOSO"];
		}catch(Exception $ex){
			return array();
		}
		
		$tble_spe = "";
		switch($loaidon){
			case 1:
				$tble_spe = "web_thicong_ct";
				
				break;
			case 2:
				$tble_spe = "web_phuhieu_tcd";
				$tble_spe_ds = "web_danhsach_tcd";
				break;
			case 3:
				$tble_spe = "web_phuhieu_xhd";
				$tble_spe_ds = "web_danhsach_xhd";
				break;
			case 4:
				$tble_spe = "web_phuhieu_xtx";
				$tble_spe_ds = "web_danhsach_xtx";
				break;
			case 5:
				$tble_spe = "web_phuhieu_xdl";
				$tble_spe_ds = "web_danhsach_xdl";
				break;
			case 6:
				$tble_spe = "web_capphep_dc";
				
				break;
			default:
				return array();
		
		}

		$sql = "
			select tc.* , mc_hs.TENTOCHUCCANHAN from ". $tble_spe ." tc
			inner join dvc_motcua_hoso_web mc_hs on tc.MAHOSO = tc.MAHOSO
			where  tc.MAHOSO = ?
		";
		//echo $sql . "$id_hoso"; exit;
		$arr_data = array();
		try{
			//$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->query($sql,array($mahoso));
			$arr_data = $stm->fetch();
			$arr_data["LOAIDON"] = $loaidon;
			//return $arr;
		}catch(Exception $ex){
		
		}
		
		$sql = "
			select tc.* from ". $tble_spe_ds ." tc
			
			where MAHOSO  = ?
		";

		$arr_ds = array();
		try{
			//$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->query($sql,array($mahoso));
			$arr_ds = $stm->fetchAll();
			
			//return $arr;
		}catch(Exception $ex){
		
		}
		//echo $sql; echo $mahoso;exit;
		$arr =array();
		$arr["data"] = $arr_data;
		$arr["danhsach"] = $arr_ds;
		//var_dump($arr); exit;
		return $arr;


	}

	function getDetailSpeByMaso($mahoso,$loai=1){
		
	   
	   
	   $dbAdapter = Zend_Db_Table::getDefaultAdapter();
       $sql = "
			select hs.MAHOSO, loai.CODE as MALOAIHOSO, loai.LOAIDON from 
			". QLVBDHCommon::Table("motcua_hoso")." hs 
			inner join motcua_loai_hoso loai on hs.ID_LOAIHOSO = loai.ID_LOAIHOSO 
			where hs.MAHOSO  = ?

	   ";
	    //echo $sql.$mahoso;
	   //$mahoso = $re["MAHOSO"];
		$loaidon = 0;
		
		
		
		try{
			
			$stm = $dbAdapter->query($sql,array($mahoso));
			$re = $stm->fetch();
			
			$loaidon = (int)$re["LOAIDON"];
			
		}catch(Exception $ex){
			return array();
		}
		
		$tble_spe = "";
		switch($loaidon){
			case 1:
				$tble_spe = "web_thicong_ct";
				
				break;
			case 2:
				$tble_spe = "web_phuhieu_tcd";
				$tble_spe_ds = "web_danhsach_tcd";
				break;
			case 3:
				$tble_spe = "web_phuhieu_xhd";
				$tble_spe_ds = "web_danhsach_xhd";
				break;
			case 4:
				$tble_spe = "web_phuhieu_xtx";
				$tble_spe_ds = "web_danhsach_xtx";
				break;
			case 5:
				$tble_spe = "web_phuhieu_xdl";
				$tble_spe_ds = "web_danhsach_xdl";
				break;
			case 6:
				$tble_spe = "web_capphep_dc";
				
				break;
			default:
				return array();
		
		}

		$sql = "
			select tc.* from ". $tble_spe ." tc
			where  MAHOSO = ?
		";
		//echo $sql . "$id_hoso"; exit;
		$arr_data = array();
		try{
			//$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->query($sql,array($mahoso));
			$arr_data = $stm->fetch();
			$arr_data["LOAIDON"] = $loaidon;
			//return $arr;
		}catch(Exception $ex){
		
		}
		
		$sql = "
			select tc.* from ". $tble_spe_ds ." tc
			
			where MAHOSO  = ?
		";

		$arr_ds = array();
		try{
			//$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->query($sql,array($mahoso));
			$arr_ds = $stm->fetchAll();
			
			//return $arr;
		}catch(Exception $ex){
		
		}
		//echo $sql; echo $mahoso;exit;
		$arr =array();
		$arr["data"] = $arr_data;
		$arr["danhsach"] = $arr_ds;
		//var_dump($arr); exit;
		return $arr;

		
		

	}

}