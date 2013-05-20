<?php
	require_once "dichvucong/models/htdb_services.php";
	require_once('dichvucong/models/xmltodb.php');
	require_once('dichvucong/models/motcua_taptin_web.php');
	class Dvc_servicesController extends Zend_Controller_Action{
	
		function init(){
			$this->view->title = "Quản lý dịch vụ công";
		}

		function indexAction(){
			QLVBDHButton::EnableAddNew("");
			QLVBDHButton::EnableDelete("");
			$this->view->subtitle = "Danh sách";
			$htdb_services = new htdb_services();
			$this->view->data = $htdb_services->selectAllForList();
			//var_dump($this->view->data);
			//QLVBDHButton::EnableSave("/dvc/services/save");
			//QLVBDHButton::EnableBack("/dvc/services/index");

		}

		function inputAction(){
			$params = $this->getRequest()->getParams();
			$this->view->id = $id = $params["id"];
			$htdb_services = new htdb_services();
			if($id){
				$this->view->subtitle = "Cập nhật";
				$this->view->data = $htdb_services->selectById($id);
				$this->view->id_lvmc = $this->view->data["ID_LV_MC"];
				$this->view->id_loaihoso = $this->view->data["ID_LOAIHOSO"];
				//var_dump($this->view->data);
			}else{
				$this->view->subtitle = "Thêm mới";
				//$htdb_services
			}
			
			$ID_LVMC = (int)$params["ID_LVMC"];
			$ID_LOAIHOSO = (int)$params["ID_LOAIHOSO"];
			$sql = "
				select * from motcua_linhvuc 
			";
			$db = Zend_Db_Table::getDefaultAdapter();
			$qr = $db->query($sql);
			$this->view->datalvmc = $qr->fetchAll();
			//if(!$ID_LVMC){
				//$ID_LVMC = 	$this->view->datalvmc[0]["ID_LV_MC"];
			//}
			$sql =" select * from motcua_loai_hoso "; 
			$qr = $db->query($sql);
			$this->view->dataloaihs = $qr->fetchAll();
			//lay thong tin chuyen doi du lieu
			/*$sql = "
				select * from htdb_xmltodbitems where SERVICECODE = ?
			";
			$qr = $db->query($sql,array($this->view->data["CODE"]) );
			$this->view->datadb = $qr->fetchAll();*/
			$datadb;
			xmltodb::getListForServiceCode(&$datadb,$this->view->data["CODE"],(int)$parent);
			$xmltemplate;
			xmltodb::dbtoxml(&$xmltemplate,$this->view->data["CODE"],0);
			$this->view->xmltemplate = htmlspecialchars($xmltemplate);
			$this->view->datadb = $datadb;
			//var_dump($this->view->datadb);
			QLVBDHButton::AddButton("Thêm node mới","","xml","addnewClick()");
			QLVBDHButton::AddButton("Quản lý chuyển đổi","","xmlmapping","mappingClick()");
			QLVBDHButton::EnableSave("/dvc/services/save");
			//QLVBDHButton::EnableBack("/dvc/services/index");
			QLVBDHButton::AddButton("Danh sách","","BackButton","BackButtonClick()");
		}

		function saveAction(){
			$db = Zend_Db_Table::getDefaultAdapter();
			$params = $this->getRequest()->getParams();
			$id = (int)$params["id"];
			$data = array(
				"TENDICHVU" =>$params["TENDICHVU"],
				"CODE" =>$params["CODE"],
				"ID_LOAIHOSO" =>$params["ID_LOAIHOSO"],
			);
			try{
				if($id){
					$db->update("htdb_services",$data,"ID_SERVICE=".(int)$id);
					xmltodb::updatexsdlt($params["CODE"]);
					if($params["redirect"]="mapping")
					{
						//luu xml
						$this->_redirect("/dvc/mapping/input/id_service/".(int)$id);
					}
				}else{
					$db->insert("htdb_services",$data);
					xmltodb::updatexsdlt($params["CODE"]);
				}
				
			}catch(Exception $ex){
				echo $ex->__toString();
			}
			$this->_redirect("/dvc/services/");
		}

		function deleteAction(){
			$params = $this->getRequest()->getParams();
			//var_dump($params);
			$db = Zend_Db_Table::getDefaultAdapter();
			$dels = $params["DEL"];
			try{
				$db->query(" delete from htdb_services where ID_SERVICE IN (" . implode(",",$dels) . ")" );
			}catch(Exception $ex){
				echo $ex->__toString();
			}
			$this->_redirect("/dvc/services/");
		}

		function getinfofromadapterAction(){
					
			$config 	= Zend_Registry::get('config');
			$dvcFolder	= $config->dvc_uploadpath;
			$params 	= $this->getRequest()->getParams();
			$ischeck 	= (int)$params["ischeck"]; 
			$wsdl 		= $config->dvc_serviceadapter;//"http://adapter.vn/divucong/server.php?wsdl";
			$username 	= $config->dvc_username;
			$password 	= $config->dvc_password;
			$fpath 		= $config->file->root_dir;
			try{
				$cliente 	= new SoapClient($wsdl);
			}catch(Exception $ex){
				echo $ex->getMessage();exit;
			}
			 if($ischeck > 0) {
                require_once 'Common/ajax.php';
                $vem = $cliente->__call('checkHoSo',array($username, $password,'Qlvbdh@f1'));
				ajax::ship('soluonghoso',$vem);
                exit;
            }else{
				//print "<p>Envio Internacional: ";
				$vem = $cliente->__call('gethoso',array($username, $password,'code','root','item'));
				//parse du lieu
				$doc = new DOMDocument();
				$doc->loadXML($vem);
				//echo htmlspecialchars($vem);exit;
				//get root
				$nodes = $doc->getElementsByTagName("item");
				$nodeListLength = $nodes->length; // this value will also change
				
				for ($i = 0; $i < $nodeListLength; $i ++)
				{
					 $node = $nodes->item($i);
					 $xmlsource =  base64_decode($node->nodeValue);

					 $madichvu = $node->getAttribute('MADICHVU');
					 $mahoso = $node->getAttribute('MAHOSO');
					 //echo $mahoso."<br>";
					 $returnstr =  xmltodb::insertXmlInfoDb($xmlsource,$madichvu,$mahoso );
			   }	   
				// Đồng bộ các tập tin
				$role = "Qlvbdh@f1";			
				$taptinModel = new motcua_taptin_web();
				$taptinModel->getFileFromAdapter($wsdl,$username,$password,$fpath,$dvcFolder,$role);
				
				$this->_redirect("/motcua/dongbohsmc/index");exit;
			}
		}

	}
