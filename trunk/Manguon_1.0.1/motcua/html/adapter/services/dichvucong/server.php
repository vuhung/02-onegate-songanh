<?php
require_once '../../lib/nusoap.php';

require_once '../../config.php';
require_once '../../db/connection.php';
require_once '../../db/common.php';
require_once '../../models/BussinessDateModel.php';

$soap = new soap_server ( );
$soap->configureWSDL ( 'SoNoiVuDaNang', 'http://php.hoshmand.org/' );
$soap->wsdl->schemaTargetNamespace = 'http://soapinterop.org/xsd/';


$soap->register ( 'gethoso', 
		array ('username' => 'xsd:string', 
			   'password' => 'xsd:string',
			   'masodichvu'=> 'xsd:string',
			   'rootname' => 'xsd:string',
			   'itemname' => 'xsd:string'

), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'getMasohoso', 
		array ('username' => 'xsd:string', 
			   'password' => 'xsd:string',
			   'madichvu' => 'xsd:string'
), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/',false,'rpc','encoded','Dịch vụ cung cấp mã số hồ sơ' );

$soap->register ( 'dongBoLoaiHoSoMotCua', 
		array ('username' => 'xsd:string', 
			   'password' => 'xsd:string',
			   'data' => 'xsd:string'
), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/',false,'rpc','encoded','Dịch vụ đồng bộ danh mục loại hồ sơ' );
$soap->register ( 'dongBoLichLamViec', 
		array ('username' => 'xsd:string', 
			   'password' => 'xsd:string',
			   'data' => 'xsd:string'
), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/',false,'rpc','encoded','Dịch vụ đồng bộ danh mục loại hồ sơ' );

$soap->register ( 'insertdanhmuc', array ('username' => 'xsd:string'
								  ,'password' => 'xsd:string'
								  , 'code'=>'xsd:string' 
								  , 'xml' => 'xsd:string'     ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ('getDanhMucLoaiHoSo', 
                 array (
                    'username' => 'xsd:string',
                    'password' => 'xsd:string'    
                    ),
                 array (
                    'sid' => 'xsd:string' ),
                 'http://soapinterop.org/'
                );

$soap->register ( 'sendFile', array ('username' => 'xsd:string'
								  ,'password' => 'xsd:string'
								  , 'mahoso'=>'xsd:string' 
								  , 'content'=>'xsd:string' 
								  , 'fileName'=>'xsd:string' 
								  , 'fileSize'=>'xsd:string' 
								  , 'mime'=>'xsd:string'
								  , 'id_part'=>'xsd:string'
								  , 'part_number'=>'xsd:string'  ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/',false,'rpc','encoded','Dich vụ gửi tập tin' );

$soap->register ( 'getAllFiles', array ('username' => 'xsd:string'
								  ,'password' => 'xsd:string'
								  ,'role' => 'xsd:string'  ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'sendDataDichVuCong', array ('username' => 'xsd:string'
												,'password' => 'xsd:string' 
												, 'mahoso'=>'xsd:string' 
												, 'madichvu'=>'xsd:string'
												, 'xml' => 'xsd:string'     ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/',false,'rpc','encoded','Dịch vụ gửi dữ liệu hồ sơ dịch vụ công' );

$soap->register ( 'inserttrangthai', array ('username' => 'xsd:string'
								  ,'password' => 'xsd:string'
								  , 'masohoso'=>'xsd:string' 
								  , 'tentochuccanhan' => 'xsd:string' 
								  , 'tenhoso' => 'xsd:string' 
								  , 'trangthai' => 'xsd:string' 
								  , 'phong' => 'xsd:string' 
								  , 'ghichu' => 'xsd:string' 
								  , 'dienthoai' => 'xsd:string' 
								  , 'barcode' => 'xsd:string' 
								  , 'ngaynop' => 'xsd:string' 
								  , 'ngaynhan' => 'xsd:string' 
								  ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'selecttrangthai', array ('username' => 'xsd:string'
								  ,'password' => 'xsd:string'
								  , 'masohoso'=>'xsd:string' 
								  ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/',false,'rpc','encoded','Dịch vụ tra cứu trạng thái hồ sơ' );

$soap->register ( 'checkHoSo', array (
								'username' => 'xsd:string',
								'password' => 'xsd:string',
								'role' => 'xsd:string'), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->service ( isset ( $HTTP_RAW_POST_DATA ) ? $HTTP_RAW_POST_DATA : '' );

function createXML(&$doc,&$parent,$data,$itemname){
  
		$fieldvalue = $data[$itemname];

		$child = $doc->createElement($itemname);
        $child = $parent->appendChild($child);
		$child->setAttribute("MAHOSO",$data["MAHOSO"]);
		$child->setAttribute("MADICHVU",$data["MADICHVU"]);

		$value = $doc->createTextNode(base64_encode($fieldvalue));
		$value = $child->appendChild($value);

}
/*	 _________| BAOTQ COMMENT |_________________________________
	|	Ham viet cho QLVBDH dong bo du lieu dich vu cong		|
	|___________________________________________________________|
*/

function gethoso($username, $password,$masodichvu,$noderoot,$nodeitem) {

	if(checkAuthentication($username, $password)){
		try{
				if(!$noderoot){
					$noderoot = "root";
				}
				if(!$nodeitem){
					$nodeitem = "SOURCEXML";
				}
				$sql = sprintf("
					select ID_LUUTRU,MAHOSO,MADICHVU,SOURCEXML as $nodeitem from adapter_luutru where IS_GET = 0
				");
				//return $sql;
				$result = query($sql);
				$doc = new DomDocument('1.0', 'UTF-8');
				$root = $doc->createElement($noderoot);
				$root = $doc->appendChild($root);
				while($row = mysql_fetch_assoc($result)){					
					 createXML($doc,$root,$row,$nodeitem);
					 //update is_get
					 $sql = sprintf("
						update adapter_luutru SET IS_GET = 1 where MAHOSO = '%s'
					 ",$row["MAHOSO"]);
					 query($sql);
				}
				$xml_string = $doc->saveXML();
				WriteLog('gethoso',"return Data to QLVBDH");
				return ($xml_string);
		}catch(Exception $ex){
			WriteLog('gethoso',$ex->getMessage());
			return $ex->__toString();
		}
	}
	
}

/*	 _________|_BAOTQ 11:58 - 14/10/2011_|______________________
	|	Ham viet cho QLVBDH goi dich vu							|
	|	Kiem tra so luong ho so can dong bo						|
	|___________________________________________________________|
*/
function checkHoSo($username,$password,$role) {
    if(checkAuthenticationWithRole($username, $password, "QLVBDH@f1")){
        $sql = sprintf("
                    select COUNT(*) as DEM from adapter_luutru where IS_GET = 0
                ");
        $result = query($sql);
        $cnt = mysql_fetch_assoc($result);
        return $cnt['DEM']; 
    }
}
/*	 _________|_BAOTQ 11:58 - 03/03/2011_|______________________
	|	Ham viet cho website goi dich vu						|
	|	Lay ma so ho so											|
	|___________________________________________________________|
*/
function getMasohoso($username, $password,$madichvu)
{
    
	WriteLog('====================','START	============================');
	WriteLog('getMasohoso','Call');    
	if(checkAuthentication($username, $password)){
        if(checkMaDichVu($madichvu) == false){
            WriteLog('getMasohoso failed : ','madichvu is invalid ('.$madichvu.') ');
            return "";
        }
		WriteLog('getMasohoso','Lgin Success');
        $maDonVi = common ::getLastSubStr(MADONVI,3);
        $maDichVu = common ::getLastSubStr((int)$madichvu,3);
        $sql = "select MAX(STT) as STT from adapter_luutru where YEAR=".date('Y')." and MADICHVU='".
        mysql_real_escape_string($madichvu)."'";
        $rs = query($sql);
		$row = mysql_fetch_assoc($rs);
        $thuTuHoSo = (int)$row["STT"] + 1;
        
		$pre = '';
		
        $maHeThong = MAHETHONG;
        $namTaiChinh = date("y");
        $tempSTT = $thuTuHoSo + 5000;
        $tempSTT = common::getLastSubStr($tempSTT,4);
        $maSoHoSo = $maDonVi.$maDichVu.$tempSTT.$namTaiChinh;
        $maSoHoSo .= common::getMaKiemTra($maSoHoSo);
        //IS_GET = 9 : Ho so chua sang sang de tiep nhan
		
		$sql = sprintf("
				insert into adapter_luutru
				( `SOURCEXML`,`MAHOSO`,`MADICHVU`,`IS_GET`,`YEAR`,`STT` )values('','".$maSoHoSo."','".mysql_real_escape_string($madichvu)."',9,".date('Y').",".$thuTuHoSo.")");
		try{
			query($sql);
		}catch(Exception $ex){
			WriteLog('getMasohoso',$ex->getMessage());
		}
		WriteLog('getMasohoso','return '.$maSoHoSo);
		return $maSoHoSo;
	}else{
		WriteLog('getMasohoso','Lgin Failed');
		return -1;
	}	
}

/*	 _________|_BAOTQ 9:26 - 26/02/2011_|_______________________
	|	Ham viet cho website goi dich vu						|
	|	Them moi hoac cap nhat cac ho so dich vu cong			|
	|___________________________________________________________|
*/
function sendDataDichVuCong($username, $password,$mahoso,$madichvu,$xml){
	WriteLog('sendDataDichViCong','Call');	
	if(checkAuthentication($username, $password)){
		WriteLog('sendDataDichViCong','Login Sucess');
        if(checkMaDichVu($madichvu) == false){
            WriteLog('sendDataDichVuCong failed : ','madichvu is invalid ('.$madichvu.') ');
            return 0;
        }
        
		$id = 0;
        if($xml == ""){
            WriteLog("sendDataDichVuCong : ", "XML NULL");
            return 0;
		}
        $sql = sprintf("
			select count(*) as DEM from adapter_luutru where MAHOSO = '%s'
		",mysql_real_escape_string($mahoso));
		
		$re = query($sql);
		$row = mysql_fetch_assoc($re);
		$xml = str_replace("'",'"',$xml);
		if((int)$row["DEM"]){
			/* cap nhat */
            $sql = sprintf("
            update adapter_luutru
            SET `SOURCEXML` = '%s'  ,`MADICHVU` = '%s',`IS_GET` = 0 
            WHERE MAHOSO = '%s'
            ",$xml,$madichvu,$mahoso);		
			query($sql);
			/* Lay cac thong tin de gui mail*/
			$sql1 = sprintf("select TENLOAI,SONGAYXULY from adapter_loai_hoso where CODE = '%s'",$madichvu);
			$re = query($sql1);
			$row = mysql_fetch_assoc($re);
			 
			$tenloaihoso = $row["TENLOAI"];
			$soNgayXuLy = $row["SONGAYXULY"];
            $ngayNhanToTime = BussinessDateModel::addDate(strtotime(date('Y-m-d')),(int)$soNgayXuLy);
            $ngayNhan = date('Y-m-d',$ngayNhanToTime); 
			/*Lay ngay nhan ho so*/
			$today = date("d/m/Y");
			/*Mang cac thong tin ho so*/
			$arrayInfo = XmlToArray($xml);
            
            try{
                $sqlTrangThai = sprintf("Insert into adapter_trangthaihoso(MASOHOSO, TENTOCHUCCANHAN, TENHOSO, TRANGTHAI,DIENTHOAI,NGAYNOP,NGAYNHAN) VALUES ('%s', '%s','%s',%d,'%s','%s','%s') ",$mahoso,base64_decode($arrayInfo['TENTOCHUCCANHAN']),$tenloaihoso,0,base64_decode($arrayInfo['DIENTHOAI']),date('Y-m-d H:i:s'),$ngayNhan);
                $id = query($sqlTrangThai);
            }catch(Exception $ex)
            {
                WriteLog('sendDataDichVuCong failed to adapter_trangthaihoso : ',$ex->getMessage());
            }
            
            try{
			//$mailerstatus = sendMail($mahoso,$tenloaihoso,$today,$arrayInfo);
			}catch(Exception $ex)
            {
                WriteLog('sendMail failed: ',$ex->getMessage());
            }
            return $id;
		}
	}else{
		WriteLog('sendDataDichViCong','Login Failed');
		return -1;
	}
}
/*	 _________|9:26 - 26/02/2011_|______________________________
	|	Ham goi mail thong bao nhan ho so						|    
	|___________________________________________________________|
*/
function sendMail($mahoso_mail,$tendichvu,$thoigiandangky,$arrayInfo) 
{		
		if($arrayInfo['EMAIL'] != ""){
			include "../../lib/UniMail/class.phpmailer.php"; 
			include "../../lib/UniMail/class.smtp.php"; 
			$mail = new PHPMailer();
			$mail->IsSMTP(); 
			$mail->IsHTML(true);
			$mail->Host			= MAIL_HOST;
			$mail->Port			= MAIL_PORT;
			$mail->Username		= MAIL_USERNAME;
			$mail->Password		= MAIL_PASSWORD;
			$mail->Mailer   = "smtp";
			//$issecure = false;
			$mail->SMTPAuth		= true; 
			$mail->SMTPSecure	= MAIL_PROTOCOL;
			$mail->From			= MAIL_ADDRESS;
			$mail->FromName		= "tttt.danang.gov.vn";
			$mail->AddAddress(base64_decode($arrayInfo['EMAIL']),base64_decode($arrayInfo['TENTOCHUCCANHAN']));
			
			$mail->AddReplyTo(MAIL_ADDRESS,"SỞ THÔNG TIN VÀ TRUYỀN THÔNG - TP ĐÀ NẴNG");
			$mail->Subject		= 'Hồ sơ '.$mahoso_mail.'-'.MAIL_SUBJECT;
			/* THIET LAP CAC THONG TIN CHO NOI DUNG EMAIL*/
			$name_rc = base64_decode($arrayInfo['TENTOCHUCCANHAN']);
			$diachi = base64_decode($arrayInfo['DIACHI']);
			$tenloaihoso = '"'.$tendichvu.'" ';
			$fax =  base64_decode($arrayInfo['FAX']);
			$coquan =  base64_decode($arrayInfo['TENDONVI']);
			$dienthoaididong = base64_decode($arrayInfo['DIENTHOAIDIDONG']);
			$dienthoaicodinh = base64_decode($arrayInfo['DIENTHOAI']);
			$email_rc = base64_decode($arrayInfo['EMAIL']);
			$noidungkhac = base64_decode($arrayInfo['NOIDUNGKHAC']);
			/* THIET LAP THONG TIN DINH KEM */
			$listFileContent = "";
			$files = base64_decode($arrayInfo['FILELIST']);
			if($files != ""){
			$listFiles = split(",",$files);
				$stt = 0;
				for($i = 0 ; $i<count($listFiles);$i++)
				{
				  $stt++;
				  if($listFiles[$i] != ""){
				  $listFileContent .= "<p class=MsoNormal style='text-align:justify'>".$stt.". Tập tin '".$listFiles[$i]."';</p>";
				  }
				}
			 }else{
				$listFileContent = "<p class=MsoNormal style='text-align:justify'> Không có;</p>";
			 }
			 /*ĐƯA NỘI DUNG EMAIL VÀO*/
			include "emailtemplate.php"; 
			$mail->Body			= $email_body;
			$mail->AltBody		= $tenloaihoso;

			$mail->IsHTML(true);	
			$issend = $mail->Send();
			if($issend == ""){
			WriteLog('Send mail to '.base64_decode($arrayInfo['EMAIL']).' :',$mail->ErrorInfo);
			}
		}
	}

/*	 _________| BAOTQ 15:50 - 26/02/2011 |______________________
	|	Ham viet cho QLVBDH lay tap tin dich vu cong			|
	|___________________________________________________________|
*/
function getAllFiles($username,$password,$role){
	
	if(checkAuthenticationWithRole($username, $password,"QLVBDH@f1")){
		WriteLog('getAllFiles',"Call");
		$newfile = searchNewFiles();
		if((int)$newfile > 0){
		
			$id_part = getMAHOSONewFile();
			
			if($id_part != "")
			{
				$sql = sprintf("select ID_FILE,MAHOSO,NAME,SIZE,MIME,SEND_DATE,CONTENT,ID_PART,PART_NUMBER from adapter_files where ID_PART = '%s' ORDER BY PART_NUMBER",$id_part);
				$re = mysql_query($sql);
				$sdata = common::SerializeData($re);
				$mhs = explode("~",$sdata);
				if($mhs[10] != "")
				{
					$sql = sprintf("update adapter_files SET IS_GET = 1 where ID_PART = '%s'",$id_part);
					query($sql);
					return $sdata;
				}else{
					return 0;
				}
			}
		}else{
			return -2;
		}		
	}else{
		return -1;
	}
}
/*	 _________| BAOTQ 19:40 - 04/03/2011 |______________________
	|	Ham kiem tra co cac file moi hay khong					|
	|___________________________________________________________|
*/
function searchNewFiles()
{
	$sql = sprintf("select count(*) as DEM from adapter_files where IS_GET = 0");
	
	$re = query($sql);
	$row = mysql_fetch_assoc($re);	
	if((int)$row["DEM"]){
		return 1;		
	}else{
		return 0;
	}
}
/*	 _________| BAOTQ 19:40 - 04/03/2011 |______________________
	|	Ham kiem tra co cac file moi hay khong					|
	|___________________________________________________________|
*/
function getMAHOSONewFile()
{
	$sql = sprintf("select ID_PART as ID from adapter_files where IS_GET = 0 lIMIT 1");
	
	$re = query($sql);
	$row = mysql_fetch_assoc($re);	
	return $row['ID'];
}

/*	 _________| BAOTQ 15:40 - 26/02/2011 |______________________
	|	Ham viet cho website gui tap tin dich vu cong			|
	|___________________________________________________________|
*/
function sendFile($username,$password,$mahoso,$content,$fileName,$fileSize,$mime,$id_part,$part_number){
	if(checkAuthentication($username, $password)){
		$send_date = date("Y-m-d h:m:s");
		$sql = sprintf("
				insert into adapter_files
				(`ID_PART`,`PART_NUMBER`,`MAHOSO`,`CONTENT`,`IS_GET`,`NAME`,`MIME`,`SIZE`,`SEND_DATE` )
				values(
					'%s','%s','%s','%s','%s','%s','%s','%s','%s'
				)
				",$id_part,$part_number,$mahoso,$content,0,$fileName,$mime,$fileSize,$send_date);		
		$result = query($sql);
	 }else{
		 return -1;
	 }
	return $result;
}

/*	 _________| BAOTQ COMMENT |_________________________________
	|	Ham PRIVATE												|
	|	Xac thuc username - password							|
	|___________________________________________________________|
*/
function checkAuthentication($username, $password){
	$sql = sprintf("
		select count(*) as DEM from adapter_users where USERNAME = '%s' and PASSWORD = '%s'
	",mysql_real_escape_string($username),md5($password));
	$result = query($sql);
	$row = mysql_fetch_assoc($result);
	$dem = $row["DEM"];
	if($dem){
		return true;
	}
	else{
		WriteLog("Login :Fail", $username);
		WriteLog("Login :Fail", $password);
		return false;
	}
}
/*	 _________| BAOTQ COMMENT |_________________________________
	|	Ham PRIVATE												|
	|	lay role cua user										|
	|___________________________________________________________|
*/
function getRole($username, $password){
	$sql = sprintf("
		select ROLE from adapter_users where USERNAME = '%s' and PASSWORD = '%s'
	",mysql_real_escape_string($username),md5($password));
	$result = query($sql);
	$row = mysql_fetch_assoc($result);
	return $row["ROLE"];
}
function checkAuthenticationWithRole($username, $password, $role){
	$sql = sprintf("
		select count(*) as DEM from adapter_users where USERNAME = '%s' and PASSWORD = '%s' and ROLE = '%s'
	",mysql_real_escape_string($username),md5($password),$role);
	$result = query($sql);
	$row = mysql_fetch_assoc($result);
	$dem = $row["DEM"];
	if($dem){
		return true;
	}else{
		WriteLog("Login with role :Fail", $username);
		return false;
	}
}

/*	 _________| BAOTQ COMMENT |_________________________________
	|	Ham viet cho QLVDH dong bo danh muc cac dich vu cong	|
	|	Ham luu danh muc cac dich vu cong vao csdl adapter		|
	|___________________________________________________________|
*/
function insertdanhmuc($username,$password,$code,$xml){
	
	if(checkAuthentication($username, $password)){
		try{
			$sql = sprintf("SELECT count(*) as DEM from adapter_luutrudanhmuc where CODE = '%s'"
			,$code);
			$re = query($sql);
			$row = mysql_fetch_assoc($re);

			if( $row["DEM"] ){
				$sql = sprintf("
				UPDATE adapter_luutrudanhmuc SET `CODE` = '%s' , `DATA` = '%s' , IS_SYNCHRONOUS = 0 
				WHERE CODE = '%s'
				",$code,$xml,$code);
			}else{
				$sql = sprintf("
				INSERT INTO adapter_luutrudanhmuc (`CODE`,`DATA`,`IS_SYNCHRONOUS`)
				VALUES ( '%s','%s',%s) ",$code,$xml,0);
			}
			query($sql);
		}catch(Exception $ex){
			return $ex->__toString();
		}
	}
}
/*
* getDanhMucLoaiHoSo
* @author : Baotq 
*/
function getDanhMucLoaiHoSo($username,$password){
    if(checkAuthentication($username, $password)){
        $sql = "SELECT TENLOAI,SONGAYXULY,LEPHI,CODE FROM adapter_loai_hoso";
        $rs = query($sql);
        $xml = common::toXML($rs);
        return $xml;
    }else{
        return -1;
    }
}

/*
* @creator : baotq
* Hàm cho QLVBDH đồng bộ danh mục
*/
function dongBoLoaiHoSoMotCua($username,$password,$data){
	if(checkAuthenticationWithRole($username, $password, "QLVBDH@f1")){
        $dataLoaiHoSo   = common::deseriallizeToArray($data);
        $cnt            = count($dataLoaiHoSo);
        $sql            = "delete from adapter_loai_hoso";
        WriteLog("dongBoLoaiHoSoMotCua ", $cnt );
        mysql_query("BEGIN");  
        try{
            query($sql);
            $rs = 0;
            for($i = 0 ; $i<$cnt;$i++){
                $sql = sprintf("
                        INSERT INTO adapter_loai_hoso(
                            ID_LOAIHOSO,
                            TENLOAI,
                            SONGAYXULY,
                            LEPHI,
                            CHUTHICH,
                            CODE,
                            ID_LOAIHSCV,
                            ID_LV_MC,
                            IS_DONGBO,
                            ID_ONWEBSITE,
                            IS_UPDATE,
                            SAPTRE                        
                        )VALUES(
                            %s,'%s','%s',%s,'%s','%s',%s,%s,%s,%s,%s,%s
                        )
                    ",
                    (int)$dataLoaiHoSo[$i]['ID_LOAIHOSO'],
                         $dataLoaiHoSo[$i]['TENLOAI'],
                         $dataLoaiHoSo[$i]['SONGAYXULY'],
                    (int)$dataLoaiHoSo[$i]['LEPHI'],
                         $dataLoaiHoSo[$i]['CHUTHICH'],
                         $dataLoaiHoSo[$i]['CODE'],
                    (int)$dataLoaiHoSo[$i]['ID_LOAIHSCV'],
                    (int)$dataLoaiHoSo[$i]['ID_LV_MC'],
                    (int)$dataLoaiHoSo[$i]['IS_DONGBO'],
                    (int)$dataLoaiHoSo[$i]['ID_ONWEBSITE'],
                    (int)$dataLoaiHoSo[$i]['IS_UPDATE'],
                    (int)$dataLoaiHoSo[$i]['SAPTRE']
                    );
                $rs += query($sql);
            }
            if($rs == $cnt){
                mysql_query("COMMIT"); 
                return 1;
            }else{
                mysql_query("ROLLBACK");
                return 0;
            }
        }catch(Exception $ex){
            mysql_query("ROLLBACK");
            return 0;
            WriteLog("dongBoLoaiHoSoMotCua :Fail", $ex->getMessage());
        }
	}else{
        return -1;
    }
}
/*
* @creator : baotq
* @purpose : Hàm cho QLVBDH đồng bộ lịch làm việc
*/
function dongBoLichLamViec($username,$password,$data){
	if(checkAuthenticationWithRole($username, $password, "QLVBDH@f1")){
        $dataLichLamViec = common::deseriallizeToArray($data);
        $cnt = count($dataLichLamViec);
        WriteLog("dongBoLichLamViec ", $cnt );
        $sql = "delete from adapter_nonworkingdate";
        mysql_query("BEGIN");  
        try{
            query($sql);
            $rs = 0;
            for($i = 0 ; $i<$cnt;$i++){
                $sql = sprintf("
                    INSERT INTO adapter_nonworkingdate(
                        `ID_NWKD`,`NAME`,`WDAY`,`ISYEAR`,`ISMONTH`,`ISREALDATE`,`ISMOON`,`ISCOMMON`,`BDAY`,`BMON`,`BYEAR`,`EDAY`,`EMON`,`EYEAR`
                    )VALUES(
                        %s,'%s',%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s
                    )",
                    (int)$dataLichLamViec[$i]['ID_NWKD'],
                         $dataLichLamViec[$i]['NAME'],
                    (int)$dataLichLamViec[$i]['WDAY'],
                    (int)$dataLichLamViec[$i]['ISYEAR'],
                    (int)$dataLichLamViec[$i]['ISMONTH'],
                    (int)$dataLichLamViec[$i]['ISREALDATE'],
                    (int)$dataLichLamViec[$i]['ISMOON'],
                    (int)$dataLichLamViec[$i]['ISCOMMON'],
                    (int)$dataLichLamViec[$i]['BDAY'],
                    (int)$dataLichLamViec[$i]['BMON'],
                    (int)$dataLichLamViec[$i]['BYEAR'],
                    (int)$dataLichLamViec[$i]['EDAY'],
                    (int)$dataLichLamViec[$i]['EMON'],
                    (int)$dataLichLamViec[$i]['EYEAR']
                );
                $rs += query($sql);                
            }
            
            if($rs == $cnt){
                mysql_query("COMMIT"); 
                return 1;
            }else{
                mysql_query("ROLLBACK");
                return 0;
            }
        }catch(Exception $ex){
            mysql_query("ROLLBACK");
            return 0;
            WriteLog("dongBoLichLamViec :Fail", $ex->getMessage());
        }
	}else{
        return -1;
    }
}

function inserttrangthai($username,$password,$masohoso,$tentochuccanhan,$tenhoso,$trangthai,$phong,$ghichu,$dienthoai,$barcode,$ngaynop,$ngaynhan){
	if(checkAuthentication($username, $password)){
		try{
			$sql = sprintf("select count(*) as DEM from adapter_trangthaihoso where MASOHOSO = '%s' ",$masohoso);
			$re = query($sql);
			$row = mysql_fetch_assoc($re);
			if( $row["DEM"] ==0 ){
                $masohoso       = base64_decode($masohoso);
                $tentochuccanhan= base64_decode($tentochuccanhan);
                $tenhoso        = base64_decode($tenhoso);
                $trangthai      = base64_decode($trangthai);
                $ghichu         = base64_decode($ghichu);
                $dienthoai      = base64_decode($dienthoai);
                $barcode        = base64_decode($barcode);
                $phong          = base64_decode($phong);
                $ngaynop        = base64_decode($ngaynop);
                $ngaynhan       = base64_decode($ngaynhan);
                
				$sql = sprintf("
					INSERT INTO adapter_trangthaihoso(
						MASOHOSO,
						TENTOCHUCCANHAN,
						TENHOSO,
						TRANGTHAI,
						GHICHU,
						DIENTHOAI,
						BARCODE,
						NGAYNOP,
						NGAYNHAN,
						PHONG
					)VALUES(
						'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'
					)
				",$masohoso,$tentochuccanhan,$tenhoso,$trangthai,$ghichu,$dienthoai,$barcode,$ngaynop,$ngaynhan,$phong);
				query($sql);
			}else{
				$sql = sprintf("
				UPDATE adapter_trangthaihoso SET
					`TENTOCHUCCANHAN`='%s',
					`TENHOSO`='%s',
					`TRANGTHAI`='%s',
					`GHICHU`='%s',
					`DIENTHOAI`='%s',
					`BARCODE`='%s',
					`NGAYNHAN`='%s'
				WHERE
					`MASOHOSO` = '%s'",$tentochuccanhan,$tenhoso,$trangthai,$ghichu,$dienthoai,$barcode,$ngaynhan,$masohoso);
				query($sql);
			}
			
			return 1;
		}catch(Exception $ex){
			return 0;
		}
	}else{
		return -1;
	}
}
function selecttrangthai($username,$password,$masohoso){
	if(checkAuthentication($username, $password)){
		// cam doi so 181758570 
		//$masohoso_search = "181758570".$masohoso;
		try{
			$sql = sprintf("select * from adapter_trangthaihoso where MASOHOSO = '%s' order by ID_TT DESC limit 0,1",mysql_real_escape_string($masohoso));
			$re = query($sql);
			$row = mysql_fetch_assoc($re);
			if($row['MASOHOSO'] == "")
			{
				$masohoso = "";
			}
			$addContent = "";
			$role = getRole($username,$password);
			if($role == 'client1'){
                $ngaynop = MysqlDateToVnDate($row["NGAYNOP"]);
                $ngaynhan = MysqlDateToVnDate($row["NGAYNHAN"]);
				$addContent .= "<NGAYNOP>".base64_encode($ngaynop)."</NGAYNOP>";
				$addContent .= "<NGAYNHAN>".base64_encode($ngaynhan)."</NGAYNHAN>";
			}
			$xml = "
				<HOSO>
					<MASOHOSO>
						".base64_encode($masohoso)."
					</MASOHOSO>
					<TENTOCHUCCANHAN>
						".base64_encode($row["TENTOCHUCCANHAN"])."
					</TENTOCHUCCANHAN>
					<TENHOSO>
						".base64_encode($row["TENHOSO"])."
					</TENHOSO>
					<TRANGTHAI>
						".base64_encode((int)$row["TRANGTHAI"])."
					</TRANGTHAI>
					<GHICHU>
						".base64_encode($row["GHICHU"])."
					</GHICHU>
					<DIENTHOAI>
						".base64_encode($row["DIENTHOAI"])."
					</DIENTHOAI>
					<BARCODE>
						".base64_encode($row["BARCODE"])."
					</BARCODE>
					".$addContent."
				</HOSO>
			";
			return $xml;
		}catch(Exception $ex){
			WriteLog("selecttrangthai : Failed ", $ex->getMessage());
			return "";
		}
	}
}

/*	 _________| COMMENT 16/09/2011 - 10:14 |____________________
	|	Ham chuyen doi du lieu xml ra array						|
	|___________________________________________________________|
*/
function XmlToArray($xml){
		$array = simplexml_load_string ( $xml );
		$newArray = array ( ) ;
		$array = ( array ) $array ;
		foreach ( $array as $key => $value )
		{
			$value = ( array ) $value ;			
			$newArray [ $key] = $value[0];
		}
		$newArray = array_map("trim", $newArray);
		return $newArray; 
}
function checkMaDichVu($madichvu){
    $sql = sprintf("select count(*) as DEM from adapter_loai_hoso where CODE = '%s'",mysql_real_escape_string($madichvu));
	$re = query($sql);
	$row = mysql_fetch_assoc($re);
	if((int)$row["DEM"] > 0){
        return true;
    }
    return false;
}
function MysqlDateToVnDate($mysqldate) {
        if ($mysqldate != "") {
            $d = explode(" ", $mysqldate);
            $d = explode("-", $d[0]);
            return (int) $d[2] . "/" . (int) $d[1] . "/" . (int) $d[0];
        }
}
function WriteLog($functionName,$content)
{
	// $today = date("[ d/m/Y ] [ h:i:s ]");
	// $myFile = LOG_FILE;
	// $fh = fopen($myFile, 'a') or die("can't open file");
	// $stringData = $today." \t".$functionName."\t".$content."\n\n";
	// fwrite($fh, $stringData);
	// fclose($fh);
}