<?php
require_once '../../lib/nusoap.php';
require_once '../../config.php';
require_once '../../db/connection.php';
require_once '../../models/linhvuc.php';
require_once '../../db/common.php';
$soap = new soap_server;
$soap->configureWSDL('linhvucService', 'http://php.hoshmand.org/');
$soap->wsdl->schemaTargetNamespace = 'http://soapinterop.org/xsd/';

$soap->register('Select',
	array(
	),
	array(
		'c' => 'xsd:string'
	),
	'http://soapinterop.org/'
);

$soap->register('getGoithieu',
	array(
	),
	array(
		'c' => 'xsd:string'
	),
	'http://soapinterop.org/'
);

$soap->register('getCocautochuc',
	array(
	),
	array(
		'c' => 'xsd:string'
	),
	'http://soapinterop.org/'
);

$soap->service(isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '');

function Select(){
	try{
		
		return linhvuc::GetData();
	}catch(Exception $ex){
		return $ex;
	}
	
}

function getGoithieu(){
	try{
		
		return linhvuc::getGoithieu();
	}catch(Exception $ex){
		return $ex;
	}
	
}


function getCocautochuc(){
	try{
		
		return linhvuc::getCocautochuc();
	}catch(Exception $ex){
		return $ex;
	}
}