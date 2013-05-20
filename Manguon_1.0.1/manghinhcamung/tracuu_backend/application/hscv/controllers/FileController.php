<?php

/**
 * FileController
 * @package hscv
 * Quan ly file dinh kem
 * @author trunglv
 * @version 1.0
 */

require_once 'Zend/Controller/Action.php';
require_once 'Zend/File/Transfer/Adapter/Http.php';
require_once 'Zend/File/Transfer.php';
require_once 'Common/AjaxEngine.php';
require_once 'hscv/models/filedinhkemModel.php';
require_once 'hscv/models/gen_tempModel.php';


class Hscv_FileController extends AjaxEngine {
	
	/**
	 * Ham khoi tao du lieu cho controller action
	 *
	 */
	function init(){
		//disable layout
		$this->_helper->layout->disableLayout();
		
	}
	/**
	 * Ham xu ly index controller , in danh sach file dinh kem 
	 */
	public function indexAction() {
		// TODO Auto-generated UploadController::indexAction() default action
		$this->returnResultHTML($this);
	}
	public function attachmentAction()
	{
		$this->returnResultHTML($this);
	}	
	private function upload(){
		//Lay cac bien toan cuc
		$con = Zend_Registry::get('config');
		$au = Zend_Registry::get('auth');
		//Lay tham so nhan tu client
		$date = getdate();
		$iddiv= $this->_request->getParam('iddiv');
		$year = QLVBDHCommon::getYear(); //nam cua bang du lieu
		$type = $this->_request->getParam('type');
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
			
		$model = new filedinhkemModel($year); //doi tuong model
		//Luu file xuong thu muc tam cua server
		$max_size = $con->file->maxsize;
		$adapter = new FileTransfer(); // doi tuong adapter nhan file dinh kem tu client
		$adapter->addValidator('size', $max_size);
		$temp_path = $model->getTempPath();
		$adapter->setDestination($temp_path); 
		
			if (!$adapter->receive()) {    
				//Loi khong the luu file 
				//thong bao loi o day
			}else{
				//luu file thanh cong , cap nhat thong tin ve file xuong csdl
			}
	
		//Lay thong tin ve file dinh kem va luu xuong csdl
		$file = new FileDinhKem();
		$file->_time_update = $date['year'].'-'.$date['mon'].'-'.$date['mday'].' '.$date['hours'].':'.$date['minutes'].':'.$date['seconds'];
		$file->_nam = $date['year'];
		$model->setNameByYear($file->_nam);
		$file->_thang = $date['mon'];
		$dirPath = $model->getDir($file->_nam,$file->_thang);
		$file->_folder = $dirPath;
		$file->_id_object = $idObject;
		$file->_user = $au->getIdentity()->ID_U;
		$file->_filename = basename($adapter->getFileName('uploadedfile'));
		$file->_mime = $adapter->getMine('uploadedfile');
		
		$id_file = $model->insertFileNoIdObject($file);
		$maso = $id_file.$file->_filename.$file->_time_update;
		$maso = md5($maso);
		$model->updateMaSo($id_file,$maso);
		$newlocation = $dirPath. DIRECTORY_SEPARATOR. $maso;
		rename($adapter->getFileName('uploadedfile'),$newlocation);
		$file->_pathFile = $newlocation;
		$file->_id_dk = $id_file;
		$model->getContent($file);
		//return $id_file;
	}
	/**
	 * Ham upload file ve server
	 */
	public function saveAction(){
		
		//$con = Zend_Registry::get('config');
		//$au = Zend_Registry::get('auth');
		//Lay tham so nhan tu client
		//$date = getdate();
		$iddiv= $this->_request->getParam('iddiv');
		$year = QLVBDHCommon::getYear(); //nam cua bang du lieu
		$type = $this->_request->getParam('type');
		//truongvc attachment for traodoi module
		$from =$this->_request->getParam('from');
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
		filedinhkemModel::insertFile($idObject,$isTemp,$iddiv,$year,$type);
		//Doan javascript de load lai danh sach file dinh kem tai client
		
		//truongvc
		
		if($from=="attachment")
			$url = '/hscv/file/attachment?iddiv='.$iddiv.'&idObject='.$idObject.'&is_new='.$is_new.'&year='.$year.'&type='.$type;
		else
			$url = '/hscv/file?iddiv='.$iddiv.'&idObject='.$idObject.'&is_new='.$is_new.'&year='.$year.'&type='.$type;
		
		echo "<script> window.parent.loadDivFromUrl('".$iddiv."','$url"."',1); </script>";
		//var_dump($this->_request->getParams());
		exit;
	}
	
	public function addfileAction(){
		$this->upload();
		exit;
	}
	
	public function updateAction(){
		$idOld = $this->_request->getParam('idOldFile');
		$idObject = $this->_request->getParam('idObject');
		$type = $this->_request->getParam('type');
		//xoa
		$year = QLVBDHCommon::getYear();
		$model = new filedinhkemModel($year);
		$model->deleteFile($idOld);
		$id_file = $this->upload();
		$model->updateFileWithIdObject($idObject,$type,$id_file);
		exit;
	}
	/*
	 * Ham ajax tra ket qua duoi dang html ve cho client  
	 */
	protected function echoHTML(){
		//Lay id object va idTemp , year tu client
		
		//if(!$type )
			//$type = -1;
		$date = getdate();
		$year = QLVBDHCommon::getYear();
		$iddiv= $this->_request->getParam('iddiv');
		if(!$year)
			$year = $date['year'];		
		$idObject = $this->getParam('idObject');
		$is_new = $this->getParam('is_new');
		if($is_new == 1){//truong hop chua co id cua Object va chua khoi tao id Object
			//Tao mot truong id Object tam cho 
			$tempTbl = new gen_tempModel();
			$idObject = $tempTbl->getIdTemp();
		}
		else{
			//truong hop da co id cua Object
		}
		$this->view->idObject = $idObject;
		$this->view->isTemp = $isTemp; 
		$this->view->year = $year;
		//Lay danh sach file dinh kem co idObject va $isTemp
		$model = new filedinhkemModel($year);
		$type = $this->getParam('type');
	
		
		if($type != -1)
			$this->view->data = $model->getFileByIdObjectAndType($idObject,$type);
		else
		    $this->view->data= $model->getListFile($idObject,$isTemp);		
		$this->view->iddiv= $iddiv;
		$this->view->type=$type;
		//kiem tra quyen truy cap
		$isreadonly = $this->_request->getParam('isreadonly');
		if(!$isreadonly)
			$isreadonly = 0;
		$isCapnhat = 1;		
		if($is_new == 1){
			$isCapnhat = 1;
		}
		else{if(hosocongviecModel::isLuutru($idObject,$year) == true || $isreadonly == 1){
			$isCapnhat = 0;
			
			}else{$isCapnhat = 1; }
		}
		$this->view->isCapnhat = $isCapnhat;	
			
	}
	/**
	 * Ham ajax hien thi khung nhap lieu file dinh kem
	 */
	function inputAction(){
		//disable layout
		
		$this->view->idObject = $this->_request->getParam('idObject');
		$this->view->isTemp = $this->_request->getParam('isTemp');
		$this->view->year = QLVBDHCommon::getYear();
		$this->view->iddiv = $this->_request->getParam('iddiv');
		$this->view->type = $this->_request->getParam('type');
		$this->view->from=$this->_request->getParam('from');
	}
	/**
	 * Ham ajax xoa file dinh kem
	 */
	function deleteAction(){
		$date = getdate();
		$year = QLVBDHCommon::getYear();
		$iddiv= $this->_request->getParam('iddiv');
		//truongvc attachment for traodoi module
		$from =$this->_request->getParam('from');
		if(!$year)
			$year = $date['year'];		
		$maso = $this->_request->getParam('maso');
		$idObject = $this->_request->getParam('idObject');
		if(!$idObject)
			$idObject = 0;
		$isTemp = $this->_request->getParam('isTemp');
		if(!$isTemp)
			$isTemp = 0;
		$type = $this->_request->getParam('type');
		$model = new filedinhkemModel($year);
		$arr_maso = $this->getParam('DELidfiledk'.$idObject);		
		for($i=0 ; $i< count($arr_maso);$i++)
			$model->deleteFileByMaso($arr_maso[$i]);
		//var_dump($arr_maso);
		if($from=="attachment")
			$url = '/hscv/file/attachment?iddiv='.$iddiv.'&idObject='.$idObject.'&is_new='.$is_new.'&year='.$year.'&type='.$type;
		else 
			$url = '/hscv/file?iddiv='.$iddiv.'&idObject='.$idObject.'&is_new='.$is_new.'&year='.$year.'&type='.$type;
			
		$this->_redirect($url);
		//echo "parent.loadDivFromUrl('".$iddiv."','$url"."',1);";
		//$url = '/hscv/file?iddiv='.$iddiv.'&idObject='.$idObject.'&is_new='.$is_new.'&year='.$year.'&type='.$type;
		echo "<script> window.parent.loadDivFromUrl('".$iddiv."','$url"."',1); </script>";
		//echo "<script> loadDiv('".$iddiv."','/hscv/file',1,".$idObject.",".$isTemp.",".$year."); </script>";
		//echo "";
		exit;
	}
	/**
	 * Ham tai file dinh kem ve may
	 */
	function downloadAction(){
		$date = getdate();
		$year = QLVBDHCommon::getYear();
		if(!$year)
			$year = $date['year'];		
		$maso = $this->_request->getParam('maso');
		$model = new filedinhkemModel($year);
		$file = $model->getFileByMaso($maso);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header( "Content-type:".$file->_mine ); 
		header( "Content-Disposition: attachment; filename=".$file->_filename ); 
		header( "Content-Description: Excel output" );
		echo file_get_contents($file->_pathFile); 
		exit;
	}
}
