<?php
/*
 * bieumauController
 * @copyright  2009 Unitech
 * @license
 * @version
 * @link
 * @since
 * @deprecated
 * @author Võ Chí Trường (truongvc@unitech.vn)
 */
require_once 'motcua/models/bieumauModel.php';
require_once 'motcua/models/ThuTucModel.php';
require_once 'motcua/models/LoaiModel.php';
require_once 'motcua/models/bieumauForm.php';
require_once 'Zend/Controller/Action.php';
require_once 'qtht/models/CustomersModel.php';

class Qtht_CustomersController extends Zend_Controller_Action
{
	/**
	 * This is index action for module /motcua/bieumau
	 *
	 */
	function indexAction()
    {
    	$id=(int)$this->_request->getParam('id');
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
		$this->customers = new CustomersModel();

		//assign value for search action
		$this->customers->_search = $search;

		//Get data for view
		$rowcount = $this->customers->Count();
		if($rowcount<=$limit) $page=1;
		$this->view->data = $this->customers->SelectAll(($page-1)*$limit,$limit,"ID");

		//View detail
		$this->view->title = "Cá nhân - Đơn vị";
		$this->view->subtitle = "Danh sách";
		$this->view->limit = $limit;
		$this->view->search = $search;
		$this->view->page = $page;
		$this->view->filter_object=$filter_object;
		// Lấy dữ liệu phụ
        $this->view->showPage = QLVBDHCommon::Paginator($rowcount,5,$limit,"frmListCustomers",$page) ;
		//Enable button
		QLVBDHButton::EnableDelete("/qtht/customers/delete");
		QLVBDHButton::EnableAddNew("/qtht/customers/input");
	}
    /*
     * This fuction is input action. This action display form to input data
     */
	function inputAction()
    {
		$customers = new CustomersModel();
    	$id=(int)$this->_request->getParam('id');
		$this->view->id = $id;
    	$error=$this->_request->getParam('error');
    	$parameter = $this->getRequest()->getParams();
    	//If it has submitted from listForm
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
		
		    
        QLVBDHButton::EnableSave("/qtht/customers/save");
		QLVBDHButton::EnableBack("/qtht/customers/");
		//if id>0 It's edit action awake
    	if($id>0)
    	{
    		$this->view->title = "Cá nhân - Đơn vị";
    		$this->view->subtitle="Chỉnh sửa";
     		$this->view->data = $customers->GetCustomersById($id);
        }
    	else
    	{
    		$this->view->title = "Cá nhân - Đơn vị";
    		$this->view->subtitle="Thêm mới";
    		
    	}
    }
    /**
     * save/edit action of bieumau controller
     *
     */
    function saveAction()
    {
		$params = $this->_request->getParams();
    	$id=(int)$params["id"];
		$name = $params["txtName"];
		$address = $params["txtAddress"];
		$email = $params["txtEmail"];
		$phone = $params["txtPhone"];

		$customers = new CustomersModel();
		if($id > 0)
		{
			$customers->updateCustomersById($id, $name, $address, $email, $phone);
			$this->_redirect('/qtht/customers');
		} else {
			$customers->insertCustomers($name, $address, $email, $phone);
			$this->_redirect('/qtht/customers');
		}
    }
    /**
     * delete action
     *
     */
    function deleteAction()
    {
    	$this->view->title = "Xóa Thủ tục hồ sơ";
    	//add button
    	QLVBDHButton::AddButton("Danh sách","/qtht/customers/","","",2);
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
						$customers = new CustomersModel();
	                	$customers->deleteCustomersById($idarray[$counter]);

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
					$this->_redirect('/qtht/customers/');
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

