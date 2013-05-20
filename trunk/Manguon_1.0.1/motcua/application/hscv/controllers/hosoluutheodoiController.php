<?php
require_once 'Zend/Controller/Action.php';
require_once 'hscv/models/HosoluutheodoiModel.php';
require_once 'hscv/models/loaihosocongviecModel.php';
require_once 'hscv/models/butpheModel.php';
require_once 'vbden/models/vbdenModel.php';
require_once 'vbden/models/vbd_dongluanchuyenModel.php';
require_once 'hscv/models/ThuMucModel.php';
require_once 'config/hscv.php';
require_once('qtht/models/UsersModel.php');
require_once('auth/models/ResourceUserModel.php');
class Hscv_hosoluutheodoiController extends Zend_Controller_Action {
	
	function init(){
		
	}
	
	function indexAction(){
		global $auth;
		$user = $auth->getIdentity();
		$param = $this->getRequest()->getParams();
		$config = Zend_Registry::get('config');
		
		$page = $param['page'];
		$limit = $param['limit1'];
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		$scope = array();
		if($param['SCOPE']){
			$scope = $param['SCOPE'];
		}
		if($param['NGAY_BD']!=""){
			$ngaybd = $param['NGAY_BD']."/".QLVBDHCommon::getYear();
			$ngaybd = implode("-",array_reverse(explode("/",$ngaybd)));
		}
		if($param['NGAY_KT']!=""){
			$ngaykt = $param['NGAY_KT']."/".QLVBDHCommon::getYear();
			$ngaykt = implode("-",array_reverse(explode("/",$ngaykt)));
		}
		$ID_LOAIHSCV = $param['ID_LOAIHSCV'];
		$NAME = $param['NAMECV'];
		
		$parameter = array(
			"ID_LOAIHSCV"=>$ID_LOAIHSCV,
			"NGAY_BD"=>$ngaybd,
			"NGAY_KT"=>$ngaykt,
			"ID_U"=>$user->ID_U,
			"NAME"=>$NAME,
			"SCOPE"=>$scope
		);
		
		$hscvcount = HosoluutheodoiModel::Count($parameter);
		$this->view->ID_LOAIHSCV = $ID_LOAIHSCV;
		$this->view->data = HosoluutheodoiModel::SelectAll($parameter,($page-1)*$limit,$limit,"");
		$this->view->NGAY_BD = $param['NGAY_BD'];
		$this->view->NGAY_KT = $param['NGAY_KT'];
		$this->view->NAME = $param['NAMECV'];
		$this->view->title="Hồ sơ công việc";
		$this->view->subtitle="Lưu theo dõi";
		$this->view->SCOPE = $scope;
		$this->view->page = $page;
		$this->view->limit = $limit;
		$this->view->showPage = QLVBDHCommon::PaginatorWithAction($hscvcount,10,$limit,"frm",$page,"/hscv/hosoluutheodoi/index");
		$this->view->loaihscv = WFEngine::GetLoaiCongViecFromUser($user->ID_U);
		$this->view->vbden = new vbdenModel(QLVBDHCommon::getYear());
	}
	
	function inputluutheodoiAction(){
		$params = $this->_request->getParams();
		$this->_helper->Layout->disableLayout();
		$this->view->ID_HSCV = $params["id"]; 
	}

	function luutheodoiAction(){
		$this->_helper->layout->disableLayout();
		$params = $this->_request->getParams();
		//var_dump($params); exit;
		$comment = $params["comment"];
		$id_hscv = $params['id_hscv'];
		$year = QLVBDHCommon::getYear();
		$authen = Zend_Registry::get('auth');
		$user = $authen->getIdentity();
		$re = HosoluutheodoiModel::luuTheodoi($year,$id_hscv,$comment,$user->ID_U);
		echo "<script>window.parent.document.frm.submit();</script>";
		//echo $re;
		exit;	
	}
	
	function phuchoiluutheodoiAction(){
		global $auth;
		$user = $auth->getIdentity();
		$param = $this->getRequest()->getParams();
		echo HosoluutheodoiModel::phuchoiluuTheodoi($param['id'],$user->ID_U);
		echo "document.frm.submit();";
		exit;
	}
	
}