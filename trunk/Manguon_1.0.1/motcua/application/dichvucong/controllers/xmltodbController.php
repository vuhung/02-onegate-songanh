<?php
	require_once('dichvucong/models/xmltodb.php');
	class Dvc_xmltodbController extends Zend_Controller_Action{
	
		function init(){
			$this->view->title = "Định nghĩa dữ liệu đầu vào";
		}

		function indexAction(){
		
		}

		function inputAction(){
			$db = Zend_Db_Table::getDefaultAdapter();
			$params = $this->getRequest()->getParams();
		
			$this->view->servicecode = $params["SERVICECODE"];
			
			
			$sql = "   show tables ";
			$stm = $db->query($sql);
            $config = Zend_Registry::get('config');
            $dbName = $config->db->params->dbname;
			$this->view->dbName = trim($dbName);
			$this->view->tbllist = $stm->fetchAll();
			$id = $params["id"];
			$this->view->id = $id;
			
			if(!$id){
				$this->view->subtitle  ="Thêm mới";
				
			}else{ //truong hop cap nhat
				$this->view->subtitle  ="Cập nhật";
				$this->view->data = xmltodb::getDetail((int)$id);
				$this->view->servicecode = $this->view->data["SERVICECODE"];
				$this->view->datachild = xmltodb::getChildDetail((int)$id);
				
				//var_dump($this->view->data);
				
			}
			QLVBDHButton::EnableSave("/dvc/services/save");
			QLVBDHButton::EnableBack("/dvc/services/index");
			//lay thong tin thu muc cha
			$sql = "
				select * from htdb_xmltodbitems where SERVICECODE = ? and TYPENODE = 1
			";
			$stm = $db->query($sql,(string)$this->view->servicecode);
			$this->view->parentinfo = $stm->fetchAll();
		}

		function saveAction(){
			$params = $this->getRequest()->getParams();
			//var_dump($params); exit;
			$servicecode = $params["SERVICECODE"];
			$db = Zend_Db_Table::getDefaultAdapter();
			$id = $params["id"];
			//cap nhat nut cha
			$data = array(
			"TABLENAME"=> $params["TABLENAME"],
			"NAME"=> $params["NAME"],
			"PARENT"=> $params["PARENT"],
			"TYPENODE"=>1,
			"SERVICECODE"=>$servicecode
			);
			
			if($id){
				$db->update("htdb_xmltodbitems",$data,"ID_DBDVCI=".(int)$id);
				$id_cur_parent = $id;
			}else{
				$db->insert("htdb_xmltodbitems",$data);
				//lay id cua node vua them
				$sql = "
					select MAX(ID_DBDVCI) as CUR_END from htdb_xmltodbitems 
				";


				$qr = $db->query($sql);
				$qr = $qr->fetch();
				$id_cur_parent = $qr["CUR_END"];
			}
			
			
			//cap nhat cac nut con
			$colslist = $params["colslist"];
			foreach($colslist as $col){
				
				$data = array(
					"TABLENAME"=> $params["TABLENAME"],
					"COLUMNNAME"=> $col,
					"NAME"=> $params["NAME__".$col],
					"PARENT"=> $id_cur_parent,
					"TYPENODE"=>0,
					"SERVICECODE"=>$servicecode	
				);
				if($params["identity"] == $col){
					$data["IS_IDENTITY"] = 1;
				}else{
					$data["IS_IDENTITY"] = 0;
				}

				//check kiểm tra đã tồn tại chưa
				if($id){
					$sql = "
						select count(*) as DEM from htdb_xmltodbitems 
						where PARENT = ? and NAME  = ?
					";
					$qr = $db->query($sql,array($id,$params["NAME__".$col]));
					$re = $qr->fetch();
					if($re["DEM"]){
						//cap nhat
						$db->update("htdb_xmltodbitems",$data,"NAME='".(string)$params["NAME__".$col]."' and PARENT=".(int)$id );
					}else{
						//them moi
						$db->insert("htdb_xmltodbitems",$data);
					}
				}else{
					//them moi
					$db->insert("htdb_xmltodbitems",$data);
				}
			}
			
			xmltodb::updatexsdlt($servicecode);
			$sql ="
				select ID_SERVICE from htdb_services 
				WHERE CODE = ?
			";
			$stm = $db->query($sql,array($servicecode));
			$data = $stm->fetch();
			//exit;
			$this->_redirect('/dvc/services/input/id/'.(int)$data["ID_SERVICE"]);
			
		}

		function deleteAction(){
			
			//tim node con
			$params = $this->getRequest()->getParams();
			$id = $params["id"];
			$db = Zend_Db_Table::getDefaultAdapter();
			$sql = "
				select count(*) as DEM from htdb_xmltodbitems where 
				PARENT = ? and TYPENODE = 1
			";
			$qr = $db->query($sql,(int)$id);
			$re = $qr->fetch();
			$dem = $re["DEM"];
			if($dem){
				//echo "Bạn không thể xóa được node này. Hãy xóa các node danh sách con trước";
				echo 0;
			}else{
				$db->delete("htdb_xmltodbitems","ID_DBDVCI=".(int)$id);
				//xoa các nút con
				$db->delete("htdb_xmltodbitems","PARENT=".(int)$id);
				
				xmltodb::updatexsdlt($servicecode);
				echo 1;
			}

			exit;
		}

		
		
	}