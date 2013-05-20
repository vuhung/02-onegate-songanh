<?php

class Common_Maso {
	
	static function getLastSubStr($str,$length){
		$len_str = strlen($str);
		
		if($len_str < $length){//truong hop length lon hon chieun dai chuoi -> them so khong dau chuoi
			$d = $length- $len_str;
			$str_add = str_repeat('0',$d);
			return $str_add.$str;
		}
		if($len_str > $length){//truong hop length dai hon->lay cac ky tu ben phai
			$l = 0 - $length;
			return substr($str,-2);
		}
		return $str;
		//return substr($str,-);
	}
	static function getYear($length){
		
		$year = QLVBDHCommon::getYear();
		//return $year;
		return Common_Maso::getLastSubStr($year,$length);
		
	}
	static function getMaCoQuanNoiBo($length){
		
		$dbApdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select CODE from `vb_coquan` where `ISSYSTEMCQ` = 1
		";
		$qr = $dbApdapter->query($sql);
		$re = $qr->fetch();
		
		return Common_Maso::getLastSubStr($re["CODE"],$length) ;
	}
	static function getTinhThanhNoiBo($length){
		$dbApdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select CODE from `qtht_tinhthanh` where `ISLOCAL` = 1
		";
		$qr = $dbApdapter->query($sql);
		$re = $qr->fetch();
		return Common_Maso::getLastSubStr($re["CODE"],$length);
	}
	
	static function getMaLoaiHoSoMotCua($idLoaiHSMC,$length){
		$dbApdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select CODE from `motcua_loai_hoso` where `ID_LOAIHOSO` = ?
		";
		$qr = $dbApdapter->query($sql,array($idLoaiHSMC));
		$re = $qr->fetch();
		//echo $idLoaiHSMC;
		return Common_Maso::getLastSubStr($re["CODE"],$length);
	}
	static function getMaPhongBan($idPhongBan,$length){
		$dbApdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select CODE_DEP from `qtht_departments` where `ID_DEP` = ?
		";
		$qr = $dbApdapter->query($sql,array($idPhongBan));
		$re = $qr->fetch();
		return Common_Maso::getLastSubStr($re["CODE_DEP"],$length);
	}
	static function getSoCoDinh($value,$length){
		return Common_Maso::getLastSubStr($value,$length);
	}
	static function getSoKhongCoDinh($func_tr,$length){
		//chua lam
		return Common_Maso::getLastSubStr("999",$length);
	}
	static function getChuoiCoDinh($value,$length){
		return Common_Maso::getLastSubStr($value,$length);
	}
	static function getChuoiKhongCoDinh($func_tr,$length){
		//chua lam
		return Common_Maso::getLastSubStr("CODE",$length);
	}
	static function getFieldOnDb($loai,$field,$length,$object){
		
		switch ($loai)
		{
				case 1: // van ban den  
					{
						
						switch($field){
							case 1: //ma co quan ban hanh
								return  Common_Maso::getMaCoQuan($object->_id_cq,$length);
								break;
							case 2: //ma loai van ban
								return  Common_Maso::getMaLoaiVanBan($object->_id_lvb,$length);
								break;
							case 3: //do mat
								return Common_Maso::getLastSubStr($object->_domat,$length);
								break;
							case 4: //do khan
								return Common_Maso::getLastSubStr($object->_dokhan,$length);
								break;
							case 5: // so den
								return Common_Maso::getLastSubStr($object->_soden,$length);
								break;
 							default:
								break;
						}
					}
					break;
				case 2: //van ban di
				{
					switch($field){
							
							case 1: //ma loai van ban
								return  Common_Maso::getMaLoaiVanBan($object->_id_lvb,$length);
								break;
							case 2: //do mat
								return Common_Maso::getLastSubStr($object->_domat,$length);
								break;
							case 3: //do khan
								return Common_Maso::getLastSubStr($object->_dokhan,$length);
								break;
							case 4: // so den
								return Common_Maso::getLastSubStr($object->_sodi,$length);
								break;
 							default:
								break;
						}		
				}
					break;
				case 3: // ho so mot cua
				switch($field){
							
							case 1: //ma loai ho so 
								return  Common_Maso::getMaLoaiHoSoMotCua($object->_id_loaihoso,$length);
								break;
							case 2: //ma phong ban
								return Common_Maso::getMaPhongBan($object->_id_phongban,$length);
								break;
							case 3: //so thu tu
								return Common_Maso::getLastSubStr($object->_sothutu,$length);
								break;
							
 							default:
								break;
						}		
					break;
				default: //khong co
					break;
			}
	}
	static function getMaSo($loai , $object){
	//lay thong tin ve cac truong trong ma so
		$dpapAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "Select * 
		from `gen_maso` ms
		where ms.`LOAI` = ?
		order by ms.`ORDER`  ASC
		";
		$qr = $dpapAdapter->query($sql,array($loai));
		$re = $qr->fetchAll();
		
		$str_maso = "";
		//var_dump($object);
		//echo count($re);
		$dem =1;
		
		foreach ($re as $row){
			//echo "<br>";
			//var_dump($row);
			//echo "</br>";
			$length = $row['LENGTH'];
			//echo $length;
			$str_item="";
			switch($row['KIEU']){
				case 1 : //nam
					$str_item = Common_Maso::getYear($length);
					continue;
				case 2 : // ma co quan noi bo
					$str_item = Common_Maso::getMaCoQuanNoiBo($length);
					continue;
				case 3 : // ma tinh thành noi bo
					$str_item = Common_Maso::getTinhThanhNoiBo($length);
					continue;
				case 4 : // so co dinh
					$str_item = Common_Maso::getSoCoDinh($row['VALUE'],$length);
					continue;
				case 5 : // so khong co dinh
					$str_item = Common_Maso::getSoKhongCoDinh($row['PHP_FUNC'],$length);
					continue;
				case 6 : //chuoi co dinh
					$str_item = Common_Maso::getChuoiCoDinh($row['VALUE'],$length);
					continue;
				case 7 : // chuoi khong co dinh
					$str_item = Common_Maso::getChuoiKhongCoDinh($row['PHP_FUNC'],$length);
					continue;
				case 8 : // truong co trong co so du lieu
				{
					//Them cac truong co trong co so du lieu
					$str_item = Common_Maso::getFieldOnDb($row['LOAI'],$row['FIELD'],$length,$object);
					//echo $dem;	
				}	
				continue;
				
			}
			
			//echo "----";
			//echo $str_item;
			$str_maso .=$str_item;
		}
		return $str_maso;
	}
/**
 * lay cac truong co trong co so du lieu
 */
//van ban den
	
	static function getMaLoaiVanBan($id_lvb,$length){
		$dbApdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select CODE from `vb_loaivanban` where `ID_LVB` = ?
		";
		$qr = $dbApdapter->query($sql,array($id_lvb));
		$re = $qr->fetch();
		return Common_Maso::getLastSubStr($re["CODE"],$length)  ;
	}
	
	static function getMaCoQuan($id_cq,$length){
		
		$dbApdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select CODE from `vb_coquan` where `ID_CQ` = ?
		";
		$qr = $dbApdapter->query($sql,array($id_cq));
		$re = $qr->fetch();
		
		return Common_Maso::getLastSubStr($re["CODE"],$length) ;
	}
	
	static function getIdDepByUser($id_u){
		$dbApdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select d.`ID_DEP` from
			(`qtht_users` u inner join `qtht_employees` e on u.`ID_EMP` = e.`ID_EMP`)
			join `qtht_departments` d
			where e.`ID_DEP` = d.`ID_DEP` and u.`ID_U` = ?
		";
		$qr = $dbApdapter->query($sql,array($id_u));
		$re = $qr->fetch();
		
		return $re["ID_DEP"] ;
	}
	
}


?>
