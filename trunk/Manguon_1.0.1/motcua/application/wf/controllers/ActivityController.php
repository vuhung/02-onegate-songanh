<?php

/**
 * ActivityController
 * 
 * @author nguyennd
 * @version 1.0
 */

require_once 'Zend/Controller/Action.php';
require_once 'wf/models/ActivityModel.php';
require_once 'wf/models/ProcessModel.php';
require_once 'wf/models/ActivityPoolModel.php';
require_once 'config/wf.php';
require_once 'Common/ValidateInputData.php';
require_once 'wf/models/TrangthaihosoModel.php';


class Wf_ActivityController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated ActivityController::indexAction() default action

		$this->view->parameter =  $this->getRequest()->getParams();
		$this->activity = new ActivityModel();
		$this->activity->_id_p = $this->view->parameter["idp"];
		$this->view->page = $this->view->parameter["page"];
		if($this->view->page==0 || $this->view->page=="")$this->view->page=1;
		
		$rowcount = $this->activity->Count();
		$this->view->data = $this->activity->fetchAll("ID_P=".$this->activity->_id_p,"NAME",10,($this->view->page-1)*10);
		$this->view->title = "Quản lý trạng thái";
		$this->view->showPage = QLVBDHCommon::Paginator($rowcount,5,10,"/wf/Process/index",$this->view->page) ;
		
		$this->process = new ProcessModel();
		$this->view->process = $this->process->fetchAll();
		
				
		$this->view->title = "Danh sách trạng thái";
		QLVBDHButton::EnableDelete("/wf/Activity/Delete");
		QLVBDHButton::EnableAddNew("/wf/Activity/Input");
	}
	/**
	 * Hiển thị form nhập liệu
	 */
	public function inputAction() {
		//Lấy parameter
		$parameter = $this->getRequest()->getParams();
		$id = $parameter["id"];
		$idp = $parameter["idp"];
		$db = Zend_Db_Table::getDefaultAdapter();
		//New các model
		$this->activity = new ActivityModel();
		$this->process = new ProcessModel();
		$this->currentprocess = $this->process->find($idp)->current();
		$this->activitypool = new ActivityPoolModel();
		$this->activitypool->_id_c = $this->currentprocess->ID_C;
		$this->view->activitypool = $this->activitypool->SelectAll(0,0,"");
		if($id>0){
			$this->view->data = $this->activity->find($id)->current();
			$this->view->title = "Trạng thái";
			$this->view->subtitle = "Cập nhật";
		}else{
			$this->view->title = "Trạng thái";
			$this->view->subtitle = "Thêm mới";
		}
		
		$this->view->idp = $idp;
		if($id){
			$this->view->tthss = TrangthaihosoModel::getAllTrangthaiWithActivity($id);
		}else{
			$this->view->tthss = TrangthaihosoModel::getAllTrangthaiWithGroupName();
		}
		//$this->view->tthss = TrangthaihosoModel::getAllTrangthaiWithGroupName();
		
		//var_dump($this->view->tthss);
		QLVBDHButton::EnableSave("#");
		QLVBDHButton::AddButton("Trở lại","","BackButton","BackButtonClick();");
		QLVBDHButton::EnableHelp("");
	}
	/**
	 * Lưu dữ liệu.
	 * Nếu đã có thì update
	 * Nếu chưa có thì insert
	 */
	public function saveAction(){
		$this->activity = new ActivityModel();
		$this->view->parameter =  $this->getRequest()->getParams();
		//var_dump($this->view->parameter);
		//exit;
		//Check data
		$this->checkInput($this->view->parameter["NAME"],$this->view->parameter["ID_AP"]);
		try{
			$id_a = $this->view->parameter["ID_A"];
			if($this->view->parameter["ID_A"]>0){
				$this->activity->update(array("NAME"=>$this->view->parameter["NAME"],"ACTIVE"=>$this->view->parameter["ACTIVE"],"ID_AP"=>$this->view->parameter["ID_AP"]),"ID_A=".$this->view->parameter["ID_A"]);
			}else{
				$id_a = $this->activity->insert(array("NAME"=>$this->view->parameter["NAME"],"ACTIVE"=>$this->view->parameter["ACTIVE"],"ID_AP"=>$this->view->parameter["ID_AP"],"ID_P"=>$this->view->parameter["idp"]));
			}
			
			$arr_old = $this->view->parameter["IS_ON"]==NULL?array():$this->view->parameter["IS_ON"];
			$arr_new = $this->view->parameter["ID_TTHS"]==NULL?array():$this->view->parameter["ID_TTHS"];
			
			$arr_add = array_diff($arr_new,$arr_old);
			$arr_delete = array_diff($arr_old,$arr_new);  
		
			$db = Zend_Db_Table::getDefaultAdapter();
			
			foreach($arr_delete as $tths){
				$db->delete("wf_activity_fktth","ID_A=".$id_a. " and ID_TTHS=".$tths );
			}

			
			foreach($arr_add as $tths){
				$db->insert("wf_activity_fktth",array("ID_A"=>$id_a,"ID_TTHS"=>$tths) );
			}
			
		
		}catch(Exception $ex){
			echo $ex->__toString();exit;
			$this->_redirect("/default/error/error?control=Activity&mod=wf&id=ERR11006001");
		}

		$this->_redirect("/wf/Workflow/index/idp/".$this->view->parameter["idp"]);
	}
	/**
	 * Kiểm tra dữ liệu, nếu ok thì trả về true.
	 */
	public function checkInput($name,$id_ap){
		$strurl='/default/error/error?control=Activity&mod=wf&id=';
		$strerr = "";
		$strerr .= ValidateInputData::validateInput('maxlength=50',$name,"ERR11006003").",";
		$strerr .= ValidateInputData::validateInput('req',$name,"ERR11006004").",";
		$strerr .= ValidateInputData::validateInput('req',$id_ap,"ERR11006008").",";
		if(strlen($strerr)!=3){
			$this->_redirect($strurl.$strerr);
		}
	}
	/**
	 * Xoá dữ liệu
	 */
	public function deleteAction(){
		$this->activity = new ActivityModel();
		$this->view->parameter =  $this->getRequest()->getParams();
		try{
			$this->activity->delete("ID_A IN (".implode(",",$this->view->parameter["DELA"]).")");
		}catch(Exception $ex){
			$this->_redirect("/default/error/error?control=Activity&mod=wf&id=ERR11006001");
		}
		$this->_redirect("/wf/Workflow/index/idp/".$this->view->parameter["idp"]);
	}
}
