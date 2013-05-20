<?php
	require_once 'wf/models/ActivityAccessModel.php';
	require_once 'wf/models/TrangthaihosoModel.php';
	class Wf_TrangthaihosoController extends Zend_Controller_Action{
	
		function init(){
		}

		function indexAction(){
			$this->view->title = "Trạng thái hồ sơ";
			$this->view->subtitle = "Danh sách";
			QLVBDHButton::EnableAddNew("/wf/trangthaihoso/Input");
			$this->view->data = TrangthaihosoModel::getAllTrangthaiWithGroupName();
		}

		function inputAction(){
			$params = $this->getRequest()->getParams();
			$this->view->title = "Trạng thái hồ sơ";
			$this->view->subtitle = "Thêm mới";
			//QLVBDHButton::EnableAddNew("/wf/trangthaihoso/Input");
			$id = $params["id"];
			$activityaccess = new ActivityAccessModel();
			$this->view->group = TrangthaihosoModel::getGroupwithChecked($id);
			//var_dump($this->view->group);
			QLVBDHButton::EnableSave("");
			
			if($id){
				$this->view->id = $id;
				$db = Zend_Db_Table::getDefaultAdapter();
				$this->view->data = $db->query(" select * from wf_trangthaihoso where ID_TTHS=?",$id)->fetch();
			}
		}

		function saveAction(){
			$params = $this->getRequest()->getParams();
			$db = Zend_Db_Table::getDefaultAdapter();
			$id_tths = $params["id"];
			if($id_tths){
				$db->update("wf_trangthaihoso",array("TEN"=>$params["NAME"],"LATRANGTHAINHAN"=>(int)$params["LATRANGTHAINHAN"],"LA_CHOBOSUNG"=>$params["LA_CHOBOSUNG"],"THUOCTOMOTCUA"=>$params["THUOCTOMOTCUA"]),"ID_TTHS=".$id_tths);	
			}else{
				
				$tthsModel = new TrangthaihosoModel();
				$id_tths = $tthsModel->insert(array("TEN"=>$params["NAME"],"LATRANGTHAINHAN"=>(int)$params["LATRANGTHAINHAN"],"LA_CHOBOSUNG"=>$params["LA_CHOBOSUNG"],"THUOCTOMOTCUA"=>$params["THUOCTOMOTCUA"]));
			}

			$arr_old = $params["IS_ON"]==NULL?array():$params["IS_ON"];
			$arr_new = $params["ID_G"]==NULL?array():$params["ID_G"];
			//var_dump($arr_new);
			//exit;

			$arr_add = array_diff($arr_new,$arr_old);
			$arr_delete = array_diff($arr_old,$arr_new);  
			var_dump($arr_new);
			
			$db = Zend_Db_Table::getDefaultAdapter();
			
			foreach($arr_delete as $id_g){
				$db->delete("wf_trangthaihoso_group","ID_G=".$id_g. " and ID_TTHS=".$id_tths );
			}

			
			foreach($arr_add as $id_g){
				
				$db->insert("wf_trangthaihoso_group",array("ID_G"=>$id_g,"ID_TTHS"=>$id_tths) );
			}
			

			$this->_redirect("/wf/trangthaihoso/index");
			exit;
		}
		function deleteAction(){
			$params = $this->getRequest()->getParams();
			$db = Zend_Db_Table::getDefaultAdapter();
			$id_tths = $params["id"];
			if($id_tths){
				$db->delete("wf_trangthaihoso","ID_TTHS=".(int)$id_tths);
				$db->delete("wf_trangthaihoso_group","ID_TTHS=".(int)$id_tths);
			}
			$this->_redirect("/wf/trangthaihoso/index");
		}
	}
?>