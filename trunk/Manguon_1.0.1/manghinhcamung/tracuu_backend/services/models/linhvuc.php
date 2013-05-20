<?php
class linhvuc
{
	public static function GetData()
    {
    	$sql="";
    	$messages = null;
    	
			$sql = "
	    	SELECT * FROM linhvuc";
	    	$messages = query($sql);
		//	return $messages;
	    	$xml="<LINHVUCS>";
	    	while($item = mysql_fetch_assoc($messages)){
	    			$xml.="<LINHVUC>";
					$xml.="<ID_LINHVUC>".($item['ID_LINHVUC'])."</ID_LINHVUC>";
					$xml.="<TEN>".base64_encode($item['TEN'])."</TEN>";
					$xml.="<ACTIVE>".base64_encode($item['ACTIVE'])."</ACTIVE>";
					$xml.="</LINHVUC>";
	    	}
	    	$xml.="</LINHVUCS>";
	        return $xml;
    		
    }

	public function getGoithieu(){
		$sql = "
	    	SELECT GIOITHIEU FROM gioithieu ";
	    	$messages = query($sql);
		
	    	while($item = mysql_fetch_assoc($messages)){
	    			return $item['GIOITHIEU'];
	    	}
	    	
	}

	function getCocautochuc(){
		$sql = "
	    	SELECT COCAUTOCHUC FROM gioithieu ";
	    	$messages = query($sql);
		
	    	while($item = mysql_fetch_assoc($messages)){
	    			return $item['COCAUTOCHUC'];
	    	}
	}
}