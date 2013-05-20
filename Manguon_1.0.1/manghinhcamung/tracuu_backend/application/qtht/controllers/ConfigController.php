<?php

/**
 * ConfigController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
require_once 'qtht/models/BussinessDateModel.php';
class Qtht_ConfigController extends Zend_Controller_Action {
	
	function init(){
		$this->view->title = 'Quản lý cấu hình';
		$this->view->subtitle = 'Danh sách thông số';
	}
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated ConfigController::indexAction() default action
		//Lay tham so he thong cu
		//$config = new Zend_Config_Ini('../application/config.ini', 'general');
		$config = new Zend_Config_Ini('../application/config.ini','general',true);
		$config_ldap = new Zend_Config_Ini('../application/config.ini','ldap',true);
		if($this->_request->isPost()){
			//nhan du lieu du client
			$param = $this->_request->getParams();
			//luu config
			//$config->
			$config->db->params->host = $param['db_host'];
			$config->db->params->username = $param['db_user'];
			$config->db->params->password = $param['db_password'];
			
		}
		
		//$config->S
		//tham so he thong
		$this->view->data_ldap=$config_ldap;
		$this->view->data = $config;
		$this->view->config_mail = new Zend_Config_Ini('../application/config.ini', 'mail_client');
		QLVBDHButton::EnableSave("/qtht/DanhMucMaSo/save");
		QLVBDHButton::AddButton("Trang chủ","","BackButton","BackButtonClick();");
		
		/**
		** Nhan va gui van ban qua mail
		*/
		$config_vbmail = new Zend_Config_Ini('../application/config.ini', 'vbmail');
		$email_account;
		$email_account->_email_addr = $config_vbmail->vbmail->email;
		$email_account->_username = $config_vbmail->vbmail->username;
		$email_account->_password = $config_vbmail->vbmail->password;
		$email_account->_incoming_hostname = $config_vbmail->vbmail->incominghost;
		$email_account->_outgoing_hostname = $config_vbmail->vbmail->outgoinghost;
		$email_account->_incoming_port = $config_vbmail->vbmail->incomingport;
		$email_account->_outgoing_port = $config_vbmail->vbmail->outgoingport;
		$email_account->_outgoing_protocol = $config_vbmail->vbmail->outgoingprotocol; 
		$email_account->_incoming_protocol = $config_vbmail->vbmail->incomingprotocol; 
		$email_account->_ssl_in = $config_vbmail->vbmail->is_out_ssl;
		$email_account->_ssl_out = $config_vbmail->vbmail->is_in_ssl;
		$this->view->email_account = $email_account;
		
		
	}
	public function viewbussdateAction(){
		$this->_helper->layout->disableLayout();
		$param = $this->getRequest()->getParams();
		$scope = $param['scope'];
		$day = $param['DAY'];
		$this->view->refresh = false;
		if(is_array($day)){
			if($scope==2){
				//kiểm tra xem ngày được chọn có trong hệ thống chưa
				foreach($day as $oneday){
					$ngay = explode("-",$oneday);
					$ngay = array("mon"=>$ngay[0],"mday"=>$ngay[1],"wday"=>$ngay[2]);
					if(!BussinessDateModel::IsNonWorkingDate($ngay)){
						BussinessDateModel::insertnonworking($ngay);
					}
				}
			}else{
			//kiểm tra xem ngày được chọn có trong hệ thống chưa
				foreach($day as $oneday){
					$ngay = explode("-",$oneday);
					$ngay = array("mon"=>$ngay[0],"mday"=>$ngay[1],"wday"=>$ngay[2]);
					BussinessDateModel::deletenonworking($ngay);
				}
			}
			$this->view->refresh=true;
		}
		$wday = $param['WDAY'];
		if(is_array($wday)){
			if($scope==2){
				//kiểm tra xem ngày được chọn có trong hệ thống chưa
				foreach($wday as $oneday){
					$ngay = array("wday"=>$oneday);
					if(!BussinessDateModel::IsNonWorkingDate($ngay)){
						BussinessDateModel::insertnonworkingwday($ngay['wday']);
					}
				}
			}else{
			//kiểm tra xem ngày được chọn có trong hệ thống chưa
				foreach($wday as $oneday){
					$ngay = array("wday"=>$oneday);
					BussinessDateModel::deletenonworkingwday($ngay['wday']);
				}
			}
			$this->view->refresh=true;
		}
		
		$curmonth = $param['month'];
		if($curmonth == 0){
			$d = getdate();
			$curmonth = $d['mon'];
			if($curmonth==13)$curmonth =1;
			if($curmonth==0)$curmonth =12;
		}else{
			if($curmonth==13)$curmonth =1;
			if($curmonth==0)$curmonth =12;
			$this->view->refresh=true;
		}
		$fromdate = mktime(0,0,0,$curmonth,1,QLVBDHCommon::getYear());
		$fromdate = getdate($fromdate);
		$fromdate = mktime(0,0,0,$curmonth,1-$fromdate['wday']+1,QLVBDHCommon::getYear());
		$fromdate = getdate($fromdate);
		$this->view->fromdate = $fromdate;
		$this->view->curmonth = $curmonth;
	}
	public function saveAction(){
		$param = $this->getRequest()->getParams();
		//var_dump($param);exit;
		$username=  str_replace("=",".",$param['ldap_username']);
		$file = '../application/config.ini';
		$fh = fopen($file, "w");
		$file_contents = "
			[ldap]
			    ldap.host=".$param['ldap_os']."
			    ldap.port=".$param['ldap_port']."
			    ldap.username=".$username."
			    ldap.password=".$param['ldap_pass']."
			    ldap.enable=".$param['kn_ldap']."
			    ldap.user_qd=".$param['ldap_user_qd']."	
			[vbmail]
				vbmail.email = ".$param["EMAIL_ADDR"]."
				vbmail.username = ".$param["USERNAME"]."
				vbmail.password = ".$param["PASSWORD"]."
				vbmail.incominghost = ".$param["INCOMING_HOSTNAME"]."
				vbmail.outgoinghost = ".$param["OUTGOING_HOSTNAME"]."
				vbmail.incomingport = ".$param["INCOMING_PORT"]."
				vbmail.outgoingport = ".$param["OUTGOING_PORT"]."
				vbmail.incomingprotocol = ".$param["INCOMING_PROTOCOL"]."
				vbmail.outgoingprotocol = ".$param["OUTGOING_PROTOCOL"]."
				vbmail.is_out_ssl = ".$param["SSL_OUT"]."
				vbmail.is_in_ssl = ".$param["SSL_IN"]."
			
			[crypt]
				crypt.default.temp = C:\qlvbdh_data\crypt\\temp
			[mail_client]
				
				mail.default.mailclientstorage = C:\mailclient_storage
				mail.default.mailclientstorage_inbox= C:\mailclient_storage\inbox
				mail.default.mailclientstorage_sent= C:\mailclient_storage\sent
				mail.default.outgoing_server = ".$param['default_outgoing_server']."
				mail.default.incoming_server = ".$param['default_incoming_server']."
				mail.default.incoming_port = 110 
				mail.default.outgoing_port = 25
				mail.default.incoming_protocol = POP3
				mail.default.outgoing_protocol = SMTP
				mail.default.ssl_in = 0
				mail.default.ssl_out = 0
				;mail cua quan tri he thong
				mail.admin.content = Bạn đã cấu hình thành công mail
				;mail quota
				mail.quota.maxsize=".$param["QUOTA_MAXSIZE"]."
			[general]
				;ten he thong
				sys_info.name_system = ".$param['sysinfo_name']."
				sys_info.version = ".$param['sysinfo_version']."
				sys_info.city = ".$param['sysinfo_city']."
				sys_info.company = ".$param['sysinfo_company']."
				sys_info.companyvt = ".$param['sysinfo_companyvt']."
				sys_info.unit = ".$param['sysinfo_unit']."
				sys_info.address = ".$param['sysinfo_address']."
				sys_info.phone = ".$param['sysinfo_phone']."
				sys_info.fax = ".$param['sysinfo_fax']."
				sys_info.kyhieutiepnhan = ".$param['sysinfo_kyhieutiepnhan']."
				;layout
				appearance.layoutPath = ../application/layouts/scripts/ 
				appearance.layout = Main 
				;database
				db.adapter = PDO_MYSQL
				db.params.host = ".$param['db_host']."
				db.params.username = ".$param['db_username']."
				db.params.password = ".$param['db_password']."
				db.params.dbname = ".$param['db_dbname']."
				db.params.dbdatastorage = ".$param['db_dbdatastorage']."
				;filedinhkem
				file.root_dir = ".$param['file_root_dir']."
				file.maxsize = ".$param['file_maxsize']." 
				file.temp_path = ".$param['file_temp_path']."
				limit=".$param['limit']."
				;cgi
				cgi.os = win
				cgi.parse_word_exe_win = C:\antiword\antiword.exe
				cgi.parse_word_mapping_win = C:\antiword
				hscv.vtbp = ".$param['hscv_vtbp']."
				lct.dukien = ".$param['lct_dukien']."
				;chi tiet, but phe, phoi hop, filedinhkem, du thao, luan chuyen, bosung
				
		";
		$HSCV_1 = $param["HSCV_1_0"].",".$param["HSCV_1_1"].",".$param["HSCV_1_2"].",".$param["HSCV_1_3"].",".$param["HSCV_1_4"].",".$param["HSCV_1_5"].",".$param["HSCV_1_6"].",".$param["HSCV_1_7"].",".$param["HSCV_1_8"].",".$param["HSCV_1_9"];
		$HSCV_2 = $param["HSCV_2_0"].",".$param["HSCV_2_1"].",".$param["HSCV_2_2"].",".$param["HSCV_2_3"].",".$param["HSCV_2_4"].",".$param["HSCV_2_5"].",".$param["HSCV_2_6"].",".$param["HSCV_2_7"].",".$param["HSCV_2_8"].",".$param["HSCV_2_9"];
		$HSCV_3 = $param["HSCV_3_0"].",".$param["HSCV_3_1"].",".$param["HSCV_3_2"].",".$param["HSCV_3_3"].",".$param["HSCV_3_4"].",".$param["HSCV_3_5"].",".$param["HSCV_3_6"].",".$param["HSCV_3_7"].",".$param["HSCV_3_8"].",".$param["HSCV_3_9"];
		$file_contents .= "
			HSCV_1 = $HSCV_1
			HSCV_2 = $HSCV_2
			HSCV_3 = $HSCV_3
		";
		fwrite($fh, $file_contents);
		fclose($fh);
		$this->_redirect("/qtht/config");
	}
	public function nextyearAction(){
		global $db;
		$sql = "SHOW tables FROM qlvbdh LIKE '%2009'";
		$r = $db->query($sql);
		$tables = $r->fetchAll();
		foreach($tables as $table){
			try{
				$r = $db->query("show create table ".$table['Tables_in_qlvbdh (%2009)']);
				$r = $r->fetch();
				$r['Create Table'] = str_replace("2009","2008",$r['Create Table']);
				$db->query($r['Create Table']);
			}catch(Exception $ex){
				
			}
		}
		exit;
	}
	
	public function checkdatabaseAction(){
		$this->_helper->layout->disablelayout();
		$params = $this->_request->getParams();
		$prams_db = array(
		'host'=>$params['db_host'],
		'username'=>$params['db_username'],
		'password' =>$params['db_password'],
		'dbname' =>$params['db_dbname']
		);
		
		try{
			$new_db = Zend_Db::factory('PDO_MYSQL',$prams_db);
			$new_db->getConnection();
			echo 1; exit;
			
		}catch (Zend_Db_Adapter_Exception $e) {
			//echo $e->__toString();
   			echo 0;
   			exit;
		} catch (Zend_Exception $e) {
			//echo $e->__toString();
    		echo 0;
    		exit;
		}
	}
   public function checkdatabaseldapAction(){
		$this->_helper->layout->disablelayout();
		$params = $this->_request->getParams();
	
	 $options = array(
               'host' => $params['ldap_os'],
               'port' => (int)$params['ldap_port'],
               'username' => $params['ldap_username'],
               'password' => $params['ldap_pass'],
                   
    	       
      ); 
                 
		
		try{
			$ldap= new Zend_Ldap($options);
            $ldap->bind();
			echo 1; exit;
			
		}catch (Zend_Ldap_Exception  $e) {
   			echo 0;
   			exit;
		} 
	}
}
