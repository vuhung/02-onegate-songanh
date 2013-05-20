<?php
require_once '../../lib/nusoap.php';
require_once '../../config.php';
require_once '../../db/connection.php';
require_once '../../models/Alarm.php';
require_once '../../db/common.php';
require_once '../../models/Backup.php';
$soap = new soap_server;
$soap->configureWSDL('BackupService', 'http://php.hoshmand.org/');
$soap->wsdl->schemaTargetNamespace = 'http://soapinterop.org/xsd/';

$soap->register('doBackup',
		array('config_path'=>'xsd:string',
		'destination_path'=>'xsd:string',
		'username'=>'xsd:string',
		'password'=>'xsd:string',
		'ismonth'=>'xsd:integer',
		'monthbegin'=>'xsd:integer',
		'monthend'=>'xsd:integer',
		'year'=>'xsd:integer',
		'isFile'=>'xsd:integer',
		'isDatabase'=>'xsd:integer',
		'isISO'=>'xsd:integer',
		'isMail'=>'xsd:integer',
		'isAll'=>'xsd:integer'
		),
		
		array('c' => 'xsd:string'
		),'http://soapinterop.org/');
$soap->register('doLogin',
		array('username'=>'xsd:string',
			  'password'=>'xsd:string'),
		array('check_result' => 'xsd:integer'),
			  'http://soapinterop.org/');


$soap->service(isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '');

function doBackup($config_path,$destination_path,$username,$password,$ismonth,$mothbegin,$monthend,$year,$isFile,$isDatabase,$isISO,$isMail,$isAll) {
	if(!Common::isAdministrators($username,$password)){
		return "-1";
	}
	Backup::doBackup($config_path,$destination_path,$username,$password,$ismonth,$mothbegin,$monthend,$year,$isFile,$isDatabase,$isISO,$isMail,$isAll);
	return "1";
}

function doLogin($username , $password)
{
	if(!Common::isAdministrators($username,$password)){
		return -1;
	}
	return 1;
}