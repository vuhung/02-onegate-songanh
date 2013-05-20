<?php

class mapping {
	
	function printTabLevel($level){
		$str = "";
		for($i = 0; $i < $level;$i++)
			$str .= "----";
		return $str;
	}
	


	
	function arrayToRow2($data,&$strrow,$datades,$keyparent){
		$stt  = 0;
		foreach($data as  $item){
				$key = $item["name"];
				$level = $item["level"];
				//la node
				//if($item["name"]){
					$strrow .= "<tr>";
					$strrow .= "<td>";
					
					if(is_array($item["childs"])){
						$strrow .= "<font color=blue size=5>";
					}
					$strrow .= htmlspecialchars(mapping::printTabLevel($item["level"]).$item["name"])."</font>";
					if(is_array($item["childs"])){
						$strrow .= "</font>";
					}
					
					//$strrow .= htmlspecialchars(implode("--",$item));
					//var_dump($item);
					$strrow .= "</td>";
					$strrow .= "<td>";
					$stroption = "";
				$stroption = mapping::arrayToComOp($datades,&$stroption);
				$strrow .="<select name='sel_".$key.($keyparent?"#".$keyparent:"")."_$level"."'  id='sel_".$key.($keyparent?"#".$keyparent:"")."_$level"."'>
					<option value=0 >[Không dùng]</option>
				".$stroption."
				</select>";

					$strrow .= "</td>";
					
					$strrow .= "</tr>";
				//}else{
					///var_dump($item["childs"]);
					
					if(is_array($item["childs"])){
						mapping::arrayToRow2($item["childs"],&$strrow,$datades,$key);
					}
				//}
			
		}
		return $strrow;
	}
	
	
	function arrayToRow($data,&$strrow,$level,$datades,$keyparent){
		if(!$level) $level = 0;
		
		foreach($data as $key => $value ){
			
			$strrow .= "<tr>";
			$strrow .= "<td>";
			
			//if(!is_array($value))
			$strrow .= mapping::printTabLevel($level) .$key;
			$strrow .= "</td>";
			
			$strrow .= "<td>";
			if(is_array($value)){
				$strrow .= "MẢNG";
				
			}else{
				$stroption = "";
				$stroption = mapping::arrayToComOp($datades,&$stroption);
				$strrow .="<select name='sel_".$key.($keyparent?"#".$keyparent:"")."_$level"."'  id='sel_".$key.($keyparent?"#".$keyparent:"")."_$level"."'>
				".$stroption."
				</select>";
			}
			
			$strrow .= "</td>";
			$strrow .= "</tr>";	
			if(is_array($value)){
				$keyparent =  $key;
				mapping::arrayToRow($value,&$strrow,$level+1,$datades,$keyparent);
			}

		}
		
		return $strrow;
	}
	
	function arrayToComOp($data,&$strrow,$level,$keyparent){
		
		
		$stt  = 0;
		foreach($data as $item){
			
				$key = $item["name"];
				$level = $item["level"];
				
				$strrow .= "<option value='".$key. ($keyparent?("#".$keyparent):""  )     ."_".$level."'>";
				//$strrow .= mapping::printTabLevel($level)   . ($keyparent?($keyparent."-->"):"||"  ).    $key;
				$strrow .= ($keyparent?($keyparent."-->"):""  ).    $key;
				
				$strrow .= "</option>";
			
					
			if(is_array($item["childs"])){
						$keyp = $key; // ($keyparent? ($keyparent."-->"):"" ). $key;
						mapping::arrayToComOp($item["childs"],&$strrow,$level+1,$keyp);
			}
				
			
		}
		return $strrow;
		/*if(!$level) $level = 0;
		foreach($data as $key => $value ){
			if(!is_array($value)){
				$strrow .= "<option value='".$key. ($keyparent?("#".$keyparent):""  )     ."_".$level."'>";
				//$strrow .= mapping::printTabLevel($level)   . ($keyparent?($keyparent."-->"):"||"  ).    $key;
				$strrow .= ($keyparent?($keyparent."-->"):""  ).    $key;
				
				$strrow .= "</option>";
			}
			if(is_array($value)){
				$keyp = ($keyparent? ($keyparent."-->"):"" ). $key;
				mapping::arrayToComOp($value,&$strrow,$level+1,$keyp);
			}
		}
		
		return $strrow;*/
	}

	
	

	function selectMappingfieldInfo($id_service){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select * from htdb_mappingdefinition where ID_SERVICE = ?
		";
		$stm = $db->query($sql,array($id_service));
		$re = $stm->fetchAll();
		return $re;
	}


}