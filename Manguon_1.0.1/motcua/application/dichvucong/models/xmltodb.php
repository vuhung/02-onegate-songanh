<?
class xmltodb{
	
	function printTabLevel($level){
		$str = "";
		for($i = 0; $i < $level;$i++)
			$str .= "--------";
		return $str;
	}
	
	function getListForServiceCode(&$arrre,$code,$parent,$level){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = "
				select * from htdb_xmltodbitems where SERVICECODE = ? and PARENT = ?
				ORDER BY TABLENAME
		";
		$stm = $db->query($sql,array($code,$parent));
		$data = $stm->fetchAll();
		foreach($data as $item){
			$item["LEVEL"] = (int)$level;
			$arrre[] = $item;
			$parent = $item["ID_DBDVCI"];
			xmltodb::getListForServiceCode(&$arrre,$code,$parent,(int)$level+1);
		}
	}

	function dbtoxml(&$xml,$code,$parent,$level){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = "
				select * from htdb_xmltodbitems where SERVICECODE = ? and PARENT = ?
				ORDER BY TABLENAME
		";
		$stm = $db->query($sql,array($code,$parent));
		$data = $stm->fetchAll();
		foreach($data as $item){
			$xml .= "<".$item["NAME"].">";
			$parent = $item["ID_DBDVCI"];
			xmltodb::dbtoxml(&$xml,$code,$parent);
			$xml .= "</".$item["NAME"].">";
			
		}
	}
	function getDetail($id){
		try{
			$db = Zend_Db_Table::getDefaultAdapter();
			$sql = "
				select * from htdb_xmltodbitems 
				where ID_DBDVCI = ?
			";
			$stm = $db->query($sql,array($id));
			return $stm->fetch();
		}catch(Exception $ex){
			return array();
		}
	}

	function getChildDetail($id){
		try{
			$db = Zend_Db_Table::getDefaultAdapter();
			$sql = "
				select * from htdb_xmltodbitems 
				where PARENT = ?
			";
			$stm = $db->query($sql,array($id));
			return $stm->fetchAll();
		}catch(Exception $ex){
			return array();
		}
		
	}

	function xmltoQuery(){
		
	}

	function insertXmlInfoDb($xmlsource,$code,$mahoso){
			
			//lay thong tin
			
			$db = Zend_Db_Table::getDefaultAdapter();
			$sql = "
				SELECT *,loaihs.CODE as MALOAI FROM htdb_services 
				LEFT JOIN motcua_loai_hoso loaihs on htdb_services.id_loaihoso=loaihs.id_loaihoso
				where htdb_services.CODE = ?
			";
			
			$stm = $db->query(
				$sql, array ($code) 	
			);
			$data = $stm->fetch();
		
			//convert sang xml
			$xslt = new XSLTProcessor;
			
			
			$xsdlto = new DOMDocument;
			$xsdlt = $data["CONVERT_TEMPLATE"];
			//echo $xsdlt;
			
			if($xsdlt){
				$xsdlto->loadXML($xsdlt);
				$xslt->importStyleSheet($xsdlto);
				$xml = new DOMDocument;
				$xml->loadXML($xmlsource);
				//$this->view->xsdlt .= ($xslt->transformToXML($xml));
				$xmldestination = ($xslt->transformToXML($xml));
				//chuyen xml thanh sql, mang sql
				$arraysql  = xmltodb::buildQuery($code,$xmldestination);
				//var_dump($arraysql);
				foreach($arraysql as $sqld){
					try{
						//$stm =$db->query($sqld["SQL"],$sqld["PARAMS"]);
						//$stm = $db->prepare($sqld["SQL"]);
						$db->query($sqld["SQL"],$sqld["PARAMS"]);
					}catch(Exception $ex){
						echo $ex->__toString();
					}
				}
				$sql = "UPDATE dvc_motcua_hoso_web SET MALOAIHOSO=? WHERE MAHOSO=?";
				$db->query(
					$sql, array ($data['MALOAI'],$mahoso) 	
				);
			}
			return $xmldestination;

		}
		//Ham build sql query
		function buildQuery($code,$xml){
		//print_r(htmlspecialchars($xml));
		try{
			$db = Zend_Db_Table::getDefaultAdapter();
			$doc = new DOMDocument();
			$doc->loadXML($xml);
			
			//kiem tra da ton tai chua, neu ton tai thi xoa
			//lay ten tble root
			$sql = " SELECT TABLENAME,NAME from htdb_xmltodbitems
					where SERVICECODE = ? and COLUMNNAME is NULL";
			$stm = $db->query($sql,array($code));
			$rows = $stm->fetchAll();
			foreach($rows as $row){
				$table_r = $row["TABLENAME"];
				$name_r = $row["NAME"];
				//lay ten cot identity
				$sql = " SELECT COLUMNNAME,NAME from htdb_xmltodbitems
						where SERVICECODE = ? and IS_IDENTITY = 1 and TABLENAME = ? ";
				$stm = $db->query($sql,array($code,$table_r));
				$row = $stm->fetch();
				$coli = $row["COLUMNNAME"];
				$name_coli = $row["NAME"];
				$noder = $doc->getElementsByTagName('hoso');
				$noder = $noder->item(0);
				$nodelc = $noder->childNodes;
				$valuei = "";
				foreach($nodelc as $n){
					//lay du lieu can lay 
					if($n->nodeName == $name_coli ){
						$valuei = base64_decode($n->nodeValue);
						break;
					}
				}

				$sql = "
					delete from $table_r where $coli = ?
				";
				if($coli)
				{
					$stm = $db->prepare($sql);
					$stm->execute(array($valuei));
				}
				//echo $sql;
			}
			$sql = "
				select TABLENAME, GROUP_CONCAT(COLUMNNAME) as COLUMNLIST , GROUP_CONCAT(NAME) AS NODENAMES   from htdb_xmltodbitems
				where SERVICECODE = ?
				group by TABLENAME 
			";
			$stm = $db->query($sql,array($code));
			$data = $stm->fetchAll();
			//NODENAMES
			$arr_result = array();
			foreach($data as $item){
				$arr_listcol = explode(",",$item["COLUMNLIST"]);
				//var_dump($arr_listcol);
				//array_shift( $arr_listcol);
				$arr_namenode = explode(",",$item["NODENAMES"]);
				$nameparent = array_shift($arr_namenode);
				$children = $doc->getElementsByTagName($nameparent);
				$len = $children->length;
				for($i=0; $i < $len; $i++){
					$node = $children->item($i);
					$valuenodes = $node->childNodes;
					$arr_values = array();
					$listcol_insert = array();
					$index_co = 0;
					foreach($arr_namenode as $namenode){		
						foreach($valuenodes as $n){
							if($n->nodeName == $namenode){
								if($n->nodeName == "MAHOSO")
								{
								$arr_values[] = base64_decode($n->nodeValue);
								}
								else
								{
								$arr_values[] = base64_decode($n->nodeValue);
								}
								$listcol_insert[] = "`".$arr_listcol[$index_co]."`";
								break;
							}
						}
						$index_co++;
						
					}
					$sql_build = "
					insert into ".$item["TABLENAME"]."
					(
						". implode( "," , $listcol_insert) ."
					)
					values(
						".xmltodb::printStringQuestionmask(count($listcol_insert))."		
					)
					";
					
					$arr_qr = array();
					$arr_qr["SQL"] = $sql_build;
					$arr_qr["PARAMS"] = $arr_values;
					$arr_result[] = $arr_qr;	
				}
			}
			
			return $arr_result;
		}catch(Exception $ex){
			echo $ex->__toString();
		}


		}

		function  printstringQuestionmask($num){
			$strq = "";
			for($i=0;$i<$num;$i++){
				
				if($i == ($num-1 ) )
					$strq .= "?";
				else
					$strq .= " ?, ";
			}
			return $strq;
		}

		
		
		//cap nhat xsdlt trong co so du lieu
		function updatexsdlt($servicecode){
			try{
				$db = Zend_Db_Table::getDefaultAdapter();
				$sql = "
					select ID_SERVICE from htdb_services where CODE = ?
				";
				$stm = $db->query($sql,array($servicecode));
				$re = $stm->fetch();
				$xmltemplate;
				xmltodb::dbtoxml(&$xmltemplate,$servicecode,0);
				//var_dump($xmltemplate);
				$sql="
					UPDATE htdb_services set
					DESTINATIONDES_XML = ?
					WHERE
					ID_SERVICE= ?";
				$stm = $db->query($sql,array($xmltemplate,(int)$re["ID_SERVICE"]));	
			}catch(Exception $ex){
				echo $ex->__toString();
			}
		
		}
		


}