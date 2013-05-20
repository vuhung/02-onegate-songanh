<?php

/**
 * IndexController - The default controller class
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
require_once 'hscv/models/hosocongviecModel.php';
require_once 'traodoi/models/ThongTinModel.php';
require_once 'vbdi/models/vbdi_dongluanchuyenModel.php';
//require_once 'vbden/models/vbd_dongluanchuyenModel.php';
require_once 'hscv/models/PhoiHopModel.php';
require_once 'hscv/models/bosunghosoModel.php';
require_once 'motcua/models/motcua_hosoModel.php';
class IndexController extends Zend_Controller_Action 
{
	/**
	 * The default action - show the home page
	 */
    public function indexAction() 
    {
        global $auth;
        global $db;
    	$user = $auth->getIdentity();
    	//var_export($user);
		$id_u=$user->ID_U;
		$d = getdate();
		$realyear = QLVBDHCommon::getYear();
		$this->view->title="Trang chủ";
		$this->view->subtitle="Truy cập nhanh";
		$this->view->user = $auth->getIdentity();
		$this->view->year = $realyear;
		
		/*
		//$dbAd = Zend_Db_Table::getDefaultAdapter();
		$dbAd = $db;
		$sql = "
			select * from motcua_loai_hoso  	
		";

		$qr = $dbAd->query($sql);
		$re =  $qr->fetchAll();
		foreach($re as $it_loai){
			$sql_g = " select (count(*)+1) as DEM from motcua_loai_hoso where ID_LOAIHOSO < ? and ID_LV_MC = ?";
			$qr_g = $dbAd->query($sql_g,array($it_loai["ID_LOAIHOSO"],$it_loai["ID_LV_MC"]));
			$re_g = $qr_g->fetch();
			$dem = $re_g["DEM"];
			
			$sql_u = " update motcua_loai_hoso set TENLOAI = ? where ID_LOAIHOSO = ? ";
			$tenloai = $dem.".".$it_loai["TENLOAI"] ;

			$stm = $dbAd->prepare($sql_u);
			$stm->execute(array($tenloai,$it_loai["ID_LOAIHOSO"]));
		}

		*/
		//Lấy các activity của người dùng
		$this->view->activity = WFEngine::GetActivityFromLoaiCV(0,$this->view->user->ID_U);
		$this->view->hscv = new hosocongviecModel();
		$this->view->bosung = new bosunghosoModel();
		//Lấy thông tin trao đổi nội bộ		
		$thongtinModel=new ThongTinModel(QLVBDHCommon::getYear());			
		$dataInbox=$thongtinModel->CountInbox();		
		if(count($dataInbox)>0)
		{
			$this->view->inbox=$dataInbox["C"];
			$this->view->unread=$dataInbox["C"]-$dataInbox["UNREAD"];
		}
		$thongtinModel->_danhan=0;
		$thongtinModel->_id_u=$id_u;
		$this->view->dataTraoDoi = $thongtinModel->SelectAllForInbox(0,10,"id_thongtin DESC,ngaygui DESC");		
		$this->view->cdb_vbdi = vbdi_dongluanchuyenModel::getVbdiChuaXemByIdUser($realyear,$user->ID_U);
		//$this->view->cdb_vbden = vbd_dongluanchuyenModel::getVbdenChuaXemByIdUser($realyear,$user->ID_U);
		$this->view->phoihop_data = PhoiHopModel::getNewPhoiHopByUser($realyear,$user->ID_U);
		$db->update(QLVBDHCommon::Table("GEN_MESSAGE"),array("STATUS"=>1),"ID_U_RECEIVE = ".$user->ID_U);
		
		//thong tin ho so mot cua
		$this->view->arr_tktths = (WfEngine::thongkesoluonghosotrentrangthaiByCurrentUser());
		$this->view->arr_trangthais = WfEngine::getAllTrangthaihosoByCurrentUser();
		$hsmccnt = 0;
		foreach($this->view->arr_tktths as $tktl)
			$hsmccnt +=  $tktl;
		$this->view->hsmccnt = $hsmccnt;
	}
}
