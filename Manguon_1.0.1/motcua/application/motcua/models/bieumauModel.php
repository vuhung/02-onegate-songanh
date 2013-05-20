<?php
/**
 * LoaisModel
 *  
 * @author truongvc
 * @version 1.0
 */
class bieumauModel extends Zend_Db_Table
{
    protected $_name = 'motcua_bieumau';
    public $_id_p = 0;
	public $_id_thutuc;
	/**
     * Count all items in motcua_loai_hoso table
     * @return integer
     */
	public function Count()
	{
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";	
		if($this->_id_thutuc > 0){
            $arrwhere[] = $this->_id_thutuc;
            $strwhere .= " and motcua_thutuc_canco.ID_THUTUC = ?";
        }	
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and motcua_bieumau.TEN_BIEUMAU like ?";
		}
		
		//Thực hiện query
		$result = $this->getDefaultAdapter()->query("
			SELECT
				count(*) as C
			FROM
				$this->_name
			LEFT JOIN motcua_thutuc_canco	ON motcua_thutuc_canco.ID_THUTUC=motcua_bieumau.ID_THUTUC
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
		if($this->_id_thutuc > 0){
            $arrwhere[] = $this->_id_thutuc;
            $strwhere .= " and motcua_thutuc_canco.ID_THUTUC = ?";
        }
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and (motcua_bieumau.TEN_BIEUMAU like ?) ";
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
			SELECT motcua_bieumau.*,motcua_thutuc_canco.TENTHUTUC AS LOAI
			FROM motcua_bieumau
			LEFT JOIN motcua_thutuc_canco ON motcua_thutuc_canco.ID_THUTUC=motcua_bieumau.ID_THUTUC
			WHERE
				$strwhere
			$strorder
			$strlimit
		",$arrwhere);
		return $result->fetchAll();
	}
	public function getFileBy($id)
	{
		try
		{
			//Thực hiện query
			$result = $this->getDefaultAdapter()->query("
				SELECT motcua_bieumau.*
				FROM motcua_bieumau
				WHERE
					ID_BIEUMAU=?				
				",array($id));
			$data=$result->fetchAll();
			if(count($data)>0)
			{
				return $data[0];
			}
			else
			{
				return null;
			}
		}
		catch(Exception $e)
		{
			return null;
		}
	}
	
	
}