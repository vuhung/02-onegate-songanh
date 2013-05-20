<?php
	class Xml_Utils{
	
	
	function xmlToArrayDetail($obj,&$arr,$level =0,$parent=""){
		if ( is_null( $arr ) ) $arr = array();
        if ( is_string( $obj ) ) {
			
			$obj = new SimpleXMLElement( $obj );
		
		}
        
		//var_dump($obj->getName());
		$arrtemp = array();
		$namecur = $arrtemp['name'] = $obj->getName();
		$arrtemp['level'] = $level;
		$arrtemp['parent'] = $parent;
		$children = $obj->children();
		
		if($children.length){
			foreach($children as $elementName => $node ){
				//$arr['chidren'] = array();
				$arrtemp['childs'] = Xml_Utils::xmlToArrayDetail($node,&$arrtemp['childs'],$level+1,($parent."/$namecur"));
			}
		}
		$arr[$namecur] = $arrtemp;
		return $arr;
	}
	
	public static function toArray( $obj, &$arr = null){
        if ( is_null( $arr ) ) $arr = array();
        if ( is_string( $obj ) ) $obj = new SimpleXMLElement( $obj );
        $children = $obj->children();
        $executed = false;
        $arrtemp = array();
		foreach ($children as $elementName => $node){
            $keysearch = array_search($elementName,$arrtemp);
			if(!$keysearch){
				$arrtemp[] = $elementName;
				if($arr[$elementName]!=null){
					if($arr[$elementName][0]!==null){
						$i = count($arr[$elementName]);
						Xml_Utils::toArray($node, $arr[$elementName][$i]);
					}else{
						$tmp = $arr[$elementName];
						$arr[$elementName] = array();
						$arr[$elementName][0] = $tmp;
						
						$i = count($arr[$elementName]);
						Xml_Utils::toArray($node, $arr[$elementName][$i]);
					}
				}else{
					$arr[$elementName] = array();
					Xml_Utils::toArray($node, $arr[$elementName]);
				}
				$executed = true;
			}
        }
        if(!$executed&&$children->getName()==""){
            $arr = (String)$obj;
        }
        return $arr;
    }

	 /**
     * The main function for converting to an XML document.
     * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
     *
     * @param array $data
     * @param string $rootNodeName - what you want the root node to be - defaultsto data.
     * @param SimpleXMLElement $xml - should only be used recursively
     * @return string XML
     */
    public static function toXml($data, $rootNodeName = 'data', &$xml=null)
    {
        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if (ini_get('zend.ze1_compatibility_mode') == 1)
        {
            ini_set ('zend.ze1_compatibility_mode', 0);
        }
        
        if (is_null($xml))
        {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
        }
        
        // loop through the data passed in.
        foreach($data as $key => $value)
        {
            // no numeric keys in our xml please!
            if (is_numeric($key))
            {
                // make string key...
                //$key = "unknownNode_". (string) $key;
				$key = "<item>";
            }
            
            // delete any char not allowed in XML element names
            $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);
            
            // if there is another array found recrusively call this function
            if (is_array($value))
            {
                $node = $xml->addChild($key);
                // recrusive call.
                Xml_Utils::toXml($value, $rootNodeName, $node);
            }
            else 
            {
                // add single node.
                $value = htmlentities($value);
                $xml->addChild($key,$value);
            }
            
        }
        // pass back as string. or simple xml object if you want!
        return $xml->asXML();
    }
	
	
	function IsItemArray($data,$namefield){
			foreach($data as $datait){
				foreach($datait as $it)
				{
					if($it["DESPAR"] == $namefield )
						return 1;
				}
			}
			return 0;
	}
	

	function buildXsdltFromArray($data,&$xml,$level,$parent,$parentpath){
		$num_array = count($mappinginfo);
		
		foreach($data[$level] as $it){
				if($level == 0){
					$xml .='<xsl:template match="'.$it["SRCFIELD"].'">';
				}
				
				if( ( $parent == $it["DESPAR"])  ){
					
					$is_array = Xml_Utils::IsItemArray($data,$it["DESFIELD"]) && $level;
					if( $is_array ){
						$xml .= '<xsl:for-each select="'.$parentpath.'/'.$it["SRCFIELD"].'">';
					}
					$xml .= "<".$it["DESFIELD"].">";
					
					
						//$parentpath = $parentpath.'/'.$it["DESFIELD"];		
						Xml_Utils::buildXsdltFromArray($data,&$xml,($level+1),$it["DESFIELD"],$parentpath.'/'.$it["SRCFIELD"]);
					
					if(!$is_array){
						$xml .= '<xsl:value-of select="'.str_repeat("../",(int)$it["LEVEL_DES"]-(int)$it["LEVEL_SRC"]).$it["SRCFIELD"].'"/>';
					}
					$xml .= "</".$it["DESFIELD"]."> \n";
					if( $is_array ){
						$xml .= '</xsl:for-each >';
					}
				}
				if($level == 0){
					$xml .='</xsl:template>';
				}
								
		}
		
		return $numchild;
		
	}
	
	function createXSDLT($id_service){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select 
			GROUP_CONCAT(PARRENT_SRC) as PARRENT_SRC,
			GROUP_CONCAT(PARRENT_DES) as PARRENT_DES,
			GROUP_CONCAT(NAMEFIELDDES) as DESFIELDS , 
			GROUP_CONCAT(NAMEFIELDSRC) as SRCFIELDS ,
			GROUP_CONCAT(LEVEL_DES) as LEVEL_DES,
			GROUP_CONCAT(LEVEL_SRC) as LEVEL_SRC
			from htdb_mappingdefinition map where ID_SERVICE = ?
			GROUP BY LEVEL_DES
		";
		$stm = $db->query($sql,array($id_service));
		$mappinginfo = $stm->fetchAll();
		//repair data mapping
		$arrR = array();
		$level = 0;
		foreach($mappinginfo as $mapping){
			//tim mapping con
			$arrR[$level] = array();
			$desfarr = explode(",",$mapping["DESFIELDS"]);
			$srcfarr = explode(",",$mapping["SRCFIELDS"]);
			$prdes   = explode(",",$mapping["PARRENT_DES"]);
			$prsrc   = explode(",",$mapping["PARRENT_SRC"]);
			$levelsrc   = explode(",",$mapping["LEVEL_SRC"]);
			$leveldes   = explode(",",$mapping["LEVEL_DES"]);
			$i = 0;
			foreach($desfarr as $desf){
				$arrtemp =  array();
				$arrtemp["DESFIELD"] = $desf;
				$arrtemp["SRCFIELD"] = $srcfarr[$i];
				$arrtemp["DESPAR"] = $prdes[$i];
				$arrtemp["SRCPAR"] = $prsrc[$i];
				$arrtemp["LEVEL_SRC"] = $levelsrc[$i];
				$arrtemp["LEVEL_DES"] = $leveldes[$i];
				$i++;
				$arrR[$level][] = $arrtemp;
			}
			$level ++;
		}
		
		//tao file xml
		$xmldes = '
			<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                >
				
				
		';
		Xml_Utils::buildXsdltFromArray($arrR,&$xmldes,0,"");
		$xmldes .="</xsl:stylesheet>";
		/*$xmldes = '
			<xsl:stylesheet version="2.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns="http://www.w3.org/1999/xhtml">
				<'.$desroot.'>
				<xsl:template match="'.$srcroot.'">
				
		';
		foreach($mappinginfo as $mapping){
			$xmldes .= '<'.$mapping["PARRENT_DES"].'>';
			$xmldes .= '<xsl:for-each select="..'.''.'/'.$mapping["PARRENT_SRC"].'">';
			$xmldes .= "<".$mapping["NAMEFIELDDES"].">";
			$xmldes .= "<xsl:value-of select='".$mapping["NAMEFIELDSRC"]."'/>";
			$xmldes .= "</".$mapping["NAMEFIELDDES"].">";
			$xmldes .= '</xsl:for-each>';
			$xmldes .= '</'.$mapping["PARRENT_DES"].'>';
		}
		$xmldes .="</xsl:template>
				   </".$desroot.">
					";
		*/
		return $xmldes;
	}

	function arrdatadbtoxml($data){
		$doc = new DomDocument('1.0', 'UTF-8');
		$root = $doc->createElement('data');
		$root = $doc->appendChild($root);
		foreach($data as $item){
			$e = $doc->createElement('item');
			$e = $root->appendChild($e);
			foreach($item as $key=>$value){
				$ce = $doc->createElement($key);
				$ce = $e->appendChild($ce);
				$textnode = $doc->createTextNode(base64_encode($value));
				$textnode = $ce->appendChild($textnode);
			}
		}
		return $doc->saveXML();
	}

	}
?>