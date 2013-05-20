<?php

require_once ('Zend/Db/Table/Abstract.php');

class ChuDeModel extends Zend_Db_Table_Abstract 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'td_chude';
	protected $_year='2009';
    function getName()
    {
    	return $this->_name;
    }
    function setYear($year)
    {
    	$this->_year=$year;
    }
    function getYear()
    {
    	return $this->_year;
    }
	function __construct($year=null)
	{
    	if($year!=null)
    	{
    		if((int)$year>2000 && (int)$year<3000)
    		{
    			$this->_name='td_chude_'.$year;
    			$this->setYear($year);
    		}
    		else QLVBDHCommon::getYear();
    	}
    	$arr = array();
		parent::__construct($arr);
	}
	/**
     * Count all items in td_chude table
     * @return integer
     */
	public function Count()
	{
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and td_chude.ten_chude like ?";
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
			$strwhere .= " and td_chude.ten_chude like ?";
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
			SELECT *
			FROM td_chude			
			WHERE
				$strwhere
			$strorder
			$strlimit
		",$arrwhere);
		return $result->fetchAll();
	}
		
}

?>
