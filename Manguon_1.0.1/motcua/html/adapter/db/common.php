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
    
    static function deseriallizeToArray($string) {
        $r = array();
        $r = explode("~", $string);
        for ($i = 0; $i < count($r); $i++) {
            $r[$i] = str_replace("&split;", "~", $r[$i]);
            $r[$i] = str_replace("&amp;", "&", $r[$i]);
        }
        $array_result = array();
        $colnum = $r[0]; //so cot
        $incol = 1;
        $countr = count($r);

        $num_object = (int) ((int) $countr / (int) $colnum);

        for ($i = 1; $i < $num_object; $i++) {
            $temp = array();
            for ($j = 0; $j < $colnum; $j++) {
                $temp["" . $r[$j + 1] . ""] = base64_decode($r[$i * $colnum + $j + 1]);
            }
            $array_result[$i - 1] = $temp;
        }
        return $array_result;
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
    /*
    * @purpose : Chuyen du lieu sang dang XML
    * @author : Baotq
    * @dateCreate : 12/10/2012
    */
	public static function toXML($object){
        $doc = new DomDocument('1.0', 'UTF-8');
        $root = $doc->createElement("DATA");
        $root = $doc->appendChild($root);
        
      
		$fields = mysql_num_fields($object);
		while ($row = mysql_fetch_assoc($object)) {
			$child = $doc->createElement("ITEM");
            $child = $root->appendChild($child);
			for($i=0; $i < $fields; $i++) {
				$fieldname  = mysql_field_name($object, $i);
                $col = $doc->createElement($fieldname);
                $col = $child->appendChild($col);
                $value = $doc->createTextNode(base64_encode($row[$fieldname]));
                $value = $col->appendChild($value);
                /*
				$xml.="<".$fieldname.">";
				$item = base64_encode($row[$fieldname]);
                $xml .= $item;
				$xml.="</".$fieldname.">";
                */
			}
		
		}
		$xml_string = $doc->saveXML();
		return $xml_string;
	}
    public static function getMaKiemTra($maso){
        $arrNumber = str_split($maso);
		$a = $arrNumber[0]+$arrNumber[2]+$arrNumber[4]+$arrNumber[6]+$arrNumber[8]+$arrNumber[10];
        $b = ($arrNumber[1]+$arrNumber[3]+$arrNumber[5]+$arrNumber[7]+$arrNumber[9]+$arrNumber[11])*3;
        $x = ($a+$b)%10;
        if($x != 0){
            $x = 10 - $x;
        }
        return $x;
	}
    public static function getLastSubStr($str,$length){
		$len_str = strlen($str);
		
		if($len_str < $length){
			$d = $length- $len_str;
			$str_add = str_repeat('0',$d);
			return $str_add.$str;
		}
		if($len_str > $length){
			$l = 0 - $length;
			return substr($str,-2);
		}
		return $str;
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