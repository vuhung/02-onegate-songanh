<?
require_once ( 'danhmuc/models/linhvucModel.php' );
class Danhmuc_linhvucController extends Zend_Controller_Action{

	function init(){
		$this->view->title = "Danh mục Lĩnh vực";
	}
	function indexAction(){
		QLVBDHButton::EnableAddNew("/qtht/DanhMucChucDanh/input");
		QLVBDHButton::EnableDelete("/qtht/DanhMucChucDanh/");
		$this->view->subtitle = "Danh mục Lĩnh vực";
		$this->view->data = linhvucModel::getAll();
		
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
			$this->view->data = linhvucModel::getById($id);
			
		}else{
			$this->view->subtitle = "Thêm mới";
		}

	}

	function saveAction(){
		$params = $this->_request->getParams();
		
		$id = $params["ID_LINHVUC"];
		linhvucModel::save($id,$params);
		$this->_redirect("/danhmuc/linhvuc/");
		exit;
	}
	function deleteAction(){
		$this->view->subtitle = "Thêm mới";
		$params = $this->_request->getParams();
		foreach($params["DEL"] as $item){
			linhvucModel::deleteById($item);
		}
		$this->_redirect("/danhmuc/linhvuc/");
		exit;
	}
}