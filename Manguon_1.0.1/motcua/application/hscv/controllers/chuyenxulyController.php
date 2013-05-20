<?php
/**
 * @author trunglv
 * @version 1.0
 * @des Chuyen xu ly (chuyen ban hanh) ho so cong viec
 */
require_once ('Zend/Controller/Action.php');
require_once 'hscv/models/ToTrinhModel.php';
require_once 'hscv/models/ChuyenXuLyModel.php';
require_once 'hscv/models/VanBanDuThaoModel.php';
require_once 'hscv/models/hosocongviecModel.php';
include_once 'hscv/models/PhoiHopModel.php';
include_once 'hscv/models/HosoluutheodoiModel.php';
require_once('qtht/models/nguoidungModel.php');
require_once('motcua/models/motcua_hosoModel.php');
require_once 'vbden/models/vbdenModel.php';
require_once 'vbden/models/vbd_dongluanchuyenModel.php';
require_once('qtht/models/UsersModel.php');

class Hscv_chuyenxulyController extends Zend_Controller_Action {
	/**
	 * Ham khoi tao cho cac action
	 *
	 */
	function init(){
		//disable layout
		$this->_helper->layout->disableLayout();
	}
	/**
	 * action : index
	 * Hien form nhap lieu de chuyen xu ly
	 */
	function indexAction(){
		$auth = Zend_Registry::get('auth');
		$user = $auth->getIdentity();
		//parameter
		$this->parameter = $this->getRequest()->getParams();
		$idHSCV = $this->parameter["id"];
		$year = $this->parameter["year"];
		$code= $this->parameter["code"];
		$id_loaihscv = $this->parameter["type"];
//chuyen du lieu cho lop view
		//tao lop model to trinh
		$totrinhModel = new ToTrinhModel($year);
		//lay danh sach co to trinh tuong ung voi ho so cong viec
		$this->view->data = $totrinhModel->getListToTrinhByIdHSCV($this->parameter["id"]);
		$this->view->idHSCV = $idHSCV;
		$this->view->id_loaihscv = $id_loaihscv;
		$this->view->code = $code;
		$this->view->wf_id_t = $this->parameter["wf_id_t"]; //wf id transition
		$this->view->ID_HSCV = $idHSCV;
		$this->view->year = $year;
		if($code == "banhanh"){
			$this->view->isBanhanh = 1; // truong hop chuyen ban hanh van ban
			$this->view->canBanhanh = 1;
			$duthao = new VanBanDuThaoModel(QLVBDHCommon::getYear());
			if(count($duthao->getListByIdHSCV($idHSCV))<=0){
				$this->view->canBanhanh = 0;
			}

		}else{
			$this->view->isBanhanh = 0; // truong hop chuyen xu ly cong vien
		}
        if($code == "khongxl"){
			$this->view->iskhongxl=1;
		}
//Lay thong tin ve cac loai ho so (van ban den, soan thao van ban,ho so mot cua)
		$this->view->loaihs_des = ChuyenXuLyModel::getObjectByIdHSCV($idHSCV,$id_loaihscv,$year);
        $this->view->actioninfo = WFEngine::getTransitionInfo($this->view->wf_id_t);
	}
	/**
	 * action : save
	 * Lưu tờ trình và chuyển xử lý cho người tiếp theo (work flow)
	 */
	function saveAction(){
/**
 * mota :
 * soan thao van ban :
 *  - chuyen xu ly
 *  - chuyen ban hanh -> cap nhat lai trang thai cac du thao trong ho so con viec
 *
 * van ban den :
 * 	- chuyen xu ly
 *  - chuyen ban hanh du thao -> cap nhat lai trang thai cac du thao trong ho so con viec
 * ho so mot cua:
 *  - chuyen xu ly
 * luu to trinh cho cac lan chuyen ban hanh va chuyen xu ly
 */
		//lay bien $auth tu registry
		$auth = Zend_Registry::get('auth');
		//lay default db adapter
		$db = Zend_Db_Table::getDefaultAdapter();
		$user = $auth->getIdentity(); //lay thong tin cua user trong session
		//Lấy parameter
		$param = $this->getRequest()->getParams();

		$idhscv = $param["id"];
		//var_dump($idhscv );
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
		$iskhongxuly= $param["iskhongxuly"];
		//var_dump($iskhongxuly );exit;
		$year = $param["year"];
		// set nam trong registry
		Zend_Registry::set('year',$year);
		$db->beginTransaction();
		try{
			//send next user , neu thanh cong thi luu to trinh vao co so du lieu
			if( !is_array($param["wf_nextuser"]) &&
            	!is_array($param["wf_nextg"]) &&
            	!is_array($param["wf_nextdep"])
            ){
				if(WFEngine::SendNextUserByObjectId2($idhscv,$wf_nexttransition,$user->ID_U,$wf_nextuser,WFEngine::$WFTYPE_USER,$wf_name_user,$wf_hanxuly_user)==1){
					$isBanhanh = $param["isBanhanh"];
					if($isBanhanh == 1){
						//cap nhat lai trang thai cua cac du thao van ban trong ho so con viec
						$VBDTModel = new VanBanDuThaoModel($year);
						$VBDTModel->updateTrangthaiByIdHSCV($idhscv,1);
						$VBDTModel->updateNguoiKyByIdHSCV($idhscv,$user->ID_U);
					}
					$type = (int)$param["type"];

					if(hosocongviecModel::isfinishhosocv($idhscv)){
						ChuyenXuLyModel::updatePCMTraHSMC($idhscv);
						$db->update(QLVBDHCommon::Table('HSCV_HOSOCONGVIEC'),array("IS_KHONGTRAKIP"=>0),"ID_HSCV=".(int)$idhscv);
					}

					$db->commit();
				}

            }else{
            	$vbd = new vbdenModel(QLVBDHCommon::getYear());
           		$vbden = $vbd->findByHscv($idhscv);
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
            	$type = (int)$param["type"];
				if(hosocongviecModel::isfinishhosocv($idhscv)){
				      ChuyenXuLyModel::updatePCMTraHSMC($idhscv);
				}
				$isBanhanh = $param["isBanhanh"];
           		if($isBanhanh == 1){
					//cap nhat ngay phong chuyen mon tra hs mot cua

					//cap nhat lai trang thai cua cac du thao van ban trong ho so con viec
					$VBDTModel = new VanBanDuThaoModel($year);
					$VBDTModel->updateTrangthaiByIdHSCV($idhscv,1);
					$VBDTModel->updateNguoiKyByIdHSCV($idhscv,$user->ID_U);
				}
				$db->commit();
            }
		}catch(Exception $ex){
			$db->rollBack();
		}

		//khong xu ly
		    try{
		        if($iskhongxuly==1){
					$where = 'ID_HSCV='.$idhscv;
					//$idkhongxulyModel= new motcua_hosoModel(qlvbdhCommon::GetYear());
					$db->update(QLVBDHCommon::Table('motcua_hoso'),
						array("KHONGXULY"=>1
		           ),$where);

				}
			}catch(Exception $ex){
		}
		//tra lai doan js de thu thi tai client
		if(hosocongviecModel::isfinishhosocv($idhscv)){
			$hsmc = new motcua_hosoModel(qlvbdhCommon::GetYear());
			$hoso = $hsmc->getHSMCByIdHSCV($idhscv);
			ChuyenXuLyModel::updatePCMTraHSMC($idhscv);
			$db->update(QLVBDHCommon::Table('HSCV_HOSOCONGVIEC'),array("IS_KHONGTRAKIP"=>0),"ID_HSCV=".(int)$idhscv);
			QLVBDHCommon::InsertHSMCService($hoso->MAHOSO,$hoso->TENTOCHUCCANHAN,$hoso->TRICHYEU,3,"Mời Ông/Bà đến nhận kết quả.",$hoso->DIENTHOAI);
			//nguyennd
			/*$log = WFEngine::GetProcessLogByObjectId($idhscv);
			foreach($log as $logitem){
				if($logitem['DATESEND']!="" && $logitem['HANXULY']>0 && $logitem['TRE']==""){
					$tre = QLVBDHCommon::getTreHan($logitem['DATESEND'],$logitem['HANXULY']);
					$db->update(QLVBDHCommon::Table("wf_processlogs"),array("TRE"=>$tre),"ID_PL=".$logitem['ID_PL']);
				}else if($logitem['HANXULY']==0){
					$db->update(QLVBDHCommon::Table("wf_processlogs"),array("TRE"=>0),"ID_PL=".$logitem['ID_PL']);
				}
			}*/
		}
		echo "<script>window.parent.document.frm.submit();</script>";

	//tra lai doan js de thu thi tai client
		if($param["type"]>=3){
			$actid = ResourceUserModel::getActionByUrl('motcua','MotCua','savetrahoso');
			$nguoidung = new nguoidungModel();
			$nguoidung = $nguoidung->FindById($param["wf_nextuser"]);
			try{
			if(wfengine::IsFinishTransition($wf_nexttransition)){
				$hsmc = new motcua_hosoModel(qlvbdhCommon::GetYear());
				$hoso = $hsmc->getHSMCByIdHSCV($idhscv);
				//QLVBDHCommon::InsertHSMCService($hoso->MAHOSO,$hoso->TENTOCHUCCANHAN,$hoso->TRICHYEU,3,"Mời Ông/Bà đến nhận kết quả.",$hoso->DIENTHOAI,null);
			}else{
                $hsmc = new motcua_hosoModel(qlvbdhCommon::GetYear());
				$hoso = $hsmc->getHSMCByIdHSCV($idhscv);

		        $newphong = new motcua_hosoModel();
		        $phong = $newphong->ws_getphong($nguoidung['ID_U']);
				QLVBDHCommon::InsertHSMCService($hoso->MAHOSO,$hoso->TENTOCHUCCANHAN,$hoso->TRICHYEU,1,"Đang xử lý",$hoso->DIENTHOAI,$phong);

			}
			}catch(Exception $ex){

			}
		}

		exit;
	}

	function banhanhAction(){
		$param = $this->_request->getParams();
		$wf_id_t = $param["wf_id_t"]; //wf id transition
		$idHSCV = $param["id"];
		$year = $param["year"];
		$id_loaihscv = $param["type"];
		$code = $param["code"];
		$this->_redirect("/hscv/chuyenxuly/index/code/".$code."/year/".$year."/wf_id_t/".$wf_id_t."/id/".$idHSCV."/type/".$id_loaihscv);
	}

	private function checkInputData($name,$active){

		$strurl='/default/error/error?control=chuyenxuly&mod=hscv&id=';
		$strerr = "";
		$strerr .= ValidateInputData::validateInput('text128_re',$name,'ERR11002001').",";
		$strerr .= ValidateInputData::validateInput('boolean',$active,"ERR11002005").",";
		if(strlen($strerr)!=2){
			$this->_redirect($strurl.$strerr);
		}
		return true;
	}
	function chuyenxulyAction(){
		$this->_helper->layout->disableLayout();

		global $auth;
		$user = $auth->getIdentity();
		$totrinhModel = new ToTrinhModel(QLVBDHCommon::getYear());
		//parameter
		$this->parameter = $this->getRequest()->getParams();
		$this->view->wf_id_t = $this->parameter["wf_id_t"];
		$this->view->ID_HSCV = $this->parameter["id"];
		$this->view->processinfo = WFEngine::GetCurrentTransitionInfoByIdHscv($this->view->ID_HSCV);
		$this->view->hanxuly = WFEngine::GetHanXuLy($this->view->wf_id_t);
		$this->view->data = $totrinhModel->getListToTrinhByIdHSCV($this->parameter["id"]);
	}
	function savechuyenxulyAction(){
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
		$iduph = array();
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
			PhoiHopModel::AddPhoiHop($iduph,$vbden['ID_VBDEN'],$user->ID_U);
		}
		if($parameter["isbanhanh"]==1){
			$db->update("WF_PROCESSITEMS_".QLVBDHCommon::getYear(),array("IS_FINISH"=>1),"ID_O = ".$id);
		}
		echo "<script>window.parent.document.frm.submit();</script>";
		//var_dump($idusend);
		exit;
	}
}

?>
