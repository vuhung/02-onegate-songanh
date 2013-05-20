 <?php
    class Common_ThongkeInfo{
         /***
             TYPE: loai cap nhat
             THANG_TIEPNHAN
             THANG_DUKIENTRA
             THANG_TRAHOSO
             NAM_TIEPNHAN
             NAM_DUKIENTRA
             NAM_TRAHOSO
             IS_DAGIAIQUYET
             IS_BOSUNG
             TRE
        **/


        function update($params){
           
            $id = $params["ID_MOTCUA"];
            switch($params["TYPE"]){
						
                case "TIEPNHAN":					
                    $array_data = array(
                        "THANG_TIEPNHAN"=>(int)$params["THANG_TIEPNHAN"],
                        "THANG_DUKIENTRAHOSO"=>(int)$params["THANG_TIEPNHAN"],
                        "NAM_TIEPNHAN"=>(int)$params["NAM_TIEPNHAN"],
                        "NAM_TRAHOSO"=>(int)$params["NAM_DUKIENTRAHOSO"],
                    );
                break;
                case "KETTHUCXULY":
                     $array_data = array(
                        "THANG_TRAHOSO"=>(int)$params["THANG_TRAHOSO"],
                        "NAM_TRAHOSO"=>(int)$params["NAM_DUKIENTRAHOSO"],
                        "IS_DAGIAIQUYET"=>(int)$params["IS_BOSUNG"],
                    );
                break;
                case "CHOBOSUNG":
                     $array_data = array(
                        "THANG_TIEPNHAN"=>(int)$params["THANG_TIEPNHAN"],
                        "THANG_TRAHOSO"=>(int)$params["THANG_TRAHOSO"],
                        "THANG_DUKIENTRAHOSO"=>(int)$params["THANG_TIEPNHAN"],
                        "NAM_TIEPNHAN"=>(int)$params["NAM_TIEPNHAN"],
                        "NAM_DUKIENTRAHOSO"=>(int)$params["NAM_DUKIENTRAHOSO"],
                        "NAM_TRAHOSO"=>(int)$params["NAM_DUKIENTRAHOSO"],
                        "IS_DAGIAIQUYET"=>(int)$params["IS_BOSUNG"],
                        "IS_BOSUNG"=>(int)$params["IS_BOSUNG"],
                        "TRE"=>(int)$params["TRE"],
                    );
                break;
            }
            try{

                $db = Zend_Db_Table::getDefaultAdapter();
                $db = $db->update(QLVBDHCommon::Table("motcua_hoso"),$array_data,"ID_HOSO=".(int)$id);
            }catch(Exception $ex){
                echo $ex->__toString();
            }
        }

       static function updateThoigianconlai($id_hoso){

                 $db = Zend_Db_Table::getDefaultAdapter();
                 $stmmc =  $db->query(" select NHAN_NGAY , NHANLAI_NGAY , TRA_NGAY from ". QLVBDHCommon::Table("motcua_hoso")." mc where ID_HOSO=? ", (int)$id_hoso );
                 $mcinfo = $stmmc->fetch();
                 // tinh han tre

                $ngayconlai = (int)QLVBDHCommon::countdatesongayconlai(strtotime($mcinfo["NHANLAI_NGAY"]));
                $db->update(QLVBDHCommon::Table("motcua_hoso"),array("SONGAYCONLAI"=>$ngayconlai ),"ID_HOSO=".(int)$id_hoso);



        }
   };

   /*
 $array_data = array(
                        "THANG_TIEPNHAN"=>(int) $params["THANG_TIEPNHAN"],
                        "THANG_TRAHOSO"=>(int)$params["THANG_TRAHOSO"],
                        "THANG_DUKIENTRAHOSO"=> (int)$params["THANG_TIEPNHAN"],
                        "NAM_TIEPNHAN"=>(int)$params["NAM_TIEPNHAN"],
                        "NAM_DUKIENTRAHOSO"=>(int)$params["NAM_DUKIENTRAHOSO"],
                        "NAM_TRAHOSO"=>(int)$params["NAM_DUKIENTRAHOSO"],
                        "IS_DAGIAIQUYET"=>(int)$params["IS_BOSUNG"],
                        "IS_BOSUNG"=>(int)$params["IS_BOSUNG"],
                        "TRE"=>(int)$params["TRE"],
                    );
*/

?>
