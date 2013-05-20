<?php	
require_once ('Zend/Controller/Action.php');
require_once 'report/models/XuLyHoSoMotCuaModel.php';
require_once('qtht/models/UsersModel.php');
require_once('report/models/xulycongviecModel.php');
require_once 'qtht/models/sovanbanModel.php';
class Report_xulycongviecController extends Zend_Controller_Action{
	function init(){
		
	}
	function indexAction(){
		$this->view->title = "Báo cáo";
		$this->view->subtitle = "Tình hình xử lý hồ sơ";
		$params = $this->_request->getParams();
		$this->view->fromdate = $params["fromdate"];
		$this->view->todate = $params["todate"];
		$this->view->user = Zend_Registry::get('auth')->getIdentity();
	}
	
	function reportviewAction(){
		$this->_helper->layout->disableLayout();
		$params = $this->_request->getParams();
        $id_dep = $params['id_dep'];
		$id_u = $params['id_u'];
        $type = $params["type"];
        $this->view->type = $type;
        $year = QLVBDHCommon::getYear();
		//$this->view->cvuser = xulycongviecModel::getxulycongviecByUser($id_u);
        $bd_from = $params["fromdate"];
        $bd_to = $params["todate"];
		$this->view->hscvlist =  xulycongviecModel::getListHscvByuser($id_u,$type,$year,$bd_from,$bd_to);
        
		$this->view->id_u = $id_u;
        $this->view->year = $year;
        $this->view->type = $type;
        //$this->view->listdetail =  xulycongviecModel::getDetails(590935,591532,1);
        //var_dump($this->view->hscvlist);
	    //var_dump($this->view->hscvlist);
	    //echo 1;
	   	// exit;
	    
	}
	
	
	function listreportAction(){
		//$this->_helper->layout->disableLayout();
		$this->view->title = "Tình hình xử lý hồ sơ";
		$params = $this->_request->getparams();
		$this->view->pbs = $params["sel_pb"];
		$this->view->lcv = $params["sel_lcv"];
		$this->view->svb = $params["sel_svb"];
		if(!is_array($this->view->lcv)){
			$this->view->lcv = array('0');
		}
		$fromdate = $params['fromdate'];
		if($fromdate == "")
			$fromdate = "1/1";
		$todate = $params['todate'];
		if($todate == "")
			$todate = "31/12";		
		$this->view->fromdate = $fromdate;
		$this->view->todate = $todate;
		
		$this->view->subtitle = "Từ ngày ".$fromdate ." đến ngày ".$todate ;
		
		QLVBDHButton::AddButton("Trở lại","","BackButton","BackButtonClick();");
	}
	
	function ajaxgetuserbydepartAction(){
        $this->_helper->layout->disableLayout();
		$params = $this->_request->getParams();
        $id_dep = $params['id_dep'];
        $data_u = UsersModel::getUsersByDepartment($id_dep);
        $type = $params["type"];
        $fromdate = $params["fromdate"];
        $todate = $params["todate"];
        $year = QLVBDHCommon::getYear();
        
        $div_html = "";
        foreach($data_u as $item ){
        	$url = "'/report/xulycongviec/reportview?fromdate=".$fromdate."&todate=".$todate."&type=".$type."&id_u=".$item['ID_U']."'";
        	$divid = "'cvuser_type".$type."_".$item["ID_U"]."'";
        	$daudongdonrong = "''";
        	
        	//$arr_re = xulycongviecModel::thongkeNhanVien($year,$item["ID_U"],$type);
			
        	$arr_re =  xulycongviecModel::getListHscvByuser($item["ID_U"],$type,$year,$fromdate,$todate);
        	
        	$id_img = "'img_$type"."_".$item['ID_U']."'";
        	$id_img_load = "'img_load_$type"."_".$item['ID_U']."'";
        	$img_plus = "'/images/iplus.gif'";
        	$img_minus = "'/images/iminus.gif'" ;
        	$div_html .= '
        	<a  href="#" 
        	
        	onclick="
        		
        		var odiv = document.getElementById('.$divid.');
        		var oimg_load = document.getElementById('.$id_img_load.');
        		var oimg = document.getElementById('.$id_img.');
        		if(odiv.innerHTML =='.$daudongdonrong.'){
        		oimg_load.style.display ='.$daudongdonrong.';	
        		var ajx = new AjaxEngine();
				ajx.loadDivFromUrlAndHideImage('.$divid.','.$url.','.$id_img_load.');
				oimg.src ='.$img_minus.';
        		}else{
        			odiv.innerHTML ='.$daudongdonrong.';
        			oimg.src ='.$img_plus.';
        		}
        		
			//oimg_load.style.display = '."'none'".';
        	return false;
			
			" ><img align="absmiddle" id="img_'.$type.'_'.$item["ID_U"].'" src="/images/iplus.gif" border="0" hspace="5" >'.
        	$item["FULLNAME"].'</a> 
			&nbsp;<i>(<b> Chờ xử lý </b><font color="Blue">'.count($arr_re["DANGXULY"]).'</font>'.($arr_re['DANGXULY_TRE']>0? '(<font color="Red"> trễ : '.$arr_re['DANGXULY_TRE'].'</font>)' : '').' 
					<b>Đã xử lý </b><font color="Blue">'.count($arr_re["DAXULY"]).'</font>'.($arr_re['DAXULY_TRE']>0? '( <font color="Red">trễ : '.$arr_re['DAXULY_TRE'].'</font> )' : '').' 
					<b>Đã kết thúc </b><font color="Blue">'.count($arr_re["KETTHUC"]).'</font>' .($arr_re['KETTHUC_TRE']>0? '( <font color="Red">trễ : '.$arr_re['KETTHUC_TRE'].'</font> )' : '').'
			)</i>
			<img id='.$id_img_load.' src="/images/loading.gif" style="display:none;">
			<div id="cvuser_type'.$type.'_'.$item["ID_U"].'" style="margin-top:20px;margin-left:0px"></div>
			';
        	
        	
        }
        echo $div_html;
        
        exit;
	}
}
