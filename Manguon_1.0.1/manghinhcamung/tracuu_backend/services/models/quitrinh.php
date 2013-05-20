<?php
class quitrinh
{
	public static function GetData($idu)
    {
    	$sql="";
    	$messages = null;
    	
			$sql = sprintf("select qt.* from quitrinh qt
		inner join fk_quitrinhs_loaihoso fk on  qt.ID_QUITRINH = fk.ID_QUITRINH
		where fk.ID_LOAIHOSO= '%s' ",mysql_real_escape_string($idu));
	    	$messages = query($sql);
		//	return $messages;
	 		$xml="<QUITRINHS>";
	    	while($item = mysql_fetch_assoc($messages)){
	    			$xml.="<QUITRINH>";
					$xml.="<ID_QUITRINH>".base64_encode($item['ID_QUITRINH'])."</ID_QUITRINH>";
					$xml.="<TEN>".base64_encode($item['TEN'])."</TEN>";
					$xml.="<ID_RESOURCE>".base64_encode($item['ID_RESOURCE'])."</ID_RESOURCE>";
					$xml.="<GHICHU>".base64_encode($item['GHICHU'])."</GHICHU>";
					$xml.="<ACTIVE>".base64_encode($item['ACTIVE'])."</ACTIVE>";
					$xml.="</QUITRINH>";
	    	}
	    	$xml.="</QUITRINHS>";
	        return $xml;
    		
    }
}
