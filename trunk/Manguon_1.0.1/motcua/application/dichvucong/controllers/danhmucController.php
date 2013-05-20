<?php
	require_once 'dichvucong/models/htdb_danhmuc.php';
	class Dvc_danhmucController extends Zend_Controller_Action{
	
		function init(){
			$this->view->title = "DVC - Danh mục";
		}

		function indexAction(){
			$this->view->subtitle  ="Danh sách";
			$this->view->data = htdb_danhmuc::getAllByList();
			QLVBDHButton::EnableAddNew("");
			QLVBDHButton::EnableDelete("");
		}

		function inputAction(){
			$db = Zend_Db_Table::getDefaultAdapter();
			$config = Zend_Registry::get('config');
			$this->view->dbname = $config->db->params->dbname;

			$params = $this->getRequest()->getParams();
			$sql = "   show tables ";
			$stm = $db->query($sql);
			$this->view->tbllist = $stm->fetchAll();
			$id = $params["id"];
			$this->view->id = $id;
			if(!$id){
				$this->view->subtitle  ="Thêm mới";
				
			}else{ //truong hop cap nhat
				$this->view->subtitle  ="Cập nhật";
				$this->view->data = htdb_danhmuc::getDetail($id);
			}
			QLVBDHButton::EnableSave("/dvc/services/save");
			QLVBDHButton::EnableBack("/dvc/services/index");
			
		}

		function saveAction(){
			$db = Zend_Db_Table::getDefaultAdapter();
			$params = $this->getRequest()->getParams();
			$fields = $params["colslist"];
			$fieldlist = implode(",",$fields);
			$data = array(
				"NAME"=>$params["NAME"],
				"TABLENAME"=>$params["TABLENAME"],
				"CODE"=>$params["CODE"],
				"FIELDLIST"=>$fieldlist,

			);
			$id = $params["id"];
			$this->view->id = $id;
			if(!$id){
				$db->insert("htdb_danhmuc",$data);
			}else{
				$db->update("htdb_danhmuc",$data,"ID_DANHMUC=".(int)$id);
			}
			$this->_redirect("/dvc/danhmuc/index");
			exit;
		}

		function deleteAction(){
			$params = $this->getRequest()->getParams();
			$db = Zend_Db_Table::getDefaultAdapter();
			$ids = $params["DEL"];
			foreach($ids as $id){
				$db->delete("htdb_danhmuc","ID_DANHMUC=".(int)$id);
			}
			$this->_redirect("/dvc/danhmuc/index");
		}

		function onshowcolAction(){
			$this->_helper->layout->disableLayout();
			$params = $this->getRequest()->getParams();
			$tblname = $params["tblname"];
			$db = Zend_Db_Table::getDefaultAdapter();
			if($tblname){
				$sql = "   show columns from $tblname ";
				$stm = $db->query($sql);
				$result = $stm->fetchAll();
			}
			echo Zend_Json::encode($result);
			exit;
		}

		function synchronousAction(){
			$config = Zend_Registry::get('config');
			$this->_helper->layout->disableLayout();
			$params = $this->getRequest()->getParams();
			$code = $params['code'];
			if($code){
				try{
					$xml = htdb_danhmuc::export2xml($code);
					$wsdl = $config->dvc_serviceadapter;//"http://adapter.vn/divucong/server.php?wsdl";
					$username = $config->dvc_username;
					$password = $config->dvc_password;
					$cliente = new SoapClient($wsdl);
					$vem = $cliente->__call('insertdanhmuc',array($username, $password,$code,$xml));
					echo 1;
					//cập nhật thời gian syn
					$db = Zend_Db_Table::getDefaultAdapter();
					$db->update("htdb_danhmuc",array( "LAST_SYS" =>   date(" Y-m-d H:i:s ") ), "CODE='".(string)$code."'" );
				}catch(Exception $ex){
					echo $ex;
				}
			}else{
				echo -1;
			}
			exit;
		}
	}


