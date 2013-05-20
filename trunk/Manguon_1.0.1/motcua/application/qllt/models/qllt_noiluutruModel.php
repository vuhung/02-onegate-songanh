<?php

class Qllt_noiluutruModel extends Zend_Db_Table
{
	
	var $_name = 'qllt_noiluutru';
	
	static function GetTree_noiluutru($id_parent,$name_tree,&$html,$sel,$onlick){                        
        $ArrLoai = array(0=>'Chưa phân loại',1=>'Kho',2=>'Kệ',3=>'Tầng',4=>'Ngăn',5=>'Hộp');
		$isFirst = false;
		$data = array();
		$forbiddenid = $sel;
		QLVBDHCommon::GetTreeWithWhere(&$data,"qllt_noiluutru","ID_NOILUUTRU","ID_THUMUCCHA",1,1,"LOAI<>5");
		//var_dump($data);
        foreach($data as $row){        	
			if($row["ID_THUMUCCHA"]==$id_parent){
        		if(!$isFirst){
        			$isFirst=true;
        			if($id_parent==0)
                        $html .= "<ul ref='open' id=" . $name_tree . " class=treeview>";
                            else
                                $html .= "<ul ref='open'>";
        		}
				if($sel != $row["ID_THUMUCCHA"])
				{						
						//			  id = 'ID_NOILUUTRU~TENTHUMUC'
						
						
							if($row['ID_NOILUUTRU']!=$sel){
								$html .= '<li id="'.$row["ID_NOILUUTRU"].'~'.$ArrLoai[$row["LOAI"]].' '.$row["TENTHUMUC"].'" onclick="'.$onlick.'">';
								$html .= '<a href="#" id="'.$row["ID_NOILUUTRU"].'~'.$ArrLoai[$row["LOAI"]].' '.$row["TENTHUMUC"].'" onclick="'.$onlick.';setCombobox('.$row["LOAI"].')">'.$ArrLoai[$row["LOAI"]].' '.$row['TENTHUMUC'].'</a>';
							}else{		
								$html .= '<li>';
								$html .= '<a href="#" ><b>'.$ArrLoai[$row["LOAI"]].' '.$row['TENTHUMUC'].'</b></a>';
								
							}
					   
						Qllt_noiluutruModel::GetTree_noiluutru($row["ID_NOILUUTRU"],$name_tree,&$html,$sel,$onlick);
						$html .= "</li>";
				}
        	}
        }
        if($isFirst)
            $html .= "</ul><script> var id_thumucchon = ".(Int)$sel." </script>"; 
        return $html;
    }
	static function GetTree_noiluutru_nohop($id_parent,$name_tree,&$html,$sel,$onlick){                        
        $ArrLoai = array(0=>'Chưa phân loại',1=>'Kho',2=>'Kệ',3=>'Tầng',4=>'Ngăn',5=>'Hộp');
		$isFirst = false;
		$data = array();
		$forbiddenid = $sel;
		QLVBDHCommon::GetTreeWithWhere(&$data,"qllt_noiluutru","ID_NOILUUTRU","ID_THUMUCCHA",1,1,"LOAI<>5");
		//var_dump($data);
        foreach($data as $row){        	
			if($row["ID_THUMUCCHA"]==$id_parent){
        		if(!$isFirst){
        			$isFirst=true;
        			if($id_parent==0)
                        $html .= "<ul ref='open' id=" . $name_tree . " class=treeview>";
                            else
                                $html .= "<ul ref='open'>";
        		}
				if($sel != $row["ID_THUMUCCHA"])
				{						
						//			  id = 'ID_NOILUUTRU~TENTHUMUC'
						
						
							if($row['ID_NOILUUTRU']!=$sel){
								$html .= '<li id="'.$row["ID_NOILUUTRU"].'~'.$ArrLoai[$row["LOAI"]].' '.$row["TENTHUMUC"].'" onclick="'.$onlick.'">';
								$html .= '<a href="#" id="'.$row["ID_NOILUUTRU"].'~'.$ArrLoai[$row["LOAI"]].' '.$row["TENTHUMUC"].'" onclick="'.$onlick.';setCombobox('.$row["LOAI"].')">'.$ArrLoai[$row["LOAI"]].' '.$row['TENTHUMUC'].'</a>';
							}else{		
								$html .= '<li>';
								$html .= '<a href="#" ><b>'.$ArrLoai[$row["LOAI"]].' '.$row['TENTHUMUC'].'</b></a>';
								
							}
					   
						Qllt_noiluutruModel::GetTree_noiluutru_nohop($row["ID_NOILUUTRU"],$name_tree,&$html,$sel,$onlick);
						$html .= "</li>";
				}
        	}
        }
        if($isFirst)
            $html .= "</ul><script> var id_thumucchon = ".(Int)$sel." </script>"; 
        return $html;
    }

	static function GetTree_storage($id_parent,$name_tree,&$html,$sel,$onlick){                        
       $isFirst = false;
	   $ArrLoai = array(0=>'',1=>'Kho',2=>'Kệ',3=>'Tầng',4=>'Ngăn',5=>'Hộp');
		$data = array();
		QLVBDHCommon::GetTreeWithWhere(&$data,"qllt_noiluutru","ID_NOILUUTRU","ID_THUMUCCHA",1,1,"LOAI<>5");
        foreach($data as $row){        	
			if($row["ID_THUMUCCHA"]==$id_parent){
        		if(!$isFirst){
        			$isFirst=true;
        			if($id_parent==0)
                        $html .= "<ul ref='open' id=" . $name_tree . " class=treeview>";
                            else
                                $html .= "<ul ref='open'>";
        		}
				//			  id = 'ID_NOILUUTRU~TENTHUMUC'
                $html .= '<li id="'.$row["ID_NOILUUTRU"].'~'.$ArrLoai[$row["LOAI"]].' '.$row["TENTHUMUC"].'" onclick="'.$onlick.'">';
                
                	if($row['ID_NOILUUTRU']!=$sel){
                		$html .= '<a href="#" id="'.$row["ID_NOILUUTRU"].'~'.$ArrLoai[$row["LOAI"]].' '.$row["TENTHUMUC"].'" onclick="'.$onlick.'">'.$ArrLoai[$row["LOAI"]].' '.$row['TENTHUMUC'].'</a>';
                	}else{
                		$html .= '<a href="#" id="'.$row["ID_NOILUUTRU"].'~'.$ArrLoai[$row["LOAI"]].' '.$row["TENTHUMUC"].'" onclick="'.$onlick.'"><b>'.$ArrLoai[$row["LOAI"]].' '.$row['TENTHUMUC'].'</b></a>';
						
                	}
               
        	    Qllt_noiluutruModel::GetTree_storage($row["ID_NOILUUTRU"],$name_tree,&$html,$sel,$onlick);
        	    $html .= "</li>";
        	}
        }
        if($isFirst)
            $html .= "</ul><script> var id_thumucchon = ".(Int)$sel." </script>"; 
        return $html;
    }

	public function CountSubFolder($id)
	{
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT
				count(*) as C
			FROM
				$this->_name
			WHERE ID_THUMUCCHA = $id
		")->fetch();
		return $result["C"];
	}

	public function CountFolder()
	{
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT
				count(*) as C
			FROM
				$this->_name			
		")->fetch();
		return $result["C"];
	}

	public function getThuocKho($idthumuccha)
	{
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT
				THUOCKHO AS C
			FROM
				$this->_name
			WHERE ID_NOILUUTRU = $idthumuccha
		")->fetch();		
		return $result['C'];
		
	}


	public function getFolderInfo($id_noiluutru)
	{
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			select main.TENTHUMUC,
				main.ID_THUMUCCHA,
				main.ACTIVE,
				main.GHICHU,
				main.LOAI,
				sub.TENTHUMUC as TENTHUMUCCHA,
				sub.LOAI as LOAITHUMUCCHA
			 FROM qllt_noiluutru main
				  inner join `qllt_noiluutru` sub on main.`ID_THUMUCCHA` =
				  sub.`ID_NOILUUTRU`
			 where main.ID_NOILUUTRU = $id_noiluutru
		")->fetch();
		return $result;		
	}


}