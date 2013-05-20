<?php 
	class choxulyModel {
		
		static function SelectAllChoxuly($parameter,$offset,$limit,$order){
			global $db;
			$param = Array();
			$strlimit = "";
			if($limit>0){
				$strlimit = " LIMIT $offset,$limit";
			}
			$sql = "
				SELECT hscv.*,luu.ID_LUUCXL, class1.ALIAS FROM 
				".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv 
				inner join ".QLVBDHCommon::Table("wf_processitems")." wfitem on hscv.ID_PI = wfitem.ID_PI
				inner join ".QLVBDHCommon::Table("HSCV_HOSOLUUCHOXULY")." luu on hscv.ID_HSCV = luu.ID_HSCV
				inner join HSCV_LOAIHOSOCONGVIEC lhs on lhs.ID_LOAIHSCV = hscv.ID_LOAIHSCV
					inner join WF_PROCESSES wfp1 on wfp1.ID_P = wfitem.ID_P
					INNER JOIN WF_CLASSES class1 on class1.ID_C = wfp1.ID_C
				where
					hscv.IS_CHOXULY = 1
					and luu.ID_U = ?
				ORDER BY DATE_CREATE DESC
				$strlimit
			";
			$param[] = $parameter['ID_U'];
			try{
				
				$r = $db->query($sql,$param);
				$result = $r->fetchAll();
				
			}catch(Exception $ex){
				echo $ex->__toString();;
				return null;
			}
			return $result;
		}
		function getLuuxulyInfoByHSCVId($id_hscv){
		
			try{
				$dbAdapter = Zend_Db_Table::getDefaultAdapter();
				$sql="
				SELECT * FROM ".QLVBDHCommon::Table("hscv_hosoluuchoxuly")." WHERE ID_HSCV=?
				";
				$re = $dbAdapter->query($sql,$id_hscv);
				
				return $re->fetch();
			}catch(Exception $ex){
				return array();
			}
		}
	static function luuChoxuly($id_hscv,$comment,$id_u){
		//var_dump($comment); exit;
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		//cap nhat co luu theo hoi cua ho so cong viec
		//kiem tra ho so cong viec co ton tai hay khong
         $sql = "
		select count(*) as DEM  from ".QLVBDHCommon::Table("wf_processlogs")." wflg 
		 inner join ".QLVBDHCommon::Table("hscv_hosocongviec")." hscv on wflg.ID_PI=hscv.ID_PI
		where hscv.ID_HSCV=? and wflg.ID_U_RECEIVE=? and wflg.TRE >0 
		";
		
		$re = array();
		try{
			$query = $dbAdapter->query($sql,array($id_hscv,$id_u));
			$re = $query->fetch();
		}catch(Exception $ex){
			//loi co so du lieu
			return -3;
		}
		
		
		
		
		
		$sql = "
		select IS_CHOXULY  from ".QLVBDHCommon::Table("hscv_hosocongviec")." hscv where ID_HSCV=?
		";
		
		$re = array();
		try{
			$query = $dbAdapter->query($sql,array($id_hscv));
			$re = $query->fetch();
		}catch(Exception $ex){
			//loi co so du lieu
			return -3;
		}
		
		
		$is_theodoi = $re["IS_CHOXULY"];
		
		//if($is_theodoi == 1){
			//ho so cong viec da o trang thai dang theo doi
			//return -2;
		//}
		
		$sql ="
			Update ".QLVBDHCommon::Table("hscv_hosocongviec")." set `IS_CHOXULY`=1 where ID_HSCV=?
		";
		try{
			$stm = $dbAdapter->prepare($sql);
			$stm->execute(array($id_hscv));
			
		}catch(Exception $ex){
			//loi co so du lieu
			return -3;
		}
		
		//them moi vao bang ho so luu theo doi
		
		//check ton tai
		
		$sql="
			SELECT * FROM ".QLVBDHCommon::Table("hscv_hosoluuchoxuly")." WHERE ID_HSCV=?
		";
		$re = $dbAdapter->query($sql,$id_hscv);
		$luutd = $re->fetch();
		//Lay dong luan chuyen hien tai
		$data_wl = WFEngine::GetCurrentTransitionInfoByIdHscv($id_hscv);
		
		$arrdata_cxl = array();
		if($luutd['ID_HSCV']>0){
			$sql="
				UPDATE ".QLVBDHCommon::Table("hscv_hosoluuchoxuly")." SET `COMMENT`=? 
				
				where `ID_HSCV` = ".$luutd['ID_HSCV']."
			";
			$arrdata_cxl = array($comment);
		}else{
			$sql="
				Insert into ".QLVBDHCommon::Table("hscv_hosoluuchoxuly")." (`ID_U`,`ID_HSCV`,`COMMENT`,`DATE_CREATE`,ID_PL_CUR,HANXULY) values (?,?,?,'".date("Y-m-d H:i:s")."',?,?) 
			";
			$arrdata_cxl = array($id_u,$id_hscv,$comment,$data_wl["ID_PL"],$data_wl["HANXULY"]);
		}
		
		

		try{
			if($data_wl["ID_PL"]){
				
				$stm = $dbAdapter->prepare($sql);
				$stm->execute($arrdata_cxl);
				/*
					Cập nhật lại wf_poccess log
				*/
				$sql = "  update ". QLVBDHCommon::Table("wf_processlogs"). " 
						  set
							  HANXULY = 0
						  where ID_PL = ?
				
				";
				$stm = $dbAdapter->prepare($sql);
				$stm->execute(array($data_wl["ID_PL"]));

				
				return $dbAdapter->lastInsertId(QLVBDHCommon::Table("hscv_hosoluuchoxuly"),'ID_LUUCXL');
			}
		}catch(Exception $ex){
			return 0;
		}		
      

	}

	static function phuchoiluuChoxuly($id_luucxl,$idu,$id_hscv){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		//lay thong tin ve ho so luu theo doi

		
		$sql ="
			select hs.* from ".QLVBDHCommon::Table("HSCV_HOSOLUUCHOXULY")." hs where ID_LUUCXL=? and ID_U=?
		";
		$re = array();
		try{
			$query = $dbAdapter->query($sql,array($id_luucxl,$idu));
			$re = $query->fetch();
			
		}catch (Exception $ex){
			//loi sql 
			return -3;
		}
		if(count($re) == 0){
			//khong tim thay ho so luu theo doi
			return -1;
		}
		if(!($re["ID_HSCV"] > 0)){
			//khong tim thay ho so cong viec tuong ung
			return -2;
		}
		
		try{
			$dbAdapter->update(QLVBDHCommon::Table("HSCV_HOSOCONGVIEC"),array("IS_CHOXULY"=>0),"ID_HSCV=".$re["ID_HSCV"]);
			
		}catch(Exception $ex){
			//echo $ex;
			return -3;
		}
		
		//xoa ho so luu theo doi trong danh sach
		try{
			
			if($re["ID_PL_CUR"]){
				$sql = "  update ". QLVBDHCommon::Table("wf_processlogs"). " 
							  set
								  HANXULY = ?
							  where ID_PL = ?
					
				";
				//Tinh han xu ly moi
				$ngay = strtotime($re["DATE_CREATE"]);
				$freedate = new Zend_Session_Namespace('freedate');
				$free = $freedate->free;
				//var_dump($free); exit;
				$delay = QLVBDHCommon::countdate($ngay,$free);
				//echo $delay; exit;
				$hxl_moi =  ($delay/8) + $re["HANXULY"];  
				$stm = $dbAdapter->prepare($sql);
				$stm->execute(array($hxl_moi,$re["ID_PL_CUR"]));
			}
			$dbAdapter->delete(QLVBDHCommon::Table("HSCV_HOSOLUUCHOXULY"),"ID_LUUCXL=".$re["ID_LUUCXL"]);
		}catch(Exception $ex){
			//loi co so du lieu
			return -3;
		}
	}

	
	}
?>