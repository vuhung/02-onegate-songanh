<?php
/*
 * ketquadauraController
 * @copyright  2009 Unitech
 * @license    
 * @version    
 * @link       
 * @since      
 * @deprecated 
 * @author Võ Chí Trường (truongvc@unitech.vn)
 */
require_once 'motcua/models/ketquadauraModel.php';
require_once 'motcua/models/ketquadauraForm.php';
require_once 'Zend/Controller/Action.php';
class Motcua_ketquadauraController extends Zend_Controller_Action 
{
	/**
	 * models
	 */
    private $_ketquadauraModel;
    private $_ketquadauraForm;
	public function init()
	{
		$this->_ketquadauraModel=new ketquadauraModel();
		$this->_ketquadauraForm=new ketquadauraForm();
	}
	/**
	 * This is index action for module /motcua/ketquadaura
	 *
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
		
		
		//assign value for search action
		$this->_ketquadauraModel->_search = $search;
		
		//Get data for view
		$rowcount = $this->_ketquadauraModel->Count();
		if($rowcount<=$limit) $page=1;
		$this->view->data = $this->_ketquadauraModel->SelectAll(($page-1)*$limit,$limit,"TENKETQUA,ID_KETQUA");
		
		//View detail
		$this->view->title = "Quản trị kết quả đầu ra Một cửa";
		$this->view->subtitle = "Danh sách";
		$this->view->limit = $limit;
		$this->view->search = $search;
		$this->view->page = $page;
		$this->view->filter_object=$filter_object;
		// Lấy dữ liệu phụ
        $this->view->showPage = QLVBDHCommon::Paginator($rowcount,5,$limit,"frmListKetQua",$page) ;
		
		//Enable button
		QLVBDHButton::EnableDelete("/motcua/ketquadaura/delete");
		QLVBDHButton::EnableAddNew("/motcua/ketquadaura/input");
	}
    /*
     * This fuction is input action. This action display form to input data
     */
	function inputAction()
    {
    	$id=(int)$this->_request->getParam('id');    	
    	$error=$this->_request->getParam('error');
    	$formData = $this->_request->getPost();   
    	$parameter = $this->getRequest()->getParams();
    	//If it has submitted from listForm
		$validFrom=$this->getRequest()->getParam("comeFrom"); 	
    	$this->view->error=$error;    	
		//get current setting for page
		$limit = $parameter["limit"];
        $page = $parameter["page"];
        $search = $parameter["search"];
        $filter_object = $parameter["filter_object"];
        // Set biến cho view
        $this->view->limit = $limit;
        $this->view->search = $search;
        $this->view->page = $page;
        $this->view->filter_object = $filter_object;
    
        QLVBDHButton::EnableSave("/motcua/ketquadaura/save");
		QLVBDHButton::EnableBack("/motcua/ketquadaura");
		QLVBDHButton::EnableHelp("");
		//if id>0 It's edit action awake
    	if($id>0)
    	{
    		$this->view->title = "Kết quả đầu ra hồ sơ Một cửa";
    		$this->view->subtitle="Chỉnh sửa";
     		$thutucs = $this->_ketquadauraModel->fetchRow('ID_KETQUA='.$id); 
     		if($thutucs!=null)    		
               		$form->populate($thutucs->toArray());                
            else  $this->_redirect('/motcua/ketquadaura/input');
            $this->view->form = $this->_ketquadauraForm;
            //Save your id thutuc ho so
            $this->view->id=$id;           
        }
    	else 
    	{
    		$this->view->title = "Kết quả đầu ra hồ sơ Một cửa";
    		$this->view->subtitle="Thêm mới"; 
    		$this->view->form = $this->_ketquadauraForm;
    	}
    	//if has error from add,update populate form and display error
    	if($error!=null)
    	{
    		$form->populate($formData); 
    	}
    	else
    	if ($this->_request->isPost() && $error==null && $validFrom==null)
        {
    		$form=$this->view->form;
    		if ($form->isValid($_POST)) 
			{ 
				$this->dispatch('saveAction');
			}
        }
     	//Add button 
    }
    /**
     * save/edit action of ketquadaura controller
     *
     */
    function saveAction()
    {
    	$formData = $this->_request->getPost();
    	$id=(int)$this->_request->getParam("id",0);
    	if($formData!=null)
    	{
	    	try 
	    	{
	       		$data = array(
			            'TENKETQUA' 		=> $formData['TENKETQUA'],
			        	'CHUTHICH' 	=> $formData['CHUTHICH'],			        	
			        	'ACTIVE' 		=> $formData['ACTIVE'],
			        	 );
		        if($id>0)	                
		        {
		        	$where="ID_KETQUA=".$id;
		        	$this->_ketquadauraModel->update($data,$where);	        	
		        }
		        else 
		        {
		        	$this->_ketquadauraModel->insert($data);	        	
		        }
		        $this->_redirect('/motcua/ketquadaura/');
		      
	        	//return;
			}
			catch(Exception $e2)
			{
				$messageError="Có lỗi xảy ra khi thêm mới/update dữ liệu";
				$this->_request->setParam('error',$messageError);
				$this->_request->setParams($formData);
				$this->dispatch('inputAction');			
			}
    	}
    	else 
    	 $this->_redirect('/motcua/thutuc/input');
    }
    /**
     * delete action
     *
     */
    function deleteAction()
    {
    	$this->view->title = "Xóa Kết quả đầu ra";
    	//add button
    	QLVBDHButton::AddButton("Danh sách","/motcua/ketquadaura/","","",2);
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
						$where = 'ID_KETQUA = ' . $idarray[$counter];
	                	$this->_ketquadauraModel->delete($where);
						
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
					$this->_redirect('/motcua/thutuc/');				
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
}

