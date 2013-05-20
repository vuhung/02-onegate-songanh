<?php
require_once('Zend/Controller/Action.php');
require_once('Report/models/HosochuatiepnhanModel.php');
require_once('qtht/models/DepartmentsModel.php');
require_once('qtht/models/DanhMucPhuongModel.php');
require_once('motcua/models/LoaiModel.php');
require_once 'motcua/models/linhvucmotcuaModel.php';
require_once('motcua/models/motcua_hosoModel.php');
include_once 'qtht/models/DanhMucPhuongModel.php';

class Report_HosochuatiepnhanController extends Zend_Controller_Action{
	function init(){
		$this->view->title = "Báo cáo";
		$this->view->subtitle = "Báo cáo tình hình tiếp nhận hồ sơ qua mạng";
	}
	
	function indexAction(){		
		$ID_LV_MC = $param['ID_LV_MC'];
		$this->view->ID_LV_MC = $ID_LV_MC;
		$this->view->linhvuc = new linhvucmotcuaModel();
		$user = Zend_Registry::get('auth')->getIdentity();
		$this->view->linhvuc = $this->view->linhvuc->SelectAllByUID($user->ID_U);
	}
	
	function reportviewAction(){
		$this->_helper->layout->DisableLayout();
		$params = $this->_request->getParams();				 
		// echo date("Y-m-d 00:00:00"); exit;	
		$fromdate = $params['fromdate'];		
	    $todate = $params['todate'];
		$sel_tinhtrang = $params['sel_tinhtrang'];
		$sel_lhss = $params['sel_lhs'];		
		$this->view->sel_lhss = $params['sel_lhs'];		
		if($params['CHOICEALL_LOAI'] == 1)
			$this->view->sel_lhss = HosochuatiepnhanModel::getIDLOAIByLV($params["ID_LV_MC"]);		
		$this->view->trangthai = $params['sel_tinhtrang'];
		$this->view->fromdate =  $params['fromdate'];
		$this->view->todate = $params['todate'];				
		
		$this->view->data = HosochuatiepnhanModel::getReportData($fromdate,$todate,$sel_tinhtrang,$this->view->sel_lhss);
		// print_r($this->view->sel_lhss);exit;		
	}

	

	function reportviewexcelAction(){
		$this->_helper->layout->disableLayout();
		$params = $this->_request->getParams();
		//var_dump($params);exit;
		$this->view->params = $params;
		$config = Zend_Registry::get('config');
		$this->view->config = $config;
		$year = QLVBDHCommon::getYear();
		$this->view->year=$year;		
	    if($params['h_isexel'] ==1){
			header("Content-Type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=baocaoHSMC.xls;");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		$this->_helper->layout->DisableLayout();
		$params = $this->_request->getParams();		
		//echo date("Y-m-d 00:00:00"); exit;	
		$fromdate = $params['fromdate'];
	    $todate = $params['todate'];
		$sel_tinhtrang = $params['sel_tinhtrang'];
		$sel_lhss = $params['sel_lhs'];		
		$this->view->sel_lhss = $params['sel_lhs'];
		if($params['CHOICEALL_LOAI'] == 1)
			$this->view->sel_lhss = HosochuatiepnhanModel::getIDLOAIByLV($params["ID_LV_MC"]);		
		$this->view->trangthai = $params['sel_tinhtrang'];
		$this->view->fromdate =  $params['fromdate'];
		$this->view->todate = $params['todate']; 				
		$this->view->ID_LV_MC = $params["ID_LV_MC"];		
		$this->view->h_isexel=$params['h_isexel'];				
		if($fromdate == "")
			$fromdate = "1/1";
		
		if($todate == "")
			$todate = "31/12";	
	    $time=$params['time'];
	    
		if($time==""){
		$this->view->thu="TỪ NGÀY"." ".$fromdate." "."ĐẾN NGÀY"." ".$todate;
		}else{
			$this->view->thu=$time;}						
		}
		
	function reporthosoAction(){
		
		$this->view->title = "Thống kê";
		$this->view->subtitle = "Số lượng hồ sơ";
		$this->view->param = $this->_request->getParams();		
		$fromdate = $this->view->param["fromdate"];
		if($fromdate == "")
			$fromdate = "1/1";
		$todate = $this->view->param['todate'];
		if($todate == "")
			$todate = "31/12";
		$this->view->todate = $todate;
		$this->view->fromdate = $fromdate;

		$this->view->xtodate = $this->view->todate;
		$this->view->xfromdate = $this->view->fromdate;
		$this->view->xtodate = $todate."/".QLVBDHCommon::getYear();
		$this->view->xtodate = implode("-",array_reverse(explode("/",$this->view->xtodate)))." 23:59:59";
		$this->view->xfromdate = $fromdate."/".QLVBDHCommon::getYear();
		$this->view->xfromdate = implode("-",array_reverse(explode("/",$this->view->xfromdate)))." 00:00:00";

		$fromdategt = $this->view->param["fromdategt"];
		if($fromdategt == "")
			$fromdategt = "1/1/".QLVBDHCommon::getYear();
		$todategt = $this->view->param['todategt'];
		if($todategt == "")
			$todategt = "31/12/".QLVBDHCommon::getYear();
		$this->view->todategt = $todategt;
		$this->view->fromdategt = $fromdategt;

		$this->view->xtodategt = $this->view->todategt;
		$this->view->xfromdategt = $this->view->fromdategt;
		
		//$this->view->xtodategt = $todategt."/".QLVBDHCommon::getYear();
		$this->view->xtodategt = implode("-",array_reverse(explode("/",$this->view->xtodategt)))." 23:59:59";
		//var_dump($this->view->xfromdategt);exit;
		//$this->view->xfromdategt = $fromdategt."/".QLVBDHCommon::getYear();
		$this->view->xfromdategt = implode("-",array_reverse(explode("/",$this->view->xfromdategt)))." 00:00:00";
	}
	function reporthosoexcelAction(){
		global $auth;
		$config = Zend_Registry::get('config');
		$this->view->config = $config;
		$this->view->user = $auth->getIdentity();
		$this->view->param = $this->_request->getParams();		
		$this->_helper->layout->disableLayout();
		if($this->view->param["excel"]==1){
			header("Content-Type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=thongkehoso.xls;");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		$this->view->title = "Thống kê";
		$this->view->subtitle = "Số lượng hồ sơ";
		
		$fromdate = $this->view->param["fromdate"];
		if($fromdate == "")
			$fromdate = "1/1";
		$todate = $this->view->param['todate'];
		if($todate == "")
			$todate = "31/12";
		$this->view->todate = $todate;
		$this->view->fromdate = $fromdate;

		$this->view->xtodate = $this->view->todate;
		$this->view->xfromdate = $this->view->fromdate;
		$this->view->xtodate = $todate."/".QLVBDHCommon::getYear();
		$this->view->xtodate = implode("-",array_reverse(explode("/",$this->view->xtodate)))." 23:59:59";
		$this->view->xfromdate = $fromdate."/".QLVBDHCommon::getYear();
		$this->view->xfromdate = implode("-",array_reverse(explode("/",$this->view->xfromdate)))." 00:00:00";

		$fromdategt = $this->view->param["fromdategt"];
		if($fromdategt == "")
			$fromdategt = "1/1/".QLVBDHCommon::getYear();
		$todategt = $this->view->param['todategt'];
		if($todategt == "")
			$todategt = "31/12/".QLVBDHCommon::getYear();
		$this->view->todategt = $todategt;
		$this->view->fromdategt = $fromdategt;

		$this->view->xtodategt = $this->view->todategt;
		$this->view->xfromdategt = $this->view->fromdategt;
		//$this->view->xtodategt = $todategt."/".QLVBDHCommon::getYear();
		$this->view->xtodategt = implode("-",array_reverse(explode("/",$this->view->xtodategt)))." 23:59:59";
		//$this->view->xfromdategt = $fromdategt."/".QLVBDHCommon::getYear();
		$this->view->xfromdategt = implode("-",array_reverse(explode("/",$this->view->xfromdategt)))." 00:00:00";
	}
}
?>