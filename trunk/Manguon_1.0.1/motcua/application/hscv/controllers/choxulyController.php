<?php 

	require_once 'Zend/Controller/Action.php';
	require_once 'hscv/models/hosocongviecModel.php';
	require_once 'hscv/models/loaihosocongviecModel.php';
	
	
	require_once 'hscv/models/ThuMucModel.php';

	require_once('qtht/models/UsersModel.php');
	
	require_once('auth/models/ResourceUserModel.php');
	

	require_once 'hscv/models/choxulyModel.php';
	
	
	class Hscv_choxulyController extends Zend_Controller_Action{
	
		function init(){
		
		}

		function listchoxulyAction(){
		
			global $auth;
			$user = $auth->getIdentity();
			
			//Lấy parameter
			$param = $this->getRequest()->getParams();
			//$param['NAMECV'] = Convert::ConvertToUnicode($param['NAMECV']);
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
			$NAME = $param['NAMECV'];
			$page = $param['page'];
			$limit = $param['limit1'];
			if($limit==0 || $limit=="")$limit=$config->limit;
			if($page==0 || $page=="")$page=1;
			$TRANGTHAI = $param['TRANGTHAI'];
			$INFILE = $param['INFILE'];
			$INNAME = $param['INNAME'];
			if($param['INNAME']==0 && $param['INFILE']==0){
				$INNAME = 1;
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
				"SCOPE"=>$scope,
				"CODE"=>$param['code'],
				"OBJECT"=>$param['OBJECT'],
				"INNAME"=>$param['INNAME'],
				"INFILE"=>$param['INFILE']
			);
			
			//Tạo đối tượng
			//$hscvcount = HosoluutheodoiModel::Count($parameter);
			if(($page-1)*$limit==$hscvcount && $hscvcount>0)$page--;
			//Lấy dữ liệu
			$this->view->loaihscv = WFEngine::GetLoaiCongViecFromUser($user->ID_U);
			$this->view->data = choxulyModel::SelectAllChoxuly($parameter,($page-1)*$limit,$limit,"");
			
			$this->view->realyear = $realyear;
			$this->view->ID_LOAIHSCV = $ID_LOAIHSCV;
			$this->view->NAME = $NAME;
			$this->view->NGAY_BD = $param['NGAY_BD'];
			$this->view->NGAY_KT = $param['NGAY_KT'];
			$this->view->NAME = $NAME;
			$this->view->TRANGTHAI = $TRANGTHAI;
			$this->view->datatrangthai = WFEngine::GetActivityFromLoaiCV($ID_LOAIHSCV,$user->ID_U);
			$this->view->id_thumuc = $id_thumuc;
			$this->view->INNAME = $INNAME;
			$this->view->INFILE = $INFILE;
			$this->view->user = $user;
			$this->view->code = $param['code'];
			$this->view->idhscv = $param['idhscv'];
			$this->view->OBJECT = $param['OBJECT'];
			$this->view->title="Hồ sơ công việc";
			$this->view->subtitle="Danh sách lưu chờ xử lý";
			$this->view->page = $page;
			$this->view->limit = $limit;
			$this->view->showPage = QLVBDHCommon::PaginatorWithAction($hscvcount,10,$limit,"frm",$page,"/hscv/hosoluutheodoi/index");
			$this->view->loaihscv = WFEngine::GetLoaiCongViecFromUser($user->ID_U);
			//$this->view->vbden = new vbdenModel(QLVBDHCommon::getYear());
			$this->view->user = $user;
		}
		function inputluuchoxulyAction(){
			$params = $this->_request->getParams();
			$this->_helper->Layout->disableLayout();
			$this->view->ID_HSCV = $params["id"];
			$this->view->data = choxulyModel::getLuuxulyInfoByHSCVId($this->view->ID_HSCV);
			//$this->view->datafolder=ThumuccanhanModel::toComboThuMucPrivate();
			//luuChoxuly
		}

		function luuchoxulyAction(){
			global $db;
			$this->_helper->layout->disableLayout();
			$params = $this->_request->getParams();
			//var_dump($params); exit;
			$comment = $params["comment"];
			$id_hscv = $params['id_hscv'];
			$year = QLVBDHCommon::getYear();
			$authen = Zend_Registry::get('auth');
			$user = $authen->getIdentity();
			//var_dump($comment); exit;
			$re = choxulyModel::luuChoxuly($id_hscv,$comment,$user->ID_U);
			//$db->insert(QLVBDHCommon::Table("hscv_fk_tmcn"),array("ID_OBJECT"=>$id_hscv,"ID_TMCN"=>$params["FOLDER"],"TYPE"=>0));
			echo "<script>window.parent.document.frm.submit();</script>";
			//echo $re;
			exit;	
		}
		function phuchoiluuchoxulyAction(){
			global $auth;
			$user = $auth->getIdentity();
			$param = $this->getRequest()->getParams();
			choxulyModel::phuchoiluuChoxuly($param['id'],$user->ID_U,$param['id_hscv']);

			echo "document.frm.submit();";
			exit;
		}
	
	}

?>