<?php
/**
 * vanbanModel
 *  
 * @author truongvc
 * @version 1.0
 */
class vanbanModel extends Zend_Db_Table
{
    protected $_name = 'vbpq_vanban';
    /**
     * Count all items in motcua_loai_hoso table
     * @return integer
     */
    //assign value for search action
	public $_tungay = "";
	public $_denngay = "";
	public $_sohieuvanban = "";
	public $_trichyeu = "";
	public $_linhvuc = "";
	public $_cap = "";
	public $_coquanbanhanh = "";
	public $_noidung = "";	
	public function Count()
	{
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_tungay != ""){
			$arrwhere[] = $this->_tungay;
			$strwhere .= " and vbpq_vanban.NGAYBANHANH >= ?";
		}
		if($this->_denngay != ""){
			$arrwhere[] = $this->_denngay;
			$strwhere .= " and vbpq_vanban.NGAYBANHANH <= ?";
		}
		if($this->_sohieuvanban != ""){
			$arrwhere[] = "%".$this->_sohieuvanban."%";
			$strwhere .= " and vbpq_vanban.SOKYHIEU like ?";
		}
		if($this->_trichyeu != ""){
			$arrwhere[] = "%".$this->_trichyeu."%";
			$strwhere .= " and vbpq_vanban.TRICHYEU like ?";
		}
		if($this->_linhvuc >0){
			$arrwhere[] = $this->_linhvuc;
			$strwhere .= " and vbpq_vanban.ID_LINHVUC = ?";
		}
		if($this->_cap >0){
			$arrwhere[] = $this->_cap;
			$strwhere .= " and vbpq_vanban.ID_CAP = ?";
		}
		if($this->_coquanbanhanh >0){
			$arrwhere[] = $this->_coquanbanhanh;
			$strwhere .= " and vbpq_vanban.ID_COQUANBANHANH = ?";
		}
		if($this->_noidung != ""){
			$arrwhere[] = "%".$this->_noidung."%";
			$strwhere .= " and vbpq_vanban.NOIDUNG like ?";
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
		if($this->_tungay != ""){
			$arrwhere[] = $this->_tungay;
			$strwhere .= " and vbpq_vanban.NGAYBANHANH >= ?";
		}
		if($this->_denngay != ""){
			$arrwhere[] = $this->_denngay;
			$strwhere .= " and vbpq_vanban.NGAYBANHANH <= ?";
		}
		if($this->_sohieuvanban != ""){
			$arrwhere[] = "%".$this->_sohieuvanban."%";
			$strwhere .= " and vbpq_vanban.SOKYHIEU like ?";
		}
		if($this->_trichyeu != ""){
			$arrwhere[] = "%".$this->_trichyeu."%";
			$strwhere .= " and vbpq_vanban.TRICHYEU like ?";
		}
		if($this->_linhvuc >0){
			$arrwhere[] = $this->_linhvuc;
			$strwhere .= " and vbpq_vanban.ID_LINHVUC = ?";
		}
		if($this->_cap >0){
			$arrwhere[] = $this->_cap;
			$strwhere .= " and vbpq_vanban.ID_CAP = ?";
		}
		if($this->_coquanbanhanh >0){
			$arrwhere[] = $this->_coquanbanhanh;
			$strwhere .= " and vbpq_vanban.ID_COQUANBANHANH = ?";
		}
		if($this->_noidung != ""){
			$arrwhere[] = "%".$this->_noidung."%";
			$strwhere .= " and vbpq_vanban.NOIDUNG like ?";
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
			SELECT vbpq_vanban.*,date_format(NGAYBANHANH, '%d/%m/%Y') as NGAYBANHANH,date_format(NGAYCOHIEULUC, '%d/%m/%Y') as NGAYCOHIEULUC,date_format(NGAYHETHIEULUC, '%d/%m/%Y') as NGAYHETHIEULUC,TEN_CAP,NAME
			FROM $this->_name	
			LEFT JOIN vbpq_cap ON vbpq_cap.ID_CAP =vbpq_vanban.ID_CAP
			LEFT JOIN vb_coquan ON vb_coquan.ID_CQ=vbpq_vanban.ID_COQUANBANHANH
			WHERE
				$strwhere
			$strorder
			$strlimit
		",$arrwhere);		
		return $result->fetchAll();
	}
	public function getAllVanBanOf($coquan,$linhvuc)
	{
		$strwhere="(1=1)";
		$arrwhere = array();
		if($coquan>0){
			$arrwhere[] = $coquan;
			$strwhere .= " and (vbpq_vanban.ID_COQUANBANHANH = ?) ";
		}
		if($linhvuc>0){
			$arrwhere[] = $linhvuc;
			$strwhere .= " and (vbpq_vanban.ID_LINHVUC = ?) ";
		}	
		try 
		{
			$resultString = $this->getDefaultAdapter()->query("
				SELECT
					*
				FROM
					vbpq_vanban
				WHERE
					$strwhere",$arrwhere);			
			$result=$resultString->fetchAll();	
			if(count($result)>0)
			{
				return $result;
			}
			else 
			{
				return null;
			}
		}
		catch (Exception $e1)
		{
			echo $e1;
			return null;
		}
	}
	public function getVanBanLienQuanOf($ID_VBPQ)
	{
		try 
		{
			$resultString = $this->getDefaultAdapter()->query("
				SELECT
					*
				FROM
					vbpq_vanbanlienquan
				LEFT JOIN vbpq_vanban ON vbpq_vanbanlienquan.ID_OBJECT = vbpq_vanban.ID_VBPQ
				WHERE
					vbpq_vanbanlienquan.ID_VBPQ=?",array($ID_VBPQ));			
			$result=$resultString->fetchAll();	
			if(count($result)>0)
			{
				return $result;
			}
			else 
			{
				return null;
			}
		}
		catch (Exception $e1)
		{
			echo $e1;
			return null;
		}
	}
	static function filterValid($arr)
	{
		if(count($arr)>0 && $arr!=null)
       	{
       		$stringVanbans=implode(",",$arr);
			try
			{
				$dbAdapter = Zend_Db_Table::getDefaultAdapter();
				$result = $dbAdapter->query ("
				SELECT
	 				ID_VBPQ
				FROM
					vbpq_vanban
				WHERE
					vbpq_vanban.ID_VBPQ IN ($stringVanbans)");
				$data= $result->fetchAll();
				if(count($data)>0)
	            {
	            	$arrayResult=array();
	            	$i=0;
	            	foreach($data as $row)
	            	{
						$arrayResult += array($i=>trim($row["ID_VBPQ"]));
						$i++;						
					}
					return implode(",",$arrayResult);
	            }
	            else
	            {
					return "abc";
				}
			}
			catch (Zend_Exception  $ex)
			{
				echo $ex;
				return null;
			}	
		}
		else
		{
			return null;
		}
		
	}
	public function getDetailsOf($id)
    {
        try
		{
			$result = $this->getDefaultAdapter ()->query ("
			SELECT
 				ID_VBPQ,
                MAVANBAN,
                TRICHYEU,
                date_format(NGAYBANHANH, '%d/%m/%Y') as NGAYBANHANH,
				date_format(NGAYCOHIEULUC, '%d/%m/%Y') as NGAYCOHIEULUC,
				date_format(NGAYHETHIEULUC, '%d/%m/%Y') as NGAYHETHIEULUC,
                VANBANHUONGDAN,
                NOIDUNG,
                NGUOIKY,
                ID_LINHVUC,
                ID_COQUANBANHANH,
				NGUOITAO,
				NGUOISUACUOI
				NGUOIKIEMDUYET,
				NGUONVANBAN,
				SOKYHIEU,
				ID_CAP,
				COQUANBANHANH_TEXT
				             
			FROM
				vbpq_vanban
			WHERE
				vbpq_vanban.ID_VBPQ=?",array($id));
			$data= $result->fetchAll();
            if(count($data)>0)
            {
               return $data[0];
            }
		}
		catch (Zend_Exception  $ex)
		{

		}
		return null;
    }
	static public function clearDataFor($vanban_id)
    {
		if($vanban_id>0)
		{
			try 
			{
				$dbAdapter = Zend_Db_Table::getDefaultAdapter();
				$delete_re=$dbAdapter->prepare("
								DELETE FROM 
							      	 vbpq_vanbanlienquan
							    WHERE ID_VBPQ=$vanban_id");
				$delete_re->execute();
			}
			catch(Zend_Exception $e2)
			{
				return false;
			}   
		}
	}
	static public function insertStringData($stringData,$vanban_id)
	{
		if($stringData!="")
		{
			try 
			{
				$dbAdapter = Zend_Db_Table::getDefaultAdapter();
				$r = $dbAdapter->prepare("
							INSERT INTO 
									vbpq_vanbanlienquan(ID_VBPQ,ID_OBJECT)  
					        SELECT  
							       $vanban_id,ID_VBPQ
						    FROM 
						      	 vbpq_vanban 
						    WHERE ID_VBPQ IN ($stringData)");						    
		    	$r->execute();		    	
			}
			catch(Zend_Exception $e2)
			{
				return false;
			}    
		}		
	}	
}