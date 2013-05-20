<?php

class Qllt_muontraModel extends Zend_Db_Table
{
    protected $_name = 'qllt_muontra';
    public $_id_p = 0;
	/**
     * Count all items in QTHT_DEPARTMENTS table
     * @return integer
     */
	public function Count()
	{
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and qllt_muontra.TENHOSO like ?";
		}
		
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT
				count(*) as C
			FROM
				$this->_name
			WHERE
				$strwhere
		",$arrwhere)->fetch();
		return $result["C"];
	}

	public function SelectById($id_hoso)
	{		
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
		SELECT

		  MT.ID_MUONTRA,
		  MT.ID_HOSO,
		  MT.NGAY_MUON,
		  MT.NGAY_TRA,
		  MT.NGAYTRA_THUCTE,
		  Q1.FIRSTNAME AS F_NGUOIMUON,
		  Q1.LASTNAME  AS L_NGUOIMUON,
		  Q2.FIRSTNAME AS F_NGUOICHOMUON,
		  Q2.LASTNAME  AS L_NGUOICHOMUON,
		  Q3.FIRSTNAME AS F_NGUOITRA,
		  Q3.LASTNAME  AS L_NGUOITRA

		FROM
		  qllt_muontra MT
		  LEFT OUTER JOIN `qtht_users` QU ON (MT.ID_U_MUON = QU.ID_U)
		  LEFT OUTER JOIN `qtht_employees` Q1 ON (QU.ID_EMP = Q1.ID_EMP)

		  LEFT OUTER JOIN `qtht_users` QS ON (MT.ID_U_CHOMUON = QS.ID_U)
		  LEFT OUTER JOIN `qtht_employees` Q2 ON (QS.ID_EMP = Q2.ID_EMP)
		  
		  LEFT OUTER JOIN `qtht_users` QD ON (MT.ID_U_TRA = QD.ID_U)
		  LEFT OUTER JOIN `qtht_employees` Q3 ON (QD.ID_EMP = Q3.ID_EMP)
		  
		  WHERE
		  
		  MT.`ID_HOSO` = $id_hoso ORDER BY MT.NGAY_MUON
		")->fetchAll();
		return $result;		
	}
	public function getNgayTra_Cuoicung($id_hoso)
	{
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			select max(NGAYTRA_THUCTE) as MAXDATE from qllt_muontra where ID_HOSO = $id_hoso
		")->fetch();
		return $result['MAXDATE'];
	}
	
	public function getNgayMuon($id_muontra)
	{
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT NGAY_MUON as C FROM qllt_muontra WHERE ID_MUONTRA = $id_muontra
		")->fetch();
		return $result['C'];
	}

	public function getFileView($id_hoso)
	{
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			select SOLANMUON as C from qllt_hoso where ID_HOSO = $id_hoso
		")->fetch();
		return $result['C'];	

	}
		 
}