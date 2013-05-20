<?php

/**
 * BussinessDateModel
 *  
 * @author baotq, moving to adapter dvc
 * @version 
 */
class BussinessDateModel {
	
	static function IsNonWorkingDate($ngay){
		$sql = "SELECT * FROM GEN_NONWORKINGDATES";
        $r = query($sql);
        while($item = mysql_fetch_array($r))
        {
			if($item['ISCOMMON']==1 && $item['WDAY']==$ngay['wday']){
				return true;
			}else if($item['ISMONTH']==1 && 
			($ngay['mday']+$ngay['mon']*31 >= $item['BDAY']+$item['BMON']*31) && 
			($ngay['mday']+$ngay['mon']*31 <= $item['EDAY']+$item['EMON']*31)){
				return true;
			}
		}
		return false;
	}
    
    static function addDate($date,$value){
        $inc = 0;
        //Đếm ngày từ ngày đến ngày hiện tại
        $value = $value*8;
        while(true){
            $date += 3600;
            $nocount = 1;
            if(self::IsNonWorkingDate(getdate($date))){
                $nocount=0;
            }
            $hour = date("H",$date);
            if($hour>=8 && $hour<12){
                $inc += $nocount;
            }else if($hour>=13 && $hour<17){
                $inc += $nocount;
            }				
            if($inc>=$value)break;
        }
        return $date;	
    }
}
