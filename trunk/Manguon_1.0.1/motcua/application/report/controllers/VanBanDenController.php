<?php

require_once ('Zend/Controller/Action.php');
require_once 'report/models/XulyvanbandenModel.php';
require_once 'report/models/VanbandenreportModel.php';
require_once 'report/models/Ad_XulyvanbandenModel.php';
class Report_VanBanDenController extends Zend_Controller_Action {
	function init(){
		$this->view->title = "Báo cáo";
		$this->view->subtitle = "Báo cáo văn bản đến";
	}
	
	function indexAction(){
		
		
	}
	
	function reportviewAction(){
		
		
		$this->_helper->layout->disableLayout();
		$param = $this->_request->getParams();
		/*if($param['h_isexel'] ==1){
			header("Content-Type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=baocaovanbanden.xls;");
			header("Pragma: no-cache");
			header("Expires: 0");
		}*/
		
		//lay id co quan ban hanh
		$fromdate = $param['fromdate'];
		$todate = $param['todate'];
		$id_lvbs = $param['sel_lvb'];
		$id_svb = $param['sel_svb'];
		$this->view->data = VanbandenreportModel::getReportData($fromdate,$todate,$id_svb,$id_lvbs);
		
	}

	function reportviewexcelAction(){
		$this->_helper->layout->disableLayout();
		$param = $this->_request->getParams();
		if($param['h_isexel'] ==1){
			header("Content-Type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=baocaovanbanden.xls;");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		$fromdate = $param['fromdate'];
		$todate = $param['todate'];
		$id_lvbs = $param['sel_lvb'];
		$id_svb = $param['sel_svb'];
		$this->view->data = VanbandenreportModel::getReportData($fromdate,$todate,$id_svb,$id_lvbs);
	}
}

?>
