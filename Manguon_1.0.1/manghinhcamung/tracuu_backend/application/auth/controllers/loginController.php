<?php

require_once ('Zend/Controller/Action.php');
require_once 'auth/models/ResourceUserModel.php';
require_once 'auth/models/LogModel.php';

class Auth_loginController extends Zend_Controller_Action {


	protected function _getAuthAdapter($formData)
	{
		global $config;
		global $db;
		global $auth;

		$authAdapter = new Zend_Auth_Adapter_DbTable($db);
		$authAdapter->setTableName('qtht_users')->setIdentityColumn('USERNAME')
		->setCredentialColumn('PASSWORD');
			
		// get "salt" for better security      |#3
		// $config = Zend_Registry::get('config');     |
		/// $salt = $config->auth->salt;      |
		/// $password = sha1($salt.$formData['password']);   |
			
		$authAdapter->setIdentity($formData['username']);
		if($formData['nomd5']=="yes"){
			$authAdapter->setCredential($formData['password']);
		}else{
			$authAdapter->setCredential(md5($formData['password']));
		}
		//echo $formData['username'].$formData['password'];
		return $authAdapter;
	}

	function init()
	{

	}

	function indexAction()
	{    global $db;
	$formdata = $this->_request->getParams();
	//var_dump(ResourceUserModel::getActionByIdUser(1));
	//var_dump(ResourceUserModel::getActionByUrl('qtht','demo'));
	//var_dump(ResourceUserModel::getAllActionID());

	//Lay authentication va acl
	$_auth = Zend_Registry::get('auth');
	$_acl = Zend_Registry::get('acl');
	$config = new Zend_Config_Ini('../application/config.ini','ldap',true);
	

	
	if($this->_request->isPost() || $this->_request->getParam("code")=="mini")
	{		//Lay du lieu du nguoi dung
		if($formdata["username"]!=null & $formdata["password"]!=null)
		{
			$resource4User=new ResourceUserModel();
			if($resource4User->getActionForUser($formdata["username"],$this->_request->getParam("nomd5")=="yes"?$formdata["password"]:md5($formdata["password"])))
			{
				$authAdapter = $this->_getAuthAdapter($formdata);
				//Kiem tra dang nhap
				$result = $_auth->authenticate($authAdapter);
				if($result->isValid()) //dang nhap thanh cong
				{
					$year = new Zend_Session_Namespace('year');
					$year->year = $formdata['currentyear'];
					//Tao session va luu du lieu xuong session
					$datasession = $authAdapter->getResultRowObject(array('ID_U','USERNAME','PASSWORD'));
					//lay nhan vien tuong ung voi username
					//var_dump($datasession->ID_U);
					
					$_auth->getStorage()->write($datasession);
					if($this->_request->getParam("code")=="mini"){
						if($this->_request->getParam("url")!=""){
							$this->_redirect($this->_request->getParam("url"));
						}else{
							$this->_redirect('/auth/user/alarm');
						}
					}else{
						$this->_redirect('/default');
					}
				}
				else
				{//var_dump($this->_request->getParams());
					echo "<span class=error>Tên đăng nhập và mật mã không đúng</span>";
					//LogModel::Logging('auth','login','index',"1");
				}
			}
			else
			{
				echo "<span class=error>Tên đăng nhập và mật mã không đúng</span>";
				//LogModel::Logging('auth','login','index',"1");
				//Zend_Registry::set('auth',null);
			}
		}
		else
		{
			echo "<span class=error>Nhập tên và mật khẩu</span>";
		}
	}else{
		$param = $this->_request->getParams();
		if($param['code']=="mini"){
			$this->_helper->layout->disableLayout();
		}
		$this->view->code = $param['code'];
	}


	}
	function registerAction()
	{

	}

	function erraclAction()
	{
		$this->_helper->layout->disableLayout();

	}
}

?>
