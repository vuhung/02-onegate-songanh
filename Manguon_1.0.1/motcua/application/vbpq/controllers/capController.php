<?php
/*
 * capController
 * @copyright  2009 Unitech
 * @license    
 * @version    
 * @link       
 * @since      
 * @deprecated 
 * @author Võ Chí Trường (truongvc@unitech.vn)
 */
require_once 'vbpq/models/capModel.php';
require_once 'vbpq/models/capForm.php';
require_once 'Zend/Controller/Action.php';
class Vbpq_capController extends Zend_Controller_Action 
{
	private $_capModel;
	private $_capForm;
	function init()
	{
		$this->_capModel=new capModel();
		$this->_capForm=new capForm();
	}
	
	/**
	 * This is index action for module /vbpq/cap
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
		
		//Refinde parameters
		if($limit==0 || $limit=="")$limit=100;
		if($page==0 || $page=="")$page=1;
		
		//assign value for search action
		$this->_capModel->_search = $search;
		
		//Get data for view
		$rowcount = $this->_capModel->Count();
		$this->view->data = $this->_capModel->SelectAll(($page-1)*$limit,$limit,"TEN_CAP,ID_CAP");
		
		//View detail
		$this->view->title = "Quản trị cấp văn bản pháp quy";
		$this->view->subtitle = "Danh sách";
		$this->view->limit = $limit;
		$this->view->search = $search;
		$this->view->page = $page;
		$this->view->showPage = QLVBDHCommon::Paginator($rowcount,5,$limit,"frmListCaps",$page) ;
		
		//Enable button
		QLVBDHButton::EnableDelete("/vbpq/cap/delete");
		QLVBDHButton::EnableAddNew("/vbpq/cap/input");
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
    	QLVBDHButton::EnableSave("/vbpq/cap/save");
		QLVBDHButton::EnableBack("/vbpq/cap");
		QLVBDHButton::EnableHelp("");
		$this->_capForm= new capForm();		
		//if id>0 It's edit action awake
    	if($id>0)
    	{
    		$this->view->title = "Cấp văn bản";
    		$this->view->subtitle ="Chỉnh sửa";
     		$caps = $this->_capModel->fetchRow('ID_CAP='.$id); 
			$this->view->formdata = $caps;
     		if($caps!=null)    		
               		$form->populate($caps->toArray());                
            else  $this->_redirect('/vbpq/cap/input');
            $this->view->form = $this->_capForm;
            //Save your id cap ho so
            $this->view->id=$id;           
        }
    	else 
    	{
    		$this->view->title = "Cấp văn bản";
    		$this->view->subtitle ="Thêm mới";
    		$this->view->form = $this->_capForm;
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
     * save/edit action of cap controller
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
	       				'TEN_CAP' 		=> $formData['TEN_CAP']			            
	       				 );
	       		if($id>0)	                
		        {
		        	$where="ID_CAP=".$id;
		        	$this->_capModel->update($data,$where);	        	
		        }
		        else 
		        {
		        	$id = $this->_capModel->insert($data);	        	
		        }			
		        $this->_redirect('/vbpq/cap/index/');
		      
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
    	 $this->_redirect('/vbpq/cap/input');
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
						$where = 'ID_CAP = ' . $idarray[$counter];
	                	$this->_capModel->delete($where);
						
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
					$this->_redirect('/vbpq/cap/');				
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

