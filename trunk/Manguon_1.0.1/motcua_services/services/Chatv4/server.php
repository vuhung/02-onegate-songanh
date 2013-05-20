<?php
require_once '../../lib/nusoap.php';
require_once '../../config.php';
require_once '../../db/connection.php';
require_once '../../models/Chatv4.php';
require_once '../../db/common.php';
$soap = new soap_server ( );
$soap->configureWSDL ( 'ChatService', 'http://php.hoshmand.org/' );
$soap->wsdl->schemaTargetNamespace = 'http://soapinterop.org/xsd/';

$soap->register ( 'Login', array ('username' => 'xsd:string', 'password' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'GetRoom', array ('session_id' => 'xsd:string', 'prameters' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'SendMessage', array ('session_id' => 'xsd:string', 'prameters' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'ReceiveMessage', array ('session_id' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'AddUserToRoom', array ('session_id' => 'xsd:string', 'prameters' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'RemoveUser', array ('session_id' => 'xsd:string', 'prameters' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'ReceiveUserInformation', array ('session_id' => 'xsd:string','username' => 'xsd:string'   ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'ReceiveAllUser', array ('session_id' => 'xsd:string'), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'SendInformation', array ('session_id' => 'xsd:string', 'prameters' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'ReceiveUserOnline', array ('session_id' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'ReceiveFile', array ('session_id' => 'xsd:string', 'prameters' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'EndSendFile', array ('session_id' => 'xsd:string', 'prameters' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'ReceiveRequestFile', array ('session_id' => 'xsd:string'), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'SendFile', array ('session_id' => 'xsd:string', 'prameters' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'BeginSendFile', array ('session_id' => 'xsd:string', 'prameters' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'AcceptSendFile', array ('session_id' => 'xsd:string', 'prameters' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'Test', array ('prameters' => 'xsd:string' ), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'GetDepartments', array ( 'prameters' => 'xsd:string'), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'Auth', array ( 'prameters' => 'xsd:string'), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'Logout', array ( 'session_id' => 'xsd:string'), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'GetGroupPublic', array ( 'session_id' => 'xsd:string'), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'ChangeNameRoom', array ( 'session_id' => 'xsd:string','prameters' => 'xsd:string'), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'BaoTri', array ( 'session_id' => 'xsd:string'), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'GetHSCVNew', array ( 'session_id' => 'xsd:string'), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->register ( 'UpdateIDLE', array ( 'session_id' => 'xsd:string'), array ('sid' => 'xsd:string' ), 'http://soapinterop.org/' );

$soap->service ( isset ( $HTTP_RAW_POST_DATA ) ? $HTTP_RAW_POST_DATA : '' );


function Login($username, $password) {
	try {
		return Chat::Login ( $username, $password );
	} catch ( Exception $ex ) {
		return "-1";
	}
}
// $parameters là chuỗi được quy định  : ID_ROOM hoặc tên bạn chát
// $sid : Session_id
function GetRoom($sid,$parameters) {
	try {
                //Chứng thực Auth
                $sessionInfo= Chat::Auth($sid);
                if($sessionInfo==0) return 0;
                $user=$sessionInfo[0];
                $parameters=$parameters.'~'.$user;
		return base64_encode(Chat::GetRoom($parameters)) ;
	} catch ( Exception $ex ) {
		return "-1";
	}
}
//  $parameters  là chuỗi encode được quy định   : base64(ID_Room)~base64(message)
// $sid : Session_id
function SendMessage($sid,$parameters) {
	try {   
                  //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid);
                if($sessionInfo==0) return 0;
                $user=$sessionInfo[0];
                $parameters=$parameters.'~'.base64_encode($user);
		return Chat::SendMessage($parameters);
	} catch ( Exception $ex ) {
		return "-1";
	}
}
// $parameters   : Không có
// $sid : Session_id
function ReceiveMessage($sid) {
	try {   
              //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid);
                if($sessionInfo==0) return 0;
                $user=$sessionInfo[0];
                $parameters=$user;
		return base64_encode(Chat::ReceiveMessage($parameters));
	} catch ( Exception $ex ) {
		return "-1";
	}
}
// $parameters là chuỗi được quy định  : idroom~banchat1~banchat2~banchat3...
// $sid : Session_id
function AddUserToRoom($sid,$parameters) {
	try {   
               //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid);
                if($sessionInfo==0) return 0;
                 $user=$sessionInfo[0];
		return Chat::AddUserToRoom($user.'~'.$parameters);
	} catch ( Exception $ex ) {
		return "-1";
	}
}


//$parameters là chuỗi được quy định  : idroom~tenbanchat
// $sid : Session_id
function RemoveUser($sid,$parameters) {
	try {   
              //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid); 
                if($sessionInfo==0) return 0;
                $user=$sessionInfo[0];
                $parameters=$parameters.'~'.$user; 
		return Chat::RemoveUser($parameters);
	} catch ( Exception $ex ) {
		return "-1";
	}
}

// $parameters   : Không có
// $sid : Session_id
function ReceiveUserInformation($sid,$username) {
	try {
               //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid);
                if($sessionInfo==0) return 0;
                $user=$sessionInfo[0];
                $parameters=$user;
		return base64_encode(Chat::ReceiveUserInformation($username));
	} catch ( Exception $ex ) {
		return "-1";
	}
}
// $sid : Session_id
// $parameters   : Không có
function ReceiveAllUser($sid) {
	try {   
                //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid);
                if($sessionInfo==0) return 0;
		return base64_encode(Chat::ReceiveAllUser()) ;
	} catch ( Exception $ex ) {
		return "-1";
	}
}
// $sid : Session_id
//$parameters  là chuỗi encode được quy định   :  visible~fullAvatar~avatar~status~typing~roomid
function SendInformation($sid,$parameters) {
	try {   
                //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid);
                if($sessionInfo==0) return 0;
                $user=$sessionInfo[0];
                $parameters=$parameters.'~'.$user;
		return (Chat::SendInformation($parameters));
	} catch ( Exception $ex ) {
		return "-1";
	}
}
// $sid : Session_id
// $parameters   : Không có
function ReceiveUserOnline($sid) {
	try {   
                //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid);
                if($sessionInfo==0) return 0;
		return base64_encode(Chat::ReceiveUserOnline()) ;
	} catch ( Exception $ex ) {
		return "-1";
	}
}
// $sid : Session_id
//$parameters là chuỗi được quy định  :  title ~ số part 
function ReceiveFile($sid,$parameters) {
	try {   
                //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid);
                if($sessionInfo==0) return 0;
		return base64_encode(Chat::ReceiveFile($parameters)) ;
	} catch ( Exception $ex ) {
		return "-1";
	}
}
// $sid : Session_id
//$parameters là chuỗi được quy định  :  id_file  
function EndSendFile($sid,$parameters) {
	try {   
               //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid);
                $user=$sessionInfo[0];
                if($sessionInfo==0) return 0;
		return Chat::EndSendFile($parameters.'~'.$user);
	} catch ( Exception $ex ) {
		return "-1";
	}
}
// $sid : Session_id
// $parameters   : Không có
function ReceiveRequestFile($sid) {
	try {   
                //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid);
                if($sessionInfo==0) return 0;
                $user=$sessionInfo[0];
                $parameters=$user;
		return base64_encode(Chat::ReceiveRequestFile($parameters)) ;
	} catch ( Exception $ex ) {
		return "-1";
	}
}
// $sid : Session_id
//$parameters  là chuỗi encode được quy định   :  maSoFile ~ content ~ filename
function SendFile($sid,$parameters) {
	try {   
               //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid);
                if($sessionInfo==0) return 0;
		return Chat::SendFile($parameters);
	} catch ( Exception $ex ) {
		return "-1";
	}
}
// $sid : Session_id
//$parameters  là chuỗi encode được quy định   :  id_room ~ maSoFile ~encode(nameFile) 
function BeginSendFile($sid,$parameters) {
	try {   
                //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid);
                if($sessionInfo==0) return 0;
                $user=$sessionInfo[0];
                $parameters=$parameters.'~'.$user;
		return Chat::BeginSendFile($parameters);
	} catch ( Exception $ex ) {
		return "-1";
	}
}
// $sid : Session_id
//$parameters là chuỗi được quy định  : encode(maSoFile)~ encode(tenfile)  
function AcceptSendFile($sid,$parameters) {
	try {   
               //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid);
                if($sessionInfo==0) return 0;
                $user=$sessionInfo[0];
                $parameters=$parameters.'~'.$user;
		return Chat::AcceptSendFile($parameters);
	} catch ( Exception $ex ) {
		return "-1";
	}
}
//mã hóa 1 chuỗi thành 1 chuỗi md5
function Test($parameters) {
       return md5($parameters);
	
}
//Get danh sách phòng ban
function GetDepartments($sid) {
        //Chứng thực Auth
        $sessionInfo=Chat::Auth($sid);
        if($sessionInfo==0) return 0;
       return base64_encode(Chat::GetDepartments()) ;
	
}

function Auth($sid) {
        //Chứng thực Auth
      return var_dump($sessionInfo=Chat::Auth($sid)) ;
	
}

function Logout($sid) {
	try {   
               //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid); 
                if($sessionInfo==0) return 0;
		return Chat::Logout($sid);
	} catch ( Exception $ex ) {
		return "-1";
	}
}
// lấy group user login
function GetGroupPublic($sid) {
	try {   
               //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid); 
                if($sessionInfo==0) return 0;
		return base64_encode(Chat::GetGroupPublic($sessionInfo[0])) ;
	} catch ( Exception $ex ) {
		return "-1";
	}
}

 //thay đổi tên room
 //$parameter ID_ROOM ~ base64_encode(tênroom)
function ChangeNameRoom($sid,$parameters){
    try {   
               //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid); 
                if($sessionInfo==0) return 0;
		return (Chat::ChangeNameRoom($parameters)) ;
	} catch ( Exception $ex ) {
		return "-1";
	}
}

function BaoTri($sid){
	try {   
               //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid); 
                if(strtolower($sessionInfo[0])=="administrator"){
					return (Chat::BaoTri()) ;
				}else{
					return 0;
				}
	} catch ( Exception $ex ) {
		return "-1";
	}
}

function GetHSCVNew($sid){
    try {   
               //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid); 
                if($sessionInfo==0) return 0;
		return base64_encode(Chat::GetHSCVNew($sessionInfo[0])) ;
	} catch ( Exception $ex ) {
		return "-1";
	}
}


function UpdateIDLE($sid){
    try {   
               //Chứng thực Auth
                $sessionInfo=Chat::Auth($sid); 
                if($sessionInfo==0) return 0;
		return Chat::UpdateIDLE($sessionInfo[0]) ;
	} catch ( Exception $ex ) {
		return "-1";
	}
}
?>
