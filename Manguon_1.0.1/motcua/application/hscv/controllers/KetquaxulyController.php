<?php
/**
 * @author trunglv
 * @version 1.0
 * @example 
 */
require_once ('Zend/Controller/Action.php');
require_once 'Common/AjaxEngine.php';
require_once 'hscv/models/filedinhkemModel.php';
require_once 'hscv/models/phienbanduthaoModel.php';
require_once 'hscv/models/gen_tempModel.php';
require_once 'hscv/models/hosocongviecModel.php';
require_once 'hscv/models/KetquaxulyModel.php';
require_once 'motcua/models/motcua_hosoModel.php';
require_once 'qtht/models/Vb_nguoikyModel.php';
class Hscv_KetquaxulyController extends AjaxEngine {
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
		$model = new KetquaxulyModel($year);
		$this->view->year = $year;
		$this->view->iddivParent = $iddivParent;
		$idHSCV = $this->_request->getParam('idHSCV');
		if($idHSCV ==0 || !$idHSCV){
			$temp = new gen_tempModel();
			$arr = array();
			$idHSCV = $temp->insert($arr);
			
		}
		$this->view->idHSCV = $idHSCV;
		$this->view->data = $model->getAllm($idHSCV);
		$this->view->id_loaihoso= $model->getIDHSByIdHSCV($idHSCV);
		$dataHS=$model->getListByIdHSCV($idHSCV);
		$this->view->dataHS=$dataHS;
	//	var_dump($this->view->dataHS);		
	}
	/**
	 * Lưu thông tin các văn bản dự thảo khi cập nhật hoặc thêm mới
	 */
	function saveAction(){
		//var_dump($this->_request->getParams());
		$year = QLVBDHCommon::getYear();
		$iddivParent= $this->_request->getParam('iddivParent');
		$model = new KetquaxulyModel($year);
		$idHSCV = $this->getParam('idHSCV');
		$idKetqua = $this->getParam('idKetqua');
		$nguoiky = $this->getParam('nguoiky');
		$sokyhieu = $this->getParam('sokyhieu');
		$param = $this->getRequest()->getParams();
		//tinh chỉnh param
		if($param['ngayky']!=""){
			$ngayky = $param['ngayky'];
			$ngayky = implode("-",array_reverse(explode("/",$ngayky)));
		}	
	//	var_dump($param['ngayky']);
		if($idKetqua>1){
			//truong hop cap nhat
			$data_json = base64_decode($this->getParam('data'));
			$data = json_decode($data_json);
			//Capnhat lai thong tin cua van ban du thao trong csdl
			$model->updateByIdKetquaNoHSCV($idKetqua,$data);
			//doan javascript de cap nhat lai danh sach cac van ban du thao
			
		echo 'loadDivFromUrl("'.$iddivParent.'","/hscv/Ketquaxuly?idHSCV='.$idHSCV.'&iddivParent='.$iddivParent.'&year='.$year.'",1);';
		}else{
			if($idKetqua == 0){
				//truong hop them moi
				
				$kq = new Ketqua();
				$kq->_id_hscv = $idHSCV;
				$kq->_ngayky = $ngayky;
				$kq->_nguoiky = $nguoiky;
				$kq->_sokyhieu = $sokyhieu;
					//Them moi ket qua
					$idKetqua = $model->insertOne($kq);
				
				//doan script de load lai trang danh sach cac van ban du thao trong ho so cong viec
				echo ' window.parent.loadDivFromUrl("'.$iddivParent.'","/hscv/Ketquaxuly?idHSCV='.$idHSCV.'&iddivParent='.$iddivParent.'&year='.$year.'",1); ';
			}
		}
		//echo "alert('".$params["iddivParent"]."');";
				
		exit; //Khong xu dung lop view
	}
	/**
	 * Ham hien thi khung nhap lieu cho van ban du thao
	 */
	function inputAction(){
		$year = QLVBDHCommon::getYear();
		$iddivParent = $this->getParam('iddivParent');
		$model = new KetquaxulyModel($year);
		$id_kq= $this->getParam('idKetqua');
	  	$dtaCaphhat = $model->getDataByIdKetqua($id_kq);
		$param = $this->getRequest()->getParams();
		//tinh chỉnh param
		if($param['ngayky']!=""){
			$ngayky = $param['ngayky'];
			$ngayky = implode("-",array_reverse(explode("/",$ngayky)));
		}
		//Khoi tao du lieu cho lop view
		$this->view->idKetqua = $dtaCaphhat->ID_KQ_MC;
		$this->view->ngayky		= $dtaCaphhat->NGAYKY;
		$this->view->nguoiky	= $dtaCaphhat->NGUOIKY;
		$this->view->sokyhieu	= $dtaCaphhat->SOKYHIEU;
		$this->view->year = $year;
		$this->view->iddivParent = $iddivParent;
		$this->view->idHSCV = $this->_request->getParam('idHSCV');
		$this->view->ngayky = $param['ngayky'];	
		$this->view->datank = Vb_nguoikyModel::getNKMC();
	}
	/**
	 * Ham cap nhat cac bien dac biet tuong ung trong mot cua
	 */
	function updateAction(){
		$data=array();
		$year = QLVBDHCommon::getYear();
		$iddivParent = $this->getParam('iddivParent');
		$model = new motcua_hosoModel($year);
		$param = $this->getRequest()->getParams();
		$idHS=$model->getHSByHSCV($param["idHSCV"]);
		$idHSCV = $this->getParam('idHSCV');
		$arr_custom_fields = motcua_hosoModel::Customfields($param['ID_LOAIHOSO']);
		foreach($arr_custom_fields as $cus){
			switch($cus["TYPE"]){
			case "DATE" :
				$data[$cus["NAME_COLUMN"]] = implode("-",array_reverse(explode("/",$param[$cus["NAME_COLUMN"]])));  
				break;
			case "VARCHAR" :
				$data[$cus["NAME_COLUMN"]] = $param[$cus["NAME_COLUMN"]];							
				break;

			case "INTEGER" :
				$data[$cus["NAME_COLUMN"]] = (int)$param[$cus["NAME_COLUMN"]];
				break;
							
			case "DOUBLE" :
				$data[$cus["NAME_COLUMN"]] = (double)$param[$cus["NAME_COLUMN"]];
				break;
				default:
				break;
			}
					}
	//	var_dump($idHS);
		$model->updateHSByHSCV($data,$idHS);
		echo ' 
		alert( \' Bạn đã cập nhật thành công \');
		window.parent.loadDivFromUrl("'.$iddivParent.'","/hscv/Ketquaxuly?idHSCV='.$idHSCV.'&iddivParent='.$iddivParent.'&year='.$year.'",1); ';
		exit;
	}
	
	/**
	 * Ham xoa cac ket qua tuong ung trong motcua_ketqua
	 */
	function deleteAction(){
		$year = QLVBDHCommon::getYear();
		$iddivParent= $this->_request->getParam('iddivParent');
		$idHSCV = $this->_request->getParam('idHSCV');
		$model = new KetquaxulyModel($year);
		$hscv_idnhapketqua = $this->getParam('DELidnhapketqua'.$idHSCV); 
		for($i=0;$i<count($hscv_idnhapketqua);$i++)
			$model->deleteOne($hscv_idnhapketqua[$i],$year);
		//doan java script tra ve cho client de cap nhạt lai danh sach cac van ban du thao trong
		//ho so cong viec tuong ung 
		echo	       'loadDivFromUrl("'.$iddivParent.'","/hscv/Ketquaxuly?idHSCV='.$idHSCV.'&iddivParent='.$iddivParent.'&year='.$year.'",1);';
		exit; //Khong xu dung lop view
	}
	
}

?>
