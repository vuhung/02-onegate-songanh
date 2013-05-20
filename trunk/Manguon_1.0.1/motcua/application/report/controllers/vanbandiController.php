<?php 
require_once ('Zend/Controller/Action.php');
require_once ('report/models/vanbandireportModel.php');
class Report_vanbandiController extends Zend_Controller_Action{
	function init(){
	}
	
	function indexAction(){
		$this->view->title = "Báo cáo";
		$this->view->subtitle = "Văn bản đi";
	}
	
	function reportviewAction(){
		$this->_helper->Layout->disableLayout();
		$params = $this->_request->getParams();
		$id_svb = $params['sel_sovanban'];
		$fromdate = $params['fromdate'];
		$todate = $params['todate'];
		$this->view->data = vanbandireportModel::getReportData($fromdate,$todate,$id_svb);
	}
	function reportviewexcelAction(){
		$this->_helper->Layout->disableLayout();
		$params = $this->_request->getParams();
		
		header("Content-Type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: attachment; filename=baocaovanbandi.xls;");
		header("Pragma: no-cache");
		header("Expires: 0");
		$config = Zend_Registry::get("config");
		$this->view->config = $config;
		$id_svb = $params['sel_sovanban'];
		$fromdate = $params['fromdate'];
		$todate = $params['todate'];
		$this->view->data = vanbandireportModel::getReportData($fromdate,$todate,$id_svb);
		if($fromdate == "")
			$this->fromdate = "1/1";				
		if($todate == "")
			$this->todate = "31/12";
		$this->view->thu="Từ ngày"." ".$fromdate." "."đến ngày"." ".$todate;
	}
}
?>