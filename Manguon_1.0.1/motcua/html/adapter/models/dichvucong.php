<?php
class dichvucong {
	public static function createXML(&$doc,&$parent,$data,$itemname){
			$fieldvalue = $data[$itemname];
			$child = $doc->createElement($itemname);
			$child = $parent->appendChild($child);
			$child->setAttribute("MAHOSO",$data["MAHOSO"]);
			$child->setAttribute("MADICHVU",$data["MADICHVU"]);
			$value = $doc->createTextNode(base64_encode($fieldvalue));
			$value = $child->appendChild($value);
	}

	/**
	* Kiểm tra user login
	*/
	public static function checkAuthentication($username, $password){
		$sql = sprintf("
			select count(*) as DEM from adapter_users where USERNAME = '%s' and PASSWORD = '%s'
		",mysql_real_escape_string($username),mysql_real_escape_string(md5($password)));
		$result = query($sql);
		$row = mysql_fetch_assoc($result);
		$dem = $row["DEM"];
		if($dem){
			return true;
		}
		else{
			self::WriteLog("Login :Fail", $username);
			self::WriteLog("Login :Fail", $password);
			return false;
		}
	}

	/**
	* Lấy role của user
	*/
	public static function getRole($username, $password){
		$sql = sprintf("
			select ROLE from adapter_users where USERNAME = '%s' and PASSWORD = '%s'
		",mysql_real_escape_string($username),mysql_real_escape_string($password));
		$result = query($sql);
		$row = mysql_fetch_assoc($result);
		return $row["ROLE"];
	}
	
	public static function checkAuthenticationWithRole($username, $password, $role){
		$sql = sprintf("
			select count(*) as DEM from adapter_users where USERNAME = '%s' and PASSWORD = '%s' and ROLE = '%s'
		",mysql_real_escape_string($username),mysql_real_escape_string(md5($password)),mysql_real_escape_string($role));
		$result = query($sql);
		$row = mysql_fetch_assoc($result);
		$dem = $row["DEM"];
		if($dem){
			return true;
		}else{
			self::WriteLog("Login with role :Fail", $username);
			return false;
		}
	}

	/**
	* Chuyển đổi dữ liệu ra dạng array
	*/
	public static function XmlToArray($xml){
			$array = simplexml_load_string ( $xml );
			$newArray = array ( ) ;
			$array = ( array ) $array ;
			foreach ( $array as $key => $value )
			{
				$value = ( array ) $value ;			
				$newArray [ $key] = $value[0];
			}
			$newArray = array_map("trim", $newArray);
			return $newArray; 
	}
		
	public static function WriteLog($functionName,$content)
	{
		$today = date("[ d/m/Y ] [ h:i:s ]");
		$myFile = LOG_FILE;
		$fh = fopen($myFile, 'a') or die("can't open file");
		$stringData = $today." \t".$functionName."\t".$content."\n\n";
		fwrite($fh, $stringData);
		fclose($fh);
	}
}
