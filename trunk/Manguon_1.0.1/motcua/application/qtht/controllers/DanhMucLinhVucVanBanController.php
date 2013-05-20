<?php

/**
 * @name DanhMucLinhVucVanBanController
 * @author Luu Van Trung
 * @package qtht
 * @version 1.0 
 * 
 */


require_once 'Zend/Controller/Action.php';
require_once 'qtht/models/LinhVucVanBanModel.php';
require_once 'Common/ValidateInputData.php';
require_once 'config/qtht.php';


class Qtht_DanhMucLinhVucVanBanController extends Zend_Controller_Action {
	
	private $model ; // Doi tuong ban linh vuc van ban
	
	/**
	 * Ham khoi tao du lieu cho doi tuong controller
	 */
	public function init()
	{
		$this->model = new LinhVucVanBanModel(); // khoi tao doi tuong table
		
		$this->view->title = "Danh Mục Lĩnh Vực";
		
	}
	
	/**
	 * Ham xu ly cho action xem danh sach cac linh vuc van ban(index)
	 */
	public function indexAction() {
		$this->view->subtitle="Danh sách";
		QLVBDHButton::EnableAddNew("/qtht/DanhMucLinhVucVanBan/input");
		QLVBDHButton::EnableDelete("/qtht/DanhMucLinhVucVanBan/delete");
		//Doc du lieu de hien thi len view
		$config = Zend_Registry::get('config');
		$page = $this->_request->getParam('page');
		$limit = $this->_request->getParam('limit');
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		$filter_object = $this->_request->getParam('filter_object');
		$this->view->filter_object = $filter_object; 
		$search = $this->_request->getParam("search");
		$this->view->search = $search;
		$this->view->data = $this->model->findByMixed($page,$limit,$search,$filter_object);
		$n_rows = $this->model->count($search,$filter_object);
		$this->view->showPage = QLVBDHCommon::Paginator($n_rows,5,$limit,"frm",$page) ;
		$this->view->limit=$limit;
		$this->view->page=$page;
	}
	
	/**
	 * Ham xu ly cho action cập nhật hay thêm mới lĩnh vực văn bản vào csdl
	 */
	public function saveAction()
	{
		
		QLVBDHButton::EnableSave("/qtht/DanhMucLinhVucVanBan/save");
		if($this->_request->isPost()){
			
			//Lay du lieu tu form nhap lieu
			$active = $this->_request->getParam("active");
			if(!$active)
				$active = 0;
			$name = $this->_request->getParam("name");
			$id = $this->_request->getParam("InputidLVVB");
			//Kiem tra du lieu nhap
			$this->checkInputData($name,$active);
			if($id > 0 ){
				
				// Thuc hien cap nhat du lieu
				$where = 'ID_LVVB='.$id; 
				try{
					$this->model->update(array('NAME'=>$name, 'ACTIVE'=>$active),$where);	
				}catch (Exception $e)
				{
					//Thong bao loi khong the cap nhat linh vuc van ban
					$this->_redirect('/default/error/error?control=DanhMuclinhvucvanban&mod=qtht&id=ERR11004006');
				}
				$this->title = "Danh mục Lĩnh Vực Văn Bản _ Cập nhật";
				
			}else {
			
				
				//Them moi linh vuc van ban vao co so du lieu
				$arr_newdata = array("NAME"=> $name , "ACTIVE" => $active );
				
				try{
					$id = $this->model->insert($arr_newdata);
					
				}catch (Exception  $ex)
				{
					// loi khi insert du lieu
					$this->_redirect('/default/error/error?control=DanhMuclinhvucvanban&mod=qtht&id=ERR11004007');
				}
				$this->title = "Danh mục Lĩnh Vực Văn Bản _ Thêm mới";
				
				
			}
			//chuyen den trang xem danh sach cac linh vuc van ban
			$this->_redirect('/qtht/DanhMucLinhVucVanBan');
		}else {
			$this->_redirect('/qtht/DanhMucLinhVucVanBan');
		}
		
		
	}
	
	/**
	 * Ham xu ly cho action xoa linh vuc van ban
	 */
	public function deleteAction()
	{
		
		
		if($this->_request->isPost())
		{
			//Lay id cua cac linh vuc van ban can xoa
			$idarray = $this->_request->getParam('DEL');
			
			//Thuc hien xoa cac linh vuc van ban duoc chon boi user
			$where = 'ID_LVVB in ('.implode(',',$idarray).')'; 
			
			try{				
				if(!$this->model->delete($where))
				{
					$this->_redirect('/default/error/error?control=DanhMuclinhvucvanban&mod=qtht&id=ERR11004008');
				}
			
			}catch (Exception  $ex)
			{
				//loi xay ra khi xoa du lieu
				$this->_redirect('/default/error/error?control=DanhMuclinhvucvanban&mod=qtht&id=ERR11004008');
			}
			
			//Hien thi trang danh sach cac loai van ban 
			$this->_redirect('/qtht/DanhMucLinhVucVanBan');
		}
		else 
		{
			$this->view->title = "Quản trị danh mục lĩnh vực văn bản_ Xóa";
		}
		
		$this->_redirect('/qtht/DanhMucLinhVucVanBan');
	
	}
	
	/**
	 *  Ham xu ly cho action hiện thị trang nhập liệu thông tin về lĩnh vực văn bản
	 */
	public function inputAction()
	{
		//Tao phan button
		QLVBDHButton::EnableSave("/qtht/DanhMucLinhVucVanBan/save");
		QLVBDHButton::AddButton("Trở lại","","BackButton","BackButtonClick();");
		//Giu cac tham so cua trang danh sach
		$this->view->page = $this->_request->getParam('page');
		$this->view->limit = $this->_request->getParam('limit');
		$this->view->filter_object = $this->_request->getParam('filter_object');
		$this->view->search = $this->_request->getParam("search");	
		
		if($this->_request->isPost())
		{
			// Lay id cua linh vuc van ban can cap nhat
			$idCapNhat = $this->_request->getParam('idLVVB');
			$this->view->subtitle="Thêm mới";
			if($idCapNhat > 0){
			//Truong hop cap nhat
				$this->view->subtitle="Cập nhật";
				//Lay thong tin ve linh vuc van ban can cap nhat
				$rowcn = $this->model->find($idCapNhat);
				
				//Kiem tra id cua linh vuc van ban can cap nhat co nam tron csdl
				
				if($rowcn->count() == 0){
					//loi khong tim thay id cua linh vuc van ban can cap nhat
					$this->_redirect('/default/error/error?control=DanhMuclinhvucvanban&mod=qtht&id=ERR11004002');	
				}else {
					
					$this->view->namebefore = $rowcn->current()->NAME;
					$this->view->activeselect = $rowcn->current()->ACTIVE;
				
					$this->view->id= $idCapNhat;
					
				}
				
			}
			
			
			
		}
		
		$this->renderScript("DanhMucLinhVucVanBan/InputData.phtml");
		
	}
	
	private function checkInputData($name,$active){
		
		$strurl='/default/error/error?control=danhmuclinhvucvanban&mod=qtht&id=';
		$strerr = "";
		$strerr .= ValidateInputData::validateInput('text128_re',$name,'ERR11004001').",";
		$strerr .= ValidateInputData::validateInput('boolean',$active,"ERR11004005").",";
		if(strlen($strerr)!=2){
			$this->_redirect($strurl.$strerr);
		}
		return true;
	}
}


