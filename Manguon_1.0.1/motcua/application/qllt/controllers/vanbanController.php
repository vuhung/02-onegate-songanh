<?php

/**
 * isoController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
include_once 'qllt/models/vanban.php';
include_once 'hscv/models/filedinhkemModel.php';
include_once 'auth/models/ResourceUserModel.php';
include_once 'qllt/models/vanbanModel.php';

class Qllt_vanbanController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated isoController::indexAction() default action
	}
	public function listAction(){
		$this->_helper->layout->disablelayout();
		//Get parameters
		$config = Zend_Registry::get('config');
		$parameter = $this->getRequest()->getParams();
		$id=(int)$this->_request->getParam('idhoso');
		$this->view->idhoso=$id;
		//Define model
		$vanbanmdl=new vanbanModel();
		$this->view->data = $vanbanmdl->GetVanbanByIdhoso($id);
		
		//View detail
		$this->view->title = "Quản lý lưu trữ";
		$this->view->subtitle = "Văn bản";

		//Enable button
		QLVBDHButton::EnableDelete("/qllt/vanban/delete");
		QLVBDHButton::EnableAddNew("/qllt/vanban/input");
	}
	public function inputAction(){
		$this->_helper->layout->disablelayout();
		$params = $this->getRequest()->getParams();
		$id=(int)$params['idhoso'];

		$this->view->idhoso=$id;
		$this->view->title="Văn bản";
		//var_dump((int)$params["idvanban"]);exit;

		$idframe=$params['IdFrame'];
		$this->view->IdFrame= $idframe;
		//var_dump($idframe);exit;
		if((int)$params["idvanban"]>0){
			$iso = new iso();
			$this->view->subtitle = "Cập nhật";
			$this->view->data = $iso->find((int)$params["idvanban"])->current();
		}else{
			$this->view->subtitle = "Thêm mới";
		}
		//var_dump($params["idvanban"]);exit;
		$vanbanmdl = new vanbanModel();
		$vanban = $vanbanmdl->GetVanbanById((int)$params["idvanban"]);
		
		$this->view->idvanban = (int)$params["idvanban"];
		$this->view->tenvanban = $vanban['TENVANBAN'];
		$this->view->kyhieuvanban = $vanban['KYHIEUVANBAN'];
		$this->view->ngaythangvanban = $vanban['NGAYTHANGVANBAN'];
		$this->view->tacgiavanban = $vanban['TACGIAVANBAN'];
		$this->view->trichyeu = $vanban['TRICHYEU'];
		$this->view->soto = $vanban['SOTO'];
		$this->view->ghichu = $vanban['GHICHU'];
		$this->view->filecode = $vanban['FILECODE'];

		QLVBDHButton::EnableSave("#");
		QLVBDHButton::AddButton("Trở lại","","BackButton","BackButtonClick();");
		QLVBDHButton::EnableHelp("");
	}
	public function saveAction(){
		//$this->_helper->layout->disablelayout();
		$params = $this->getRequest()->getParams();
		$id = (int)$this->_request->getParam('idvanban');
		$idhoso=(int)$params['idhoso'];
		//var_dump($id);exit;
		if((int)$params["idvanban"]>0){
			$iso = new iso();
			$olddata = $iso->find((int)$params["idvanban"])->current();
			$re = vanbanModel::insertFileVanban($olddata->FILECODE);
			//var_dump($re);exit;
			if($re[0]!=""){
				$iso->update(
					array(
						"KYHIEUVANBAN"=>$params["kyhieuvanban"],
						"NGAYTHANGVANBAN"=>implode("-",array_reverse(explode("/",$params["ngaythangvanban"]))),
						"TACGIAVANBAN"=>$params["tacgiavanban"],
						"TRICHYEU"=>$params["trichyeu"],
						"SOTO"=>(int)$params["soto"],
						"GHICHU"=>$params["ghichu"],
						"TENVANBAN"=>$params["tenvanban"],
						"ID_HOSO"=>$params["idhoso"],
						"FILECODE"=>$re[0],
						"FILENAME"=>$re[2],
						"FILEMIME"=>$re[1]
					),
					"ID_VANBAN=".((int)$params["idvanban"])
				);
			}else{
				$iso->update(
					array(
						"KYHIEUVANBAN"=>$params["kyhieuvanban"],
						"NGAYTHANGVANBAN"=>implode("-",array_reverse(explode("/",$params["ngaythangvanban"]))),
						"TACGIAVANBAN"=>$params["tacgiavanban"],
						"TRICHYEU"=>$params["trichyeu"],
						"SOTO"=>$params["soto"],
						"GHICHU"=>$params["ghichu"],
						"TENVANBAN"=>$params["tenvanban"],
						"ID_HOSO"=>$params["idhoso"]
					),
					"ID_VANBAN=".((int)$params["idvanban"])
				);
			}
			//echo $re;
		}else{
			$iso = new iso();
			$re = vanbanModel::insertFileVanban("");
			$id = $iso->insert(
				array(
					"KYHIEUVANBAN"=>$params["kyhieuvanban"],
					"NGAYTHANGVANBAN"=>implode("-",array_reverse(explode("/",$params["ngaythangvanban"]))),
					"TACGIAVANBAN"=>$params["tacgiavanban"],
					"TRICHYEU"=>$params["trichyeu"],
					"SOTO"=>$params["soto"],
					"GHICHU"=>$params["ghichu"],
					"TENVANBAN"=>$params["tenvanban"],
					"ID_HOSO"=>$params["idhoso"],
					"FILECODE"=>$re[0],
					"FILENAME"=>$re[2],
					"FILEMIME"=>$re[1]
				)
			);
		}
		//$this->_redirect("/qllt/vanban/list");
		echo "<script>window.parent.loadDivFromUrl('groupcontent".$idhoso."','/qllt/vanban/list/idhoso/".$idhoso."',1);</script>";
		exit;
	}

	public function downloadAction(){
		$con = Zend_Registry::get('config');
		$id = $this->_request->getParam('id');
		$iso = new iso();
		$data = $iso->find($id)->current();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header( "Content-type:".$data->FILEMIME ); 
		header( 'Content-Disposition: attachment; filename="'.$data->FILENAME.'"' ); 
		header( "Content-Description: Excel output" );
		echo file_get_contents($con->file->root_dir.DIRECTORY_SEPARATOR."vanban".DIRECTORY_SEPARATOR.$data->FILECODE); 
		exit;
	}
	public function deleteAction(){
		$id=(int)$this->_request->getParam('idhoso');
		$this->view->idhoso=$id;
		//exit;
		$idvb=(int)$this->_request->getParam('idvanban');
		
		//xóa file trong thư mục
		$con = Zend_Registry::get('config');
		$iso = new iso();
		$data = $iso->find($idvb)->current();
		unlink($con->file->root_dir.DIRECTORY_SEPARATOR."vanban".DIRECTORY_SEPARATOR.$data->FILECODE);

		//xóa file trong db
		$this->view->parameter =  $this->getRequest()->getParams();
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->delete('qllt_vanban', "ID_VANBAN=".$idvb);
	

		//
		echo "window.parent.loadDivFromUrl('groupcontent".$id."','/qllt/vanban/list/idhoso/".$id."',1);";
		exit;
	}
}
