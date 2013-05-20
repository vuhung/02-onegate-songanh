<?php
require_once 'mailclient/models/EmailEngine/EmailEngineOut.php';
require_once 'motcua_ub/models/MotCuaModel.php';
require_once 'motcua_ub/models/motcua_hosoModel.php';

	class mailProcess{
		public function send($mahoso,$tenloaihoso,$ngay_tra,$id_files,$isPreview,$isAttach){
            $config         = Zend_Registry::get('config');
            $tenCoQuan      = $config->sys_info->company;
            $website        = $config->sys_info->website;
            $phonedvc     = $config->sys_info->phonedvc;
            $year           = QLVBDHCommon::getYear();
            $motcuahsModel  = new motcua_hosoModel($year);
            $hscv_info      = $motcuahsModel->findhsmc($mahoso);
            $mahoso_return  = $hscv_info["MAHOSO"];
            
            $buttonSubmit = "";
            if($isPreview == '1')
            {
                $buttonSubmit = "<input type='submit' value='Gửi Mail'/><hr/>";
                $fileInForm = "";
            }
            
            if($isAttach == 0){
                $id_files = null;
            }
            
            foreach($id_files as $f){
                    $fileInForm .="<input type='hidden' name='idFile[]' value='".$f."'/>";
            }
            $fileInForm .="<input type='hidden' name='mahoso' value='".$mahoso."'/>"; 	
            $fileInForm .="<input type='hidden' name='tenloaihoso' value='".$tenloaihoso."'/>"; 	
            $fileInForm .="<input type='hidden' name='ngay_tra' value='".$ngay_tra."'/>"; 	
                
            $MotCuaModel = new MotCuaModel();
            $year= QLVBDHCommon::getYear();
            /*Lấy các thông tin chung*/
            $emailrc = $hscv_info["EMAIL"];
            $tentochuccanhan = $hscv_info["TENTOCHUCCANHAN"];
            $date = new DateTime($ngay_tra);
            $ngay_tra = $date->format('d/m/Y');
            $guiluc = QLVBDHCommon::MysqlDateToVnDate($hscv_info["NHAN_NGAY"]);
            //$guiluc = $hscv_info["NHAN_NGAY"];
            $diachi = $hscv_info["DIACHI"];
            $coquan = $hscv_info["TENDONVI"];
            $fax = $hscv_info["FAX"];
            $dienthoaicodinh = $hscv_info["DIENTHOAI"];
            $dienthoaididong = $hscv_info["DIDONG"];
            $ykienkhac = $hscv_info["NOIDUNGKHAC"];
            
            /*Lấy danh sách tập tin gởi đi*/
            if(count($id_files) > 0 ){
            //var_dump($id_files);exit;
            $listNameSend = $MotCuaModel->getListFileNameSend($year,$id_files);
            }
            if($mahoso != "" ){
            /*Lấy danh sách tập tin nhận được*/					
            $listNameReceived = $MotCuaModel->getListFileNameReceived($mahoso);
            }			
            $listKetqua = "";
            $sttListKetqua = 0;
            if(count($listNameSend)>0){
                foreach($listNameSend as $ln){
                    $sttListKetqua++;
                    $listKetqua .="<p class=MsoNormal style='text-align:justify'>".$sttListKetqua.". Tập tin “".$ln['FILENAME']."”;</p>";
                }
            }else{
                    $listKetqua .="<p class=MsoNormal style='text-align:justify'>Không có</p>";
            }
            $listNhanDuoc = "";
            $sttListNhanDuoc = 0;
            if(count($listNameReceived) >0){
                foreach($listNameReceived as $ln){
                    $sttListNhanDuoc++;
                    $listNhanDuoc .="<p class=MsoNormal style='text-align:justify'>".$sttListNhanDuoc.". Tập tin “".$ln['FILENAME']."”;</p>";
                }
            }else{
                    $listNhanDuoc .="<p class=MsoNormal style='text-align:justify'>Không có</p>";
            }        
            
            /*Lấy thông tin email từ chức năng cấu hình của QT hệ thống*/
            $config = new Zend_Config_Ini('../application/config.ini', 'vbmail');
            $arr_email = array(
                "USERNAME" => $config->vbmail->username,
                "PASSWORD" => $config->vbmail->password,
                "ACTIVE" => 1,
                "INCOMING_PORT" => $config->vbmail->incomingport,
                "OUTGOING_PORT" => $config->vbmail->outgoingport,
                "INCOMING_HOSTNAME" => $config->vbmail->incominghost,
                "OUTGOING_HOSTNAME" => $config->vbmail->outgoinghost,
                "INCOMING_PROTOCOL" => $config->vbmail->incomingprotocol,
                "OUTGOING_PROTOCOL" => $config->vbmail->outgoingprotocol,
                "NAME_INFO" => $tenCoQuan,
                "SSL_IN" => $config->vbmail->is_in_ssl,
                "SSL_OUT" => $config->vbmail->is_out_ssl,
                "EMAIL_ADDR" => $config->vbmail->email
            );
            $emailMocua =  $config->vbmail->email;
            if($isAttach == 1){
                include 'dichvucong/models/email_template_level4.php';
            }else{
                include 'dichvucong/models/email_template_level3.php';
            }
            if($isPreview == '1')
            {
                echo $email_body;
                exit;	
            }
            $email_account = Email_account::arrayToObject($arr_email);
            
            $messenger = new EmailEngineOut(Email_account::objectToArray($email_account));
            /* END */	
                
            $subject = $tenCoQuan." Đà nẵng - Thông báo";
            $addr_to = $hscv_info['EMAIL'];
            $addr_cc = "";
            $addr_bcc = "";					
            $re = $messenger->sendMailGlobal($subject,$email_body,$addr_to,$addr_cc,$addr_bcc,$id_files);
            return $re;
        }

		
	}
