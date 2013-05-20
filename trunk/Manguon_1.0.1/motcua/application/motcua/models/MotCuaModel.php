<?php
class MotCuaModel {

	/*
	note:
		1. Viet hàm filter parametters
	*/
	
	function filterMotcuaParams($parameter){
		/*param nhận vào
			1. Trích yếu hồ sơ
			2. Tên tổ chức cá nhân
			3. Phường
			4. Loại hồ sơ
			5. Ngày tiếp nhận
			6. Ngày hẹn trả
			7. Ngày trả thực tế
		*/
		$where = array();
		$param = array();
		$is_ser_mc = 0;
		$is_no_param = 1;
		//trich yếu hồ sơ công việc
		if($parameter['NAME']!=""){
			$wheretemp = "";
			if($parameter['INNAME']==1){
				Common_DBUtils::repairTableBeforeFulltextSearch(QLVBDHCommon::Table('HSCV_HOSOCONGVIEC'));
				$wheretemp = " match(hscv.NAME) against (? IN BOOLEAN MODE)";
				$order_sub = " match(hscv.NAME) against ('".str_replace("'","''",$parameter['NAME'])."') DESC";
				$param[] = $parameter['NAME'];
				$where[] .= "  (".$wheretemp.($parameter['INNAME']==1 && $parameter['INFILE']==1?" OR ":")");
				$is_no_param = 0;
			}
			if($parameter['INFILE']==1){
				/*Common_DBUtils::repairTableBeforeFulltextSearch(QLVBDHCommon::Table('GEL_FILEDINHKEM'));
				$innerjoin .= " left join hscv_duthao_".$realyear." dt on dt.ID_HSCV = hscv.ID_HSCV";
				$innerjoin .= " left join hscv_phienbanduthao_".$realyear." pbdt on pbdt.ID_DUTHAO = dt.ID_DUTHAO";
				$innerjoin .= " left join gen_filedinhkem_".$realyear." dk on dk.ID_OBJECT = pbdt.ID_PB_DUTHAO and dk.TYPE=2";
				$wheretemp = " match(dk.CONTENT) against (? IN BOOLEAN MODE)";
				$order_sub = " match(dk.CONTENT) against ('".str_replace("'","''",$parameter['NAME'])."') DESC";
				$param[] = $parameter['NAME'];
				$where .= ($parameter['INNAME']==1 && $parameter['INFILE']==1?"":" and (").$wheretemp.")";*/
			}
		}
		
		// loại hồ sơ công việc 
		if($parameter['ID_LOAIHOSO']){
				$is_ser_mc = 1;
				$id_loaihoso = (int)($parameter['ID_LOAIHOSO']);
				$where[] = " mc.ID_LOAIHOSO = ? ";
				$param[] = $id_loaihoso;
				$is_no_param = 0;
				
		}

		if((int)$parameter['PHUONG'] > 0){
			$is_ser_mc = 1;
			$phuong = (int)$parameter['PHUONG'];
			$where[] = " mc.PHUONG = ?";
			$param[] = $phuong;
			$is_no_param = 0;
		}

		//Tìm theo ngày tiếp nhận
		if($parameter['NHAN_NGAY_BD']!=""){
			$is_ser_mc = 1;
			$ngayden_bd = $parameter['NHAN_NGAY_BD'];
			$where[] = " mc.NHAN_NGAY >= ?";
			$param[] = $ngayden_bd;
			$is_no_param = 0;
		}
		
		

		if($parameter['NHAN_NGAY_KT']!=""){
			$is_ser_mc = 1;
			$ngayden_bd = $parameter['NHAN_NGAY_KT'];
			$where[]= "  mc.NHAN_NGAY <= ?";
			$param[] = $ngayden_bd;
			$is_no_param = 0;
		}

		//ngày hẹn trả
		if($parameter['NHANLAI_NGAY_BD']!=""){
			$is_ser_mc = 1;
			$ngayden_bd = $parameter['NHANLAI_NGAY_BD'];
			$where[] = " mc.NHANLAI_NGAY >= ?";
			$param[] = $ngayden_bd;
			$is_no_param = 0;
		}

		if($parameter['NHANLAI_NGAY_KT']!=""){
			$is_ser_mc = 1;
			$ngayden_bd = $parameter['NHANLAI_NGAY_KT'];
			
			$where[] = " mc.NHANLAI_NGAY <= ?";
			$param[] = $ngayden_bd;
			$is_no_param = 0;
		}
		
		$arr_result = array();
		$arr_result["where"] = $where;
		$arr_result["param"] = $param;
		$arr_result["is_ser_mc"] = $is_ser_mc;
		$arr_result["is_no_param"]  = $is_no_param;
		return $arr_result;
	}

	
	function countAll($parameter){
		
		$where = "(1=1)";
		$param = array();
		$auth = Zend_Registry::get('auth');
		$user = $auth->getIdentity();
		//Check mã số hồ sơ một cửa
		$is_ser_mc = 0;
		
		$arr_filter_params = MotCuaModel::filterMotcuaParams($parameter);
		$is_ser_mc = $arr_filter_params['is_ser_mc'];
		if($is_ser_mc)
			$inner_joinmc = " inner join  ".QLVBDHCommon::Table("motcua_hoso"). " mc on hscv.ID_HSCV = mc.ID_HSCV";
		$is_no_param = $arr_filter_params["is_no_param"];
		if($is_no_param){
			return -1;
		}
		
		$where_arr = $arr_filter_params['where'];
		if(count($where_arr)){
			$where = implode(" and " , $where_arr );
		}
		if($where){
			$where .= "and";
		}
		$param = $arr_filter_params['param'];
		//var_dump($param); exit;
		$strlimit = "";
		if($limit>0){
			$strlimit = " LIMIT $offset,$limit";
		}
		//var_dump($parameter); exit;
		if($parameter["ID_TTHS"] >0){
			
			$arr_tths = WFEngine::getAllTrangthaihosoByCurrentUser();
			$count_bshs = 0;
			foreach($arr_tths as $it_tths){
				if($it_tths["ID_TTHS"] == $parameter["ID_TTHS"] ){
					if($it_tths["LA_CHOBOSUNG"] && $it_tths["THUOCTOMOTCUA"]){
						$is_bosung = 1;
					}
					if($it_tths["LA_CHOBOSUNG"] && !$it_tths["THUOCTOMOTCUA"]){
						$is_chobosung = 1;
					}
					break;
				}
			}
			if($is_bosung){
				//return MotCuaModel::SelectAllBosunghoso($parameter,$offset,$limit,$order);
			
			}else{
				
				if($is_chobosung){
					$sql = "
						select count(distinct hscv.ID_HSCV) as CNT  from 
						".QLVBDHCommon::Table("hscv_hosocongviec")."  hscv 
						$inner_joinmc
						inner join ".QLVBDHCommon::Table("wf_trangthaihosologs")." tths on hscv.ID_HSCV = 
						tths.ID_HSCV
						where $where tths.ID_U = ?  and IS_CHOBOSUNG=1 
					";
					$arrwhere = ($param);
					$arrwhere[] = $user->ID_U;
					//$arrwhere = array_merge($param,$param);
					//$arrwhere[] = $user->ID_U;
					//$arrwhere[] = $parameter["ID_TTHS"];
				
				}else{
					$sql = "
						select count(distinct hscv.ID_HSCV) as CNT  from 
						".QLVBDHCommon::Table("hscv_hosocongviec")."  hscv 
						$inner_joinmc
						inner join ".QLVBDHCommon::Table("wf_trangthaihosologs")." tths on hscv.ID_HSCV = tths.ID_HSCV
						where $where  tths.ID_U = ? and tths.ACTIVE = 1 and ID_TTHS = ?
						
					";
					$arrwhere = ($param);
					$arrwhere[] = $user->ID_U;
					$arrwhere[] = $parameter["ID_TTHS"];
					
				}
			}
		}
		
		
		if($parameter["ID_TTHS"] ==-1){
			$sql = "
				select count(distinct hscv.ID_HSCV) as CNT  from 
				".QLVBDHCommon::Table("hscv_hosocongviec")."  hscv
				$inner_joinmc
				inner join ".QLVBDHCommon::Table("wf_trangthaihosologs")." tths on hscv.ID_HSCV = tths.ID_HSCV
				where $where tths.ID_U = ? and tths.ACTIVE = 0
				
			";
			$arrwhere = ($param);
			$arrwhere[] = $user->ID_U;
			
		}
		$db = Zend_Db_Table::getDefaultAdapter();
		$result = $db->query($sql,$arrwhere);
		$re = $result->fetch();
		return $re["CNT"];
	}

	function SelectAll($offset,$limit,$parameter){
		
		//Build phần where
		$where = "(1=1) ";
		$param = array();
		$auth = Zend_Registry::get('auth');
		$user = $auth->getIdentity();
		//Check mã số hồ sơ một cửa
		$is_ser_mc = 0;
		
		$arr_filter_params = MotCuaModel::filterMotcuaParams($parameter);
		$is_ser_mc = $arr_filter_params['is_ser_mc'];
		if($is_ser_mc)
			$inner_joinmc = " inner join  ".QLVBDHCommon::Table("motcua_hoso"). " mc on hscv.ID_HSCV = mc.ID_HSCV";
		
		$where_arr = $arr_filter_params['where'];
		if(count($where_arr)){
			$where = implode(" and " , $where_arr );
		}
		if($where){
			$where .= "and";
		}
		$param = $arr_filter_params['param'];
		//var_dump($param); exit;
		$strlimit = "";
		if($limit>0){
			$strlimit = " LIMIT $offset,$limit";
		}
		//var_dump($parameter); exit;
		if($parameter["ID_TTHS"] >0){
			
			$arr_tths = WFEngine::getAllTrangthaihosoByCurrentUser();
			$count_bshs = 0;
			foreach($arr_tths as $it_tths){
				if($it_tths["ID_TTHS"] == $parameter["ID_TTHS"] ){
					if($it_tths["LA_CHOBOSUNG"] && $it_tths["THUOCTOMOTCUA"]){
						$is_bosung = 1;
					}
					if($it_tths["LA_CHOBOSUNG"] && !$it_tths["THUOCTOMOTCUA"]){
						$is_chobosung = 1;
					}
					break;
				}
			}
			if($is_bosung){
				return MotCuaModel::SelectAllBosunghoso();
			
			}else{
				
				if($is_chobosung){
					$sql = "
						select hscv.*  from 
						".QLVBDHCommon::Table("hscv_hosocongviec")."  hscv 
						$inner_joinmc
						inner join ".QLVBDHCommon::Table("wf_trangthaihosologs")." tths on hscv.ID_HSCV = 
						tths.ID_HSCV
						where $where tths.ID_U = ?  and IS_CHOBOSUNG=1 
						group by ID_HSCV
						$strlimit
					";
					$arrwhere = ($param);
					$arrwhere[] = $user->ID_U;
					//$arrwhere = array_merge($param,$param);
					//$arrwhere[] = $user->ID_U;
					//$arrwhere[] = $parameter["ID_TTHS"];
				
				}else{
					$sql = "
						select hscv.* , (tths.PRE_SEQ)  from 
						".QLVBDHCommon::Table("hscv_hosocongviec")."  hscv 
						$inner_joinmc
						inner join ".QLVBDHCommon::Table("wf_trangthaihosologs")." tths on hscv.ID_HSCV = tths.ID_HSCV
						where $where  tths.ID_U = ? and tths.ACTIVE = 1 and ID_TTHS = ?
						$strlimit
					";
					$arrwhere = ($param);
					$arrwhere[] = $user->ID_U;
					$arrwhere[] = $parameter["ID_TTHS"];
					
				}
			}
		}
		
		
		if($parameter["ID_TTHS"] <=-1){
			$sql = "
				select hscv.* , (max(tths.PRE_SEQ)+1) as PRE_SEQ  from 
				".QLVBDHCommon::Table("hscv_hosocongviec")."  hscv
				$inner_joinmc
				inner join ".QLVBDHCommon::Table("wf_trangthaihosologs")." tths on hscv.ID_HSCV = tths.ID_HSCV
				where $where tths.ID_U = ? and tths.ACTIVE = 0
				group by ID_HSCV
				$strlimit
			";
			$arrwhere = ($param);
			$arrwhere[] = $user->ID_U;
			
		}
		//echo $sql;exit;
		$db = Zend_Db_Table::getDefaultAdapter();
		$result = $db->query($sql,$arrwhere);
		return $result->fetchAll();
	}

	function SelectAllBosunghoso($parameter,$offset,$limit,$order){
	
			global $db;
			$user = Zend_Registry::get('auth')->getIdentity();
			
			$where = "  ";
			$param = array();
			$param[] = $user->ID_U;
			if($parameter['TENTOCHUCCANHAN']!=""){
				$wheretemp = "";
				Common_DBUtils::repairTableBeforeFulltextSearch(QLVBDHCommon::Table('MOTCUA_HOSO'));
				$wheretemp = " match(mc.TENTOCHUCCANHAN) against (? IN BOOLEAN MODE)";
				$order = " match(mc.TENTOCHUCCANHAN) against ('".str_replace("'","''",$parameter['TENTOCHUCCANHAN'])."') DESC";
				$param[] = $parameter['TENTOCHUCCANHAN'];
				$where .= " and (".$wheretemp.")";
			}
			
		//check phuong
			if($parameter['ID_PHUONG']!=0){
				if($parameter['ID_PHUONG']==-1){
					$where .= " and mc.PHUONG is null";
				}else{
					$param[] = $parameter['ID_PHUONG'];
					$where .= " and mc.PHUONG = ?";
				}
			}
		//
		if($parameter['MAHOSO']!=""){
				$wheretemp = "";
				$wheretemp = " mc.MAHOSO = ? ";
				$param[] = $parameter['MAHOSO'];
				$where .= " and (".$wheretemp.")";
			}
			
		

		//$param = Array();
		$sql = "
				SELECT hscv.*, mc.PHUONG , mc.TENTOCHUCCANHAN, mc.MAHOSO,  max(bs.ID_YEUCAU) as ID_YEUCAU FROM 
					".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv 
				inner join ".  QLVBDHCommon::Table("MOTCUA_HOSO") ." mc on hscv.ID_HSCV =  mc.ID_HSCV
				inner join motcua_loai_hoso loaihs on mc.ID_LOAIHOSO = loaihs.ID_LOAIHOSO 
				inner join ( select lv.* from motcua_linhvuc lv inner join motcua_linhvuc_quyen pq on lv.ID_LV_MC = pq.ID_LV_MC   where ID_U = ?) 
				linhvuchs on loaihs.ID_LV_MC = linhvuchs.ID_LV_MC
				inner join ".  QLVBDHCommon::Table("HSCV_PHIEU_YEUCAU_BOSUNG") ." bs on hscv.ID_HSCV = bs.ID_HSCV
				GROUP BY hscv.ID_HSCV having hscv.IS_BOSUNG = 1
				$where
				$strlimit
			";
			//
			try{
				//echo $sql;
				$r = $db->query($sql,$param);
				$result = $r->fetchAll();
				//var_dump($param);
			}catch(Exception $ex){
				echo $ex->__toString();;
				return null;
			}
			return $result;
	}

	function SelectAllChoBosungUser(){
		$sql = "
			select * from 
		";
	}

	function GetAllLoais($idlvmc=null)
	{
	
		if($idlvmc==""){
		
		$r = Zend_Db_Table::getDefaultAdapter()->query("
				SELECT
					ID_LOAIHOSO,TENLOAI,ID_LOAIHSCV, lv.NAME as NAME_LV
				FROM
					MOTCUA_LOAI_HOSO loai
				INNER JOIN MOTCUA_LINHVUC lv on loai.ID_LV_MC = lv.ID_LV_MC 
				order by  NAME_LV, TENLOAI
				");
			return $r->fetchAll();
		}else{
			$r = $this->getDefaultAdapter()->query("
				SELECT
					ID_LOAIHOSO,TENLOAI,ID_LOAIHSCV, lv.NAME as NAME_LV
				FROM
					MOTCUA_LOAI_HOSO loai
				INNER JOIN MOTCUA_LINHVUC lv on loai.ID_LV_MC = lv.ID_LV_MC 
				WHERE ID_LV_MC = ?
				order by  NAME_LV, TENLOAI
				",$idlvmc);
			return $r->fetchAll();
		}

		
	}

	 public function ToCombo($data,$sel){
        $html="";
        $html .= "<option value='0'>--Chọn loại thủ tục--</option>";
        foreach($data as $row){
            $html .= "<option value='".$row["ID_LOAIHOSO"]."' ".($row["ID_LOAIHOSO"]==$sel?"selected":"").">".$row["NAME_LV"]."----".$row["TENLOAI"]."</option>";
        }
        return $html;
    }

}
?>