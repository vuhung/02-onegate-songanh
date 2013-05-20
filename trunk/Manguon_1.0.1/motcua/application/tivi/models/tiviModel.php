<?php

class tiviModel extends Zend_Db_Table_Abstract {

    public $_name = '';
    public function __construct() {

    }
    /**
     *  Lấy lịch công tác cơ quan
     * @return <array>
     */
    public function getAllLCT() {

        $db = Zend_Db_Table::getDefaultAdapter();
        $year = QLVBDHCommon::getYear();
        $date_str = date('Y-m-d');
        $t = strtotime(date('Y-m-d'));
        $d = getdate($t);

        if ($d['wday'] == 0)
            $d['wday'] = 7;
        $begindate = $t - ($d['wday'] - 1) * 86400;
        $bdate_str = date("Y-m-d", $begindate);
        $edate_str = date("Y-m-d", $begindate + 86400 * 7);

        $str_old = "";
        $month = date('m');
        $sql = "
                select * from lct2_corporation_$year
                where NGAY <= '$edate_str' AND NGAY >= '$bdate_str'
                ORDER BY NGAY
            ";
        $re = $db->query($sql);
        $ret = array();
        while ($row = $re->fetch()) {
            array_push($ret, $row);
        }
        return ($ret);
    }

    public function getKetQuaXLHS() {

        $db = Zend_Db_Table::getDefaultAdapter();
        $year = QLVBDHCommon::getYear();
        $date_str = date('Y-m-d');

        $str_old = "";
        $month = date('m');
        // if ($month == 1 || $month == 2)
            // $str_old = "
            // UNION
            // SELECT  motcua.TRICHYEU , motcua.NHANLAI_NGAY, motcua.TRA_NGAY , motcua.LYDOKHONGXULY as LYDOTRE , pi.IS_FINISH , dep.NAME as DEPNAME from motcua_hoso_" . ($year - 1) . " motcua
                    // inner join hscv_hosocongviec_" . ($year - 1) . " hscv on motcua.ID_HSCV = hscv.ID_HSCV
                    // inner join wf_processitems_" . ($year - 1) . " pi on motcua.ID_HSCV = pi.ID_O

                    // left join qtht_users u on pi.ID_U = u.ID_U
                    // left join qtht_employees emp on  u.ID_EMP = emp.ID_EMP
                    // left join qtht_departments dep on emp.ID_DEP = dep.ID_DEP
                    // where TRA_NGAY is  NULL and ( hscv.IS_THEODOI = 0 or IS_THEODOI is NULL)
                // ";

        $sql = "SELECT  motcua.TRICHYEU , motcua.NHANLAI_NGAY, motcua.TRA_NGAY , motcua.LYDOKHONGXULY as LYDOTRE , pi.IS_FINISH , dep.NAME as DEPNAME from motcua_hoso_$year motcua
                    inner join hscv_hosocongviec_$year hscv on motcua.ID_HSCV = hscv.ID_HSCV
                    inner join wf_processitems_$year pi on motcua.ID_HSCV = pi.ID_O

                    left join qtht_users u on pi.ID_U = u.ID_U
                    left join qtht_employees emp on  u.ID_EMP = emp.ID_EMP
                    left join qtht_departments dep on emp.ID_DEP = dep.ID_DEP
                    where TRA_NGAY is  NULL and ( hscv.IS_THEODOI = 0 or IS_THEODOI is NULL)
            $str_old

            ORDER BY NHANLAI_NGAY
                        ";
        
        $re = $db->query($sql);
        $ret = array();
        while ($row = $re->fetch()) {
            if ($row->IS_FINISH) {
                $row->TRANGTHAI = "Đã giải quyết";
            } else {
                $row->TRANGTHAI = "Đang xử lý tại " . $row->DEPNAME;
            }
            array_push($ret, $row);
        }
        return ($ret);
    }

}
