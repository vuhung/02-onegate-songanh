<?php

	class motcua_taptin_web extends Zend_Db_Table_Abstract {
		//var $_name = 'dvc_motcua_taptin_web';
		function __construct(){
			$this->_name = "dvc_motcua_taptin_web";
			parent::__construct(array());
		}
		function selectById($id){
			$sql = "
				select * from  dvc_motcua_taptin_web 
				where ID_SERVICE = ?
			";
			$db = Zend_Db_Table::getDefaultAdapter();
			$qr = $db->query($sql,(int)$id);
			return $qr->fetch();

		}
		function selectAll(){
			$sql = "
				select * from dvc_motcua_taptin_web
			";
			$db = Zend_Db_Table::getDefaultAdapter();
			$qr = $db->query($sql,(int)$id);
			return $qr->fetchAll();
		}
		function selectAllForList(){
			$sql = "
				select ser.ID_SERVICE , ser.TENDICHVU, ser.CODE , ser.ID_LOAIHOSO, loai.TENLOAI
				
				from htdb_services ser
				inner join motcua_loai_hoso loai on ser.ID_LOAIHOSO = loai.ID_LOAIHOSO
			";
			$db = Zend_Db_Table::getDefaultAdapter();
			$qr = $db->query($sql,(int)$id);
			return $qr->fetchAll();
		}
		function getFileFromAdapter($wsdl,$username,$password,$fpath,$dvcFolder,$role)
		{
			$cliente = new SoapClient($wsdl);
			$role = "Qlvbdh@f1";
		    $string = $cliente->__call('getAllFiles',array($username, $password,$role
			));		
			if($string != "-2")
			{		
				$arrData = $this->DeseriallizeToArray($string);
				$fileData = "";
				$fileName = "";
				$idFile = "";
				if(count($arrData) > 0)
				{	
					foreach($arrData as $row)
					{
						$fileData .= base64_decode($row["CONTENT"]);
						$fileName = base64_decode($row["NAME"]);
						$idFile = $row["ID_FILE"];
						$filenameMD5 = md5($fileName.microtime());
						$fileName = $fpath."\\".$dvcFolder."\\".$idFile.'-'.$filenameMD5;
						$data = array(
						"MAHOSO" =>$row["MAHOSO"],						
						"ID_PART" =>$row["ID_PART"],
						"PART_NUMBER" =>$row["PART_NUMBER"],
						"FILENAME" =>base64_decode($row["NAME"]),
						"MIME" =>$row["MIME"],
						"SIZE" =>$row["SIZE"],
						"MASO" => $idFile.'-'.$filenameMD5,
						"SEND_DATE" =>$row["SEND_DATE"],
						"LINK" =>$fpath."\\".$dvcFolder
						);
					}
					$db = Zend_Db_Table::getDefaultAdapter();				
					$db->insert("dvc_motcua_taptin_web",$data);
					//$lsID = $db->lastInsertId();
					
				}
				if($fileData != "")
				{				
				$this->writeFile($fileName,$fileData);
				}				
				$this->getFileFromAdapter($wsdl,$username,$password,$fpath,$dvcFolder,$role);
			}
		}
		
		public static function DeseriallizeToArray($string){
			$r = array();
			$r = explode("~",$string);
			for($i=0;$i<count($r);$i++){
				$r[$i] = str_replace("&split;","~",$r[$i]);
				$r[$i] = str_replace("&amp;","&",$r[$i]);
			}
			$array_result = array();
			$colnum = $r[0]; //so cot
			$incol = 1;
			$countr = count($r);

			$num_object = (int)((int)$countr/(int)$colnum);

			for($i =1 ; $i < $num_object ; $i ++ ){
				$temp = array();
				for($j = 0; $j < $colnum ; $j++){
					 $temp["".$r[$j+1].""] = $r[$i*$colnum+$j+1];
				}
				$array_result[$i-1] = $temp;
			}
			return $array_result;
		}
		function writeFile($fname, $string) {
			$fp = fopen($fname, 'w');
			fwrite($fp, $string);
			fclose($fp);
		}
	}
