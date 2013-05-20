<?
require_once ( 'danhmuc/models/quitrinhModel.php' );
require_once ( 'danhmuc/models/loaihosoModel.php' );
class Danhmuc_quitrinhController extends Zend_Controller_Action{

	function init(){
		$this->view->title = "Danh mục Qui Trình";
	}
	function indexAction(){
		QLVBDHButton::EnableAddNew("/qtht/DanhMucChucDanh/input");
		QLVBDHButton::EnableDelete("/qtht/DanhMucChucDanh/");
		$this->view->subtitle = "Danh mục Qui Trình";
		$params = $this->_request->getParams();
		$this->view->data = quitrinhModel::getAllWithFilter($params);
		$this->view->ID_LOAIHOSO = $params["ID_LOAIHOSO"];
		
	}

	function inputAction(){
		//Tao phan button
		$params = $this->_request->getParams();
		$id = $params["id"];
		$this->view->id = $id;
		QLVBDHButton::EnableSave("/qtht/DanhMucChucDanh/save");
		QLVBDHButton::AddButton("Trở lại","","BackButton","BackButtonClick();");
		if($id){
			$this->view->subtitle = "Cập nhật";
			$this->view->data = quitrinhModel::getById($id);
			
		}else{
			$this->view->subtitle = "Thêm mới";
		}

	}
	
	function saveAction(){
		$params = $this->_request->getParams();
		
		$id = $params["ID_QUITRINH"];
		$id_loaihoso = $params["ID_LOAIHOSO"];
		$id_loaihoso_old = $params["ID_LOAIHOSO_OLD"];
		quitrinhModel::save($id,$params);
		if(!$id){
			$id = quitrinhModel::getLastInsertId();
		}
		quitrinhModel::saveloaihoso($id,$id_loaihoso,$id_loaihoso_old);
		$this->_redirect("/danhmuc/quitrinh/");
		exit;
	}
	function deleteAction(){
		$this->view->subtitle = "Xóa";
		$params = $this->_request->getParams();
		foreach($params["DEL"] as $item){
			quitrinhModel::deleteById($item);
		}
		$this->_redirect("/danhmuc/quitrinh/");
		exit;
	}
}