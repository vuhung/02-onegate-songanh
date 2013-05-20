<?php
/**
 * ketquadauraModel
 *  
 * @author truongvc
 * @version 1.0
 */
class ketquadauraModel extends Zend_Db_Table
{
    protected $_name = 'motcua_ketqua_daura';
    /**
     * Count all items in motcua_ketqua_daura table
     * @return integer
     */
	public function Count()
	{
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";			
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and motcua_ketqua_daura.TENKETQUA like ?";
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
     * Select all menus from $offset to $limit with $order arrange
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
			$strwhere .= " and (motcua_ketqua_daura.TENKETQUA like ?) ";
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
			SELECT motcua_ketqua_daura.*
			FROM motcua_ketqua_daura				
			WHERE
				$strwhere
			$strorder
			$strlimit
		",$arrwhere);
		return $result->fetchAll();
	}
	/*
	*/
	public function getAllKetQuas()
	{
		try
		{
			$result=$this->getDefaultAdapter()->query("
			SELECT *
			FROM motcua_ketqua_daura
			WHERE ACTIVE=1				
			");
			return $result->fetchAll();
		}
		catch(Exception $e)
		{}
	}

	
	
}