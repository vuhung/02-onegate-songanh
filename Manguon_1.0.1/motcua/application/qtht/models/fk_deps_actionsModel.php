<?php
/**
 * ClassModel
 *  
 * @author hieuvt
 * @version 1.0
 */
require_once 'Zend/Db/Table/Abstract.php';
require_once ('Zend/Db/Table.php');

class fk_deps_actionsModel extends Zend_Db_Table_Abstract {
    /**
     * The default table name 
     */
    var $_name = 'fk_deps_actions';
	public function SelectAllByIDDEP($idd){
		$r = $this->getDefaultAdapter()->query("
			SELECT
				t.SEL,ac.DESCRIPTION as NAME,m.ID_MOD,m.NAME as MODNAME,ac.ID_ACT,gm.NAME as GMODNAME,gm.ID_GMOD
			FROM
			(SELECT
			    a.ID_ACT,
			    (a.ID_DEP=u.ID_DEP) as SEL
			FROM
				(
					SELECT
						ua.ID_DEP,ac.NAME,ac.ID_ACT
					FROM
						QTHT_ACTIONS ac
						left join (
							SELECT 
								ID_DEP,ID_ACT
							from
								`fk_deps_actions`
							where ID_DEP=?) ua on ac.ID_ACT = ua.ID_ACT
				) a
				left join (
					SELECT
						ID_DEP
					FROM
						`qtht_departments`
					WHERE
						ID_DEP=?
			) u on a.ID_DEP = u.ID_DEP) t
			inner join QTHT_ACTIONS ac on t.ID_ACT = ac.ID_ACT
			inner join QTHT_MODULES m on m.ID_MOD = ac.ID_MOD
			inner join QTHT_GROUPMODULES gm on gm.ID_GMOD = m.ID_GMOD
			WHERE
				ac.ISPUBLIC = 0 or ac.ISPUBLIC is null
			ORDER BY
				gm.ID_GMOD,m.ID_MOD,m.NAME,ac.NAME
		",array($idd,$idd));
		return $r->fetchAll();	
	}
}
