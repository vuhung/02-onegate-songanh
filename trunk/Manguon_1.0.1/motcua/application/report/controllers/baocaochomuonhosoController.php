<?php

require_once ('Zend/Controller/Action.php');
require_once 'report/models/XulyvanbandenModel.php';
require_once 'report/models/baocaochomuonhosoreportModel.php';
class Report_baocaochomuonhosoController extends Zend_Controller_Action {
	function init(){
		$this->view->title = "Báo cáo";
		$this->view->subtitle = "Báo cáo cho mượn hồ sơ";
	}
	
	function indexAction(){		
		
	}	
	function reportviewAction(){ 		
		$this->_helper->layout->disableLayout();
		$param = $this->_request->getParams();
	   // var_dump($param);exit;
		/*if($param['h_isexel'] ==1){
			header("Content-Type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=baocaovanbanden.xls;");
			header("Pragma: no-cache");
			header("Expires: 0");
		}*/
		
		//lay id co quan ban hanh
		$fromdate = $param['fromdate'];
		$todate = $param['todate'];
		$id_lhs = $param['sel_lhs'];
		$id_tt = $param['sel_tt'];		
		$this->view->data = baocaochomuonhosoreportModel::getReportData($fromdate,$todate,$id_tt,$id_lhs);		
		$is_in = $param['is_in'];
		if($is_in){
			global $config;
			$config = Zend_Registry::get("config");
			$this->view->config = $config;
			if($fromdate == "")
				$fromdate = "1/1/2010";
			$todate = $param['todate'];
			if($todate == "")
				$todate = "31/12/2010";
			$this->view->thu="Từ ngày"." ".$fromdate." "."đến ngày"." ".$todate;
			$this->renderScript("baocaochomuonhoso/reportview_in.phtml");
		}
		
	}	

	function reportviewexcelAction(){
		$this->_helper->layout->disableLayout();
		$param = $this->_request->getParams();
		global $config;
		$config = Zend_Registry::get("config");
		$this->view->config = $config;
		if($param['h_isexel'] ==1){
			header("Content-Type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=baocaochomuonhoso.xls;");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		$fromdate = $param['fromdate'];
		$todate = $param['todate'];
		if($fromdate == "")
				$fromdate = "1/1/2010";
			
		if($todate == "")
				$todate = "31/12/2010";
			$this->view->thu="TỪ NGÀY"." ".$fromdate." "."ĐẾN NGÀY"." ".$todate;
		$id_lhs = $param['sel_lhs'];
		$id_tt = $param['sel_tt'];		
		$this->view->data = baocaochomuonhosoreportModel::getReportData($fromdate,$todate,$id_tt,$id_lhs);
	}

	function indextkeAction(){
		$param = $this->_request->getParams();	
		//var_dump($param["fromdate"]);exit;
        $this->view->todate = $param["todate"]; 
		$this->view->fromdate = $param["fromdate"];

		if($param["fromdate"] ==""){
		 $this->view->fromdatex = "1/1/2010";		
		}else{
			$this->view->fromdatex = $param["fromdate"];
			}
		if($param["todate"] ==""){
		    $this->view->todatex = "31/12/2010";		
		}else{
			$this->view->todatex = $param["todate"];
			}		
		$this->view->param = $param;	//var_dump($param);exit;		
		$this->view->id_lhs=$param["sel_lhs"];
		$this->view->hienthi = $param['in'];
		//var_dump($this->view->id_lhs);exit;
		$this->view->title="Thống kê";
		$this->view->subtitle="hồ sơ cho mượn";
	}
	
	function xuatwebtkeAction(){
		global $auth;
		$user = $auth->getIdentity();
		$config = Zend_Registry::get('config');
		$this->view->config = $config;		
		$this->_helper->layout->disableLayout();
		$param = $this->_request->getParams();
		//var_dump($param);exit;
		$this->view->todate = $param["todate"]; 
		$this->view->fromdate = $param["fromdate"];
		$this->view->param = $param;		//var_dump($param);exit;
		$this->view->id_pb=$param["sel_pb"];
		$this->view->id_lhs=$param["sel_lhs"];
		$this->view->thu="Từ ngày"." ".$this->view->fromdate." "."đến ngày"." ".$this->view->todate;
		//var_dump($this->view->id_lhs);exit;
		$this->view->title="Thống kê";
		$this->view->subtitle="hồ sơ cho mượn";
	}
	function xuatexceltkeAction(){
		$this->_helper->layout->disableLayout();		
		header("Content-Type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: attachment; filename=thongkechomuonhoso.xls;");
		header("Pragma: no-cache");
		header("Expires: 0");		
		global $auth;
		$user = $auth->getIdentity();
		$config = Zend_Registry::get('config');
		$this->view->config = $config;
		$param = $this->_request->getParams();
		//var_dump($param);exit;
		$this->view->todate = $param["todate"]; 
		$this->view->fromdate = $param["fromdate"];
		$this->view->param = $param;		//var_dump($param);exit;
		$this->view->id_pb=$param["sel_pb"];
		$this->view->id_lhs=$param["sel_lhs"];
		$this->view->thu="Từ ngày"." ".$this->view->fromdate." "."đến ngày"." ".$this->view->todate;
		//var_dump($this->view->id_lhs);exit;
		$this->view->title="Thống kê";
		$this->view->subtitle="hồ sơ cho mượn";
	}	
}

?>