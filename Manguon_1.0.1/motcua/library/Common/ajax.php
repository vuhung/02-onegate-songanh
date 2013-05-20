<?php
class ajax
{
    protected static $_customType = null;
    public function setCustomType($type){
        self::$_customType = $type;
    }
    public function getCustomType(){
        return self::$_customType;
    }
	public static function ship($name,$object)
	{
		$customType = self::getCustomType();
        if($customType == null){
            $objType = gettype($object);
            switch ($objType) {
              case "string":
                echo '01 '.$name.">".ajax::stringPackage($object); 
                break;
              case "resource":
                echo '02 '.$name.">".ajax::resourcePackage($object);
                break;
              case "object":
                echo '02 '.$name.">".ajax::resourcePackage($object);
                break;
              case "array":
                echo '03 '.$name.">".ajax::arrayPackage($object);
                break;
              case "boolean":
                echo '04 '.$name.">".ajax::booleanPackage($object);
                break;
              case "integer":
                echo '05 '.$name.">".ajax::stringPackage($object); 
                break;
            }
        }else{
            if($customType == "arrayMultiDimension"){
                echo '02 '.$name.">".ajax::arrayMultiDimension($object);
            }
        }
	}	
	private static function stringPackage($string)
	{		
		return ajax::FilterString($string)."&&";
	}
	private static function booleanPackage($string)
	{		
		return ajax::FilterString($string)."&&";
	}	
	private static function FilterString($s){
		$s = str_replace(">","\u003E",$s);
		$s = str_replace("&","\u0026",$s);
		$s = str_replace("~","\u007E",$s);
        $s = str_replace("'","\u0027",$s);
		$s = str_replace('"',"\u0022",$s);
		return $s;
	}
	private static function FilterArray($s){
		$s = str_replace(">","\u003E",$s);
		$s = str_replace("&","\u0026",$s);
		$s = str_replace("~","\u007E",$s);
		$s = str_replace(":","\u003A",$s);
        $s = str_replace("'","\u0027",$s);
		$s = str_replace('"',"\u0022",$s);
		return $s;
	}
    
	private static function arrayMultiDimension($array){
		$data = "";
		$fields = count($array[0]);
		$data .= $fields;
        foreach(array_keys($array[0]) as $sub)
		{
            $data .= "~".$sub;
		}
        for($i=0; $i < count($array); $i++) {
            foreach(array_keys($array[$i]) as $sub)
            {
                $data .= "~".self::FilterString($array[$i][$sub]);
            }
        }
		return $data."&&";
	}
	private static function resourcePackage($object){
		$data = "";
		$fields = $object->columnCount();
		$data .= $fields;
		for($i=0; $i < $fields; $i++) {
		    $ifield  = $object->getColumnMeta($i);
		    $fieldname  = $ifield['name'];
		    $data .= "~".$fieldname;
		}
		while ($row = $object->fetch()) {
			for($i=0; $i < $fields; $i++) {
				$ifield  = $object->getColumnMeta($i);
				$fieldname  = $ifield['name'];
			    $data .= "~".ajax::FilterString($row[$fieldname]);
				}
		}
		return $data."&&";
	}
	private static function arrayPackage($arr){
		$data = "";
		$i = 0;
		foreach(array_keys($arr) as $sub)
		{
			$data .= "~".ajax::FilterArray($sub).":".ajax::FilterArray($arr[$i]);
			$i++;
		}		
		$data = ltrim($data,"~")."&&";
		return $data;
	}
}
?>