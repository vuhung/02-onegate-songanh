<?php

/**
 * CdController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
require_once 'qllt/Models/loaihsltModel.php';
require_once 'qtht/models/DepartmentsModel.php';

class Qllt_loaihsltController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	function indexAction()
    {    	
		
		//$loaihsltmdl = new loaihsltModel();
		//$this->view->data =$loaihsltmdl->GetAllLoaihoso();

		//Get parameters
		$config = Zend_Registry::get('config');
		$parameter = $this->getRequest()->getParams();
		$limit = $parameter["limit"];
		$page = $parameter["page"];
		$search = $parameter["search"];
		$filter_object = $parameter["filter_object"];
		
		
		//Refinde parameters
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		if($filter_object==0 || $filter_object=="") $filter_object=0;
		
		//Define model
		$loaihsltmdl=new loaihsltModel();
		
		//assign value for search action
		$loaihsltmdl->_search = $search;
		
		//Get data for view
		$rowcount = $loaihsltmdl->Count();
		if($rowcount<=$limit) $page=1;
		$this->view->data = $loaihsltmdl->SelectAll(($page-1)*$limit,$limit,"TENLOAI");
		
		//View detail
		$this->view->title = "Loại hồ sơ lưu trữ";
		$this->view->subtitle = "Danh sách";
		$this->view->limit = $limit;
		$this->view->search = $search;
		$this->view->page = $page;
		$this->view->filter_object=$filter_object;
		// Lấy dữ liệu phụ
        $this->view->loaihslt = $loaihsltmdl->SelectAll(0,0,"");
        $this->view->showPage = QLVBDHCommon::Paginator($rowcount,5,$limit,"frmListLoaihslt",$page) ;
	
		//Enable button
		QLVBDHButton::EnableDelete("/qllt/loaihslt/delete");
		QLVBDHButton::EnableAddNew("/qllt/loaihslt/input");
	}

	function inputAction()
	{
		//get pars
		$id=(int)$this->_request->getParam('id');
		$this->view->id = $id;
		//View detail
		if ($id == 0)
		{
			$this->view->title = "Loại hồ sơ lưu trữ";
			$this->view->subtitle = "Thêm mới";
		}else
		{
			$this->view->title = "Loại hồ sơ lưu trữ";
			$this->view->subtitle = "Cập nhật";

			//bind db to field when update
			$loaihslt = new loaihsltModel();
			$loaihslts = $loaihslt->GetLoaihsltById($id);

			//lay phong ban by idloaihoso GetDepartByIdLoaihoso
			$loaihslt2 = new loaihsltModel();
			$loaihslts2 = $loaihslt2->GetDepartByIdLoaihoso($id);
			
			$this->view->phongban = $loaihslts2['NAME'];
			$this->view->idphongban = $loaihslts2['ID_DEP'];
			//var_dump($loaihslts2['NAME']);exit;

			//lấy kho by idloaihoso
			$loaihslt3 = new loaihsltModel();
			$loaihslts3 = $loaihslt3->GetKhoByIDLoaihoso($id);
			$this->view->idnoiluutru = $loaihslts3['ID_NOILUUTRU'];
			$this->view->tenthumuc = $loaihslts3['TENTHUMUC'];
			//
			$this->view->kyhieu = $loaihslts['KYHIEU'];
			$this->view->tenloai = $loaihslts['TENLOAI'];
			//$this->view->phongban = $loaihslts['TENPHONGBAN'];
			$this->view->mota = $loaihslts['MOTA'];
			$this->view->kichhoat = $loaihslts['ACTIVE'];
			
		}

		//Bind Departments to ComboBox
		$depart = new DepartmentsModel();
		$this->view->depart = $depart->GetAllDeps();	

		//bind thuoc kho to combo when update
		$loaihslts1 = new loaihsltModel();
		$this->view->thuockho = $loaihslts1->GetTenThuMuc();
		//var_dump($this->view->thuockho);exit;

		//enable button
		QLVBDHButton::EnableSave("/qllt/loaihslt");
		QLVBDHButton::EnableBack("/qllt/loaihslt");
		QLVBDHButton::EnableHelp("");
	}

	function saveAction()
	{	
		//get pars
		$id_loaihoso=(int)$this->_request->getParam('id');
		$kyhieu=$this->_request->getParam('kyhieu');
		$tenloai=$this->_request->getParam('tenloai');
		$phongban=$this->_request->getParam('phongban');
		$mota = $this->_request->getParam('mota');
		$kichhoat=$this->_request->getParam('kichhoat');
		$active = is_null($kichhoat)? '0':'1';
		$thuockho=$this->_request->getParam('thuockho');

		$array = array('KYHIEU'=>$kyhieu, 'TENLOAI'=>$tenloai, 'TENPHONGBAN'=>$phongban, 
						'MOTA'=>$mota, 'ACTIVE'=>$active, 'THUOCKHO'=>$thuockho
					);
		
		//check exits loaihoso
		$lhs = loaihsltModel::GetLoaihosoByCondition($tenloai,$kyhieu);
		//var_dump($lhs);exit;
		if (count($lhs) != 0)
		{
			
			if ($id_loaihoso == 0)//trường hợp thêm mới
			{
				$this->_redirect('/default/error/error?control=loaihslt&mod=qllt&id=ERR01030001');
			}
			else 
			{
				$loaihsltmdl=new loaihsltModel();
				$this->view->test = $id_loaihoso;
				$loaihsltmdl->NewLoaihslt($id_loaihoso, $array);
			}
		}
		else 
		{
			$loaihsltmdl=new loaihsltModel();
			$this->view->test = $id_loaihoso;
			$loaihsltmdl->NewLoaihslt($id_loaihoso, $array);
		}

		$this->_redirect("/qllt/loaihslt/");
		
	}

	function deleteAction()
	{
		$loaihsltmdl=new loaihsltModel();
		$this->view->parameter =  $this->getRequest()->getParams();
		try{
			$loaihsltmdl->delete("ID_LOAIHOSO IN (".implode(",",$this->view->parameter["DEL"]).")");
		}catch(Exception $ex){
			
		}
		$this->_redirect("/qllt/loaihslt");
	}

}
