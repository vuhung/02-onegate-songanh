<?php

/**
 * UserController
 *
 * @author
 * @version
 */

require_once 'Zend/Controller/Action.php';
require_once 'hscv/models/hosocongviecModel.php';
require_once 'auth/models/ResourceUserModel.php';
require_once 'qtht/models/UsersModel.php';

class Auth_UserController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated UserController::indexAction() default action
	}
	public function changeyearAction(){
		$this->_helper->layout->disableLayout();
		$year = new Zend_Session_Namespace('year');
		$year->year = $this->getRequest()->getParam("currentyear");
		echo "window.location.reload(true);";
		exit;
	}
	public function delayAction(){
		$this->_helper->layout->disableLayout();
		/*
		 $date = $this->getRequest()->getParam("date");
		 $hanxuly = $this->getRequest()->getParam("hanxuly");
		 if($hanxuly==0)$hanxuly=2;
		 //echo $date.$hanxuly;
		 if($date!="" && $hanxuly>0){
			echo QLVBDHCommon::trehantostr(QLVBDHCommon::getTreHan($date,$hanxuly),$date,$hanxuly);
			}
			*/
		$data = $this->getRequest()->getParam("data");
		$data = explode(",",$data);
		$html = "";
		for($i=0;$i<count($data);$i++){
			$idhscv = $data[$i];
			$i++;
			$date = $data[$i];
			$i++;
			$hanxuly = $data[$i];
			$i++;
			$ureceive = $data[$i];
			if($date!="" && $hanxuly>0){
				//$html .= QLVBDHCommon::trehantostr(QLVBDHCommon::getTreHan($date,$hanxuly),$date,$hanxuly);
				$html .= 'document.getElementById(\'TREHAN'.$idhscv.'\').innerHTML += \''.QLVBDHCommon::trehantostrwithusername(QLVBDHCommon::getTreHan($date,$hanxuly),$date,$hanxuly,UsersModel::getEmloyeeNameByIdUser($ureceive)).'\';';
			}
		}
		echo $html;
		exit;
	}
	public function getnextstepAction(){
		$this->_helper->layout->disableLayout();
		global $auth;
		$user = $auth->getIdentity();
		$year = QLVBDHCommon::getYear();
		$data = $this->getRequest()->getParam("data");
		//echo $data;
		if($data=="")exit;
		$data = explode(",",$data);
		$config = Zend_Registry::get('config');
		for($i=0;$i<count($data);$i++){
			$idhscv = $data[$i];
			$i++;
			$code = $data[$i];
			$i++;
			$count = $data[$i];
			$i++;
			$type = $data[$i];
			$i++;
			$idpi = $data[$i];
			$html = "";

			if(strtoupper($code)=="PRE" || strtoupper($code)=="OLD"){
				$last = WFEngine::GetCurrentTransitionInfoByIdHscv($idhscv);
				if($last['ID_U_NC']==$user->ID_U){
					if($count>2){
						$html .= '<a href="javascript:SwapIframe('.$idhscv.',\\\'/hscv/hscv/thuhoi/year/'.$year.'/id/'.$idhscv.'/type/'.$type.'\\\');">Thu hồi</a><br>';
					}else{

						$createarr = WFEngine::GetCreateProcessButtonFromLoaiCV($type,$user->ID_U);
						if(count($createarr)>0){
							$html .= '<a href="javascript:SwapIframe('.$idhscv.',\\\'/hscv/hscv/chuyenlai/wf_id_t/'.$createarr["ID_T"].'/year/'.$year.'/id/'.$idhscv.'/type/'.$type.'\\\');">Chuyển lại</a><br>';
							if($type != 1){
								$html .= '<a href="'.$createarr["LINK"].'/type/'.$type.'/wf_id_t/'.$createarr["ID_T"].'/year/'.$year."/id_hscv/".$idhscv.'">Cập nhật</a><br>';
							}
							$html .= '<a href="#" onclick="DeleteHscv('.$idhscv.','.$type.');">Xóa</a><br>';
						}
					}
				}else if($config->hscv->vtbp==1){
					if($type==1){
						$actid = ResourceUserModel::getActionByUrl('hscv','hscv','chuyenlaivtbp');
						if(ResourceUserModel::isAcionAlowed($user->USERNAME,$actid[0])){
							if(WFEngine::CanChuyenLaiForVTBP($idhscv)){
								$html .= '<a href="javascript:SwapIframe('.$idhscv.',\\\'/hscv/hscv/chuyenlaivtbp/id/'.$idhscv.'\\\');">Chuyển lại</a><br>';
								$html .= '<a href="#" onclick="DeleteHscvForVTBP('.$idhscv.','.$type.');">Xóa</a><br>';
							}
						}
					}
				}
				//check gia hạn

			}else if(strtoupper($code)==""){
				$action = WFEngine::GetNextTransitions($idpi,$user->ID_U);
				if(count($action)>0){
					foreach($action as $rowa){
						//echo $rowa['LINK'];
						$arraction = explode("/",$rowa['LINK']);
						$actid = ResourceUserModel::getActionByUrl($arraction[1],$arraction[2],$arraction[3]);
						if(ResourceUserModel::isAcionAlowed($user->USERNAME,$actid[0])){
							$html .= '<a href="javascript:SwapIframe('.$idhscv.',\\\''.$rowa["LINK"].'/year/'.$year.'/wf_id_t/'.$rowa["ID_T"].'/id/'.$idhscv.'/type/'.$type.'\\\');">'.$rowa["NAME"].'</a><br>';
						}
					}
					//$link_luutheodoi = "/hscv/";
					$onclick_td = '
						
					';
					$actid = ResourceUserModel::getActionByUrl('hscv','hosoluutheodoi','luutheodoi');
					if(ResourceUserModel::isAcionAlowed($user->USERNAME,$actid[0])){
						//$html .='<a href=javascript:luutheodoiJs("'.$idhscv.'");>Lưu theo dõi</a><br>';
						$html .= '<a href="javascript:SwapIframe('.$idhscv.',\\\'/hscv/hosoluutheodoi/inputluutheodoi/id/'.$idhscv.'\\\');">Lưu theo dõi</a><br>';
					}
					if($type >= 3){
						$actid = ResourceUserModel::getActionByUrl("motcua","motcua","inputbosung");
						if(ResourceUserModel::isAcionAlowed($user->USERNAME,$actid[0])){
							$html .= '<a href="javascript:SwapIframe('.$idhscv.',\\\'/motcua/motcua/inputbosung/id/'.$idhscv.'\\\');">Yêu cầu bổ sung</a><br>';
						}
					}
				}else{
					if($type >= 3){
						$html .= '<a href="javascript:SwapIframe('.$idhscv.',\\\'/motcua/motcua/trahoso/id/'.$idhscv.'/type/'.$type.'\\\');">Trả hồ sơ</a><br>';
					}else{
						$html .= '<a href="javascript:SwapIframe('.$idhscv.',\\\'/hscv/hscv/tofolder/id/'.$idhscv.'/type/'.$type.'\\\');">Lưu thư mục</a><br>';
					}
				}
			}else if(strtoupper($code)=="ZIP"){
				$actid = ResourceUserModel::getActionByUrl('hscv','hscv','tofolder');
				if(ResourceUserModel::isAcionAlowed($user->USERNAME,$actid[0])){
					$html .= '<a href="javascript:SwapIframe('.$idhscv.',\\\'/hscv/hscv/tofolder/id/'.$idhscv.'/type/'.$type.'\\\');">Lưu thư mục</a><br>';
				}
			}//
			$script .= 'document.getElementById(\'NEXTSTEP'.$idhscv.'\').innerHTML = \''.$html.'\';';
			if($i>=count($data)-3)break;
		}
		echo $script;
		exit;
	}
	public function changepasswordAction(){

		global $db;
		global $auth;
		$user = $auth->getIdentity();
		//var_dump($user->USERNAME);
		$this->view->title = "Người dùng";
		$this->view->subtitle = "Thiết lập";
		QLVBDHButton::EnableSave("/auth/user/changepassword");
		$param = $this->getRequest()->getParams();
		$config = new Zend_Config_Ini('../application/config.ini','ldap',true);
		$basedn = explode(",",$config->ldap->username);
		$basedn =str_replace("$basedn[0],","",$config->ldap->username);
		$basedn =str_replace(".","=",$basedn);
		$options = array(
               'host' => $config->ldap->host,
               'port' => (int)$config->ldap->port,
               'username' => str_replace(".","=",$config->ldap->username),
               'password' => $config->ldap->password,
			

		);
			
		if($param['ok']==1){
			if($param['oldpass']!=""){

				//kiem tra mat khau cu va moi co giong nhau khong
				if(md5($param['oldpass'])!=$user->PASSWORD){
					$message = "Mật khẩu cũ không hợp lệ";
				}else{
					if($param['newpass']!=$param['renewpass']){
						$message = "Mật khẩu mới không hợp lệ";
					}else{
							
						if((int)$config->ldap->enable==1)
						{
							try{

								$ldap= new Zend_Ldap($options);
								$ldap->bind();
								$sr=ldap_search($ldap->getResource(),$basedn,"cn=*");
								$info=ldap_get_entries($ldap->getResource(),$sr);

								$sruid=ldap_search($ldap->getResource(),$basedn,"uid=*");
								$infouid=ldap_get_entries($ldap->getResource(),$sruid);
								 
								for ($i=0; $i<$info["count"]; $i++)
								{
									if($info[$i]["cn"][0]==$user->USERNAME)
									{
										$getuserpass=$info[$i];
										$userlap=$getuserpass["dn"];
										break;
									}
								}

								for ($i=0; $i<$infouid["count"]; $i++)
								{
									if($infouid[$i]["uid"][0]==$user->USERNAME)
									{
										$getuserpass=$infouid[$i];
										$userlap=$getuserpass["dn"];
										break;
									}
								}
								ldap_mod_replace($ldap->getResource(),$userlap,
								array('userpassword' => "{MD5}". base64_encode(pack("H*",md5($param['newpass'])))));
								$optionskiemtra = array(
                                              'host' => $config->ldap->host,
                                              'port' => (int)$config->ldap->port,
                                              'username' => $userlap,
                                              'password' => $param['newpass'],
									
								);
								try{
									$ldapkt= new Zend_Ldap($optionskiemtra);
									$ldapkt->bind();
									$db->update("QTHT_USERS",array("PASSWORD"=>md5($param['newpass'])),"ID_U=".$user->ID_U);
								}catch(Zend_Ldap_Exception $e){   echo "<span class=error>Không thể thay đổi mật khẩu </span>";
									
								return; }


									
							}catch (Zend_Ldap_Exception  $e){  echo "<span class=error>Không thể thay đổi mật khẩu </span>";
							}

						}else{
							$db->update("QTHT_USERS",array("PASSWORD"=>md5($param['newpass'])),"ID_U=".$user->ID_U);
						}
							
					}
				}
			}
			//Xóa ủy quyền
			$db->delete("QTHT_MULTIACCOUNT","ID_U = ".$user->ID_U);
			if($param['ID_U']>0){
				$db->insert("QTHT_MULTIACCOUNT",array("ID_U_UQ"=>$param['ID_U'],"ID_U"=>$user->ID_U));
			}
			$this->_redirect("/auth/logout");
		}
		$r = $db->query("SELECT * FROM QTHT_MULTIACCOUNT WHERE ID_U = ".$user->ID_U);
		$this->view->uq = $r->fetch();
		//var_dump($this->view->uq);
		$this->view->message = $message;
		$this->view->user = $user;
	}
	public function alarmAction(){
		$this->_helper->layout->disableLayout();
		global $auth;
		global $db;
		$user = $auth->getIdentity();
		$sql = "select * from ".QLVBDHCommon::Table("GEN_MESSAGE")." where id_u_receive = ? and (status<>1 or status is null)";
		$r = $db->query($sql,$user->ID_U);
		if($r->rowCount()==0){
			$this->_redirect("/auth/user/nomessage");
		}
		$this->view->data = $r->fetchAll();
		$this->view->user = $user;
	}
	public function nomessageAction(){
		$this->_helper->layout->disableLayout();
		global $auth;
		global $db;
		$user = $auth->getIdentity();
		$db->update(QLVBDHCommon::Table("GEN_MESSAGE"),array("STATUS"=>1),"id_u_receive=".$user->ID_U." and ID_MESS in (".$this->getRequest()->getParam("id").")");
		exit;
	}
	public function adddateAction(){
		$this->_helper->layout->disableLayout();
		global $db;
		$param = $this->getRequest()->getParams();
		$date = $param["date"];
		$value = $param["value"];
		$date = strtotime(implode("-",array_reverse(explode("/",$date))));
		echo date("d/m/Y", QLVBDHCommon::addDate($date,$value));
		exit;
	}
	public function checksendableAction(){
		$param = $this->getRequest()->getParams();
		$wf_id_t = $param["wf_id_t"];
		$idu = $param["idu"];
		if(WFEngine::HaveSendAbleByTransition($wf_id_t,$idu)){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
	public function changeaccAction(){
		global $auth;
		global $db;
		$user = $auth->getIdentity();
		$param = $this->getRequest()->getParams();
		$r = $db->query("SELECT
			u.* 
		FROM 
			QTHT_MULTIACCOUNT macc
			INNER JOIN QTHT_USERS u on u.ID_U = macc.ID_U
			INNER JOIN QTHT_EMPLOYEES emp on u.ID_EMP = emp.ID_EMP
		WHERE macc.ID_U_UQ = ? AND macc.ID_U = ?",array($user->ID_U_PARENT,$param['ID_U']));
		//echo $user->ID_U_PARENT." ".$param['ID_U'];
		$u = $r->fetch();
		if($r->rowCount()==1 ||  $param['ID_U'] == $user->ID_U_PARENT){
			$resource4User=new ResourceUserModel();
			$user->ID_U = $param['ID_U'];
			$userinfo = $resource4User->GetUserInfo($param['ID_U']);
			$user->FULLNAME = $userinfo['FULLNAME'];
			$user->ID_DEP = $userinfo['ID_DEP'];
			$user->ID_G = $userinfo['ID_G'];
			$user->ISLEADER = $userinfo['ISLEADER'];
			$user->DEPLEADER = $userinfo['DEPLEADER'];
			$user->USERNAME = $userinfo['USERNAME'];
			$user->PASSWORD = $userinfo['PASSWORD'];
			$acl = Zend_Registry::get("acl") ;
			$acl = new QLVBDH_ACL($auth);
			$menu = new Zend_Session_Namespace('menu');
			unset($menu->menu);
		}
		$this->_redirect("/");
		exit;
	}
	public function helpAction(){
		$this->view->title="Trợ giúp";
		$this->view->subtitle="download";
	}
	public function convertAction(){
		global $db;
		$db->query("TRUNCATE TABLE VBD_VANBANDEN_2008");
		$db->query("TRUNCATE TABLE VBDI_VANBANDI_2008");
		$db->query("TRUNCATE TABLE GEN_FILEDINHKEM_2008");
		$db->query("TRUNCATE TABLE VBD_DONGLUANCHUYEN_2008");
		$db->query("TRUNCATE TABLE HSCV_HOSOCONGVIEC_2008");
		$db->query("TRUNCATE TABLE WF_PROCESSITEMS_2008");
		$db->query("TRUNCATE TABLE WF_PROCESSLOGS_2008");
		$db->query("TRUNCATE TABLE VBD_FK_VBDEN_HSCVS_2008");

		include_once "common/plugin/vbden.php";

		exit;
	}
}
