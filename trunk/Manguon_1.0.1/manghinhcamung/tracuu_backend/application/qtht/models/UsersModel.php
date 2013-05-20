<?php
/**
 * UsersModel
 *  
 * @author truongvc
 * @version 1.0
 */
require_once 'qtht/models/employeesModel.php';
class UsersModel extends Zend_Db_Table
{
    protected $_name = 'qtht_users';
    public $_id_p = 0;
    /**
     * Count all items in QTHT_USERS table
     * @return integer
     */
	public function Count()
	{
		//Build phần where
		$arrwhere = array();
		$strwhere = "(1=1)";		
		if($this->_search != ""){
			$arrwhere[] = "%".$this->_search."%";
			$strwhere .= " and USERNAME like ?";
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
     * Check exist of instance of users throught 'NameUser'
     *
     * @param  Zend_Db_Table $tableSel
     * @param  String $NameUser
     * @return boolean
     */
	function checkExistUser($NameUser)
	{
		
		$select=$this->select();
		$where=" USERNAME ='".$NameUser."'";
		$select->from($this->_name,'COUNT(*) AS NUM');
		$select->where($where);
		$row=$this->fetchRow($select);
		if($row->NUM >0)
		{
			return true;	
		}
		else return false;
		
		
	}
	/**
     * Select all item ID_U with USERNAME, FIRSTNAME,LASTNAME
     *
     * @return array
     */
	function selectAllUsersJoinEmployees()
	{
		$r = $this->getDefaultAdapter()->query("
			SELECT
     			QTHT_USERS.ID_U,USERNAME,CONCAT(FIRSTNAME , ' ' , LASTNAME) as NAME
			FROM
				QTHT_USERS
			LEFT JOIN
				QTHT_EMPLOYEES
			ON QTHT_USERS.ID_EMP=QTHT_EMPLOYEES.ID_EMP");
		return $r->fetchAll();
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
			$strwhere .= " and USERNAME like ?";
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
     * Get all items in QTHT_MENUS table with pairs : ID_MNU and NAME
     * @return array
     */
	function GetAllNames()
	{
		$r = $this->getDefaultAdapter()->query("
			SELECT
     			USERNAME
			FROM
				QTHT_USERS");
		return $r->fetchAll();
	}
	
	static function getEmloyeeNameByIdUser($id_u){
		$model = new UsersModel();
		$model->getDefaultAdapter()->beginTransaction();
		$query = $model->getDefaultAdapter()->query('Select e.`FIRSTNAME` , e.`LASTNAME`
		from `qtht_users` u JOIN `qtht_employees` e
		where u.`ID_EMP` = e.ID_EMP and u.`ID_U`='.$id_u);
		$re = $query->fetchAll();
		$model->getDefaultAdapter()->commit();
		//return var_dump($re);;
		return $re[0]["FIRSTNAME"].' '.$re[0]["LASTNAME"];
		//retue->FIRSTNAME.' '.$re->LASTNAME;
	}
	
	static function SelectByDep($iddep){
		global $db;
		$sql = 	"SELECT u.ID_U, concat(e.FIRSTNAME,' ',e.LASTNAME) as NAME FROM 
			QTHT_USERS u
			inner join QTHT_EMPLOYEES e on u.ID_EMP = e.ID_EMP
			WHERE
				e.ID_DEP = ?
		";
		$r = $db->query($sql,$iddep);
		return $r->fetchAll();
	}
	static function SelectByGroup($idg){
		global $db;
		$sql = 	"SELECT u.ID_U, concat(e.FIRSTNAME,' ',e.LASTNAME) as NAME FROM 
			QTHT_USERS u
			inner join QTHT_EMPLOYEES e on u.ID_EMP = e.ID_EMP
			inner join FK_USERS_GROUPS g on g.ID_U = u.ID_U
			WHERE
				g.ID_G = ?
		";
		$r = $db->query($sql,$idg);
		return $r->fetchAll();
	}
	public function getName($id_u)
	{
		$r = $this->getDefaultAdapter()->query("
			SELECT
     			USERNAME AS NGUOITAO,CONCAT(FIRSTNAME,' ',LASTNAME) AS TENNGUOITAO
			FROM
				QTHT_USERS
			INNER JOIN QTHT_EMPLOYEES ON QTHT_EMPLOYEES.ID_EMP=QTHT_USERS.ID_EMP
			WHERE ID_U=?",array($id_u));
		$data= $r->fetchAll();
		if(count($data)>0) return $data[0];
		else return -1;
	}
	
	static function getAllUserName(){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select USERNAME from qtht_users 
		";
		try{
			$qr = $dbAdapter->query($sql);
			return $qr->fetchAll();
		
		}catch (Exception $ex){
			return array();
		}
	}

	static function getUsersByDepartment($idDepartment){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			SELECT u.`ID_U` , concat(e.`FIRSTNAME`, ' ',e.`LASTNAME`) as FULLNAME
			FROM 
				`qtht_users` u 
			inner join
				`qtht_employees` e on e.`ID_EMP` = u.`ID_EMP`
			inner join 
				`qtht_departments` d on d.`ID_DEP`= e.`ID_DEP`
			where
				d.`ID_DEP` = ? 
			order by u.ORDERS
				
		";
		$stm = $dbAdapter->query($sql,array($idDepartment));
		return $stm->fetchAll();
	}
	
	static function getAllNameAndId($is_check_active){
		$users = "";
		if(!$is_check_active)
			$is_check_active = 0;
		if($is_check_active == 0)
			$users = " QTHT_USERS ";
		else 
			$users = " (select * from QTHT_USERS where ACTIVE=1) QTHT_USERS  ";
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		//try{
			$r = $dbAdapter->query("
				SELECT
	     			QTHT_USERS.ID_U,USERNAME,CONCAT(FIRSTNAME , ' ' , LASTNAME) as NAME
				FROM".
				$users
				."LEFT JOIN
					QTHT_EMPLOYEES
				ON QTHT_USERS.ID_EMP=QTHT_EMPLOYEES.ID_EMP");
			return $r->fetchAll();
		//}catch (Exception $ex){
			return array();
		//}
	}
	
	static function getUserDepId($id_u){
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$sql = "
			SELECT d.*
			FROM 
				`qtht_users` u 
			inner join
				`qtht_employees` e on e.`ID_EMP` = u.`ID_EMP`
			inner join 
				`qtht_departments` d on d.`ID_DEP`= e.`ID_DEP`
			where
				u.`ID_U` = ?
			";
			try{
				$stm = $dbAdapter->query($sql,array($id_u));
				return $stm->fetch();
			}catch(Exception $ex){
				return array();
			}
	}

	static function getUsernameById($id_u){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = " select USERNAME from qtht_users where ID_U = ?";
		try{
			$qr = $dbAdapter->query($sql,array($id_u));
			$re = $qr->fetch();
			return $re["USERNAME"];
		}catch(Exception $ex){
			return "";
		}
	}
	
	
}