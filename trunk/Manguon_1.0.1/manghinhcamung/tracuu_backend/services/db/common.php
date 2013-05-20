<?php
class Common
{
    /**
     * @return string
     */
    public static function Login($username,$password)
    {
		$sid = "0";
		$sql = sprintf("SELECT * FROM QTHT_USERS where USERNAME = '%s' and PASSWORD = '%s'",
			mysql_real_escape_string($username),$password);
		$user = query($sql);
		if(mysql_num_rows($user)==1){
			$user = mysql_fetch_assoc($user);
			$sid = md5(rand(1,1000000000).$username.md5(time()));
			$sql = sprintf("SELECT * FROM QTHT_SESSION WHERE ID_U = '%s'",
				$user['ID_U']);
			$session = query($sql);
			if(mysql_num_rows($session)==1){
				$session = mysql_fetch_assoc($session);
				$sql = sprintf("UPDATE QTHT_SESSION SET SESSION='%s',LIMIT_DATE='%s',IP='%s' WHERE ID_U = '%s'",
					$sid,date("Y-m-d H:i:s",time()+86400),$_SERVER['REMOTE_ADDR'],$user['ID_U']);
				query($sql);
			}else{
				$sql = sprintf("INSERT INTO QTHT_SESSION(SESSION,LIMIT_DATE,IP,ID_U) VALUES('%s','%s','%s','%s')",
					$sid,date("Y-m-d H:i:s",time()+86400),$_SERVER['REMOTE_ADDR'],$user['ID_U']);
				query($sql);
			}
		}
		return $sid;
    }
	public static function Login2($username,$password)
    {
		$sid = "0";
		$sql = sprintf("SELECT * FROM WEB_USERS where USERNAME = '%s' and PASSWORD = '%s'",
			mysql_real_escape_string($username),$password);
		$user = query($sql);
		if(mysql_num_rows($user)==1){
			return true;
		}
		return false;
    }
    public static function GetAuth($sid){
        $sql = sprintf("SELECT * FROM QTHT_USERS u inner join QTHT_SESSION s on u.ID_U = s.ID_U WHERE s.SESSION='%s' AND IP='%s'",mysql_real_escape_string($sid),$_SERVER['REMOTE_ADDR']);
        $user = query($sql);
        if(mysql_num_rows($user)==1){
        	$user = mysql_fetch_assoc($user);
        	return $user['ID_U'];
        }
        return 0;
    }
	public static function GetID_U($sid){
        $sql = sprintf("SELECT * FROM QTHT_USERS WHERE USERNAME='%s'",mysql_real_escape_string($sid));
        $user = query($sql);
        if(mysql_num_rows($user)==1){
        	$user = mysql_fetch_assoc($user);
        	return $user['ID_U'];
        }
        return 0;
    }
	public static function getYear(){
   		$d=getdate();
   		$year = $d['year'];
    	return $year;
    }
	public static function Table($tablename){
		return $tablename."_".Common::getYear();
	}
	public static function Deseriallize($string){
		$r = array();
		$r = explode("~",$string);
		for($i=0;$i<count($r);$i++){
			$r[$i] = str_replace("&split;","~",$r[$i]);
			$r[$i] = str_replace("&amp;","&",$r[$i]);
		}
		return $r;
	}
	public static function SerializeString($s){
		$s = str_replace("&","&amp;",$s);
		$s = str_replace("~","&split;",$s);
		return $s;
	}
	public static function SerializeData($object){
		$data = "";
		$fields = mysql_num_fields($object);
		$data .= $fields;
		for($i=0; $i < $fields; $i++) {
		    $fieldname  = mysql_field_name($object, $i);
		    $data .= "~".$fieldname;
		}
		while ($row = mysql_fetch_assoc($object)) {
			for($i=0; $i < $fields; $i++) {
				$fieldname  = mysql_field_name($object, $i);
			    $data .= "~".Common::SerializeString($row[$fieldname]);
			}
		}
		return $data;
	}

	public static function isAdministrators($username,$password){
		$sid = "0";
		$sql = sprintf("SELECT * FROM qtht_users  u
		inner join fk_users_groups fk on u.ID_U = fk.ID_U
		inner join qtht_groups g on fk.ID_G = g.ID_G 
		where USERNAME = '%s' and PASSWORD = '%s' and g.CODE = 'NQT'  " ,
			mysql_real_escape_string($username),md5(mysql_real_escape_string($password)));
			
		$user = query($sql);
		if(mysql_num_rows($user)==1){
			return true;
		}
		return false;
	}
}