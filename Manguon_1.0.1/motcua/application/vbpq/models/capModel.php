<?php
/**
 * capModel
 *  
 * @author truongvc
 * @version 1.0
 */
class capModel extends Zend_Db_Table
{
    protected $_name = 'vbpq_cap';
    /**
     * Count all items in motcua_loai_hoso table
     * @return integer
     */
	public function Count()
	{
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and vbpq_cap.TEN_CAP like ?";
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
	public function SelectAll($offset,$limit,$order)
	{
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and (vbpq_cap.TEN_CAP like ?) ";
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
			SELECT vbpq_cap.*
			FROM $this->_name	
			WHERE
				$strwhere
			$strorder
			$strlimit
		",$arrwhere);
		return $result->fetchAll();
	}
	public function getAllCap()
	{
		try
		{
			$result = $this->getDefaultAdapter ()->query ("
			SELECT
 				*
			FROM
				$this->_name
			");
			return $result->fetchAll();
		}
		catch (Zend_Exception  $ex)
		{

		}
		return null;
	}	
}