<?php

/**
 * CdController
 * 
 * @author
 * @version 
 */
require_once 'qllt/models/qllt_noiluutruModel.php';
require_once 'qllt/models/vanban.php';
require_once 'Zend/Controller/Action.php';
require_once 'qllt/Models/hsltModel.php';
require_once 'Zend/Db/Table/Abstract.php';
require_once 'qllt/Models/LoaihsltModel.php';
require_once 'qtht/Models/UsersModel.php';

class Qllt_hsltController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	function indexAction()
    {   
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
		$hsltmdl=new hsltModel();
		$loaihslt = new LoaihsltModel();
		
		//assign value for search action
		$hsltmdl->_search = $search;
		$hsltmdl->parameter = $parameter;
		
		//Get data for view
		$rowcount = $hsltmdl->Count();
		if($rowcount<=$limit) $page=1;
		$this->view->data = $hsltmdl->SelectAll(($page-1)*$limit,$limit,"NGAYLUUHOSO DESC");
		
		//View detail
		$this->view->title = "Quản lý lưu trữ";
		$this->view->subtitle = "Hồ sơ";
		$this->view->limit = $limit;
		$this->view->search = $search;
		$this->view->page = $page;
		$this->view->filter_object=$filter_object;
		$this->view->parameter = $parameter;

		// Lấy dữ liệu phụ
		$this->view->lhslt = $loaihslt->GetAllLoaihoso();
		$this->view->systemName	= $config->sys_info->company;
        $this->view->showPage = QLVBDHCommon::Paginator($rowcount,5,$limit,"frmListhslt",$page) ;
		
		QLVBDHButton::EnableDelete("/qllt/hslt/delete");
		QLVBDHButton::EnableAddNew("/qllt/hslt/input");
	}

	function inputAction()
	{
		//get pars
		$id=(int)$this->_request->getParam('id_hslt');
		$this->view->id = $id;//var_dump($this->view->id);
		//View detail
		if ($id == 0)
		{
			$this->view->title = "Quản lý hồ sơ";
			$this->view->subtitle = "Thêm mới";
		}else
		{
			$this->view->title = "Quản lý hồ sơ";
			$this->view->subtitle = "Cập nhật";
			//bind db to field when update
			$hslt = new hsltModel();
			$hslts = $hslt->getHsltById($id);

			//getloaihoso by idhoso
			$loai_hs = hsltModel::getLoaihsByIdhoso($id);
			//var_dump($loai_hs);exit;
			$this->view->id_loai_hoso = $loai_hs['ID_LOAIHS'];
			$this->view->loai_hoso = $loai_hs['TEN_LOAI'];
			
			//
			$this->view->tenhoso = $hslts['TENHOSO'];
			$this->view->maso = $hslts['MASO'];
			$ArrLoai = array(0=>'',1=>'Kho',2=>'Kệ',3=>'Tầng',4=>'Ngăn',5=>'Hộp');
			$this->view->noiluutru = "Kho ".$hslts['TENKHO'];

			$this->view->id_noiluutru = $hslts['ID_NOILUUTRU'];
			$this->view->thuockho = $hslts['THUOCKHO'];
			$this->view->tenthumuc = $hslts['TENTHUMUC'];

			//$this->view->loaihoso = $hslts['LOAIHOSO'];
			$this->view->ngaybatdau = $hslts['NGAYBATDAU'];
			$this->view->ngayketthuc = $hslts['NGAYKETTHUC'];
			
			$this->view->namluutru = $hslts['NAMLUUTRU'];
			$this->view->thoihanluutru = $hslts['THOIHANLUUTRU'];
		}

		//Bind Loaihslt to ComboBox
		$loaihslt = new LoaihsltModel();
		$this->view->loaihslt = $loaihslt->GetAllLoaihoso();
		//var_dump($this->view->loaihslt);exit;

		//enable button
		QLVBDHButton::EnableSave("/qllt/hslt");
		QLVBDHButton::EnableBack("/qllt/hslt");
		QLVBDHButton::EnableHelp("");		

	}

	function saveAction()
	{	
		//get pars
		$id=(int)$this->_request->getParam('id_hslt');//var_dump($id);exit;
		$tenhoso=$this->_request->getParam('tenhoso');
		$maso=$this->_request->getParam('maso');
		$id_loaihoso=$this->_request->getParam('loaihoso');
		$ngaybatdau = $this->_request->getParam('ngaybatdau');
		$namluutru=$this->_request->getParam('namluutru');
		$ngayketthuc=$this->_request->getParam('ngayketthuc');
		$thoihanluutru=(int)$this->_request->getParam('thoihanluutru');
		$noiluutru=(int)$this->_request->getParam('noiluutru');
		$hopso=$this->_request->getParam('hopso');
		$noiluutru = hsltModel::getIdHopsoByName($hopso,$noiluutru);		
		if($noiluutru<=0){
			$this->_redirect('/default/error/error?control=hslt&mod=qllt&id=ERR01030002');
		}
		$array = array(
			'TENHOSO'=>$tenhoso, 'MASO'=>$maso, 'ID_NOILUUTRU'=>$noiluutru, 'ID_LOAIHOSO'=>$id_loaihoso,
			'NGAYBATDAU'=>$ngaybatdau, 'NGAYKETTHUC'=>$ngayketthuc, 'NAMLUUTRU'=>$namluutru, 'THOIHANLUUTRU'=>$thoihanluutru
		);
		$hs = hsltModel::GetHosoByCondition($tenhoso, $maso, $namluutru);
		//var_dump($hs);exit;
		if (count($hs) != 0)//nếu hồ sơ đã tồn tại
		{
			if ($id == 0)//trường hợp thêm mới
			{
				$this->_redirect('/default/error/error?control=hslt&mod=qllt&id=ERR01030002');	
			}
			else 
			{
				$hsltmdl=new hsltModel();
				$hsltmdl->Newhslt($id, $array);
			}
			
		}
		else// có rồi thì làm tùm lum
		{	
			//var_dump($hs);exit;
			$hsltmdl=new hsltModel();
			$hsltmdl->Newhslt($id, $array);
		}			

		$this->_redirect("/qllt/hslt/");		
	}

	function deleteAction()
	{
		$hsltmdl=new hsltModel();
		$vanban = new iso();
		$this->view->parameter =  $this->getRequest()->getParams();
		try{
			//$db = Zend_Db_Table::getDefaultAdapter();
			$vanban->delete("ID_HOSO IN (".implode(",",$this->view->parameter["DEL"]).")");

			$hsltmdl->delete("ID_HOSO IN (".implode(",",$this->view->parameter["DEL"]).")");
		}catch(Exception $ex){
			
		}
		$this->_redirect("/qllt/hslt");
	}

	function detailAction()
	{
		$this->_helper->layout->disablelayout();
		$id=(int)$this->_request->getParam('id');

		$hslt = new hsltModel();
		$hslts = $hslt->getHsltById($id);
		//var_dump($hslts);exit;
		$this->view->id_noiluutru = $hslts['ID_NOILUUTRU'];
		$this->view->tenhoso = $hslts['TENHOSO'];
		$this->view->maso = $hslts['MASO'];
		$this->view->noiluutru = $hslts['TENTHUMUC'];
		$this->view->loaihoso = $hslts['TENLOAIHOSO'];
		$this->view->ngaybatdau = $hslts['NGAYBATDAU'];
		$this->view->ngayketthuc = $hslts['NGAYKETTHUC'];
		//var_dump($this->view->ngayketthuc);exit;
		$this->view->namluutru = $hslts['NAMLUUTRU'];
		$this->view->thoihanluutru = $hslts['THOIHANLUUTRU'];
	}

}
