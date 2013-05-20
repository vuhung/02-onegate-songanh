<?php
/**
 * @name DanhMucSoVanBan
 * @author trunglv
 * @package qtht
 * @version 1.0
 */
require_once 'Zend/Controller/Action.php';
require_once 'qtht/models/SoVanBanModel.php';
require_once 'Common/ValidateInputData.php';
require_once 'config/qtht.php';
require_once 'qtht/models/CoQuanModel.php';
require_once 'motcua/models/linhvucmotcuaModel.php';
require_once 'qtht/models/UsersModel.php';
require_once 'qtht/models/DepartmentsModel.php';
class Qtht_DanhMucSoVanBanController extends Zend_Controller_Action {

	
	var $sovanbanTable; // bang So Van Ban
	
	/**
	 * Ham khoi tao du lieu
	 */
	public function init()
	{
		 $this->model = new SoVanBanModel();
		 $this->view->title="Danh mục Sổ văn bản";
	}
	/**
	 *  Ham xu ly cho action xem (view index.phtml)
	 */
	public function indexAction()
	{
		$config = Zend_Registry::get('config');
		$page = $this->_request->getParam('page');
		$limit = $this->_request->getParam('limit');
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		$this->view->subtitle="Danh sách chức danh";
		$filter_object = $this->_request->getParam('filter_object');
		$this->view->filter_object = $filter_object; 
		$search = $this->_request->getParam("search");
		$this->view->search = $search;
		$this->view->data = $this->model->findByMixed($page,$limit,$search,$filter_object);
		$n_rows = $this->model->count($search,$filter_object);
		QLVBDHButton::EnableAddNew("/qtht/DanhMucChucDanh/input");
		QLVBDHButton::EnableDelete("/qtht/DanhMucChucDanh/");
		QLVBDHButton::EnableHelp('');
		$this->view->showPage = QLVBDHCommon::Paginator($n_rows,5,$limit,"frm",$page) ;
		$this->view->limit=$limit;
		$this->view->page=$page;
		$this->view->subtitle="Danh sách";
		
	}
	
	/**
	 * Ham xu ly cho action thêm  so van ban 
	 */
	public function saveAction()
	{
		$this->view->subtitle = 'Thêm mới';
		$this->view->action = 'them';
		QLVBDHButton::EnableSave('/qtht/DanhMucSoVanBan/them');
		
		if($this->_request->isPost())
		{
			//Lay cac tham so duoc nhan tu user khi form submit voi post
			
			$active = $this->_request->getParam("active");
			if(!$active)
				$active = 0;
			$name = $this->_request->getParam("name");
			$id = $this->_request->getParam("InputIDSVB");
			$year = $this->_request->getParam("year");
			$type = $this->_request->getParam("choiceSVB");
			$lvb_lq = (int)$this->_request->getParam('choiceLVB');
			$id_lv_mc = (int)$this->_request->getParam('id_lv_mc');
			$id_cq = (int)$this->_request->getParam('choiceCQ');
			//$id_cq = (int)$this->_request->getParam('choiceIDCQ');
			$id_dep = (int)$this->_request->getParam('choiceDEP');
			if($id_dep == 0) $id_dep = null;
			$id_lv_mc = (int)$this->_request->getParam('id_lv_mc');
			if($id_lv_mc == 0) $id_lv_mc = null;
			$id_lhs_mc = (int)$this->_request->getParam('id_lhs_mc');
			
			if($id_lhs_mc == 0) $id_lhs_mc = null;
			//Kiem tra du lieu nhap
			$this->checkInputData($name,$active,$year,$type);	
			if($id > 0) 
			{
				
				//thuc hien cap nhat lai thong tin cua so van ban trong csdl
				$where = 'ID_SVB='.$id; 	
				try{
					$this->model->update(array('NAME'=>$name,"YEAR"=> $year , "TYPE"=> $type, 'ACTIVE'=> $active,'ID_LVB'=>$lvb_lq,'ID_CQ'=>$id_cq,'ID_DEP'=>$id_dep,'ID_LV_MC'=>$id_lv_mc,'ID_LHS_MC'=>$id_lhs_mc),$where);
					
				}catch (Exception $e)
				{
					//loi khon the cap nhat csdl
					$this->_redirect('/default/error/error?control=DanhMucsovanban&mod=qtht&id=ERR11006006');
				}
				
				
			}
			else{
					
				//Them vao co so du lieu
				$arr_newdata = array("NAME"=> $name ,"YEAR" => $year , "TYPE" =>$type ,"ACTIVE" => $active,'ID_LVB'=>$lvb_lq,'ID_CQ'=>$id_cq ,'ID_DEP'=>$id_dep,'ID_LV_MC'=>$id_lv_mc,'ID_LHS_MC'=>$id_lhs_mc);
				try{
						$this->model->insert($arr_newdata);	
				}catch (Exception $ex)
				{
					//Thong bao loi neu du lieu khong them vao duoc
					$this->_redirect('/default/error/error?control=DanhMucsovanban&mod=qtht&id=ERR11006007');
				}
				
			
			}
			$this->_redirect('/qtht/DanhMucSoVanBan');

			
		}
		else 
		{
			$this->_redirect('/qtht/DanhMucSoVanBan');
		}
	}
	
	/**
	 * Ham xu ly cho action xoa so van ban
	 */
	public function deleteAction()
	{
		if($this->_request->isPost())
		{
			
			//Lay id cua so van ban can xoa
			$idarray = $this->_request->getParam('DEL');
			//thuc hien xoa so van ban duoc chon
			$where = 'ID_SVB in ('.implode(',',$idarray).')'; 
			try{
				if(!$this->model->delete($where))
				{
					//Loi khong the xoa
					$this->_redirect('/default/error/error?control=DanhMucsovanban&mod=qtht&id=ERR11006008');
				}
			}catch (Exception $ex)
			{
				//loi khong the xoa cac record trong csdl
				$this->_redirect('/default/error/error?control=DanhMucsovanban&mod=qtht&id=ERR11006008');
			}
			//Hien thi trang xem danh sach so van ban
			$this->_redirect('/qtht/DanhMucSoVanBan');
			
		}
		else 
		{
			$this->_redirect('/qtht/DanhMucSoVanBan');
		}
		
	}
	
	/**
	 * Ham xu ly cho action cap nhat mot so van ban
	 */
	public function inputAction()
	{
		$user = Zend_Registry::get('auth')->getIdentity();
		$department_data = UsersModel::getUserDepId($user->ID_U);
		
		$this->view->dep_name =  $department_data["NAME"];
		//$this->view->ID_DEP = $department_data[""]
		//Tao phan button
		QLVBDHButton::EnableSave("/qtht/DanhMucChucDanh/save");
		QLVBDHButton::AddButton("Trở lại","","BackButton","BackButtonClick();");
		//Giu cac tham so cua trang danh sach
		$this->view->page = $this->_request->getParam('page');
		$this->view->limit = $this->_request->getParam('limit');
		$this->view->filter_object = $this->_request->getParam('filter_object');
		$this->view->search = $this->_request->getParam("search");	
		if($this->_request->isPost())
		{
			// Lay id cua so van ban can cap nhat
			$idCapNhat = $this->_request->getParam('idSVB');
			$this->view->id= $idCapNhat;
			
			if($idCapNhat)
			{	//Cap nhat thong tin ve so van ban 
				//Kiem tra so van ban co trong csdl hay khong
				$rowcn = $this->model->find($idCapNhat);
				if($rowcn->count() == 0)
				{
					//loi khong tim thay id trong csdl
					$this->_redirect('/default/error/error?control=DanhMucsovanban&mod=qtht&id=ERR11006002');
				}
				else {
					$this->view->namebefore = $rowcn->current()->NAME;
					$this->view->yearbefore = $rowcn->current()->YEAR;
					$this->view->type = $rowcn->current()->TYPE;
					$this->view->activeselect = $rowcn->current()->ACTIVE;
					$this->view->id_lvb = $rowcn->current()->ID_LVB;					
					$this->view->id_cq = $rowcn->current()->ID_CQ;
					$this->view->id_dep = $rowcn->current()->ID_DEP ;
					$this->view->id_lv_mc = $rowcn->current()->ID_LV_MC ;
                    $this->view->id_lhs_mc = $rowcn->current()->ID_LHS_MC ;
				}
				$this->view->subtitle = 'Cập nhật';
			}
			else{
					$params = $this->_request->getParams();
					$this->view->namebefore = $params["name"];
					$this->view->yearbefore = $params["year"];
					$this->view->type = $params["choiceSVB"];					 
					$this->view->activeselect = $params["active"];
					$this->view->id_lvb = $params["choiceLVB"];					
					$this->view->id_lv_mc = $params["id_lv_mc"];
                    $this->view->id_lhs_mc = $params["id_lhs_mc"];
                    $this->view->id =  $params["InputIDSVB"];
                    $this->view->limit=  $params["limit"];
                    $this->view->filter_object=  $params["filter_object"];
                    $this->view->search=  $params["search"];
                    $this->view->page=  $params["page"];
                    $this->view->ID_DEP=  $params["ID_DEP"];
				   $this->view->subtitle = 'Thêm mới';
			}
			
			$this->renderScript("DanhMucSoVanBan/InputData.phtml");	
		}
		
		
	}
private function checkInputData($name,$active,$year,$type){
		
		$strurl='/default/error/error?control=danhmucsovanban&mod=qtht&id=';
		$strerr = "";
		$strerr .= ValidateInputData::validateInput('text128_re',$name,'ERR11006001').",";
		$strerr .= ValidateInputData::validateInput('boolean',$active,"ERR11006005").",";
		$strerr .= ValidateInputData::validateInput('year',$year,"ERR11006003").",";
		$strerr .= ValidateInputData::validateInput('int_between_no_inclusive=0,4',$type,"ERR11006009").",";
		if(strlen($strerr)!=4){
			$this->_redirect($strurl.$strerr);
		}
		return true;
	}
	
}


