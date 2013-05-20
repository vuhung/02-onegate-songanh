<?php
class adapterHSCV{
	protected $_maSoHoSo = null;
	protected $_tenToChucCaNhan = null;
	protected $_tenHoSo = null;
	protected $_trangThai = null;
	protected $_ghiChu = null;
	protected $_dienThoai = null;
	protected $_ngayNop = null;
	protected $_barcode = null;
	protected $_config = null;
	protected $_soapClient = null;
    
	/**
     * setMasohoso()
     *
     * @param string $masohoso
     */
	public function setMasohoso($masohoso){
        $this->_maSoHoSo = $masohoso;
	}
	public function getMasohoso(){
        if($this->_maSoHoSo == null){
            throw new Exception("<font color='red'> <b>masohoso khong hop le, masohoso is NULL </b></font>");
        }
        return $this->_maSoHoSo;
	}
    
	/**
     * setTenToChucCaNhan()
     *
     * @param string $tenToChucCaNhan
     */
	public function setTenToChucCaNhan($tenToChucCaNhan){
        $this->_tenToChucCaNhan = $tenToChucCaNhan;
	}
	public function getTenToChucCaNhan(){
        if($this->_tenToChucCaNhan == null){
            throw new Exception("<font color='red'> <b>tenToChucCaNhan khong hop le, tenToChucCaNhan is NULL </b></font>");
        }
        return $this->_tenToChucCaNhan;
	}
	/**
     * setTenHoSo()
     *
     * @param string $tenHoSo
     */
	public function setTenHoSo($tenHoSo){
        $this->_tenHoSo = $tenHoSo;
	}
	public function getTenHoSo(){
        return $this->_tenHoSo;
	}
	/**
     * setTrangThai()
     *
     * @param integer $trangThai
     */
	public function setTrangThai($trangThai){
        $this->_trangThai = $trangThai;
	}
	public function getTrangThai(){
        if($this->_trangThai == null){
            throw new Exception("<font color='red'> <b>trangthai khong hop le, trangthai is NULL</b></font>");
        }
        if(!is_int($this->_trangThai)){
            throw new Exception("<font color='red'> <b>trangthai phai la so nguyen duong</b></font>");
        }
        return $this->_trangThai;
	}
	
    /**
     * setGhiChu()
     *
     * @param string $ghiChu
     */
	public function setGhiChu($ghiChu){
        $this->_ghiChu = $ghiChu;
	}
	public function getGhiChu(){
        return $this->_ghiChu;
	}
	
    /**
     * getConfig()
     *
     */
	public function getConfig(){
		if($this->_config == null){
			try{
                $config = Zend_Registry::get('config');
                $this->_config = array(
                    "WSDL" => $config->dvc_serviceadapter,
                    "USERNAME" => $config->dvc_username,
                    "PASSWORD" => $config->dvc_password
                );
            }catch(Exception $ex){
                throw new Exception("<font color='red'> <b>Error in web service config : ".$ex->getMessage()."</b></font>");;
            }
			
		}
		return $this->_config;		
	}
	
    /**
     * getConfig()
     *
     */
	public function getSoapClient(){
		if($this->_soapClient == null){
			$config = $this->getConfig();
			try{
				$this->_soapClient = new SoapClient($config['WSDL']);
			}catch(Exception $ex){
				throw new Exception("<font color='red'> <b>Could not connect to web service : ".$ex->getMessage()."</b></font>");
			}
		}
		return $this->_soapClient;		
	}
    
    /**
     * setDienThoai()
     *
     * @param string $dienThoai
     */
	public function setDienThoai($dienThoai){
        $this->_dienThoai = $dienThoai;
	}
	public function getDienThoai(){
        return $this->_dienThoai;
	}
    /**
     * setBarcode()
     *
     * @param string $barcode
     */
	public function setBarcode($barcode){
        $this->_barcode = $barcode;
	}
	public function getBarcode(){
        return $this->barcode;
	}
	
    
	/**
     * setNgayNop()
     *
     * @param string $ngayNop
     */
	public function setNgayNop($ngayNop){
        $this->_ngayNop = $ngayNop;
	}
	public function getNgayNop(){
        if($this->_ngayNop == null){
            throw new Exception("<font color='red'> <b>ngayNop khong hop le, ngayNop is NULL</b></font>");
        }
        return $this->_ngayNop;
	}
    
	public function send(){        
        $masohoso           = $this->getMasohoso();
        $tentochuccanhan    = $this->getTenToChucCaNhan();
        $tenhoso            = $this->getTenHoSo();
        $trangthai          = $this->getTrangThai();
        $ghichu             = $this->getGhiChu();
        $dienthoai          = $this->getDienThoai();
        $barcode            = $this->getBarcode();
        $config 			= $this->getConfig();
        $cliente            = $this->getSoapClient();
		global $db;
		
		//lay nguoi hien tai dang nhan ho so
		$sql = " select wf_it.ID_U from  ". QLVBDHCommon::Table("motcua_hoso") ." motcua  inner join  " . QLVBDHCommon::Table("wf_processitems")." wf_it   
		on motcua.ID_HSCV = wf_it.ID_O
		where motcua.MAHOSO = ?
		";
		$r = $db->query($sql,array($masohoso));
		$r = $r->fetch();
		$id_u_cur = $r["ID_U"];

		$r = $db->query("SELECT dep.* from 
		QTHT_USERS u 
		inner join QTHT_EMPLOYEES emp on u.ID_EMP = emp.ID_EMP
		inner join QTHT_DEPARTMENTS dep on emp.ID_DEP = dep.ID_DEP
		where u.ID_U=".(int)$id_u_cur);
		$r = $r->fetch();
		
		$u = $db->query("SELECT * from MOTCUA_HOSO_".QLVBDHCommon::getYear()." where MAHOSO=".$masohoso);
		$u = $u->fetch();
		//var_dump($r['NAME']);exit;
		$data = array(
			"MASOHOSO"=>$masohoso,
			"TENTOCHUCCANHAN"=>base64_encode($tentochuccanhan),
			"TENHOSO"=>base64_encode($tenhoso),
			"TRANGTHAI"=>$trangthai,
			"GHICHU"=>base64_encode($ghichu),
			"DIENTHOAI"=>$dienthoai,
			"BARCODE"=>substr(md5($masohoso),0,6),
			"PHONG"=>$r['NAME'],
			"NGAYNOP"=>$u['NGAYNHAN'],
			"NHANLAI_NGAY"=>$u['NHANLAI_NGAY'],
			"DIACHI"=>$u['DIACHI']
		);
		try{
            $db->insert("SERVICES_MOTCUA_TRACUU",$data);
        }catch (Exception $ex){
            throw new Exception("<font color='red'><b>".$ex->getMessage()."</b></font>");
		}
		$arrParams = array(
			$config['USERNAME'],
			$config['PASSWORD'],
			base64_encode($masohoso),
			base64_encode($tentochuccanhan),
			base64_encode($tenhoso),
			base64_encode($trangthai),
			base64_encode($r['NAME']),
			base64_encode($ghichu),
			base64_encode($dienthoai),
			base64_encode($this->getBarcode()),
			base64_encode($this->getNgayNop()),
			base64_encode($u['NHANLAI_NGAY'])
		);
		
		try{
			$vem = $cliente->__call('insertTrangThai',$arrParams);
            return $vem;
		}catch (Exception $ex){
            throw new Exception("<font color='red'><b>insertTrangThai : ".$ex->getMessage()."</b></font>");
		}
	}
    
    public function serializeString($s) {
        $s = str_replace("&", "&amp;", $s);
        $s = str_replace("~", "&split;", $s);
        return base64_encode($s);
    }
    
    public function serializeData($object){
		$data = "";
		$fields = $object->columnCount();
		$data .= $fields;
		for($i=0; $i < $fields; $i++) {
		    $ifield  = $object->getColumnMeta($i);
		    $fieldname  = $ifield['name'];
		    $data .= "~".$fieldname;
		}
		while ($row = $object->fetch()) {
			for($i=0; $i < $fields; $i++) {
				$ifield  = $object->getColumnMeta($i);
				$fieldname  = $ifield['name'];
			    $data .= "~".$this->serializeString($row[$fieldname]);
				}
		}
		return $data."&&";
	}
    static function deseriallizeToArray($string) {
        $r = array();
        $r = explode("~", $string);
        for ($i = 0; $i < count($r); $i++) {
            $r[$i] = str_replace("&split;", "~", $r[$i]);
            $r[$i] = str_replace("&amp;", "&", $r[$i]);
        }
        $array_result = array();
        $colnum = $r[0]; //so cot
        $incol = 1;
        $countr = count($r);

        $num_object = (int) ((int) $countr / (int) $colnum);

        for ($i = 1; $i < $num_object; $i++) {
            $temp = array();
            for ($j = 0; $j < $colnum; $j++) {
                $temp["" . $r[$j + 1] . ""] = base64_decode($r[$i * $colnum + $j + 1]);
            }
            $array_result[$i - 1] = $temp;
        }
        return $array_result;
    }

}