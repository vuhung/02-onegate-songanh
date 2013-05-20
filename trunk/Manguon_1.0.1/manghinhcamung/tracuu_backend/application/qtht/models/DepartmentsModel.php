<?php

class DepartmentsModel extends Zend_Db_Table
{
    protected $_name = 'qtht_departments';
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
			$strwhere .= " and QTHT_DEPARTMENTS.NAME like ?";
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
	/**
     * Get all items in QTHT_MENUS table with pairs : ID_MNU and NAME
     * @return array
     */
	function GetAllDeps()
	{
		$r = $this->getDefaultAdapter()->query("
			SELECT
     			ID_DEP,NAME
			FROM
				QTHT_DEPARTMENTS");
		return $r->fetchAll();
	}
	
	static function getAll()
	{
		$r = Zend_Db_Table::getDefaultAdapter()->query("
			SELECT
     			*
			FROM
				QTHT_DEPARTMENTS
			WHERE ID_DEP != 1
				");
		return $r->fetchAll();
	}
	
	public function checkExistDep($tableSel,$idDep)
	{
		$result= $tableSel->fetchRow('ID_DEP='.$idDep,'ID_DEP ASC');
		$returnValue = $result->ID_DEP;			
		if($returnValue!=null)
		{
			return false;
		}
		else 
		{
			return true;
		}
			
	}
	function checkExistNameDep($tableSel,$NameDep)
	{
		$result= $tableSel->fetchRow('NAME="'.$NameDep.'"','ID_DEP ASC');
		$returnValue = $result->NAME;			
		if($returnValue!=null)
		{
			return false;
		}
		else 
		{
			return true;
		}
			
	}
/**
     * Select all from $offset to $limit with $order arrange
     *
     * @param  integer $offset
     * @param  integer $limit
     * @param  String $order
     * @return boolean
     */
	public function SelectAll($offset,$limit,$order){
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and NAME like ?";
		}
		$strwhere .= " and QTHT_DEPARTMENTS.ID_DEP <> 1"; 
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
	 * trunglv : Đưa dữ liệu phòng ban vào combobox
	 * 
	 */
	public static function toComboName(){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "select `CODE_DEP`,`NAME`,`ID_DEP` from `qtht_departments` 
		where `ID_DEP` !=1  ORDER BY NAME 
		";
		$query = $dbAdapter->query($sql);
		$re = $query->fetchAll();
		foreach ($re as $row){
			echo "<option id='".$row['CODE_DEP']."' value=".$row['ID_DEP'].">".$row['NAME']."</option>";
		}
	}
	
	static function getNameById($id){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "select `NAME` from `qtht_departments` 
		where `ID_DEP` = ?
		";
		$query = $dbAdapter->query($sql,array($id));
		$re = $query->fetch();
		return $re["NAME"];
	}
	
}