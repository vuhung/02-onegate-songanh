<?php

/**
 * HscvController
 * Xử lý các thao tác về hố sơ công việc như
 * Danh sách
 * Tìm kiếm
 * @deprecated 20/10/2009 by nguyennd
 * @author nguyennd
 * @version 1.0
 */

require_once 'Zend/Controller/Action.php';
require_once 'hscv/models/hosocongviecModel.php';
require_once 'hscv/models/loaihosocongviecModel.php';
require_once 'hscv/models/butpheModel.php';
//require_once 'vbden/models/vbdenModel.php';
//require_once 'vbden/models/vbd_dongluanchuyenModel.php';
require_once 'hscv/models/ThuMucModel.php';
require_once 'config/hscv.php';
require_once('qtht/models/UsersModel.php');
require_once('auth/models/ResourceUserModel.php');
require_once 'hscv/models/HosoluutheodoiModel.php';
include_once 'hscv/models/PhoiHopModel.php';
include_once 'hscv/models/bosunghosoModel.php';
include_once 'motcua/models/linhvucmotcuaModel.php';
include_once 'qtht/models/DanhMucPhuongModel.php';
require_once 'motcua/models/motcua_thutuc_bosungModel.php';
require_once 'motcua/models/HoSoMotCuaModel.php';

class Hscv_HscvController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
	}
	/**
	 * Tạo form list cho HSCV chung chung
	 */
	public function listAction(){
		$this->view->start = (float) array_sum(explode(' ',microtime())); 
		global $auth;
		$user = $auth->getIdentity();
		
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$config = Zend_Registry::get('config');
		
		//tinh chỉnh param
		if($param['NGAY_BD']!=""){
			$ngaybd = $param['NGAY_BD']."/".QLVBDHCommon::getYear();
			$ngaybd = implode("-",array_reverse(explode("/",$ngaybd)));
		}
		if($param['NGAY_KT']!=""){
			$ngaykt = $param['NGAY_KT']."/".QLVBDHCommon::getYear();
			$ngaykt = implode("-",array_reverse(explode("/",$ngaykt)));
		}
		$realyear = QLVBDHCommon::getYear();

		$id_thumuc = $param["id_thumuc"];
		$id_thumuc = $id_thumuc==""?1:$id_thumuc;
		//check quyen vao thu muc neu thu muc >1
		if($id_thumuc>1){
			if(!ThuMucModel::CheckPermission($user->ID_U,$id_thumuc)){
				$this->_redirect("/default/error/error?control=hscv&mod=hscv&id=ERR11001001");
			}
		}
		$ID_LOAIHSCV = $param['ID_LOAIHSCV'];
		$ID_LV_MC = $param['ID_LV_MC'];
		$ID_PHUONG = $param['ID_PHUONG'];
		$NAME = $param['NAMECV'];
		$TENTOCHUCCANHAN=$param['NAMECD'];
		$MAHOSO=$param['MABIENNHAN'];
		$page = $param['page'];
		$limit = $param['limit1'];
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		$TRANGTHAI = $param['TRANGTHAI'];
		$scope = array();
		if($param['SCOPE']){
			$scope = $param['SCOPE'];
		}

		if($param['bosung']){
			$bosung = $param['bosung'];
		}
		$this->view->is_bosung = (int)$bosung;
		$parameter = array(
			"ID_THUMUC"=>$id_thumuc,
			"ID_LOAIHSCV"=>$ID_LOAIHSCV,
			"NGAY_BD"=>$ngaybd,
			"NGAY_KT"=>$ngaykt,
			"TRANGTHAI"=>$TRANGTHAI,
			"ID_U"=>$user->ID_U,
			"ID_G"=>$user->ID_G,
			"ID_DEP"=>$user->ID_DEP,
			"NAME"=>$NAME,
			"TENTOCHUCCANHAN"=>$TENTOCHUCCANHAN,
			"DIACHIKINHDOANH"=>$param['DIACHIKINHDOANH'],
			"MAHOSO"=>$MAHOSO,
			"SCOPE"=>$scope,
			"CODE"=>$param['code'],
			"ID_LV_MC"=>$ID_LV_MC,
			"ID_PHUONG"=>$ID_PHUONG,
			"IS_BOSUNG" => $bosung
		);
		
		//Tạo đối tượng
		$hscv = new hosocongviecModel();
		$hscvcount = $hscv->Count($parameter);
		if(($page-1)*$limit==$hscvcount && $hscvcount>0)$page--;
		//Lấy dữ liệu
		$this->view->loaihscv = WFEngine::GetLoaiCongViecFromUser($user->ID_U);
		$this->view->data = $hscv->SelectAll($parameter,($page-1)*$limit,$limit,"");
		$this->view->realyear = $realyear;
		$this->view->ID_LOAIHSCV = $ID_LOAIHSCV;
		$this->view->ID_LV_MC = $ID_LV_MC;
		$this->view->ID_PHUONG = $ID_PHUONG;
		$this->view->NAME = $NAME;
		$this->view->TENTOCHUCCANHAN=$TENTOCHUCCANHAN;
		$this->view->MAHOSO = $MAHOSO;
		$this->view->NGAY_BD = $param['NGAY_BD'];
		$this->view->DIACHIKINHDOANH = $param['DIACHIKINHDOANH'];
		$this->view->NGAY_KT = $param['NGAY_KT'];
		$this->view->NAME = $NAME;
		$this->view->TRANGTHAI = $TRANGTHAI;
		$this->view->datatrangthai = WFEngine::GetActivityFromLoaiCV($ID_LOAIHSCV,$user->ID_U);
		$this->view->id_thumuc = $id_thumuc;
		$this->view->SCOPE = $scope;
		$this->view->user = $user;
		$this->view->code = $param['code'];
		$this->view->idhscv = $param['idhscv'];
		
		//Create button
		if($id_thumuc<2){
			$createarr = WFEngine::GetCreateProcessButtonFromLoaiCV($ID_LOAIHSCV,$user->ID_U);
			if(count($createarr)>0){
				QLVBDHButton::AddButton($createarr["NAME"],"","AddNewButton","CreateButtonClick(\"".$createarr["LINK"]."/type/$ID_LOAIHSCV/wf_id_t/".$createarr["ID_T"]."/year/".$realyear."\")");
			}
			
			$createarr = WFEngine::GetCreateProcessButtonFromLoaiCV($ID_LOAIHSCV,$user->ID_U);
			if(count($createarr)>0){
				QLVBDHButton::AddButton($createarr["TENTOCHUCCANHAN"],"","AddNewButton","CreateButtonClick(\"".$createarr["LINK"]."/type/$ID_LOAIHSCV/wf_id_t/".$createarr["ID_T"]."/year/".$realyear."\")");
			}
			$createarr = WFEngine::GetCreateProcessButtonFromLoaiCV($ID_LOAIHSCV,$user->ID_U);
			if(count($createarr)>0){
				QLVBDHButton::AddButton($createarr["MAHOSO"],"","AddNewButton","CreateButtonClick(\"".$createarr["LINK"]."/type/$ID_LOAIHSCV/wf_id_t/".$createarr["ID_T"]."/year/".$realyear."\")");
			}
		}
		QLVBDHButton::AddButton("Chuyển nhiều","","SaveButton","document.location.href=\"/hscv/hscv/multisend\";");
		
		//page
		$this->view->title = "Hồ sơ công việc";
		if(strtoupper($param['code'])=='OLD'){
			$this->view->subtitle = "danh sách đã xử lý";
		}else if(strtoupper($param['code'])=='PRE'){
			$this->view->subtitle = "danh sách vừa xử lý";
		}else if(strtoupper($param['code'])=='ZIP'){
			$this->view->subtitle = "danh sách lưu trữ";
		}else if(strtoupper($param['code'])=='PHOIHOP'){
			$this->view->subtitle = "danh sách phối hợp";
		}else{
			$this->view->subtitle = "<font color=red>danh sách chờ xử lý</font>";
			if($bosung == 1){
				$this->view->subtitle = "<font color=red>danh sách đang yêu cầu bổ sung</font>";
			}
		}
		$this->view->page = $page;
		$this->view->limit = $limit;
		
		$thumuc = new ThuMucModel();
		$this->view->showPage = QLVBDHCommon::PaginatorWithAction($hscvcount,10,$limit,"frm",$page,"/hscv/hscv/list/id_thumuc/".$id_thumuc."/code/".$param['code']);
		//$this->view->thumuc = $thumuc->SelectAll(($page-1)*$limit,$limit,4);
		$this->view->thumuc = ThuMucModel::GetTreeByPermission($user->ID_U);
		$this->view->vbden = new vbdenModel(QLVBDHCommon::getYear());
		$this->view->linhvuc = new linhvucmotcuaModel();
		$this->view->linhvuc = $this->view->linhvuc->SelectAll();
		$this->view->phuong = new DanhMucPhuongModel();
		$this->view->phuong = $this->view->phuong->SelectAll(0,0,"TENPHUONG");
		if($param['nolayout']==1){
			$this->_helper->layout->disableLayout();
			$this->renderScript("hscv/listnolayout.phtml");
		}
	}
	
	/**
	* Tim kiem toan bo danh sach ho so cong viec da xu ly
	*/
	
	function listsearchallAction(){ 
		global $auth;
		$user = $auth->getIdentity();
		
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$param['code'] = 'OLD';
		$config = Zend_Registry::get('config');
		
		//tinh chỉnh param
		if($param['NGAY_BD']!=""){
			$ngaybd = $param['NGAY_BD']."/".QLVBDHCommon::getYear();
			$ngaybd = implode("-",array_reverse(explode("/",$ngaybd)));
		}
		
		if($param['NGAY_KT']!=""){
			$ngaykt = $param['NGAY_KT']."/".QLVBDHCommon::getYear();
			$ngaykt = implode("-",array_reverse(explode("/",$ngaykt)));
		}
		$realyear = QLVBDHCommon::getYear();
		
		
		$ID_LOAIHOSO = $param['ID_LOAIHOSO'];
		if($param['is_lv_change'] == 1) $ID_LOAIHOSO = 0; 
		$NAME = $param['NAMECV'];
		$TENTOCHUCCANHAN=$param['NAMECD'];
		$MAHOSO=$param['MABIENNHAN'];
		$page = $param['page'];
		$limit = $param['limit1'];
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		$TRANGTHAI = $param['TRANGTHAI'];
		$ID_LV_MC = $param['ID_LV_MC'];
		$parameter = array(
			"ID_THUMUC"=>$id_thumuc,
			"ID_LV_MC" => $ID_LV_MC,
			"ID_LOAIHOSO"=>$ID_LOAIHOSO,
			"NGAY_BD"=>$ngaybd,
			"NGAY_KT"=>$ngaykt,
			"ID_U"=>$user->ID_U,
			"ID_G"=>$user->ID_G,
			"ID_DEP"=>$user->ID_DEP,
			"NAME"=>$NAME,
			"TENTOCHUCCANHAN"=>$TENTOCHUCCANHAN,
			"MAHOSO"=>$MAHOSO,	
			"CODE"=>$param['code']
		);
		
		//Tạo đối tượng
		$hscv = new hosocongviecModel();
		$hscvcount = $hscv->CountSearchAll($parameter);
		if(($page-1)*$limit==$hscvcount && $hscvcount>0)$page--;
		//Lấy dữ liệu
		//$this->view->loaihscv = WFEngine::GetLoaiCongViecFromUser($user->ID_U);
		$this->view->lv_mc = $param['ID_LV_MC'];
		$this->view->data = $hscv->SelectSearchAll($parameter,($page-1)*$limit,$limit,"");
		$this->view->realyear = $realyear;
		$this->view->ID_LOAIHOSO = $ID_LOAIHOSO;
		$this->view->TENTOCHUCCANHAN= $TENTOCHUCCANHAN;
		$this->view->NAME = $NAME;
		$this->view->MAHOSO = $MAHOSO;
		$this->view->NGAY_BD = $param['NGAY_BD'];
		$this->view->NGAY_KT = $param['NGAY_KT'];
		$this->view->NAME = $NAME;		
		$this->view->user = $user;
		$this->view->code = $param['code'];
		$this->view->idhscv = $param['idhscv'];
		
		
		//page
		$this->view->title = "Hồ sơ công việc";
		$this->view->subtitle = "<font color=red>Tìm kiếm hồ sơ </font>";
		$this->view->page = $page;
		$this->view->limit = $limit;
		$this->view->showPage = QLVBDHCommon::PaginatorWithAction($hscvcount,10,$limit,"frm",$page,"/hscv/hscv/listsearchall");
		$this->view->vbden = new vbdenModel(QLVBDHCommon::getYear());
	}

	/**
	 * Tạo form nhập liệu cho bút phê văn bản đến
	 */
	function inputbutpheAction(){
		$this->_helper->layout->disableLayout();
		
		global $auth;
		$user = $auth->getIdentity();
		
		//parameter
		$this->parameter = $this->getRequest()->getParams();
		$this->view->wf_id_t = $this->parameter["wf_id_t"];
		$this->view->ID_HSCV = $this->parameter["id"];
		$this->view->year = $this->parameter["year"];
		$this->view->code = $this->parameter["code"];
		$this->view->processinfo = WFEngine::GetCurrentTransitionInfoByIdHscv($this->view->ID_HSCV);
		if($this->view->code=="butphe"){
			$this->_redirect("hscv/hscv/butphe/id/".$this->parameter["id"]."/wf_id_t/".$this->parameter["wf_id_t"]);
		}
	}
	function butpheAction(){
		$this->_helper->layout->disableLayout();
		
		global $auth;
		$user = $auth->getIdentity();
		
		//parameter
		$this->parameter = $this->getRequest()->getParams();
		$this->view->wf_id_t = $this->parameter["wf_id_t"];
		$this->view->ID_HSCV = $this->parameter["id"];
		$this->view->processinfo = WFEngine::GetCurrentTransitionInfoByIdHscv($this->view->ID_HSCV);
		$this->view->hanxuly = WFEngine::GetHanXuLy($this->view->wf_id_t);
	}
	function savebpAction(){
		global $auth;
		global $db;
		$user = $auth->getIdentity();
		
		$parameter = $this->getRequest()->getParams();
		
		$idu = $parameter["ID_U"];
		$hanxuly = $parameter["HANXULY"];
		$type = $parameter["TYPE"];
		$id = $parameter["id"];
		$wf_id_t = $parameter["wf_id_t"];
		$noidung = $parameter["NOIDUNG"];
		$istheodoi = $parameter["istheodoi"];
		
		$idusend = array();
		$hanxulysend = array();
		$noidungsend = array();
		$idudb = array();
		for($i=0;$i<count($idu);$i++){
			if($type[$i]==2){
				$idusend[] = $idu[$i];
				$hanxulysend[] = $hanxuly[$i];
				$noidungsend[] = $noidung;
			}else if($type[$i]==1){
				$idudb[] = $idu[$i];
			}else{
				$iduph[] = $idu[$i];
				$idudb[] = $idu[$i];
			}
		}
		
		$processinfo = WFEngine::GetCurrentTransitionInfoByIdHscv($id);
		$usernk = $processinfo["ID_U_NK"];
		$usernc = $processinfo["ID_U_NC"];
		$vbd = new vbdenModel(QLVBDHCommon::getYear());
        $vbden = $vbd->findByHscv($id);
		//insert vao butphe
		if($noidung!=""){
			$db->insert(QLVBDHCommon::Table("HSCV_BUTPHE"),array(
				"NOIDUNG"=>$noidung,
				"NGUOIKY"=>$usernk,
				"NGUOICHUYEN"=>$usernc,
				"ID_VBD"=>$vbden['ID_VBDEN'],
				"NGAYBP"=>date("Y-m-d H:i:s")
			));
		}
		$lc = new vbd_dongluanchuyenModel(QLVBDHCommon::getYear());
		$lc->send($vbden['ID_VBDEN'],$idudb,$noidung,$user->ID_U);
		if(count($idusend)>0){
			try{
	           	hosocongviecModel::SendAll(
	            	$id,
	            	$user->ID_U,
	            	$wf_id_t,
	            	array(),
	            	array(),
	            	array(),
	            	array(),
	            	array(),
	            	array(),
	            	$idusend,
	            	$noidungsend,
	            	$hanxulysend,
	            	$vbden['ID_VBDEN'],
	            	"ID_VBDEN",
	            	"VBD_FK_VBDEN_HSCVS"
	           	);
			}catch(Exception $ex){
				echo $ex->__toString();
			}
		}else{
			if($istheodoi==1){
				HosoluutheodoiModel::luuTheodoi(QLVBDHCommon::getYear(),$id,"",$user->ID_U);
			}
		}
		if(count($iduph)>0){
			PhoiHopModel::AddPhoiHop($iduph,$vbden['ID_VBDEN']);
		}
		echo "<script>window.parent.document.frm.submit();</script>";
		//var_dump($idusend);
		exit;
	}
	/**
	 * Lưu nội dung bút phê
	 */
	function savebutpheAction(){
		global $auth;
		global $db;
		$user = $auth->getIdentity();
		
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$idhscv = $param["id"];
		$wf_id_t = $param["wf_id_t"];
		$wf_nexttransition = $param["wf_nexttransition"];
		$wf_nextuser = $param["wf_nextuser"];
		$wf_nextg = $param["wf_nextg"];
		$wf_nextdep = $param["wf_nextdep"];
		$wf_name_user = $param["wf_name_user"];
		$wf_name_g = $param["wf_name_g"];
		$wf_name_dep = $param["wf_name_dep"];
		$wf_hanxuly_user = $param["wf_hanxuly_user"];
		$wf_hanxuly_g = $param["wf_hanxuly_g"];
		$wf_hanxuly_dep = $param["wf_hanxuly_dep"];
		$year = QLVBDHCommon::getYear();
		
		$processinfo = WFEngine::GetCurrentTransitionInfoByIdHscv($idhscv);
		$usernk = $processinfo["ID_U_NK"];
		$usernc = $processinfo["ID_U_NC"];
		$noidung = $param["NOIDUNG"];
		$hanxuly = $param["HANXULY"];
		if($hanxuly=="")$hanxuly=0;
		
		//Tạo đối tượng
		$hscv = new hosocongviecModel();
		$db->beginTransaction();
		try{
			$vbd = new vbdenModel(QLVBDHCommon::getYear());
            $vbden = $vbd->findByHscv($idhscv);
			//insert vao butphe
			$db->insert("HSCV_BUTPHE_".$year,array(
				"NOIDUNG"=>$noidung,
				"NGUOIKY"=>$usernk,
				"NGUOICHUYEN"=>$usernc,
				"HANXULY"=>$hanxuly,
				"ID_VBD"=>$vbden['ID_VBDEN']
			));
			if( !is_array($param["wf_nextuser"]) &&
            	!is_array($param["wf_nextg"]) &&
            	!is_array($param["wf_nextdep"])
            ){
				//send next user
				if(WFEngine::SendNextUserByObjectId2($idhscv,$wf_nexttransition,$user->ID_U,$wf_nextuser,WFEngine::$WFTYPE_USER,$wf_name_user,$wf_hanxuly_user)==1){
					$db->commit();
				}else{
					$db->rollBack();
				}
            }else{
            	hosocongviecModel::SendAll(
	            	$idhscv,
	            	$user->ID_U,
	            	$wf_nexttransition,
	            	$wf_nextg,
	            	$wf_name_g,
	            	$wf_hanxuly_g,
	            	$wf_nextdep,
	            	$wf_name_dep,
	            	$wf_hanxuly_dep,
	            	$wf_nextuser,
	            	$wf_name_user,
	            	$wf_hanxuly_user,
	            	$vbden['ID_VBDEN'],
	            	"ID_VBDEN",
	            	"VBD_FK_VBDEN_HSCVS"
            	);
            	$db->commit();
            }
		}catch(Exception $ex){
			//$ex->__toString();exit;
			$db->rollBack();
		}
		echo "<script>window.parent.document.frm.submit();</script>";
		exit;
	}
	/**
	 * Xem nội dung bút phê
	 */
	function viewbutpheAction(){
		global $auth;
		$user = $auth->getIdentity();
		
		$this->_helper->layout->disableLayout();
		
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$idhscv = $param["id"];
		$year = QLVBDHCommon::getYear();
		
		//Tạo đối tượng
		$butphe = new butpheModel($year);
		
		$this->view->data = $butphe->SelectByIdHSCV($idhscv);
		
	}
	/**
	 * Chuyển bút phê
	 */
	function sendbutpheAction(){
		$this->_helper->layout->disableLayout();
		
		global $auth;
		$user = $auth->getIdentity();
		
		//parameter
		$this->parameter = $this->getRequest()->getParams();
		$this->view->wf_id_t = $this->parameter["wf_id_t"];
		$this->view->ID_HSCV = $this->parameter["id"];
		$this->view->year = $this->parameter["year"];
		Zend_Registry::set("year",$this->view->year);
	}
	/**
	 * Chuyển lưu trữ
	 */
	function zipAction(){
		$this->_helper->layout->disableLayout();
		
		global $auth;
		$user = $auth->getIdentity();
				
		//parameter
		$this->parameter = $this->getRequest()->getParams();
		$this->view->wf_id_t = $this->parameter["wf_id_t"];
		$this->view->ID_HSCV = $this->parameter["id"];
		$this->view->year = $this->parameter["year"];
		$this->view->code = $this->parameter["code"];
		Zend_Registry::set("year",$this->view->year);
		$this->view->processinfo = WFEngine::GetCurrentTransitionInfoByIdHscv($this->view->ID_HSCV);
	}
	/**
	 * Chuyển lưu trữ
	 */
	function savezipAction(){
		global $auth;
		global $db;
		$user = $auth->getIdentity();
		
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$idhscv = $param["id"];
		$wf_id_t = $param["wf_id_t"];
		$wf_nexttransition = $param["wf_nexttransition"];
		$wf_nextuser = $param["wf_nextuser"];
		$wf_name_user = $param["wf_name_user"];
		$wf_hanxuly_user = $param["wf_hanxuly_user"];
		$year = QLVBDHCommon::getYear();
		$thumuc = $param["THUMUC"];
		Zend_Registry::set("year",$year);
	
		$db->beginTransaction();
		try{
			$db->update("WF_PROCESSITEMS_".$year,array("IS_FINISH"=>1),"ID_O = ".$idhscv);
			//send next user
			if(WFEngine::SendNextUserByObjectId2($idhscv,$wf_nexttransition,$user->ID_U,$wf_nextuser,WFEngine::$WFTYPE_USER,$wf_name_user,$wf_hanxuly_user)==1){
				$db->commit();
			}else{
				$db->rollBack();
			}
		}catch(Exception $ex){
			echo $ex->__toString();
			$db->rollBack();
		}
		echo "<script>window.parent.document.frm.submit();</script>";exit;
	}
	public function wayAction(){
    	$this->_helper->layout->disableLayout();
		
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$idhscv = $param["id"];
		$year = QLVBDHCommon::getYear();
		$type = $param["type"];
		$code = $param["code"];
		//var_dump($param);
		//Tao object
		$idobject = 0;
		$way = array();
		if($type==1){
			$lcvbd = new vbd_dongluanchuyenModel($year);
			$vbden = new vbdenModel($year);
			$idobject = $vbden->findByHscv($idhscv);
			$idobject = $idobject['ID_VBD'];
			$way = $lcvbd->way($idobject);
		}
		
		$this->view->sendprocess = WFEngine::GetProcessLogByObjectId($idhscv);
		$this->view->ID_HSCV = $idhscv;
		$this->view->type = $type;
		$this->view->year = QLVBDHCommon::getYear();
		$this->view->way = $way;
		$this->view->code = $code;
		$this->view->idobject = $idobject;
		//kiem tra dieu kien truy cap
		$isreadonly = $this->_request->getParam('isreadonly');
		if(!$isreadonly)
			$isreadonly = 0;
		$isCapnhat = 1;
		if(hosocongviecModel::isLuutru($idhscv,$year) == true || $isreadonly == 1){
			$isCapnhat = 0;
			}else{$isCapnhat = 1; }
		
		$this->view->isCapnhat = $isCapnhat;
    }
	function sendAction(){
		$this->_helper->layout->disableLayout();
		
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$idhscv = $param["id"];
		$year = QLVBDHCommon::getYear();
		$type = $param["type"];
		$code = $param["code"];
		$idvbd = $param["idvbd"];
		
		$this->view->ID_HSCV = $idhscv;
		$this->view->year = $year;
		$this->view->type = $type;
		$this->view->code = $code;
		$this->view->idvbd = $idvbd;
	}
	function savesendAction(){
		global $auth;
		$user = $auth->getIdentity();
		
		$param = $this->getRequest()->getParams();
		$idhscv = $param["id"];
		$year = QLVBDHCommon::getYear();
		$type = $param["type"];
		$code = $param["code"];
		$idvbd = $param["idvbd"];
		//echo $idvbd;
		//Văn bản đến
		if($type==1){
			if($idvbd==0){
			//Lấy ID văn bản đến từ idhscv
			$vbden = new vbdenModel($year);
			$lc = new vbd_dongluanchuyenModel($year);
			$idvbd = $vbden->findByHscv($idhscv);
			$idvbd = $idvbd['ID_VBD'];
			$lc->send($idvbd,$param['ID_U'],$param['NOIDUNG'],$user->ID_U);
			}else{
				$lc = new vbd_dongluanchuyenModel($year);
			//Thêm mới vào dòng luân chuyển
			$lc->send($idvbd,$param['ID_U'],$param['NOIDUNG'],$user->ID_U);
			}
		}else if($type==2){//Văn bản đi
			
		}
		//var_dump($this->_request->getParams());
		//echo $idvbd;
		if($code=="vbd"){
			echo "<script>window.parent.loadDivFromUrl('groupcontent".$idvbd."','/vbden/vbden/way/id_vbd/".$idvbd."/id/".$idhscv."/year/".$year."/type/".$type."/code/vbd',1);</script>";
		}else{
			echo "<script>window.parent.loadDivFromUrl('groupcontent".$idhscv."','/hscv/hscv/way/id/".$idhscv."/year/".$year."/type/".$type."',1);</script>";
		}
		exit;
		
	}
	function thuhoiAction(){
		$this->_helper->layout->disableLayout();
		$this->parameter = $this->getRequest()->getParams();
		$this->view->current = WFEngine::GetCurrentTransitionInfoByIdHscv($this->parameter["id"]);
		$this->view->ID_HSCV = $this->parameter["id"];
	}
	function savethuhoiAction(){
		global $auth;
        global $db;
		$user = $auth->getIdentity();
		$param = $this->getRequest()->getParams();
		
		$hscv = new hosocongviecModel();
		$ok = $hscv->rollback($param['id'],$user->ID_U);
		if($ok==1){
			$hscv->update(array("ID_THUMUC"=>1),"ID_HSCV=".(int)$param['id']);
			$where = 'ID_HSCV='.$param['id'];
            $db->update(QLVBDHCommon::Table('motcua_hoso'),
						array("KHONGXULY"=>0             
		           ),$where); 
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('Đã thu hồi công việc thành công.');window.parent.document.frm.submit();</script>";
		}else if($ok==2){
			$hscv->getDefaultAdapter()->delete(QLVBDHCommon::Table("VBD_FK_VBDEN_HSCVS"),"ID_HSCV=".(int)$param['id']);
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('Đã thu hồi công việc thành công...');window.parent.document.frm.submit();</script>";
		}else{
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('Thu hồi công việc không thành công.');window.parent.document.frm.submit();</script>";
		}
		exit;
	}
	function bosungAction(){
		$this->_helper->layout->disableLayout();
		$hscv = new hosocongviecModel();
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$idhscv = $param["id"];
		$year = QLVBDHCommon::getYear();
		$this->view->data = $hscv->bosung($idhscv);
		$this->view->bs=$hscv->getIsbosung($idhscv);
		$this->view->idhscv=$idhscv;
	}
	function tofolderAction(){
		$this->_helper->layout->disableLayout();
		$this->parameter = $this->getRequest()->getParams();
		$this->view->ID_HSCV = $this->parameter["id"];
		$thumuc = array();
		QLVBDHCommon::GetTree(&$thumuc,"HSCV_THUMUC","ID_THUMUC","ID_THUMUC_CHA",1,1);
		$this->view->thumuc = $thumuc;
		if($this->parameter["THUMUC"]>1){
			$hscv = new hosocongviecModel();
			$hscv->getDefaultAdapter()->update(QLVBDHCommon::Table("HSCV_HOSOCONGVIEC"),array("ID_THUMUC"=>$this->parameter["THUMUC"]),"ID_HSCV=".(int)$this->parameter["id"]);
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('Đã lưu trữ thành công.');window.parent.document.frm.submit();</script>";
			exit;
		}
	}
	
	//action chuyen lai , khi vua moi them moi
	function chuyenlaiAction(){
		$this->_helper->layout->disableLayout();
		$params = $this->_request->getParams();
		$this->view->id = $params['id'];
		$this->view->wf_id_t = $params['wf_id_t'];
		
		//exit;
	}
	//action chuyen lai , khi vua moi them moi
	function chuyenlaivtbpAction(){
		$this->_helper->layout->disableLayout();
		$params = $this->_request->getParams();
		$this->view->id = $params['id'];
		//exit;
	}
	//luu chuyen lai sau khi them moi, can than !!! -> xoa ho so cong viec cu
	function savechuyenlaiAction(){
		$this->_helper->layout->disableLayout();
		$params = $this->_request->getParams();
		$id_u =Zend_Registry::get('auth')->getIdentity()->ID_U;
		$id = $params['id'];
		$wf_id_t = $params['wf_id_t'];
		$this->view->id = $id;
		$this->view->wf_id_t = $wf_id_t;
		$year = QLVBDHCommon::getYear();
		//lay thong tin ve ho so cu
		$old_data = hosocongviecModel::getDetail($year,$id);
		$objhscv = new hosocongviecModel();
		
		//doi tuong db adapter
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		//Tro lai trang thai truoc do
		if($re = WFEngine::RollBack($id,$id_u,true)!=0){
			
			//chuyen lai cho nguoi khac
			$name = $old_data["NAME"];
		 	$userceceive = $params["wf_nextuser"];
		 	$noidung = $params["wf_name_user"];
		 	$hanxuly = $params["wf_hanxuly_user"];
			$next_transition_id = $params["wf_nexttransition"];
			
		 	if($i =WFEngine::SendNextUserByObjectId($id,$next_transition_id,$id_u,$userceceive,$noidung,$hanxuly)>0){
		 		//cap nhat lai 
		 		$extra = UsersModel::getEmloyeeNameByIdUser($userceceive);
		 		$dbAdapter->update(QLVBDHCommon::Table("HSCV_HOSOCONGVIEC"),array("EXTRA"=>$extra),"ID_HSCV=".$id);
		 	}
		}
		//doan js refresh lai trang danh sach van ban vua xu ly
		echo "<script>window.parent.document.frm.submit();</script>";
		exit;
	}
	//luu chuyen lai sau khi them moi, can than !!! -> xoa ho so cong viec cu
	function savechuyenlaivtbpAction(){
		$this->_helper->layout->disableLayout();
		$params = $this->_request->getParams();
		$id = $params['id'];
		$lastpl = WFEngine::GetCurrentTransitionInfoByIdHscv($id);
		$id_u = $lastpl["ID_U_NC"];
		$wf_id_t = $params['wf_id_t'];
		$this->view->id = $id;
		$this->view->wf_id_t = $wf_id_t;
		$year = QLVBDHCommon::getYear();
		//lay thong tin ve ho so cu
		$old_data = hosocongviecModel::getDetail($year,$id);
		$objhscv = new hosocongviecModel();
		
		//doi tuong db adapter
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		//echo $id_u;exit;
		//Tro lai trang thai truoc do
		if($re = WFEngine::RollBack($id,$id_u,true)!=0){
			
			//chuyen lai cho nguoi khac
			$name = $old_data["NAME"];
		 	$userceceive = $params["wf_nextuser"];
		 	$noidung = $params["wf_name_user"];
		 	$hanxuly = $params["wf_hanxuly_user"];
			$next_transition_id = $params["wf_nexttransition"];
			
		 	if($i =WFEngine::SendNextUserByObjectId($id,$next_transition_id,$id_u,$userceceive,$noidung,$hanxuly)>0){
		 		//cap nhat lai 
		 		$extra = UsersModel::getEmloyeeNameByIdUser($userceceive);
		 		$dbAdapter->update(QLVBDHCommon::Table("HSCV_HOSOCONGVIEC"),array("EXTRA"=>$extra),"ID_HSCV=".$id);
		 	}
		}
		//doan js refresh lai trang danh sach van ban vua xu ly
		echo "<script>window.parent.document.frm.submit();</script>";
		exit;
	}
	function viewresultAction(){
		global $auth;
		global $db;
		$user = $auth->getIdentity();
		
		$this->_helper->layout->disableLayout();
		
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$idhscv = $param["id"];
		
		//Tạo đối tượng
		$sql = "
			SELECT TRICHYEU,SOKYHIEU,NGAYBANHANH,concat(emp.FIRSTNAME , ' ' , emp.LASTNAME) as EMPNAME FROM
				".QLVBDHCommon::Table("VBDI_VANBANDI")." vbdi
				inner join QTHT_USERS u on u.ID_U = vbdi.NGUOIKY
				inner join QTHT_EMPLOYEES emp on u.ID_EMP = emp.ID_EMP
			WHERE ID_HSCV = ?
		";
		$r = $db->query($sql,$idhscv);
		$this->view->data = $r->fetchAll();
	}
	function giahanAction(){
		global $auth;
		global $db;
		$user = $auth->getIdentity();
		$param = $this->getRequest()->getParams();
		//echo "alert('".$param['HANXULY']."');";
		if($param['HANXULY']!=""){
			$db->update(QLVBDHCommon::Table("WF_PROCESSLOGS"),array("HANXULY"=>$param["HANXULY"]),"ID_PL=".(int)$param["ID_PL"]);
		}
		exit;
	}
	function deleteAction(){
		global $auth;
		global $db;
		$user = $auth->getIdentity();
		$this->_helper->layout->disableLayout();
		$param = $this->getRequest()->getParams();
		$idhscv = $param["id_hscv"];
		$type = $param["type"];
		$hscv = new hosocongviecModel();
		$hscv->deletehscv($idhscv,$user->ID_U,$type);
		$this->_redirect("/hscv/hscv/list/code/old");
		exit;
	}
	function deletevtbpAction(){
		global $auth;
		global $db;
		$user = $auth->getIdentity();
		$this->_helper->layout->disableLayout();
		$param = $this->getRequest()->getParams();
		$idhscv = $param["id_hscv"];
		$hscv = new hosocongviecModel();
		$actid = ResourceUserModel::getActionByUrl('hscv','hscv','deletevtbp');
		if(ResourceUserModel::isAcionAlowed($user->USERNAME,$actid[0])){
			if(WFEngine::CanChuyenLaiForVTBP($idhscv)){
				//echo "aa";exit;
				$hscv->deletehscvforvtbp($idhscv);
			}
		}
		$this->_redirect("/hscv/hscv/list/code/old");
		exit;
	}
	function listbosungAction(){
	$this->view->start = (float) array_sum(explode(' ',microtime())); 
		global $auth;
		$user = $auth->getIdentity();
		
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$config = Zend_Registry::get('config');
		
		//tinh chỉnh param
		if($param['NGAY_BD']!=""){
			$ngaybd = $param['NGAY_BD']."/".QLVBDHCommon::getYear();
			$ngaybd = implode("-",array_reverse(explode("/",$ngaybd)));
		}
		if($param['NGAY_KT']!=""){
			$ngaykt = $param['NGAY_KT']."/".QLVBDHCommon::getYear();
			$ngaykt = implode("-",array_reverse(explode("/",$ngaykt)));
		}
		$realyear = QLVBDHCommon::getYear();

		$id_thumuc = $param["id_thumuc"];
		$id_thumuc = $id_thumuc==""?1:$id_thumuc;
		//check quyen vao thu muc neu thu muc >1
		if($id_thumuc>1){
			if(!ThuMucModel::CheckPermission($user->ID_U,$id_thumuc)){
				$this->_redirect("/default/error/error?control=hscv&mod=hscv&id=ERR11001001");
			}
		}
		$ID_LOAIHSCV = $param['ID_LOAIHSCV'];
		$ID_LV_MC = $param['ID_LV_MC'];
		$ID_PHUONG = $param['ID_PHUONG'];
		$NAME = $param['NAMECV'];
		$TENTOCHUCCANHAN=$param['NAMECD'];
		$MAHOSO=$param['MABIENNHAN'];
		$page = $param['page'];
		$limit = $param['limit1'];
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		$TRANGTHAI = $param['TRANGTHAI'];
		$scope = array();
		if($param['SCOPE']){
			$scope = $param['SCOPE'];
		}
		
		$parameter = array(
			"ID_THUMUC"=>$id_thumuc,
			"ID_LOAIHSCV"=>$ID_LOAIHSCV,
			"NGAY_BD"=>$ngaybd,
			"NGAY_KT"=>$ngaykt,
			"TRANGTHAI"=>$TRANGTHAI,
			"ID_U"=>$user->ID_U,
			"ID_G"=>$user->ID_G,
			"ID_DEP"=>$user->ID_DEP,
			"NAME"=>$NAME,
			"TENTOCHUCCANHAN"=>$TENTOCHUCCANHAN,
			"MAHOSO"=>$MAHOSO,
			"SCOPE"=>$scope,
			"CODE"=>$param['code'],
			"ID_LV_MC"=>$ID_LV_MC,
			"ID_PHUONG"=>$ID_PHUONG
		);
		
		//Tạo đối tượng
		$hscv = new bosunghosoModel();
		$hscvcount = $hscv->Count($parameter);
		if(($page-1)*$limit==$hscvcount && $hscvcount>0)$page--;
		//Lấy dữ liệu
		$this->view->loaihscv = WFEngine::GetLoaiCongViecFromUser($user->ID_U);
		$this->view->data = $hscv->SelectAll($parameter,($page-1)*$limit,$limit,"");
		$this->view->realyear = $realyear;
		$this->view->ID_LOAIHSCV = $ID_LOAIHSCV;
		$this->view->ID_LV_MC = $ID_LV_MC;
		$this->view->ID_PHUONG = $ID_PHUONG;
		$this->view->NAME = $NAME;
		$this->view->TENTOCHUCCANHAN=$TENTOCHUCCANHAN;
		$this->view->MAHOSO = $MAHOSO;
		$this->view->NGAY_BD = $param['NGAY_BD'];
		$this->view->NGAY_KT = $param['NGAY_KT'];
		$this->view->NAME = $NAME;
		$this->view->TRANGTHAI = $TRANGTHAI;
		$this->view->datatrangthai = WFEngine::GetActivityFromLoaiCV($ID_LOAIHSCV,$user->ID_U);
		$this->view->id_thumuc = $id_thumuc;
		$this->view->SCOPE = $scope;
		$this->view->user = $user;
		$this->view->code = $param['code'];
		$this->view->idhscv = $param['idhscv'];
		
		//Create button
		if($id_thumuc<2){
			$createarr = WFEngine::GetCreateProcessButtonFromLoaiCV($ID_LOAIHSCV,$user->ID_U);
			if(count($createarr)>0){
				QLVBDHButton::AddButton($createarr["NAME"],"","AddNewButton","CreateButtonClick(\"".$createarr["LINK"]."/type/$ID_LOAIHSCV/wf_id_t/".$createarr["ID_T"]."/year/".$realyear."\")");
			}
			
			$createarr = WFEngine::GetCreateProcessButtonFromLoaiCV($ID_LOAIHSCV,$user->ID_U);
			if(count($createarr)>0){
				QLVBDHButton::AddButton($createarr["TENTOCHUCCANHAN"],"","AddNewButton","CreateButtonClick(\"".$createarr["LINK"]."/type/$ID_LOAIHSCV/wf_id_t/".$createarr["ID_T"]."/year/".$realyear."\")");
			}
			$createarr = WFEngine::GetCreateProcessButtonFromLoaiCV($ID_LOAIHSCV,$user->ID_U);
			if(count($createarr)>0){
				QLVBDHButton::AddButton($createarr["MAHOSO"],"","AddNewButton","CreateButtonClick(\"".$createarr["LINK"]."/type/$ID_LOAIHSCV/wf_id_t/".$createarr["ID_T"]."/year/".$realyear."\")");
			}
		}
		//page
		$this->view->title = "Hồ sơ công việc";
		if(strtoupper($param['code'])=='OLD'){
			$this->view->subtitle = "danh sách đã xử lý";
		}else if(strtoupper($param['code'])=='PRE'){
			$this->view->subtitle = "danh sách vừa xử lý";
		}else if(strtoupper($param['code'])=='ZIP'){
			$this->view->subtitle = "danh sách lưu trữ";
		}else if(strtoupper($param['code'])=='PHOIHOP'){
			$this->view->subtitle = "danh sách phối hợp";
		}else{
			$this->view->subtitle = "<font color=red>danh sách chờ bổ sung</font>";
		}
		$this->view->page = $page;
		$this->view->limit = $limit;
		
		$thumuc = new ThuMucModel();
		$this->view->showPage = QLVBDHCommon::PaginatorWithAction($hscvcount,10,$limit,"frm",$page,"/hscv/hscv/list/id_thumuc/".$id_thumuc."/code/".$param['code']);
		//$this->view->thumuc = $thumuc->SelectAll(($page-1)*$limit,$limit,4);
		$this->view->thumuc = ThuMucModel::GetTreeByPermission($user->ID_U);
		$this->view->vbden = new vbdenModel(QLVBDHCommon::getYear());
		$this->view->linhvuc = new linhvucmotcuaModel();
		$this->view->linhvuc = $this->view->linhvuc->SelectAll();
		$this->view->phuong = new DanhMucPhuongModel();
		$this->view->phuong = $this->view->phuong->SelectAll(0,0,"TENPHUONG");
	
	}
	function updatedaxemphoihopAction(){
		$year = QLVBDHCommon::getYear();
		//$user = $auth->getIdentity();
		$params = $this->getRequest()->getParams();
		$id_u = $params["id_u"];
		$id_hscv = $params["id_hscv"];
		PhoiHopModel::updateDaXem($year,$id_u,$id_hscv);
		exit;
	}
    function updatedaxembosungAction(){
		$params = $this->getRequest()->getParams();	
		$id_yeucau = $params["id_yeucau"];
		$id_hscv = $params["id_hscv"];
		//echo $id_yeucau;
		if($id_yeucau)
			bosunghosoModel::CapnhatVuabosungDaxem($id_yeucau );
		if($id_hscv){
			$db = Zend_Db_Table::getDefaultAdapter();
			$db->update(QLVBDHCommon::Table("hscv_phieu_yeucau_bosung"),array("DA_XEM_HDBS"=>1),"ID_HSCV=".(int)$id_hscv);
		}

		exit;
	}
	function thutucformAction(){
		$this->_helper->layout->disableLayout();
		$hscv = new hosocongviecModel();
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$id = $param["idHSCV"];
		$this->view->data = $hscv->loadtenthutuc($id);
		
		//var_dump($this->view->data);
	}
	function phanquyennhapketqua(){}
	function multisendAction(){

global $auth;
		$user = $auth->getIdentity();
		
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$config = Zend_Registry::get('config');
		
		//tinh chỉnh param
		if($param['NGAY_BD']!=""){
			$ngaybd = $param['NGAY_BD']."/".QLVBDHCommon::getYear();
			$ngaybd = implode("-",array_reverse(explode("/",$ngaybd)));
		}
		if($param['NGAY_KT']!=""){
			$ngaykt = $param['NGAY_KT']."/".QLVBDHCommon::getYear();
			$ngaykt = implode("-",array_reverse(explode("/",$ngaykt)));
		}
		$realyear = QLVBDHCommon::getYear();

		$id_thumuc = $param["id_thumuc"];
		$id_thumuc = $id_thumuc==""?1:$id_thumuc;
		//check quyen vao thu muc neu thu muc >1
		if($id_thumuc>1){
			if(!ThuMucModel::CheckPermission($user->ID_U,$id_thumuc)){
				$this->_redirect("/default/error/error?control=hscv&mod=hscv&id=ERR11001001");
			}
		}
		$ID_LOAIHSCV = $param['ID_LOAIHSCV'];
		$ID_LV_MC = $param['ID_LV_MC'];
		$ID_PHUONG = $param['ID_PHUONG'];
		$NAME = $param['NAMECV'];
		$TENTOCHUCCANHAN=$param['NAMECD'];
		$MAHOSO=$param['MABIENNHAN'];
		$TRANGTHAI = $param['TRANGTHAI'];
		$scope = array();
		if($param['SCOPE']){
			$scope = $param['SCOPE'];
		}
		if($param['SCOPE']){
			$scope = $param['SCOPE'];
		}
		
		$parameter = array(
			"ID_THUMUC"=>$id_thumuc,
			"ID_LOAIHSCV"=>$ID_LOAIHSCV,
			"NGAY_BD"=>$ngaybd,
			"NGAY_KT"=>$ngaykt,
			"TRANGTHAI"=>$TRANGTHAI,
			"ID_U"=>$user->ID_U,
			"ID_G"=>$user->ID_G,
			"ID_DEP"=>$user->ID_DEP,
			"NAME"=>$NAME,
			"TENTOCHUCCANHAN"=>$TENTOCHUCCANHAN,
			"MAHOSO"=>$MAHOSO,
			"SCOPE"=>$scope,
			"CODE"=>$param['code'],
			"ID_LV_MC"=>$ID_LV_MC,
			"ID_PHUONG"=>$ID_PHUONG
		);
		
		//Tạo đối tượng
		$hscv = new hosocongviecModel();

		//Lấy dữ liệu
		$this->view->loaihscv = WFEngine::GetLoaiCongViecFromUser($user->ID_U);
		$this->view->data = $hscv->SelectAll($parameter,0,0,"");
		$this->view->realyear = $realyear;
		$this->view->ID_LOAIHSCV = $ID_LOAIHSCV;
		$this->view->ID_LV_MC = $ID_LV_MC;
		$this->view->ID_PHUONG = $ID_PHUONG;
		$this->view->NAME = $NAME;
		$this->view->TENTOCHUCCANHAN=$TENTOCHUCCANHAN;
		$this->view->MAHOSO = $MAHOSO;
		$this->view->NGAY_BD = $param['NGAY_BD'];
		$this->view->NGAY_KT = $param['NGAY_KT'];
		$this->view->NAME = $NAME;
		$this->view->TRANGTHAI = $TRANGTHAI;
		$this->view->datatrangthai = WFEngine::GetActivityFromLoaiCV($ID_LOAIHSCV,$user->ID_U);
		$this->view->id_thumuc = $id_thumuc;
		$this->view->SCOPE = $scope;
		$this->view->user = $user;
		$this->view->code = $param['code'];
		$this->view->idhscv = $param['idhscv'];
		$this->view->ID_U_RECEIVE = 0+$param['ID_U_RECEIVE'];
		//page
		$this->view->title = "Hồ sơ công việc";
		$this->view->subtitle = "Chuyển nhiều";
		
		$thumuc = new ThuMucModel();
		$this->view->thumuc = ThuMucModel::GetTreeByPermission($user->ID_U);
		$this->view->vbden = new vbdenModel(QLVBDHCommon::getYear());
		$this->view->linhvuc = new linhvucmotcuaModel();
		$this->view->linhvuc = $this->view->linhvuc->SelectAll();
		$this->view->phuong = new DanhMucPhuongModel();
		$this->view->phuong = $this->view->phuong->SelectAll(0,0,"TENPHUONG");
		$this->view->ID_U = $user->ID_U;
		$this->view->ID_DEP = 0+$param['ID_DEP'];

		QLVBDHButton::AddButton("Chuyển","","SaveButton","CreateButtonClick()");
	}
	function multisendsaveAction(){
		global $auth;
		$user = $auth->getIdentity();
		$param = $this->getRequest()->getParams();
		//var_dump($param);exit;
		//array(21) { ["module"]=> string(4) "hscv" ["controller"]=> string(4) "hscv" ["action"]=> string(13) "multisendsave" ["ID_LV_MC"]=> string(1) "0" ["ID_PHUONG"]=> string(1) "0" ["NAMECV"]=> string(0) "" ["SCOPE"]=> array(3) { [0]=> string(1) "0" [1]=> string(1) "0" [2]=> string(1) "0" } ["NAMECD"]=> string(0) "" ["MABIENNHAN"]=> string(0) "" ["NGAY_BD"]=> string(0) "" ["NGAY_KT"]=> string(0) "" ["ID_DEP"]=> string(2) "25" ["ID_U_RECEIVE"]=> string(2) "71" ["ID_T"]=> array(2) { [0]=> string(3) "863" [1]=> string(1) "0" } ["temp_HANXULY"]=> array(2) { [0]=> string(1) "0" [1]=> string(1) "0" } ["HANXULY"]=> array(2) { [0]=> string(0) "" [1]=> string(1) "0" } ["type_HANXULY2278"]=> string(1) "8" ["type_real_HANXULY2278"]=> string(1) "8" ["ID_HSCV"]=> array(2) { [0]=> string(4) "2278" [1]=> string(4) "2268" } ["type_HANXULY2268"]=> string(1) "8" ["type_real_HANXULY2268"]=> string(1) "8" }
		for($i=0;$i<count($param['ID_HSCV']);$i++){
			if($param['ID_T_'.$param['ID_HSCV'][$i]]>0){
				if(WFEngine::HaveSendAbleByTransition($param['ID_T_'.$param['ID_HSCV'][$i]],$param['ID_U_RECEIVE'])){
					WFEngine::SendNextUserByObjectId2($param['ID_HSCV'][$i],$param['ID_T_'.$param['ID_HSCV'][$i]],$user->ID_U,$param['ID_U_RECEIVE'],WFEngine::$WFTYPE_USER,"",$param['HANXULY'][$i]);
				}
			}
		}
		$this->_redirect("/hscv/hscv/multisend");
	}
	function listmonitorAction(){
		$this->view->start = (float) array_sum(explode(' ',microtime())); 
		global $auth;
		$user = $auth->getIdentity();
		
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$config = Zend_Registry::get('config');
		
		//tinh chỉnh param
		if($param['NGAY_BD']!=""){
			$ngaybd = $param['NGAY_BD']."/".QLVBDHCommon::getYear();
			$ngaybd = implode("-",array_reverse(explode("/",$ngaybd)));
		}
		if($param['NGAY_KT']!=""){
			$ngaykt = $param['NGAY_KT']."/".QLVBDHCommon::getYear();
			$ngaykt = implode("-",array_reverse(explode("/",$ngaykt)));
		}
		$realyear = QLVBDHCommon::getYear();

		$id_thumuc = $param["id_thumuc"];
		$id_thumuc = $id_thumuc==""?1:$id_thumuc;
		//check quyen vao thu muc neu thu muc >1
		if($id_thumuc>1){
			if(!ThuMucModel::CheckPermission($user->ID_U,$id_thumuc)){
				$this->_redirect("/default/error/error?control=hscv&mod=hscv&id=ERR11001001");
			}
		}
		$ID_LOAIHSCV = $param['ID_LOAIHSCV'];
		$ID_LV_MC = $param['ID_LV_MC'];
		$ID_PHUONG = $param['ID_PHUONG'];
		$NAME = $param['NAMECV'];
		$TENTOCHUCCANHAN=$param['NAMECD'];
		$MAHOSO=$param['MABIENNHAN'];
		$page = $param['page'];
		$limit = $param['limit1'];
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		$TRANGTHAI = $param['TRANGTHAI'];
		$scope = array();
		if($param['SCOPE']){
			$scope = $param['SCOPE'];
		}
		
		$parameter = array(
			"ID_THUMUC"=>$id_thumuc,
			"ID_LOAIHSCV"=>$ID_LOAIHSCV,
			"NGAY_BD"=>$ngaybd,
			"NGAY_KT"=>$ngaykt,
			"TRANGTHAI"=>$TRANGTHAI,
			"ID_U"=>$user->ID_U,
			"ID_G"=>$user->ID_G,
			"ID_DEP"=>$user->ID_DEP,
			"NAME"=>$NAME,
			"TENTOCHUCCANHAN"=>$TENTOCHUCCANHAN,
			"MAHOSO"=>$MAHOSO,
			"SCOPE"=>$scope,
			"CODE"=>$param['code'],
			"ID_LV_MC"=>$ID_LV_MC,
			"ID_PHUONG"=>$ID_PHUONG
		);
		
		//Tạo đối tượng
		$hscv = new hosocongviecModel();		
		$hscvcount = $hscv->CountMonitor($parameter);
		if(($page-1)*$limit==$hscvcount && $hscvcount>0)$page--;
		//Lấy dữ liệu
		$this->view->loaihscv = WFEngine::GetLoaiCongViecFromUser($user->ID_U);
		$this->view->data = $hscv->SelectAllMonitordefault($parameter,($page-1)*$limit,$limit,"");
		$this->view->realyear = $realyear;
		$this->view->ID_LOAIHSCV = $ID_LOAIHSCV;
		$this->view->ID_LV_MC = $ID_LV_MC;
		$this->view->ID_PHUONG = $ID_PHUONG;
		$this->view->NAME = $NAME;
		$this->view->TENTOCHUCCANHAN=$TENTOCHUCCANHAN;
		$this->view->MAHOSO = $MAHOSO;
		$this->view->NGAY_BD = $param['NGAY_BD'];
		$this->view->NGAY_KT = $param['NGAY_KT'];
		$this->view->NAME = $NAME;
		$this->view->TRANGTHAI = $TRANGTHAI;
		$this->view->datatrangthai = WFEngine::GetActivityFromLoaiCV($ID_LOAIHSCV,$user->ID_U);
		$this->view->id_thumuc = $id_thumuc;
		$this->view->SCOPE = $scope;
		$this->view->user = $user;
		$this->view->code = $param['code'];
		$this->view->idhscv = $param['idhscv'];
		
		//Create button

		//page
		$this->view->title = "Hồ sơ công việc";
		$this->view->subtitle = "Theo dõi";
		$this->view->page = $page;
		$this->view->limit = $limit;
		
		$thumuc = new ThuMucModel();
		$this->view->showPage = QLVBDHCommon::PaginatorWithAction($hscvcount,10,$limit,"frm",$page,"/hscv/hscv/listmonitor");
		//$this->view->thumuc = $thumuc->SelectAll(($page-1)*$limit,$limit,4);
		$this->view->thumuc = ThuMucModel::GetTreeByPermission($user->ID_U);
		$this->view->vbden = new vbdenModel(QLVBDHCommon::getYear());
		$this->view->linhvuc = new linhvucmotcuaModel();
		$this->view->linhvuc = $this->view->linhvuc->SelectAll();
		$this->view->phuong = new DanhMucPhuongModel();
		$this->view->phuong = $this->view->phuong->SelectAll(0,0,"TENPHUONG");
	}
	function deletemonitorAction(){
		global $db;
		$param = $this->getRequest()->getParams();
		$db->update(QLVBDHCommon::Table('HSCV_HOSOCONGVIEC'),array("IS_KHONGTRAKIP"=>0),"ID_HSCV=".(int)$param['id']);
		$this->_redirect("/hscv/hscv/listmonitor");
	}

	function phuchoibosungAction(){
		
		global $auth;
		$user = $auth->getIdentity();
		$param = $this->getRequest()->getParams();
		
		if( !$param['id'] ){
			echo "alert(' Không thể thu hồi yêu cầu bổ sung thành công ');";
			exit;
		}
		
		//phuc hoi bo sung ho so sau cung

		$sql = "
			select ID_YEUCAU,HANXULY_CU,ID_PL_CURHAN from
			".QLVBDHCommon::Table('hscv_phieu_yeucau_bosung')."
			where ID_HSCV = ? and NGUOIBOSUNG is NULL 
			ORDER BY ID_YEUCAU DESC
		";
		///echo $sql;
		try{
			
			$db = Zend_Db_Table::getDefaultAdapter();
			$qr = $db->query($sql,array($param['id']));
			$row = $qr->fetch();
			if($row["ID_YEUCAU"]){
				$sql = " delete from  ". QLVBDHCommon::Table('hscv_phieu_yeucau_bosung') ."  where ID_YEUCAU = ". $row["ID_YEUCAU"];
				$stm = $db->prepare($sql);
				$stm->execute();

				$sql = "update ". QLVBDHCommon::Table('hscv_hosocongviec') ."  set IS_BOSUNG = 0 where ID_HSCV = ".$param['id'];
				$stm = $db->prepare($sql);
				$stm->execute();
				
				$sql = " UPDATE ". QLVBDHCommon::Table("wf_processlogs")   ."   set HANXULY = ? where ID_PL = ? ";
				$stm = $db->prepare($sql);
				$stm->execute(array($row["HANXULY_CU"],$row["ID_PL_CURHAN"]));
				
				echo "document.frm.submit();";
			}else{
				echo "alert(' Không thể thu hồi yêu cầu bổ sung thành công ');";
			}

		}catch(Exception $ex){
			echo "alert(' Không thể thu hồi yêu cầu bổ sung thành công ');";
		}
		exit;
	}
	function inphieubosunghosoAction(){
		$this->_helper->layout->disableLayout();
		$year = QLVBDHCommon::getYear();
		$hsmc = new HoSoMotCuaModel($year);
		$hscv = new hosocongviecModel();
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$idhscv = $param["id"];
		$this->view->data = $hsmc->getDetailhsmc($idhscv);
		$this->view->bs = $hscv->bosung($idhscv);
//		var_dump($this->view->data);
	}
	function inphieubosunghosowordAction(){
		$this->_helper->layout->disableLayout();
		$year = QLVBDHCommon::getYear();
		$hsmc = new HoSoMotCuaModel($year);
		$hscv = new hosocongviecModel();
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$idhscv = $param["id"];
		$this->view->data = $hsmc->getDetailhsmc($idhscv);
		$this->view->bs = $hscv->bosung($idhscv);
		header("Content-Type: application/msword; name='word'");
		header("Content-Disposition: attachment; filename=phieutrahoso.doc;");
		header("Pragma: no-cache");
		header("Expires: 0");

	}

}
