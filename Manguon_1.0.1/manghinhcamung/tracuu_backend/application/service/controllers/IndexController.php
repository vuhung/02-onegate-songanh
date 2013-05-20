<?php

/**
 * index
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
require_once 'service/models/alarm.php';
require_once 'service/models/common.php';

require_once "Zend/Exception.php";
class Service_IndexController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		//echo "aaa";
		exit;
	}
	public function getdataAction() {
		$param = $this->getRequest()->getParams();
		if($param['sid']==""){
			exit;
		}
		$user = Common::GetAuth($param['sid']);
		echo Alarm::GetData($user);exit;
	}
	public function loginAction(){
		$param = $this->getRequest()->getParams();
		$sid = Common::Login($param['username'],$param['password']);
		if(count($sid)==2){
			echo "<sessionid>".$sid[0]."</sessionid>";
		}else{
			echo "<sessionid>0</sessionid>";
		}
		exit;
	}
}


