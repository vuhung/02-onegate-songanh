<?php
/**
 * ThuMucController
 * 
 * @author hieuvt
 * @version 1.0
 */
require_once 'Zend/Controller/Action.php';
require_once 'hscv/models/ThuMucModel.php';
require_once 'motcua/models/linhvucmotcuaModel.php';

class Hscv_ThuMucController extends Zend_Controller_Action {
	public function indexAction(){
		$this->view->title = "Quản lý thư mục lưu trữ";
		$this->view->subtitle = "Danh sách";
		$thumuc = Array();
		
		QLVBDHCommon::GetTree(&$thumuc,"HSCV_THUMUC","ID_THUMUC","ID_THUMUC_CHA",1,1);
		$this->view->data = $thumuc;
		//Enable button
		QLVBDHButton::EnableDelete("/hscv/thumuc/Delete");
		QLVBDHButton::EnableAddNew("/hscv/thumuc/Input");
	}
	public function inputAction(){
		
		//Lấy parameter
		$parameter = $this->getRequest()->getParams();
		$id = $parameter["id"];
		$ID_LV_MC = $parameter["ID_LV_MC"];
		$this->ID_LV_MC = $ID_LV_MC;
		$this->view->linhvuc = new linhvucmotcuaModel();
		$user = Zend_Registry::get('auth')->getIdentity();
		$this->view->linhvuc = $this->view->linhvuc->SelectAllByUID($user->ID_U);

		//New các model
		$this->thumuc = new ThuMucModel();
		$this->thumuc->_id = $id;
		
		//Lấy dữ liệu
		$thumuc = Array();
		
		
		QLVBDHCommon::GetTreeNoChild(&$thumuc,"HSCV_THUMUC","ID_THUMUC","ID_THUMUC_CHA",1,1,$id);
		$this->view->thumuc = $thumuc;
		
		if($id>0){
			$this->view->data = $this->thumuc->find($id)->current();
			$this->view->title = "Quản lý thư mục lưu trữ";
			$this->view->subtitle = "Cập nhật";
		}else{
			$this->view->title = "Quản lý thư mục lưu trữ";
			$this->view->subtitle = "Thêm mới";
		}
		
		$this->view->group = $this->thumuc->GetAllGroup();
		$this->view->dep = $this->thumuc->GetAllDep();
		$this->view->user = $this->thumuc->GetAllUser();
		QLVBDHButton::EnableSave("#");
		QLVBDHButton::AddButton("Trở lại","","BackButton","BackButtonClick();");
		QLVBDHButton::EnableHelp("");
	}
	public function saveAction(){
		$this->thumuc = new ThuMucModel();
		$parameter =  $this->getRequest()->getParams();
		
		//Check data
		try{
			if($parameter["ID_THUMUC"]>0){
				$this->thumuc->_id = $parameter["ID_THUMUC"];
				$this->thumuc->update(array("NAME"=>$parameter["NAME"],"ID_THUMUC_CHA"=>$parameter["ID_THUMUC_CHA"]),"ID_THUMUC=".$parameter["ID_THUMUC"]);
			}else{
				$this->thumuc->_id = $this->thumuc->insert(array("NAME"=>$parameter["NAME"],"ID_THUMUC_CHA"=>$parameter["ID_THUMUC_CHA"]));
			}
			$this->thumuc->UpdateGroup($parameter["SEL_GROUP_XEM"],$parameter["SEL_GROUP_THEMMOI"],$parameter["SEL_GROUP_PHANQUYEN"]);
			$this->thumuc->UpdateDep($parameter["SEL_DEP_XEM"],$parameter["SEL_DEP_THEMMOI"],$parameter["SEL_DEP_PHANQUYEN"]);
			$this->thumuc->UpdateUser($parameter["SEL_USER_XEM"],$parameter["SEL_USER_THEMMOI"],$parameter["SEL_USER_PHANQUYEN"]);
		}catch(Exception $ex){
			echo $ex->__toString();exit;
			$this->_redirect("/default/error/error?control=ThuMuc&mod=hscv&id=ERR11001001");
		}
		$this->_redirect("/hscv/thumuc");
	}
	public function deleteAction(){
		$thumuc = new ThuMucModel();
		$param =  $this->getRequest()->getParams();
		$thumuc->delete("ID_THUMUC in (".implode(",",$param["DEL"]).")");
		$this->_redirect("/hscv/thumuc");
	}
}