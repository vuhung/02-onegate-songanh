<?php
require_once ('Zend/Controller/Action.php');
require_once 'motcua/models/phieu_yeucau_bosungModel.php';
require_once 'motcua/models/motcua_hosoModel.php';
require_once 'hscv/models/hosocongviecModel.php';
require_once 'motcua/models/LoaiModel.php';
require_once 'motcua/models/dkwebModel.php';
require_once 'hscv/models/filedinhkemModel.php';
require_once 'motcua/models/motcua_hosoForm.php';
require_once 'motcua/models/motcua_nhangomModel.php';
require_once 'motcua/models/LoaiModel.php';
require_once 'motcua/models/MotCuaNhanGomModel.php';
require_once 'config/motcua.php';
require_once 'hscv/models/VanBanDuThaoModel.php';
require_once 'motcua/models/linhvucmotcuaModel.php';
require_once 'hscv/models/gen_tempModel.php';
require_once 'qtht/models/SoVanBanModel.php' ;
require_once 'qtht/models/DepartmentsModel.php';
require_once('qtht/models/adapter.php');
require_once 'motcua/models/motcua_thutuc_bosungModel.php';
require_once 'hscv/models/ThuMucModel.php';
require_once 'motcua/models/MotCuaModel.php';
class Motcua_motcuaController extends Zend_Controller_Action {
	function init(){
		$this->_helper->layout->disableLayout();
	}
	function inputbosungAction(){
		global $auth;
		$user = $auth->getIdentity();
		//parameter
		$this->parameter = $this->getRequest()->getParams();
		$this->view->wf_id_t = $this->parameter["wf_id_t"];
		$this->view->ID_HSCV = $this->parameter["id"];
		$this->view->year = $this->parameter["year"];
		$this->view->idHSCV = $this->parameter["id"];
		$this->view->id_loaihscv = $this->_request->getParam('type');
		//$this->view->thutucdanhans = motcua_nhangomModel::getThutucDanhanByHoso($this->view->idHSCV);
	}
	function saveyeucaubosungAction(){
		global $auth;
		global $db;
		$user = $auth->getIdentity();
		//Lấy parameter
		$param = $this->getRequest()->getParams();
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
		$wf_nexttransition = $param["wf_nexttransition"];
		//var_dump($param); exit;
		$idHSCV = $param["id"];
		$phieu_yc = new phieu_yeucau_bosung();
		$phieu_yc->_id_hscv = $idHSCV;
		$phieu_yc->_sophieu = $param['sophieu'];
		$phieu_yc->_ngay_yeucau = date('y-m-d h:m:s');
		$phieu_yc->_cacghichu = $param['ghichu'];
		$phieu_yc->_nguoiyeucau = $user->ID_U;
		//$data_wl = WFEngine::GetCurrentTransitionInfoByIdHscv($idHSCV);
		//Lay id_pl để tính hạn xử lý (là dòng luân chuyển để tính hạn xử lý)
		$sql = "
			SELECT
				pl.DATESEND,
				pl.HANXULY,
				pl.ID_PL,
				pl.ID_PI,
				pl.ID_T,
				wf_t.ISLAST,
				wf_t.END_AT
			FROM
				".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv
				inner join ".QLVBDHCommon::Table("WF_PROCESSLOGS")." pl on hscv.ID_PI = pl.ID_PI
				inner join wf_transitions wf_t on pl.ID_T = wf_t.ID_T
				inner join WF_ACTIVITIES ac on ac.ID_A = pl.ID_A_END
			WHERE
				hscv.ID_HSCV = ?  and  ( wf_t.END_AT > 0 or pl.HANXULY > 0 )
			ORDER BY
				pl.ID_PL DESC
		";
		$r = $db->query($sql,array($idHSCV));
		$data_wl =  $r->fetch();
		//var_dump($data_wl);
		//exit;
		$phieu_yc->_hanxuly_cu = $data_wl["HANXULY"];
		$phieu_yc->_id_pl_curhan = $data_wl["ID_PL"];
		/* Cập nhật dòng luân chuyển vừa tìm lại hạn xư lý bằng 0 để không còn tính trễ*/
		$sql = " UPDATE ". QLVBDHCommon::Table("wf_processlogs")   ."   set HANXULY = ? where ID_PL = ? ";
		$stm = $db->prepare($sql);
		$stm->execute( array(0,$data_wl["ID_PL"]) );
		$phieu_ycModel = new phieu_yeucau_bosungModel(qlvbdhCommon::GetYear());
		$phieu_ycModel->inserOne($phieu_yc);
		$db->update(qlvbdhCommon::Table("HSCV_HOSOCONGVIEC"),array("IS_BOSUNG"=>1),"ID_HSCV=".$idHSCV);
		$id_yeucau = $phieu_ycModel->getLastInsertId();
		/*
        foreach($param["DEL_THUTUC"] as $id_tailieu_nhan){
			motcua_thutuc_bosungModel::addFromThutucNhan($id_tailieu_nhan,$id_yeucau);
		}
		//cap nhat danh sach thu tuc moi can bo sung
		$arr_thutucmoi = $param["NEW_THUTUC_TEXT"];
		$arr_choicemoi = $param["NEW_THUTUC"];
		$stt_ttm = 0;
		foreach($arr_choicemoi as $ctt){
			//motcua_thutuc_bosungModel::addFromThutucNhan($id_tailieu_nhan,$id_yeucau);
			$params["TEN_THUTUC"] = $arr_thutucmoi[$stt_ttm];
			$params["ID_YEUCAU"] = $id_yeucau;
			motcua_thutuc_bosungModel::addNew($params);
			$stt_ttm++;
		}
		//echo
		*/
        //(WFEngine::SendNextUserByObjectId2($idHSCV,$wf_nexttransition,$user->ID_U,$wf_nextuser,WFEngine::$WFTYPE_USER,$wf_name_user,$wf_hanxuly_user));
		//var_dump($wf_nexttransition);
		//exit;
		//cap nhat trang thai cho bo sung
		$db->update(QLVBDHCommon::Table("wf_trangthaihosologs"),array("IS_CHOBOSUNG"=>1),"ID_HSCV=".$idHSCV." and ACTIVE=1" );
		$hsmc = new motcua_hosoModel(qlvbdhCommon::GetYear());
		$hoso = $hsmc->getHSMCByIdHSCV($idHSCV);

        //cap nhat trang thai tho   ng ke
        $db->update(QLVBDHCommon::Table("motcua_hoso"),array("IS_BOSUNG"=>1),"ID_HOSO=".(int)$idHSCV);
        QLVBDHCommon::InsertHSMCService($hoso->MAHOSO,$hoso->TENTOCHUCCANHAN,$hoso->TRICHYEU,2,$param['ghichu'],$hoso->DIENTHOAI,null);
		echo "<script>window.parent.document.frm.submit();</script>";
		exit;
	}
	function bosungAction(){
		global $auth;
		$user = $auth->getIdentity();
		//parameter
		$this->parameter = $this->getRequest()->getParams();
		$this->view->ID_HSCV = $this->parameter["id"];
		$this->view->idHSCV = $this->parameter["id"];
		$this->view->id_loaihscv = $this->_request->getParam('type');
		$phieu_ycModel = new phieu_yeucau_bosungModel(qlvbdhCommon::GetYear());
		$this->view->phieu_yc =  $phieu_ycModel->getNearPhieuYeuCauByIdHSCV($this->view->idHSCV);
	}
	function savebosungAction(){
		global $auth;
		global $db;
		$user = $auth->getIdentity();
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		$idHSCV = $param["id"];
		$id_yeucau = $param["id_yeucau"];
		$phieu_yc = new phieu_yeucau_bosung();
		$phieu_yc->_id_yeucau = $id_yeucau;
		$phieu_yc->_nguoibosung = $user->ID_U;
		$phieu_yc->_ngay_bosung = date('y-m-d h:m:s');
		$phieu_yc->_cacghichu = $param['pre_ghichu']."\n"."<font color=blue><b>".$user->FULLNAME.":</b>".$param['ghichu']."</font>";
		$phieu_ycModel = new phieu_yeucau_bosungModel(qlvbdhCommon::GetYear());
		$phieu_ycModel->updateWhenBoSung($phieu_yc);
		$db->update(qlvbdhCommon::Table("HSCV_HOSOCONGVIEC"),array("IS_BOSUNG"=>0),"ID_HSCV=".$idHSCV);
		echo "<script>window.parent.document.frm.submit();</script>";
		$hsmc = new motcua_hosoModel(qlvbdhCommon::GetYear());
		$hoso = $hsmc->getHSMCByIdHSCV($idHSCV);
		$id_uu= wfengine::GetCurrentTransitionInfoByIdHscv($idHSCV);
		$newphong = new motcua_hosoModel();
		$phong = $newphong->ws_getphong($id_uu['ID_U_NK']);
		QLVBDHCommon::InsertHSMCService($hoso->MAHOSO,$hoso->TENTOCHUCCANHAN,$hoso->TRICHYEU,1,"Đang xử lý",$hoso->DIENTHOAI,$phong);
		exit;
	}
	function trahosoAction(){
		global $auth;
		$user = $auth->getIdentity();
		//parameter
		$this->parameter = $this->getRequest()->getParams();
		$year = QLVBDHCommon::getYear();
		$this->view->wf_id_t = $this->parameter["wf_id_t"];
		$this->view->ID_HSCV = $this->parameter["id"];
		$this->view->year = $year;
		$this->view->idHSCV = $this->parameter["id"];
		$this->view->id_loaihscv = $this->_request->getParam('type');
		$model = new motcua_hosoModel($year);
		$this->view->hsmcInfo = $model->getHSMCByIdHSCV($this->view->ID_HSCV);
		$tlnhangomModel = new motcua_nhangomModel($year);
		$this->view->TenLoaiHoSo = ($model->getTenLoaiHoSoById($this->view->hsmcInfo->ID_LOAIHOSO));
		QLVBDHCommon::GetTree(&$thumuc,"HSCV_THUMUC","ID_THUMUC","ID_THUMUC_CHA",1,1);
		$this->view->thumuc = $thumuc;
		$thumuc=ThuMucModel::GetAllThumuc();
		$this->view->thumuc = $thumuc;
		$this->view->hskxly=(int)$model->getKhongXuLyById($this->parameter["id"]);
        if($this->view->hskxly){
            $db = Zend_Db_Table::getDefaultAdapter();
            $sql = "SELECT NOIDUNG FROM ".QLVBDHCommon::Table("WF_PROCESSLOGS")." wfl
            inner join ".QLVBDHCommon::Table("WF_PROCESSITEMS")." wfi on wfl.ID_PI = wfi.ID_PI
            WHERE  ID_O = ?  ORDER BY ID_PL DESC";
            $r = $db->query($sql,$this->view->ID_HSCV);
            $r = $r->fetch();
            //var_dump($r);
            $this->view->noidungkhongxuly = $r["NOIDUNG"];
        }

	}
	function savetrahosoAction(){
		global $auth;
		///global $db;
		$user = $auth->getIdentity();
		$param = $this->getRequest()->getParams();
		$idHSCV = $param["id"];
		$wf_id_t = $param["wf_id_t"];
		$wf_nexttransition = $param["wf_nexttransition"];
		$wf_nextuser = $param["wf_nextuser"];
		//$year = $param["year"];
		$year = QLVBDHCommon::getYear();
		$ngay_tra = $param["ngay_tra"];
		$wf_name_user = $param["wf_name_user"];
		$wf_hanxuly_user = $param["wf_hanxuly_user"];
		//chuyen dinh dang ngay thang (30/12/2009 -> 2009-12-30)
		$ngay_tra = trim($ngay_tra);
		$arr = explode('/',$ngay_tra);
		$ngay_tra = date('y-m-d',mktime(null,null,null,$arr[1],$arr[0],$arr[2]));
		$luc_tra = $param["luc_tra"];
		$is_khongxuly = $param['is_khongxuly'];
		$ismonitor = $param['IS_MONITOR'];
		$lydo=$param["khongxuly"];
		if(!$is_khongxuly) $is_khongxuly = 0;
		if($is_khongxuly > 0) $is_khongxuly = 1;
		$db = Zend_Db_Table::getDefaultAdapter();
		//Zend_Registry::set('year',$year);
		//Kiem tra ho so da duoc tra hay khong
		//if(WFEngine::SendNextUserByObjectId($idHSCV,$wf_nexttransition,$user->ID_U,$wf_nextuser,$wf_name_user,$wf_hanxuly_user)==1){
			$motcuahsModel = new motcua_hosoModel($year);
			$motcuahsModel->updateAfterTraHoSo($idHSCV,$ngay_tra,$luc_tra,$is_khongxuly,$lydo,(int)$ismonitor);
			//cap nhat trang thai cac du thao trong ho so mot cua
			$VBDTModel = new VanBanDuThaoModel($year);
			$VBDTModel->updateTrangthaiByIdHSCV($idHSCV,1);
			$VBDTModel->updateNguoiKyByIdHSCV($idHSCV,$user->ID_U);
		//}
		if($is_khongxuly == 0 ){
			$hoso = $motcuahsModel->getHSMCByIdHSCV($idHSCV);
			$adapter = new adapter();
			$adapter->insertHSMCService($hoso->MAHOSO,$hoso->TENTOCHUCCANHAN,$hoso->TRICHYEU,3,"Mời Ông/Bà đến nhận kết quả",$hoso->DIENTHOAI,$hoso->NHAN_NGAY);
		}
		if($param["THUMUC"]>1){
			$hscv = new hosocongviecModel();
			$hscv->getDefaultAdapter()->update(QLVBDHCommon::Table("HSCV_HOSOCONGVIEC"),array("ID_THUMUC"=>$param["THUMUC"]),"ID_HSCV=".(int)$idHSCV);
			//echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('Đã lưu trữ thành công.');window.parent.document.frm.submit();</script>";
			//exit;
		}
		$db->update(QLVBDHCommon::Table("wf_trangthaihosologs"),array("ACTIVE"=>0),"ACTIVE=1 and "." ID_HSCV=".$idHSCV );
		//cap nhật các trạng thái hồ sơ công việc thành Deactice
		echo "<script>window.parent.document.frm.submit();</script>";
		exit;
	}
	function insertAction(){
		exit;
	}
	/*
     * This fuction is input action. This action display form to input data
     */
	function inputAction()
    {  
    	global $auth;
		$user = $auth->getIdentity();
    	//Enable Layout
    	$this->_helper->layout->enableLayout();
    	//lay id Ho so mot cua
    	$id=(int)$this->_request->getParam('id');
    	$error=$this->_request->getParam('error');
		$is_dkquamang=$this->_request->getParam('isqm');
    	$wf_id_t=(int)$this->_request->getParam('wf_id_t',0);
    	$type=(int)$this->_request->getParam('type',3);
    	$formData = $this->_request->getPost();
		$validFrom=$this->getRequest()->getParam("inputSubmit");
		$arrayIdFile=$this->_request->getParam('idFile');
		$tochuc = $this->_request->getParam('dataToChucCaNhan');
		//lay tu combobox
        $this->view->idlhs = $formData["ID_LOAIHOSO"];
    	$year= QLVBDHCommon::getYear();
		$month = date("n");      
		/*
			Insert File vào DB
		*/
		$idObject = $this->getRequest()->getParam("idObject");		
		$id_dkquamang = $this->getRequest()->getParam("id_dkquamang");		
		$isInsertFileDVC = $this->getRequest()->getParam("isInsertFileDVC");		
		$tempTbl = new gen_tempModel();
		if((int)$idObject == 0 ){		
			$idObject = $tempTbl->getIdTemp();
		}
		if($isInsertFileDVC != 1){
		dkwebModel::getInsertFileDinhKem($idObject,$id_dkquamang,$user->ID_U,$year,$month);	
		}
    	//thiet lap cac bien view
    	$this->view->wf_id_t = $wf_id_t;
    	$this->view->type =  $type;
    	$this->view->error=$error;
		$this->view->is_dkquamang=$is_dkquamang;
    	$this->view->idObject=$idObject;
		$today = date("Y-m-d");
		$motcuaModel=new motcua_hosoModel($year);
		$filedinhkemModel =  new filedinhkemModel($year);
		$this->view->formdata = $formData;
		$this->view->datamotcua = $formData;
		//Lay danh sach thu tuc va le phi
		$linhvuc = new linhvucmotcuaModel();
		$this->view->data = $linhvuc->SelectAllByUID($user->ID_U);
		if($formData["ID_LV_MC"]==0){
			$formData["ID_LV_MC"]=$this->view->data[0]["ID_LV_MC"];
		}
		$loaimodel=new LoaiModel();
		$this->view->dataLoai=$loaimodel->fetchAll();
		//Lay id_hscv tu trang /hscv/hscv/list
		$id_hscv=(int)$this->_request->getParam('id_hscv');
		if($formData["ID_LOAIHOSO"]>0)
				$type_id=$loaimodel->getIdLoaiHscvByIdLoai($formData["ID_LOAIHOSO"]);
		$createarr = WFEngine::GetCreateProcessButtonFromLoaiCV($type_id,$user->ID_U);
		if(count($createarr)>0)
		{
			$this->view->wf_id_t=$createarr["ID_T"];
		}
		else
		{
			$this->view->wf_id_t="";
		}
		if($id_hscv>0)
		{
			$rowHSMC=$motcuaModel->getHSMCByIdHSCV($id_hscv);
			if($rowHSMC!=null)	$id=$rowHSMC->ID_HOSO;
		}
		$form = new motcua_hosoForm(array('id'=>$id,"ID_LV_MC"=>$formData["ID_LV_MC"]));
    	if($id>0)
    	{
    		$this->view->title = "Hồ sơ một cửa";
    		$this->view->subtitle = "Cập nhật";
     		$motcuas = $motcuaModel->fetchRow('ID_HOSO='.$id);
     		$this->view->datamotcua = $motcuas;
			if($motcuas!=null)
               		$form->populate($motcuas->toArray());
            else  $this->_redirect('/motcua/motcua/input');
            $this->view->form = $form;
            //Save your id loai ho so
            $this->view->id=$id;
            $nhan_ngay="";
            $nhan_ngay=implode("/",array_reverse(explode("-",$motcuas->NHAN_NGAY)));
            $nhanlai_ngay="";
            $nhanlai_ngay=implode("/",array_reverse(explode("-",$motcuas->NHANLAI_NGAY)));
            $this->view->nhan_ngay=$nhan_ngay;
            $this->view->nhanlai_ngay=$nhanlai_ngay;
            $this->view->loaihoso=$motcuas->ID_LOAIHOSO;
            $this->view->mahoso=$motcuas->MAHOSO;
            $this->view->idTemp=$motcuas->ID_HSCV;
			$this->view->id_stn = $motcuas->ID_STN;

			$this->view->so = $motcuas->SO;
			$this->view->phuong = $motcuas->PHUONG;
		    $this->view->id_yeucau= $this->_request->getParam('id_yeucau');
    	    if($this->view->id_yeucau >0){
	        		 $this->view->subtitle = "Bổ sung hồ sơ";
					 $ngay_yc= $motcuaModel->getyeucaubosung($this->view->id_yeucau);
                     $this->view->noidung_ycbo  =$ngay_yc["CACGHICHU"];
	        	     $ngay_yeucau=strtotime($ngay_yc["NGAY_YEUCAU"]);
	        		 $delay = QLVBDHCommon::countdate($ngay_yeucau,null);
	        		 if(floor($delay/8)>0){
	        		 $delay= floor($delay/8) +1;
	        		 }else{ $delay=1; }
	        	 $this->view->hanxuly =$motcuas->SONGAYXULY + $delay;
	        	 $this->view->delay=$delay;
    	    }else {$this->view->hanxuly =$motcuas->SONGAYXULY;
    	    }
        }
    	else
    	{
			$id_dkquamang = $this->getRequest()->getParam("id_dkquamang");
			if($id_dkquamang > 0){
				$this->view->id_dkquamang = $id_dkquamang;
				$data_dkquamang = dkwebModel::getDetail($id_dkquamang);
				//var_dump($data_dkquamang);
				//$this->view->loaihoso = $data_dkquamang ["ID_LOAIHOSO"];
				
				$formData = array();
				$formData["ID_LOAIHOSO"]= $data_dkquamang ["ID_LOAIHOSO"] ;
				$formData["ID_LV_MC"]= $data_dkquamang ["ID_LV_MC"] ;
				//$form = new motcua_hosoForm(array('id'=>$id,"ID_LV_MC"=>$formData["ID_LV_MC"]));
				$formData["TENTOCHUCCANHAN"]=$data_dkquamang ["TENTOCHUCCANHAN"] ;
				$formData["DIACHI"]=$data_dkquamang ["DIACHI"] ;
				$formData["DIENTHOAI"]=$data_dkquamang ["DIENTHOAI"] ;
				$formData["EMAIL"]=$data_dkquamang ["EMAIL"] ;
                $formData["MAHOSO"]=$data_dkquamang ["MAHOSO"] ;
				$formData["NOIDUNGKHAC"]=$data_dkquamang ["NOIDUNGKHAC"] ;
				$formData["TENHOSO"]=$data_dkquamang ["TENHOSO"] ;
				$form = new motcua_hosoForm(array('id'=>$id,"ID_LV_MC"=>$formData["ID_LV_MC"]));
				
			}
			//$form->populate($formData);
			
    		$this->view->title = "Hồ sơ một cửa";
    		$this->view->subtitle = "Thêm mới";
    		      if(!$formData["NHAN_LUC"]) $formData["NHAN_LUC"] = date("H:i");
		  $loaihoso= (int)$formData["ID_LOAIHOSO"];
    		$sql="select * from `".QLVBDHCommon::Table("motcua_hoso")."` where $loaihoso in (select ID_LOAIHOSO from motcua_loai_hoso where CODE= ? || CODE =? || CODE=?)";
					$dbAdapter = Zend_Db_Table::getDefaultAdapter();
					$query = $dbAdapter->query($sql,array(52,90,71));
					$re = $query->fetchAll();
						 if(count($re)>0){ $formData["NHANLAI_LUC"] = "15:00";
					                   }else{
                                              $formData["NHANLAI_LUC"] = "09:00";
									   }
    		$this->view->form = $form;
    		//Luu danh sach file dinh kem neu submit bi loi
     		try
     		{
	     		if(count($arrayIdFile)>0)
	     		{
	     			$dataFiledinhkem=$filedinhkemModel->fetchRow("MASO='".$arrayIdFile[0]."'");
	     			if($dataFiledinhkem!=null)
	     			{
	     				$this->view->idTemp=$dataFiledinhkem->ID_OBJECT;
	     			}
	     		}
     		}
     		catch(Exception $e2){}
     	}
     	//if has error from add,update populate form and display error
    	if($error!=null)
    	{
    		$form->populate($formData);
    	}
    	else
    	if ($this->_request->isPost() && $validFrom!=null)
        {
        	$form=$this->view->form;
			//var_dump($form->isValid($_POST));
    		if ($form->isValid($_POST))
			{
				$this->dispatch('saveAction');
			}
			else
			{
				if($formData["ID_LOAIHOSO"]>0)
	        	{
	        		$formData["LEPHI"]=$loaimodel->getLePhiByIdLoai($formData["ID_LOAIHOSO"]);
	        		$dataLoai=$loaimodel->fetchRow("ID_LOAIHOSO=".$formData["ID_LOAIHOSO"]);
					if(count($dataLoai)>0)
	        		{
	        			$formData["TRICHYEU"]=$dataLoai->TENLOAI." cho ".$formData["TENTOCHUCCANHAN"];
	        			$this->view->songayxuly=$dataLoai->SONGAYXULY;
	        			$this->view->tenloai=$dataLoai->TENLOAI;
	        			$this->view->nhan_ngay=date("d/m/Y");
	        	    	$tempNhanNgay= QLVBDHCommon::addDateAll(strtotime($today." ".date("H:i:s")),$this->view->songayxuly);
						if($formData["ID_LOAIHOSO"]==95 ||$formData["ID_LOAIHOSO"]==108 ||$formData["ID_LOAIHOSO"]==136){
                            $this->view->nhanlai_ngay=date("d/m/Y");
						}else{
	        	    	$this->view->nhanlai_ngay=date('d/m/Y',$tempNhanNgay);}
	        	    	$this->view->hanxuly = $dataLoai->SONGAYXULY;
	        		}
	        		$this->view->type_id=$formData["ID_LOAIHOSO"];
	        	}
	        	$form->populate($formData);
			}
        }
        else
        {
        	if($formData["ID_LOAIHOSO"]>0)
        	{
        		$formData["LEPHI"]=$loaimodel->getLePhiByIdLoai($formData["ID_LOAIHOSO"]);
        		$dataLoai=$loaimodel->fetchRow("ID_LOAIHOSO=".$formData["ID_LOAIHOSO"]);
        		if(count($dataLoai)>0)
        		{
        			$formData["TRICHYEU"]=$dataLoai->TENLOAI." cho ".$formData["TENTOCHUCCANHAN"];
        			$this->view->songayxuly=$dataLoai->SONGAYXULY;
        			$this->view->tenloai=$dataLoai->TENLOAI;
					$this->view->BUILDNAME = $dataLoai->BUILDNAME;
        			$this->view->nhan_ngay=date("d/m/Y");
        	    	$tempNhanNgay= QLVBDHCommon::addDateAll(strtotime($today." ".date("H:i:s")),$this->view->songayxuly);
        	    	if($formData["ID_LOAIHOSO"]==95 ||$formData["ID_LOAIHOSO"]==108 ||$formData["ID_LOAIHOSO"]==136){
                            $this->view->nhanlai_ngay=date("d/m/Y");
						}else{
	        	    	$this->view->nhanlai_ngay=date('d/m/Y',$tempNhanNgay);}
        	    	$this->view->hanxuly = $dataLoai->SONGAYXULY;
        		}
        		$this->view->type_id=$formData["ID_LOAIHOSO"];
        	}
        	$form->populate($formData);
     		try
     		{
	     		if(count($arrayIdFile)>0)
	     		{
	     			$dataFiledinhkem=$filedinhkemModel->fetchRow("MASO='".$arrayIdFile[0]."'");
	     			if($dataFiledinhkem!=null)
	     			{
	     				$this->view->idTemp=$dataFiledinhkem->ID_OBJECT;
	     			}
	     		}
     		}
     		catch(Exception $e2){}
        }
		if($formData["ID_LOAIHOSO"]>0)
				$type_id=$loaimodel->getIdLoaiHscvByIdLoai($formData["ID_LOAIHOSO"]);			
		$createarr = WFEngine::GetCreateProcessButtonFromLoaiCV($type_id,$user->ID_U);		
		if(count($createarr)>0)
		{
			$this->view->wf_id_t=$createarr["ID_T"];			
		} 
		else 
		{
			$this->view->wf_id_t="";
		}
		
		$this->view->formdata = $formData;
     	//Add button
     	QLVBDHButton::EnableSave("/motcua/motcua/save");
     	if($id>0){
     	QLVBDHButton::AddButton("In phiếu","","PrintButton",'window.open("/motcua/motcua/phieunhanhoso/id/'.$id.'/id_yeucau/'.$this->view->id_yeucau.'");');
     	}
		QLVBDHButton::EnableBack("/motcua/motcua");
		//exit;
    }
    /**
	 * Them moi/cap nhat Ho So mot Cua
	 *
	 */
	function saveAction()
	{ 
		$db = Zend_Db_Table::getDefaultAdapter();
        $formData = $this->_request->getPost();
			// var_dump($formData);exit;
		$id=(int)$this->_request->getParam("id",0);
    	$id_hscv=(int)$this->_request->getParam("ID_HSCV",0);
    	$type=(int)$this->_request->getParam("type",3);
    	$year= QLVBDHCommon::getYear();
    	$wf_id_t = (int)$this->_request->getParam("wf_id_t",0);
    	$wf_nexttransition = $formData["wf_nexttransition"];
		$wf_nextuser = $formData["wf_nextuser"];		
		$wf_nextg = $formData["wf_nextg"];
		$wf_nextdep = $formData["wf_nextdep"];
		$wf_name_user = $formData["wf_name_user"];
		$wf_name_g = $formData["wf_name_g"];
		$wf_name_dep = $formData["wf_name_dep"];
		$wf_hanxuly_user = $formData["wf_hanxuly_user"];
		$wf_hanxuly_g = $formData["wf_hanxuly_g"];
		$wf_hanxuly_dep = $formData["wf_hanxuly_dep"];
    	$hscv=new hosocongviecModel();
		$motcua_hoso=new motcua_hosoModel($year);
		$filedinhkemModel =  new filedinhkemModel($year);
		$motcuanhangomModel = new MotCuaNhanGomModel($year);
		$loaimodel=new LoaiModel();

    	//lay ngay hien tai
    	$today = date("Y-m-d H:m:s");
		$getIdHscv=	$formData['ID_HSCV'];
		$get_IdHscv = $getIdHscv;
		//lay danh sach Id của filedinhkem
		$arrayIdFile=$formData['idFile'];
		//lay id_session
		$id_session=session_id();
		//Lay danh sach ten thu tuc
		$arrayTenThuTuc=$this->getRequest()->getParam('tenthutuc');
		$mahoso_dkquamang=$this->getRequest()->getParam('mahoso_dkquamang');
		$arrayTenThuTuc1=$this->getRequest()->getParam('tenthutuc2');
		//xu ly truong nhan_ngay
		$nhan_ngay=implode("-",array_reverse(explode("/",$formData['NHAN_NGAY'])));
		$nhanlai_ngay=implode("-",array_reverse(explode("/",$formData['NHANLAI_NGAY'])));
		$type_id=$loaimodel->getIdLoaiHscvByIdLoai($formData["ID_LOAIHOSO"]);
		if($type_id>0)
			$type=$type_id;
		if($getIdHscv==0)
		{
			$getIdHscv = $hscv->CreateHSCV(
			$formData["TRICHYEU"],1,
			$type,
			$today,
			$today,
			Zend_Registry::get('auth')->getIdentity()->ID_U,
			$wf_nextuser,
	        $formData["wf_name_user"],
	        $formData["wf_hanxuly_user"]);
		}
		//tuanpp cập nhật , get lại wf_nextuser 
		elseif (($getIdHscv!=0)&&$wf_nextuser==-1) {			
			$year = QLVBDHCommon::getYear();
			$motcuaModel=new motcua_hosoModel($year);			
			$data = $motcuaModel->fetchRow("ID_HSCV = ".$getIdHscv)->toArray();			
			$wf_nextuser = $data["NGUOINHAN"];			
		}
    	if($formData!=null)
    	{
    		try
	    	{
	       		if($formData['SOKYHIEU_CHAR'])
					$sokyhieu =  $formData['SO'] . "/" .$formData['SOKYHIEU_CHAR'];
				$data = array(
	                    'ID_LOAIHOSO' 		=> $formData['ID_LOAIHOSO'],
	                    'TRICHYEU' 		=> $formData['TRICHYEU'],
	                	'NGUOINHAN' 	=> $wf_nextuser,
	                	'MAHOSO' 		=> $formData['MAHOSO'],
	                	'TENTOCHUCCANHAN' 		=> $formData['TENTOCHUCCANHAN'],
	                	'DIACHI' 	=> $formData['DIACHI'] ,
	                	'NHAN_LUC'	=>(trim($formData['NHAN_LUC']) == ""?null:$formData['NHAN_LUC']),
	                	'NHAN_NGAY'=> $nhan_ngay,
	                	'NHANLAI_LUC'	=>(trim($formData['NHANLAI_LUC']) == ""?null:$formData['NHANLAI_LUC']),
	                	'NHANLAI_NGAY'=> $nhanlai_ngay,
	                	'LEPHI'		=> (trim($formData['LEPHI_hidden']) == ""?null:$formData['LEPHI_hidden']),
	                	'TRANGTHAI' 	=> '1',
	                	'NGAYNHAN' 	=> $today,
	                	'CHUTHICH' 	=> $formData['CHUTHICH'],
	                	'EMAIL' 	=> $formData['EMAIL'],
	                	'DIENTHOAI' 	=> $formData['DIENTHOAI'],
	       				'SO' 	=> $formData['SO'],
						'ID_STN' => $formData['ID_STN'],
						'SOKYHIEU' 	=> $sokyhieu, 'SOKYHIEU_CHAR' 	=> $formData['SOKYHIEU_CHAR'],
	       		        'NGUOITAO'=>Zend_Registry::get('auth')->getIdentity()->ID_U,
				        'PHUONG'=> $formData['phuong'],
				        'SONGAYXULY'=> $formData['HANXULY'],
						'BARCODE' => substr(md5(rand(1, 1000000000).date()), 0, 6),
						'TOCHUCCANHANLOWER' 		=> mb_strtolower($formData['TENTOCHUCCANHAN'])
	                );
				$arr_custom_fields = motcua_hosoModel::Customfields($formData['ID_LOAIHOSO']);
				//var_dump($arr_custom_fields);
				//var_dump($formData);
				foreach($arr_custom_fields as $cus){
					switch($cus["TYPE"]){
						case "DATE" :
							$data[$cus["NAME_COLUMN"]] = implode("-",array_reverse(explode("/",$formData[$cus["NAME_COLUMN"]])));
							break;
						case "VARCHAR" :
							$data[$cus["NAME_COLUMN"]] = $formData[$cus["NAME_COLUMN"]];
							break;
						case "INTEGER" :
							$data[$cus["NAME_COLUMN"]] = (int)$formData[$cus["NAME_COLUMN"]];
							break;
						case "DOUBLE" :
							$data[$cus["NAME_COLUMN"]] = (double)$formData[$cus["NAME_COLUMN"]];
							break;
						default:
							break;
					}
				}
                //lay so so so
                $sothutu = 0;
                if($id >0){

                }else{

                   $sql_shs = "select max(SO) as SOTHUTU from ".QLVBDHCommon::Table("motcua_hoso")." where ID_LOAIHOSO = ?";
                   $re_sh = $db->query($sql_shs,array($formData['ID_LOAIHOSO']));
                   $re_shs = $re_sh->fetch();
                   $sothutu =$re_shs["SOTHUTU"];
                   if(!$sothutu)
                       $sothutu = 1;
                }
		        if($id>0)
		        {
		        	$where="ID_HOSO=".$id;
		        	if($formData["ID_HSCV"]>0)
		        	{
		        		//$motcua_hoso->getDefaultAdapter()->beginTransaction();
			        	try
			        	{
							$motcua_hoso->getDefaultAdapter()->update("HSCV_HOSOCONGVIEC_".$year,array("NAME"=>$formData['TRICHYEU']),"ID_HSCV=".$formData["ID_HSCV"]);
					        $data +=array("ID_HSCV"=>$formData["ID_HSCV"]);
			           		$motcua_hoso->update($data,$where);
			        	}
			        	catch(Exception $ex)
			        	{
			        		$messageError="Lỗi cập nhật HSCV".$ex;
							$this->_request->setParam('error',$messageError);
							$this->_request->setParams($formData);
							//$motcua_hoso->getDefaultAdapter()->rollBack();
							$this->dispatch('inputAction');
			        	}
		       	 	}
		        	//$motcua_hoso->update($data,$where);
					 if($formData['id_yeucau'] >0){
						/*
						* Cập nhật lại các trạng thái liên quan đến bổ sung hồ sơ
						1.trường IS_BOSUNG bên hồ sơ công việc
						2.trường IS_CHOBOSUNG bên các bảng log trạng thái hồ sơ
						*/
						global $db;
						global $auth;
						$user = $auth->getIdentity();
						$id_pl=$motcua_hoso->getid_pl($id_hscv);
						$cacghichu=$motcua_hoso->getyeucaubosung($formData['id_yeucau']);
						$cacgc=$cacghichu['CACGHICHU'];
						$phieu_yc = new phieu_yeucau_bosung();
						$phieu_yc->_id_yeucau = $formData['id_yeucau'];
						$phieu_yc->_nguoibosung = $user->ID_U;
						$phieu_yc->_ngay_bosung = date('y-m-d h:m:s');
						$phieu_yc->_cacghichu = $cacgc." "."<font color=blue> (Đã bổ sung)</font>";
						$phieu_ycModel = new phieu_yeucau_bosungModel(qlvbdhCommon::GetYear());
						try{
							$phieu_ycModel->updateWhenBoSung($phieu_yc);
							$db->update(qlvbdhCommon::Table("HSCV_HOSOCONGVIEC"),array("IS_BOSUNG"=>0),"ID_HSCV=".$id_hscv);
							//update bảng trạng thái hồ sơ log
							$db->update(qlvbdhCommon::Table("wf_trangthaihosologs"),array("IS_CHOBOSUNG"=>0),"ID_HSCV=".$id_hscv);
							//cap nhat lai hạn xử lý của dòng luân chuyển cần tính lại trễ hạn
							$ngay = strtotime($cacghichu["NGAY_YEUCAU"]);
							$freedate = new Zend_Session_Namespace('freedate');
							$free = $freedate->free;
							$delay = QLVBDHCommon::countdate($ngay,$free);
							$hxl_moi =  ($delay/8) + $cacghichu["HANXULY_CU"];
							$db->update(qlvbdhCommon::Table("wf_processlogs"),array("HANXULY"=>$hxl_moi),"ID_PL=".$cacghichu["ID_PL_CURHAN"]);
							for($i=0;$i<count($arrayTenThuTuc1);$i++)
							{
								$motcuanhangomModel->insert(array("ID_HOSO"=>$id,"ID_SESSION"=>null,"TEN_THUTUC"=>$arrayTenThuTuc1[$i]));
							}
                            //cap nhat lai trang thai thong ke xu ly
                            $db->update(QLVBDHCommon::Table("motcua_hoso"),array("IS_BOSUNG"=>0),"ID_HSCV=".$id_hscv );

	        		    }catch(Exception $e2){
	        			}
					 }
		        }
		        else
		        {
		        	$data +=array("ID_HSCV"=>$getIdHscv);
		        	try{
						$id=$motcua_hoso->insert($data);
					}catch(Exception $ex){
						echo $ex->getMessage();
						exit;
					}
		        }
				//cap nhat dong bo ho so tren website
                if((int)$formData['id_dkquamang'] > 0)
				{
                    try{
                        //$this->mailProcessTiepNhan($formData['id_dkquamang'],$mahoso_dkquamang,$formData['TENHOSO'],$ngay_tra,null,0);
                    }catch(Exception $ex){
                        echo $ex->getMessage();
                    }
                    dkwebModel::deletehosomotcua($formData['id_dkquamang']);
					dkwebModel::updateDKQuaMang((int)$formData['id_dkquamang'],$id);
				}
				//cap nhat lai trang thai cho cac file dinh kem voi ID_HSCV
				if($getIdHscv>0)
		        {
			        for($i=0;$i<count($arrayIdFile);$i++)
		        	{
	                	$filedinhkemModel->update(array("ID_OBJECT"=>$getIdHscv,"TYPE"=>1),"MASO='".$arrayIdFile[$i]."'");
		        	}
		        }
				//Xoa het thu tuc truoc
	        	if(!($formData['id_yeucau'] >0)){
					$motcuanhangomModel->delete("ID_HOSO=".$id);
	        		$motcuanhangomModel->delete("ID_SESSION='".$id_session."'");
	        		//Cap nhat lai trang thai thu tuc can co trong truong hop tao moi
	        		for($i=0;$i<count($arrayTenThuTuc);$i++)
	        		{
	        			try
	        			{
	        				$motcuanhangomModel->insert(array("ID_HOSO"=>$id,"ID_SESSION"=>null,"TEN_THUTUC"=>$arrayTenThuTuc[$i]));
	        			}
	        			catch(Exception $e2)
	        			{
	        				echo $e2->__toString();exit;
	        			}
	        		}
				}
	        	$MASOHOSO = 'MSVB';
		        /**
		         * trunglv tao doi tuong de lay ma so van ban den
		         */
				
		      //  if($sothutu){

                    $hs_mocua = new hosomotcua();
                    $hs_motcua->_id_loaihoso = $formData['ID_LOAIHOSO'];
                    //lay so thu tu


                    $hs_motcua->_sothutu = $sothutu; // so thu tu la id tang tu dong cua ho so
                    $id_pb = Common_Maso::getIdDepByUser($wf_nextuser);                    
                    $hs_motcua->_id_phongban = $id_pb;
                    $MASOHOSO = Common_Maso::getMaSo(3,$hs_motcua);                                        

                    $phong = motcua_hosoModel::ws_getphong($wf_nextuser);
					if((int)$formData['id_dkquamang'] > 0)
					{
						$MASOHOSO = $mahoso_dkquamang;
					}
                    $motcua_hoso->update(array('MAHOSO'=>$MASOHOSO),"ID_HOSO=".$id);
              //  }
               
                // cập nhật trạng thái thống kê
                //$nhan_ngay=implode("-",array_reverse(explode("/",$formData['NHAN_NGAY'])));
		        //$nhanlai_ngay=implode("-",array_reverse(explode("/",$formData['NHANLAI_NGAY'])));
                $arr_parse = explode("/",$formData['NHAN_NGAY']);
                $thang_tiepnhan = (int)$arr_parse[1];
                $nam_tiepnhan = (int)$arr_parse[2];
                //parse tháng nhận hồ sơ
                 $arr_parse = explode("/",$formData['NHANLAI_NGAY']);
                $thang_dukientrahoso = (int)$arr_parse[1];
                $nam_dukientrahoso = (int)$arr_parse[2];
                //parse tháng dự kiến trả hồ sơ
                //parse tháng
				
                $param_cntk = array(
                        "TYPE"=>"TIEPNHAN",
                        "ID_MOTCUA" =>(int) $id,
                        "THANG_TIEPNHAN"=>(int)$thang_tiepnhan,
                        "THANG_DUKIENTRAHOSO"=> (int)$thang_dukientrahoso,
                        "NAM_TIEPNHAN"=>(int)$nam_tiepnhan,
                        "NAM_DUKIENTRAHOSO"=>(int)$nam_dukientrahoso,
                );
                Common_ThongkeInfo::update($param_cntk);				
                Common_ThongkeInfo::updateThoigianconlai((int)$id);	
				
				$adapter = new adapter();
				$adapter->insertHSMCService($MASOHOSO,$formData['TENTOCHUCCANHAN'],$formData['TRICHYEU'],1,"Đang xử lý",$formData['DIENTHOAI'],$today);
				if($get_IdHscv == "" || empty($get_IdHscv))
				{
		        	echo "
		        	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		        	<script>
		        	if(confirm('Tạo hồ sơ thành công. Bạn có muốn in phiếu biên nhận không?')){
		        		window.open('/motcua/motcua/phieunhanhoso/id/$id');
		        	}
		        	document.location.href = '/hscv/hscv/list/code/old';
		        	</script>";
		        	exit;
				} else {
					echo "
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
					<script>
					if(confirm('Cập nhật hồ sơ thành công. Bạn có muốn in phiếu biên nhận không?')){
					window.open('/motcua/motcua/phieunhanhoso/id/$id');
					}
					document.location.href = '/hscv/hscv/list/code/old';
					</script>";
					exit;
				}
				
                //end trung lv
	        	//$this->_redirect('/hscv/hscv/list/code/pre');
			}
			catch(Exception $e2)
			{
				$messageError="Có lỗi xảy ra khi thêm mới/update dữ liệu".$e2;
				$this->_request->setParam('error',$messageError);
				$this->_request->setParams($formData);
				$this->dispatch('inputAction');
			}
    	}
    	else{
    		$this->_redirect('/motcua/motcua/input');
    	}
	}
	/**
     * delete action
     *
     */
    function deleteAction()
    {
    	$this->view->title = "Xóa Hồ sơ một cửa";
    	$year=QLVBDHCommon::getYear();
    	//add button
    	QLVBDHButton::AddButton("Danh sách","/mocua/motcua/","","",2);
	    //Get messages
        $this->view->deleteError = '';
        //list Id cannot delete
        $adderror='';
    	if($this->_request->isPost())
		{
			$idarray = $this->_request->getParam('DEL');
			if(count($idarray)<=0)
			{
				$this->view->deleteError="Không có mục nào để tiến hành xóa( xin vui lòng chọn một mục!)";
			}
			$counter=0;
			while ( $counter < count($idarray ))
			{
				if ($idarray[$counter] > 0)
				{
					try
					{
						$delLoai = new motcua_hosoModel($year);
	                	$where = 'ID_HOSO = ' . $idarray[$counter];
	                	$delLoai->delete($where);
					}
					catch(Exception $er){ $adderror=$adderror.$idarray[$counter].' ; ';};
				}
				$counter++;
			}
			//already delete some or all items
			if($counter>0)
			{
				if($counter==count($idarray ))
				{
					$this->view->deleteError="Xóa thành công các mục đã chọn";
					$this->_redirect('/hscv/hscv/list');
				}
				else
				{
					$this->view->deleteError="Không xóa được mục đã chọn";
				}
			}
			if($adderror!='')
			{
				$this->view->deleteError="Xóa không thành công các mục với id= ".$adderror;
			}
		}
	}
	/**
	 * The default action - show list page
	 */
	public function indexAction()
	{
		$this->_helper->layout->enableLayout();
		global $auth;
		$user = $auth->getIdentity();
		$this->view->user = $user;
		//Lấy parameter
		$param = $this->getRequest()->getParams();
		//$param['NAMECV'] = Convert::ConvertToUnicode($param['NAMECV']);
		$config = Zend_Registry::get('config');
		if(is_null($param["id_tths"])){
			$param["id_tths"] = -1;
		}
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
		$ID_LOAIHOSO = $param['ID_LOAIHOSO'];
		$NAME = $param['NAMECV'];
		$MASOHOSO = $param['MASOHOSO'];
		$TENTOCHUCCANHAN = $param['TENTOCHUCCANHAN'];
		$SOKYHIEU_VB=$param['SOKYHIEU_VB'];
		$NGAYBANHANH_VB=$param['NGAYBANHANH_VB'];
		$NGUOINHAN=$param['NGUOINHAN'];
		$TRANGTHAI = $param['TRANGTHAI'];
		$INFILE = $param['INFILE'];
		$INNAME = $param['INNAME'];
		if($param['INNAME']==0 && $param['INFILE']==0){
			$INNAME = 1;
		}
		if($param['NHAN_NGAY_BD']!=""){
			$NHAN_NGAY_BD = $param['NHAN_NGAY_BD'];//."/".QLVBDHCommon::getYear();
			$NHAN_NGAY_BD = implode("-",array_reverse(explode("/",$NHAN_NGAY_BD)));
		}
		if($param['NHAN_NGAY_KT']!=""){
			$NHAN_NGAY_KT = $param['NHAN_NGAY_KT'];//."/".QLVBDHCommon::getYear();
			$NHAN_NGAY_KT = implode("-",array_reverse(explode("/",$NHAN_NGAY_KT)));
		}
		if($param['NHANLAI_NGAY_BD']!=""){
			$NHANLAI_NGAY_BD = $param['NHANLAI_NGAY_BD'];//."/".QLVBDHCommon::getYear();
			$NHANLAI_NGAY_BD = implode("-",array_reverse(explode("/",$NHANLAI_NGAY_BD)));
		}
		if($param['NHANLAI_NGAY_KT']!=""){
			$NHANLAI_NGAY_KT = $param['NHANLAI_NGAY_KT'];//."/".QLVBDHCommon::getYear();
			$NHANLAI_NGAY_KT = implode("-",array_reverse(explode("/",$NHANLAI_NGAY_KT)));
		}
		if($param['NGUOINHAN'] == ''){
			$param['ID_U_NNHAN'] = 0;
		}
		$phuong = $param['phuong'];
		$parameter = array(
			"ID_THUMUC"=>$id_thumuc,
			"ID_LOAIHOSO"=>$ID_LOAIHOSO,
			"NGAY_BD"=>$ngaybd,
			"NGAY_KT"=>$ngaykt,
			"TRANGTHAI"=>$TRANGTHAI,
			"ID_U"=>$user->ID_U,
			"ID_G"=>$user->ID_G,
			"ID_DEP"=>$user->ID_DEP,
			"NAME"=>$NAME,
			"MASOHOSO"=>$MASOHOSO,
			"TENTOCHUCCANHAN"=>$TENTOCHUCCANHAN,
			"SCOPE"=>$scope,
			"CODE"=>$param['code'],
			"OBJECT"=>$param['OBJECT'],
			"ID_SVB" => $param['ID_SVB'],
			"ID_LVB" => $param['ID_LVB'],
			'MC_NGAYBANHANH_BD'=>$MC_NGAYBANHANH_BD,
			'MC_NGAYBANHANH_KT'=>$MC_NGAYBANHANH_KT,
			'NHAN_NGAY_BD'=>$NHAN_NGAY_BD,
			'NHAN_NGAY_KT'=>$NHAN_NGAY_KT,
			"INNAME"=>$param['INNAME'],
			"INFILE"=>$param['INFILE'],
			"SOKYHIEU_VB"=>$param['SOKYHIEU_VB'],
			"NGUOINHAN"=>$param['NGUOINHAN'],
			"ID_U_NNHAN"=>$param['ID_U_NNHAN'],
			"ID_LOAIHSCV"=>$param["ID_LOAIHSCV"],
			"ID_TTHS"=>$param["id_tths"],
			'NHANLAI_NGAY_BD'=>$NHANLAI_NGAY_BD,
			'NHANLAI_NGAY_KT'=>$NHANLAI_NGAY_KT,
			'PHUONG'=>$phuong,
		);
		$this->view->realyear = $realyear;
		$this->view->ID_LOAIHOSO = $ID_LOAIHOSO;
		$this->view->NAME = $NAME;
		$this->view->NHAN_NGAY_BD = $param['NHAN_NGAY_BD'];
		$this->view->NGAY_NGAY_KT = $param['NHAN_NGAY_KT'];
		$this->view->NHANLAI_NGAY_BD = $param['NHANLAI_NGAY_BD'];
		$this->view->NHANLAI_NGAY_KT = $param['NHANLAI_NGAY_KT'];
		$this->view->NGUOINHAN=$param['NGUOINHAN'];
		$this->view->NGAYBANHANH_VB=$param['NGAYBANHANH_VB'];
		$this->view->NAME = $NAME;
		$this->view->MASOHOSO = $MASOHOSO;
		$this->view->TENTOCHUCCANHAN = $TENTOCHUCCANHAN;
		$this->view->SOKYHIEU_VB=$SOKYHIEU_VB;
		$this->view->TRANGTHAI = $TRANGTHAI;
		//$this->view->datatrangthai = WFEngine::GetActivityFromLoaiCV($ID_LOAIHSCV,$user->ID_U);
		$this->view->id_thumuc = $id_thumuc;
		$this->view->INNAME = $INNAME;
		$this->view->INFILE = $INFILE;
		$this->view->user = $user;
		$this->view->code = $param['code'];
		$this->view->idhscv = $param['idhscv'];
		$this->view->OBJECT = $param['OBJECT'];
		$this->view->ID_LOAIHSCV = $param['ID_LOAIHSCV'];
		$this->view->MC_NGAYBANHANH_BD = $param['MC_NGAYBANHANH_BD'];
		$this->view->MC_NGAYBANHANH_KT = $param['MC_NGAYBANHANH_KT'];
		$this->view->ID_U_NNHAN = $param['ID_U_NNHAN'];
		$this->view->phuong = $param['phuong'];
		$this->view->title = "Hồ sơ một cửa";
		$this->view->subtitle="Danh sách";
		QLVBDHButton::EnableAddNew("/motcua/motcua/input");
		//Doc du lieu de hien thi len view
		$id = $this->_request->getParam('id');
		$this->view->id = $id;
		$config = Zend_Registry::get('config');
		$page = $this->_request->getParam('page');
		$year = QLVBDHCommon::getYear();
		//$model=new motcua_hosoModel($year);
		//xac định trạng thái là đang xử lý hay đã xử lý
		$id_tths = $param["id_tths"];
		$num_daxl = WfEngine::getCountHosoDaxulyByCurrentUser();
		$this->view->num_daxl = $num_daxl;
		$this->view->arr_tktths = (WfEngine::thongkesoluonghosotrentrangthaiByCurrentUser());
		$this->view->arr_trangthais = WfEngine::getAllTrangthaihosoByCurrentUser();
		//var_dump($this->view->arr_trangthais);
		$this->view->is_dangxuly = 0;
		$num_all = 0;
		foreach($this->view->arr_trangthais as $tt){
			if($id_tths == $tt["ID_TTHS"]){
				$this->view->is_dangxuly = $tt["LATRANGTHAINHAN"];
				break;
			}
		}
		$this->view->arr_tktths["-1"] =  $num_daxl;
		$n_rows = MotCuaModel::countAll($parameter);
		//var_dump($n_rows);
		if($n_rows == -1)
			$n_rows = $this->view->arr_tktths[$id_tths];
		//var_dump($this->view->arr_tktths);
		$page = $param['page'];
		$limit = $param['limit1'];
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		$limit = $this->_request->getParam('limit1');
		//echo $limit; exit;
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		//if( ($page-1)*$n_rows==$n_rows && $n_rows>0)$page--;
		$this->view->data = MotCuaModel::SelectAll(($page-1)*$limit,$limit,$parameter,$order);
		$this->view->showPage = QLVBDHCommon::Paginator($n_rows,5,$limit,"frm",$page) ;
		$this->view->limit=$limit;
		$this->view->page=$page;
		$this->view->year=$year;
		$this->view->ID_TTHS = (int)$param["id_tths"];
		//if((int)$this->view->ID_TTHS == 0)
			//$this->view->ID_TTHS = -1;
		$hscvvbss = WFEngine::getListIdHscvVuaBS();
		$this->view->arr_idvuabosung  = array();
		foreach($hscvvbss as $hscvvbs){
			$this->view->arr_idvuabosung[] = $hscvvbs["ID_HSCV"];
		}
	}
	public function phieunhanhosoAction(){
		global $auth;
		$this->_helper->layout->disableLayout();
		$id = $this->_request->getParam('id');
		$year = QLVBDHCommon::getYear();
		$model=new motcua_hosoModel($year);
		$loai = new LoaiModel();
		$motcuas = $model->fetchRow('ID_HOSO='.$id);
		$this->view->phuong = $motcuas->PHUONG;
		$this->view->tenphuong = $model->getTENPHUONG($this->view->phuong);
		$this->view->hanxuly =$motcuas-> SONGAYXULY;
		$nhangom = new MotCuaNhanGomModel($year);
		$data = $model->find((int)$id)->current();
		$tenloai = $loai->find($data->ID_LOAIHOSO)->current();
		$nhangom = $nhangom->fetchAll("ID_HOSO=".(int)$id);
		$user = $auth->getIdentity();
		$config = Zend_Registry::get('config');
		$this->view->config = $config;
		$this->view->user = $user;
		$this->view->phong= DepartmentsModel::getNameById($user->ID_DEP);
		$this->view->tenloai = $tenloai->TENLOAI;
		$this->view->data = $data;
		$this->view->id_loaihoso=$data->ID_LOAIHOSO;
		$this->view->nhangom = $nhangom;
		$this->view->id_yeucau= $this->_request->getParam('id_yeucau');
		$linhvuc = new linhvucmotcuaModel();
		$this->view->noidung = $linhvuc->GetNoidung($this->view->id_loaihoso);
		if($this->view->id_yeucau >0){
	        	 $this->view->subtitle = "Bổ sung hồ sơ";
				 $this->view->thutucbosungs = motcua_thutuc_bosungModel::getByIdYeuCau($this->view->id_yeucau);
    	    }else{
			}
	}
	public function phieutrahosoAction(){
		global $auth;
		$this->_helper->layout->disableLayout();
		$id = $this->_request->getParam('id');
		$year = QLVBDHCommon::getYear();
		$model=new motcua_hosoModel($year);
		$loai = new LoaiModel();
		if($id>0)
		{
			$motcuas = $model->fetchRow('ID_HOSO='.$id);
			$this->view->phuong = $motcuas->PHUONG;
			$this->view->tenphuong = $model->getTENPHUONG($this->view->phuong);
			$this->view->hanxuly =$motcuas-> SONGAYXULY;
			$nhangom = new MotCuaNhanGomModel($year);
			$data = $model->find((int)$id)->current();
			$tenloai = $loai->find($data->ID_LOAIHOSO)->current();
			$nhangom = $nhangom->fetchAll("ID_HOSO=".(int)$id);
			$user = $auth->getIdentity();
			$config = Zend_Registry::get('config');
			$this->view->config = $config;
			$this->view->user = $user;
			$this->view->phong= DepartmentsModel::getNameById($user->ID_DEP);
			$this->view->tenloai = $tenloai->TENLOAI;
			$this->view->data = $data;
			$this->view->id_loaihoso=$data->ID_LOAIHOSO;
			$this->view->nhangom = $nhangom;
			$this->view->id_yeucau= $this->_request->getParam('id_yeucau');
			$linhvuc = new linhvucmotcuaModel();
			$this->view->noidung = $linhvuc->GetNoidung($this->view->id_loaihoso);
			if($this->view->id_yeucau >0){
		        	 $this->view->subtitle = "Bổ sung hồ sơ";
					 $this->view->thutucbosungs = motcua_thutuc_bosungModel::getByIdYeuCau($this->view->id_yeucau);
	    	    }else{
				}
		}
	}
	public function getsohosoAction(){
		$this->_helper->layout->disableLayout();
		$params =  $this->_request->getParams();
		$id_stn = $params["id_stn"];
		//echo $id_stn;
		echo motcua_hosoModel::getNextSoHoso($id_stn);
		exit;
	}
	function khongtrakipAction(){
		global $auth;
		$user = $auth->getIdentity();
		//parameter
		$this->parameter = $this->getRequest()->getParams();
		$year = QLVBDHCommon::getYear();
		$this->view->wf_id_t = $this->parameter["wf_id_t"];
		$this->view->ID_HSCV = $this->parameter["id"];
		$this->view->year = $year;
		$this->view->idHSCV = $this->parameter["id"];
		$this->view->id_loaihscv = $this->_request->getParam('type');
		$model = new motcua_hosoModel($year);
		$this->view->hsmcInfo = $model->getHSMCByIdHSCV($this->view->ID_HSCV);
		$tlnhangomModel = new motcua_nhangomModel($year);
		$this->view->tailieu = $tlnhangomModel->getTaiLieuByIdHSMC($this->view->hsmcInfo->ID_HOSO);
		$this->view->TenLoaiHoSo = ($model->getTenLoaiHoSoById($this->view->hsmcInfo->ID_LOAIHOSO));
		QLVBDHCommon::GetTree(&$thumuc,"HSCV_THUMUC","ID_THUMUC","ID_THUMUC_CHA",1,1);
		$this->view->thumuc = $thumuc;
		$thumuc=ThuMucModel::GetAllThumuc();
		$this->view->thumuc = $thumuc;
		$this->view->hskxly=$model->getKhongXuLyById($this->parameter["id"]);
	}
    function savekhongtrakipAction(){
				global $auth;
		///global $db;
		$user = $auth->getIdentity();
		$param = $this->getRequest()->getParams();
		$idHSCV = $param["id"];
		//$year = $param["year"];
		$year = QLVBDHCommon::getYear();
		$lydo=$param["khongtrakip"];
		$db = Zend_Db_Table::getDefaultAdapter();
			$motcuahsModel = new motcua_hosoModel($year);
			$motcuahsModel->updateAfterTrakhongkip($idHSCV,$lydo);
			//cap nhat trang thai cac du thao trong ho so mot cua
		echo "<script>window.parent.document.frm.submit();</script>";
		exit;
	}
}