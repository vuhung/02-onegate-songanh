<?php

/**
 * index
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
require_once 'service/models/alarm.php';
require_once 'service/models/common.php';

require_once "Zend/Exception.php";
class Service_IndexController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		//echo "aaa";
		exit;
	}
	public function getallvanbandenAction() {
		$this->_helper->layout->disableLayout();
		srand((double)microtime()*1000000);
		$data = array();

		// add random height bars:
		for( $i=0; $i<10; $i++ )
		  $data[] = rand(2,9);

		require_once('OFC/OFC_Chart.php');

		$title = new OFC_Elements_Title( date("D M d Y") );

		$bar = new OFC_Charts_Bar_3d();
		$bar->set_values( $data );
		$bar->colour = '#D54C78';

		$x_axis = new OFC_Elements_Axis_X();
		$x_axis->set_3d( 5 );
		$x_axis->colour = '#909090';
		$x_axis->set_labels( array(1,2,3,4,5,6,7,8,9,10) );

		$chart = new OFC_Chart();
		$chart->set_title( $title );
		$chart->add_element( $bar );
		$chart->set_x_axis( $x_axis );
		echo $chart->toPrettyString();exit;
	}
	public function loginAction(){
		
	}
    public function chatAction(){
        try{
            $configChat = new Zend_Config_Ini('../application/config.ini', 'chat');
		}catch(Exception $ex){
            echo 0;
            exit;
        }
        header("Content-type: text/xml");
        echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
        echo "<info>";
        echo "<ip>".$configChat->services->ip."</ip>";
        echo "<serviceschung>".$configChat->services->chung."</serviceschung>";
        echo "<serviceschat>".$configChat->services->chat."</serviceschat>";
        echo "</info>";
        exit;		
	}
}


