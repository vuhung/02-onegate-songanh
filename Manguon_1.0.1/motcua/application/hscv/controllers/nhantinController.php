<?php
/**
 * @author locbc
 * @version 1.0
 * @des nhantin 
 */
require_once ('Zend/Controller/Action.php');
//require_once('vbden/models/vbdenModel.php');
class Hscv_nhantinController extends Zend_Controller_Action {
	/**
	 * Ham khoi tao cho cac action
	 *
	 */
	function init(){
		//disable layout
		$this->_helper->layout->disableLayout();
	}
	
	function indexAction(){
		$parameter = $this->getRequest()->getParams();       
		$id_hscv = $parameter["id"];		
		$iddivParent=$parameter["iddivParent"];
		global $db;
		$tablename = QLVBDHCommon::Table("motcua_hoso");
			$sql = "
	    		SELECT 
	    			obj.*,mc_p.TENPHUONG,
	    			concat(empnn.FIRSTNAME,' ',empnn.LASTNAME) as EMPXLNAME,
	    			lhs.TENLOAI as LHSNAME
	    		FROM
	    			$tablename obj
	    			inner join ".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv on hscv.ID_HSCV = obj.ID_HSCV
	    			left join QTHT_USERS unn on obj.NGUOINHAN = unn.ID_U
	    			left join MOTCUA_LOAI_HOSO lhs on lhs.ID_LOAIHOSO = obj.ID_LOAIHOSO
	    			left join QTHT_EMPLOYEES empnn on empnn.ID_EMP = unn.ID_EMP
	    			left join motcua_phuong  mc_p  on  mc_p.ID_PHUONG = obj.PHUONG
	    		WHERE
	    			hscv.ID_HSCV = ? 	
	    	";
	    	$r = $db->query($sql,$id_hscv);
	    	$this->view->data = $r->fetch();
			
	}
	function inputAction(){
		
	}
	function saveAction(){
		$this->parameter =  $this->getRequest()->getParams();
	    //var_dump($this->parameter);exit;
		$id_hscv = $this->parameter["id"];
		$masohoso = $this->parameter["masohoso"];
		global $db;
		//$is_sms=$this->parameter["IS_SMS"];
		$dienthoai=$this->parameter["sodt"];
		try{
			//select gan nhat
			$r = $db->query("SELECT ID_TRACUU FROM services_motcua_tracuu WHERE MASOHOSO = ? AND TRANGTHAI = 3",$masohoso)->fetch();

			$db->update(services_motcua_tracuu,
				array("DIENTHOAI"=>$dienthoai,"IS_SMS"=>1,"ISGET"=>0 ),array("ID_TRACUU=".$r["ID_TRACUU"]) );					
	       }catch(Exception $e2)
	        {	        			
	        }
			
		echo "<script>window.parent.document.frm.submit();</script>";	    	
		exit;
	}

}

?>
