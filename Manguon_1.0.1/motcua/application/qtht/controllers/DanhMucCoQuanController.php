<?php
/**
 * @name DanhMucCoQuanController
 * @author trunglv
 * @package qtht
 * @version 1.0
 */
require_once ('Zend/Controller/Action.php');
require_once 'qtht/models/CoQuanModel.php';
require_once 'Common/common.php';
require_once 'Common/button.php';
require_once 'Common/ValidateInputData.php';
require_once 'config/qtht.php';

class Qtht_DanhMucCoQuanController extends Zend_Controller_Action {

	var $coquanTable; // doi tuong bang co quan
	
	/**
	 * Ham khoi tao du lieu cho controller
	 */
	public function init()
	{
		$this->coquanTable = new CoQuanModel();
		$this->view->title = "Danh Mục Cơ Quan";
	}
	
	/**
	 * Ham xu ly cho action xem danh sach bang co quan
	 */
	
	public function indexAction()
	{
		$this->view->data = array();
		$this->view->subtitle="Quản lý danh mục cơ quan";
		QLVBDHButton::EnableAddNew("/qtht/DanhMucCoQuan/input");
		QLVBDHButton::EnableDelete("/qtht/DanhMucCoQuan/delete");
		$search = $this->_request->getParam("search");
		$this->view->search = $search;
		$search = "%".$search."%";
		QLVBDHCommon::GetTreeByName($search,'NAME',&$this->view->data,"vb_coquan","ID_CQ","ID_CQ_PARENT",1,1);
	}
	
	/**
	 * Ham xu ly cho action them moi hoac cap nhat thong tin co quan
	 *  
	 */
	
	public function saveAction()
	{
		//$this->view->data = array();//$this->coquanTable->fetchAll();
		QLVBDHCommon::GetTree(&$this->view->data,"vb_coquan","ID_CQ","ID_CQ_PARENT",1,1);
		
		
		if($this->_request->isPost())
		{
			//Lay du lieu post len tu form nhap lieu
		 	$phamvi = $this->_request->getParam("phamvi");
			if(!$phamvi)
				$phamvi = 0;
			$kyhieu = $this->_request->getParam("kyhieu");	
		 	$name = $this->_request->getParam("name");
			$id = $this->_request->getParam("idCoQuan");
			$parent = $this->_request->getParam("choiceCQCha");
			$capcq = $this->_request->getParam("choiceCapCQ");
			$code = $this->_request->getParam("code");
			$email = $this->_request->getParam("email");
			//Kiem tra du lieu hop le
			$this->checkInputData($name,$phamvi,$parent,$capcq) ;
			if($id>1){
				//Truong hop cap nhat du lieu
				$where = 'ID_CQ='.$id;
				try{
					$this->coquanTable->update(array('NAME'=>$name,'CODE'=>$code ,'ISSYSTEMCQ'=>$phamvi , "ID_CQ_PARENT" =>$parent,"CAPCQ"=>$capcq,"EMAIL"=>$email,"KYHIEU"=>$kyhieu),$where);
					$this->_redirect('/qtht/DanhMucCoQuan');
				}catch (Exception $e){
					//cap nhat co so du lieu khong thanh cong
					$this->_redirect('/default/error/error?control=DanhMucCoQuan&mod=qtht&id=ERR11013008');
				}	
			
			}else{
				//Truong hop them moi du lieu
				$arr_newdata = array("NAME"=> $name , "ISSYSTEMCQ" => $phamvi , 'CODE'=>$code,"ID_CQ_PARENT" =>$parent ,"CAPCQ"=>$capcq,"KYHIEU"=>$kyhieu);
				try{
					$this->coquanTable->insert($arr_newdata);
				}catch (Exception $ex){
					//Loi khong the them co quan vao csdl
					$this->_redirect('/default/error/error?control=DanhMucCoQuan&mod=qtht&id=ERR11013009');
				}
			}
			//chuyen den trang xem danh sach danh muc co quan
			$this->_redirect('/qtht/DanhMucCoQuan');
		}else {
			//chuyen den trang nhap lieu
			$this->renderScript("DanhMucCoQuan/InputData.phtml");
		}
	}
	
	/**
	 * Ham xu ly cho action xoa mot co quan
	 */
	public function deleteAction()
	{
		
		
		if($this->_request->isPost())
		{
			
			// Lay id cua cac co quan can xoa
			$idarray = $this->_request->getParam('DEL');
			
			//Thuc hien xoa cac co quan
			$where = 'ID_CQ in ('.implode(',',$idarray).')';
			
			try{
				if(!$this->coquanTable->delete($where)) 
				{
					//Khong the xoa
					$this->_redirect('/default/error/error?control=DanhMucCoQuan&mod=qtht&id=ERR11013010');
				}
				
			}catch (Exception  $e)
			{
				
				$this->_redirect('/default/error/error?control=DanhMucCoQuan&mod=qtht&id=ERR11013010');
				//Xoa co quan that bai
			}
			//chuyen den trang xem danh sach danh muc co quan
			$this->_redirect('/qtht/DanhMucCoQuan');
		}
		else 
		{
			$this->_redirect('/qtht/DanhMucCoQuan');
		}
		
		
	
	}
	
	/**
	 * Ham xu ly cho action hien thi form nhap lieu cho nguoi dung
	 */
	public function inputAction()
	{
		
		$this->view->data = array();
		QLVBDHCommon::GetTree(&$this->view->data,"vb_coquan","ID_CQ","ID_CQ_PARENT",1,1);
		QLVBDHButton::EnableSave("/qtht/DanhMucCoQuan/save");
		QLVBDHButton::AddButton("Trở lại","","BackButton","BackButtonClick();");
		$this->view->page = $this->_request->getParam('page');
		$this->view->limit = $this->_request->getParam('limit');
		$this->view->filter_object = $this->_request->getParam('filter_object');
		$this->view->search = $this->_request->getParam("search");	
		if($this->_request->isGet())
		{
			// Lay id cua co quan can cap nhat
			$idCapNhat = $this->_request->getParam('idCoQuan');
			if($idCapNhat>0){
				//Truong hop cap nhat du lieu
				//Kiem tra id cua co quan co ton tai hay khong
				$rowcn = $this->coquanTable->find($idCapNhat);		
				if($rowcn->count() == 0){
						//Khong tim thay record trong csdl
						$this->_redirect('/default/error/error?control=DanhMucCoQuan&mod=qtht&id=ERR11013008');
				}
				else
				{   $this->view->kyhieu = $rowcn->current()->KYHIEU;
					$this->view->namebefore = $rowcn->current()->NAME;
					$this->view->codebefore = $rowcn->current()->CODE;
					$this->view->isnoibo = $rowcn->current()->ISSYSTEMCQ;
					$this->view->id= $idCapNhat;
					$this->view->cap =$rowcn->current()->CAPCQ;	
					$this->view->emailbefore =$rowcn->current()->EMAIL;	
					
				}
				
				$this->view->subtitle="Cập nhật";
				$this->view->action="capnhat";
			}
			else{
				$this->view->subtitle="Thêm mới";
				$this->view->action="themmoi";
				//truong hop them moi
			}
			//Hien trang nhap lieu 
			$this->renderScript("DanhMucCoQuan/InputData.phtml");
			
			
		}else{
			$this->_redirect('/qtht/DanhMucCoQuan');
		}
			
	}
	
	private function checkInputData($name,$phamvi,$parent,$capcq){
		$strurl='/default/error/error?control=DanhMucCoQuan&mod=qtht&id=';
		$strerr = "";
		$strerr .= ValidateInputData::validateInput('text128_re',$name,"ERR11013001").",";
		$strerr .= ValidateInputData::validateInput('boolean',$phamvi,"ERR11013006").",";
		$strerr .= ValidateInputData::validateInput('int',$parent,"ERR11013007").",";
		$strerr .= ValidateInputData::validateInput('int_between_inclusive=0,4',$capcq,"ERR11013005").",";
		if(strlen($strerr)!=4){
			$this->_redirect($strurl.$strerr);
		}
		return true;
	}
	 
}


