<?php 
require_once('motcua/models/DongboHSMCModel.php');
require_once('motcua/models/dkwebModel.php');
require_once('qtht/models/usersModel.php');
require_once('qtht/models/adapter.php');
class Motcua_DongboHSMCController extends Zend_Controller_Action{
	
	function init(){
		$this->view->title = "Đồng bộ hồ sơ một cửa";
	}
	
	function indexAction(){
		$this->view->subtitle = "Danh sách hồ sơ đăng ký qua mạng";
		$params = $this->getRequest()->getParams();
		$this->view->data = dkwebModel::getHosoFromWebsite($params);
		//echo 'bà điên0'; exit;
		$this->view->LOAIHSMCID = $params["LOAIHSMCID"];
		$this->view->TRANGTHAI = $params["TRANGTHAI"];
		QLVBDHButton::AddButton("Đồng bộ hồ sơ","","SyncButton","Receive()");
	}

	function detailAction(){
		$this->_helper->layout->disableLayout();
		$params = $this->getRequest()->getParams();
		$mahoso = $params["mhs"];
		//echo '<h1>'.$mahoso.'</h1>'; exit;
		//dkwebModel::getFileMotCua($mahoso);
		$this->view->data 		= dkwebModel::getDetail($params["id"]);
		$this->view->file_data 	= dkwebModel::getFileDetail($this->view->data["MAHOSO"]);	
	}

	function nosignAction(){
		$params = $this->getRequest()->getParams();		
		//DongboHSMCModel::updateTiepnhan(2,$params["id_dkquamang"]);		
		// dkwebModel::delete($params["id_dkquamang"]);
		dkwebModel::updateTiepnhan($params["id_dkquamang"],3);
		$this->_redirect("/motcua/dongbohsmc/index");
	}

	function traodoiAction(){
		$this->_helper->layout->disableLayout();
		$params = $this->_request->getParams();
		$this->view->ID_MC_HSW = $params["id"];
		$this->view->data  = dkwebModel::getGopy($params["id"]);
		$this->view->code = $params["code"];
		//var_dump($data);
		//$datesend = date("Y-m-d");
		//$params["DATESEND"] = $datesend;
		//echo $datesend;	
	}
	function traodoisaveAction(){
		$users = Zend_Registry::get('auth')->getIdentity();
		$this->_helper->layout->disableLayout();
		$params = $this->_request->getParams();
		$datesend = date("Y-m-d");
		$params["DATESEND"] = $datesend;
		$datahs = dkwebModel::getDetail($params["ID_MC_HSW"]);
		$params["MAHOSO"] = $datahs["MAHOSO"];
		$adapter = new adapter();
		$params["NAME_U"] = usersModel::getEmloyeeNameByIdUser($users->ID_U);
		dkwebModel::insertGopy($params);
		//if($params["code"] == "khonghople"){
		$datahs = dkwebModel::getDetail($params["ID_MC_HSW"]);		
		dkwebModel::updateTrangthai($params["ID_MC_HSW"],2);
		dkwebModel::updateTiepnhan($params["ID_MC_HSW"],2);
		$adapter->InsertHSMCService($datahs["MAHOSO"],$datahs["TENTOCHUCCANHAN"],$datahs["TENHOSO"],2,$params["NOIDUNG"],$datahs["DIENTHOAI"],$datahs["GUI_LUC"]);
		//}
		
		$this->_redirect("/motcua/dongbohsmc/traodoi/id/".$params["ID_MC_HSW"]);
		exit;

	}

	function viewdonAction(){
		$this->_helper->layout->disableLayout();
		$params = $this->_request->getParams();
		$print = $params["print"];
		$this->view->print = $print;
		$this->view->ID_MC_HSW = $params["id"];
		if($params["id_hscv"])
			$this->view->ID_FR = $params["id_hscv"];
		else
			$this->view->ID_FR = $params["id"];
		//echo $this->view->ID_MC_HSW;
		$arr_data = array();
		if($params["id"]){
			$arr_data = dkwebModel::getDetailSpe($this->view->ID_MC_HSW);
		}else{
			$arr_data = dkwebModel::getDetailSpeByMaso($params["mahoso"]);
		}

		$this->view->data = $arr_data["data"];
		$this->view->danhsach = $arr_data["danhsach"];
		
		//var_dump();
		switch($this->view->data["LOAIDON"]){
			case 1:
				$this->renderScript("dongbohsmc/thicong_ct.phtml");
				break;
			case 2:
				$this->renderScript("dongbohsmc/phuhieu_tcd.phtml");
				break;
			case 3:
				$this->renderScript("dongbohsmc/phuhieu_xhd.phtml");
				break;
			case 4:
				$this->renderScript("dongbohsmc/phuhieu_xtx.phtml");
				break;
			case 5:
				$this->renderScript("dongbohsmc/phuhieu_xdl.phtml");
			case 6:
				$this->renderScript("dongbohsmc/capphep_dc.phtml");
				break;
		}
		
	}

	function downloadAction(){
		$config = Zend_Registry::get('config');
		$fpath 			= $config->dvc_uploadpath;
		$rootpath 		= $config->file->root_dir;
		$date = getdate();
		$year = QLVBDHCommon::getYear();
		if(!$year)
			$year = $date['year'];		
		$maso = $this->_request->getParam('maso');
		$model = new dkwebModel();
		$file = $model->getFileByMaso($maso);
		
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header( "Content-type:".$file["MIME"] ); 
		header( "Content-Disposition: attachment; filename=".$file["FILENAME"] ); 
		header( "Content-Description: Excel output" );
		$pathfile = $rootpath.'\\'.$fpath.'\\'.$file["MASO"];
		echo file_get_contents($pathfile);		
		exit;
	}
}
