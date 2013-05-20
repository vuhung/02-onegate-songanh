<?php
require_once '../../lib/nusoap.php';
require_once '../../config.php';
require_once '../../db/connection.php';
require_once '../../models/loaihoso.php';
require_once '../../db/common.php';
header('Content-Type:text/html; charset=UTF-8');
$soap = new soap_server;
$soap->configureWSDL('loaihosoService', 'http://php.hoshmand.org/');
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
		return loaihoso::GetData($sid);
	}catch(Exception $ex){
		return $ex;
	}
	
}

