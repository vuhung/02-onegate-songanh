<?php

/*
 * vanbanController
 * @copyright  2009 Unitech
 * @license    
 * @version    
 * @link       
 * @since      
 * @deprecated 
 * @author Võ Chí Trường (truongvc@unitech.vn)
 */
require_once 'vbpq/models/vanbanModel.php';
require_once 'vbpq/models/vanbanForm.php';
require_once 'qtht/models/CoQuanModel.php';
require_once 'qtht/models/LinhVucVanBanModel.php';
require_once 'vbpq/models/capModel.php';
require_once 'Zend/Controller/Action.php';
class Vbpq_vanbanController extends Zend_Controller_Action{
	private $_vanbanModel;
	private $_vanbanForm;
	private $_coquanModel;
	private $_capModel;
	private $_linhvucvanbanModel;
	function init()
	{
		$this->_vanbanModel=new vanbanModel();
		$this->_vanbanForm=new vanbanForm();
		$this->_coquanModel=new CoQuanModel();
		$this->_capModel=new capModel();
		$this->_linhvucvanbanModel=new LinhVucVanBanModel();
	}
	
	/**
	 * This is index action for module /vbpq/vanban
	 *
	 */
	function indexAction()
    {
    	//Get parameters
		$config = Zend_Registry::get('config');
		$parameter = $this->getRequest()->getParams();
		$limit = $parameter["limit"];
		$page = $parameter["page"];
		$p_tungay = $parameter["tungay"];
		$p_denngay = $parameter["denngay"];
		$p_sohieuvanban = $parameter["p_sohieuvanban"];
		$p_trichyeu = $parameter["p_trichyeu"];
		$p_linhvuc = $parameter["p_linhvuc"];
		$p_cap = $parameter["p_cap"];
		$p_coquanbanhanh = $parameter["p_coquanbanhanh"];
		$p_noidung = $parameter["p_noidung"];
		
		if($parameter['tungay']!="")
        {
            $tungay = new Zend_Date($parameter['tungay'],"vi_VN");
            $p_tungay=$tungay->get(Zend_Date::YEAR)."-".$tungay->get(Zend_Date::MONTH)."-".$tungay->get(Zend_Date::DAY);
            $stringtungay=$tungay->get(Zend_Date::DAY)."/".$tungay->get(Zend_Date::MONTH)."/".$tungay->get(Zend_Date::YEAR);
        } 
		if($parameter['denngay']!="")
        {
            $denngay = new Zend_Date($parameter['denngay'],"vi_VN");
            $p_denngay=$denngay->get(Zend_Date::YEAR)."-".$denngay->get(Zend_Date::MONTH)."-".$denngay->get(Zend_Date::DAY);
            $stringdenngay=$denngay->get(Zend_Date::DAY)."/".$denngay->get(Zend_Date::MONTH)."/".$denngay->get(Zend_Date::YEAR);
        } 
		
		//Refinde parameters
		if($limit==0 || $limit=="")$limit=100;
		if($page==0 || $page=="")$page=1;
		
		//assign value for search action
		$this->_vanbanModel->_tungay = $p_tungay;
		$this->_vanbanModel->_denngay = $p_denngay;
		$this->_vanbanModel->_sohieuvanban = $p_sohieuvanban;
		$this->_vanbanModel->_trichyeu = $p_trichyeu;
		$this->_vanbanModel->_linhvuc = $p_linhvuc;
		$this->_vanbanModel->_cap = $p_cap;
		$this->_vanbanModel->_coquanbanhanh = $p_coquanbanhanh;
		$this->_vanbanModel->_noidung = $p_noidung;	
		
		//Get data for view
		$rowcount = $this->_vanbanModel->Count();
		$this->view->data = $this->_vanbanModel->SelectAll(($page-1)*$limit,$limit,"TEN_CAP,ID_VBPQ");
		
		//View detail
		$this->view->title = "Văn bản pháp quy";
		$this->view->subtitle = "Danh sách";
		$this->view->limit = $limit;
		$this->view->search = $search;
		$this->view->page = $page;
		$this->view->showPage = QLVBDHCommon::Paginator($rowcount,5,$limit,"frmListVanBans",$page) ;
		$this->view->datacoquan = $this->_coquanModel->getAllCoQuan();
    	$this->view->datalinhvuc = $this->_linhvucvanbanModel->getAllLinhVuc();
    	$this->view->datacap = $this->_capModel->getAllCap();
    	
    	$this->view->tungay = $stringtungay;
		$this->view->denngay = $stringdenngay;
		$this->view->sohieuvanban = $p_sohieuvanban;
		$this->view->trichyeu = $p_trichyeu;
		$this->view->linhvuc = $p_linhvuc;
		$this->view->cap = $p_cap;
		$this->view->coquanbanhanh = $p_coquanbanhanh;
		$this->view->noidung = $p_noidung;	
		//Enable button
		QLVBDHButton::EnableDelete("/vbpq/vanban/delete");
		QLVBDHButton::EnableAddNew("/vbpq/vanban/input");
	}
    /*
     * This fuction is input action. This action display form to input data
     */
	function inputAction()
    {
    	$id=(int)$this->_request->getParam('id');    	
    	$error=$this->_request->getParam('error');
    	$formData = $this->_request->getPost();   
    	//If it has submitted from listForm
		$validFrom=$this->getRequest()->getParam("comeFrom"); 	
    	$this->view->error=$error;
    	QLVBDHButton::EnableSave("/vbpq/vanban/save");
		QLVBDHButton::EnableBack("/vbpq/vanban");
		QLVBDHButton::EnableHelp("");
		$this->_vanbanForm= new vanbanForm();		
		//if id>0 It's edit action awake
    	if($id>0)
    	{
    		$this->view->title = "Văn bản pháp quy";
    		$this->view->subtitle ="Chỉnh sửa";
     		$vanbans = $this->_vanbanModel->getDetailsOf($id);
     		if($vanbans!=null)    		
               		$this->_vanbanForm->populate($vanbans);                
            else  $this->_redirect('/vbpq/vanban/input');
            $this->view->form = $this->_vanbanForm;
            //Save your id vanban ho so
            $this->view->id=$id;
			$this->view->NOIDUNG=$vanbans["NOIDUNG"];
			$this->view->NGAYBANHANH=$vanbans["NGAYBANHANH"];
			$this->view->NGAYCOHIEULUC=$vanbans["NGAYCOHIEULUC"];
			$this->view->NGAYHETHIEULUC=$vanbans["NGAYHETHIEULUC"];    
			$this->view->vanbanlienquan=$this->_vanbanModel->getVanBanLienQuanOf($id);				
        }
    	else 
    	{
    		$this->view->title = "Văn bản pháp quy";
    		$this->view->subtitle ="Thêm mới";
    		$this->view->form = $this->_vanbanForm;
    	}
    	$this->view->datacoquan = $this->_coquanModel->getAllCoQuan();
    	$this->view->datalinhvuc = $this->_linhvucvanbanModel->getAllLinhVuc();
    	//if has error from add,update populate form and display error
    	if($error!=null)
    	{
    		$this->_vanbanForm->populate($formData); 
    	}
    	else
    	if ($this->_request->isPost() && $error==null && $validFrom==null)
        {
    		$form=$this->view->form;
    		if ($form->isValid($_POST)) 
			{ 
				$this->dispatch('saveAction');
			}
			else
			{
				$this->view->NOIDUNG=$formData["NOIDUNG"];
				$this->view->NGAYBANHANH=$formData["NGAYBANHANH"];
				$this->view->NGAYCOHIEULUC=$formData["NGAYCOHIEULUC"];
				$this->view->NGAYHETHIEULUC=$formData["NGAYHETHIEULUC"];
			}
        }
     	//Add button 
    }
    /**
     * save/edit action of vanban controller
     *
     */
    function saveAction()
    {
    	$formData = $this->_request->getPost();
    	$id=(int)$this->_request->getParam("id",0);
    	if($formData['NGAYBANHANH']!="")
        {
            $ngaybanhanh = new Zend_Date($formData['NGAYBANHANH'],"vi_VN");
            $stringngaybanhanh=$ngaybanhanh->get(Zend_Date::YEAR)."-".$ngaybanhanh->get(Zend_Date::MONTH)."-".$ngaybanhanh->get(Zend_Date::DAY);
        } 
		if($formData['NGAYCOHIEULUC']!="")
        {
            $ngaycohieuluc = new Zend_Date($formData['NGAYCOHIEULUC'],"vi_VN");
            $stringngaycohieuluc=$ngaybanhanh->get(Zend_Date::YEAR)."-".$ngaybanhanh->get(Zend_Date::MONTH)."-".$ngaybanhanh->get(Zend_Date::DAY);
        } 
		if($formData['NGAYHETHIEULUC']!="")
        {
            $ngayhethieuluc = new Zend_Date($formData['NGAYHETHIEULUC'],"vi_VN");
            $stringngayhethieuluc=$ngaybanhanh->get(Zend_Date::YEAR)."-".$ngaybanhanh->get(Zend_Date::MONTH)."-".$ngaybanhanh->get(Zend_Date::DAY);
        }      
		//van ban lien quan
		$arr_vanban=$this->getRequest()->getParam("vanban_id");
		$str_vanban=vanbanModel::filterValid($arr_vanban);  
        if($formData!=null)
    	{
	    	try 
	    	{
	       		$data = array(
	       				'ID_CAP' 		=> $formData['ID_CAP'],
	       				'MAVANBAN' 		=> $formData['MAVANBAN'],
	       				'TRICHYEU' 		=> $formData['TRICHYEU'],
	       				'NGAYBANHANH' 		=> $stringngaybanhanh,
	       				'NGAYCOHIEULUC' 		=> $stringngaycohieuluc,
	       				'NGAYHETHIEULUC' 		=> $stringngayhethieuluc,
	       				'VANBANHUONGDAN' 		=> $formData['VANBANHUONGDAN'],
	       				'NOIDUNG' 		=> $formData['NOIDUNG'],
	       				'ID_COQUANBANHANH' 		=> $formData['ID_COQUANBANHANH'],
	       				'COQUANBANHANH_TEXT' 		=> $formData['COQUANBANHANH_TEXT'],
	       				'ID_LINHVUC' 		=> $formData['ID_LINHVUC'],
	       				'NGUONVANBAN' 		=> $formData['NGUONVANBAN'],
	       				'SOKYHIEU' 		=> $formData['SOKYHIEU'],
	       				'NGUOITAO' 		=> $formData['NGUOITAO'],
	       				'NGUOIKY' 		=> $formData['NGUOIKY'],
	       				 );
	       		if($id>0)	                
		        {
		        	$where="ID_VBPQ=".$id;
		        	$this->_vanbanModel->update($data,$where);	        	
		        }
		        else 
		        {
		        	$id = $this->_vanbanModel->insert($data);	        	
		        }
		        vanbanModel::clearDataFor($id);    
		        vanbanModel::insertStringData($str_vanban,$id);									
		        $this->_redirect('/vbpq/vanban/index/');
		      
	        	//return;
			}
			catch(Exception $e2)
			{
				$messageError="Có lỗi xảy ra khi thêm mới/update dữ liệu".$e2;
				$this->_request->setParam('error',$messageError);
				$this->_request->setParams($formData);
				$this->dispatch('inputAction');			
			}
			
    	}
    	else 
    	 $this->_redirect('/vbpq/vanban/input');
    }
    /**
     * delete action
     *
     */
    function deleteAction()
    {
    	$this->view->title = "Xóa";
    	//add button
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
						$where = 'ID_VBPQ = ' . $idarray[$counter];
	                	$this->_vanbanModel->delete($where);
						
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
					$this->_redirect('/vbpq/vanban/');				
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
	function getvanbanAction()
	{
		if ($this->_helper->layout)
			$this->_helper->layout->disablelayout ();
		$this->_helper->viewRenderer->setNoRender ();
		$coquan=(int)$this->getRequest()->getParam("coquan",0);
		$linhvuc=(int)$this->getRequest()->getParam("linhvuc",0);	
		if($coquan>0 || $linhvuc>0 )
		{
			$data=$this->_vanbanModel->getAllVanBanOf($coquan,$linhvuc);
			if(count($data)>0)
			{
				$result=array(
					'isValid'=>true,
					'data'=>$data,
					'message'=>"Cập nhật thành công !"
				);
				$json=Zend_Json::encode($result);
				echo $json;
			}
			else 
			{
				$result=array(
					'isValid'=>false,
					'data'=>NULL,
					'message'=>"Không có văn bản nào!"
				);
				$json=Zend_Json::encode($result);
				echo $json;
			}
		}
		else
		{
			$result=array(
				'isValid'=>false,
				'data'=>NULL,
				'message'=>"Không có văn bản nào!"
			);
			$json=Zend_Json::encode($result);
			echo $json;
		}		
	}
	
}