<?php
	class CustomersModel extends Zend_Db_Table_Abstract
	{
		protected $_name = "qtht_customers";	

		public function Count()
		{
			$arrwhere = array();
			$strwhere = "(1=1)";
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

			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$sql = "SELECT COUNT(*) AS C FROM qtht_customers 
					WHERE $strwhere
					$strorder
					$strlimit";
			try
			{
				$query = $dbAdapter->query($sql, $arrwhere);
				$re = $query->fetch();
				return $re['C'];
			} catch(Exception $e) {
				echo "Error fetch: " + $e->getMessage();
			}
		}

		public function SelectAll($offset,$limit,$order)
		{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$arrwhere = array();
			$strwhere = "(1=1)";
			if($this->_search != ""){
				$arrwhere[] = "%".$this->_search."%";
				$strwhere .= " and (NAME like ?) ";
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
			$sql = "SELECT * FROM qtht_customers 
					WHERE $strwhere
					$strorder
					$strlimit";
			try
			{
				$query = $dbAdapter->query($sql, $arrwhere);
				$re = $query->fetchAll();
				return $re;
			} catch(Exception $e) {
				echo "Error fetch: " + $e->getMessage();
			}
		}

		public function GetCustomersById($id)
		{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$sql = "SELECT * FROM qtht_customers WHERE ID = ?";
			try 
			{
				$query = $dbAdapter->query($sql, $id);
				$re = $query->fetchAll();
				return $re;
			} catch(Exception $e) {
				echo "Error get data: " + $e->getMessage();
			}
		}

		public function updateCustomersById($id, $name, $address, $email, $phone)
		{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$sql = "UPDATE qtht_customers SET NAME = ?, ADDRESS = ?, EMAIL = ?, PHONE = ?
					WHERE ID = ?";
			try
			{
				$query = $dbAdapter->query($sql, array($name, $address, $email, $phone, $id));
			} catch(Exception $e) {
				echo "Error: " + $e->getMessage();
			}
		}

		public function insertCustomers($name, $address, $email, $phone)
		{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$sql = "INSERT INTO qtht_customers(NAME, ADDRESS, EMAIL, PHONE) 
					VALUES(?, ?, ?, ?)";
			try
			{
				$query = $dbAdapter->query($sql, array($name, $address, $email, $phone));
			} catch(Exception $e) {
				echo "Error: " + $e->getMessage();
			} 
		}

		public function deleteCustomersById($id)
		{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$sql = "DELETE FROM qtht_customers WHERE ID = ?";
			try
			{
				$query = $dbAdapter->query($sql, $id);
			} catch(Exception $e) {
				echo "Error: " + $e->getMessage();
			} 
		}
	}
?>