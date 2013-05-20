<?php
/**
 * @author trunglv
 * @version 1.0
 * Lop model cho bang motcua_hoso_...
 */
require_once ('Zend/Db/Table/Abstract.php');

class hosomotcua {
	var $_id_loaihoso; //id loai ho so
	var $_sothutu; //so thu tu
	var $_id_phongban;//phong ban xu ly ho so (khong co trong)
}
class motcua_hosoModel extends Zend_Db_Table_Abstract {
	protected $_name = 'motcua_hoso_2009';
    public $_id_hoso = 0;
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
    /**
     * Khoi tao model cho Ho So Mot Cua
     *
     * @param int $year
     */
	function __construct($year)
	{
		if($year!=null)
    	{
    		if((int)$year>2000 && (int)$year<3000)
    		{
    			$this->_name='motcua_hoso'.'_'.$year;
    			$this->setYear($year);
    		}
    		else
    		{
    			$this->setYear(QLVBDHCommon::getYear());
    		}

    	}
    	$arr = array();
		parent::__construct($arr);
	}
	/**
	 * Cap nhat ho so mot cua sau khi tra ho so
	 */
	function updateAfterTraHoSo($idHSCV,$ngay_tra,$luc_tra,$is_khongxuly,$lydo){
		//cap nhat ngay tra , luc tra , co xu ly va trang thai da tra ho so
		$where = 'ID_HSCV='.$idHSCV;
		$data = array(
		'TRA_NGAY'=>$ngay_tra,
		'TRA_LUC'=>$luc_tra,
		'KHONGXULY'=>$is_khongxuly,
		'LYDOKHONGXULY'=>$lydo,
		'TRANGTHAI'=> 2
		);
		$this->update($data,$where);
		$r =  $this->getDefaultAdapter()->query("
			SELECT
				*
			FROM
				".$this->getName()."
		 	WHERE
				ID_HSCV=?
		 ",$idHSCV)->fetch();
		 $this->getDefaultAdapter()->update(QLVBDHCommon::Table('HSCV_HOSOCONGVIEC'),array("IS_MONITOR"=>$ismonitor),'ID_HSCV='.$idHSCV);
		 if($r['PCMTRA_NGAY']!=""){
			$where = 'ID_HSCV='.$idHSCV;
			$data = array(
				'PCMTRA_NGAY'=>$ngay_tra
			);
			$this->update($data,$where);
		 }
	}

	function updateAfterTrakhongkip($idHSCV,$lydo){
		//cap nhat ngay tra , luc tra , co xu ly va trang thai da tra ho so
		$where = 'ID_HSCV='.$idHSCV;
		$data = array(
		'LYDOKHONGTRAKIP'=>$lydo
		);
		$this->update($data,$where);
		$this->getDefaultAdapter()->update(QLVBDHCommon::Table('HSCV_HOSOCONGVIEC'),array("IS_KHONGTRAKIP"=>1),'ID_HSCV='.$idHSCV);

	}
	  public function CountHS($parameter)
	{
		//Build phan where


		$where = "(1=1)";
		$param = array();


		$auth = Zend_Registry::get('auth');
		$user = $auth->getIdentity();


		//Check mã số hồ sơ một cửa
		$is_ser_mc = 0;
		if($parameter['MASOHOSO'] !=""){
			$is_ser_mc = 1;
			$mshs = substr($parameter['MASOHOSO'],0,12);
			$where .= " and mc.MAHOSO REGEXP '^".$mshs."' ";
			//$param[] = $parameter['MASOHOSO'];
		}

		//Check tên tổ chức cá nhân
		if($parameter['TENTOCHUCCANHAN'] !=""){
			$is_ser_mc = 1;
			$mshs = trim($parameter['TENTOCHUCCANHAN']);
			$where .= " and mc.TENTOCHUCCANHAN LIKE '%".$mshs."%' ";
			//$param[] = $parameter['MASOHOSO'];
		}

	//Check tên chuyên viên xử lý
		if((int)$parameter['ID_U_NNHAN'] > 0){
			$is_ser_mc = 1;
			//$is_ser_mc_nn=1;
			//$mshs = trim($parameter['NGUOINHAN']);

			$where .= " and mc.NGUOINHAN = ? ";
			$param[] = $parameter['ID_U_NNHAN'];
		}

	//Check số ký hiệu hồ sơ một cửa
		if($parameter['SOKYHIEU_VB'] !=""){
				$is_ser_mc = 1;
				$mshs = trim($parameter['SOKYHIEU_VB']);
				$where .= " and mc.SOKYHIEU_VB LIKE '%".$mshs."%' ";
			//$param[] = $parameter['MASOHOSO'];
		}

	//Check ngay nhan
		if($parameter['ID_LOAIHOSO']){
				$is_ser_mc = 1;
				$id_loaihoso = (int)($parameter['ID_LOAIHOSO']);
				$where .= " and mc.ID_LOAIHOSO = ? ";
				$param[] = $id_loaihoso;
				//echo $where.$id_loaihoso; exit;
		}
	//Tìm theo ngày tiếp nhận

		if($parameter['NHAN_NGAY_BD']!=""){
			$is_ser_mc = 1;
			$ngayden_bd = $parameter['NHAN_NGAY_BD']." 00:00:01";

			$where .= " and mc.NHAN_NGAY >= ?";
			$param[] = $ngayden_bd;
		}

		if($parameter['NHAN_NGAY_KT']!=""){
			$is_ser_mc = 1;
			$ngayden_bd = $parameter['NHAN_NGAY_KT']." 23:59:59";

			$where .= " and mc.NHAN_NGAY <= ?";
			$param[] = $ngayden_bd;
		}

	//Tìm theo hồ sơ một cửa

		if($parameter['MC_NGAYBANHANH_BD']!=""){
			$is_ser_mc = 1;
			$mc_ngaybanhanh_bd = $parameter['MC_NGAYBANHANH_BD']." 00:00:01";

			$where .= " and mc.NGAYBANHANH_VB >= ?";
			$param[] = $mc_ngaybanhanh_bd;
		}

		if($parameter['MC_NGAYBANHANH_KT']!=""){
			$is_ser_mc = 1;
			$mc_ngaybanhanh_kt = $parameter['MC_NGAYBANHANH_KT']." 23:59:59";

			$where .= " and mc.NGAYBANHANH_VB <= ?";
			$param[] = $mc_ngaybanhanh_kt;
		}



		if($is_ser_mc == 1){
			$innerjoin .= "
				INNER JOIN ". QLVBDHCommon::Table("MOTCUA_HOSO") ." mc on hscv.ID_HSCV = mc.ID_HSCV ";
		}

		if($parameter['NAME']!=""){
			$wheretemp = "";
			if($parameter['INNAME']==1){
				Common_DBUtils::repairTableBeforeFulltextSearch(QLVBDHCommon::Table('HSCV_HOSOCONGVIEC'));
				$wheretemp = " match(hscv.NAME) against (? IN BOOLEAN MODE)";
				$order_sub = " match(hscv.NAME) against ('".str_replace("'","''",$parameter['NAME'])."') DESC";
				$param[] = $parameter['NAME'];
				$where .= " and (".$wheretemp.($parameter['INNAME']==1 && $parameter['INFILE']==1?" OR ":")");
			}
			if($parameter['INFILE']==1){
				Common_DBUtils::repairTableBeforeFulltextSearch(QLVBDHCommon::Table('GEL_FILEDINHKEM'));
				$innerjoin .= " left join hscv_duthao_".$realyear." dt on dt.ID_HSCV = hscv.ID_HSCV";
				$innerjoin .= " left join hscv_phienbanduthao_".$realyear." pbdt on pbdt.ID_DUTHAO = dt.ID_DUTHAO";
				$innerjoin .= " left join gen_filedinhkem_".$realyear." dk on dk.ID_OBJECT = pbdt.ID_PB_DUTHAO and dk.TYPE=2";
				$wheretemp = " match(dk.CONTENT) against (? IN BOOLEAN MODE)";
				$order_sub = " match(dk.CONTENT) against ('".str_replace("'","''",$parameter['NAME'])."') DESC";
				$param[] = $parameter['NAME'];
				$where .= ($parameter['INNAME']==1 && $parameter['INFILE']==1?"":" and (").$wheretemp.")";
			}
		}

		//Check order
		if($order!=""){
			$order = "ORDER BY $order";
		}else{
			$order = "ORDER BY LASTCHANGE DESC";
		}

		$strlimit = "";
		if($limit>0){
			$strlimit = " LIMIT $offset,$limit";
		}





		$sql =


			"


			SELECT count(distinct ID_HSCV ) as CNT from (


			SELECT distinct hscv.* ,'PRESENT' AS CODE, LASTCHANGE
			from ".QLVBDHCommon::Table("motcua_hoso")." mc
			inner join ". QLVBDHCommon::Table("hscv_hosocongviec")." hscv on mc.ID_HSCV = hscv.ID_HSCV
			inner join
			". QLVBDHCommon::Table("wf_processitems")." wfitem on hscv.ID_PI = wfitem.ID_PI
			WHERE
			$where
			and wfitem.ID_U = ".$user->ID_U."



			Union

			SELECT distinct hscv.* , 'OLD' AS CODE ,LASTCHANGE
			from ".QLVBDHCommon::Table("motcua_hoso")." mc
			inner join ". QLVBDHCommon::Table("hscv_hosocongviec")." hscv on mc.ID_HSCV = hscv.ID_HSCV
			inner join
			(SELECT ID_U_SEND as ID_U, ID_A_BEGIN as ID_A,pl.ID_PI,DATESEND as LASTCHANGE,pl.ID_P,t.CNTPL
					FROM
					wf_processlogs_2010 pl
					INNER JOIN (SELECT ID_PL as ID_PL,(SELECT COUNT(*) FROM wf_processlogs_2010 temp WHERE temp.ID_PI = temp1.ID_PI) as CNTPL FROM wf_processlogs_2010 temp1) t on t.ID_PL = pl.ID_PL
				WHERE
					ID_U_SEND =".$user->ID_U."
				) wfitem on hscv.ID_PI = wfitem.ID_PI
			WHERE
			$where



			) hscv


		";
		$arrwhere = array_merge($param,$param);
		$qr = $this->getDefaultAdapter()->query($sql,$arrwhere);
		$re = $qr->fetch();

		return $re["CNT"];
	}
	/**
	 * Danh sách Hồ sơ một cửa
	 * @param  integer $offset
     * @param  integer $limit
     * @param  String $order
     * @return boolean
	 */
	function SelectAll($offset,$limit,$search,$parameter ,$order){

		//Build phần where

		$where = "(1=1)";
		$param = array();


		$auth = Zend_Registry::get('auth');
		$user = $auth->getIdentity();


		//Check mã số hồ sơ một cửa
		$is_ser_mc = 0;
		if($parameter['MASOHOSO'] !=""){
			$is_ser_mc = 1;
			$mshs = substr($parameter['MASOHOSO'],0,12);
			$where .= " and mc.MAHOSO REGEXP '^".$mshs."' ";
			//$param[] = $parameter['MASOHOSO'];
		}

		//Check tên tổ chức cá nhân
		if($parameter['TENTOCHUCCANHAN'] !=""){
			$is_ser_mc = 1;
			$mshs = trim($parameter['TENTOCHUCCANHAN']);
			$where .= " and mc.TENTOCHUCCANHAN LIKE '%".$mshs."%' ";
			//$param[] = $parameter['MASOHOSO'];
		}

	//Check tên chuyên viên xử lý
		if((int)$parameter['ID_U_NNHAN'] > 0){
			$is_ser_mc = 1;
			//$is_ser_mc_nn=1;
			//$mshs = trim($parameter['NGUOINHAN']);

			$where .= " and mc.NGUOINHAN = ? ";
			$param[] = $parameter['ID_U_NNHAN'];
		}

	//Check số ký hiệu hồ sơ một cửa
		if($parameter['SOKYHIEU_VB'] !=""){
				$is_ser_mc = 1;
				$mshs = trim($parameter['SOKYHIEU_VB']);
				$where .= " and mc.SOKYHIEU_VB LIKE '%".$mshs."%' ";
			//$param[] = $parameter['MASOHOSO'];
		}

	//Check ngay nhan
		if($parameter['ID_LOAIHOSO']){
				$is_ser_mc = 1;
				$id_loaihoso = (int)($parameter['ID_LOAIHOSO']);
				$where .= " and mc.ID_LOAIHOSO = ? ";
				$param[] = $id_loaihoso;
				//echo $where.$id_loaihoso; exit;
		}
	//Tìm theo ngày tiếp nhận

		if($parameter['NHAN_NGAY_BD']!=""){
			$is_ser_mc = 1;
			$ngayden_bd = $parameter['NHAN_NGAY_BD']." 00:00:01";

			$where .= " and mc.NHAN_NGAY >= ?";
			$param[] = $ngayden_bd;
		}

		if($parameter['NHAN_NGAY_KT']!=""){
			$is_ser_mc = 1;
			$ngayden_bd = $parameter['NHAN_NGAY_KT']." 23:59:59";

			$where .= " and mc.NHAN_NGAY <= ?";
			$param[] = $ngayden_bd;
		}

	//Tìm theo hồ sơ một cửa

		if($parameter['MC_NGAYBANHANH_BD']!=""){
			$is_ser_mc = 1;
			$mc_ngaybanhanh_bd = $parameter['MC_NGAYBANHANH_BD']." 00:00:01";

			$where .= " and mc.NGAYBANHANH_VB >= ?";
			$param[] = $mc_ngaybanhanh_bd;
		}

		if($parameter['MC_NGAYBANHANH_KT']!=""){
			$is_ser_mc = 1;
			$mc_ngaybanhanh_kt = $parameter['MC_NGAYBANHANH_KT']." 23:59:59";

			$where .= " and mc.NGAYBANHANH_VB <= ?";
			$param[] = $mc_ngaybanhanh_kt;
		}



		if($is_ser_mc == 1){
			$innerjoin .= "
				INNER JOIN ". QLVBDHCommon::Table("MOTCUA_HOSO") ." mc on hscv.ID_HSCV = mc.ID_HSCV ";
		}

		if($parameter['NAME']!=""){
			$wheretemp = "";
			if($parameter['INNAME']==1){
				Common_DBUtils::repairTableBeforeFulltextSearch(QLVBDHCommon::Table('HSCV_HOSOCONGVIEC'));
				$wheretemp = " match(hscv.NAME) against (? IN BOOLEAN MODE)";
				$order_sub = " match(hscv.NAME) against ('".str_replace("'","''",$parameter['NAME'])."') DESC";
				$param[] = $parameter['NAME'];
				$where .= " and (".$wheretemp.($parameter['INNAME']==1 && $parameter['INFILE']==1?" OR ":")");
			}
			if($parameter['INFILE']==1){
				Common_DBUtils::repairTableBeforeFulltextSearch(QLVBDHCommon::Table('GEL_FILEDINHKEM'));
				$innerjoin .= " left join hscv_duthao_".$realyear." dt on dt.ID_HSCV = hscv.ID_HSCV";
				$innerjoin .= " left join hscv_phienbanduthao_".$realyear." pbdt on pbdt.ID_DUTHAO = dt.ID_DUTHAO";
				$innerjoin .= " left join gen_filedinhkem_".$realyear." dk on dk.ID_OBJECT = pbdt.ID_PB_DUTHAO and dk.TYPE=2";
				$wheretemp = " match(dk.CONTENT) against (? IN BOOLEAN MODE)";
				$order_sub = " match(dk.CONTENT) against ('".str_replace("'","''",$parameter['NAME'])."') DESC";
				$param[] = $parameter['NAME'];
				$where .= ($parameter['INNAME']==1 && $parameter['INFILE']==1?"":" and (").$wheretemp.")";
			}
		}

		//Check order
		if($order!=""){
			$order = "ORDER BY $order";
		}else{
			$order = "ORDER BY LASTCHANGE DESC";
		}

		$strlimit = "";
		if($limit>0){
			$strlimit = " LIMIT $offset,$limit";
		}


		if($parameter["ID_TTHS"] >0){

			$arr_tths = WFEngine::getAllTrangthaihosoByCurrentUser();
			$count_bshs = 0;
			foreach($arr_tths as $it_tths){
				if($it_tths["ID_TTHS"] == $parameter["ID_TTHS"] )
					if($it_tths["LA_CHOBOSUNG"] && $it_tths["THUOCTOMOTCUA"]){
						$is_bosung = 1;
					}
			}
			if($is_bosung){
				return motcua_hosoModel::SelectAllBosunghoso();
			}else{
				$sql = "
					select mc.* , 'PRESENT' as CODE_U  from
					".QLVBDHCommon::Table("hscv_hosocongviec")."  mc
					inner join ".QLVBDHCommon::Table("wf_trangthaihosologs")." tths on mc.ID_HSCV = tths.ID_HSCV
					where tths.ID_U = ? and tths.ACTIVE = 1 and ID_TTHS = ?
				";


				$arrwhere = array_merge($param,$param);
				$arrwhere[] = $user->ID_U;
				$arrwhere[] = $parameter["ID_TTHS"];
			}
		}


		if($parameter["ID_TTHS"] ==-1){
			$sql = "
				select mc.* , 'PRESENT' as CODE_U  from
				".QLVBDHCommon::Table("hscv_hosocongviec")."  mc
				inner join ".QLVBDHCommon::Table("wf_trangthaihosologs")." tths on mc.ID_HSCV = tths.ID_HSCV
				where tths.ID_U = ? and tths.ACTIVE = 0
				group by ID_HSCV
			";
			$arrwhere = array_merge($param,$param);
			$arrwhere[] = $user->ID_U;
		}
		echo $sql;
		$result = $this->getDefaultAdapter()->query($sql,$arrwhere);
		return $result->fetchAll();
	}
	/**
	 * Lay HSMC voi tham so $idHSCV
	 *
	 * @param int $idHSCV
	 * @return object
	 */
	function getHSMCByIdHSCV($idHSCV){
		$se = $this->select()->where('ID_HSCV=?',$idHSCV);
		return $this->fetchRow($se);
	}
	/**
     * Lấy hồ sơ công việc
     */
	public function findHscv($id_hscv){
        //Build phần where
        $arrwhere = array();
        $strwhere = "(1=1)";
        if($id_hscv != ""){
            $arrwhere[] = $id_hscv;
            $strwhere .= " and ID_HSCV = ?";
        }
        //Thực hiện query
        $result = $this->getDefaultAdapter()->query("
            SELECT
                *
            FROM
                HSCV_HOSOCONGVIEC_".$this->getYear()."
            WHERE
                $strwhere
        ",$arrwhere);
       	$re = $result->fetch();
       	return $re;
    }
	/**
	 * Lay ten loai ho so cong viec theo id loai ho so
	 */
	function getTenLoaiHoSoById($id_loaihoso){
		$query = $this->getDefaultAdapter()->query("
			select `TENLOAI` from `motcua_loai_hoso`
			where `ID_LOAIHOSO`=?
		",$id_loaihoso);
		$re = $query->fetch();
		return $re['TENLOAI'];
	}
	function getKhongXuLyById($id_hscv){
		$query = $this->getDefaultAdapter()->query("
			select `KHONGXULY`  from ".QLVBDHCommon::Table("MOTCUA_HOSO")."
			where `ID_HSCV`=?
		",$id_hscv);
		$re = $query->fetch();
		return $re['KHONGXULY'];
	}
	static function getNextSo(){
		global $db;
		$query = $db->query("
			select max(SO) as SO FROM ".QLVBDHCommon::Table("MOTCUA_HOSO")."
		");
		$re = $query->fetch();
		return $re['SO']+1;
	}
	static function getNextSoHoso($id_stn){
		global $db;
		$where = "";
		if($id_stn > 0)
		$where = "  where ID_STN = ? group by ID_STN ";
		$query = $db->query("
			select max(SO) as SO FROM ".QLVBDHCommon::Table("MOTCUA_HOSO")."
			$where

		", array($id_stn));
		$re = $query->fetch();
		return $re['SO']+1;
	}
  public static function toCombophuong($sel){

		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "select * from `motcua_phuong`
		  ORDER BY TENPHUONG
		";
		$query = $dbAdapter->query($sql);
		$re = $query->fetchAll();
		foreach ($re as $row){
		     $selected = "";
		     if($row['ID_PHUONG'] == $sel)$selected = "selected";
			echo "<option id='".$row['ID_PHUONG']."' value=".$row['ID_PHUONG']." $selected>".$row['TENPHUONG']."</option>";
		}
	}
 public static function ws_getphong($id_u){

		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = "select NAME from `qtht_departments` dp
		       INNER JOIN qtht_employees emp  ON emp.ID_DEP = dp.ID_DEP
		       INNER JOIN qtht_users ON qtht_users.ID_EMP = emp.ID_EMP
		   where ID_U = $id_u
		";
		$query = $dbAdapter->query($sql);
		$re = $query->fetch();
		return $re['NAME'];
	}
 public static function getyeucaubosung($id_yeucau){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();

		$sql = "select * from ".QLVBDHCommon::Table("hscv_phieu_yeucau_bosung")."
		   where ID_YEUCAU= $id_yeucau
		";
		$query = $dbAdapter->query($sql);
		$re = $query->fetch();
		return $re;
	}
 public static function getid_pl($id_o){
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$sql = " select wfpl.ID_PL from ".QLVBDHCommon::Table("wf_processlogs")." wfpl
		          INNER JOIN ".QLVBDHCommon::Table("wf_processitems")." wfpi On wfpl.ID_PI = wfpi.ID_PI
		          where wfpi.ID_O = $id_o and wfpl.HANXULY >0 AND wfpl.`TRE`is NULL
		";
		$query = $dbAdapter->query($sql);
		$re = $query->fetch();
		return $re['ID_PL'];
	}

	function getHSByHSCV($idttt){
		$sql = "
			SELECT ID_HOSO FROM motcua_hoso_".QLVBDHCommon::getYear()." kq
			inner join hscv_hosocongviec_".QLVBDHCommon::getYear()." hscongviec on kq.ID_HSCV=hscongviec.ID_HSCV WHERE hscongviec.ID_HSCV=?" ;
		$r = $this->getDefaultAdapter()->query($sql,array($idttt));

		$a= $r->fetch();
		return $a['ID_HOSO'];
	}

	function updateHSByHSCV($arrdata,$idHs){
		try
		{
			$where = 'ID_HOSO='.$idHs;
			$r = $this->update($arrdata,$where);
		}
		catch(Exception $e)
		{
			echo $e->__toString();
		}
	}

	function getTENPHUONG($idp){
		$sql = "
			Select TENPHUONG from motcua_phuong mcp
			INNER JOIN motcua_hoso_".QLVBDHCommon::getYear()." mchs ON mcp.ID_PHUONG=mchs.PHUONG
			where PHUONG=?" ;
		$r = $this->getDefaultAdapter()->query($sql,array($idp));

		$a= $r->fetch();
		return $a['TENPHUONG'];
	}

	static function Customfields($id_loaihoso){
		$sql = "  select mc_cf.* from motcua_custom_field mc_cf
		inner join motcua_loai_hoso loaihs on mc_cf.LOAIHOSO_CODE = loaihs.CODE
		where ID_LOAIHOSO = ?
		";
		//try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($id_loaihoso));
			return $qr->fetchAll();
		//}catch(Exception $ex){
			return array();
		//}
	}
	static function CustomfieldsInBN($id_loaihoso){
		$sql = "  select mc_cf.* from motcua_custom_field mc_cf
		inner join motcua_loai_hoso loaihs on mc_cf.LOAIHOSO_CODE = loaihs.CODE
		where ID_LOAIHOSO = ? and IS_INCHOCD=1
		";
		//try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($id_loaihoso));
			return $qr->fetchAll();
		//}catch(Exception $ex){
			return array();
		//}
	}


	/*
	* add new by trunglv
	*
	*/
	function countBosunghoso(){

	}

	function SelectAllBosunghoso($parameter,$offset,$limit,$order){

		global $db;
		$user = Zend_Registry::get('auth')->getIdentity();

		$where = "  ";
		$param = array();
		$param[] = $user->ID_U;
		if($parameter['TENTOCHUCCANHAN']!=""){
			$wheretemp = "";
			Common_DBUtils::repairTableBeforeFulltextSearch(QLVBDHCommon::Table('MOTCUA_HOSO'));
			$wheretemp = " match(mc.TENTOCHUCCANHAN) against (? IN BOOLEAN MODE)";
			$order = " match(mc.TENTOCHUCCANHAN) against ('".str_replace("'","''",$parameter['TENTOCHUCCANHAN'])."') DESC";
			$param[] = $parameter['TENTOCHUCCANHAN'];
			$where .= " and (".$wheretemp.")";
		}

	//check phuong
		if($parameter['ID_PHUONG']!=0){
			if($parameter['ID_PHUONG']==-1){
				$where .= " and mc.PHUONG is null";
			}else{
				$param[] = $parameter['ID_PHUONG'];
				$where .= " and mc.PHUONG = ?";
			}
		}
	//
	if($parameter['MAHOSO']!=""){
			$wheretemp = "";
			$wheretemp = " mc.MAHOSO = ? ";
			$param[] = $parameter['MAHOSO'];
			$where .= " and (".$wheretemp.")";
		}



	//$param = Array();
	$sql = "
			SELECT hscv.*, mc.PHUONG , mc.TENTOCHUCCANHAN, mc.MAHOSO,  max(bs.ID_YEUCAU) as ID_YEUCAU FROM
				".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv
			inner join ".  QLVBDHCommon::Table("MOTCUA_HOSO") ." mc on hscv.ID_HSCV =  mc.ID_HSCV
			inner join motcua_loai_hoso loaihs on mc.ID_LOAIHOSO = loaihs.ID_LOAIHOSO
			inner join ( select lv.* from motcua_linhvuc lv inner join motcua_linhvuc_quyen pq on lv.ID_LV_MC = pq.ID_LV_MC   where ID_U = ?)
			linhvuchs on loaihs.ID_LV_MC = linhvuchs.ID_LV_MC
			inner join ".  QLVBDHCommon::Table("HSCV_PHIEU_YEUCAU_BOSUNG") ." bs on hscv.ID_HSCV = bs.ID_HSCV
			GROUP BY hscv.ID_HSCV having hscv.IS_BOSUNG = 1
			$where
		";
		//
		try{
			//echo $sql;
			$r = $db->query($sql,$param);
			$result = $r->fetchAll();
			//var_dump($param);
		}catch(Exception $ex){
			echo $ex->__toString();;
			return null;
		}
		return $result;
    }





}

?>
