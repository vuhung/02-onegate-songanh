<?php

/**
 * soanthaoController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
require_once 'hscv/models/filedinhkemModel.php';
require_once 'hscv/models/hosocongviecModel.php';
require_once 'vbdi/models/congviecsoanthaoModel.php';
require_once 'config/vbdi.php';
// Dùng bên listAction
require_once 'hscv/models/loaihosocongviecModel.php';
require_once 'hscv/models/butpheModel.php';
require_once 'vbden/models/vbdenModel.php';
require_once 'vbden/models/vbd_dongluanchuyenModel.php';
require_once 'hscv/models/ThuMucModel.php';
require_once 'config/hscv.php';
class Vbdi_soanthaoController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated soanthaoController::indexAction() default action
		$this->_redirect('/hscv/hscv/list');
	}
	/**
	 * The input action - show edit or new page
	 */
	public function inputAction(){
		// Lấy parameter
        $parameter = $this->getRequest()->getParams();        
        $id_hscv = $parameter["id_hscv"];
        $year = QLVBDHCommon::getYear();
        $this->view->year = $year;
        
        // New các model
        $this->congviecsoanthao = new congviecsoanthaoModel($year);          
        $userModel=new UsersModel();
        
 		// Lấy dữ liệu
        if($id_hscv>0)
        {
            $this->view->id_hscv = $id_hscv;            
            $this->view->nguoitao=$this->view->data->NGUOIYEUCAU;
            $this->view->nguoixuly=$this->view->data->NGUOIXULY;
            $this->view->data = congviecsoanthaoModel::getDetailByHSCV($year,$id_hscv);
        	$this->view->title = MSG11001001;
            $this->view->subtitle = MSG11001002;
        }
        else
        {
            $this->view->title = MSG11001001;
            $this->view->subtitle = MSG11001003;
            $this->view->nguoitao=Zend_Registry::get('auth')->getIdentity()->ID_U;
        }
        
        // Set biến cho view
        $this->view->year = $year;
        $this->view->userdata=$userModel->selectAllUsersJoinEmployees();
        
            
        QLVBDHButton::EnableSave("/vbdi/soanthao/Save");
        QLVBDHButton::EnableBack("/hscv//list");
                
        global $auth;
		$user = $auth->getIdentity();
		
		//parameter
		$this->parameter = $this->getRequest()->getParams();
		$this->view->wf_id_t = $this->parameter["wf_id_t"];
		$wf_id_t = $parameter["wf_id_t"];
		//Lay danh sach nhan vien va phong ban tham gia vao transaction nay
		$arr_wf_users = WFEngine::GetAccessUserFromTransition($wf_id_t);
		$arr_wf_deps = WFEngine::GetAccessDepFromTransition($wf_id_t);
		//var_dump($this->view->arr_wf_deps);
		/* Loc danh sach phong ban*/
		$this->view->arr_deps_se = array();
		$this->view->arr_deps_re = array();
		foreach ($arr_wf_deps as $dep){
			if($dep['TYPE'] == 0)
				array_push($this->view->arr_deps_se,$dep);
			else 
				array_push($this->view->arr_deps_re,$dep);
		}
		
		/* Loc danh sach nhan vien*/
		$this->view->id_dep_cur_user_se = -2;
		$this->view->id_dep_cur_user_re = -2;
		$this->view->arr_users_se = array();
		$this->view->arr_users_re = array();
		foreach ($arr_wf_users as $user_it){
			if($user_it['TYPE'] == 0){
				array_push($this->view->arr_users_se,$user_it);
				if($user_it['ID_U'] == $user->ID_U)
				$this->view->id_dep_cur_user_se = $user_it['ID_DEP'];
			}
			else{ 
				array_push($this->view->arr_users_re,$user_it);
				if($user_it['ID_U'] == $user->ID_U)
				$this->view->id_dep_cur_user_re = $user_it['ID_DEP'];
			}
			
			
		}
		
		
	}
    /**
	 * The input action - show edit or new page
	 */
	public function sendAction(){
	    // Lấy parameter
        $parameter = $this->getRequest()->getParams();        
        $id_hscv = $parameter["id_hscv"];
                 
        // New các model
        $this->congviecsoanthao = new congviecsoanthaoModel();        
        
        $this->view->data = $this->congviecsoanthao->findByHscv($id_hscv)->current();
        $this->view->title = MSG11001001;
        $this->view->subtitle = MSG11001012;

        QLVBDHButton::AddButton("Gửi","","SaveButton","PQClick();");
        QLVBDHButton::EnableBack("/hscv/hscv/list");
        
        global $auth;
		$user = $auth->getIdentity();
		
		//parameter
		$this->parameter = $this->getRequest()->getParams();
		$this->view->wf_id_t = $this->parameter["wf_id_t"];
	}
    /**
     * The save action - insert if item is new or update if if item is exist
     */
    public function saveAction(){
        
        //Lay cac tham so
    	$parameter = $this->getRequest()->getParams(); 
       	
    	$year=QLVBDHCommon::getYear();
           
      	$congviecsoanthao = new congviecsoanthaoModel($year);
        $filedinhkem =  new filedinhkemModel($year);
        
        //var_dump($parameter);
        $id_hscv = $parameter["id_hscv"];
        if($parameter["id_hscv"]>0)
        {
        	//truong hop cap nhat
        	//Chua su dung
        	try
        	{
        		//$cvstdt = congviecsoanthaoModel::getDetailByHSCV($year,$id_hscv);
        		
        		$congviecsoanthao->update(array("NAME"=>$parameter["NAME"]),"ID_HSCV=$id_hscv");
        		//$this->congviecsoanthao->update(array("NAME"=>$this->view->parameter["NAME"])); 
        		//$this->congviecsoanthao->update(array("NAME"=>$this->view->parameter["NAME"],"NOIDUNG"=>$this->view->parameter["NOIDUNG"],"GHICHU"=>$this->view->parameter["GHICHU"],"TRANGTHAI"=>$this->view->parameter["TRANGTHAI"],"NGUOIYEUCAU"=>$this->view->parameter["NGUOIYEUCAU"],"NGUOIXULY"=>$this->view->parameter["NGUOIXULY"]));
        		 //cap nhat lai ngay bat dau va ngay ket thuc trong ban ho so cong viec
        		 //$dbAdapter = Zend_Db_Table::getDefaultAdapter();
        		 //$ngay_bd = implode("-",array_reverse(explode("/",$this->view->parameter["NGAY_BD"]))); 
        		 //$ngay_kt = implode("-",array_reverse(explode("/",$this->view->parameter["NGAY_KT"]))); 
        		 //$dbAdapter->update("hscv_hosocongviec_$year",array("NGAY_BD"=>$ngay_bd,"NGAY_KT"=>$ngay_kt),"ID_HSCV=".$this->view->parameter["id_hscv"]);
		 	}
        	
        	
        	catch(Exception $ex)
        	{
        		//echo $ex->__toString();
        		//$this->_redirect("/hscv/hscv/list/code/old");
        		//exit;
        		exit;
        		
        	}   
        	//$this->_redirect("/hscv/hscv/list/code/old");         
        	//$this->_redirect("/hscv/hscv/list/code/old");         
        }
        else
        {
            //truong hop them moi
            //tao model ho so cong viec
            $hscv = new hosocongviecModel();
            
            
            $id_hscv = $hscv->CreateHSCV(
	            $parameter["NAME"],
	            1,
	            2,
	            "$year-1-1 00:00:00", //ngay bat dau
	            "$year-12-31 23:59:59", //ngay ket thuc
	            $parameter["NGUOIYEUCAU"],
	            $parameter["NGUOIXULY"],
	        	$parameter["NOIDUNG"], //extend
            	$parameter["HANXULY"]
	        );
	        
	        if($id_hscv>0){
				$congviecsoanthao->insert(
					array(
					"NAME"=>$parameter["NAME"],
					"ID_HSCV"=>$id_hscv,
					"NOIDUNG"=>$parameter["NOIDUNG"],
					"GHICHU"=>$parameter["GHICHU"],
					"TRANGTHAI"=>$parameter["TRANGTHAI"],
					"NGUOIYEUCAU"=>$parameter["NGUOIYEUCAU"],
					"NGUOIXULY"=>$parameter["NGUOIXULY"],
					"NGAYTAO"=>date("Y-m-d"))
				);
	            for($i=0;$i<count($parameter["idFile"]);$i++){   
	                $filedinhkem->update(
	                	array(
	                		"ID_OBJECT"=>$id_hscv,
	                		"TYPE"=>1),
	                		"MASO='".$parameter["idFile"][$i]."'"
	                	);
	            }
			}else{
				
			}
	    }
	    //exit;
        $this->_redirect("/hscv/hscv/list/code/old");        
    }
    /**
     * The delete action - delete item
     */
    public function deleteAction(){        
        $this->view->parameter =  $this->getRequest()->getParams();
        $parameter = $this->getRequest()->getParams(); 
        // Set Year
        $year = $parameter["year"];
        Zend_Registry::set('year',$year);
        $year = (!isset($year) || $year == '' || $year == 0)?Zend_Date::now()->get(Zend_Date::YEAR):$year;
        
        $this->congviecsoanthao = new congviecsoanthaoModel();
        try{
            $this->congviecsoanthao->delete("ID_VBDI_CVST IN (".implode(",",$this->view->parameter["DEL"]).")");
        }catch(Exception $ex){
        }
        $this->_redirect("/hscv/hscv/list");        
    }
}
