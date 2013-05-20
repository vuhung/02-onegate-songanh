<?php
class loaihoso
{
	public static function GetData($idu)
    {
    	$sql="";
    	$messages = null;
    	
			$sql = sprintf("select lhs.*,lv.TEN as TEN_LINHVUC from loaihoso lhs
		inner join linhvuc lv on  lhs.ID_LINHVUC = lv.ID_LINHVUC
		where lv.ID_LINHVUC= '%s' ",mysql_real_escape_string($idu));
			$messages = query($sql);
		//	return $messages;
	    	$xml="<LOAIHOSOS>";
	    	while($item = mysql_fetch_assoc($messages)){
	    			$xml.="<LOAIHOSO>";
					$xml.="<ID_LOAIHOSO>".($item['ID_LOAIHOSO'])."</ID_LOAIHOSO>";
					$xml.="<ID_LINHVUC>".base64_encode($item['ID_LINHVUC'])."</ID_LINHVUC>";
					$xml.="<TEN_LINHVUC>".base64_encode($item['TEN_LINHVUC'])."</TEN_LINHVUC>";
					$xml.="<TEN>".base64_encode($item['TEN'])."</TEN>";
					$xml.="<LEPHI>".base64_encode($item['LEPHI'])."</LEPHI>";
					$xml.="<SONGAYXULY>".base64_encode($item['SONGAYXULY'])."</SONGAYXULY>";
					$xml.="<GHICHU>".base64_encode($item['GHICHU'])."</GHICHU>";
					$xml.="<ACTIVE>".base64_encode($item['ACTIVE'])."</ACTIVE>";
					$xml.="</LOAIHOSO>";
	    	}
	    	$xml.="</LOAIHOSOS>";
	        return $xml;
    		
    }
}