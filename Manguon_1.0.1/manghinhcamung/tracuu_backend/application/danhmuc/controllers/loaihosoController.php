<?
require_once ( 'danhmuc/models/loaihosoModel.php' );
require_once ( 'danhmuc/models/linhvucModel.php' );
class Danhmuc_loaihosoController extends Zend_Controller_Action{

	function init(){
		$this->view->title = "Danh mục Loại hồ sơ";
	}
	function indexAction(){
		QLVBDHButton::EnableAddNew("/qtht/DanhMucChucDanh/input");
		QLVBDHButton::EnableDelete("/qtht/DanhMucChucDanh/");
		$this->view->subtitle = "Danh sách";
		$params = $this->_request->getParams();

		$id_linhvuc = $params["ID_LINHVUC"]; 
		$this->view->ID_LINHVUC = $id_linhvuc ;
		
		$this->view->data = loaihosoModel::getAllByLV($id_linhvuc);
		
	}
	
	function inputAction(){
		//Tao phan button
		$params = $this->_request->getParams();
		$id = $params["id"];
		$this->view->id = $id;
		$this->view->id_lv_old = $params["id_lv"];
		QLVBDHButton::EnableSave("/danhmuc/loaihoso/save");
		QLVBDHButton::AddButton("Trở lại","","BackButton","BackButtonClick();");
		if($id){
			$this->view->subtitle = "Cập nhật";
			$this->view->data = loaihosoModel::getById($id);
			
			//var_dump($this->view->data);exit;
		}else{
			$this->view->subtitle = "Thêm mới";
		}

	}

	function saveAction(){
		$params = $this->_request->getParams();
		
		$id = $params["ID_LOAIHOSO"];
		$params["CHITIET_HOSO"] = base64_encode(htmlspecialchars_decode($params["CHITIET_HOSO"]));
		//var_dump($params);
		loaihosoModel::save($id,$params);
		$last_id = $id;
		if(!$last_id)
			$last_id = loaihosoModel::select_last_id();
		//luu file qui trinh
		$con = Zend_Registry::get('config');
		$dir = $con->file_quitrinh;
		$filepath = $dir.DIRECTORY_SEPARATOR.md5($last_id .$_FILES['uploadedfile']['tmp_name']).".png";		
		
		if($_FILES['uploadedfile']['tmp_name']){
			$file_name = md5($last_id.$_FILES['uploadedfile']['tmp_name']).".png";
			if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'],$filepath)){
				loaihosoModel::update_file_quitrinh($last_id,$file_name);
			}
		}
		//var_dump($_FILES);
		$id_lv_old = $params["id_lv_old"];
		$this->_redirect("/danhmuc/loaihoso/index?ID_LINHVUC=".$id_lv_old);
		exit;
	}
	function deleteAction(){
		$this->view->subtitle = "Thêm mới";
		$params = $this->_request->getParams();
		foreach($params["DEL"] as $item){
			loaihosoModel::deleteById($item);
		}
		$this->_redirect("/danhmuc/loaihoso/");
		exit;
	}
}