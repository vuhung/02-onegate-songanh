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
class Motcua_bieumauController extends Zend_Controller_Action
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
		$this->bieumaus = new bieumauModel();
		$this->thutucModel=new ThuTucModel();
		$this->loaiModel=new LoaiModel();
		$this->bieumaus->_id_thutuc = $filter_object;

		//assign value for search action
		$this->bieumaus->_search = $search;

		//Get data for view
		$rowcount = $this->bieumaus->Count();
		if($rowcount<=$limit) $page=1;
		$this->view->dataloai=$this->loaiModel->fetchAll();
		$this->view->data = $this->bieumaus->SelectAll(($page-1)*$limit,$limit,"TEN_BIEUMAU,ID_BIEUMAU");

		//View detail
		$this->view->title = "Quản trị biểu mẫu";
		$this->view->subtitle = "Danh sách";
		$this->view->limit = $limit;
		$this->view->search = $search;
		$this->view->page = $page;
		$this->view->filter_object=$filter_object;
if(count($this->view->dataloai)>0) $this->view->dataloai=$this->view->dataloai->toArray();
		// Lấy dữ liệu phụ
        $this->view->loais = $this->thutucModel->GetAllThuTucs();
        $this->view->showPage = QLVBDHCommon::Paginator($rowcount,5,$limit,"frmListBieuMaus",$page) ;

		//Enable button
		QLVBDHButton::EnableDelete("/motcua/bieumau/delete");
		QLVBDHButton::EnableAddNew("/motcua/bieumau/input");
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
		$bieumauModel=new bieumauModel();
		$this->loaiModel=new LoaiModel();
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
		$this->view->dataloai=$this->loaiModel->fetchAll();
		$this->view->idloai = $this->loaiModel->getLoaiHSByBieuMau($id);
		if(count($this->view->dataloai)>0) $this->view->dataloai=$this->view->dataloai->toArray();
		
		    
        QLVBDHButton::EnableSave("/motcua/bieumau/save");
		QLVBDHButton::EnableBack("/motcua/bieumau");
		//if id>0 It's edit action awake
    	if($id>0)
    	{
			$form = new bieumauForm("edit");
    		$this->view->title = "Biểu mẫu";
    		$this->view->subtitle="Chỉnh sửa";
     		$bieumaus = $bieumauModel->fetchRow('ID_BIEUMAU='.$id);
     		if($bieumaus!=null)
               		$form->populate($bieumaus->toArray());
            else  $this->_redirect('/motcua/bieumau/input');
            $this->view->form = $form;
			$this->view->noidung= $bieumaus->NOIDUNG;
			$this->view->cancu=$bieumaus->CANCU;
			$this->view->file_bieumau = $bieumaus->FILE_BIEUMAU;
            //Save your id bieumau ho so
            $this->view->id=$id;
        }
    	else
    	{
			$form = new bieumauForm();
    		$this->view->title = "Biểu mẫu";
    		$this->view->subtitle="Thêm mới";
    		$this->view->form = $form;
			$this->view->cancu=$formData["CANCU"];
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
				if($form->FILE_BIEUMAU!=null)
                {
                  if ($form->FILE_BIEUMAU->receive())
                  {
                     $this->dispatch('saveAction');
                  }
                }
                else
                {
                     $this->dispatch('saveAction');
                }
			}
        }
     	//Add button
    }
    /**
     * save/edit action of bieumau controller
     *
     */
    function saveAction()
    {
    	$formData = $this->_request->getPost();
    	$id=(int)$this->_request->getParam("id",0);
		$soluong=(int)$this->_request->getParam("SOLUONG",0);
		$file_name = $_FILES['FILE_BIEUMAU']['name'];
    	if($formData!=null)
    	{
	    	try
	    	{
	       		$data = array(
			            'TEN_BIEUMAU' 		=> $formData['TEN_BIEUMAU'],

			        	'ID_THUTUC' 	=> $formData['ID_THUTUC'],
			        	'CANCU' 		=> $formData['CANCU'],
						'SOLUONG' 		=> $soluong,
						'NOIDUNG' 		=> $formData['NOIDUNG']

			        	 );
				if($file_name!="") $data+=array('FILE_BIEUMAU'=> $file_name);
		        $bieumauModel = new bieumauModel();
		        if($id>0)
		        {
		        	$where="ID_BIEUMAU=".$id;
		        	$bieumauModel->update($data,$where);
		        }
		        else
		        {
		        	$bieumauModel->insert($data);
		        }
		        $this->_redirect('/motcua/bieumau/');

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
    	 $this->_redirect('/motcua/bieumau/input');
    }
    /**
     * delete action
     *
     */
    function deleteAction()
    {
    	$this->view->title = "Xóa Thủ tục hồ sơ";
    	//add button
    	QLVBDHButton::AddButton("Danh sách","/motcua/bieumau/","","",2);
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
						$delbieumau = new bieumauModel();
	                	$where = 'ID_bieumau = ' . $idarray[$counter];
	                	$delbieumau->delete($where);

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
					$this->_redirect('/motcua/bieumau/');
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
	public function downloadAction()
    {
        if ($this->_helper->layout)
			$this->_helper->layout->disablelayout ();
		$this->_helper->viewRenderer->setNoRender ();
		$id = $this->_request->getParam ('id');
		$bieumauModel = new bieumauModel();
        $pathdir=Zend_Registry::get ( 'config' )->sys_info->bieumau;
		if($id>0)
		{
			$data = $bieumauModel->getFileBy($id);
            //Process path
			if(file_exists($pathdir. "/". $data['FILE_BIEUMAU']))
			{
				$path = $pathdir. "/". $data['FILE_BIEUMAU'];
				$tmp_path = $path;
				$pos = strrpos ( $tmp_path, "/" );
				$filename = substr ( $tmp_path, $pos + 1, strlen ( $tmp_path ) );
				header ( "Pragma: public" );
				header ( "Expires: 0" );
				header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
				header( "Content-type:doc/pdf" );
				header ( "Content-Disposition: attachment; filename=" . $filename );
				header ( "Content-Description: binary" );
				echo file_get_contents ($path);
	            exit;
			}
			else
			{
// 				$this->_redirect("/motcua/bieumau");
				header ( "Pragma: public" );
				header ( "Expires: 0" );
				header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
				header( "Content-type:doc/pdf" );
				header ( "Content-Disposition: attachment; filename=" . $data['FILE_BIEUMAU'] );
				header ( "Content-Description: binary" );
			}
		}
		else
		{
			$this->_redirect("/motcua/bieumau");
		}
    }
    function gethosoAction()
	{
		if ($this->_helper->layout)
			$this->_helper->layout->disablelayout ();
		$this->_helper->viewRenderer->setNoRender ();
		$thutucModel=new ThuTucModel();
		$id_loaihoso=(int)$this->getRequest()->getParam("id",0);
		if($id_loaihoso>0)
		{
			$data=$thutucModel->fetchAll("ID_LOAIHOSO=".$id_loaihoso);
			if(count($data)>0) $data=$data->toArray();
			if(count($data)>0)
			{
				$result=array(
					'isValid'=>true,
					'data'=>$data,
					'message'=>"Lấy thông tin thành công !"
				);
				$json=Zend_Json::encode($result);
				echo $json;
			}
			else 
			{
				$result=array(
					'isValid'=>false,
					'data'=>NULL,
					'message'=>"Không có hồ sơ cần có nào!"
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
				'message'=>"Không có hồ sơ cần có nào!"
			);
			$json=Zend_Json::encode($result);
			echo $json;
		}		
	}
}

