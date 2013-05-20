<?php
/*
 * noiluutru Controller
 * @copyright	2010 Unitech
 * @license    
 * @version		1.0
 * @link       
 * @since      
 * @deprecated 
 * @author		Trần Quốc Bảo (baotq@unitech.vn)
 */

require_once 'qllt/models/qllt_noiluutruModel.php';
require_once 'Zend/Controller/Action.php';
require_once 'Common/ValidateInputData.php';
require_once 'config/qllt.php';

class Qllt_noiluutruController extends Zend_Controller_Action 
{
	public function init()
	{
		$this->noiluutruTable = new Qllt_noiluutruModel();
		$this->view->title = TIT01010002;
	}
	function indexAction()
    {
    	//Get parameters
		$config = Zend_Registry::get('config');
		$parameter = $this->getRequest()->getParams();

		//Refinde parameters
		if($limit	==0 || $limit	=="")$limit	=$config->limit;
		if($page	==0 || $page	=="")$page	=1;
				
		//Get data for view
		$data = array();
		QLVBDHCommon::GetTree(&$data,"qllt_noiluutru","ID_NOILUUTRU","ID_THUMUCCHA",1,1);
		$this->view->data = $data;

		//View detail		
		$this->view->title				= TIT01010001;
		$this->view->subtitle			= SUB01010001 ;
		$this->view->limit				= $limit;
		$this->view->page				= $page;
		
		//Enable button
		QLVBDHButton::EnableDelete("/qllt/noiluutru/delete");
		QLVBDHButton::EnableAddNew("/qllt/noiluutru/input");
	}     

	function inputAction()
	{
		$parameter						= $this->getRequest()->getParams();
		$id_noiluutru					=(int)$this->_request->getParam('id_noiluutru');  
		$config = Zend_Registry::get('config');
		
		$Qllt_noiluutruModel = new Qllt_noiluutruModel();
		//Get data for view
		$this->view->data = $Qllt_noiluutruModel->getFolderInfo($id_noiluutru);	
		$this->view->id_noiluutru = $id_noiluutru;
		if($id_noiluutru>0)
		{
			$this->view->title			= TIT01010002;
			$this->view->subtitle		= SUB01010003 ;
			$this->view->foldertitle	= "Tên thư mục cần chỉnh sửa" ;
			$this->view->id = $id_noiluutru;
		}
		else
		{
			$this->view->title			= TIT01010002;
			$this->view->subtitle		= SUB01010002;
			$this->view->foldertitle	= "Tên thư mục cần tạo" ;
		}
		$this->view->systemName			= $config->sys_info->company;
		
		QLVBDHButton::EnableSave("/qllt/noiluutru/save");
		QLVBDHButton::EnableBack("/qllt/noiluutru");
	}

	function saveAction()
	{
		// lấy tham số id để biết update hay insert
		$params = $this->getRequest()->getParams();
		$tenthumuc = $params["tenthumuc"];
		$idthumuccha = $params["idthumuccha"];
		$active = $params["active"];
		$ghichu = $params["ghichu"];
		$loai = $params["loai"];
		$id = $params["id"];
	
		//Kiem tra du lieu nhap
		$this->checkInput_SaveData($tenthumuc,$idthumuccha,$loai);

		// Tạo đối tượng model
		$model			= new Qllt_noiluutruModel();
		$thuockho = 0;
		if($loai != 1)
		{	
			$thuockho		= $model->getThuocKho($params["idthumuccha"]);
			if($thuockho == 0)
			{
				$thuockho = $params["idthumuccha"];
			}
		}
		else
		{
			$thuockho = 0;
		}

		//echo $thuockho.'---'.$params["idthumuccha"].'>>>'.$id; exit;

		if($id){
			try
			{
			$model->update(array( "TENTHUMUC"=>$params["tenthumuc"],
						"ID_THUMUCCHA"=>$params["idthumuccha"],
						"ACTIVE"=>$params["active"],
						"GHICHU"=>$params["ghichu"],
						"THUOCKHO"=>(int)$thuockho,
						"LOAI"=>$params["loai"]),"ID_NOILUUTRU=".(int)$id);	
			}
			catch(Exception $ex)
			{
				//lỗi cập nhật dữ liệu
				$this->_redirect('/default/error/error?control=noiluutru&mod=qllt&id=ERR01010006');
			}
			
		}
		else			
		{
			try
			{
			$model->insert(
				array( "TENTHUMUC"=>$params["tenthumuc"],
						"ID_THUMUCCHA"=>(int)$params["idthumuccha"],
						"ACTIVE"=>$params["active"],
						"GHICHU"=>$params["ghichu"],	
						"THUOCKHO"=>(int)$thuockho,
						"LOAI"=>$params["loai"]));	
			}
			catch(Exception $ex)
			{
				//lỗi thêm mới dữ liệu
				$this->_redirect('/default/error/error?control=noiluutru&mod=qllt&id=ERR01010007');
			}
		}
		$this->_redirect('/qllt/noiluutru');


		//exit;
	}

	function deleteAction()
	{		
		$this->view->parameter =  $this->getRequest()->getParams();

		$ArrId = $this->view->parameter["DEL"];
		
		$this->checkInput_DeleteData($ArrId);				

		$Qllt_noiluutruModel	= new Qllt_noiluutruModel();
		$this->view->parameter	= $this->getRequest()->getParams();
		try{
			$Qllt_noiluutruModel->delete("ID_NOILUUTRU IN (".implode(",",$this->view->parameter["DEL"]).")");
		}catch(Exception $ex){
			
			//lỗi cập nhật dữ liệu
			$this->_redirect('/default/error/error?control=noiluutru&mod=qllt&id=ERR01010008');
			
		}
		$this->_redirect("/qllt/noiluutru");
		exit;	
	}

	private function checkInput_SaveData($tenthumuc,$idthumuccha,$loai){		
		$strurl ='/default/error/error?control=noiluutru&mod=qllt&id=';
		$strerr = "";
		$strerr .= ValidateInputData::validateInput('require',$tenthumuc,'ERR01010001').",";
		$strerr .= ValidateInputData::validateInput('require',$idthumuccha,"ERR01010002").",";
		$strerr .= ValidateInputData::validateInput('require',$loai,"ERR01010003").",";

		if(strlen($strerr)!= 3){
			$this->_redirect($strurl.$strerr);
		}
		return true;
	}

	private function checkInput_DeleteData($ArrId){	
		$strurl		 ='/default/error/error?control=noiluutru&mod=qllt&id=';
		$this->noiluutrumodel	=	new Qllt_noiluutruModel();
		$strerr		 = "";
		$rowcount	 = 0;
		$countfolder = $this->noiluutrumodel->CountFolder() - 1;	

		if(count($ArrId) == 0)
		{
			$strerr		.= "ERR01010009";
		}			

		if($countfolder != count($ArrId))
		{
			for($counter = 0; $counter < count($ArrId); $counter++) 
			{
				$rowcount += $this->noiluutrumodel->CountSubFolder($ArrId[$counter]);		
			}
		}
		else
		{
			return true;
		}
		
		if($rowcount > 0)
		{	
			$strerr .= 'ERR01010004';			
		}
		if(strlen($strerr)!= 0 ){
			$lhh = strlen($strerr);
			$this->_redirect($strurl.$strerr);
		}		
		return true;		
		
	}
	
}

