<?php
require_once('Zend/Controller/Action.php');
require_once('qtht/models/DepartmentsModel.php');
require_once ('qtht/models/DanhMucPhuongModel.php');
require_once('motcua/models/LoaiModel.php');
include_once ('Report/models/DientichdatModel.php');
include_once ('motcua/models/linhvucmotcuaModel.php');
class Report_DientichdatController extends Zend_Controller_Action{
	function init(){
		$this->view->title = "Báo cáo";
		$this->view->subtitle = "Thống kê diện tích đất";
	}
	
	function indexAction(){
		$ID_LV_MC = $param['ID_LV_MC'];
		$this->view->ID_LV_MC = $ID_LV_MC;
		$this->view->linhvuc = new linhvucmotcuaModel();
		$this->view->linhvuc = $this->view->linhvuc->SelectAll();
	}
	
	function thongkeviewAction(){
		$this->_helper->layout->DisableLayout();
		$params = $this->_request->getParams();
			
		$fromdate = $params['fromdate'];
	    $todate = $params['todate'];
		$sel_tinhtrang = $params['sel_tinhtrang'];
		$sel_lhss = $params['sel_lhs'];
		$this->view->data = DientichdatModel::getReportData($fromdate,$todate,$sel_tinhtrang,$sel_lhss);
	   // var_dump($this->view->data);exit;
	}

	function thongkeviewexcelAction(){
		$this->_helper->layout->disableLayout();
		$params = $this->_request->getParams();
	    if($params['h_isexel'] ==1){
			header("Content-Type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=baocaoHSMC.xls;");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		$fromdate = $params['fromdate'];
		$todate = $params['todate'];
		$sel_tinhtrang = $params['sel_tinhtrang'];
		$sel_lhss = $params['sel_lhs'];
		$time=$params['time'];
		if($time==""){
		$this->view->thu="từ ngày"." ".$fromdate." "."đến ngày"." ".$todate;
		}else{
			$this->view->thu=$time;
		}
		$this->view->data = DientichdatModel::getReportData($fromdate,$todate,$sel_tinhtrang,$sel_lhss);
		
	}
	
	function thongkeAction(){
		$this->view->title = "Thống kê";
		$this->view->subtitle = "Diện tích đất";
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
		$this->view->xtodate = implode("-",array_reverse(explode("/",$this->view->xtodate)))." 00:00:00";
		$this->view->xfromdate = $fromdate."/".QLVBDHCommon::getYear();
		$this->view->xfromdate = implode("-",array_reverse(explode("/",$this->view->xfromdate)))." 23:59:59";
	}

	function  thongkeexcelAction(){
		global $auth;
		$this->view->user = $auth->getIdentity();

		$this->_helper->layout->disableLayout();
		header("Content-Type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: attachment; filename=thongkedientichdat.xls;");
		header("Pragma: no-cache");
		header("Expires: 0");

		$this->view->title = "Thống kê";
		$this->view->subtitle = "Diện tích đất";
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
		$this->view->xtodate = implode("-",array_reverse(explode("/",$this->view->xtodate)))." 00:00:00";
		$this->view->xfromdate = $fromdate."/".QLVBDHCommon::getYear();
		$this->view->xfromdate = implode("-",array_reverse(explode("/",$this->view->xfromdate)))." 23:59:59";
	}
}
?>