<?php
/*
 * Muontra controller
 * @copyright  2010 Unitech
 * @license    
 * @version    
 * @link       
 * @since      
 * @deprecated 
 * @author Trần Quốc Bảo (baotq@unitech.vn)
 */
require_once 'qllt/Models/qllt_muontraModel.php';
require_once 'qllt/Models/hsltModel.php';
require_once 'Zend/Controller/Action.php';
require_once 'Common/ValidateInputData.php';
require_once 'config/qllt.php';
class Qllt_muontraController extends Zend_Controller_Action 
{	
	function indexAction()
    {			
		$this->_helper->layout->disablelayout();
		$config = Zend_Registry::get('config');
		$parameter = $this->getRequest()->getParams();
		$id_hoso = $parameter["id_hoso"];
		$this->view->id_hoso	= $id_hoso;		
		//Define model
		$hsltmdl=new Qllt_muontraModel();

		//Get data for view
		$this->view->data = $hsltmdl->SelectById($id_hoso);		
	}     

	function addnewAction()
	{
			$this->_helper->layout->disablelayout();	
			$parameter					= $this->getRequest()->getParams();
			$IdFrame					= $parameter["IdFrame"];
			$this->view->IdFrame		= $IdFrame;
			$id_hoso					= $parameter["id_hoso"];
			$this->view->id_hoso		= $id_hoso;
			$ngaytra_thucte				= $parameter["ngaytra_thucte"];			
			$this->view->ngaytra_thucte	= implode("/",array_reverse(explode("-",$ngaytra_thucte)));
	}

	function updateAction()
	{
			$this->_helper->layout->disablelayout();	
			$parameter					= $this->getRequest()->getParams();
			$IdFrame					= $parameter["IdFrame"];
			$this->view->IdFrame		= $IdFrame;
			$id_hoso					= $parameter["id_hoso"];			
			$this->view->id_hoso		= $id_hoso;
			$last_ngaymuon				= $parameter["last_ngaymuon"];			
			$this->view->last_ngaymuon	= implode("/",array_reverse(explode("-",$last_ngaymuon)));
			$id_muontra					= $parameter["idmt"];
			$this->view->id_muontra		= $id_muontra;
			
	}

	function saveAction()
	{
			global $auth;
			$user					= $auth->getIdentity();
			$parameter				= $this->getRequest()->getParams();
			$execute				= $parameter["execute"];
			$idhoso					= $parameter["idhoso"];		
			$HsltModel = new HsltModel();	
			if($execute == "insert")
			{			
				$idnguoimuon			= $parameter["idnguoimuon"];
				$idnguoichomuon			= $user->ID_U;
				$ngaymuon				= $parameter["ngaymuon"];
				$ngayphaitra			= $parameter["ngayphaitra"];
							
				$id_u_tra				= 0;
				$ngaytrathucte			= null;
				try
				{
					$this->checkInput_AddnewData($ngaymuon,$ngayphaitra,$idnguoimuon,$idhoso);

					// Tạo đối tượng model
					$model = new Qllt_muontraModel();		
					
					$model->insert(
							array( "ID_HOSO"=>(int)$idhoso,
									"NGAY_MUON"=>implode("-",array_reverse(explode("/",$ngaymuon))),
									"NGAY_TRA"=>implode("-",array_reverse(explode("/",$ngayphaitra))),
									"ID_U_MUON"=>(int)$idnguoimuon,		
									"ID_U_CHOMUON"=>$idnguoichomuon,
									"ID_U_TRA"=>$id_u_tra,
									"NGAYTRA_THUCTE"=>$ngaytrathucte));				
					
					$fileview = $model->getFileView($idhoso);
					$is_muontra = 1;
					$fileview ++;
					$HsltModel->update(array("SOLANMUON"=>$fileview,"IS_MUONTRA"=>$is_muontra),"ID_HOSO=".(int)$idhoso);						
						echo "<script>window.parent.loadDivFromUrl('groupcontent".$idhoso."','/qllt/muontra/index/id_hoso/".$idhoso."',1);</script>";	
				}
				catch(Exception $ex)
				{
					//lỗi chèn dữ liệu
					$this->_redirect('/default/error/error?control=muontra&mod=qllt&id=ERR01020015');
				}						
			
			}
			if($execute == "update")
			{
				$id_u_tra				= $parameter["id_nguoitra"];
				$ngaytrathucte			= $parameter["ngaytrathucte"];
				$id_muontra				= $parameter["id_muontra"];		

				try{
					$this->checkInput_UpdateData($id_muontra,$id_u_tra,$ngaytrathucte,$idhoso);

					// Tạo đối tượng model
					$model = new Qllt_muontraModel();		
					$is_muontra = 2;

					$model->update(array("ID_U_TRA"=>$id_u_tra,"NGAYTRA_THUCTE"=>implode("-",array_reverse(explode("/",$ngaytrathucte)))),"ID_MUONTRA=".(int)$id_muontra);		
					
					$HsltModel->update(array("IS_MUONTRA"=>$is_muontra),"ID_HOSO=".(int)$idhoso);	
						echo "<script>window.parent.loadDivFromUrl('groupcontent".$idhoso."','/qllt/muontra/index/id_hoso/".$idhoso."',1);</script>";	
			
				}
				catch(Exception $ex)
				{
					//lỗi cập nhật dữ liệu
					$this->_redirect('/default/error/error?control=muontra&mod=qllt&id=ERR01020014');
				}
			}	
			
			exit;
	}

	function deleteAction()
	{
		$this->_helper->layout->disablelayout();	
		$parameter				= $this->getRequest()->getParams();
		$id_hoso				= $parameter["idhoso"];
		$id_muontra				= $parameter["idmt"];
		$this->view->id_hoso	= $id_hoso;	
		
		$this->checkInput_DeleteData($id_muontra);

		$Qllt_muontraModel	= new Qllt_muontraModel();
		try
		{
			$Qllt_muontraModel->delete("ID_MUONTRA IN (".$id_muontra.")");
		}
		catch(Exception $ex)
		{
			//lỗi xóa dữ liệu
			$this->_redirect('/default/error/error?control=muontra&mod=qllt&id=ERR01020012');
		}		
			echo "window.parent.loadDivFromUrl('groupcontent".$id_hoso."','/qllt/muontra/index/id_hoso/".$id_hoso."',1);";				
		
		exit;	
	}
			
	private function checkInput_DeleteData($id)
	{		
		$strerr = "";
		$strerr .= ValidateInputData::validateInput('require',$id,'Không có mã hồ sơ mượn trả\n');
		if($id == 0)
		{	
			$strerr .= 'Tham số không đúng định dạng';			
		}
		if(strlen($strerr)!= 0){			
			echo 'alert("'.$strerr.'");';
			exit;
		}
		else
		{
			return true;
		}
	}

	private function checkInput_UpdateData($id_muontra,$id_u_tra,$ngaytrathucte,$idhoso)
	{		
		$strerr  = "";
		$strerr .= ValidateInputData::validateInput('require',$id_muontra,'Không tìm thấy mã hồ sơ mượn trả\n');
		$strerr .= ValidateInputData::validateInput('require',$id_u_tra,'Không tìm thấy mã người trả\n');
		$strerr .= ValidateInputData::validateInput('date',$ngaytrathucte,'Ngày trả không đúng định dạng\n');

		$this->muontraModel = new Qllt_muontraModel();	
		
		$ngaymuon = $this->muontraModel->getNgayMuon($id_muontra);
		$ndate = implode("-",array_reverse(explode("/",$ngaytrathucte)));
		if($ngaymuon > $ndate)
		{
			$strerr .= 'Chọn sai ngày trả';
		}
		
		if(strlen($strerr)!= 0){						
			echo "<script>window.parent.loadDivFromUrl('groupcontent".$idhoso."','/qllt/muontra/index/id_hoso/".$idhoso."',1);</script>";
			echo '<script>alert("'.$strerr.'");</script>';
			exit;
		}
		else
		{
			return true;
		}
	}

	private function checkInput_AddnewData($ngaymuon,$ngayphaitra,$id_nguoimuon,$idhoso)
	{		
		$strerr  = "";
		$strerr .= ValidateInputData::validateInput('require',$ngaymuon,'Chọn ngày mượn \n');
		$strerr .= ValidateInputData::validateInput('require',$ngayphaitra,'Chọn ngày trả \n');
		$strerr .= ValidateInputData::validateInput('require',$id_nguoimuon,'Không tìm thấy mã người mượn\n');
		$strerr .= ValidateInputData::validateInput('date',$ngayphaitra,'Ngày trả không đúng định dạng\n');
		$strerr .= ValidateInputData::validateInput('date',$ngaymuon,'Ngày mượn không đúng định dạng\n');

		$this->muontraModel = new Qllt_muontraModel();		
		$date3		= $this->muontraModel->getNgayTra_Cuoicung($idhoso);

		$ngaymuon1	= implode("-",array_reverse(explode("/",$ngaymuon)));

		$date1		= implode("-",array_reverse(explode("/",$ngaymuon)));
		$date2		= implode("-",array_reverse(explode("/",$ngayphaitra)));
		
		if($date1 > $date2)
		{
			$strerr .= 'Chọn sai ngày trả\n'.$ngaymuon.'\n'.$ngayphaitra;
		}	

		if($ngaymuon1 < $ngaymuoncc)
		{
			$strerr .= 'Chọn sai ngày mượn\n';
		}	
		
		if(strlen($strerr)!= 0){						
			echo "<script>window.parent.loadDivFromUrl('groupcontent".$idhoso."','/qllt/muontra/index/id_hoso/".$idhoso."',1);</script>";
			echo '<script>alert("'.$strerr.'");</script>';
			exit;
		}
		else
		{
			return true;
		}
	}

	private function compareDate($date1,$date2)
	{
		
	}
}

