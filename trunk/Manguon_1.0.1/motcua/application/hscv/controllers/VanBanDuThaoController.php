<?php
/**
 * @author trunglv
 * @version 1.0
 * @example 
 */
require_once ('Zend/Controller/Action.php');
require_once 'Common/AjaxEngine.php';
require_once 'hscv/models/VanBanDuThaoModel.php';
require_once 'hscv/models/filedinhkemModel.php';
require_once 'hscv/models/phienbanduthaoModel.php';
require_once 'hscv/models/gen_tempModel.php';
require_once 'hscv/models/hosocongviecModel.php';

class Hscv_VanBanDuThaoController extends AjaxEngine {
	/**
	 * Ham khoi tao
	 *
	 */
	function init(){
		//Khong cho hien thi layout
		$this->_helper->layout->disableLayout();
	}
	/**
	 * Ham xu ly cho action index 
	 *
	 */
	function indexAction(){
		$this->returnResultHTML($this);
	}
	/**
	 * Ham ke thua tu lop AjaxEngine 
	 */
	protected function echoHTML(){
		$year = QLVBDHCommon::getYear();
		$iddivParent = $this->getParam('iddivParent');
		$model = new VanBanDuThaoModel($year);
		$this->view->year = $year;
		$this->view->iddivParent = $iddivParent;
		$idHSCV = $this->_request->getParam('idHSCV');
		$this->view->isNoHSCV = 0;
		if($idHSCV ==0 || !$idHSCV){
			$temp = new gen_tempModel();
			$arr = array();
			$idHSCV = $temp->insert($arr);
			$this->view->isNoHSCV = 1;
		}
		$this->view->idHSCV = $idHSCV;
		$this->view->data = $model->getListByIdHSCV($idHSCV);
		//Kiem tra cac truong hop truy xuat den file dinh kem
		$isreadonly = $this->_request->getParam('isreadonly');
		if(!$isreadonly)
			$isreadonly = 0;
		$isCapnhat = 1;
		if($this->view->isNoHSCV == 1){
			$isCapnhat = 1;
		}
		else{if(hosocongviecModel::isLuutru($idHSCV,$year) == true || $isreadonly == 1){
			$isCapnhat = 0;
			$this->view->isreadonly = 1;
			}else{$isCapnhat = 1; }
		}
		$this->view->isCapnhat = $isCapnhat;
	}
	/**
	 * Lưu thông tin các văn bản dự thảo khi cập nhật hoặc thêm mới
	 */
	function saveAction(){
		$year = QLVBDHCommon::getYear();
		$type = $this->getParam('type');
		$iddivParent= $this->_request->getParam('iddivParent');
		$model = new VanBanDuThaoModel($year);
		$idHSCV = $this->getParam('idHSCV');
		$idDuthao = $this->getParam('idDuthao');
		$idlvb = (int)$this->getParam('ID_LVB');
		$version = $this->getParam('version');
		$tenduthao = $this->getParam('duthao_tenvanbanduthao');
		$isNoHSCV = $this->_request->getParam('isNoHSCV');
		if($idDuthao>1){
			//truong hop cap nhat
			$data_json = base64_decode($this->getParam('data'));
			$data = json_decode($data_json);
			//Capnhat lai thong tin cua van ban du thao trong csdl
			$model->updateByIdDuthaoNoHSCV($idDuthao,$data);
			//doan javascript de cap nhat lai danh sach cac van ban du thao
			echo 'loadDivFromUrl("'.$iddivParent.'","/hscv/Vanbanduthao?idHSCV='.$idHSCV.'&iddivParent='.$iddivParent.'&year='.$year.'",1);';
		}else{
			if($idDuthao == 0){
				//truong hop them moi
				$myAuth = Zend_Registry::get('auth');
				$duthao = new DuThao();
				$duthao->_nguoisoan = $myAuth->getIdentity()->ID_U;
				$duthao->_id_hscv = $idHSCV;
				$duthao->_trangthai = 0;
				if($isNoHSCV == 1)
					$duthao->_trangthai = -1;
				$duthao->_tenduthao = $tenduthao;
				$duthao->_idlvb = $idlvb;
				//Them moi du thao
				$idDuthao = $model->insertOne($duthao);
				//Them moi cac phien ban du thao
				$phienbanModel = new PhienBanDuThaoModel($year);
				$idPBDuthao = $phienbanModel->insertOne($idDuthao,$version,$myAuth->getIdentity()->ID_U);
				//Them moi cac file dinh kem tuong ung voi cac phien ban du thao co trong du thao
				filedinhkemModel::insertFile($idPBDuthao,1,$iddivParent,$year,$type);
				//doan script de load lai trang danh sach cac van ban du thao trong ho so cong viec
				echo '<script> window.parent.loadDivFromUrl("'.$iddivParent.'","/hscv/Vanbanduthao?idHSCV='.$idHSCV.'&iddivParent='.$iddivParent.'&year='.$year.'",1); </script>';
			}
		}
		exit; //Khong xu dung lop view
	}
	/**
	 * Ham hien thi khung nhap lieu cho van ban du thao
	 */
	function inputAction(){
		$year = QLVBDHCommon::getYear();
		$iddivParent = $this->getParam('iddivParent');
		$model = new VanBanDuThaoModel($year);
		$isnew = $this->getParam('isnew');
		if(!$isnew)
			$isnew = 0;
		$this->view->isnew = $isnew;
		$id_duthao = $this->getParam('idDuthao');
		$dtaCaphhat = $model->getDataByIdDuthao($id_duthao);
		//Khoi tao du lieu cho lop view
		$this->view->idDuthao = $dtaCaphhat->ID_DUTHAO;
		$this->view->tenduthao = $dtaCaphhat->TENDUTHAO;
		$this->view->nguoiky = $dtaCaphhat->NGUOIKY;
		$this->view->nguoisoan = $dtaCaphhat->NGUOISOAN;
		$this->view->trangthai =$dtaCaphhat->TRANGTHAI;
		$this->view->year = $year;
		$this->view->iddivParent = $iddivParent;
		$this->view->idHSCV = $this->_request->getParam('idHSCV');
		$this->view->isNoHSCV = $this->_request->getParam('isNoHSCV');
		$r = $model->getAdapter()->query("SELECT * FROM VB_LOAIVANBAN");
		$this->view->loaivb = $r->fetchAll();
		$this->view->idlvb = $dtaCaphhat->ID_LVB;
	}
	/**
	 * Ham xoa cac van ban du thao tuong ung trong ho so cong viec
	 */
	function deleteAction(){
		$year = QLVBDHCommon::getYear();
		$iddivParent= $this->_request->getParam('iddivParent');
		$idHSCV = $this->_request->getParam('idHSCV');
		$model = new VanBanDuThaoModel($year);
		$hscv_idvanbanduthao = $this->getParam('DELidvanbanduthao'.$idHSCV); 
		for($i=0;$i<count($hscv_idvanbanduthao);$i++)
			$model->deleteOne($hscv_idvanbanduthao[$i],$year);
		//doan java script tra ve cho client de cap nhạt lai danh sach cac van ban du thao trong
		//ho so cong viec tuong ung 
		echo 'loadDivFromUrl("'.$iddivParent.'","/hscv/Vanbanduthao?idHSCV='.$idHSCV.'&iddivParent='.$iddivParent.'&year='.$year.'",1);';
		exit; //Khong xu dung lop view
	}
}

?>
