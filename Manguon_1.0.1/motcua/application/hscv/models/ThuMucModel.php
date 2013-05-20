<?php

/**
 * ClassModel
 *  
 * @author hieuvt
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';
require_once ('Zend/Db/Table.php');
require_once 'motcua/models/linhvucmotcuaModel.php';

class ThuMucModel extends Zend_Db_Table_Abstract {
    /**
     * The default table name 
     */
    var $_name = 'hscv_thumuc';
    var $_search = "";
    var $_id = 0;
    /**
     * Select toàn bộ dữ liệu
     */

    public function SelectAll($offset,$limit,$order){
        //Build phần where
        $arrwhere = array();
        $strwhere = "(1=1)";
        if($this->_search != ""){
            $arrwhere[] = "%".$this->_search."%";
            $strwhere .= " and NAME like ?";	
        }
        //Build phần limit
        $strlimit = "";
        if($limit>0){
            $strlimit = " LIMIT $offset,$limit";
        }
        //Build order
        $strorder = "";
        if($order>0){
            $strorder = " ORDER BY $order";
        }
        //Thực hiện query
        $result = $this->getDefaultAdapter()->query("
            SELECT
                *
            FROM
                $this->_name
            WHERE
                $strwhere
            $strorder
            $strlimit
        ",$arrwhere);
        return $result->fetchAll();
    }
    
    /**
     * Chuyển dữ liệu tới combobox
     */
    static function ToTree($data,$id_parent,$name_tree,&$html,$sel){                        
        $isFirst = false;
        
        foreach($data as $row){
        	if($row["ID_THUMUC_CHA"]==$id_parent){
        		if(!$isFirst){
        			$isFirst=true;
        			if($id_parent==0)
                        $html .= "<ul ref='open' id=" . $name_tree . " class=treeview>";
                            else
                                $html .= "<ul ref='open'>";
        		}
                $html .= "<li>";
                if($row["PER"]){
                	if($row['ID_THUMUC']!=$sel){
                		$html .= "<a href='/hscv/hscv/list/code/zip/id_thumuc/".$row["ID_THUMUC"]."'>".$row["NAME"]."</a>";
                	}else{
                		$html .= "<a href='/hscv/hscv/list/code/zip/id_thumuc/".$row["ID_THUMUC"]."'><b>".$row["NAME"]."</b></a>";
                	}
                }else{
                	$html .= $row["NAME"];
                }
        	    ThuMucModel::ToTree($data,$row["ID_THUMUC"],$name_tree,&$html,$sel);
        	    $html .= "</li>";
        	}
        }
        if($isFirst)
            $html .= "</ul>";
        return $html;
    }

	public function getAllThuMuc ()
	{
		$user = Zend_Registry::get('auth')->getIdentity();
					$dbAdapter = Zend_Db_Table::getDefaultAdapter();
					$sql="select tm.* from hscv_thumuc tm 
							inner join motcua_linhvuc mclv on
							tm.NAME=mclv.NAME
							inner join motcua_linhvuc_quyen mclvq
							on mclv.ID_LV_MC=mclvq.ID_LV_MC where mclvq.ID_U='".$user->ID_U."'";
					$tm=$dbAdapter->query($sql);
					return $tm->fetchAll();
	}

    function UpdateGroup($xem,$themmoi,$phanquyen){
    	$this->getDefaultAdapter()->delete("HSCV_PHANQUYEN_THUMUC","ID_THUMUC = ".$this->_id." AND ID_G > 0");
    	if(is_array($xem)){
    		foreach($xem as $q){
    			$this->getDefaultAdapter()->insert("HSCV_PHANQUYEN_THUMUC",array("ID_THUMUC"=>$this->_id,"ID_G"=>$q,"QUYENXEM"=>1));
    		}
    	}
    	if(is_array($themmoi)){
    		foreach($themmoi as $q){
    			$this->getDefaultAdapter()->insert("HSCV_PHANQUYEN_THUMUC",array("ID_THUMUC"=>$this->_id,"ID_G"=>$q,"QUYENTHEMMOI"=>1));
    		}
    	}
    	if(is_array($phanquyen)){
    		foreach($phanquyen as $q){
    			$this->getDefaultAdapter()->insert("HSCV_PHANQUYEN_THUMUC",array("ID_THUMUC"=>$this->_id,"ID_G"=>$q,"QUYENPHANQUYEN"=>1));
    		}
    	}
    }
	function UpdateDep($xem,$themmoi,$phanquyen){
    	$this->getDefaultAdapter()->delete("HSCV_PHANQUYEN_THUMUC","ID_THUMUC = ".$this->_id." AND ID_DEP > 0");
    	if(is_array($xem)){
    		foreach($xem as $q){
    			$this->getDefaultAdapter()->insert("HSCV_PHANQUYEN_THUMUC",array("ID_THUMUC"=>$this->_id,"ID_DEP"=>$q,"QUYENXEM"=>1));
    		}
    	}
    	if(is_array($themmoi)){
    		foreach($themmoi as $q){
    			$this->getDefaultAdapter()->insert("HSCV_PHANQUYEN_THUMUC",array("ID_THUMUC"=>$this->_id,"ID_DEP"=>$q,"QUYENTHEMMOI"=>1));
    		}
    	}
    	if(is_array($phanquyen)){
    		foreach($phanquyen as $q){
    			$this->getDefaultAdapter()->insert("HSCV_PHANQUYEN_THUMUC",array("ID_THUMUC"=>$this->_id,"ID_DEP"=>$q,"QUYENPHANQUYEN"=>1));
    		}
    	}
    }
	function UpdateUser($xem,$themmoi,$phanquyen){
    	$this->getDefaultAdapter()->delete("HSCV_PHANQUYEN_THUMUC","ID_THUMUC = ".$this->_id." AND ID_U > 0");
    	if(is_array($xem)){
    		foreach($xem as $q){
    			$this->getDefaultAdapter()->insert("HSCV_PHANQUYEN_THUMUC",array("ID_THUMUC"=>$this->_id,"ID_U"=>$q,"QUYENXEM"=>1));
    		}
    	}
    	if(is_array($themmoi)){
    		foreach($themmoi as $q){
    			$this->getDefaultAdapter()->insert("HSCV_PHANQUYEN_THUMUC",array("ID_THUMUC"=>$this->_id,"ID_U"=>$q,"QUYENTHEMMOI"=>1));
    		}
    	}
    	if(is_array($phanquyen)){
    		foreach($phanquyen as $q){
    			$this->getDefaultAdapter()->insert("HSCV_PHANQUYEN_THUMUC",array("ID_THUMUC"=>$this->_id,"ID_U"=>$q,"QUYENPHANQUYEN"=>1));
    		}
    	}
    }
    
	function GetAllGroup(){
		$r = $this->getDefaultAdapter()->query("
			SELECT
     			PT.SEL,G.`ID_G`,G.NAME,QUYENXEM,QUYENTHEMMOI,QUYENPHANQUYEN
			FROM
				`QTHT_GROUPS` G
				LEFT JOIN (
				     SELECT
				           ID_THUMUC,ID_G,max(QUYENXEM)  as QUYENXEM,max(QUYENTHEMMOI)  as QUYENTHEMMOI,max(QUYENPHANQUYEN)  as QUYENPHANQUYEN, 1 AS SEL
				     FROM
				         `HSCV_PHANQUYEN_THUMUC`
				     WHERE
				          ID_THUMUC=?
				     GROUP BY ID_THUMUC,ID_G
				     ) PT ON PT.`ID_G` = G.`ID_G`
			ORDER BY NAME
		",array($this->_id));
		return $r->fetchAll();
	}
	function GetAllDep(){
		$r = $this->getDefaultAdapter()->query("
			SELECT
     			PT.SEL,DEP.`ID_DEP`,DEP.NAME,QUYENXEM,QUYENTHEMMOI,QUYENPHANQUYEN
			FROM
				`QTHT_DEPARTMENTS` DEP
				LEFT JOIN (
				     SELECT
				           ID_THUMUC,ID_DEP,max(QUYENXEM)  as QUYENXEM,max(QUYENTHEMMOI)  as QUYENTHEMMOI,max(QUYENPHANQUYEN)  as QUYENPHANQUYEN, 1 AS SEL
				     FROM
				         `HSCV_PHANQUYEN_THUMUC`
				     WHERE
				          ID_THUMUC=?
				     GROUP BY ID_THUMUC,ID_DEP
				     ) PT ON PT.`ID_DEP` = DEP.`ID_DEP`
			ORDER BY ID_DEP_PARENT,NAME
		",array($this->_id));
		return $r->fetchAll();
	}
	function GetAllUser(){
		$r = $this->getDefaultAdapter()->query("
			SELECT
     			PT.SEL,U.`ID_U`,concat(EMP.FIRSTNAME,' ',EMP.LASTNAME) as NAME,QUYENXEM,QUYENTHEMMOI,QUYENPHANQUYEN
			FROM
				`QTHT_USERS` U
				INNER JOIN QTHT_EMPLOYEES EMP on U.ID_EMP = EMP.ID_EMP
				LEFT JOIN (
				     SELECT
				           ID_THUMUC,ID_U,max(QUYENXEM)  as QUYENXEM,max(QUYENTHEMMOI)  as QUYENTHEMMOI,max(QUYENPHANQUYEN)  as QUYENPHANQUYEN, 1 AS SEL
				     FROM
				         `HSCV_PHANQUYEN_THUMUC`
				     WHERE
				          ID_THUMUC=?
				   	 GROUP BY ID_THUMUC,ID_U
				     ) PT ON PT.`ID_U` = U.`ID_U`
			ORDER BY EMP.LASTNAME
		",array($this->_id));
		return $r->fetchAll();
	}
	static function GetUser($idthumuc){
		global $db;
		$r = $db->query("
			SELECT
     			PT.SEL,U.`ID_U`,concat(EMP.FIRSTNAME,' ',EMP.LASTNAME) as NAME,QUYENXEM,QUYENTHEMMOI,QUYENPHANQUYEN
			FROM
				`QTHT_USERS` U
				INNER JOIN QTHT_EMPLOYEES EMP on U.ID_EMP = EMP.ID_EMP
				INNER JOIN (
				     SELECT
				           ID_THUMUC,ID_U,max(QUYENXEM)  as QUYENXEM,max(QUYENTHEMMOI)  as QUYENTHEMMOI,max(QUYENPHANQUYEN)  as QUYENPHANQUYEN, 1 AS SEL
				     FROM
				         `HSCV_PHANQUYEN_THUMUC`
				     WHERE
				          ID_THUMUC=?
				   	 GROUP BY ID_THUMUC,ID_U
				     ) PT ON PT.`ID_U` = U.`ID_U`
			ORDER BY EMP.LASTNAME
		",array($idthumuc));
		return $r->fetchAll();
	}
	function GetGroup($idthumuc){
		global $db;
		$r = $db->query("
			SELECT
     			PT.SEL,G.`ID_G`,G.NAME,QUYENXEM,QUYENTHEMMOI,QUYENPHANQUYEN
			FROM
				`QTHT_GROUPS` G
				INNER JOIN (
				     SELECT
				           ID_THUMUC,ID_G,max(QUYENXEM)  as QUYENXEM,max(QUYENTHEMMOI)  as QUYENTHEMMOI,max(QUYENPHANQUYEN)  as QUYENPHANQUYEN, 1 AS SEL
				     FROM
				         `HSCV_PHANQUYEN_THUMUC`
				     WHERE
				          ID_THUMUC=?
				     GROUP BY ID_THUMUC,ID_G
				     ) PT ON PT.`ID_G` = G.`ID_G`
			ORDER BY NAME
		",array($idthumuc));
		return $r->fetchAll();
	}
	function GetDep($idthumuc){
		global $db;
		$r = $db->query("
			SELECT
     			PT.SEL,DEP.`ID_DEP`,DEP.NAME,QUYENXEM,QUYENTHEMMOI,QUYENPHANQUYEN
			FROM
				`QTHT_DEPARTMENTS` DEP
				INNER JOIN (
				     SELECT
				           ID_THUMUC,ID_DEP,max(QUYENXEM)  as QUYENXEM,max(QUYENTHEMMOI)  as QUYENTHEMMOI,max(QUYENPHANQUYEN)  as QUYENPHANQUYEN, 1 AS SEL
				     FROM
				         `HSCV_PHANQUYEN_THUMUC`
				     WHERE
				          ID_THUMUC=?
				     GROUP BY ID_THUMUC,ID_DEP
				     ) PT ON PT.`ID_DEP` = DEP.`ID_DEP`
			ORDER BY ID_DEP_PARENT,NAME
		",array($idthumuc));
		return $r->fetchAll();
	}
	static function GetTreeByPermission($idu){
		$tree = array();
		QLVBDHCommon::GetTreeNoChild(&$tree,"HSCV_THUMUC","ID_THUMUC","ID_THUMUC_CHA",1,1,0);
		$temp = array();
		for($pos=0;$pos<count($tree);$pos++){
			if(ThuMucModel::CheckVisible($idu,$tree,$pos)){
				$tree[$pos]["PER"] = ThuMucModel::CheckPermission($idu,$tree[$pos]['ID_THUMUC']);
				$temp[] = $tree[$pos];
			}
		}
		$tree = $temp;
		//var_dump($tree);
		return $tree;
	}
	static function CheckVisible($idu,$data,$pos){
		$curlevel = $data[$pos]['LEVEL'];
		$curper = ThuMucModel::CheckPermission($idu,$data[$pos]['ID_THUMUC']);
		
		$pos++;
		if($pos>=count($data)){
			if(!$curper)return false;
			return true;
		}
		while($curlevel<$data[$pos]['LEVEL']){
			if($curper)return true;
			
			$curper = ThuMucModel::CheckPermission($idu,$data[$pos]['ID_THUMUC']);
			if($pos>=count($data)){
				if(!$curper)return false;
				return true;
			}
			$pos++;
		}
		if(!$curper)return false;
		return true;
	}
	static function CheckPermission($idu,$idthumuc){
		global $db;
		$r = $db->query("
			SELECT U.ID_U FROM
				QTHT_DEPARTMENTS DEP
				INNER JOIN QTHT_EMPLOYEES EMP ON EMP.ID_DEP = DEP.ID_DEP
				INNER JOIN QTHT_USERS U ON U.ID_EMP = EMP.ID_EMP
				INNER JOIN HSCV_PHANQUYEN_THUMUC TM ON DEP.ID_DEP = TM.ID_DEP
			WHERE U.ID_U = ? AND ID_THUMUC = ?
			UNION
			SELECT U.ID_U FROM
				FK_USERS_GROUPS G
				INNER JOIN QTHT_USERS U ON U.ID_U = G.ID_U
				INNER JOIN HSCV_PHANQUYEN_THUMUC TM ON G.ID_G = TM.ID_G
			WHERE U.ID_U = ? AND ID_THUMUC = ?
			UNION
			SELECT TM.ID_U FROM
				HSCV_PHANQUYEN_THUMUC TM
			WHERE TM.ID_U=? AND ID_THUMUC = ?
		",array($idu,$idthumuc,$idu,$idthumuc,$idu,$idthumuc));
		
		if($r->rowCount()==0){
			if($idthumuc>1){
				$r = $db->query("SELECT ID_THUMUC_CHA FROM HSCV_THUMUC WHERE ID_THUMUC = ?",$idthumuc);
				$row = $r->fetch();
				return ThuMucModel::CheckPermission($idu,$row['ID_THUMUC_CHA']);
			}else{
				return false;
			}
		}
		return true;
	}
}
