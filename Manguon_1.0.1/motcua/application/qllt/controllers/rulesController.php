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

require_once 'qllt/models/rulesModel.php';
require_once 'hscv/models/gen_tempModel.php';

class Qllt_rulesController extends Zend_Controller_Action 
{
    protected $_name = 'lib_search';
	public function init()
	{
		$this->view->title = "Nội quy nơi lưu trữ";
		$this->view->subtitle = "danh sách";
	}
	function indexAction()
    {    	
        $type = 8888;        
        $id = 0;
        $year = QLVBDHCommon::getYear();
        $Qllt_rulesModel = new Qllt_rulesModel();
        $id = $Qllt_rulesModel->getId($type,$year);
        if((int)$id == 0 ){
            $tempTbl = new gen_tempModel();
            $id = $tempTbl->getIdTemp();
        }
        $this->view->year = $year;
        $this->view->idObject = $id;
        $this->view->type = $type;
        
	}
}

