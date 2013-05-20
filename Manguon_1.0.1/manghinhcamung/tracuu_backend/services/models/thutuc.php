<?php
class thutuc
{
	public static function GetData($idu)
    {
    	$sql="";
    	$messages = null;
    		//mysql_query("SET CHARACTER SET utf8");
			$sql = sprintf("select CHITIET_HOSO , lhs.TEN ,IMAGE_QUITRINH , lv.TEN as TEN_LINHVUC, lhs.LEPHI, lhs.SONGAYXULY from loaihoso lhs
			inner join linhvuc lv on lhs.ID_LINHVUC = lv.ID_LINHVUC 
			where ID_LOAIHOSO= '%s' ",mysql_real_escape_string($idu));
	    	$messages = query($sql);
			//return base64_encode($messages);
	 		$xml="<THUTUCS>";
	    	while($item = mysql_fetch_assoc($messages)){
	    			/*$xml.="<CHITIETHOSO>";
					$xml.="<ID_THUTUC>".base64_encode($item['ID_THUTUC'])."</ID_THUTUC>";
					$xml.="<TEN>".base64_encode($item['TEN'])."</TEN>";
					$xml.="<ACTIVE>".base64_encode($item['ACTIVE'])."</ACTIVE>";
					$xml.="<GHICHU>".base64_encode($item['GHICHU'])."</GHICHU>";
					$xml.="<ID_RESOURCE>".base64_encode($item['ID_RESOURCE'])."</ID_RESOURCE>";
					$xml.="</THUTUC>";*/
					$xml.="<TEN_LINHVUC>";
					
					$xml.=base64_encode($item['TEN_LINHVUC']);
					$xml.="</TEN_LINHVUC>";
					$xml.="<TEN>";
					
					$xml.=base64_encode($item['TEN']);
					$xml.="</TEN>";
					$xml.="<LEPHI>";
					
					$xml.=base64_encode($item['LEPHI']);
					$xml.="</LEPHI>";

					$xml.="<SONGAYXULY>";
					
					$xml.=($item['SONGAYXULY']);
					$xml.="</SONGAYXULY>";

					$xml.="<CHITIETHOSO>";
					//src="/public/userfiles/image/GTVT/QLGTDT/2.png"
					$html_ct = base64_decode($item['CHITIET_HOSO']);
					$html_ct = str_replace("/public/userfiles/image","http://tracuumc_gtvt.vn/public/userfiles/image",$html_ct);
					$xml.=base64_encode($html_ct);
					$xml.="</CHITIETHOSO>";
					
					$xml.="<IMAGE_QUITRINH>";
					
					$xml.= base64_encode($item['IMAGE_QUITRINH']);
					$xml.="</IMAGE_QUITRINH>";
	    	}
	    	$xml.="</THUTUCS>";
	        return $xml;

    		
    }
}
