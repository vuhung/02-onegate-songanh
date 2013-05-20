<?php

/**
 * ClassController
 * 
 * @author locbc
 * @version 1.0
 */


require_once 'qtht/models/DanhMucPhuongModel.php';

class Qtht_DanhMucPhuongController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated ActivityPoolController::indexAction() default action
		
		//Lấy parameter
		$config = Zend_Registry::get('config');
		$parameter = $this->getRequest()->getParams();
		$limit = $parameter["limit"];
		$page = $parameter["page"];
		$search = $parameter["search"];
		
		//Tinh chỉnh parameter
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		
		//New các model
		$this->danhmucphuong = new DanhMucPhuongModel();
		
		//Khởi động các biến cho các model
		$this->danhmucphuong->_search = $search;
	
		//Lấy dữ liệu chính
		$rowcount = $this->danhmucphuong->Count();
		
		$this->view->data = $this->danhmucphuong->SelectAll(($page-1)*$limit,$limit,"TENPHUONG");
		//var_dump($this->view->data);exit;
		//Set biến cho view
		$this->view->title = "Quản lý Phường/Xã";
		$this->view->subtitle = "Danh sách";
		$this->view->limit = $limit;
		$this->view->search = $search;
		$this->view->page = $page;
		$this->view->showPage = QLVBDHCommon::Paginator($rowcount,5,$limit,"frm",$page) ;
		
		//Enable button
		QLVBDHButton::EnableDelete("/qtht/DanhMucPhuong/Delete");
		QLVBDHButton::EnableAddNew("/qtht/DanhMucPhuong/Input");
	}
	/**
	 * Hiển thị form input dữ liệu
	 */
	public function inputAction(){
		
		//Lấy parameter
		$parameter = $this->getRequest()->getParams();
			
		$id = $parameter["id"];	
		$this->view->id=$id;	
		//New các model
		$this->danhmucphuong = new DanhMucPhuongModel();	
		
		//Lấy dữ liệu
		if($id>0){
			$this->view->data = $this->danhmucphuong->find($id)->current();
			$this->view->tenphuong = $this->view->data->TENPHUONG;
			$this->view->maso= $this->view->data->MA;
			$this->view->activeselect = $this->view->data->ACTIVE;
			$this->view->title = "Quản lý Phường";			
			$this->view->subtitle = "Cập nhật";
		}else{
			$this->view->title = "Quản lý Phường";			
			$this->view->subtitle = "Thêm mới";
		}	
		QLVBDHButton::EnableSave("/qtht/DanhMucPhuong/save");
		QLVBDHButton::AddButton("Trở lại","","BackButton","BackButtonClick();");
		QLVBDHButton::EnableHelp("");
	}
	/**
	 * Lưu dữ liệu.
	 * Nếu đã có thì update
	 * Nếu chưa có thì insert
	 */
	public function saveAction(){
	  
	    $this->danhmucphuong = new DanhMucPhuongModel();		
		$this->view->parameter =  $this->getRequest()->getParams();
		$id = $this->view->parameter["id_phuong"];		
				
			if($id>0){
			     $where = 'ID_PHUONG='.$id;			  
		       try{
				   $this->danhmucphuong->getDefaultAdapter()->update('motcua_phuong',
				   array("TENPHUONG"=>$this->view->parameter["tenphuong"],
				         "MA"=>$this->view->parameter["maso"],
				         "ACTIVE"=>$this->view->parameter["active"]),$where);
		          }catch (Exception  $ex){
		  	                            echo $ex->__toString();
		                               	exit;
		                               } 
			}else{
			try{
				$this->danhmucphuong->getDefaultAdapter()->insert('motcua_phuong',
				     array("TENPHUONG"=>$this->view->parameter["tenphuong"], 
				     "MA"=>$this->view->parameter["maso"],
				     "ACTIVE"=>$this->view->parameter["active"]
				     ));				
			   }catch (Exception  $ex){
		  	                            echo $ex->__toString();
		                               	exit;
		                               } 
			}		
		$this->_redirect("/qtht/DanhMucPhuong/index");
		
	}
	
	
	/**
	 * Xoá dữ liệu
	 */
	public function deleteAction(){
	   
		$this->danhmucphuong = new DanhMucPhuongModel();
		$this->view->parameter =  $this->getRequest()->getParams();
		try{
			$this->danhmucphuong->delete("ID_PHUONG IN (".implode(",",$this->view->parameter["DEL"]).")");
		}catch(Exception $ex){
			echo $ex->__toString();
		    exit;
		}
		$this->_redirect("/qtht/DanhMucPhuong/index");
	}
}
?>