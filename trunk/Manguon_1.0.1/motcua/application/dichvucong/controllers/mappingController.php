<?php
	
	require_once 'dichvucong/models/mapping.php';
	class Dvc_mappingController extends Zend_Controller_Action {
		
		function init(){
			$this->view->title = "Dịch vụ công - Quản lý chuyển đổi dữ liệu";
		}
		
		function indexAction(){
		}

		function inputAction(){
			$params = $this->getRequest()->getParams();
			$id_service = $params["id_service"];
			$this->view->id_service = $id_service;
			$db = Zend_Db_Table::getDefaultAdapter();
			if($id_service){
				$sql = "
					select * from htdb_services where ID_SERVICE = ?
				";
				$stm = $db->query($sql, array($id_service));
				$data = $stm->fetch();
				//var_dump($data);
			}		
			$xmlsource = $params["xmlsource"];
			if(!$xmlsource)
				$xmlsource = $data["SOURCEDES_XML"];
			else
				$db->update("htdb_services",array(
				 "SOURCEDES_XML"	=>  $xmlsource
				),"ID_SERVICE=".(int)$id_service
				);

			$this->view->xmlsource = $xmlsource;
			
			
			$arr = array();
			//var_dump($xmlsource); exit;
			if($xmlsource){
				(Xml_Utils::xmlToArrayDetail($xmlsource,&$arr));
			}

			$this->view->arrdata = $arr;
			//var_dump($arr);
			
			//$xmldestination = $params["xmldestination"];
			if(!$xmldestination)
				$xmldestination = $data["DESTINATIONDES_XML"];
			$this->view->xmldestination = $xmldestination;
			
			if($xmldestination)
				$this->arrdes = (Xml_Utils::xmlToArrayDetail($xmldestination,&$arrdes));

			
			$this->view->arrdes = $arrdes;

			$this->view->mappinginfo = mapping::selectMappingfieldInfo($id_service);
		
			//chuyển đổi thành file xsdlt
			if($xmldestination)
				$this->view->xsdlt = Xml_Utils::createXSDLT($id_service);
			
			//
			$this->view->xmlsourcetest = $xmlsourcetest = $params["xmlsourcetest"];
			$this->view->xmldestinationtest = $xmldestinationtest = $params["xmldestinationtest"];
			
			if($xmlsourcetest ){
				
				$xslt = new XSLTProcessor;
				$xsdlto = new DOMDocument;
				$xsdlto->loadXML($this->view->xsdlt);
				$xslt->importStyleSheet($xsdlto);
				$xml = new DOMDocument;
				$xml->loadXML($xmlsourcetest);
				//$this->view->xsdlt .= ($xslt->transformToXML($xml));
				$this->view->xmldestinationtest = $xmldestinationtest = ($xslt->transformToXML($xml));
			}else{
				
			}

			
			QLVBDHButton::AddButton("Định nghĩa dữ liệu","","write","OnclickDefineXML();");
			QLVBDHButton::EnableSave("/dvc/mapping/save");
			QLVBDHButton::EnableBack("/dvc/mapping/index");
			
		}
		
		/*function echoJsArrDes($arrdes,$level){
			$jscode = "var arrdesjs = new Array();";
			
			foreach($arrdes as $key => $value){
				
				$jscode .= "
				arrdesjs[] = new Array(level,);
				
				"	
			}
		}*/
		

		function xmltotreeAction(){
			$params = $this->getRequest()->getParams();
			var_dump($params);
			$xmlsource = $params["xmlsource"];
		}

		function saveAction(){
			$params = $this->getRequest()->getParams();
			$db = Zend_Db_Table::getDefaultAdapter();
			$id_service = (int)$params["id_service"];
			$xmlsource = $params["xmlsource"];
			$xmldestination = $params["xmldestination"];
			
			$db->delete("htdb_mappingdefinition","ID_SERVICE=".(int)$id_service);
			foreach($params as $key=>$value){
				if($value){
					$keyparses = explode("_",$key);
					if($keyparses[0] == "sel"){
						//dung thang combobox mapping roi
						$level = $keyparses[count($keyparses)-1];
						$arrmidle = array();
						for($k=1; $k < count($keyparses)-1 ; $k++ ){
							$arrmidle[] = $keyparses[$k];
						}
						$source_value = implode("_",$arrmidle);
						
						$dess = explode("_",$value);
						$level_des = $dess[count($dess)-1];
						$arrmidle = array();
						for($k=0; $k < count($dess) -1 ; $k++ ){
							$arrmidle[] = $dess[$k];
						}
						$desfield = implode("_",$arrmidle);
						
						$arr_p_des = explode("#",$desfield);
						$arr_p_src = explode("#",$source_value); 
						$datamapping = array(
							"ID_SERVICE"=> (int)$id_service,
							"NAMEFIELDDES"=> $arr_p_src[0],
							"NAMEFIELDSRC"=> $arr_p_des[0],
							"LEVEL_SRC"=> (int)$level_des,
							"LEVEL_DES"=> (int)$level,
							"PARRENT_DES"=>(string)$arr_p_src[1],
							"PARRENT_SRC"=>(string)$arr_p_des[1],
						);
						
						$db->insert("htdb_mappingdefinition",$datamapping);

						/*$sql = " select count(*) as DEM from htdb_mappingdefinition where 
								 ID_SERVICE = ? and
								 NAMEFIELDSRC = ? and  LEVEL_SRC = ? 
						";
						$stm = $db->query($sql,array($id_service,$arr_p_src[0],$level));
						$re = $stm->fetch();
						if($re["DEM"]){
							$db->update("htdb_mappingdefinition",$datamapping,"ID_SERVICE=".(int)$id_service."  AND NAMEFIELDSRC='".addslashes($arr_p_src[0])."'  AND LEVEL_SRC=".(int)$level."");	
						}else{
							$db->insert("htdb_mappingdefinition",$datamapping);	
						}*/
							
					}
				}
			}
			$xsdlt = Xml_Utils::createXSDLT($id_service);
			$data = array(
				"SOURCEDES_XML"=>$xmlsource,
				"CONVERT_TEMPLATE"=>$xsdlt
			);
			
			//var_dump($params);exit;			
			$db->update("htdb_services",$data,"ID_SERVICE=".(int)$id_service);
			
			
			$this->_redirect("/dvc/mapping/input/id_service/".(int)$id_service);
		}
		function showxmlAction(){	
			header( "Content-type: text/xml;charset=utf-8" );
			
			$params = $this->getRequest()->getParams();
			
			$code = $params["code"];
			if($code){
				$type = $params["type"];
				switch($type){
					case 1:
						$colname = "DESTINATIONDES_XML";
						break;
					case 0:
						$colname = "SOURCEDES_XML";
						break;
					case 3:
						$colname = "CONVERT_TEMPLATE";
					default:
						break;
				}	
				
				$db = Zend_Db_Table::getDefaultAdapter();
				$sql ="
					select $colname from htdb_services 
					WHERE ID_SERVICE = ?
				";
				$stm = $db->query($sql,array($code));
				$data = $stm->fetch();
				
				echo $data[$colname];

			}else{
				echo base64_decode($params["data"]);
			}
			exit;
		}

	}
?>