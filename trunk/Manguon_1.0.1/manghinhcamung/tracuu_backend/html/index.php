<?php
/**
 * My new Zend Framework project
 * 
 * @author  
 * @version 
 */
$rootPath = dirname(dirname(__FILE__));
set_include_path(get_include_path() . PATH_SEPARATOR .  
                 $rootPath . '/application' . PATH_SEPARATOR .  
                 $rootPath . '/library' . PATH_SEPARATOR .  
                 $rootPath . '/html');
require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();
$config = new Zend_Config_Ini('../application/config.ini', 'general');
$registry = Zend_Registry::getInstance();
$registry->set('config', $config);
$db = Zend_Db::factory($config->db);
Zend_Db_Table::setDefaultAdapter($db);
$controller = Zend_Controller_Front::getInstance();
$controller->setControllerDirectory('../application/default/controllers');

$controller->addControllerDirectory('../application/hscv/controllers', 'hscv');
$controller->addControllerDirectory('../application/qtht/controllers', 'qtht');
$controller->addControllerDirectory('../application/auth/controllers', 'auth');
$controller->addControllerDirectory('../application/danhmuc/controllers', 'danhmuc');


require_once 'Zend/Layout.php'; 
$layout = Zend_Layout::startMvc($config->appearance);
$layout->getView()->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
require_once 'Common/button.php';
require_once 'Common/common.php';
require_once 'wf/wfengine.php';
require_once 'auth/models/QLVBDH_ACL.php'; 
require_once 'default/controllers/ErrorController.php';
require_once 'fckeditor/fckeditor_php5.php' ;
require_once 'qtht/models/qtht_year.php';
$auth = Zend_Auth::getInstance();
Zend_Registry::set('auth', $auth);
$acl = new QLVBDH_ACL($auth);
Zend_Registry::set('acl', $acl);
$url = 'http://hoian.qlvbdh.vn/';
Zend_Registry::set('url', $url);
require_once 'auth/controllers/Auth_Plugin_Controller.php';
$controller->registerPlugin(new Auth_Plugin_Controller($auth,$acl));
$controller->throwExceptions(true); // should be turned on in development time 
try{
$controller->dispatch();
}catch (Exception  $ex){
	echo $ex->__toString();
}