<?php

/**
 * IndexController - The default controller class
 * 
 * @author
 * @version 
 */


class IndexController extends Zend_Controller_Action 
{
	/**
	 * The default action - show the home page
	 */
    public function indexAction() 
    {
      $this->view->title = "Phần mềm tra cứu thủ tục hành chính qua màn hình cảm ứng";
	  $this->view->subtitle = "Nhập liệu";
	}
}
