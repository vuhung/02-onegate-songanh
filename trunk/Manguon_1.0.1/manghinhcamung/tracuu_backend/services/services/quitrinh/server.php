<?php
require_once '../../lib/nusoap.php';
require_once '../../config.php';
require_once '../../db/connection.php';
require_once '../../models/quitrinh.php';
require_once '../../db/common.php';
$soap = new soap_server;
$soap->configureWSDL('quitrinhService', 'http://php.hoshmand.org/');
$soap->wsdl->schemaTargetNamespace = 'http://soapinterop.org/xsd/';

$soap->register('Select',
	array(
			'sid'=>'xsd:string'
	),
	array(
		'c' => 'xsd:string'
	),
	'http://soapinterop.org/'
);

$soap->service(isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '');

function Select($sid){
	try{
		return quitrinh::GetData($sid);
	}catch(Exception $ex){
		return $ex;
	}
	
}

