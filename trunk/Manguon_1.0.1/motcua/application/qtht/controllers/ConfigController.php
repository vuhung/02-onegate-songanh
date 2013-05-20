<?php

/**
 * ConfigController
 *
 * @author
 * @version
 */

require_once 'Zend/Controller/Action.php';
require_once 'qtht/models/BussinessDateModel.php';
require_once 'dichvucong/models/nonworkingdateModel.php';
require_once 'Common/adapterHSCV.php';
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
		try{
		$this->view->config_chat = new Zend_Config_Ini('../application/config.ini', 'chat');
		}catch(Exception $ex){

        }
        $this->view->config_mail = new Zend_Config_Ini('../application/config.ini', 'mail_client');
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

		$bkcon = $this->dataBackupConfig();		
        $this->view->TYPE = $bkcon[0]['TYPE'];        
        $this->view->TIME = $bkcon[0]['TIME'];        
        $this->view->TYPE_VALUE = $bkcon[0]['TYPE_VALUE'];        
        $this->view->FOLDER = $bkcon[0]['FOLDER'];

        $dvc->dvc_username = $config->dvc_username;
        $dvc->dvc_password = $config->dvc_password;
        $dvc->dvc_serviceadapter = $config->dvc_serviceadapter;
        $dvc->dvc_uploadpath = $config->dvc_uploadpath;
        $this->view->dvc = $dvc;
	}
		public function dataBackupConfig() { 
            global $db;            
            $a=  $db->fetchAll("Select * from qtht_backup_config order by CREATE_DATE desc");            
            return $a;
        }
        public function dobackupnowAction(){
		$this->_helper->layout->disablelayout();
		$params = $this->_request->getParams();
                $data['TYPE'] = $params['LL_THEO'];
                $data['TIME']= $params['HOUR'];
                if($data['TYPE']==0) $data['TYPE_VALUE'] =0;
                else $data['TYPE_VALUE']= $params['TYPE_VALUE'];
                $data['FOLDER'] = $params['FOLDER_BK'];
                $data['CREATE_DATE']=date('Y-m-d H:i:m');
                global $db;
                if($db->insert("qtht_backup_config",$data))
                {
                    echo 1;exit;
                } else {
                    echo 0;exit;
                }		
		}

	

    public function docreatebackupschedule(){
		
		
		$config = Zend_Registry::get('config');
		$tools_dir =  dirname(dirname(dirname(dirname(__FILE__)))).DIRECTORY_SEPARATOR."html".DIRECTORY_SEPARATOR."tools";
		echo $tools_dir;exit;
		$path_batchfile = $tools_dir.DIRECTORY_SEPARATOR."set_schedule.bat";
		
		
		///tao file backup
		$config_db = $config->db->params;
		$path_dich = $config->backup->THUMUC_DICH;
		$path_dk = $config->file->root_dir;
		$bk_file_bat = '"' .$tools_dir.DIRECTORY_SEPARATOR."mysqldump". '" -u '. $config_db->username. " -p"."\"".$config_db->password."\" -h  " . $config_db->host ." " .$config_db->dbname . " > " . '"'. $path_dich.DIRECTORY_SEPARATOR."backup_qlvbdh.sql".'"'.
		"";   
		$bk_file_bat .= "\n xcopy $path_dk $path_dich  /E /H /R /Y "  ;
		
		$file = fopen($tools_dir.DIRECTORY_SEPARATOR."backup_schedule.bat",'w');
		file_put_contents($tools_dir.DIRECTORY_SEPARATOR."backup_schedule.bat",$bk_file_bat);
		
		$backup_batchfile = $tools_dir.DIRECTORY_SEPARATOR."backup_schedule.bat";
		//$backup_params = '"/create /tn "backup_qlvbdh" /tr '. $backup_batchfile .'" /sc daily /st 08:00:00 /ru TRUNGMEO\trunglv /rp trung133"';
		
		$backup_params = array();
		$backup_params[] = "\"$backup_batchfile\"";
		$backup_params[] = "\"".$config->backup->LL_THEO."\"";
		if($config->backup->LL_THEO == "WEEKLY")
			$backup_params[] = "\"".$config->backup->WEEKLY."\"";
		if($config->backup->LL_THEO == "MONTHLY")
			$backup_params[] = "\"".$config->backup->MONTHLY."\"";
		
		if($config->backup->LL_THEO == "DAILY")
			$backup_params[] = "\"daily\"";
		$backup_params[] = "\"".$config->backup->HOUR."\""; ;

		//$backup_params[] = "\"".$config->backup->."\""; ;
		
		$param_exe = implode(' ',$backup_params);
		
		$command = "call $path_batchfile $param_exe " ;
		//echo $command ; exit;
		pclose(popen("start /B ".$command , "r"));

		
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
        
        /**
        * Đồng bộ dữ liệu lịch làm việc lên adapter
        * Để tính ngày hẹn trả  
        */
        $this->synchronousToAdapter();
	}
    
    /**
    * @creator : Baotq
    * @createDate : 2012-12-13 16:50:00
    * @description : Lấy tất cả dữ liệu từ bảng, tuần tự hóa và gọi webservice gửi lên adapter
    */
    public function synchronousToAdapter(){
        $model      = new nonworkingdateModel();
        $data       = $model->getAll();
        $adapter    = new adapterHSCV();
        $strData    = $adapter->serializeData($data);
        $config     = Zend_Registry::get('config');
        
		$wsdl       = $config->dvc_serviceadapter;
		$username   = $config->dvc_username;
		$password   = $config->dvc_password;
        
		try{
			$cliente = new SoapClient($wsdl);
			$status = $cliente->__call('dongBoLichLamViec',array($username, $password,$strData));
            return 1;
		}catch (Exception $ex){
            echo "<font color='red'><b>".$ex->getMessage()."</b></font>";
            exit;
		}        
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
			[chat]
				services.chung = \"".$param["serviceschung"]."\"
				services.chat = \"".$param["serviceschat"]."\"
				services.ip = \"".$param["ipserverchat"]."\"
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
                sys_info.noinhanhoso = ".$param['sysinfo_noinhanhoso']."
                sys_info.motcuaname = \"".$param['sysinfo_motcuaname']."\"
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
				;backup
				backup.LL_THEO =".$param['LL_THEO']."
				backup.WEEKLY =".$param['WEEKLY']."
				backup.MONTHLY =".$param['MONTHLY']."
				backup.HOUR =".$param['HOUR']."
				backup.IS_BK_FDK =".$param['IS_BK_FDK']."
				backup.IS_BK_CSDL =".$param['IS_BK_CSDL']."
				backup.THUMUC_DICH =".$param['THUMUC_DICH']."
				backup.DOMAIN =".$param['DOMAIN']."
				backup.ADMISTRATOR =".$param['ADMISTRATOR']."
				backup.PASS_ADMISTRATOR =".$param['PASS_ADMISTRATOR']."
				;cgi
				cgi.os = win
				cgi.parse_word_exe_win = C:\antiword\antiword.exe
				cgi.parse_word_mapping_win = C:\antiword
				hscv.vtbp = ".$param['hscv_vtbp']."
				lct.dukien = ".$param['lct_dukien']."
				;chi tiet, but phe, phoi hop, filedinhkem, du thao, luan chuyen, bosung

		; dich vu cong
    dvc_username =".$param['dvc_username']."
    dvc_password =".$param['dvc_password']."
    dvc_serviceadapter =".$param['dvc_serviceadapter']."
    dvc_uploadpath = \"".$param['dvc_uploadpath']."\"
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
		//$this->docreatebackupschedule(); ko cần
		$this->_redirect("/qtht/config");
	}
	public function nextyearAction(){
		global $db;
		$sql = "SHOW tables FROM qlvbdh LIKE '%2012'";
		$r = $db->query($sql);
		$tables = $r->fetchAll();

		foreach($tables as $table){
			try{
				$r = $db->query("show create table ".$table['Tables_in_qlvbdh (%2012)']);
				$r = $r->fetch();
				$r['Create Table'] = str_replace("2012",date("Y"),$r['Create Table']);
				//var_dump($r);exit;
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

	function taonammoiinputAction(){

		$this->view->title = "Quản lý năm làm việc";
		$this->view->subtitle = "Tạo mới";
		$db = Zend_Db_Table::getDefaultAdapter();
		//lay nam gan nhat
		//$year_new = QlVBDHCommon::getYear();
		$query = $db->query("select YEAR from qtht_year order by YEAR DESC ");
		$re = $query->fetchAll();
		$this->view->data_year = $re;
		$nam_cuoi = $re[0]["YEAR"];
		$this->view->nam_cuoi = $nam_cuoi ;

		$query = $db->query(" select * from vb_sovanban where YEAR = ?  ORDER BY TYPE", array($nam_cuoi));
		$re = $query->fetchAll();
		$this->view->data_sovb = $re;


	}

	function taomoinammoisaveAction(){
		$params = $this->getRequest()->getParams();
		///var_dump($params);
		$db = Zend_Db_Table::getDefaultAdapter();
		//Tao cac bang cho nam moi
		$config = Zend_Registry::get('config');
		$_auth = Zend_Registry::get('auth');

		$year_new = $params["YEAR"];

		$d = getdate();
		//Zend_Registry::set("year",$d['year']);
		$sql = "SHOW tables FROM ".($config->db->params->dbname)." LIKE 'qtht_log_".$year_new."'";
		$r = $db->query($sql);
		$tables = $r->fetchAll();
		if(count($tables)==0){
			$year = new qtht_year();
			$qr = $db->query("select count(*) as DEM  from qtht_year where YEAR=?",array($year_new));
			$re = $qr->fetch();
			if((int)$re["DEM"]==0){
				$db->insert('qtht_year',array("YEAR"=>$year_new));
			}
			//tao nam lam viec
			$sql = "SHOW tables FROM ".($config->db->params->dbname)." LIKE '%".($year_new-1)."'";
			$r = $db->query($sql);
			$tables = $r->fetchAll();
			foreach($tables as $table){
				try{
					$r = $db->query("show create table ".$table['Tables_in_'.($config->db->params->dbname).' (%'.($year_new-1).')']);
					$r = $r->fetch();
					$r['Create Table'] = str_replace($year_new-1,$year_new,$r['Create Table']);
					$db->query($r['Create Table']);

				}catch(Exception $ex){

				}
			}

		}



		//Tao so van ban cho nam moi
		$names = $params["NAME"];
		$id_lvbs = $params["ID_LVB"];
		$types = $params["TYPE"];
		$actives = $params["ACTIVE"];
		$id_cqs = $params["ID_CQ"];
		$id_deps = $params["ID_DEP"];
		$id_lvmcs = $params["ID_LV_MC"];
		$id_lhsmcs = $params["ID_LHS_MC"];

		$stt = 0;
		foreach($names as $name){
			$arraydata = array(
				"NAME"	=> $name,
				"ID_LVB" => (int)$id_lvbs[$stt],
				"TYPE" => (int)$types[$stt],
				"ACTIVE" => (int)$actives[$stt],
				"ID_CQ" => (int)$id_cqs[$stt],
				"ID_DEP" => (int)$id_deps[$stt],
				"YEAR" => (int)$params["YEAR"],
				"ID_LV_MC" => (int)$id_lvmcs[$stt],
				"ID_LHS_MC" => (int)$id_lhsmcs[$stt]
			);

			
			$db->insert("vb_sovanban",$arraydata);
			$stt++;
		}


		$this->_redirect("/qtht/config/taonammoiinput");
		//exit;
	}

}
