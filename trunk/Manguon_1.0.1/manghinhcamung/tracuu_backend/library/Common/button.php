<?php
class QLVBDHButton {
	static $button = array();
	static function AddButton($name,$action,$class,$script){
		QLVBDHButton::$button[count(QLVBDHButton::$button)] = array($name,$action,$class,$script);
	}
	static function EnableAddNew($action,$label="Thêm mới"){
		QLVBDHButton::$button[count(QLVBDHButton::$button)] = array($label,$action,"AddNewButton","AddNewButtonClick();");
	}
	static function EnableDelete($action){
		QLVBDHButton::$button[count(QLVBDHButton::$button)] = array("Xoá",$action,"DeleteButton","DeleteButtonClick();");
	}
	static function EnableHelp($action){
		QLVBDHButton::$button[count(QLVBDHButton::$button)] = array("Trợ giúp",$action,"HelpButton","HelpButtonClick();");
	}
	static function EnableCheckAll($child){
		return "<input type=checkbox name=DELALL onclick=\"SelectAll(this,'".$child."')\">";
	}
	//Bottom
	static function EnableSave($action){
		QLVBDHButton::$button[count(QLVBDHButton::$button)] = array("Lưu",$action,"SaveButton","SaveButtonClick();");
	}
	static function EnableBack($action){
		QLVBDHButton::$button[count(QLVBDHButton::$button)] = array("Trở lại",$action,"BackButton","BackButtonClick();");
	}
	static function DrawButton(){
		$html = "";
		$html .= "<table class=\"toolbar\"><tr>";
		for($i=0;$i<count(QLVBDHButton::$button);$i++){
			$html.="<td class=\"button\" id=\"toolbar-".QLVBDHButton::$button[$i][2]."\">";
			if(QLVBDHButton::$button[$i][3]==""){
				$html.="<a href=\"".QLVBDHButton::$button[$i][1]."\" class=\"toolbar\"><span class=\"icon-".QLVBDHButton::$button[$i][2]."\" title=\"".QLVBDHButton::$button[$i][0]."\"></span>".QLVBDHButton::$button[$i][0]."</a>";
			}else{
				$html.="<a href=\"#\" onclick='".QLVBDHButton::$button[$i][3]."' class=\"toolbar\"><span class=\"icon-".QLVBDHButton::$button[$i][2]."\" title=\"".QLVBDHButton::$button[$i][0]."\"></span>".QLVBDHButton::$button[$i][0]."</a>";
			}
			$html.="</td>";
		}
		$html .= "</tr></table>";
		return $html;
	}
}
?>
