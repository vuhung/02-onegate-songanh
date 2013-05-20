<?
require_once 'qtht/models/CoquanModel.php';
require_once 'qtht/models/LoaiVanBanModel.php';
require_once 'report/models/Ad_XulyvanbandenModel.php';
class Report_XulyvanbandenController extends Zend_Controller_Action{
		function init(){
		}

		function indexAction(){
			$this->view->title = "Báo cáo";
			$this->view->subtitle = "Xử lý văn bản đến";
		}

		function reportviewAction(){
			$this->_helper->layout->disableLayout();
			
			
			$param = $this->_request->getParams();
			
			$config = Zend_Registry::get("config");
			
			//tham so du lieu
			
			$type = $param['sel_lcv'];//loai cong viec
			if(!$type || $type==0)
				$type = 1;
			$fromdate = $param["fromdate"];
			if($fromdate == "")
				$fromdate = "1/1";
			$todate = $param['todate'];
			if($todate == "")
				$todate = "31/12";		
			$id_pbs = $param["sel_pb"];
			$this->view->type = $type;
			$this->view->todate = $todate;
			$this->view->fromdate = $fromdate;
			
			
			
			$arrparam = array();
			
			
			
			$arrparam["type_date"] = (int)$param["type_date"];
			$this->view->type_date = $arrparam["type_date"];

			$arrparam["sel_lvb"] = $param["sel_lvb"];
			$arrparam["op_lvb"] = (int)$param["op_lvb"];
			
			$this->view->sel_lvb = $param["sel_lvb"];
			$this->view->op_lvb = (int)$param["op_lvb"];

			$arrparam["sel_cqbh"] = $param["sel_cqbh"];
			$arrparam["op_cqbh"] = (int)$param["op_cqbh"];
			
			$this->view->sel_cqbh = $param["sel_cqbh"];
			$this->view->op_cqbh = $param["op_cqbh"];
			
			$arrparam["sel_trangthai"] = (int)$param["sel_trangthai"];
			$arrparam["op_trangthai"] = (int)$param["op_trangthai"];
			

			$this->view->sel_trangthai = (int)$param["sel_trangthai"];
			$this->view->op_trangthai = $arrparam["op_trangthai"];

			$this->view->subtitle="từ ngày $fromdate đến ngày $todate";
			
			//tham so phan trang
			$page = $param['page'];
			$limit = $param['limit1'];
			if($limit==0 || $limit=="")$limit=$config->limit;
			if($page==0 || $page=="")$page=1;
			$this->view->page = $page;
			$this->view->limit = $limit;
			
			//echo "111111122222"; 
			
			$arr_thongke = Ad_XulyvanbandenModel::thongke($type,$todate,$fromdate,$arrparam);
			$this->view->arr_thongke = $arr_thongke;
			//var_dump($arr_thongke);
			$count = $arr_thongke["dem"];
			//Ad_XulyvanbandenModel::getCountReportData($type,$todate,$fromdate,$arrparam);
			
			//echo $count; exit;
			$this->view->showPage = QLVBDHCommon::PaginatorWithAction($count,10,$limit,"frm",$page,"/report/Xulyvanbanden/reportview");
			$this->view->data = Ad_XulyvanbandenModel::getReportData($type,$todate,$fromdate,$arrparam,($page-1)*$limit,$limit);
			
			
			//$this->view->arr_thongke = $arr_thongke;
			//var_dump($arr_thongke);
			//exit;
			//var_dump($this->view->data); exit;
		}
		function reportviewexcelAction() {
			$this->_helper->layout->disableLayout();
			header("Content-Type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=baocaovanbanden.xls;");
			header("Pragma: no-cache");
			header("Expires: 0");
				$this->_helper->layout->disableLayout();
			
			
			$param = $this->_request->getParams();
			
			$config = Zend_Registry::get("config");
			
			$type = $param['sel_lcv'];//loai cong viec
			if(!$type || $type==0)
				$type = 1;
			$fromdate = $param["fromdate"];
			if($fromdate == "")
				$fromdate = "1/1";
			$todate = $param['todate'];
			if($todate == "")
				$todate = "31/12";		
			$id_pbs = $param["sel_pb"];
			$this->view->type = $type;
			$this->view->todate = $todate;
			$this->view->fromdate = $fromdate;
			
			
			
			$arrparam = array();
			
			
			
			$arrparam["type_date"] = (int)$param["type_date"];
			$this->view->type_date = $arrparam["type_date"];

			$arrparam["sel_lvb"] = $param["sel_lvb"];
			$arrparam["op_lvb"] = (int)$param["op_lvb"];
			
			$this->view->sel_lvb = $param["sel_lvb"];
			$this->view->op_lvb = (int)$param["op_lvb"];

			$arrparam["sel_cqbh"] = $param["sel_cqbh"];
			$arrparam["op_cqbh"] = (int)$param["op_cqbh"];
			
			$this->view->sel_cqbh = $param["sel_cqbh"];
			$this->view->op_cqbh = $param["op_cqbh"];
			
			$arrparam["sel_trangthai"] = (int)$param["sel_trangthai"];
			$arrparam["op_trangthai"] = (int)$param["op_trangthai"];
			

			$this->view->sel_trangthai = (int)$param["sel_trangthai"];
			$this->view->op_trangthai = $arrparam["op_trangthai"];

			$this->view->subtitle="từ ngày $fromdate đến ngày $todate";
			
			//tham so phan trang
			/*$page = $param['page'];
			$limit = $param['limit1'];
			if($limit==0 || $limit=="")$limit=$config->limit;
			if($page==0 || $page=="")$page=1;
			$this->view->page = $page;
			$this->view->limit = $limit;*/
			
			//echo "111111122222"; 
			
			$arr_thongke = Ad_XulyvanbandenModel::thongke($type,$todate,$fromdate,$arrparam);
			$this->view->arr_thongke = $arr_thongke;
			//var_dump($arr_thongke);
			$count = $arr_thongke["dem"];
			//Ad_XulyvanbandenModel::getCountReportData($type,$todate,$fromdate,$arrparam);
			
			//echo $count; exit;
			//$this->view->showPage = QLVBDHCommon::PaginatorWithAction($count,10,$limit,"frm",$page,"/report/Xulyvanbanden/reportview");
			$this->view->data = Ad_XulyvanbandenModel::getReportData($type,$todate,$fromdate,$arrparam,0,$count);
			
			
		
			

		}
}