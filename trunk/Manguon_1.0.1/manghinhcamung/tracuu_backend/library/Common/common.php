<?php
	require_once 'qtht/models/BussinessDateModel.php';
	class QLVBDHCommon{
		static function highlightWords($text, $words) { 
		    if(is_null($colors) || !is_array($colors))
	        {
	                $colors = array('yellow', 'pink', 'green');
	        }
	
	        $i = 0;
	        /*** the maximum key number ***/
	        $num_colors = max(array_keys($colors));
			
			$words = trim($words);
		    $the_count = 0; 
		    $wordsArray = explode(' ', $words); 
			foreach($wordsArray as $word) { 
				if(strlen(trim($word)) != 0){
				    $text = str_ireplace($word, '~'.$i.$word.'^'.$i, $text, $count);
					if($i==$num_colors){ $i = 0; } else { $i++; }
				    $the_count = $count + $the_count;
				}
			}
			//var_dump($colors);
			for($i=0;$i<count($colors);$i++){
				$text = str_replace('~'.($i),'<span style="background-color:'.$colors[$i].'">',$text);
				$text = str_replace('^'.($i),'</span>',$text);
			}
			return $text;
		}
		
		static function AutoComplete($data,$fieldid,$fieldname,$controlid,$controltext,$fullcompare,$style,$onokaction,$defaultid,$defaultname){
			$html="
				<script>
					var DATA_$controlid = new Array();
			";
			foreach($data as $item){
				if($item[$fieldid]==$defaultid)$defaultname=$item[$fieldname];
				$html.="DATA_".$controlid."[DATA_".$controlid.".length]=new Array('".$item[$fieldid]."','".$item[$fieldname]."');";
			}
			$html.="
				</script>
			";
			$html.="
				<input autocomplete=off onclick='cancelEvent(event)' class=autocombobox value='$defaultname' type=text style='$style' name=$controltext id=$controltext  onkeydown='at_KeyDown(event)' onkeyup='at_Display(event)' onfocus=\"at_Load('$controltext','$controlid',DATA_$controlid,".($fullcompare==true?"true":"false").",'$onokaction');\">
				<input type=hidden style='$style' name=$controlid id=$controlid value='$defaultid'>
			";
			return $html;
		}

		static function Table($tablename){
			return $tablename."_".QLVBDHCommon::getYear();
		}
		static function ShowError($message){
			$this->_redirect("/demo/cd/list");
		}
		static function GetTree($tree,$tablename,$fieldid,$fieldidparent,$parentvalue,$level){
			global $db;
			//echo "SELECT *,$level as LEVEL from $tablename where $fieldidparent=$parentvalue";
			$r = $db->query("SELECT *,$level as LEVEL from $tablename where $fieldidparent=?",array($parentvalue));
			$rows = $r->fetchAll();
			$r->closeCursor();
			
			for($i=0;$i<$r->rowCount();$i++){
				$tree[count($tree)] = $rows[$i];
				QLVBDHCommon::GetTree(&$tree,$tablename,$fieldid,$fieldidparent,$rows[$i][$fieldid],$level+1);
			}
		}
		static function GetTreeWithCase($tree,$tablename,$fieldid,$fieldidparent,$parentvalue,$level,$params){
			$arr_where = array();
			$arr_param = array($parentvalue);
			$ln = 0;
			foreach ($params as $key => $value ){
				$arr_where[] = " $key= ? ";
				$arr_param[] = $value;
				$ln = 1;
			}
			$where_pr = "";
			if($ln == 1){
				$where_pr = " and " . implode(" and ",$arr_where);
			}
			global $db;
			//echo "SELECT *,$level as LEVEL from $tablename where $fieldidparent=$parentvalue";
			$r = $db->query("SELECT *,$level as LEVEL from $tablename where $fieldidparent=? $where_pr ",$arr_param);
			$rows = $r->fetchAll();
			$r->closeCursor();
			
			for($i=0;$i<$r->rowCount();$i++){
				$tree[count($tree)] = $rows[$i];
				QLVBDHCommon::GetTree(&$tree,$tablename,$fieldid,$fieldidparent,$rows[$i][$fieldid],$level+1);
			}
		}
		static function GetTreeNoChild($tree,$tablename,$fieldid,$fieldidparent,$parentvalue,$level,$id){
			global $db;
			//echo "SELECT *,$level as LEVEL from $tablename where $fieldidparent=?";
			$r = $db->query("SELECT *,$level as LEVEL from $tablename where $fieldidparent=?",array($parentvalue));
			$rows = $r->fetchAll();
			$r->closeCursor();
			
			for($i=0;$i<$r->rowCount();$i++){
				if($rows[$i][$fieldid]!=$id){
					$tree[count($tree)] = $rows[$i];
					QLVBDHCommon::GetTreeNoChild(&$tree,$tablename,$fieldid,$fieldidparent,$rows[$i][$fieldid],$level+1,$id);
				}
			}
		}
		static function GetTreeByName($value,$fieldname,$tree,$tablename,$fieldid,$fieldidparent,$parentvalue,$level){
			global $db;
			//$value = '%b%';
			$arrparam = array($value,$parentvalue);
			$r = $db->query("SELECT *,$level as LEVEL from $tablename where  $fieldname like ? and $fieldidparent=?",$arrparam);
			$rows = $r->fetchAll();
			$r->closeCursor();
			
			for($i=0;$i<$r->rowCount();$i++){
				$tree[count($tree)] = $rows[$i];
				QLVBDHCommon::GetTree(&$tree,$tablename,$fieldid,$fieldidparent,$rows[$i][$fieldid],$level+1);
			}
		}
		static function Paginator($numrows,$posrows,$valuepage,$formname,$currentpage){
			$html = "";
			if(floor($numrows/$valuepage) < $posrows){
				if($numrows % $valuepage == 0){
					$posrows = floor($numrows/$valuepage);	
				}
				else{
					$posrows = floor($numrows/$valuepage)+1;
				}
			}
			$pageCurrent = $currentpage;
			$beginpage = $currentpage - $posrows + 1;
			if($beginpage<=0){
				$beginpage=1;
			}
			$endpage = $beginpage + $posrows - 1;
			
			if($currentpage!=1){
				$hasFirst = "
					<div class='button2-right'><div class='start'><a href='#' title='Bắt đầu' onclick='javascript: document.".$formname.".page.value=1; document.".$formname.".submit();'>Bắt đầu</a></div></div>
					<div class='button2-right'><div class='prev'><a href='#' title='Trước' onclick='javascript: document.".$formname.".page.value=".($currentpage-1)."; document.".$formname.".submit();'>Trước</a></div></div>
				";
			}
			
			if($currentpage < $numrows/$valuepage){
				$hasNext = "
					<div class='button2-left'><div class='next'><a href='#' title='Tiếp' onclick='javascript: document.".$formname.".page.value=".($currentpage+1)."; document.".$formname.".submit();'>Tiếp</a></div></div>
					<div class='button2-left'><div class='end'><a href='#' title='Cuối' onclick='javascript: document.".$formname.".page.value=".(ceil($numrows/$valuepage))."; document.".$formname.".submit();'>Cuối</a></div></div>
				";
			}
			$html .= $hasFirst;
			$html .= "<div class='button2-left'><div class='page'>";
			for($i=$beginpage;$i<=$endpage;$i++){
				if($i==$currentpage){
					$html .= "<span>".$i."</span>";
				}else{
					$html .= "<a href='#' onclick='document.".$formname.".page.value=".$i."; document.".$formname.".submit();'> ".$i." </a>";
				}
			}
			$html .= "</div></div>";
			$html .= $hasNext;
			return $html;
		}
		static function PaginatorAjax($numrows,$posrows,$valuepage,$formname,$currentpage,$action,$divid){
			$html = "";
			if(floor($numrows/$valuepage) < $posrows){
				if($numrows % $valuepage == 0){
					$posrows = floor($numrows/$valuepage);	
				}
				else{
					$posrows = floor($numrows/$valuepage)+1;
				}
			}
			$pageCurrent = $currentpage;
			$beginpage = $currentpage - $posrows + 1;
			if($beginpage<=0){
				$beginpage=1;
			}
			$endpage = $beginpage + $posrows - 1;
			
			if($currentpage!=1){
				$hasFirst = "
					<div class='button2-right'><div class='start'><a href='#' title='Bắt đầu' onclick=\"
						document.".$formname.".page.value=1;
						hscv = new AjaxEngine();
						hscv.loadDivFromUrlAndForm('".$divid."','".$action."',document.".$formname.");
					\">Bắt đầu</a></div></div>
					<div class='button2-right'><div class='prev'><a href='#' title='Trước' onclick=\"
						document.".$formname.".page.value=".($currentpage-1).";
						hscv = new AjaxEngine();
						hscv.loadDivFromUrlAndForm('".$divid."','".$action."',document.".$formname.");
					\">Trước</a></div></div>
				";
			}
			
			if($currentpage < $numrows/$valuepage){
				$hasNext = "
					<div class='button2-left'><div class='next'><a href='#' title='Tiếp' onclick=\"
						document.".$formname.".page.value=".($currentpage+1).";
						hscv = new AjaxEngine();
						hscv.loadDivFromUrlAndForm('".$divid."','".$action."',document.".$formname.");	
					\">Tiếp</a></div></div>
					<div class='button2-left'><div class='end'><a href='#' title='Cuối' onclick=\"
						document.".$formname.".page.value=".(ceil($numrows/$valuepage)).";
						hscv = new AjaxEngine();
						hscv.loadDivFromUrlAndForm('".$divid."','".$action."',document.".$formname.");	
					\">Cuối</a></div></div>
				";
			}
			$html .= $hasFirst;
			$html .= "<div class='button2-left'><div class='page'>";
			for($i=$beginpage;$i<=$endpage;$i++){
				if($i==$currentpage){
					$html .= "<span><font color=red>".$i."</font></span>";
				}else{
					$html .= "<a href='#' onclick=\"
						document.".$formname.".page.value=".$i.";
						hscv = new AjaxEngine();
						hscv.loadDivFromUrlAndForm('".$divid."','".$action."',document.".$formname.");\"> ".$i." </a>";
				}
			}
			$html .= "</div></div>";
			$html .= $hasNext;
			return $html;
		}
		static function PaginatorWithAction($numrows,$posrows,$valuepage,$formname,$currentpage,$action){
			$html = "";
			if(floor($numrows/$valuepage) < $posrows){
				if($numrows % $valuepage == 0){
					$posrows = floor($numrows/$valuepage);	
				}
				else{
					$posrows = floor($numrows/$valuepage)+1;
				}
			}
			$pageCurrent = $currentpage;
			$beginpage = $currentpage - $posrows + 1;
			if($beginpage<=0){
				$beginpage=1;
			}
			$endpage = $beginpage + $posrows - 1;
			
			if($currentpage!=1){
				$hasFirst = "
					<div class='button2-right'><div class='start'><a href='#' title='Bắt đầu' onclick=\"
						document.".$formname.".page.value=1;
						document.".$formname.".action='".$action."';
						document.".$formname.".submit();
					\">Bắt đầu</a></div></div>
					<div class='button2-right'><div class='prev'><a href='#' title='Trước' onclick=\"
						document.".$formname.".page.value=".($currentpage-1).";
						document.".$formname.".action='".$action."';
						document.".$formname.".submit();
					\">Trước</a></div></div>
				";
			}
			
			if($currentpage < $numrows/$valuepage){
				$hasNext = "
					<div class='button2-left'><div class='next'><a href='#' title='Tiếp' onclick=\"
						document.".$formname.".page.value=".($currentpage+1).";
						document.".$formname.".action='".$action."';
						document.".$formname.".submit();	
					\">Tiếp</a></div></div>
					<div class='button2-left'><div class='end'><a href='#' title='Cuối' onclick=\"
						document.".$formname.".page.value=".(ceil($numrows/$valuepage)).";
						document.".$formname.".action='".$action."';
						document.".$formname.".submit();
					\">Cuối</a></div></div>
				";
			}
			$html .= $hasFirst;
			$html .= "<div class='button2-left'><div class='page'>";
			for($i=$beginpage;$i<=$endpage;$i++){
				if($i==$currentpage){
					$html .= "<span><font color=red>".$i."</font></span>";
				}else{
					$html .= "<a href='#' onclick=\"
						document.".$formname.".page.value=".$i.";
						document.".$formname.".action='".$action."';
						document.".$formname.".submit();
					\"> ".$i." </a>";
				}
			}
			$html .= "</div></div>";
			$html .= $hasNext;
			return $html;
		}
		static function checkChild($data,$pos){
			$curlevel = $data[$pos]['LEVEL'];
			$curactid = $data[$pos]['ACTID'];
			$pos++;
			if($pos>=count($data)){
				if($curactid==0)return false;
				return true;
			}
			while($curlevel<$data[$pos]['LEVEL']){
				if($data[$pos]['ACTID']!=0)return true;
				if($pos>=count($data)){
					if($curactid==0)return false;
					return true;
				}
				$pos++;
			}
			if($curactid==0)return false;
			return true;
		}
		/**
	     * Build menu with <li class="node"><a href="">Menu level 1</a><ul></li> formal;
	     * 
	     * @param  array $menuData
	     * @return echo menu
	     */
		static function buildMenuUL_LI($menuData)
		{
			$result='';
			$temp = array();
			for($pos=0;$pos<count($menuData);$pos++){
				if(QLVBDHCommon::checkChild($menuData,$pos)){
					$temp[] = $menuData[$pos];
				}
			}
			$menuData = $temp;
			for($pos=0;$pos<count($menuData);$pos++){
				if($menuData[$pos+1]["LEVEL"]>$menuData[$pos]["LEVEL"]){
					$html = "
						<li class='node'>
							<a href='".($menuData[$pos]["URL"]==""?$menuData[$pos]["URL_ACTION"]:$menuData[$pos]["URL"])."'>".$menuData[$pos]["NAME"]."</a>
							<ul>
					";
					$result .= $html;
				}else if($menuData[$pos+1]["LEVEL"]==$menuData[$pos]["LEVEL"]) {
					$html = "
						<li>
							<a href='".($menuData[$pos]["URL"]==""?$menuData[$pos]["URL_ACTION"]:$menuData[$pos]["URL"])."'>".$menuData[$pos]["NAME"]."</a>
						</li>
					";
					$result .= $html;
				}else{
					$html = "
						<li>
							<a href='".($menuData[$pos]["URL"]==""?$menuData[$pos]["URL_ACTION"]:$menuData[$pos]["URL"])."'>".$menuData[$pos]["NAME"]."</a>
						</li>
					";
				
					if($pos==count($menuData)-1){
						for($j=1;$j<=$menuData[$pos]["LEVEL"] - 1;$j++){
							$html .= "</ul></li>";
						}
					}else{
						for($j=1;$j<=$menuData[$pos]["LEVEL"] - $menuData[$pos+1]["LEVEL"];$j++){
							$html .= "</ul></li>";
						}
					}
					$result .= $html;
				}
			}
			return $result;		
		}
		static function calendar($value, $name, $id){
			return '
				<input type="text" size=10 name="'.$name.'" id="'.$id.'" value="'.$value.'" onblur="getMinDate(this);if(typeof(ChangeDate)==\'function\')eval(\'ChangeDate()\');"><img src="/images/calendar.png" onclick="document.getElementById(\''.$id.'\').focus();var dp_cal_'.$id.' = null;
					dp_cal_'.$id.'  = new Epoch(\'epoch_popup\',\'popup\',document.getElementById(\''.$id.'\'),false,'.QLVBDHCommon::getYear().',\'d/m\');
					dp_cal_'.$id.'.show();HasEvent=true;"></img>
			';
		}
		static function calendarFull($value, $name, $id){
			return '
				<input type="text" size=10 name="'.$name.'" id="'.$id.'" value="'.$value.'" onblur="getFullDate(this);if(typeof(ChangeDate)==\'function\')eval(\'ChangeDate()\');"><img src="/images/calendar.png" onclick="document.getElementById(\''.$id.'\').focus();var dp_cal_'.$id.' = null;
					dp_cal_'.$id.'  = new Epoch(\'epoch_popup\',\'popup\',document.getElementById(\''.$id.'\'),false,'.QLVBDHCommon::getYear().',\'d/m/Y\');
					dp_cal_'.$id.'.show();HasEvent=true;"></img>
			';
		}
		static function calendarFullWithNoEvent($value, $name, $id){
			return '
				<input type="text" size=10 name="'.$name.'" id="'.$id.'" value="'.$value.'" onblur="getFullDate(this);"><img src="/images/calendar.png" onclick="document.getElementById(\''.$id.'\').focus();var dp_cal_'.$id.' = null;
					dp_cal_'.$id.'  = new Epoch(\'epoch_popup\',\'popup\',document.getElementById(\''.$id.'\'),false,'.QLVBDHCommon::getYear().',\'d/m/Y\');
					dp_cal_'.$id.'.show();HasEvent=false;"></img>
			';
		}
		/**
	     * Get top error message
	     * 
	     * @return String
	     */
		public function getTopErrorMessage(Zend_Form_Element $element)
		{
			$getMessage=$element->getMessages();
			$getError=$element->getErrors();
			
			if($getMessage!=null && $getError!=null)
			{
				return $getMessage[$getError[0]];
			}
			else return '';
		}
		function returnMIMEType($filename)
	    {
	        preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);
	
	        switch(strtolower($fileSuffix[1]))
	        {
	            case "js" :
	                return "application/x-javascript";
	
	            case "json" :
	                return "application/json";
	
	            case "jpg" :
	            case "jpeg" :
	            case "jpe" :
	                return "image/jpg";
	
	            case "png" :
	            case "gif" :
	            case "bmp" :
	            case "tiff" :
	                return "image/".strtolower($fileSuffix[1]);
	
	            case "css" :
	                return "text/css";
	
	            case "xml" :
	                return "application/xml";
	
	            case "doc" :
	            case "docx" :
	                return "application/msword";
	
	            case "xls" :
	            case "xlt" :
	            case "xlm" :
	            case "xld" :
	            case "xla" :
	            case "xlc" :
	            case "xlw" :
	            case "xll" :
	                return "application/vnd.ms-excel";
	
	            case "ppt" :
	            case "pps" :
	                return "application/vnd.ms-powerpoint";
	
	            case "rtf" :
	                return "application/rtf";
	
	            case "pdf" :
	                return "application/pdf";
	
	            case "html" :
	            case "htm" :
	            case "php" :
	                return "text/html";
	
	            case "txt" :
	                return "text/plain";
	
	            case "mpeg" :
	            case "mpg" :
	            case "mpe" :
	                return "video/mpeg";
	
	            case "mp3" :
	                return "audio/mpeg3";
	
	            case "wav" :
	                return "audio/wav";
	
	            case "aiff" :
	            case "aif" :
	                return "audio/aiff";
	
	            case "avi" :
	                return "video/msvideo";
	
	            case "wmv" :
	                return "video/x-ms-wmv";
	
	            case "mov" :
	                return "video/quicktime";
	
	            case "zip" :
	                return "application/zip";
	
	            case "tar" :
	                return "application/x-tar";
	
	            case "swf" :
	                return "application/x-shockwave-flash";
	
	            default :
	            if(function_exists("mime_content_type"))
	            {
	                $fileSuffix = mime_content_type($filename);
	            }
	
	            return "unknown/" . trim($fileSuffix[0], ".");
	        }
	    }
		/**
		 * Tạo select user từ select department
		 * 
		 * @param int $DName tên combobox chứa danh sách department
		 * @param int $UName tên combobox chứa danh sách user thuộc department trên
		 * @return html code
		 */
		static function writeSelectDepartmentUser($DName,$UName){
			global $db;
			
			$arr_user = 'ARR_' . $UName;
			$department = array(); 
			QLVBDHCommon::GetTree(&$department,"QTHT_DEPARTMENTS","ID_DEP","ID_DEP_PARENT",1,1);
			$r = $db->query("
				SELECT
					DEP.ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
				FROM				
					QTHT_USERS U 
					INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
					INNER JOIN QTHT_DEPARTMENTS DEP ON E.ID_DEP=DEP.ID_DEP
				ORDER BY
					U.ORDERS, E.LASTNAME
			");
			$user = $r->fetchAll();
			$r->closeCursor();
			
			$html.="    	<select name=$DName id=$DName onchange='FillComboByComboExp(this,document.getElementById(\"$UName\"),$arr_user,arr_user);'>";
			$html.="        	<option value=0>--Chọn phòng--</option>";
			for($i=0;$i<count($department);$i++){
				$html.="        <option value=".$department[$i]["ID_DEP"].">".str_repeat("--",$department[$i]['LEVEL']).$department[$i]["NAME"]."</option>";
			}
			$html.="        </select>";
			
			$html.="<select name=$UName id=$UName></select>";
			
			$html.="<script>";
			$html.="	var $arr_user = new Array();";
			for($i=0;$i<count($user);$i++)
				$html.=$arr_user."[".$i."] = new Array('".$user[$i]['ID_DEP']."','".$user[$i]['ID_U']."','".$user[$i]['NAME']."');";
			$html.="	FillComboByComboExp(document.getElementById(\"$DName\"),document.getElementById(\"$UName\"),$arr_user,null);";
			$html.="</script>";
			
			return $html;
	    }
	    /**
		 * Tạo select user từ select department
		 * 
		 * @param int $DName tên combobox chứa danh sách department
		 * @param int $UName tên combobox chứa danh sách user thuộc department trên
		 * @return html code
		 */
		static function writeSelectDepartmentMultiUser($DName,$UName){
			global $db;
			
			$arr_user = 'ARR_' . $UName;
			$department = array(); 
			QLVBDHCommon::GetTree(&$department,"QTHT_DEPARTMENTS","ID_DEP","ID_DEP_PARENT",1,1);
			$r = $db->query("
				SELECT
					DEP.ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME,U.USERNAME AS USERNAME
				FROM				
					QTHT_USERS U 
					INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
					INNER JOIN QTHT_DEPARTMENTS DEP ON E.ID_DEP=DEP.ID_DEP
				ORDER BY
					U.ORDERS, E.LASTNAME
			");
			$user = $r->fetchAll();
			$r->closeCursor();
			
			$html.="    	<div style='float:left'><select name=$DName id=$DName onchange='FillComboByComboExp(this,document.getElementById(\"$UName\"),$arr_user,arr_user);'>";
			$html.="        	<option value=0>--Chọn phòng--</option>";
			for($i=0;$i<count($department);$i++){
				$html.="        <option value=".$department[$i]["ID_DEP"].">".str_repeat("--",$department[$i]['LEVEL']).$department[$i]["NAME"]."</option>";
			}
			$html.="        </select></div>";
			
			$html.="<div style='float:left'><select name=$UName id=$UName multiple size=5 ondblclick=\"if(typeof(InsertIntoArr)=='function')eval('InsertIntoArr()');\"></select></div>";
			
			$html.="<script>";
			$html.="	var $arr_user = new Array();";
			for($i=0;$i<count($user);$i++)
				$html.=$arr_user."[".$i."] = new Array('".$user[$i]['ID_DEP']."','".$user[$i]['USERNAME']."','".$user[$i]['NAME']."');";
			$html.="	FillComboByComboExp(document.getElementById(\"$DName\"),document.getElementById(\"$UName\"),$arr_user,null);";
			$html.="</script>";
			
			return $html;
	    }
	    static function getDepList($data,$parent){
	    	$result = array();
	    	foreach($data as $item){
	    		$result[] = $item['ID_DEP'];
	    	}
	    	$result[] = $parent;
	    	return $result;
	    }
		/**
		 * Tạo select user từ select department
		 * 
		 * @param int $DName tên combobox chứa danh sách department
		 * @param int $UName tên combobox chứa danh sách user thuộc department trên
		 * @return html code
		 */
		static function writeSelectDepartmentUserWithSel($DName,$UName,$sel,$depparent){
			global $db;
			
			$arr_user = 'ARR_' . $UName;
			$department = array(); 
			QLVBDHCommon::GetTree(&$department,"QTHT_DEPARTMENTS","ID_DEP","ID_DEP_PARENT",$depparent,1);
			$iddep = implode(",",QLVBDHCommon::getDepList($department,$depparent));
			$r = $db->query("
				SELECT
					DEP.ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
				FROM				
					QTHT_USERS U 
					INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
					INNER JOIN QTHT_DEPARTMENTS DEP ON E.ID_DEP=DEP.ID_DEP
				".($depparent>1?"WHERE
					DEP.ID_DEP in ($iddep)":"")."
				ORDER BY
					U.ORDERS, E.LASTNAME
			");
			$user = $r->fetchAll();
			$r->closeCursor();
			
			$html.="    	<select name=$DName id=$DName onchange='FillComboByComboWithSel(this,document.getElementById(\"$UName\"),$arr_user,$sel);'>";
			$html.="        	<option value=0>--Chọn phòng--</option>";
			for($i=0;$i<count($department);$i++){
				$html.="        <option value=".$department[$i]["ID_DEP"].">".str_repeat("--",$department[$i]['LEVEL']).$department[$i]["NAME"]."</option>";
			}
			$html.="        </select>";
			
			$html.="<select name=$UName id=$UName></select>";
			
			$html.="<script>";
			$html.="	var $arr_user = new Array();";
			for($i=0;$i<count($user);$i++)
				$html.=$arr_user."[".$i."] = new Array('".$user[$i]['ID_DEP']."','".$user[$i]['ID_U']."','".$user[$i]['NAME']."');";
			$html.="	FillComboByComboWithSel(document.getElementById(\"$DName\"),document.getElementById(\"$UName\"),$arr_user,\"$sel\");";
			$html.="</script>";
			
			return $html;
	    }
	    static function getFullUserByDepId($return,$iddep){
	    	global $db;
	    				
			$dep = array();
			$dep[] = 0;
			QLVBDHCommon::getAllDepChild(&$dep,$iddep);
	    	//lấy danh sách user của phòng
	    	$r = $db->query("
				SELECT
					$iddep as ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
				FROM				
					QTHT_USERS U 
					INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
					INNER JOIN QTHT_DEPARTMENTS DEP ON E.ID_DEP=DEP.ID_DEP
				WHERE
					DEP.ID_DEP in ($iddep,".implode(",",$dep).")
				ORDER BY
					U.ORDERS, E.LASTNAME
			");
			$user = $r->fetchAll();
			foreach($user as $item){
				$return[] = $item;
			}
			
			//Lấy các phòng con
			$r = $db->query("
				SELECT
					ID_DEP
				FROM				
					QTHT_DEPARTMENTS
				WHERE
					ID_DEP_PARENT in ($iddep)
			");
			$dep = $r->fetchAll();
			foreach($dep as $item){
				//QLVBDHCommon::getFullUserByDepId(&$return,$iddepparent,$item['ID_DEP']);
				QLVBDHCommon::getFullUserByDepId(&$return,$item['ID_DEP']);
			}
	    }
		static function getFullUserByDepIdWithNoGroup($return,$iddep){
	    	global $db;
	    				
			$dep = array();
			$dep[] = 0;
			QLVBDHCommon::getAllDepChild(&$dep,$iddep);
	    	//lấy danh sách user của phòng
	    	$r = $db->query("
				SELECT
					-1 as ID_G,$iddep as ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
				FROM				
					QTHT_USERS U 
					INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
					INNER JOIN QTHT_DEPARTMENTS DEP ON E.ID_DEP=DEP.ID_DEP
				WHERE
					DEP.ID_DEP in ($iddep,".implode(",",$dep).")
				ORDER BY
					U.ORDERS, E.LASTNAME
			");
			$user = $r->fetchAll();
			foreach($user as $item){
				$return[] = $item;
			}
			
			//Lấy các phòng con
			$r = $db->query("
				SELECT
					ID_DEP
				FROM				
					QTHT_DEPARTMENTS
				WHERE
					ID_DEP_PARENT in ($iddep)
			");
			$dep = $r->fetchAll();
			foreach($dep as $item){
				//QLVBDHCommon::getFullUserByDepId(&$return,$iddepparent,$item['ID_DEP']);
				QLVBDHCommon::getFullUserByDepIdWithNoGroup(&$return,$item['ID_DEP']);
			}
	    }
	    static function getAllDepChild($return,$iddep){
	    	global $db;
	    	$r = $db->query("
				SELECT
					ID_DEP
				FROM				
					QTHT_DEPARTMENTS
				WHERE
					ID_DEP_PARENT in ($iddep)
			");
			$dep = $r->fetchAll();
			foreach($dep as $item){
				$return[] = $item["ID_DEP"];
				QLVBDHCommon::getAllDepChild(&$return,$item["ID_DEP"]);
			}
	    }
		static function writeSelectDepartmentUserWithSelAndAction($DName,$UName,$sel,$depparent,$action){
			global $db;
			
			$arr_user = 'ARR_' . $UName;
			$department = array(); 
			QLVBDHCommon::GetTree(&$department,"QTHT_DEPARTMENTS","ID_DEP","ID_DEP_PARENT",$depparent,1);
			$iddep = implode(",",QLVBDHCommon::getDepList($department,$depparent));
			/*
			$r = $db->query("
				SELECT
					DEP.ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
				FROM				
					QTHT_USERS U 
					INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
					INNER JOIN QTHT_DEPARTMENTS DEP ON E.ID_DEP=DEP.ID_DEP
				".($depparent>1?"WHERE
					DEP.ID_DEP in ($iddep)":"")."
				ORDER BY
					U.ORDERS, E.LASTNAME
			");
			$user = $r->fetchAll();
			$r->closeCursor();
			*/
			$user = Array();
			QLVBDHCommon::getFullUserByDepId(&$user,$depparent,$depparent);
			
			$html.="    	<select name=$DName id=$DName onchange='FillComboByComboWithSel(this,document.getElementById(\"$UName\"),$arr_user,$sel);'>";
			$html.="        	<option value=1>--Chọn phòng--</option>";
			for($i=0;$i<count($department);$i++){
				$html.="        <option value=".$department[$i]["ID_DEP"].">".str_repeat("--",$department[$i]['LEVEL']).$department[$i]["NAME"]."</option>";
			}
			$html.="        </select>";
			
			$html.="<select name=$UName id=$UName onchange='$action'></select>";
			
			$html.="<script>";
			$html.="	var $arr_user = new Array();";
			for($i=0;$i<count($user);$i++)
				$html.=$arr_user."[".$i."] = new Array('".$user[$i]['ID_DEP']."','".$user[$i]['ID_U']."','".$user[$i]['NAME']."');";
			$html.="	FillComboByComboWithSel(document.getElementById(\"$DName\"),document.getElementById(\"$UName\"),$arr_user,\"$sel\");";
			$html.="</script>";
			
			return $html;
	    }
		/**
		 * Tạo select user từ select department
		 * 
		 * @param int $DName tên combobox chứa danh sách department
		 * @param int $UName tên combobox chứa danh sách user thuộc department trên
		 * @return html code
		 */
		static function writeMultiSelectDepartmentUser($DName,$UName){
			global $db;
			
			$arr_user = 'ARR_' . $UName;
			$department = array(); 
			QLVBDHCommon::GetTree(&$department,"QTHT_DEPARTMENTS","ID_DEP","ID_DEP_PARENT",1,1);
			$r = $db->query("
				SELECT
					ID_G,NAME
				FROM				
					QTHT_GROUPS			
			");
			$group = $r->fetchAll();
			$r->closeCursor();
			$r = $db->query("
				SELECT
					G.ID_G,DEP.ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME, U.ORDERS, E.LASTNAME
				FROM				
					QTHT_USERS U 
					INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
					INNER JOIN QTHT_DEPARTMENTS DEP ON E.ID_DEP=DEP.ID_DEP
					INNER JOIN FK_USERS_GROUPS G ON G.ID_U=U.ID_U
				UNION ALL

				SELECT
					G.ID_G,-1 as ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME, U.ORDERS, E.LASTNAME
				FROM				
					QTHT_USERS U 
					INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
					INNER JOIN FK_USERS_GROUPS G ON G.ID_U=U.ID_U
				UNION ALL
				SELECT
					-1 as ID_G,-1 as ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME, U.ORDERS, E.LASTNAME
				FROM				
					QTHT_USERS U 
					INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
				ORDER BY ORDERS, LASTNAME, NAME
			");
			$user = $r->fetchAll();
			$r->closeCursor();
			
			QLVBDHCommon::getFullUserByDepIdWithNoGroup(&$user,1);
			
			$html.="
				<table>
					<tr><td style='width:130px;'><b>Nhóm</b></td><td>
			";
			$html.="<select name=G$DName id=G$DName onchange='FillComboBy2Combo(this,document.getElementById(\"$DName\"),document.getElementById(\"$UName\"),$arr_user,arr_user_temp);'>";
			$html.="        	<option value=-1>--Chọn nhóm--</option>";
			for($i=0;$i<count($group);$i++){
				$html.="        <option value=".$group[$i]["ID_G"].">".$group[$i]["NAME"]."</option>";
			}
			$html.="        </select>";
			$html.="</td></tr>
					<tr><td style='width:130px;'><b>Phòng</b></td><td>
			";
			$html.="<select name=$DName id=$DName onchange='FillComboBy2Combo(document.getElementById(\"G$DName\"),this,document.getElementById(\"$UName\"),$arr_user,arr_user_temp);'>";
			$html.="        	<option value=-1>--Chọn phòng--</option>";
			for($i=0;$i<count($department);$i++){
				$html.="        <option value=".$department[$i]["ID_DEP"].">".str_repeat("--",$department[$i]['LEVEL']).$department[$i]["NAME"]."</option>";
			}
			$html.="        </select>";
			$html.="</td></tr>
					<tr><td style='width:130px;'><b>Người</b></td><td>
			";
			$html.="<select name=$UName id=$UName size=5 multiple ondblclick=\"if(typeof(InsertIntoArr)=='function')eval('InsertIntoArr()');\"></select>";
			$html.="</td></tr>
				</table>
			";
			
			$html.="<script>";
			$html.="	var $arr_user = new Array();";
			for($i=0;$i<count($user);$i++)
				$html.=$arr_user."[".$i."] = new Array('".$user[$i]['ID_G']."','".$user[$i]['ID_DEP']."','".$user[$i]['ID_U']."','".$user[$i]['NAME']."');";
			$html.="	FillComboBy2Combo(document.getElementById(\"G$DName\"),document.getElementById(\"$DName\"),document.getElementById(\"$UName\"),$arr_user,null);";
			$html.="</script>";
			
			return $html;
	    }
	    
	    /**
		 * Tạo select user từ select department
		 * 
		 * @param int $DName tên combobox chứa danh sách department
		 * @param int $UName tên combobox chứa danh sách user thuộc department trên
		 * @return html code
		 */
		static function writeSelectDepartment($DName,$UName){
			global $db;
			
			$arr_user = 'ARR_' . $UName;
			$department=array();
			QLVBDHCommon::GetTree(&$department,"QTHT_DEPARTMENTS","ID_DEP","ID_DEP_PARENT",1,1);	
			$r = $db->query("
				SELECT
					DEP.ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
				FROM				
					QTHT_USERS U 
					INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
					INNER JOIN QTHT_DEPARTMENTS DEP ON E.ID_DEP=DEP.ID_DEP
				ORDER BY
					U.ORDERS, E.LASTNAME
			");
			$user = $r->fetchAll();
			$r->closeCursor();
			
			$html.="    	<select name=$DName id=$DName onchange='FillComboByCombo(this,document.getElementById(\"$UName\"),$arr_user);'>";
			$html.="        	<option value=0>--Chọn phòng--</option>";
			for($i=0;$i<count($department);$i++){
				$html.="        <option value=".$department[$i]["ID_DEP"].">".str_repeat("--",$department[$i]["LEVEL"]).($department[$i]["LEVEL"]>1?"-> ":"").$department[$i]["NAME"]."</option>";
			}
			$html.="        </select>";
			
			$html.="<script>";
			$html.="	var $arr_user = new Array();";
			for($i=0;$i<count($user);$i++)
				$html.=$arr_user."[".$i."] = new Array('".$user[$i]['ID_DEP']."','".$user[$i]['ID_U']."','".$user[$i]['NAME']."');";
			$html.="</script>";
			return $html;
	    }
	    static function TabHscv($num,$loaihscv){
	    	if($loaihscv>=3)$loaihscv=3;
	    	$config = Zend_Registry::get('config');
	    	eval('$tabhscv = $config->HSCV_'.$loaihscv.';');
	    	$tabhscv = explode(",",$tabhscv);
	    	return $tabhscv[$num-1];
	    }
	    static function doDateStandard2Viet($standar){
	    	//return string time vietnamese (d/m/y h:m:s)
	    	$date_o = strtotime($standar);
	    	return date('h:m',$date_o). ' ngày ' . date('d/m/y',$date_o);
	    }
	    static function doDateViet2Standard(string $vietdate){
	    	//return standard time (y-m-d h:m:s)
	    	$ngay_tra = trim($ngay_tra);
			$arr = explode('/',$ngay_tra);
			$ngay_tra = date('y-m-d',mktime(null,null,null,$arr[1],$arr[0],$arr[2]));
	    }
	    static function getYear(){
	    	$year = new Zend_Session_Namespace('year');
	    	if(!isset($year->year)){
	    		$d=getdate();
	    		$year->year = $d['year'];
	    	}
	    	return $year->year;
	    }
	    static function MysqlDateToVnDate($mysqldate){
	    	if($mysqldate!=""){
	    	$d = explode(" ",$mysqldate);
	    	$d = explode("-",$d[0]);
	    	return (int)$d[2]."/".(int)$d[1]."/".(int)$d[0];
	    	}
	    }
		 static function MysqlDateToVnDateNoneZero($mysqldate){
	    	if($mysqldate!=""){
	    	$d = explode(" ",$mysqldate);
	    	$d = explode("-",$d[0]);
	    	return htmlspecialchars($d[2]."/".$d[1]."/".$d[0]);
	    	}
	    }
	    /**
	     * Đếm ngày bị trễ từ ngày đến ngày hiện tại
	     * trả về: gio bị trễ
	     * Ngày làm 8 giờ
	     * Không tính thứ 7 chủ nhật
	     * @param $ngay int
	     * @param $arrngaynghilam lấy từ database (UTC date)
	     */
		static function countdate($ngay,$arrngaynghilam){
			$tre = 0;
			$curdate = time();
			if($ngay>$curdate){
				return 0;
			}
			//tao nagy bat dau
			$begindate = getdate($ngay);
			$begindate = strtotime($begindate['year']."-".$begindate['mon']."-".$begindate['mday']." 00:00:00");
			$enddate = getdate($curdate);
			$enddate = strtotime($enddate['year']."-".$enddate['mon']."-".$enddate['mday']." 00:00:00");
			$isbegin = true;
			while(true){
				//chekc ngay nghi
				if(BussinessDateModel::IsNonWorkingDate(getdate($begindate))){

				}else{
					$hour = date("H",$begindate);
					if($hour>=8 && $hour<12){
						$tre++;
					}else if($hour>=13 && $hour<=17){
						$tre++;
					}
				}
				$begindate += 3600;
				if($begindate>$enddate)break;
			}
			return $tre;
		}
		/**
		 * Tra ve so gio bi tre (1 ngay co 8 gio)
		 */
		static function getTreHan($ngay,$hanxuly){
			if($hanxuly==0)return 0;
			$ngay = strtotime($ngay);
			$freedate = new Zend_Session_Namespace('freedate');
			$free = $freedate->free;
			$delay = QLVBDHCommon::countdate($ngay,$free);
			return ($delay)-($hanxuly*8);
		}
		static function getFreeDate(){
			global $db;
			$r = $db->query("SELECT * FROM GEN_LICHNGHILAM");
			$data = $r->fetchAll();
			$result = array();
			foreach($data as $row){
				if($row['ISRANGE']==1){
					$result[] = array(strtotime($row['TUNGAY']." 00:00:01"),strtotime($row['DENNGAY']." 23:59:59"));
				}
			}
			return $result;
		}
		static function addDate($date,$value){
			$inc = 0;
			//Đếm ngày từ ngày đến ngày hiện tại
			$value = $value*8;
			while(true){
				$date += 3600;
				$nocount = 1;
				if(BussinessDateModel::IsNonWorkingDate(getdate($date))){
					$nocount=0;
				}
				$hour = date("H",$date);
				if($hour>=8 && $hour<12){
					$inc += $nocount;
				}else if($hour>=13 && $hour<=17){
					$inc += $nocount;
				}				
				if($inc>=$value)break;
			}
			return $date;
			
		}
		static function trehantostr($tre,$ngay,$hanxuly){
			if($tre>0){
				$r = " <font color=red><i>Trễ ".(floor($tre/8)>0?floor($tre/8)." ngày ":"").($tre%8>0?($tre%8)." giờ":"")."</i></font>";
			}else if($tre<0){
				/*
				$ngay = strtotime($ngay);
				$ngay = QLVBDHCommon::addDate($ngay,$hanxuly);
				$ngay = date("d/m/Y H:i:s",$ngay);
				$r = " <font color=blue><i>(Gần tới hạn - ".$ngay.")</i></font>";
				*/
				$tre = $tre * -1;
				$r = " <font color=blue><i>Còn ".(floor($tre/8)>0?floor($tre/8)." ngày ":"").($tre%8>0?($tre%8)." giờ":"")."</i></font>";
			}
			return $r;
		}
		static function trehantostrwithusername($tre,$ngay,$hanxuly,$username){
			if($tre>0){
				$r = " <font color=red><i>(Trễ ".(floor($tre/8)>0?floor($tre/8)." ngày ":"").($tre%8>0?($tre%8)." giờ":"")." - ".$username.")</i></font>";
			}else if($tre>-8){
				$ngay = strtotime($ngay);
				$ngay = QLVBDHCommon::addDate($ngay,$hanxuly);
				$ngay = date("d/m/Y H:i:s",$ngay);
				$r = " <font color=blue><i>(Gần tới hạn - ".$ngay." - ".$username.")</i></font>";
			}
			return $r;
		}
		static function SendMessage($usend,$ureceive,$content,$link){
			global $db;
			global $url;
			$db->insert(QLVBDHCommon::Table("GEN_MESSAGE"),array("ID_U_SEND"=>$usend,"ID_U_RECEIVE"=>$ureceive,"NOIDUNG"=>$content,"LINK"=>$url.$link,"DATE_SEND"=>date("Y-m-d H:i:s")));
		}
		static function UpdateIndex($tablename,$data,$ido){
			global $db;
			$tablefulltext = $tablename."_FULLTEXT"."_".QLVBDHCommon::getYear();
			$tablefulltextdata = $tablename."_FULLTEXT"."_DATA_".QLVBDHCommon::getYear();
			$keywords = preg_split( "/[\s,.!?]*\\\"([^\\\"]+)\\\"[\s,.!?]*|[\s,.!?]+/", $data, 0, PREG_SPLIT_DELIM_CAPTURE );
			$db->delete($tablefulltextdata,"ID_O=".$ido);
			foreach($keywords as $key){
				if($key!=""){
					//check xem key word co chua
					$sql = "select * from $tablefulltext where DATA = ?";
					$r = $db->query($sql,$key);
					if($r->rowCount()>0){
						$fulltext = $r->fetch();
						$db->insert($tablefulltextdata,array("ID_FT"=>$fulltext['ID_FT'],"ID_O"=>$ido));
					}else{
						$db->insert($tablefulltext,array("DATA"=>$key));
						$db->insert($tablefulltextdata,array("ID_FT"=>$db->lastInsertId($tablefulltext),"ID_O"=>$ido));
					}
				}
			}
		}
		static function MakeInString($data){
			$r = "";
			$keywords = preg_split( "/[\s,.!?]*\\\"([^\\\"]+)\\\"[\s,.!?]*|[\s,.!?]+/", $data, 0, PREG_SPLIT_DELIM_CAPTURE );
			foreach($keywords as $key){
				if($key!=""){
					if($key==""){
						$r .= "'".$key."'";
					}else{
						$r .= ",'".$key."'";
					}
				}
			}
			return $r;
		}

		static function get_string_between($string, $start, $end){
				$string = " ".$string;
				$ini = strpos($string,$start);
				if ($ini == 0) return "";
				$ini += strlen($start);   
				$len = strpos($string,$end,$ini) - $ini;
				return substr($string,$ini,$len);
		}
		static function InsertHSMCService($masohoso,$tentochuccanhan,$tenhoso,$trangthai,$ghichu,$dienthoai){
			global $db;
			$db->insert("SERVICES_MOTCUA_TRACUU",array("MASOHOSO"=>$masohoso,"TENTOCHUCCANHAN"=>$tentochuccanhan,"TENHOSO"=>$tenhoso,"TRANGTHAI"=>$trangthai,"GHICHU"=>$ghichu,"DIENTHOAI"=>$dienthoai));
		}
		function createTextHanXuLy($value){
			if($value<1 && $value>0){
				$value = $value * 8;
				return "" . round($value,1) . " giờ";
			}else if($value>=1){
				return "" . round($value,1) . " ngày";
			}else{
				return "";
			}
		}
		function createInputHanxuly($id,$name,$value,$onchange){
			$type = $value<1?8:1;
			if($type==8){
				$value=round($value*8,1);
			}else{
				$value = round($value,1);
			}
			$html="";
			$html  = "<input id='".$id."' type=textbox onkeypress='return isNumberKey(event)' name='temp_".$name."' size=3 maxlength=3 value='".$value."' onchange='document.getElementById(\"real_".$id."\").value=this.value/document.frm.type_real_".$id.".value;".$onchange."'>";
			$html .= "<input style='display:none' type=text id='real_".$id."' name='".$name."' value='".($value/$type)."'>";
			$html .= "<input ".($type==1?"checked":"")." type=radio name='type_".$id."' id='type_1_".$id."' onclick=\"document.frm.type_real_".$id.".value=1;document.getElementById('real_".$id."').value=document.getElementById('".$id."').value/this.value;".$onchange."\" value=1>ngày ";
			$html .= "<input ".($type==8?"checked":"")." type=radio name='type_".$id."' id='type_8_".$id."' onclick=\"document.frm.type_real_".$id.".value=8;document.getElementById('real_".$id."').value=document.getElementById('".$id."').value/this.value;".$onchange."\" value=8>giờ";
			$html .= "<input style='display:none' type=text id='type_real_".$id."' name='type_real_".$id."' value='".$type."'>";
			return $html;
			
		}
	}