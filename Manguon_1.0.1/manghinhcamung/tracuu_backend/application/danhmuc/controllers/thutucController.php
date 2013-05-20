<?
require_once ( 'danhmuc/models/loaihosoModel.php' );
require_once ( 'danhmuc/models/linhvucModel.php' );
require_once ( 'danhmuc/models/thutucModel.php' );
class Danhmuc_thutucController extends Zend_Controller_Action{

	function init(){
		$this->view->title = "Danh mục Thủ tục";
	}
	function indexAction(){
		QLVBDHButton::EnableAddNew("");
		QLVBDHButton::EnableDelete("");
		$this->view->subtitle = "Danh sách";
		$params = $this->_request->getParams();
		$this->view->data = thutucModel::getAllWithFilter($params);
		$this->view->ID_LOAIHOSO = $params["ID_LOAIHOSO"];
	}
	
	function inputAction(){
		//Tao phan button
		$params = $this->_request->getParams();
		$id = $params["id"];
		$this->view->id = $id;
		QLVBDHButton::EnableSave("/danhmuc/thutuc/save");
		QLVBDHButton::AddButton("Trở lại","","BackButton","BackButtonClick();");
		if($id){
			$this->view->subtitle = "Cập nhật";
			$this->view->data = thutucModel::getById($id);
		}else{
			$this->view->subtitle = "Thêm mới";
		}

	}

	function saveAction(){
		$params = $this->_request->getParams();
		
		$id = $params["ID_THUTUC"];
		$id_loaihoso = $params["ID_LOAIHOSO"];
		$id_loaihoso_old = $params["ID_LOAIHOSO_OLD"];
		//var_dump($params); exit;
		thutucModel::save($id,$params);
		if(!$id){
			$id = thutucModel::getLastInsertId();

		}
		thutucModel::saveloaihoso($id,$id_loaihoso,$id_loaihoso_old);
		$this->_redirect("/danhmuc/thutuc/");
		exit;
	}
	function deleteAction(){
		$this->view->subtitle = "Thêm mới";
		$params = $this->_request->getParams();
		foreach($params["DEL"] as $item){
			thutucModel::deleteById($item);
		}
		$this->_redirect("/danhmuc/thutuc/");
		exit;
	}
}