<?php

/**
 * DanhMucLoaiVanBanController
 * 
 * @author Luu Van Trung
 * @version 1.0 
 */

require_once 'qtht/models/LoaiVanBanModel.php';
require_once 'Zend/Controller/Action.php';
require_once 'Common/ValidateInputData.php';
require_once 'config/qtht.php';
class Qtht_DanhMucLoaiVanBanController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	
	private $model ; // Doi tuong bang loai van ban
	
	
	/**
	 * Khoi tao du lieu controller
	 */
	public function init()
	{
		$this->model = new LoaiVanBanModel();
		
		$this->view->title = "Quản lý Loại Văn Bản";
	}
	
	/**
	 * Ham xu ly cho action xem (index)
	 */
	public function indexAction() {
		
		QLVBDHButton::EnableAddNew("/qtht/DanhMucLinhVucVanBan/input");
		QLVBDHButton::EnableDelete("/qtht/DanhMucLinhVucVanBan/delete");
		//Doc du lieu de hien thi len view
		$config = Zend_Registry::get('config');
		$page = $this->_request->getParam('page');
		$limit = $this->_request->getParam('limit');
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		$this->view->subtitle="Danh sách chức danh";
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
	 * Ham controller cho chuc nang them moi loai van ban
	 */
	
	public function saveAction()
	{
		if($this->_request->isPost())
		{
			//Doc du lieu tu form nhap lieu
			$active = $this->_request->getParam("active");
			if(!$active)
				$active = 0;
			$kyhieu = $this->_request->getParam("kyhieu");	
			$name = $this->_request->getParam("name");
			$id = $this->_request->getParam("InputIDLVB");
			$code = $this->_request->getParam('code');
			
			
			//Kiem tra hop le cua du lieu nhap
			$this->checkInputData($name,$active);
			if($id >0){
					//truong hop cap nhat
					//Thuc hien cap nhat du lieu
					$where = 'ID_LVB='.$id; 
					try{
						$this->model->update(array('NAME'=>$name,'CODE'=>$code ,'ACTIVE'=>$active,'KYHIEU'=>$kyhieu
						
						),$where);
						
					}catch (Exception $e)
					{
						$this->_redirect('/default/error/error?control=DanhMucloaivanban&mod=qtht&id=ERR11005006');
					}
					
								
				}else{
					//truong hop them moi
					//thuc hien them moi du lieu
					$arr_newdata = array("NAME"=> $name , 'CODE'=>$code ,"ACTIVE" => $active,'KYHIEU'=>$kyhieu );
					try{
						$this->model->insert($arr_newdata);
						
					}catch (Exception  $ex)
					{
						//Khong the them moi loai van ban
						$this->_redirect('/default/error/error?control=DanhMucloaivanban&mod=qtht&id=ERR11005007');
					}
				}	
		}
		//chuyen den trang xem danh sach cac loai van ban
		$this->_redirect('/qtht/DanhMucLoaiVanBan');
		
		
	}
	
	/**
	 * Ham xu ly cho action xoa loai van ban
	 */
	
	public function deleteAction()
	{
		if($this->_request->isPost())
		{
			//Lay id cac van ban can xoa
			$idarray = $this->_request->getParam('DEL');
			
			//thuc hien xoa cac loai van ban duoc chon
			$where = 'ID_LVB in ('.implode(',',$idarray).')'; 
			try{
				if(!$this->model->delete($where)){
					$this->_redirect('/default/error/error?control=DanhMucloaivanban&mod=qtht&id=ERR11005008');
				}
			}catch (Exception $ex)
			{
				//Loi trong qua trinh xoa
				$this->_redirect('/default/error/error?control=DanhMucloaivanban&mod=qtht&id=ERR11005008');
			}
			
		}
		//chuyen den trang xem danh sach cac loai van ban
		$this->_redirect('/qtht/DanhMucLoaiVanBan');
			
	}
	
	/**
	 * Ham xu ly cho action cap nhat linh vuc van ban
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
			//Lay id cua linh vuc van ban can cap nhat
			$idCapNhat = $this->_request->getParam('idLVB');
			if($idCapNhat >0){
				//truong hop cap nhat du lieu
				//Kiem tra loai van ban can cap nhat co tron csdl
				$rowcn = $this->model->find($idCapNhat);
				if($rowcn->count() == 0)
				{
					//loi khong tim thay id cua loai van ban
					$this->_redirect('/default/error/error?control=DanhMucloaivanban&mod=qtht&id=ERR11005002');
				}
				
				//Lay du lieu cua linh vuc van ban can cap nhat
				$this->view->kyhieu=$rowcn->current()->KYHIEU;
				$this->view->namebefore = $rowcn->current()->NAME;
				$this->view->codebefore = $rowcn->current()->CODE;
				$this->view->activeselect = $rowcn->current()->ACTIVE;
				$this->view->id= $idCapNhat;
				
				$this->view->title = "Danh Mục Loại Văn Bản _ Cập Nhật";
			}else{
				//truong hop them moi du lieu
				$this->view->title = "Danh Mục Loại Văn Bản _ Thêm mới";
			}
			
		}
		//Hien thi trang nhap lieu
		$this->renderScript("DanhMucLoaiVanBan/InputData.phtml");
		
	}
	
	/**
	 * Ham xu ly cho action thong bao loi
	 */
	public function errorAction()
	{
		
	}
	
	private function checkInputData($name,$active){
		
		$strurl='/default/error/error?control=danhmucloaivanban&mod=qtht&id=';
		$strerr = "";
		$strerr .= ValidateInputData::validateInput('text128_re',$name,'ERR11005001').",";
		$strerr .= ValidateInputData::validateInput('boolean',$active,"ERR11005005").",";
		if(strlen($strerr)!=2){
			$this->_redirect($strurl.$strerr);
		}
		return true;
	}


}
