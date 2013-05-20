<?php
/**
 * @author trunglv
 * @version 1.0
 */
require_once ('Zend/Controller/Action.php');
require_once ('hscv/models/PhienBanDuThaoModel.php');
require_once 'hscv/models/filedinhkemModel.php';
require_once 'hscv/models/hosocongviecModel.php';

class Hscv_PhienBanDuThaoController extends Zend_Controller_Action {
	
	function init(){
		//Khong cho hien thi layout
		$this->_helper->layout->disableLayout();
	}
	/**
	 * action hien thi  danh sach cac phien ban du thao
	 *
	 */
	function indexAction(){
		global $auth;
		$user = $auth->getIdentity();
		$year = QLVBDHCommon::getYear(); 
		$idDuthao = $this->_request->getParam('idDuthao');
		$idHSCV = $this->_request->getParam('idHSCV');
		$model = new PhienBanDuThaoModel($year);
		$this->view->data = $model->getListByIdDuthao($idDuthao,$year);		
		$this->view->year = $year;
		$this->view->idDuthao = $idDuthao;
		$this->view->isXoa = $this->_request->getParam('isXoa');
		$this->view->ID_U=$user->ID_U;
		$isreadonly = $this->_request->getParam('isreadonly');
		if(!$isreadonly)
		     $isreadonly = 0;
		$isCapnhat = 1;
		if(hosocongviecModel::isLuutru($idHSCV,$year) == true || $isreadonly == 1){
			$isCapnhat = 0;
			
			}else{$isCapnhat = 1; }
		$this->view->isCapnhat = $isCapnhat;
		$this->view->idHSCV = $idHSCV ;
		
	}
	/**
	 * action hien thi khung nhap lieu de them moi
	 * hay cap nhat cac phien ban du thao
	 */
	function inputAction(){
		
		$year = QLVBDHCommon::getYear(); 
		$idPBDuthao = $this->_request->getParam('idPBDuthao');
		$idHSCV = $this->_request->getParam('idHSCV');
		$idDuthao =$this->_request->getParam('idDuthao');
		$model = new PhienBanDuThaoModel($year);
		$re = $model->getDataById($idPBDuthao,$idDuthao);
		$this->view->version = $re +1;
		$this->view->year = $year;
		$this->view->idPBDuthao =$idPBDuthao ;
		$this->view->type=3;
		$this->view->iddiv = $this->_request->getParam('iddiv');
		$this->view->iddivParent = $this->_request->getParam('iddivParent');
		$this->view->idOldFile = $this->_request->getParam('idOldFile');
		$this->view->idDuthao= $this->_request->getParam('idDuthao');
		$this->view->idHSCV = $idHSCV ;
		$is_new = $this->_request->getParam('is_new');
		//is_new = 1 : them moi else cap nhat
		if(!$is_new)
			$is_new = 0;
		$this->view->is_new = $is_new;
	}
	/**
	 * Ham luu thong tin ve phien ban du thao khi cap nhat
	 * hay them moi
	 */
	function saveAction(){
		//Lay du lieu nhan ve tu client
		$iddiv= $this->_request->getParam('iddiv');
		$type = $this->_request->getParam('type');
		$version = $this->_request->getParam('version');
		$idDuthao = $this->_request->getParam('idDuthao');
		$idHSCV = $this->_request->getParam('idHSCV');		
		$year = QLVBDHCommon::getYear(); //nam cua bang du lieu
		$idPBDuthao = $this->_request->getParam('idPBDuthao');
		global $auth;
		$user = $auth->getIdentity();
		if(!$idPBDuthao){
			$idPBDuthao = 0;
		}
		if(!$type)
			$type = -1;
		if(!$year)
			$year = $date['year'];		
		$idObject = $this->_request->getParam('idObject');//id cua doi tuong chua file dinh kem
		if(!$idObject)
			$idObject = 0;
		$isTemp = $this->_request->getParam('isTemp');
		if(!$isTemp)
			$isTemp = 0;
		$idOld = $this->_request->getParam('idOldFile');
		//tao cac lop model tuong ung
		$fdk = new filedinhkemModel($year);
		$model = new PhienBanDuThaoModel($year);
		if($idPBDuthao == 0){//truong hop them moi phien ban du thao
			//themoi phien ban du thao truoc de lay id
			$idPBDuthao = $model->insertOne($idDuthao,$version,$user->ID_U);
		}
		else{ // truong hop cap nhat phien ban du thao
			//echo $user->ID_U;exit;
			$model->updateVersion($idPBDuthao,$version,$user->ID_U);
		}
		//Cap nhat file dinh kem (them file moi, xoa file cu) 
		$re = filedinhkemModel::insertFile($idPBDuthao,$isTemp,$iddiv,$year,$type);
		if($re != -1){
			if($idOld>0)
				$fdk->deleteFile($idOld);	
		}
		//echo $idHSCV;
		//exit;
		//doan js cap nhat lai danh sach cac van ban du thao
		$this->a= PhienBanDuThaoModel::gettongphienbanduthao($idDuthao);		
		$this->b= PhienBanDuThaoModel::gettongphienbanduthaoidu($this->ID_U,$idDuthao);
		echo "<script>window.parent.loadDivFromUrl('PhienBanDiv".$idDuthao."','/hscv/PhienBanDuThao?year=".$year."&idDuthao=".$idDuthao."&idHSCV=".$idHSCV."',1);		
		</script>";
		
        if(($this->a < 2 && $this->b ==1)||($this->b ==$this->a)){
		echo "<script> window.parent.document.getElementById('DELidvanbanduthao".$idHSCV."_$idDuthao').style.display='none'; </script>";
		}
		exit; //khong xu dung lop view
	}
	/**
	 * Ham xoa phien ban du thao
	 *
	 */
	function deleteAction(){
		$idDuthao = (int)$this->_request->getParam('idDuthao');
		$idHSCV = $this->_request->getParam('idHSCV');
		$year = QLVBDHCommon::getYear(); //nam cua bang du lieu
		$model = new PhienBanDuThaoModel($year);		
		$arr_id = $this->_request->getParam('DELidpbdt'.$idDuthao); // Lay thong tin ve cac phien ban du thao duoc chon xoa
		for($i = 0 ; $i< count($arr_id); $i++){
			$arr = explode(',',$arr_id[$i]);
			$model->deleteOneById($arr[0]);	
		}
		//doan javascript cap nhat lai danh sach cac phien ban du thao
		echo "loadDivFromUrl('PhienBanDiv".$idDuthao."','/hscv/PhienBanDuThao?year=".$year."&idDuthao=".$idDuthao."&idHSCV=".$idHSCV."',1);";		
		exit ; //khong xu dung lop view
	}
}

?>
