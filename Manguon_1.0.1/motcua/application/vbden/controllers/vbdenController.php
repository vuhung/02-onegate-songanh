<?php

/**
 * vbdenController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
require_once 'hscv/models/filedinhkemModel.php';
require_once 'hscv/models/hosocongviecModel.php';
require_once 'vbden/models/vbdenModel.php';
require_once 'qtht/models/SoVanBanModel.php';
require_once 'config/vbden.php';
// Dùng bên listAction
require_once 'hscv/models/loaihosocongviecModel.php';
require_once 'hscv/models/butpheModel.php';
require_once 'vbden/models/vbd_dongluanchuyenModel.php';
require_once 'vbden/models/fk_vbden_hscvsModel.php';
require_once 'hscv/models/ThuMucModel.php';
require_once 'config/hscv.php';
require_once('vbden/models/vbdenModel.php');
require_once 'vbmail/models/vbmail_vanbannhanModel.php';
include_once 'hscv/models/PhoiHopModel.php';
include_once 'hscv/models/HosoluutheodoiModel.php';

class vbden_vbdenController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated vbdenController::indexAction() default action
		$this->_redirect('/hscv/hscv/list');
	}
    
    public function detailAction(){
    	$this->_helper->layout->disablelayout();
    	global $db;
    	$param =  $this->getRequest()->getParams();
    	$idhscv = $param["id"];
		$year = $param["year"];
		$type = $param["type"];
		if($type==1){
			$tablename = QLVBDHCommon::Table("vbd_vanbanden");
			
			$sql = "
	    		SELECT 
	    			obj.*,svb.NAME as SVBNAME,
	    			concat(empnt.FIRSTNAME,' ',empnt.LASTNAME) as EMPNTNAME,
	    			lvvb.NAME as LVVBNAME,
	    			lvb.NAME as LVBNAME
	    		FROM
	    			$tablename obj
	    			inner join ".QLVBDHCommon::Table("vbd_fk_vbden_hscvs")." fkhscv on fkhscv.ID_VBDEN = obj.ID_VBD
	    			inner join ".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv on hscv.ID_HSCV = fkhscv.ID_HSCV
	    			left join VB_SOVANBAN svb on svb.ID_SVB = obj.ID_SVB
	    			left join QTHT_USERS unt on obj.NGUOITAO = unt.ID_U
	    			left join VB_LINHVUCVANBAN lvvb on lvvb.ID_LVVB = obj.ID_LVVB
	    			left join VB_LOAIVANBAN lvb on lvb.ID_LVB = obj.ID_LVB
	    			left join QTHT_EMPLOYEES empnt on empnt.ID_EMP = unt.ID_EMP
	    		WHERE
	    			hscv.ID_HSCV = ? 	
	    	";
			if($param["isidvbden"] == 1){
				$sql = "
				SELECT
                vb.*, lvb.NAME as LVBNAME, lvvb.NAME as LVVBNAME,
                svb.NAME as SVBNAME, cq.NAME as CQNAME,
                concat(emp.FIRSTNAME,' ',emp.LASTNAME) as EMPNAME
            FROM
                ".QLVBDHCommon::Table("VBD_VANBANDEN")."  vb
                inner join VB_LOAIVANBAN lvb on vb.ID_LVB = lvb.ID_LVB
                left join VB_LINHVUCVANBAN lvvb on vb.ID_LVVB =lvvb.ID_LVVB
                inner join VB_SOVANBAN svb on vb.ID_SVB = svb.ID_SVB
                left join VB_COQUAN cq	on vb.ID_CQ = cq.ID_CQ
                inner join QTHT_USERS u on vb.NGUOITAO = u.ID_U
                inner join QTHT_EMPLOYEES emp on u.ID_EMP = emp.ID_EMP
            WHERE
                vb.ID_VBD = ?";
			}
			
			$r = $db->query($sql,$idhscv);
	    	$this->view->data = $r->fetch();
	    	$sql = "
	    		SELECT dk.* FROM ".QLVBDHCommon::Table("GEN_FILEDINHKEM")." dk 
	    		WHERE
	    			 ID_OBJECT = '".$this->view->data['ID_VBD']."'
	    			 and
	    			 TYPE = 3
	    	";
	    	//echo $sql;
	    	$r = $db->query($sql);
	    	$this->view->file = $r->fetchAll();
	    	//var_dump($this->view->data);
		}else if($type==2){
			$tablename = QLVBDHCommon::Table("hscv_congviecsoanthao");
			$sql = "
	    		SELECT 
	    			obj.*,  
	    			concat(empyc.FIRSTNAME,' ',empyc.LASTNAME) as EMPYCNAME,
	    			concat(empxl.FIRSTNAME,' ',empxl.LASTNAME) as EMPXLNAME
	    		FROM
	    			$tablename obj
	    			inner join ".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv on hscv.ID_HSCV = obj.ID_HSCV
	    			left join QTHT_USERS uyc on obj.NGUOIYEUCAU = uyc.ID_U
	    			left join QTHT_USERS uxl on obj.NGUOIXULY = uxl.ID_U
	    			left join QTHT_EMPLOYEES empyc on empyc.ID_EMP = uyc.ID_EMP
	    			left join QTHT_EMPLOYEES empxl on empxl.ID_EMP = uxl.ID_EMP
	    			  
 	    		WHERE
	    			hscv.ID_HSCV = ?
	    	";
	    	$r = $db->query($sql,$idhscv);
	    	$this->view->data = $r->fetch();
	    	$sql = "
	    		SELECT dk.* FROM ".QLVBDHCommon::Table("GEN_FILEDINHKEM")." dk 
	    		WHERE
	    			 ID_OBJECT = '$idhscv'
	    			 and
	    			 TYPE = 1
	    	";
	    	$r = $db->query($sql);
	    	$this->view->file = $r->fetchAll();
		}else{
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
	    	$r = $db->query($sql,$idhscv);
	    	$this->view->data = $r->fetch();
	    	$sql = "
	    		SELECT dk.* FROM ".QLVBDHCommon::Table("GEN_FILEDINHKEM")." dk 
	    		WHERE
	    			 ID_OBJECT = '$idhscv'
	    			 and
	    			 TYPE = 1
	    	";
	    	$r = $db->query($sql);
	    	$this->view->file = $r->fetchAll();
		}
    	$this->view->type = $type;
    }

}
