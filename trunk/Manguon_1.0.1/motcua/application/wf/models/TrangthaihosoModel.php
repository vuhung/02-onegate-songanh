<?php
class TrangthaihosoModel extends Zend_Db_Table_Abstract {

	var $_name = 'wf_trangthaihoso';
	function __contruct(){

	}

	function getAllTrangthai(){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select * from wf_trangthaihoso
		";
		$qr = $db->query($sql);
		return $qr->fetchAll();
	}


	function getAllTrangthaiWithGroupName(){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select tths.*, CONVERT ( GROUP_CONCAT(g.NAME) USING utf8)  as G_NAME from wf_trangthaihoso  tths
			left join wf_trangthaihoso_group fk on  tths.ID_TTHS = fk.ID_TTHS
			left join qtht_groups g on fk.ID_G = g.ID_G
			group by tths.ID_TTHS
		";
		$qr = $db->query($sql);
		return $qr->fetchAll();
	}

	function getAllTrangthaiWithActivity($id_a){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select tths.*, g.NAME as G_NAME , fk.ID_TTHS  as IS_ON  from wf_trangthaihoso  tths
			inner join wf_trangthaihoso_group fk_g on tths.ID_TTHS = fk_g.ID_TTHS
			inner join qtht_groups g on  fk_g.ID_G = g.ID_G
			left join ( select * from wf_activity_fktth where ID_A = ?  ) fk on tths.ID_TTHS = fk.ID_TTHS


		";
		$qr = $db->query($sql,array($id_a));
		return $qr->fetchAll();
	}

	function getGroupwithChecked($id_tths){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select g.*, fk.ID_G as IS_ON from qtht_groups g
			left join ( select ID_G from wf_trangthaihoso_group where ID_TTHS = ?) fk on g.ID_G = fk.ID_G

		";
		$qr = $db->query($sql,array($id_tths));
		return $qr->fetchAll();
	}

}