<?php
/*
 * Department controller
 * @copyright  2009 Unitech
 * @license    
 * @version    
 * @link       
 * @since      
 * @deprecated 
 * @author Võ Chí Trường (truongvc@unitech.vn)
 */
require_once 'qtht/models/DepartmentsModel.php';
require_once 'qtht/models/DepartmentsForm.php';
require_once 'qtht/models/UsersModel.php';
require_once 'Zend/Controller/Action.php';
require_once 'qtht/models/fk_deps_actionsModel.php';
class Qtht_DepartmentsController extends Zend_Controller_Action 
{
	
	/**
	 * This is index action for module /qtht/departments
	 * @return voi
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
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		
		//Define model
		$this->departments = new DepartmentsModel();

		//assign value for search action
		$this->departments->_search = $search;
		
		//Get data for view
		//$rowcount = $this->departments->Count();
		$data = array();
		QLVBDHCommon::GetTree(&$data,"QTHT_DEPARTMENTS","ID_DEP","ID_DEP_PARENT",1,1);//$this->departments->SelectAll(($page-1)*$limit,$limit,"NAME,ID_DEP");
		$this->view->data = $data;
		//View detail
		$this->view->title = "Quản trị Phòng ban";
		$this->view->subtitle = "Danh sách";
		$this->view->limit = $limit;
		$this->view->search = $search;
		$this->view->page = $page;
		$this->view->showPage = QLVBDHCommon::Paginator($rowcount,5,$limit,"frmListDepartments",$page) ;
		
		//Enable button
		QLVBDHButton::EnableDelete("/qtht/departments/delete");
		QLVBDHButton::EnableAddNew("/qtht/departments/add");
/*
		global $db;
		$r = $db->query("SELECT * FROM MOTCUA_HOSO_2009")->fetchAll();
		foreach($r as $hsmcitem){
			$sql = "SELECT pl.*,ds.CAP as CAPS,dr.CAP as CAPR FROM 
			WF_PROCESSITEMS_2009 pi
			INNER JOIN WF_PROCESSLOGS_2009 pl on pl.ID_PI = pi.ID_PI
			INNER JOIN QTHT_USERS us on pl.ID_U_SEND = us.ID_U
			INNER JOIN QTHT_EMPLOYEES es on us.ID_EMP = es.ID_EMP
			INNER JOIN QTHT_DEPARTMENTS ds on es.ID_DEP = ds.ID_DEP
			INNER JOIN QTHT_USERS ur on pl.ID_U_RECEIVE = ur.ID_U
			INNER JOIN QTHT_EMPLOYEES er on ur.ID_EMP = er.ID_EMP
			INNER JOIN QTHT_DEPARTMENTS dr on er.ID_DEP = dr.ID_DEP
			WHERE
			pi.ID_O=?
			ORDER BY pl.ID_PL
			";
			$l = $db->query($sql,$hsmcitem['ID_HSCV'])->fetchAll();
			foreach($l as $litem){
				if($litem['CAPS']!=$litem['CAPR'] && $litem['CAPR']==1){
					$db->update("MOTCUA_HOSO_2009",array("CHUYENUB_NGAY"=>$litem['DATESEND']),"ID_HSCV=".$hsmcitem['ID_HSCV']);
					break;
				}
			}
		}
		*/
	}  
     /*
     * This fuction is input action. This action display form to input data
     */
	function inputAction()
    {
    	$id=(int)$this->_request->getParam('id');    	
    	$error=$this->_request->getParam('error');
    	$formData = $this->_request->getPost();  
    	//Because rootmenu cannot edit by user, it send direct list menu if id=1(1 is id of root menu)
		if($id_p==1)   $this->_redirect('/qtht/departments/'); 
    	//If it has submitted from listForm
		$validFrom=$this->getRequest()->getParam("comeFrom"); 	
    	$this->view->error=$error;
    	QLVBDHButton::EnableSave("/qtht/departments/save");
		QLVBDHButton::EnableBack("/qtht/departments");
		$form = new DepartmentsForm(array('idCurrentDep'=>$id));
		$depModel=new DepartmentsModel();
		//if id>0 It's edit action awake
    	if($id>0)
    	{
    		$this->view->title = "Quản trị Phòng ban";
    		$this->view->subtitle ="Chỉnh sửa";
     		$deps = $depModel->fetchRow('ID_DEP='.$id); 
     		if($deps!=null)    		
            $form->populate($deps->toArray());                
            else  $this->_redirect('/qtht/departments/input');
            $this->view->form = $form;
            //Save your id 
            $this->view->id=$id;
            $this->view->user = UsersModel::SelectByDep($id);
            $this->view->ID_U_DAIDIEN = $deps->ID_U_DAIDIEN;
            $this->view->dep =$deps->CAP;
        }
    	else 
    	{
    		$this->view->title = "Quản trị Phòng ban";
    		$this->view->subtitle ="Thêm mới";
    		$this->view->form = $form;
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
		$this->fk_deps_actions = new fk_deps_actionsModel();
        
        // Khởi động các biến cho các model
        $this->view->action = $this->fk_deps_actions->SelectAllByIDDEP($id);
    }
    /**
     * save/edit action of loai controller
     *
     */
    function saveAction()
    {
    	$formData = $this->_request->getPost();
		//var_dump($formData);exit;
    	$id=(int)$this->_request->getParam("id",0);
    	$depname = $formData['NAME'];
    	$departmentsModel = new DepartmentsModel();
    	$active = $this->_request->getParam('act');
    	if($active!=null&&$id>0)
		{
			  $check=true;
			  if($active=='0') 
			  {
			  	$active='1';
			  }
			  else if($active=='1') 
			  {
			  	$active='0';
			  }
			  else $check=false;
			  if($check)
			  {
			  	$data = array(
                	'active'	=> $active,
               	 	);             
              	$where = 'ID_DEP = ' . $id;	                                     
              	$departmentsModel->update($data, $where);	                    	
              	$this->_redirect('/qtht/departments/');	
			  }
			  else $this->_redirect('/qtht/departments/');	
			  
		}
    	if($formData!=null && $depname!='')
    	{
	    	try 
	    	{
	       		$data = array(
		                    'name' => $depname,	                   
		                	'active'	=> $formData['ACTIVE'],
	       					'isleader'	=> $formData['ISLEADER'],
		                	'code_dep'=>$formData['CODE_DEP'],
		                	'KYHIEU_PB'=>$formData['KYHIEU_PB'],
		                	'id_dep_parent'	=> ($formData['ID_DEP_PARENT'] == ""?null:$formData['ID_DEP_PARENT']),
	       					'ID_U_DAIDIEN'=>($formData['ID_U_DAIDIEN'] == "0"?null:$formData['ID_U_DAIDIEN']),
					        'CAP'=>$formData['ISUBND']
		                );		      
		        if($id>0)	                
		        {
		        	$where="ID_DEP=".$id;
		        	$departmentsModel->update($data,$where);	        	
		        }
		        else 
		        {
		        	$id = $departmentsModel->insert($data);	        	
		        }
				try{
					// delete all item have this id
					$this->fk_deps_actions = new fk_deps_actionsModel();
					$this->fk_deps_actions->delete("ID_DEP=".$id);
					// insert into database for each id check in grid
					for($i=0;$i<count($formData["ID_ACT"]);$i++){            
						$this->fk_deps_actions->insert(array("ID_DEP"=>$id,"ID_ACT"=>$formData["ID_ACT"][$i]));                
					}            
				}catch(Exception $ex){
				}	
		        $this->_redirect('/qtht/departments/');
		      
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
    	 $this->_redirect('/qtht/departments/input');
    } 
    /**
     * Delete items action
     *
     */  
    function deleteAction()
    {
    	$this->view->title = "Xóa phòng ban";
    	//add button
    	QLVBDHButton::AddButton("Danh sách","/qtht/departments/","","",2);
	    //Get messages
        $this->view->deleteError = '';
        //list Id cannot delete
        $adderror='';
        $checkroot=true;
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
				if($idarray[$counter]==1)
				{
					$this->view->deleteError=' Danh mục root không được xóa';
					$checkroot=false;
				}
				else if ($idarray[$counter] > 0) 
				{
					try 
					{
						$delDepartments = new DepartmentsModel();
	                	$where = 'ID_DEP = ' . $idarray[$counter];
	                	$delDepartments->delete($where);
						
					}
					catch(Exception $er){ $adderror=$adderror.$idarray[$counter].' ; ';  };
				}
				$counter++;
			}
			//already delete some or all items
			if($counter>0 && $checkroot)
			{
				if($counter==count($idarray ))	
				{
					$this->view->deleteError="Xóa thành công các mục đã chọn";					
					$this->_redirect('/qtht/departments/');				
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
		else 
		{
		}
    }
}

