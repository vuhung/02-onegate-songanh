<?php
require_once 'Zend/Controller/Action.php';
require_once 'report/models/tamnghivanghihanModel.php';
class  Report_TamnghivanghihanController extends Zend_Controller_Action{
	function init(){
		$this->view->title="Báo cáo";
		$this->view->subtitle="Báo cáo tạm nghĩ và nghĩ hẳn";
	}
	
	
    function indexAction(){
		$param = $this->_request->getParams();		
		$this->view->todate = $param["todate"]; 
		$this->view->fromdate = $param["fromdate"];
		
	}
  function reportviewAction(){
        $this->_helper->layout->disableLayout();
		$param = $this->_request->getParams();		
		$this->view->todate = $param["todate"]; 
		$this->view->fromdate = $param["fromdate"];
		$this->view->param = $param;
		$fromdate = $param["fromdate"];
		$todate= $param["todate"];
		if($fromdate == "")
			$fromdate = "1/1";
		$todate = $param['todate'];
		if($todate == "")
			$todate = "31/12";	
		
		$this->view->subtitle="Báo cáo tạm nghĩ và nghĩ hẳn";
		$this->view->dataub=tamnghivanghihanModel::getReportDataUB($fromdate,$todate);		
		$this->view->datatckh=tamnghivanghihanModel::getReportDataTCKH($fromdate,$todate);
		
	}
	
	///
	function reporttnnhexcelAction(){
	   	$this->_helper->layout->disableLayout();
		$param = $this->_request->getParams();	
		//var_dump($param['ngaygio']);exit;			
		$config = Zend_Registry::get('config');
		$this->view->config = $config;	
		$year = QLVBDHCommon::getYear();
		$this->view->year=$year;
		$this->view->todate = $param["todate"]; 
		$this->view->fromdate = $param["fromdate"];	
		if($params['h_isexel'] ==1){
		header("Content-Type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: attachment; filename=tamnghivanghihan.xls;");
		header("Pragma: no-cache");
		header("Expires: 0");
		}
		$this->view->param = $param;
		$fromdate = $param["fromdate"];
		$todate= $param["todate"];
		if($fromdate == "")
			$fromdate = "1/1";
		$todate = $param['todate'];
		if($todate == "")
			$todate = "31/12";	
	    $time=$param['ngaygio'];
		if($time==""){
		$this->view->thu="TỪ NGÀY"." ".$fromdate." "."ĐẾN NGÀY"." ".$todate;
		}else{
			$this->view->thu=$time;}		
		$this->view->dataub=tamnghivanghihanModel::getReportDataUB($fromdate,$todate);		
		$this->view->datatckh=tamnghivanghihanModel::getReportDataTCKH($fromdate,$todate);
	}

}


