<?php

/**
 * TransitionController
 * 
 * @author nguyennd
 * @version 1.0
 */

require_once 'Zend/Controller/Action.php';
require_once 'wf/models/TransitionModel.php';
require_once 'config/wf.php';
require_once 'Common/ValidateInputData.php';

class Wf_TransitionController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated TransitionController::indexAction() default action
	}
	/**
	 * Lưu dữ liệu.
	 * Nếu đã có thì update
	 * Nếu chưa có thì insert
	 */
	public function saveAction(){
		try{
			$this->parameter =  $this->getRequest()->getParams();
			$this->transition = new TransitionModel();
			
			//var_dump($this->parameter);exit;
			for($i=0;$i<count($this->parameter["ID_T"]);$i++){
				$id_t = $this->parameter["ID_T"][$i];
				$end_at_arr = $this->parameter["END_AT".$id_t];
				$end_at_multi = implode(",",$end_at_arr);
				if($this->parameter["HANXULY"][$i]=="")$this->parameter["HANXULY"][$i] = 2;
				if($this->parameter["ID_T"][$i]==0 && $this->parameter["ID_A_BEGIN"][$i]>-1 && $this->parameter["ID_A_END"][$i]>0 && $this->parameter["ID_TP"][$i]>0){
					if($this->parameter["ISFIRST"]==-1){
						$idnew =$this->transition->insert(array("ORDERS"=>$i,"ID_A_BEGIN"=>$this->parameter["ID_A_BEGIN"][$i],"ID_A_END"=>$this->parameter["ID_A_END"][$i],"ID_P"=>$this->parameter["idp"],"ID_TP"=>$this->parameter["ID_TP"][$i],"ISFIRST"=>"1","NAME"=>$this->parameter["NAME"][$i],"HANXULY"=>$this->parameter["HANXULY"][$i],"END_AT"=>$this->parameter["END_AT"][$i]));
					}else{
						$idnew = $this->transition->insert(array("ORDERS"=>$i,"ID_A_BEGIN"=>$this->parameter["ID_A_BEGIN"][$i],"ID_A_END"=>$this->parameter["ID_A_END"][$i],"ID_P"=>$this->parameter["idp"],"ID_TP"=>$this->parameter["ID_TP"][$i],"ISFIRST"=>"0","NAME"=>$this->parameter["NAME"][$i],"HANXULY"=>$this->parameter["HANXULY"][$i],"END_AT"=>$this->parameter["END_AT"][$i]));
					}
				}else{
					if($this->parameter["ISFIRST"]==$this->parameter["ID_T"][$i]){
						$this->transition->update(array("ORDERS"=>$i,"ID_A_BEGIN"=>$this->parameter["ID_A_BEGIN"][$i],"ID_A_END"=>$this->parameter["ID_A_END"][$i],"ID_TP"=>$this->parameter["ID_TP"][$i],"ISFIRST"=>"1","NAME"=>$this->parameter["NAME"][$i],"HANXULY"=>$this->parameter["HANXULY"][$i],"END_AT"=>$this->parameter["END_AT"][$i],"END_AT_MULTI"=>$end_at_multi),"ID_T=".$this->parameter["ID_T"][$i]);
					}else{
						$this->transition->update(array("ORDERS"=>$i,"ID_A_BEGIN"=>$this->parameter["ID_A_BEGIN"][$i],"ID_A_END"=>$this->parameter["ID_A_END"][$i],"ID_TP"=>$this->parameter["ID_TP"][$i],"ISFIRST"=>"0","NAME"=>$this->parameter["NAME"][$i],"HANXULY"=>$this->parameter["HANXULY"][$i],"END_AT"=>$this->parameter["END_AT"][$i],"END_AT_MULTI"=>$end_at_multi),"ID_T=".$this->parameter["ID_T"][$i]);
					}
				}
			}
			
			$this->transition->update(array("ISLAST"=>0),"ID_P=".(int)$this->parameter["idp"]);
			
			$islast = $this->parameter["ISLAST"];
			foreach($islast as $islastitem){
				if($islastitem==-1){
					$this->transition->update(array("ISLAST"=>1),"ID_T=".$idnew);
				}else{
					$this->transition->update(array("ISLAST"=>1),"ID_T=".(int)$islastitem);
				}
			}
			
			$multi = $this->parameter['MULTI'];
			$this->transition->update(array("MULTI"=>0),"ID_P=".(int)$this->parameter["idp"]);
			foreach($multi as $multiitem){
				if($multiitem>0){
					$this->transition->update(array("MULTI"=>1),"ID_T=".(int)$multiitem);
				}else{
					$this->transition->update(array("MULTI"=>1),"ID_T=".$idnew);
				}
			}
		}catch(Exception $ex){
			echo $ex;exit;
		}
		$this->_redirect("/wf/Workflow/index/idp/".$this->parameter["idp"]);
	}
	/**
	 * Xoá dữ liệu
	 */
	public function deleteAction(){
		$this->transition = new TransitionModel();
		$this->parameter =  $this->getRequest()->getParams();
		try{
			$this->transition->delete("ID_T IN (".implode(",",$this->parameter["DELT"]).")");
		}catch(Exception $ex){
			echo $ex->__toString();
		}
		$this->_redirect("/wf/Workflow/index/idp/".$this->parameter["idp"]);
	}
}
