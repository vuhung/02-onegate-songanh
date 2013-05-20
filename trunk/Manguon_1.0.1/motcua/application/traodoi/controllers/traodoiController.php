<?php
/*
 * traodoi controller
 * @copyright  2009 Unitech
 * @license    
 * @version    
 * @link       
 * @since      
 * @deprecated 
 * @author Võ Chí Trường (truongvc@unitech.vn)
 */
require_once 'traodoi/models/ChuDeModel.php';
require_once 'qtht/models/UsersModel.php';
require_once 'traodoi/models/ThongTinModel.php';
require_once 'traodoi/models/NhanTempModel.php';
require_once 'traodoi/models/NhanModel.php';
require_once 'traodoi/models/ThongTinForm.php';
require_once 'hscv/models/filedinhkemModel.php';
require_once 'hscv/models/gen_tempModel.php';
require_once 'Zend/Controller/Action.php';
/**
 * traodoiController is class to control send/receive message
 * 
 * @author truongvc
 * @version 1.0
 */
class Traodoi_traodoiController extends Zend_Controller_Action 
{
	 /*
     * This is index action for module /traodoi/traodoi/
     * @return void
     */
	function indexAction()
    {
    	//Get 
    	global $auth;
		$user = $auth->getIdentity();
		$nguoitao=$user->ID_U;
		$year=QLVBDHCommon::getYear(); 
		
    	//Get parameters
		$config = Zend_Registry::get('config');
		$parameter = $this->getRequest()->getParams();		
		$search = $this->_request->getParam('search'); ;
		$limit=(int)$this->_request->getParam('limit',0);    	
		$page=(int)$this->_request->getParam('page',0); 
		$filterRead= $this->_request->getParam('filterBySelect');  	
		$filter_object=(int)$this->_request->getParam('filter_object',0);   
		$danhan=$this->_request->getParam('danhan',0);	
		
		//Refine parameters
		if($limit==0 || $limit=="")$limit=$config->limit;
		if($page==0 || $page=="")$page=1;
		if($filter_object==0 || $filter_object=="")$filter_object=0;
		
		//Define model
		$this->thongtins = new ThongTinModel($year);
		$this->chudes=new ChuDeModel();
		$this->thongtins->_id_chude = $filter_object;
		$dataInbox=$this->thongtins->CountInbox();
		
		//assign value for search action
		$this->thongtins->_search = $search;
		$this->view->drafts=$this->thongtins->CountDraft();
		$this->view->sent=$this->thongtins->CountSentItems();
		if(count($dataInbox)>0)
		{
			$this->view->inbox=$dataInbox["C"];
			$this->view->unread=$dataInbox["C"]-$dataInbox["UNREAD"];
		}
		$this->view->filterRead=$filterRead;
		$this->view->danhan=$danhan;
		
		//defines where
		$private = new Zend_Session_Namespace('private');
		$view=$this->_request->getParam('view');		
		if($view==null)
		{
			if(isset($private->view))
			{
				$view=$private->view;		
			}				
			else 
			$view="inbox";			
		}
		$this->view->pos=$view;
		//Assign the current possion to session
		if($view=="draft")
		{
			$this->thongtins->_extsearch = "1";
			$private->view="draft";					
			$rowcount = $this->thongtins->Count();
			$this->view->data = $this->thongtins->SelectAll(($page-1)*$limit,$limit,"id_thongtin DESC");
						
		}
		else if($view=="inbox")
		{
			$private->view="inbox";	
			if($filterRead!="")
			{
				$this->thongtins->_danhan=$filterRead;								
			}					
			$rowcount = $this->thongtins->CountForInbox();
			
			$this->view->data = $this->thongtins->SelectAllForInbox(($page-1)*$limit,$limit,"id_thongtin DESC,ngaygui DESC");
		}
		else if($view=="sentitems")
		{
			$this->thongtins->_extsearch = "0";
			$private->view="sentitems";

			$rowcount = $this->thongtins->Count();
			$this->view->data = $this->thongtins->SelectAll(($page-1)*$limit,$limit,"id_thongtin DESC");		
			
		}
		
		//Get data for view
		$this->view->chudedata=$this->chudes->fetchAll();
		
		//View detail
		$this->view->title = "Trao đổi nội bộ";
		$this->view->subtitle = "Quản trị tin đến/đi";
		$this->view->limit = $limit;
		$this->view->search = $search;
		$this->view->filter_object = $filter_object;
		$this->view->page = $page;
		$this->view->class=$view;
		$this->view->showPage = QLVBDHCommon::Paginator($rowcount,5,$limit,"frmTraodoi",$page) ;
		//Enable button
		QLVBDHButton::AddButton("Soạn tin","/traodoi/traodoi/input","soantin","Compose()");		
	}      
    /*
     * This fuction is input action. This action display form to input data
     */
	function inputAction()
    {
    	$year=QLVBDHCommon::getYear();
    	$form=new ThongTinForm();    	
    	
    	//initiation of models
		$thongtinModel=new ThongTinModel($year);		
		$this->chudes=new ChuDeModel();
		$nhantempmodel=new NhanTempModel($year);
		$nhanmodel=new NhanModel($year);
		$filedinhkemModel=new filedinhkemModel($year);
		$userModel=new UsersModel(); 	
		$tempTbl = new gen_tempModel();
		
		//multi autocomplete
		$this->view->usernamedata = UsersModel::getAllUserName();

		//Get variables from post
		$id=(int)$this->_request->getParam('id');    	
    	$error=$this->_request->getParam('error');
    	$formData = $this->_request->getPost();     	
    	$limit = $formData["limit"];
		$page = $formData["page"];
		$search = $formData["search"];		
		$filter_object = $formData["filter_object"];
		$action = $formData['act'];
		//validFrom use to check where it's submitted
    	$validFrom=$this->getRequest()->getParam("comeFrom"); 	
    	$idreply=(int)$this->_request->getParam('idreply');
    	$idforward=(int)$this->_request->getParam('idforward');
    	//Get variable from session    	
    	$private = new Zend_Session_Namespace('private');
    	$idTemp=(int)$this->_request->getParam('idTemp');
    	$view=$private->view;
    	$dataInbox=$thongtinModel->CountInbox();
    	
    	//set for view
		$this->view->chudedata=$this->chudes->fetchAll();	
		$this->view->title = "Trao đổi nội bộ";
		$this->view->drafts=$thongtinModel->CountDraft();
		$this->view->sent=$thongtinModel->CountSentItems();
		if(count($dataInbox)>0)
		{
			$this->view->inbox=$dataInbox["C"];
			$this->view->unread=$dataInbox["C"]-$dataInbox["UNREAD"];
		}
		$this->view->year=$year;			
		$this->view->error=$error;   
		//Add Back Button
		QLVBDHButton::EnableBack("/traodoi/traodoi");	
		//neu idreply > o mo giao dien phan hoi
		if($idreply>0) //interface for reply
		{
			//Initiation
			$arrayForForm=array(
						"nguoinhan"=>$formData["nguoinhan"],
						"tieude"=>"Hồi đáp : ".$formData["tieude"],						
						);			
			
			//Process
			$dataThongtin=$thongtinModel->fetchRow("id_thongtin=".$idreply);			
			if(count($dataThongtin)>0)
			{
				//Create message for reply action
				$dateTime = new DateTime($dataThongtin->ngaytao);
				$data_nguoitao=$userModel->getName($dataThongtin->nguoitao);
				$this->view->noidung="<br><br><br><u>Vào lúc ".$dateTime->format("h:m").", Ngày ".$dateTime->format("d/m/Y")." ".$data_nguoitao["NGUOITAO"]." đã viết</u>:";
				$this->view->noidung.="<div style='border-left-style:double; border-left-width:2px; border-bottom-color:#0033FF'>";
				$this->view->noidung.="<table width=100%>";
				$this->view->noidung.="<tr><td>";
				if($data_nguoitao!=null)
				{
					$this->view->noidung.="&nbsp;&nbsp;Gửi từ : ".$data_nguoitao["NGUOITAO"]."<i>(".$data_nguoitao["TENNGUOITAO"].")</i>";
				}
				else 
				{
					$this->view->noidung.="&nbsp;&nbsp;Gửi từ : Noname";
				}
				
				$this->view->noidung.="<br>&nbsp;&nbsp;Tiêu đề : ".$dataThongtin->tieude;
				$this->view->noidung.="<br>&nbsp;&nbsp;<u>Nội dung</u>";
				$this->view->noidung.="</td></tr>";
				$this->view->noidung.="<tr><td>";
				$this->view->noidung.=$dataThongtin->noidung;
				$this->view->noidung.="</td></tr></table>";		
				$this->view->noidung.="</div>";
				
			}
			
			//Set for view
			$this->view->subtitle = "Phản hồi";			
			$this->view->thongtinlienquan=$idreply;
			$this->view->fw_or_re=1;
			$form->populate($arrayForForm); 
			
		}
		else if($idforward>0) //interface for forward
		{
			//Copy file attachments	
			$idObject = $tempTbl->getIdTemp();
			$filedinhkemModel->copyFile($year,$idforward,$idObject,6,-1);			
			$dataThongtin=$thongtinModel->fetchRow("id_thongtin=".$idforward);
			//Process
			if(count($dataThongtin)>0)
			{
				//Create message for Forward action
				$dateTime = new DateTime($dataThongtin->ngaytao);
				$data_nguoitao=$userModel->getName($dataThongtin->nguoitao);
				$this->view->noidung="<br><br><br><u>Vào lúc ".$dateTime->format("h:m").", Ngày ".$dateTime->format("d/m/Y")." ".$data_nguoitao["NGUOITAO"]." đã viết</u>:";
				$this->view->noidung.="<div style='border-left-style:double; border-left-width:2px; border-bottom-color:#0033FF'>";
				$this->view->noidung.="<table width=100%>";
				$this->view->noidung.="<tr><td>";
				if($data_nguoitao!=null)
				{
					$this->view->noidung.="&nbsp;&nbsp;Gửi từ : ".$data_nguoitao["NGUOITAO"]."<i>(".$data_nguoitao["TENNGUOITAO"].")</i>";
				}
				else 
				{
					$this->view->noidung.="&nbsp;&nbsp;Gửi từ : Noname";
				}
				$this->view->noidung.="<br>&nbsp;&nbsp;Tiêu đề : ".$dataThongtin->tieude;
				$this->view->noidung.="<br>&nbsp;&nbsp;<u>Nội dung</u>";
				$this->view->noidung.="</td></tr>";
				$this->view->noidung.="<tr><td>";
				$this->view->noidung.=$dataThongtin->noidung;
				$this->view->noidung.="</td></tr></table>";		
				$this->view->noidung.="</div>";
			}
			
			//Set for view
			$this->view->subtitle = "Chuyển tiếp";	
			$this->view->idTemp	=$idObject;
			$this->view->thongtinlienquan=$idforward;
			$this->view->fw_or_re=2;
			$this->view->type=-1;
			$form->populate(array("tieude"=>"Chuyển tiếp : ".$formData["tieude"])); 
		} 
		else if($id>0)
    	{
    		//Process
    		$nguoinhanString="";
    		if($view=="draft")
			{
				$nguoinhandata=$nhantempmodel->getNguoiNhan($id);
				foreach($nguoinhandata as $nguoinhan)
				{
					$nguoinhanString.=$nguoinhan['USERNAME'].";";
				}						
			}			
			$thongtins = $thongtinModel->fetchRow('id_thongtin='.$id);      		
     		if(count($thongtins)>0)    		
     		{
		   		$form->populate($thongtins->toArray()+array("nguoinhan"=>$nguoinhanString));  
		   		$this->view->noidung =$thongtins->noidung;             		   		
     		}
            
            else  $this->_redirect('/traodoi/traodoi/input');
            //Set for view
            $this->view->subtitle = "Tin nháp";	
            $this->view->form = $form;
            $this->view->idTemp=$id; 
            $this->view->id=$id;     
            $this->view->class=$view;      
        }
    	else 
    	{
    		$this->view->title = "Trao đổi nội bộ";
			$this->view->subtitle = "Tạo tin mới";
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
				if($action=="draft")
		    	{
		    		$this->dispatch('savedraftAction');
		    	}
		    	else if($action=="send")
		    	{
		    		$this->dispatch('saveAction');
		    	}
		    	else 
		    	{
		    		$this->_redirect('/traodoi/traodoi/index/from/received');
		    	}
				
			}
			else 
			{
				$form->populate($formData); 				
				$this->view->noidung=$formData['noidung'];	
			}
        }
        //Set for view
		$this->view->form=$form;
		$this->view->limit = $limit;
		$this->view->search = $search;
		$this->view->page = $page;
		$this->view->filter_object = $filter_object;
		
    }
    /**
     * save/edit action of loai controller
     *
     */
    function saveAction()
    {
    	//Init
    	global $auth;
		$user = $auth->getIdentity();
		$nguoitao=$user->ID_U;
		$year=QLVBDHCommon::getYear();
		//Create current date
    	$today = date("Y-m-d h:m:s");
    	//Get value
    	$formData = $this->_request->getPost();    
    	$id=(int)$this->_request->getParam("id",0);     	   	
    	$limit = $formData["limit"];
		$page = $formData["page"];
		$search = $formData["search"]; 
		$arrayIdFile=$formData['idFile'];	
		$filter_object = $formData["filter_object"];
		//Get array of sent user
		$arrayUsers = split(";",$formData["nguoinhan"]);		
		$whereUser="";		
		if(count($arrayUsers)>0)
		{
			for($i=0;$i<count($arrayUsers);$i++)
			{
				if(strlen($arrayUsers[$i])>0)
				{
					$whereUser.="USERNAME='".trim($arrayUsers[$i])."' OR ";								
				}
			}
		}			
		if(strlen($whereUser)>4)	
			$whereUser=substr($whereUser,0,count($whereUser)-4);		
		if($whereUser=="") $this->_redirect('/traodoi/traodoi/');
		//Init model   	
    	$thongtins =new ThongTinModel($year);
    	$usersmodel=new UsersModel();
    	$nhantempmodel=new NhanTempModel($year);
    	$nhanmodel=new NhanModel($year);    
    	$filedinhkemModel=new filedinhkemModel($year);      	
    	if($formData!=null)
    	{
	    	try 
	    	{
	    		//data for insert or update.
	       		$data = array(
		                    'id_chude' 		=> $filter_object,
		                	'thongtinlienquan' 	=> ($formData['thongtinlienquan']>0?$formData['thongtinlienquan']:null),
		                	'nguoitao' 		=> $nguoitao,
		                	'tieude' 		=> $formData['tieude'],
		                	'noidung' 	=> stripslashes($formData['noidung']),
		                	'ngaytao'	=> $today,
		                	'fw_or_re'=>($formData['fw_or_re']>0?$formData['fw_or_re']:null),
		                	'hienthi'=> 1,
		                	'draft' 	=> 0,
		                	
		                );		        
		        if($id>0) //update	                
		        {
		        	$where="id_thongtin=".$id;
		        	$thongtins->update($data,$where);		        	
		        	try 
		        	{
		        		$nhantempmodel->delete("id_thongtin=".$id);        	
		        	}
		        	catch(Exception $e3)
		        	{
		        		
		        	}
		        }
		        else //Insert
		        {
		        	$id=$thongtins->insert($data);	
		        	
		        }
		        try 
		        {
		        	//If draft message is created Then temp user create
		        	$dataUsers=$usersmodel->fetchAll($whereUser);
		        	if(count($dataUsers)>0)
			    	{
			    		foreach ($dataUsers as $row )
						{
							try 
							{
									$datatemp=array
					    			(
					    				'nguoinhan'=>$row->ID_U,
					    				'id_thongtin'=>$id,
					    				'ngaygui'=>$today,
					    				
					    			);
					    			$nhanmodel->insert($datatemp);
					    			QLVBDHCommon::SendMessage(
										$nguoitao,
										$row->ID_U,
										$formData['tieude'],
										"traodoi/traodoi/index"
									);
							}
							catch(Exception $e4){}
						}
			    	}        	
	        	}
	        	catch(Exception $e2)
	        	{
	        		
	        	}
	        	//Save attachment files
	        	if(count($arrayIdFile)>0)
	           	{	           	
		           	for($i=0;$i<count($arrayIdFile);$i++)
		        	{   
	                	$filedinhkemModel->update(array("ID_OBJECT"=>$id,"TYPE"=>6),"MASO='".$arrayIdFile[$i]."'");				  
		        	}
	           	}
		        $this->_redirect('/traodoi/traodoi/');	        	
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
    	$this->_redirect('/traodoi/traodoi/input');
    } 
    /**
     * savedraft action
     *
     */
    function savedraftAction()
    {
    	//Get current user
    	global $auth;
		$user = $auth->getIdentity();
		$nguoitao=$user->ID_U;
		$year=QLVBDHCommon::getYear();
		//Get current date
    	$today = date("Y-m-d h:m:s");    	    	
		//Get data from post
    	$formData = $this->_request->getPost();
    	$id=(int)$this->_request->getParam("id",0); 
       	$limit = $formData["limit"];
		$page = $formData["page"];
		$search = $formData["search"]; 
		$arrayIdFile=$formData['idFile'];		
		$filter_object = $formData["filter_object"];
		//Process user
		$arrayUsers = split(";",$formData["nguoinhan"]);
		$whereUser="";
		if(count($arrayUsers)>0)
		{
			for($i=0;$i<count($arrayUsers);$i++)
			{
				if(strlen($arrayUsers[$i])>0)
				{
					$whereUser.="USERNAME='".$arrayUsers[$i]."' OR ";
				}
			}
		}
		if(strlen($whereUser)>4)
			$whereUser=substr($whereUser,0,count($whereUser)-4);
		//Init models	
    	$thongtins =new ThongTinModel($year);
    	$usersmodel=new UsersModel();
    	$nhantempmodel=new NhanTempModel($year); 
    	$filedinhkemModel=new filedinhkemModel($year); 	    	
    	if($formData!=null)
    	{
	    	try 
	    	{
	    		//create data for insert or update
	       		$data = array(
		                    'id_chude' 		=> $filter_object,
		                	'thongtinlienquan' 	=> ($formData['thongtinlienquan']>0?$formData['thongtinlienquan']:null),
		                	'nguoitao' 		=> $nguoitao,
		                	'tieude' 		=> $formData['tieude'],
		                	'noidung' 	=> stripslashes($formData['noidung']),
		                	'ngaytao'	=> $today,
		                	'hienthi'=> 1,
		                	'draft' 	=> 1,
		                	
		                );		        
		        if($id>0)	//update                
		        {
		        	$where="id_thongtin=".$id;
		        	$thongtins->update($data,$where);
		        	try 
		        	{
		        		$nhantempmodel->delete("id_thongtin=".$id);        	
		        	}
		        	catch(Exception $e3){}
		        }
		        else //insert
		        {
		        	$id=$thongtins->insert($data);			        	
		        }		       
		        try 
		        {
		        	//create temp user
		        	$dataUsers=$usersmodel->fetchAll($whereUser);
		        	if(count($dataUsers)>0)
			    	{
			    		foreach ($dataUsers as $row )
						{
							try 
							{
									$datatemp=array
					    			(
					    				'nguoinhan'=>$row->ID_U,
					    				'id_thongtin'=>$id,
					    				
					    			);
					    			$nhantempmodel->insert($datatemp);
							}
							catch(Exception $e4){}				    		
						}						
			    	}        	
	        	}
	        	catch(Exception $e2)
	        	{
	        		
	           	}
	           	//Update attachment files
	           	if(count($arrayIdFile)>0)
	           	{	           	
		           	for($i=0;$i<count($arrayIdFile);$i++)
		        	{   
	                	$filedinhkemModel->update(array("ID_OBJECT"=>$id,"TYPE"=>6),"MASO='".$arrayIdFile[$i]."'");				  
		        	}
	           	}
	        	$this->_redirect('/traodoi/traodoi/');
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
    	$this->_redirect('/traodoi/traodoi/input');
    }
    /**
     * viewinbox action
     *
     */
    function viewinboxAction()
    {
    	//Get
    	$year=QLVBDHCommon::getYear();
    	$form=new ThongTinForm();
    	global $auth;
		$user = $auth->getIdentity();
		$nguoinhan=$user->ID_U;
		//Get current date
		$today = date("Y-m-d h:m:s");
		//Get data		
		$id=(int)$this->_request->getParam('id');    	
    	$error=$this->_request->getParam('error');
    	$formData = $this->_request->getPost();  
    	$limit = $formData["limit"];
		$page = $formData["page"];
		$search = $formData["search"];		
		$filter_object = $formData["filter_object"];
    	//If it has submitted from listForm
    	$action = $formData['act'];
		$validFrom=$this->getRequest()->getParam("comeFrom"); 	
    	$this->view->error=$error;   
    	$private = new Zend_Session_Namespace('private');
    	$view=$private->view;   
    		
    	//Init models
		$thongtinModel=new ThongTinModel($year);		
		$this->chudes=new ChuDeModel();
		$nhantempmodel=new NhanTempModel($year);
		$nhanmodel=new NhanModel($year);
		$dataInbox=$thongtinModel->CountInbox();
    	
    	//View
		$this->view->chudedata=$this->chudes->fetchAll();	
		$this->view->drafts=$thongtinModel->CountDraft();
		$this->view->sent=$thongtinModel->CountSentItems();	
		if(count($dataInbox)>0)
		{
			$this->view->inbox=$dataInbox["C"];
			$this->view->unread=$dataInbox["C"]-$dataInbox["UNREAD"];
		}
		$this->view->year=$year;
		$this->view->isreadonly=1;
		$this->view->class=$view;	
		QLVBDHButton::EnableBack("/traodoi/traodoi");		
		
		//if id>0 It's edit action awake
    	if($id>0)
    	{
    			$this->view->title="Trao đổi nội bộ";
    			$this->view->subtitle = "Tin đến";	
				$this->view->nguoinhans=$nhanmodel->getNguoiNhan($id);											
	     		$thongtins = $thongtinModel->fetchRow('id_thongtin='.$id);   
	     		$nguoigui=$thongtinModel->getNguoiGui($id);	     		
	     		if(count($thongtins)>0)    		
	     		{
	     			$extArray=array(
	     						"nguoinhan"=> $nguoigui['nguoigui'],	     						
	     					   );
			   		$form->populate($thongtins->toArray()+$extArray);  
			   		$this->view->noidung =$thongtins->noidung; 			   		
			   		$this->view->tennguoigui=$nguoigui['tennguoigui'];	
			   		if($thongtins->thongtinlienquan >0 )
			   		{
			   			$thongtinModel->getThongTinLienQuan(&$dataLienquan,$thongtins->thongtinlienquan,$nguoinhan);	
			   			$dataLienquan=array_reverse($dataLienquan);		   			
			   			$this->view->lienquan=$dataLienquan;			   			
			   		}
	     		}
	           
	            
	            //Update status 'ngaynhan' && 'danhan'
	            $data = array(
		                    'ngaynhan'	=> $today,
		                	'danhan'=> 1,	                	                	
		                );	
		        try 
		        {
		        	$whereTemp="id_thongtin=".$id." AND nguoinhan=".$nguoinhan;		        		        	
		        	$nhanmodel->update($data,$whereTemp);
		        	
		        }
		        catch(Exception $e5)
		        {
		        	echo $e5;
		        }
		        //View		        
		        $this->view->idTemp=$id;  		        
	            $this->view->form = $form;	            
	            $this->view->id=$id; 	           
        }
    	else //Create new
    	{
    		$this->view->title = "Trao đổi nội bộ";
			$this->view->subtitle = "Tạo tin mới";
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
				if($action=="draft")
		    	{
		    		$this->dispatch('savedraftAction');
		    	}
		    	else if($action=="send")
		    	{
		    		$this->dispatch('saveAction');
		    	}
		    	else 
		    	{
		    		$this->_redirect('/traodoi/traodoi/index/from/received');
		    	}
				
			}
        }
        //View		
		$this->view->form=$form;
		$this->view->limit = $limit;
		$this->view->search = $search;
		$this->view->page = $page;
		$this->view->filter_object = $filter_object;
    }
    /**
     * viewsentitems action
     *
     */
    function viewsentitemsAction()
    {
    	//Get 
    	$year=QLVBDHCommon::getYear();
    	$form=new ThongTinForm();
    	$id=(int)$this->_request->getParam('id');    	
    	$error=$this->_request->getParam('error');
    	$formData = $this->_request->getPost();  
    	$limit = $formData["limit"];
		$page = $formData["page"];
		$search = $formData["search"];		
		$filter_object = $formData["filter_object"];
    	//If it has submitted from listForm
    	$action = $formData['act'];
		$validFrom=$this->getRequest()->getParam("comeFrom"); 	
    	$this->view->error=$error;     	
    	$private = new Zend_Session_Namespace('private');
    	$view=$private->view;    	
    	
    	//Init models
    	$thongtinModel=new ThongTinModel($year);		
		$this->chudes=new ChuDeModel();
		$nhantempmodel=new NhanTempModel($year);
		$nhanmodel=new NhanModel($year);
		$dataInbox=$thongtinModel->CountInbox();    	
    	QLVBDHButton::EnableBack("/traodoi/traodoi");	
    		
		//if id>0 It's edit action awake
    	if($id>0)  //view sent items
    	{
    			$this->view->title="Trao đổi nội bộ";
    			$this->view->subtitle = "Tin đã gửi";	
				$this->view->nguoinhans=$nhanmodel->getNguoiNhan($id);											
	     		$thongtins = $thongtinModel->fetchRow('id_thongtin='.$id);   
	     		$nguoigui=$thongtinModel->getNguoiGui($id);	     		
	     		if(count($thongtins)>0)    		
	     		{
	     			$extArray=array(
	     						"nguoinhan"=> $nguoigui['nguoigui'],	     						
	     					   );
			   		$form->populate($thongtins->toArray()+$extArray);  
			   		$this->view->noidung =$thongtins->noidung;             		   		
			   		$this->view->tennguoigui=$nguoigui['tennguoigui'];
	     		}
	            
	            else  $this->_redirect('/traodoi/traodoi/index/from/received');
	            //View
	            $this->view->form = $form;	           
	            $this->view->id=$id; 
	            $this->view->idTemp=$id;            
        }
    	else //Create new
    	{
    		$this->view->title = "Trao đổi nội bộ";
			$this->view->subtitle = "Tạo tin mới";
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
				if($action=="draft")
		    	{
		    		$this->dispatch('savedraftAction');
		    	}
		    	else if($action=="send")
		    	{
		    		$this->dispatch('saveAction');
		    	}
		    	else 
		    	{
		    		$this->_redirect('/traodoi/traodoi/index/from/received');
		    	}
				
			}
        }
        //View detail
		$this->view->chudedata=$this->chudes->fetchAll();
		$this->view->drafts=$thongtinModel->CountDraft();
		$this->view->sent=$thongtinModel->CountSentItems();
		$this->view->inbox=$thongtinModel->CountInbox();	
		if(count($dataInbox)>0)
		{
			$this->view->inbox=$dataInbox["C"];
			$this->view->unread=$dataInbox["C"]-$dataInbox["UNREAD"];
		}
		$this->view->year=$year;
		$this->view->isreadonly=1;
		$this->view->is_new=0;
		$this->view->class=$view;
        //View	
		$this->view->form=$form;
		$this->view->limit = $limit;
		$this->view->search = $search;
		$this->view->page = $page;
		$this->view->filter_object = $filter_object;
    }
    /*
     * This is delete action 
     * @return void
     */   
    function deleteAction()
    {
    	//Get data
    	global $auth;
		$user = $auth->getIdentity();
		$nguoitao=$user->ID_U;
        //Get messages
        $this->view->deleteError = '';
        //list Id cannot delete
        $adderror='';
        $year=QLVBDHCommon::getYear();
        
        //Init models
        $thongtinmodel=new ThongTinModel($year);
        $delchudes = new ChuDeModel();
        $nhantempmodel=new NhanTempModel($year);
        $nhanmodel=new NhanModel($year);
       
        
        //defines where
		$private = new Zend_Session_Namespace('private');
		$view=$this->_request->getParam('view');		
		if($view==null)
		{
			if(isset($private->view))
			{
				$view=$private->view;		
			}				
			else 
			$view="inbox";			
		}
		$this->view->pos=$view;		
		//Assign the current possion to session		
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
					if($view=="draft")   //delete draft
					{
						try 
						{
							$data=array( "hienthi"=>0);						
		                	$where = 'id_thongtin = ' . $idarray[$counter];
		                	$thongtinmodel->update($data,$where);	                	
							
						}
						catch(Exception $er){ }
									
					}
					else if($view=="inbox")	//delete inbox
					{
						try 
						{
							$data=array( "hienthi"=>0);												
		                	$where = 'id_thongtin = ' . $idarray[$counter].' AND nguoinhan='.$nguoitao;		                	
		                	$nhanmodel->update($data,$where);	                	
							
						}
						catch(Exception $er){ echo $er;exit;}
					}
					else if($view=="sentitems") //delete sent items
					{
							
						try 
						{
							$data=array( "hienthi"=>0);						
		                	$where = 'id_thongtin = ' . $idarray[$counter];
		                	$thongtinmodel->update($data,$where);	                	
							
						}
						catch(Exception $er){ }
					}
					
				}
				$counter++;
			}
			//already delete some or all items
			if($counter>0)
			{
				if($counter==count($idarray ))	
				{
					$this->view->deleteError="Xóa thành công các mục đã chọn";
					$this->_redirect('/traodoi/traodoi/');
										
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
			$this->_redirect('/traodoi/traodoi/');
		}
	
    }
    /*
     * This is update action for module
     * @return void
     */   
    function updateAction()
    {
    	//get data
    	global $auth;
		$user = $auth->getIdentity();
		$nguoitao=$user->ID_U;		
    	$danhan = (int)$this->_request->getParam('danhan',0);    	
    	$private = new Zend_Session_Namespace('private');
		$view=$this->_request->getParam('view');		
		
    	//Int model
        $year=QLVBDHCommon::getYear();
        $thongtinmodel=new ThongTinModel($year);
        $delchudes = new ChuDeModel();
        $nhantempmodel=new NhanTempModel($year);
        $nhanmodel=new NhanModel($year);
        //defines where
		if($view==null)
		{
			if(isset($private->view))
			{
				$view=$private->view;		
			}				
			else 
			$view="inbox";			
		}
		//Assign the current possion to session		
    	if($this->_request->isPost())
		{
			$idarray = $this->_request->getParam('DEL');	
			$counter=0;
			while ( $counter < count($idarray )) 
			{
				if ($idarray[$counter] > 0) 
				{
					if($view=="inbox")	//update for inbox
					{
						try 
						{
							$data=array( "danhan"=>$danhan);						
		                	$where = 'id_thongtin = ' . $idarray[$counter].' AND nguoinhan='.$nguoitao;
		                	$nhanmodel->update($data,$where);	                	
							
						}
						catch(Exception $er)
						{ 
							echo $er;							
							$this->_redirect('/traodoi/traodoi/');
						}
					}					
				}
				$counter++;
			}
			$this->_redirect('/traodoi/traodoi/');
		}	
		else 
		$this->_redirect('/traodoi/traodoi/');
    }
    /**
     * income Ajax : get incoming message in index
     *
     */
    function incomeAction()
    {
    	//Get
    	global $auth;
		$user = $auth->getIdentity();
		$nguoinhan=$user->ID_U;		
    	$this->_helper->layout->disableLayout();
    	$checkData =  $this->getRequest()->getParams(); 
    	$filter_object=(int)$this->_request->getParam('filter_object',0);
    	$this->view->page = (int)$this->_request->getParam('page',1);
    	
    	$year=QLVBDHCommon::getYear();
    	//init model
    	$thongtinModel=new ThongTinModel($year);
    	if($filter_object>0) $thongtinModel->setValueIdChuDe($filter_object);
    	//Process
    	$arrayId = split(",",$checkData["stringid"]);    			
		$whereIdTinNhan="";		
		if(count($arrayId)>0)
		{
			for($i=0;$i<count($arrayId);$i++)
			{
				if(strlen($arrayId[$i])>0)
				{
					$whereIdTinNhan.="id_nhan <> ".$arrayId[$i]." AND ";								
				}
			}
		}	
		if(strlen($whereIdTinNhan)>4)	
			$whereIdTinNhan=substr($whereIdTinNhan,0,count($whereUser)-5);
		if($topid>0)
			$whereIdTinNhan .=" AND id_nhan > ".$topid;			
		if(strlen($whereIdTinNhan)>0)
    		$dataMoiNhan=$thongtinModel->getMoiNhan($whereIdTinNhan,$nguoinhan);  
    	//Build output  
    	$json = '{"messages": {';	
    	if(count($dataMoiNhan)>0)
    	{
    		$json .= '"message":[ ';	
    		foreach($dataMoiNhan as $rows)			
			{
			$json .= '{';
			$json .= '  "id_thongtin":  "' . $rows['id_thongtin'] . '",
						"username": "' . htmlspecialchars($rows['username']).count($dataMoiNhan). '",
						"realname": "' . htmlspecialchars($rows['nguoitao']) . '",
						"tieude": "' . htmlspecialchars($rows['tieude']) . '",						
						"danhan": "' . htmlspecialchars($rows['danhan']) . '",
						"ngaytao": "' . $rows['ngaytao'] . '",
						"id_nhan": "' . $rows['id_nhan'] . '"
					},';
			}
			$json .= ']';
    	} 
    	else 
    		$json .= '"message":[]';  
    	$json .= '}}'; 	
    	echo $json;    	
    	exit;
    }
     
}
