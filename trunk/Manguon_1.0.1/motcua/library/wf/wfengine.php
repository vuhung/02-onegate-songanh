<?php
/**
 * @name WF API + ENGINE
 * Các function dành riêng cho WF. Mọi function đều là static.
 *
 * @author Nếu các đoạn code dưới đây chạy tốt thì nó được viết bởi Nguyennd. Nếu không: không biết ai viết mà tệ quá.
 * @deprecated 23/09/2009
 * @filesource wfengine.php
 * @access static
 */
class WFEngine{
	static $WFTYPE_USER = 1;
	static $WFTYPE_GROUP = 2;
	static $WFTYPE_DEP = 3;
	/**
	 * Tạo process item.
	 * Sau khi khởi tạo một process item thì hệ thống cũng tự động gọi hàm sendnextuser
	 * Yêu cầu: Process phải có 1 transition là isfirst và 1 activity hành động (thêm mới, add)
	 * Cách sử dụng:
	 * 		$arr = CreateProcess("TNXLVBD",1234,"Cong việc a",$user_id);
	 * @param string $process_alias
	 * @param number $object_id
	 * @param string $name
	 * @return int
	 */
	static function CreateProcess($process_alias,$object_id,$name,$user_create,$user_receive,$name1,$hanxuly){
		try{
			global $db;
			//Get and check process alias
			$r = $db->query("
				select
					p.ID_P,p.ID_C,c.ALIAS
				from
					wf_processes p inner join wf_classes c on p.ID_C=c.ID_C
				where p.ALIAS=?",array($process_alias));
			if($r->rowCount()==0){
				return -1001;
			}
			$process = $r->fetch();
			$r->closeCursor();
			//Check name
			if(trim($name)=="" || strlen($name)>1024){
				return -1004;
			}
			//Lấy AID đầu tiên của process
			$r = $db->query("select ID_P,ID_T,ID_A_BEGIN,ID_A_END from WF_TRANSITIONS where ISFIRST=1 and ID_P=?",array($process['ID_P']));
			$activity = $r->fetch();
			$r->closeCursor();
			//kiem tra quyen truy cap BEGIN ACTIVITY doi voi nguoi gui
			if(WFEngine::HaveAccessableActivity($activity["ID_A_BEGIN"],$user_create)){
				//insert vào process item
				if($user_receive==0 || $user_receive==""){
					//Kiem tra quyen truy cap END ACTIVITY với người gui
					if(WFEngine::HaveAccessableActivity($activity["ID_A_END"],$user_create)){
						$r = $db->insert(QLVBDHCommon::Table("wf_processitems"),array("NAME"=>$name,"ID_O"=>$object_id,"ID_A"=>$activity["ID_A_END"],"ID_P"=>$process["ID_P"],"ID_U"=>$user_create,"LASTCHANGE"=>date("Y-m-d H:i:s")));
						$r = $db->lastInsertId();
						//ghi Log tao process
						WFEngine::WriteLog(
							$r,
							$activity["ID_T"],
							$user_create,
							0,
							$activity["ID_A_BEGIN"],
							$activity["ID_A_END"],
							$activity["ID_P"],
							0,
							0
						);
					}else{
						return -1003;
					}
				}else{
				
					//Kiem tra quyen truy cap voi ID_A_END cuar nguoi nhan
					if(WFEngine::HaveAccessableActivity($activity["ID_A_END"],$user_receive)){
						$r = $db->insert(QLVBDHCommon::Table("wf_processitems"),array("NAME"=>$name,"ID_O"=>$object_id,"ID_A"=>$activity["ID_A_END"],"ID_P"=>$process["ID_P"],"ID_U"=>$user_receive,"LASTCHANGE"=>date("Y-m-d H:i:s")));
						$r = $db->lastInsertId();
						//ghi Log tao process
						WFEngine::WriteLog(
							$r,
							$activity["ID_T"],
							$user_create,
							0,
							$activity["ID_A_BEGIN"],
							$activity["ID_A_END"],
							$activity["ID_P"],
							0,
							0,
							"Thêm mới",
							0
						);
						WFEngine::WriteLog(
							$r,
							$activity["ID_T"],
							$user_create,
							$user_receive,
							$activity["ID_A_BEGIN"],
							$activity["ID_A_END"],
							$activity["ID_P"],
							0,
							0,
							$name1,
							$hanxuly
						);
					}else{
						return -1003;
					}
				}
			}else{
				return -1002;
			}
			return $r;
		}catch(Exception $ex){
			echo $ex->__toString();
			return -0001;
		}
	}
	/**
	 * Tạo process item.
	 * Sau khi khởi tạo một process item thì hệ thống cũng tự động gọi hàm sendnextuser
	 * Yêu cầu: Process phải có 1 transition là isfirst và 1 activity hành động (thêm mới, add)
	 * Cách sử dụng:
	 * 		$arr = CreateProcess("TNXLVBD",1234,"Cong việc a",$user_id);
	 * @param string $process_alias
	 * @param number $object_id
	 * @param string $name
	 * @return int
	 */
	static function CreateProcess2($process_alias,$object_id,$name,$user_create,$id_receive,$type){
		try{
			global $db;
			//Get and check process alias
			$r = $db->query("
				select
					p.ID_P,p.ID_C,c.ALIAS
				from
					wf_processes p inner join wf_classes c on p.ID_C=c.ID_C
				where p.ALIAS=?",array($process_alias));
			if($r->rowCount()==0){
				return -1001;
			}
			$process = $r->fetch();
			$r->closeCursor();
			//Check name
			if(trim($name)=="" || strlen($name)>128){
				return -1004;
			}
			//Lấy AID đầu tiên của process
			$r = $db->query("select ID_P,ID_T,ID_A_BEGIN,ID_A_END from WF_TRANSITIONS where ISFIRST=1 and ID_P=?",array($process['ID_P']));
			$activity = $r->fetch();
			$r->closeCursor();
			//kiem tra quyen truy cap BEGIN ACTIVITY doi voi nguoi gui
			if(WFEngine::HaveAccessableActivity($activity["ID_A_BEGIN"],$user_create)){
				//Kiem tra quyen truy cap voi ID_A_END cua nguoi nhan
				$ok = false;
				if($type==WFEngine::$WFTYPE_USER){
					$ok = WFEngine::HaveAccessableActivity($activity["ID_A_END"],$id_receive);
				}else if($type==WFEngine::$WFTYPE_DEP){
					$ok = WFEngine::HaveAccessableActivityDep($activity["ID_A_END"],$id_receive);
				}else if($type==WFEngine::$WFTYPE_GROUP){
					$ok = WFEngine::HaveAccessableActivityGroup($activity["ID_A_END"],$id_receive);
				}
				if($ok){
					$r = $db->insert(
						QLVBDHCommon::Table("wf_processitems"),
						array("NAME"=>$name,
						"ID_O"=>$object_id,
						"ID_A"=>$activity["ID_A_END"],
						"ID_P"=>$process["ID_P"],
						"ID_U"=>$type==WFEngine::$WFTYPE_USER?$id_receive:0,
						"ID_G"=>$type==WFEngine::$WFTYPE_GROUP?$id_receive:0,
						"ID_DEP"=>$type==WFEngine::$WFTYPE_DEP?$id_receive:0,
						"LASTCHANGE"=>date("Y-m-d H:i:s")));
					$r = $db->lastInsertId();
					//ghi Log tao process
					WFEngine::WriteLog(
						$r,
						$activity["ID_T"],
						$user_create,
						0,
						$activity["ID_A_BEGIN"],
						$activity["ID_A_END"],
						$activity["ID_P"],
						0,
						0,
						0,
						0
					);
					WFEngine::WriteLog(
						$r,
						$activity["ID_T"],
						$user_create,
						$type==WFEngine::$WFTYPE_USER?$id_receive:0,
						$activity["ID_A_BEGIN"],
						$activity["ID_A_END"],
						$activity["ID_P"],
						$type==WFEngine::$WFTYPE_GROUP?$id_receive:0,
						$type==WFEngine::$WFTYPE_DEP?$id_receive:0,
						0,
						0
					);
				}else{
					return -1003;
				}
			}else{
				return -1002;
			}
			return $r;
		}catch(Exception $ex){
			$ex->__toString();
			return -0001;
		}
	}
	/**
	 * Lấy danh sách các Transition từ 1 process item id
	 *
	 * @param int $process_item_id
	 * @param int $user_id
	 * @return array
	 */
	static function GetNextTransitions($process_item_id,$user_id){
		global $db;
		$r=$db->query("
		SELECT TR.ID_T,TR.NAME,TP.LINK from
			WF_TRANSITIONS TR
			inner join (
				SELECT AC.ID_A FROM
					FK_USERS_GROUPS G
					INNER JOIN QTHT_USERS U ON U.ID_U = G.ID_U
					INNER JOIN WF_ACTIVITYACCESSES AC ON G.ID_G = AC.ID_G
				WHERE U.ID_U = ?
				UNION
				SELECT AC.ID_A FROM
					QTHT_DEPARTMENTS DEP
					INNER JOIN QTHT_EMPLOYEES EMP ON EMP.ID_DEP = DEP.ID_DEP
					INNER JOIN QTHT_USERS U ON U.ID_EMP = EMP.ID_EMP
					INNER JOIN WF_ACTIVITYACCESSES AC ON DEP.ID_DEP = AC.ID_DEP
				WHERE U.ID_U = ?
				UNION
				SELECT AC.ID_A FROM
					WF_ACTIVITYACCESSES AC
				WHERE AC.ID_U = ?
			) T on TR.ID_A_BEGIN=T.ID_A
			inner join wf_transitionpools TP on tp.ID_TP=TR.ID_TP
			inner join ".QLVBDHCommon::Table("wf_processitems")." PR on pr.ID_A=tr.ID_A_BEGIN
		WHERE
		     PR.ID_PI=?
		",array($user_id,$user_id,$user_id,$process_item_id));
		return $r->fetchAll();
	}
	static function GetNextTransitionsByTransition($transition_id){
		global $db;
		$r=$db->query("
			SELECT
				tr1.*
			FROM
				WF_TRANSITIONS TR
				INNER JOIN WF_TRANSITIONS TR1 on tr.`ID_A_END` = tr1.`ID_A_BEGIN`
			WHERE
				tr.`ID_T` = ?
			",
			array($transition_id)
		);
		return $r->fetch();
	}
	/**
	 * Kiểm tra quyền truy cập đến một process item của một user
	 * Ngoài ra nó có có chức năng check xem process item có tồn tại không
	 *
	 * @param int $process_item_id
	 * @param int $user_id
	 * @return bool true:có quyền
	 * 				flase: không có quyền
	 */
	static function HaveAccessableProcessItem($process_item_id,$user_id){
		global $db;
		$r = $db->query("
			SELECT U.ID_U FROM
				QTHT_DEPARTMENTS DEP
				INNER JOIN QTHT_EMPLOYEES EMP ON EMP.ID_DEP = DEP.ID_DEP
				INNER JOIN QTHT_USERS U ON U.ID_EMP = EMP.ID_EMP
				INNER JOIN WF_ACTIVITYACCESSES AC ON DEP.ID_DEP = AC.ID_DEP
				INNER JOIN ".QLVBDHCommon::Table("WF_PROCESSITEMS")." P ON P.ID_A = AC.ID_A
			WHERE U.ID_U = ? AND P.ID_PI=?
			UNION
			SELECT U.ID_U FROM
				FK_USERS_GROUPS G
				INNER JOIN QTHT_USERS U ON U.ID_U = G.ID_U
				INNER JOIN WF_ACTIVITYACCESSES AC ON G.ID_G = AC.ID_G
				INNER JOIN ".QLVBDHCommon::Table("WF_PROCESSITEMS")." P ON P.ID_A = AC.ID_A
			WHERE U.ID_U = ? AND P.ID_PI=?
			UNION
			SELECT AC.ID_U FROM
				WF_ACTIVITYACCESSES AC
				INNER JOIN ".QLVBDHCommon::Table("WF_PROCESSITEMS")." P ON P.ID_A = AC.ID_A
			WHERE AC.ID_U = ? AND P.ID_PI=? AND P.ID_U=?
		",array($user_id,$process_item_id,$user_id,$process_item_id,$user_id,$process_item_id,$user_id));
		if($r->rowCount()==0){
			return false;
		}
		return true;
	}
	static function HaveAccessableProcess($process_id,$user_id){
		global $db;
		return true;
	}
	/**
	 * Kiểm tra quyền truy cập đến một activity của một user
	 * Ngoài ra còn sử dụng để biết 1 lần sendnextuser có hợp lệ phái người nhận ko.
	 *
	 * @param int $activity_id
	 * @param int $user_id
	 * @return bool true:có quyền
	 * 				flase: không có quyền
	 */
	static function HaveAccessableActivity($activity_id,$user_id){
		global $db;
		$r = $db->query("
			SELECT U.ID_U FROM
				FK_USERS_GROUPS G
				INNER JOIN QTHT_USERS U ON U.ID_U = G.ID_U
				INNER JOIN WF_ACTIVITYACCESSES AC ON G.ID_G = AC.ID_G
				WHERE U.ID_U=? AND AC.ID_A=?
			UNION
			SELECT U.ID_U FROM
				QTHT_DEPARTMENTS DEP
				INNER JOIN QTHT_EMPLOYEES EMP ON EMP.ID_DEP = DEP.ID_DEP
				INNER JOIN QTHT_USERS U ON U.ID_EMP = EMP.ID_EMP
				INNER JOIN WF_ACTIVITYACCESSES AC ON DEP.ID_DEP = AC.ID_DEP
				WHERE U.ID_U=? AND AC.ID_A=?
			UNION
			SELECT AC.ID_U FROM
				WF_ACTIVITYACCESSES AC
			WHERE AC.ID_U=? AND AC.ID_A=?
		",array($user_id,$activity_id,$user_id,$activity_id,$user_id,$activity_id));
		/*echo "
			SELECT U.ID_U FROM
				FK_USERS_GROUPS G
				INNER JOIN QTHT_USERS U ON U.ID_U = G.ID_U
				INNER JOIN WF_ACTIVITYACCESSES AC ON G.ID_G = AC.ID_G
				WHERE U.ID_U=$user_id AND AC.ID_A=$activity_id
			UNION
			SELECT U.ID_U FROM
				QTHT_DEPARTMENTS DEP
				INNER JOIN QTHT_EMPLOYEES EMP ON EMP.ID_DEP = DEP.ID_DEP
				INNER JOIN QTHT_USERS U ON U.ID_EMP = EMP.ID_EMP
				INNER JOIN WF_ACTIVITYACCESSES AC ON DEP.ID_DEP = AC.ID_DEP
				WHERE U.ID_U=$user_id AND AC.ID_A=$activity_id
			UNION
			SELECT AC.ID_U FROM
				WF_ACTIVITYACCESSES AC
			WHERE AC.ID_U=$user_id AND AC.ID_A=$activity_id
		";*/
		if($r->rowCount()==0){
			return false;
		}
		return true;
	}
	/**
	 * Kiểm tra quyền truy cập đến một activity của một user
	 * Ngoài ra còn sử dụng để biết 1 lần sendnextuser có hợp lệ phái người nhận ko.
	 *
	 * @param int $activity_id
	 * @param int $user_id
	 * @return bool true:có quyền
	 * 				flase: không có quyền
	 */
	static function HaveAccessableActivityGroup($activity_id,$group_id){
		global $db;
		$r = $db->query("
			SELECT ID_G FROM
				WF_ACTIVITYACCESSES
				WHERE ID_G=? AND ID_A=?
		",array($group_id,$activity_id));
		if($r->rowCount()==0){
			return false;
		}
		return true;
	}
	/**
	 * Kiểm tra quyền truy cập đến một activity của một user
	 * Ngoài ra còn sử dụng để biết 1 lần sendnextuser có hợp lệ phái người nhận ko.
	 *
	 * @param int $activity_id
	 * @param int $user_id
	 * @return bool true:có quyền
	 * 				flase: không có quyền
	 */
	static function HaveAccessableActivityDep($activity_id,$dep_id){
		global $db;
		$r = $db->query("
			SELECT ID_DEP FROM
				WF_ACTIVITYACCESSES
				WHERE ID_DEP=? AND ID_A=?
		",array($dep_id,$activity_id));
		if($r->rowCount()==0){
			return false;
		}
		return true;
	}
	/**
	 * Kiểm tra xem từ 1 process item khi send có đúng theo quy trình không
	 *
	 * @param int $process_item_id
	 * @param int $next_transition_id
	 */
	static function HaveSendAble($process_item_id,$next_transition_id){
		global $db;
		$r = $db->query("
			SELECT* FROM
				".QLVBDHCommon::Table("wf_processitems")." PR
				INNER JOIN `WF_TRANSITIONS` TR ON PR.`ID_A`=TR.`ID_A_BEGIN`
			WHERE
				PR.`ID_PI`=? AND TR.`ID_T`=?
		",array($process_item_id,$next_transition_id));
		if($r->rowCount()>0)return true;
		return false;
	}
/**
	 * Kiểm tra xem từ 1 process item khi send có đúng theo quy trình không
	 *
	 * @param int $process_item_id
	 * @param int $next_transition_id
	 */
	static function HaveSendAbleByTransition($next_transition_id,$user_receive){
		global $db;
		$r = $db->query("select ID_P,ID_A_BEGIN,ID_A_END from WF_TRANSITIONS where ID_T=?",array($next_transition_id));
		$transition = $r->fetch();
		if(WFEngine::HaveAccessableActivity($transition["ID_A_END"],$user_receive)){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * Chuyển process item cho người khác
	 *
	 * @param int $process_item_id
	 * @param int $next_transition_id
	 * @param int $user_send
	 * @param int $user_receive
	 * @return bool true: chuyển thành công
	 * 				false: chuyển không thành công (do hack);
	 */
	static function SendNextUser($process_item_id,$next_transition_id,$user_send,$user_receive,$noidung,$hanxuly){
		try{
			global $db;
			//Check quyen truy cap process_item_id
			if(WFEngine::HaveAccessableProcessItem($process_item_id,$user_send)){
				//Lay activity tiep theo
				$r = $db->query("select ID_P,ID_A_BEGIN,ID_A_END from WF_TRANSITIONS where ID_T=?",array($next_transition_id));
				$transition = $r->fetch();
				//Kiem tra ton tai transition
				if($r->rowCount()==1){
					$r->closeCursor();
					//Kiem tra tinh hop le cua quy trinh
					if(WFEngine::HaveSendAble($process_item_id,$next_transition_id)){
						//Kiem tra quyen truy cap activity cua nguoi nhan
						if(WFEngine::HaveAccessableActivity($transition["ID_A_END"],$user_receive)){
							$r = $db->update(QLVBDHCommon::Table("wf_processitems"),array("ID_A"=>$transition["ID_A_END"],"ID_U"=>$user_receive,"LASTCHANGE"=>date("Y-m-d H:i:s")),"ID_PI=".$process_item_id);
							WFEngine::WriteLog(
								$process_item_id,
								$next_transition_id,
								$user_send,
								$user_receive,
								$transition["ID_A_BEGIN"],
								$transition["ID_A_END"],
								$transition["ID_P"],
								0,
								0,
								$noidung,
								$hanxuly
							);
						}else{
							return -1003;
						}
					}else{
						return -1007;
					}
				}
			}else{
				return -1006;
			}
			return 1;
		}catch(Exception $ex){
			return -1;
		}
	}
/**
	 * Chuyển process item cho người khác
	 *
	 * @param int $process_item_id
	 * @param int $next_transition_id
	 * @param int $user_send
	 * @param int $user_receive
	 * @return bool true: chuyển thành công
	 * 				false: chuyển không thành công (do hack);
	 */
	static function SendNextUserByObjectId($idobject,$next_transition_id,$user_send,$user_receive,$noidung,$hanxuly){
		try{
			global $db;
			$sql="
				SELECT
					ID_PI
				FROM
					".QLVBDHCommon::Table("WF_PROCESSITEMS")."
				WHERE
					ID_O=?
			";
			$r = $db->query($sql,array($idobject));
			$process = $r->fetch();
			$process_item_id = $process["ID_PI"];
			//Check quyen truy cap process_item_id
			if(WFEngine::HaveAccessableProcessItem($process_item_id,$user_send)){
				//Lay activity tiep theo
				$r = $db->query("select ID_P,ID_A_BEGIN,ID_A_END from WF_TRANSITIONS where ID_T=?",array($next_transition_id));
				$transition = $r->fetch();
				//Kiem tra ton tai transition
				if($r->rowCount()==1){
					$r->closeCursor();
					//Kiem tra tinh hop le cua quy trinh
					if(WFEngine::HaveSendAble($process_item_id,$next_transition_id)){
						//Kiem tra quyen truy cap activity cua nguoi nhan
						if(WFEngine::HaveAccessableActivity($transition["ID_A_END"],$user_receive)){
							$r = $db->update(QLVBDHCommon::Table("wf_processitems"),array("ID_A"=>$transition["ID_A_END"],"ID_U"=>$user_receive,"LASTCHANGE"=>date("Y-m-d H:i:s")),"ID_PI=".$process_item_id);
							WFEngine::WriteLog(
								$process_item_id,
								$next_transition_id,
								$user_send,
								$user_receive,
								$transition["ID_A_BEGIN"],
								$transition["ID_A_END"],
								$transition["ID_P"],
								0,
								0,
								$noidung,
								$hanxuly
							);
						}else{
							return -1003;
						}
					}else{
						return -1007;
					}
				}
			}else{
				return -1006;
			}
			return 1;
		}catch(Exception $ex){
			echo $ex->__toString();
			return -1;
		}
	}
	static function SendNextUserByObjectId2($idobject,$next_transition_id,$user_send,$id_receive,$type,$noidung,$hanxuly){
		try{
			global $db;
			$sql="
				SELECT
					ID_PI
				FROM
					".QLVBDHCommon::Table("WF_PROCESSITEMS")."
				WHERE
					ID_O=?
			";
			$r = $db->query($sql,array($idobject));
			$process = $r->fetch();
			$process_item_id = $process["ID_PI"];
			//kiem tra nguoi dai dien
			if($type==WFEngine::$WFTYPE_GROUP){
				$sql = "SELECT ID_U_DAIDIEN FROM QTHT_GROUPS WHERE ID_G=? AND ID_U_DAIDIEN>0";
				$r = $db ->query($sql,$id_receive);
				if($r->rowCount()==1){
					$g = $r->fetch();
					$id_receive = $g['ID_U_DAIDIEN'];
					$type = WFEngine::$WFTYPE_USER;
				}
			}else if($type==WFEngine::$WFTYPE_DEP){
				$sql = "SELECT ID_U_DAIDIEN FROM QTHT_DEPARTMENTS WHERE ID_DEP=? AND ID_U_DAIDIEN>0";
				$r = $db ->query($sql,$id_receive);
				if($r->rowCount()==1){
					$dep = $r->fetch();
					$id_receive = $dep['ID_U_DAIDIEN'];
					$type = WFEngine::$WFTYPE_USER;
				}
			}
			//Check quyen truy cap process_item_id
			if(WFEngine::HaveAccessableProcessItem($process_item_id,$user_send)){
				//Lay activity tiep theo
				$r = $db->query("select ID_P,ID_A_BEGIN,ID_A_END from WF_TRANSITIONS where ID_T=?",array($next_transition_id));
				$transition = $r->fetch();
				//Kiem tra ton tai transition
				if($r->rowCount()==1){
					$r->closeCursor();
					//Kiem tra tinh hop le cua quy trinh
					if(WFEngine::HaveSendAble($process_item_id,$next_transition_id)){
						//Kiem tra quyen truy cap activity cua nguoi nhan
						$ok = false;
						if($type==WFEngine::$WFTYPE_USER){
							$ok = WFEngine::HaveAccessableActivity($transition["ID_A_END"],$id_receive);
						}else if($type==WFEngine::$WFTYPE_DEP){
							$ok = WFEngine::HaveAccessableActivityDep($transition["ID_A_END"],$id_receive);
						}else if($type==WFEngine::$WFTYPE_GROUP){
							$ok = WFEngine::HaveAccessableActivityGroup($transition["ID_A_END"],$id_receive);
						}
						if($ok){
							$r = $db->update(
								QLVBDHCommon::Table("wf_processitems"),
								array(
									"ID_A"=>$transition["ID_A_END"],
									"ID_U"=>$type==WFEngine::$WFTYPE_USER?$id_receive:0,
									"ID_G"=>$type==WFEngine::$WFTYPE_GROUP?$id_receive:0,
									"ID_DEP"=>$type==WFEngine::$WFTYPE_DEP?$id_receive:0,
									"LASTCHANGE"=>date("Y-m-d H:i:s")
								),
								"ID_PI=".$process_item_id
							);
							WFEngine::WriteLog(
								$process_item_id,
								$next_transition_id,
								$user_send,
								$type==WFEngine::$WFTYPE_USER?$id_receive:0,
								$transition["ID_A_BEGIN"],
								$transition["ID_A_END"],
								$transition["ID_P"],
								$type==WFEngine::$WFTYPE_GROUP?$id_receive:0,
								$type==WFEngine::$WFTYPE_DEP?$id_receive:0,
								$noidung,
								$hanxuly
							);
						}else{
							return -1003;
						}
					}else{
						return -1007;
					}
				}
			}else{
				return -1006;
			}
			return 1;
		}catch(Exception $ex){
			echo $ex->__toString();
			return -1;
		}
	}
	/**
	 * Lấy id của log khởi nguồn mọi rắc rối
	 */
	static function GetStartLogIdByEndAt($process_item_id,$transition_id){
		//Lấy END_AT tu transition_id
		/*global $db;
		$sql = "SELECT * FROM WF_TRANSITIONS WHERE END_AT=? ";
		$r=$db->query($sql,$transition_id);
		if($r->rowCount()>=1){
			$result = $r->fetchAll();
			$logid = array();
			foreach($result as $item){
				$transitionid[] = $item['ID_T'];
			}
			$sql = "SELECT ID_PL,HANXULY,DATESEND,ID_U_RECEIVE FROM ".QLVBDHCommon::Table("WF_PROCESSLOGS")." WHERE ID_T in (".implode(",",$transitionid).") AND ID_PI = ?";
			$r = $db->query($sql,$process_item_id);
			return $r->fetchAll();
		}else{
			return 0;
		}*/
		global $db;
		$sql = "SELECT * FROM WF_TRANSITIONS ";
		$r=$db->query($sql);
		$re = $r->fetchAll();
		$result = array();
		foreach($re as $re_it){
			$at_end_mul = $re_it["END_AT_MULTI"];
			if($at_end_mul){
				$arr_end_at_nul = explode(",",$at_end_mul);
				$ln = 0;
				foreach($arr_end_at_nul as $end_at){
					if($end_at == $transition_id){
						//lam gi day
						$result[] = $re_it;
					}
				}
			}
		}
		if(count($result)>=1){
			$logid = array();
			foreach($result as $item){
				$transitionid[] = $item['ID_T'];
			}
			$sql = "SELECT ID_PL,HANXULY,DATESEND,ID_U_RECEIVE FROM ".QLVBDHCommon::Table("WF_PROCESSLOGS")." WHERE ID_T in (".implode(",",$transitionid).") AND ID_PI = ? AND TRE IS NULL ORDER BY ID_PL";
			$r = $db->query($sql,$process_item_id);
			return $r->fetchAll();
		}else{
			return 0;
		}
	}
	/**
	 * Lấy id của log khởi nguồn mọi rắc rối
	 */
	static function GetStartLogIdByProcessItem($process_item_id){
		//Lấy END_AT tu transition_id
		global $db;
		$sql = "SELECT
			tr.ID_T
		FROM
			WF_TRANSITIONS tr
			inner join ".QLVBDHCommon::Table("WF_PROCESSITEMS")." p on p.ID_P = tr.ID_P
		WHERE
			p.ID_PI = ? AND
			tr.END_AT > 0";
		$r=$db->query($sql,$process_item_id);
		if($r->rowCount()>=1){
			$result = $r->fetchAll();
			$logid = array();
			foreach($result as $item){
				$transitionid[] = $item['ID_T'];
			}
			$sql = "SELECT ID_PL,HANXULY,DATESEND,ID_U_RECEIVE,ID_U_SEND,ID_G_RECEIVE,ID_DEP_RECEIVE FROM ".QLVBDHCommon::Table("WF_PROCESSLOGS")." WHERE ID_T in (".implode(",",$transitionid).") AND ID_PI = ? AND TRE IS NULL ";
			$r = $db->query($sql,$process_item_id);
			return $r->fetchAll();
		}else{
			return 0;
		}
	}
	/**
	 * Lấy id của log khởi nguồn mọi rắc rối
	 */
	static function CheckUpdateTre($transition_id){
		//Lấy END_AT tu transition_id
		global $db;
		$sql = "SELECT * FROM WF_TRANSITIONS WHERE END_AT > 0 AND ID_T = ?";
		$r=$db->query($sql,$transition_id);
		if($r->rowCount()>=1){
			return 0;
		}else{
			return 1;
		}
	}
	/**
	 * Cập nhật Log
	 *
	 * @param int $process_item_id
	 * @param int $user_send
	 * @param int $user_receive
	 */
	static function WriteLog($process_item_id,$transition_id,$user_send,$user_receive,$activity_begin,$activity_end,$process,$group_receive,$dep_receive,$noidung,$hanxuly,$sms,$email){
		global $db;
		//tìm dòng luân chuyển trước
		$lastpl = WFEngine::GetStartLogIdByEndAt($process_item_id,$transition_id);
		if(is_array($lastpl)){
			foreach($lastpl as $item){
				if($item['DATESEND']!="" && $item['HANXULY']>0){
					$tre = QLVBDHCommon::getTreHan($item['DATESEND'],$item['HANXULY']);
					$db->update(QLVBDHCommon::Table("wf_processlogs"),array("TRE"=>$tre),"ID_PL=".$item['ID_PL']);
				}else if($item['HANXULY']==0){
					$db->update(QLVBDHCommon::Table("wf_processlogs"),array("TRE"=>0),"ID_PL=".$item['ID_PL']);
				}
			}
			$lastpl = WFEngine::GetCurrentTransitionInfoByIdProcess($process_item_id);
			if(WFEngine::CheckUpdateTre($lastpl['ID_T'])==1){
				if($lastpl['DATESEND']!="" && $lastpl['HANXULY']>0){
					$tre = QLVBDHCommon::getTreHan($lastpl['DATESEND'],$lastpl['HANXULY']);
					$db->update(QLVBDHCommon::Table("wf_processlogs"),array("TRE"=>$tre),"ID_PL=".$lastpl['ID_PL']);
				}
			}
		}else{
			$lastpl = WFEngine::GetCurrentTransitionInfoByIdProcess($process_item_id);
			if(WFEngine::CheckUpdateTre($lastpl['ID_T'])==1){
				if($lastpl['DATESEND']!="" && $lastpl['HANXULY']>0){
					$tre = QLVBDHCommon::getTreHan($lastpl['DATESEND'],$lastpl['HANXULY']);
					$db->update(QLVBDHCommon::Table("wf_processlogs"),array("TRE"=>$tre),"ID_PL=".$lastpl['ID_PL']);
				}
			}
		}
		$hanxuly = $hanxuly==""?0:$hanxuly;
		//insert vào log
		$enddate = QLVBDHCommon::addDate(time(),($hanxuly==0?999:$hanxuly));
		$db->insert(QLVBDHCommon::Table("wf_processlogs"),array(
			"ID_U_SEND"=>$user_send,
			"ID_U_RECEIVE"=>$user_receive,
			"ID_PI"=>$process_item_id,
			"ID_T"=>$transition_id,
			"DATESEND"=>date("Y-m-d H:i:s"),
			"ID_A_BEGIN"=>$activity_begin,
			"ID_A_END"=>$activity_end,
			"ID_P"=>$process,
			"ID_G_RECEIVE"=>$group_receive,
			"ID_DEP_RECEIVE"=>$dep_receive,
			"NOIDUNG"=>$noidung,
			"HANXULY"=>$hanxuly,
			"SMS"=>$sms,
			"EMAIL"=>$email,
			"DATEEND"=>date("Y-m-d H:i:s",$enddate)
		));
		$id_log = $db->lastInsertId(QLVBDHCommon::Table("wf_processlogs"));
		//cap nhat log trang thai ho so
		//lay các trạng thái cần cập nhật
		//lay cac trang thai ho so tuong ung voi trang thai hien tai
		$r = $db->query("SELECT ID_O, pi.NAME, c.ALIAS  FROM ".QLVBDHCommon::Table("WF_PROCESSITEMS")." pi
        INNER JOIN WF_PROCESSES p on pi.ID_P = p.ID_P
        INNER JOIN WF_CLASSES c on p.ID_C = c.ID_C
        WHERE ID_PI=?",$process_item_id);
		$r = $r->fetch();
		//chdeck finish
		$is_finish = WFEngine::IsFinishTransition($transition_id);
		if($is_finish){
			$db->update(QLVBDHCommon::Table("WF_PROCESSITEMS"),array("IS_FINISH"=>1),"ID_PI=".$process_item_id);
		}
		//cập nhật log trạng thái hồ sơ
		if($is_finish) $is_lastest = 1;
		//$db->update(QLVBDHCommon::Table("wf_trangthaihosologs"),array("ACTIVE"=>0),"ID_HSCV=".$r["ID_O"]);
		$db->update(QLVBDHCommon::Table("wf_trangthaihosologs"),array("ACTIVE"=>0,"IS_PRE"=>1)," ACTIVE=1 AND ID_HSCV=".$r["ID_O"]);
		//lay pre_seq trước
		$qr_presq = $db->query("select PRE_SEQ,IS_CHOBOSUNG from ". QLVBDHCommon::Table("wf_trangthaihosologs")." where ID_HSCV = ? ORDER BY ID_TTHSLOG DESC ",array($r["ID_O"]));
		$pre_sqRe =  $qr_presq->fetch();
		$pre_sq = $pre_sqRe["PRE_SEQ"];
		$is_chobosung = $pre_sqRe["IS_CHOBOSUNG"];
		$tthss = $db->query("select tths.* from wf_trangthaihoso tths inner join wf_activity_fktth fk on tths.ID_TTHS = fk.ID_TTHS where ID_A = ? ",$activity_end)->fetchAll();
		foreach($tthss as $tt ){
			$id_u_tt = 0;
			if($tt["LATRANGTHAINHAN"])
				$id_u_tt = $user_receive;
			else
				$id_u_tt =  $user_send ;
			//xem trang thai cua nguoi hien tai co trong log hay khong
			//$db->query("select count(*) as DEM from wf_trangthaihosologs where ID_HSCV  ")
			if($id_u_tt >0)
				$db->insert(QLVBDHCommon::Table("wf_trangthaihosologs"),array("ID_TTHS"=>$tt["ID_TTHS"],"ID_U"=>$id_u_tt,"ID_HSCV"=>$r["ID_O"],"ID_PL"=>$id_log,"PRE_SEQ"=>(int)($pre_sq+1),"IS_CHOBOSUNG"=>$is_chobosung,"IS_LASTEST"=>(int)$is_lastest));
		}
		/*note
			1. Tổ chức lưu lại log khi thu hồi hồ sơ
			2. Cập nhật trạng thái Cho bo sung ho so cho cac ho so sau
		*/
        if($r["ALIAS"] == "MOTCUA"){
            WFEngine::updateTTHSMCForward($r["ID_O"],$transition_id,$is_finish);
        }
		if($user_receive!=0){
			QLVBDHCommon::SendMessage(
				$user_send,
				$user_receive,
				$r['NAME'],
				"hscv/hscv/list/idhscv/".$r["ID_O"]."#hscv".$r["ID_O"]
			);
			$sql="
				SELECT ds.CAP as CAPS FROM
				QTHT_USERS us
				INNER JOIN QTHT_EMPLOYEES es on us.ID_EMP = es.ID_EMP
				INNER JOIN QTHT_DEPARTMENTS ds on es.ID_DEP = ds.ID_DEP
				WHERE
				us.ID_U=?
			";
			$rs = $db->query($sql,$user_send)->fetch();
			$sql="
				SELECT ds.CAP as CAPS FROM
				QTHT_USERS us
				INNER JOIN QTHT_EMPLOYEES es on us.ID_EMP = es.ID_EMP
				INNER JOIN QTHT_DEPARTMENTS ds on es.ID_DEP = ds.ID_DEP
				WHERE
				us.ID_U=?
			";
			$rr = $db->query($sql,$user_receive)->fetch();
			if($rs['CAPS']!=$rr['CAPS'] && $rr['CAPS']==1){
				$db->update(QLVBDHCommon::Table("MOTCUA_HOSO"),array("CHUYENUB_NGAY"=>date("Y-m-d H:i:s")),"ID_HSCV=".$r["ID_O"]);
			}
		}
		return true;
	}
	static function IsMulti($transition_id){
		global $db;
		$r = $db->query("
		SELECT
			tr.MULTI
		FROM
			WF_TRANSITIONS tr
			inner join WF_TRANSITIONPOOLS tp on tr.ID_TP=tp.ID_TP
			inner join WF_PROCESSES p on p.ID_P = tr.ID_P
			inner join WF_CLASSES c on p.ID_C = c.ID_C
		WHERE
			tr.MULTI = 1 AND
			c.HAVEMULTI = 1 AND
			tp.HAVEMULTI = 1 AND
			ID_T=?
		",array($transition_id));
		if($r->rowCount()==1)return true;
		return false;
	}
	static function IsCheckMulti($transitionpool_id){
		global $db;
		$r = $db->query("
		SELECT
			1 as a
		FROM
			WF_TRANSITIONPOOLS tp
			inner join WF_CLASSES c on c.ID_C = tp.ID_C
		WHERE
			c.HAVEMULTI = 1 AND
			tp.HAVEMULTI = 1 AND
			ID_TP=?
		",$transitionpool_id);
		if($r->rowCount()==1)return true;
		return false;
	}
	/**
	 * Tạo form send next user.
	 *
	 * @param int $transition_id
	 * @return string $html
	 */
	static function FormSend($transition_id,$forceone = false){
		global $db;
		//Lay han xu ly mac dinh
		$r = $db->query("SELECT HANXULY FROM WF_TRANSITIONS WHERE ID_T=?",$transition_id);
		$hanxuly = $r->fetch();
		$hanxuly = $hanxuly['HANXULY'];
		//Lấy danh sách các group
		$r = $db->query("
		SELECT
			G.ID_G,G.NAME
		FROM
			WF_ACTIVITYACCESSES AC
			INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_END
			INNER JOIN QTHT_GROUPS G ON AC.ID_G=G.ID_G
		WHERE
			TR.ID_T=?
		ORDER BY NAME
		",array($transition_id));
		$group = $r->fetchAll();
		$r->closeCursor();
		//Lấy danh sách các phòng
		$r = $db->query("
		SELECT
			DEP.ID_DEP,DEP.NAME,DEP.ID_U_DAIDIEN
		FROM
			WF_ACTIVITYACCESSES AC
			INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_END
			INNER JOIN QTHT_DEPARTMENTS DEP ON AC.ID_DEP=DEP.ID_DEP
		WHERE
			TR.ID_T=?
		ORDER BY NAME
		",array($transition_id));
		$dep = $r->fetchAll();
		$r->closeCursor();
		//Lay danh sách các user
		$r = $db->query("
			SELECT
				'-1' as ID_G, DEP.ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
			FROM
				WF_ACTIVITYACCESSES AC
				INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_END
				INNER JOIN QTHT_DEPARTMENTS DEP ON DEP.ID_DEP = AC.ID_DEP
				INNER JOIN QTHT_EMPLOYEES E ON E.ID_DEP = DEP.ID_DEP
				INNER JOIN QTHT_USERS U ON U.ID_EMP = E.ID_EMP
			WHERE
				TR.ID_T=?
			UNION ALL
			SELECT
				UG.ID_G, DEP.ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
			FROM
				WF_ACTIVITYACCESSES AC
				INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_END
				INNER JOIN QTHT_DEPARTMENTS DEP ON DEP.ID_DEP = AC.ID_DEP
				INNER JOIN QTHT_EMPLOYEES E ON E.ID_DEP = DEP.ID_DEP
				INNER JOIN QTHT_USERS U ON U.ID_EMP = E.ID_EMP
				INNER JOIN FK_USERS_GROUPS UG ON UG.ID_U=U.ID_U
			WHERE
				TR.ID_T=?
			UNION ALL
			SELECT
				G.ID_G,-1 AS ID_DEP, U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
			FROM
				WF_ACTIVITYACCESSES AC
				INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_END
				INNER JOIN QTHT_GROUPS G ON AC.ID_G=G.ID_G
				INNER JOIN FK_USERS_GROUPS UG ON UG.ID_G=G.ID_G
				INNER JOIN QTHT_USERS U ON U.ID_U = UG.ID_U
				INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
			WHERE
				TR.ID_T=?
			UNION ALL
			SELECT
				-1 AS ID_G,'-1' AS ID_DEP, U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
			FROM
				WF_ACTIVITYACCESSES AC
				INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_END
				INNER JOIN QTHT_USERS U ON AC.ID_U=U.ID_U
				INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
			WHERE TR.ID_T=?
			ORDER BY NAME
		",array($transition_id,$transition_id,$transition_id,$transition_id));
		$user = $r->fetchAll();
		$r->closeCursor();
		if(WFEngine::IsMulti($transition_id) && $forceone==false){
			require_once 'plugin/multisend.php';
		}else{
			if(count($user)>1){
				//Xuat du lieu ra html
				$html = "";
				$html.="<div class='required clearfix'>";
				$html.="  <label>Nhóm</label>";
				$html.="  <select name=wf_selg id=wf_selg onchange='FillComboBy2Combo(this,document.getElementById(\"wf_seldep\"),document.getElementById(\"wf_nextuser\"),wf_arr_user);'>";
				$html.="  	<option value=-1>--Chọn nhóm--</option>";
				for($i=0;$i<count($group);$i++){
					$html.=" <option value=".$group[$i]["ID_G"].">".$group[$i]["NAME"]."</option>";
				}
				$html.="  </select>";
				$html.="</div>";
				$html.="<div class='required clearfix'>";
				$html.="  <label>Phòng</label>";
				$html.="  <select name=wf_seldep id=wf_seldep onchange='FillComboBy2Combo(document.getElementById(\"wf_selg\"),this,document.getElementById(\"wf_nextuser\"),wf_arr_user);'>";
				$html.="  	<option value=-1>--Chọn phòng--</option>";
				for($i=0;$i<count($dep);$i++){
					$html.=" <option value=".$dep[$i]["ID_DEP"].">".$dep[$i]["NAME"]."</option>";
				}
				$html.="  </select>";
				$html.="</div>";
				$html.="<div class='required clearfix'>";
				$html.="  <label>Người</label>";
				$html.="  <select name=wf_nextuser id=wf_nextuser>";
				$html.="  </select>";
				$html.="</div>";
				$html.="<div class='required clearfix'>";
				$html.="  <label>Nội dung</label>";
				$html.="  <textarea name=wf_name_user id=wf_name_user cols=100 rows=3>";
				$html.="  </textarea>";
				$html.="</div>";
				$html.="<div class='required clearfix'>";
				$html.="  <label>Hạn xử lý</label>";
				$html .= QLVBDHCommon::createInputHanxuly("wf_hanxuly_user","wf_hanxuly_user",$hanxuly);
				//$html.="  <input size=3 maxlength=3 name=wf_hanxuly_user onkeypress='return isNumberKey(event)' id=wf_hanxuly_user value='".$hanxuly."'>";
				$html.="</div>";
				$html.="<input type=hidden name=wf_nexttransition value='".$transition_id."'>";
				$html.="<script>";
				$html.="	var wf_arr_user = new Array();";
				for($i=0;$i<count($user);$i++){
					$html.="wf_arr_user[".$i."] = new Array('".$user[$i]['ID_G']."','".$user[$i]['ID_DEP']."','".$user[$i]['ID_U']."','".$user[$i]['NAME']."');";
				}
				$html.="	FillComboBy2Combo(document.getElementById(\"wf_selg\"),document.getElementById(\"wf_seldep\"),document.getElementById(\"wf_nextuser\"),wf_arr_user);";
				$html.="</script>";
			}else{
				$html.="<input type=hidden name=wf_nexttransition value='".$transition_id."'>";
				$html.="<input type=hidden name='wf_nextuser' value='".$user[0]['ID_U']."'>";
				$html.="<div class='required clearfix'><label>Nhân viên</label>".$user[0]['NAME']."</div>";
				$html.="<div class='required clearfix'>";
				$html.="  <label>Nội dung</label>";
				$html.="  <textarea name=wf_name_user id=wf_name_user cols=100 rows=3>";
				$html.="  </textarea>";
				$html.="</div>";
				$html.="<div class='required clearfix'>";
				$html.="  <label>Hạn xử lý</label>";
				$html .= QLVBDHCommon::createInputHanxuly("wf_hanxuly_user","wf_hanxuly_user",$hanxuly);
				//$html.="  <input size=4 name=wf_hanxuly_user id=wf_hanxuly_user value='".$hanxuly."'>";
				$html.="</div>";
			}
			return $html;
		}
	}
	/**
	 * Lấy menu xử lý. Menu trả về có dạng <ul><li>
	 * Class menu cha: parentmenu
	 * Class menu con: childmemu
	 *
	 * @param int $user_id
	 * @return string $html
	 */
	static function GetMainMenu($user_id){
		global $db;
		$r = $db->query("
		SELECT
			PR.ALIAS, PR.ID_P, PR.NAME as PR_NAME, A.`ID_A`,A.`NAME`,AP.`LINK`,AP.ORDERS as ORDERS_AP,PR.ORDERS as ORDERS_PR
		FROM
			`WF_PROCESSES` PR
			INNER JOIN `WF_ACTIVITIES` A ON A.`ID_P`=PR.`ID_P`
			INNER JOIN `WF_ACTIVITYACCESSES` AC ON A.`ID_A`= AC.`ID_A`
			INNER JOIN QTHT_USERS U ON U.`ID_U` = AC.`ID_U`
			INNER JOIN `WF_ACTIVITYPOOLS` AP ON AP.`IP_AP` = A.`ID_AP`
		WHERE
			U.`ID_U`=?
		UNION
		SELECT
			PR.ALIAS, PR.ID_P, PR.NAME as PR_NAME, A.`ID_A`,A.`NAME`,AP.`LINK`,AP.ORDERS as ORDERS_AP,PR.ORDERS as ORDERS_PR
		FROM
			`WF_PROCESSES` PR
			INNER JOIN `WF_ACTIVITIES` A ON A.`ID_P`=PR.`ID_P`
			INNER JOIN `WF_ACTIVITYACCESSES` AC ON A.`ID_A`= AC.`ID_A`
			INNER JOIN `WF_ACTIVITYPOOLS` AP ON AP.`IP_AP` = A.`ID_AP`
			INNER JOIN `QTHT_GROUPS` G ON G.`ID_G` = AC.`ID_G`
			INNER JOIN `FK_USERS_GROUPS` UG ON UG.`ID_G`=G.`ID_G`
		WHERE
			UG.`ID_U`=?
		ORDER BY
			ORDERS_PR,ORDERS_AP
		",array($user_id,$user_id));
		$process = $r->fetchAll();
		$r->closeCursor();
		$html="";
		if($r->rowCount()>0){
			$currentprocess = 0;
			$html.="<ul><li class=parentmenu>".$process[0]["PR_NAME"]."<ul>";
			for($i=0;$i<$r->rowCount();$i++){
				if($currentprocess!=$process[$i]["ID_P"] && $currentprocess>0){
					$html.="</ul></li></ul><ul><li>".$process[$i]["PR_NAME"]."<ul>";
					$currentprocess = $process[$i]["ID_P"];
				}else{
					$create = WFEngine::GetCreateProcessButton($process[$i]["ALIAS"],$user_id);
					if(count($create)>0){
						$html.="<li class=childmenu><a href='".$create["LINK"]."/wf_id_t/".$create["ID_T"]."'>".$create["NAME"]."</a></li>";
					}else{
						$html.="<li class=childmenu><a href='".$process[$i]["LINK"]."?wf_id_p=".$process[$i]["ID_P"]."&wf_id_a=".$process[$i]["ID_A"]."'>".$process[$i]["NAME"]."</a></li>";
					}
					$currentprocess = $process[$i]["ID_P"];
				}
			}
			$html.="</ul></li></ul>";
		}
		return $html;
	}
	/**
	 * Tạo button ho việc khởi tạo process
	 * @param int $process_id
	 * @param int $activity_id
	 * @param int $user_id
	 * @return array $arr
	 */
	static function GetCreateProcessButton($process_alias,$user_id){
		global $db;
		//Get transition co begin activity = null
		$r = $db->query("
			select
				ID_T,tp.ID_TP,LINK,tp.NAME
			from
				WF_TRANSITIONS t
				inner join WF_TRANSITIONPOOLS tp on t.ID_TP=tp.ID_TP
			where
				ISFIRST=1 and tp.ALIAS=?",array($process_alias));
		$transition = $r->fetch();
		$r->closeCursor();
		$arr = array();
		if($r->rowCount()>0){
		//xuat ra html
			$arr = array("ID_T"=>$transition["ID_T"],"LINK"=>$transition["LINK"],"NAME"=>$transition["NAME"]);
		}
		return $arr;
	}
	/**
	 * Tạo button ho việc khởi tạo process
	 * @param int $process_id
	 * @param int $activity_id
	 * @param int $user_id
	 * @return array $arr
	 */
	static function GetCreateProcessButtonFromLoaiCV($loaicv,$user_id){
		global $db;
		//Get transition co begin activity = null
		$r = $db->query("
			select
				ID_T,tp.ID_TP,LINK,tp.NAME,t.ID_A_BEGIN
			from
				WF_TRANSITIONS t
				inner join WF_TRANSITIONPOOLS tp on t.ID_TP=tp.ID_TP
				inner join WF_PROCESSES p on p.ID_P = t.ID_P
				inner join HSCV_LOAIHOSOCONGVIEC lcv on p.ALIAS = lcv.MASOQUYTRINH
			where
				ISFIRST=1 and lcv.ID_LOAIHSCV=?",array($loaicv));
		$transition = $r->fetch();
		$r->closeCursor();
		$arr = array();
		if($r->rowCount()>0){
			if(WFEngine::HaveAccessableActivity($transition["ID_A_BEGIN"],$user_id)){
				$arr = array("ID_T"=>$transition["ID_T"],"LINK"=>$transition["LINK"],"NAME"=>$transition["NAME"]);
			}
		}
		return $arr;
	}
	/**
	 *
	 */
	static function GetActivityFromLoaiCV($loaicv,$user_id){
		global $db;
		//check loai hscv
		$param = array();
		$where = "";
		if($loaicv>0){
			$where = "and lcv.ID_LOAIHSCV=?";
			$param[] = $loaicv;
		}
		//Get transition co begin activity = null
		$r = $db->query("
			select
				distinct a.ID_A,a.NAME as NAME_AP,ap.ORDERS as ORDERS_AP,p.ID_P,p.NAME as NAME_P,p.ORDERS as ORDERS_P
			from
				WF_ACTIVITIES a
				inner join WF_ACTIVITYPOOLS ap on a.ID_AP = ap.IP_AP
				inner join WF_PROCESSES p on p.ID_P = a.ID_P
				inner join HSCV_LOAIHOSOCONGVIEC lcv on p.ALIAS = lcv.MASOQUYTRINH
				inner join WF_TRANSITIONS tp on tp.ID_A_END=a.ID_A
			where
				true
				$where
			ORDER BY
				ORDERS_P,ORDERS_AP
			",$param);
		$activity = $r->fetchAll();
		$r->closeCursor();
		$arr = array();
		if($r->rowCount()>0){
			for($i=0;$i<$r->rowCount();$i++){
				if(WFEngine::HaveAccessableActivity($activity[$i]["ID_A"],$user_id)){
					$arr[] = array($activity[$i]["ID_P"],$activity[$i]["NAME_P"],$activity[$i]["ID_A"],$activity[$i]["NAME_AP"]);
				}
			}
		}
		return $arr;
	}
	static function GetLoaiCongViecFromUser($user_id){
		global $db;
		//Get transition co begin activity = null
		$r = $db->query("
			select
				lcv.ID_LOAIHSCV,p.NAME,p.ID_P
			from
				WF_PROCESSES p
					inner join HSCV_LOAIHOSOCONGVIEC lcv on p.ALIAS = lcv.MASOQUYTRINH
			",array());
		$process = $r->fetchAll();
		$r->closeCursor();
		$arr = array();
		if($r->rowCount()>0){
			for($i=0;$i<count($process);$i++){
				if(WFEngine::HaveAccessableProcess($process[$i]["ID_P"],$user_id)){
					$arr[] = array("ID_LOAIHSCV"=>$process[$i]["ID_LOAIHSCV"],"NAME"=>$process[$i]["NAME"]);
				}
			}
		}
		return $arr;
	}
	/**
	 *
	 */
	static function ToCombo($arr,$sel){
		$html = "";
		$idp=0;
		for($i=0;$i<count($arr);$i++){
			if($arr[$i][0]!=$idp){
				if($idp>0){
					$html .= "</optgroup>";
				}
				$html .= "<optgroup label='".htmlspecialchars($arr[$i][1])."'>";
				$idp = $arr[$i][0];
				//kiểm tra xem có phải activity cuối không
				if($arr[$i][2]!=$sel){
					$html .= "<option value=".$arr[$i][2].">".htmlspecialchars($arr[$i][3])."</option>";
				}else{
					$html .= "<option value=".$arr[$i][2]." selected>".htmlspecialchars($arr[$i][3])."</option>";
				}
			}else{
				//kiểm tra xem có phải activity cuối không
				if($arr[$i][2]!=$sel){
					$html .= "<option value=".$arr[$i][2].">".htmlspecialchars($arr[$i][3])."</option>";
				}else{
					$html .= "<option value=".$arr[$i][2]." selected>".htmlspecialchars($arr[$i][3])."</option>";
				}
			}
		}
		$html .= "</optgroup>";
		return $html;
	}
	static function GetCurrentTransitionInfoByIdHscv($idhscv){
		global $db;
		//Lấy thông tin lần chuyển cuối cùng
		$sql = "
			SELECT
				concat(empnc.FIRSTNAME,' ',empnc.LASTNAME) as EMPNCNAME,unc.ID_U as ID_U_NC,
				concat(empnk.FIRSTNAME,' ',empnk.LASTNAME) as EMPNKNAME,unk.ID_U as ID_U_NK,
				g.NAME as GROUPNAME,
				dep.NAME as DEPNAME,
				pl.DATESEND,
				pl.HANXULY,
				pl.ID_PL,
				pl.ID_PI,
				pl.ID_T,
				ac.NAME as AC_NAME,
				tr.ISLAST,
				pl.NOIDUNG,
				hscv.IS_CHOXULY
			FROM
				".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv
				inner join ".QLVBDHCommon::Table("WF_PROCESSLOGS")." pl on hscv.ID_PI = pl.ID_PI
				inner join QTHT_USERS unc on unc.ID_U = pl.ID_U_SEND
				inner join QTHT_EMPLOYEES empnc on unc.ID_EMP = empnc.ID_EMP
				left join QTHT_USERS unk on unk.ID_U = pl.ID_U_RECEIVE
				left join QTHT_EMPLOYEES empnk on unk.ID_EMP = empnk.ID_EMP
				left join QTHT_GROUPS g on g.ID_G = pl.ID_G_RECEIVE
				left join QTHT_DEPARTMENTS dep on dep.ID_DEP = pl.ID_DEP_RECEIVE
				inner join WF_ACTIVITIES ac on ac.ID_A = pl.ID_A_END
				inner join WF_TRANSITIONS tr on tr.ID_T = pl.ID_T
			WHERE
				hscv.ID_HSCV = ?
			ORDER BY
				pl.ID_PL DESC
		";
		$r = $db->query($sql,array($idhscv));
		return $r->fetch();
	}
	static function GetCurrentTransitionInfoByIdProcess($idprocess){
		global $db;
		//Lấy thông tin lần chuyển cuối cùng
		$sql = "
			SELECT
				concat(empnc.FIRSTNAME,' ',empnc.LASTNAME) as EMPNCNAME,unc.ID_U as ID_U_NC,
				concat(empnk.FIRSTNAME,' ',empnk.LASTNAME) as EMPNKNAME,unk.ID_U as ID_U_NK,
				g.NAME as GROUPNAME,
				dep.NAME as DEPNAME,
				pl.DATESEND,
				pl.HANXULY,
				pl.ID_PL,
				pl.ID_T,
				pl.NOIDUNG
			FROM
				".QLVBDHCommon::Table("WF_PROCESSLOGS")." pl
				inner join QTHT_USERS unc on unc.ID_U = pl.ID_U_SEND
				inner join QTHT_EMPLOYEES empnc on unc.ID_EMP = empnc.ID_EMP
				left join QTHT_USERS unk on unk.ID_U = pl.ID_U_RECEIVE
				left join QTHT_EMPLOYEES empnk on unk.ID_EMP = empnk.ID_EMP
				left join QTHT_GROUPS g on g.ID_G = pl.ID_G_RECEIVE
				left join QTHT_DEPARTMENTS dep on dep.ID_DEP = pl.ID_DEP_RECEIVE
			WHERE
				pl.ID_PI = ?
			ORDER BY
				pl.ID_PL DESC
		";
		$r = $db->query($sql,array($idprocess));
		return $r->fetch();
	}
	static function GetProcessLogByObjectId($objectid){
		 global $db;
		 $sql= "
		 	SELECT
		 		unc.ID_U as ID_U_NC,
		 		unn.ID_U as ID_U_NN,
		 		concat(empnc.FIRSTNAME,' ',empnc.LASTNAME) as EMPNCNAME,
				concat(empnn.FIRSTNAME,' ',empnn.LASTNAME) as EMPNNNAME,
				g.NAME as GROUPNAME,
				dep.NAME as DEPNAME,
				tr.NAME,
				pl.DATESEND,
				pl.HANXULY,
				pl.TRE,
				pl.NOIDUNG,
				hscv.`NAME` as HSCV_NAME,
				hscv.`EXTRA`,
				hscv.IS_THEODOI,
				hscv.IS_BOSUNG,
				pl.ID_PL,
				tr.ISLAST,
				depnn.ID_DEP as DEPNNID,
				depnn.KYHIEU_PB as DEPNNNAME
		 	FROM
		 		".QLVBDHCommon::Table("WF_PROCESSLOGS")." pl
		 		inner join ".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv on pl.ID_PI = hscv.ID_PI
		 		inner join QTHT_USERS unc on unc.ID_U = pl.ID_U_SEND
				inner join QTHT_EMPLOYEES empnc on unc.ID_EMP = empnc.ID_EMP
				left join QTHT_USERS unn on unn.ID_U = pl.ID_U_RECEIVE
				left join QTHT_EMPLOYEES empnn on unn.ID_EMP = empnn.ID_EMP
				left join QTHT_DEPARTMENTS depnn on depnn.ID_DEP = empnn.ID_DEP
				left join QTHT_GROUPS g on g.ID_G = pl.ID_G_RECEIVE
				left join QTHT_DEPARTMENTS dep on dep.ID_DEP = pl.ID_DEP_RECEIVE
				inner join WF_TRANSITIONS tr on tr.ID_T = pl.ID_T
				inner join WF_TRANSITIONPOOLS tp on tr.ID_TP = tp.ID_TP
			WHERE
				hscv.ID_HSCV = ?
			ORDER BY ID_PL
			LIMIT 1,999
		 ";
		 $r = $db->query($sql,array($objectid));
		return $r->fetchAll();
	}
	static function RollBack($idhscv,$user_id,$nodelete=false){
		global $db;
		//Lấy log cuối cùng
		$sql="SELECT pl.*
		FROM
			".QLVBDHCommon::Table("WF_PROCESSLOGS")." pl
			INNER JOIN (SELECT max(ID_PL) as ID_PL FROM ".QLVBDHCommon::Table("WF_PROCESSLOGS")." GROUP BY ID_PI) t on t.ID_PL = pl.ID_PL
			INNER JOIN ".QLVBDHCommon::Table("WF_PROCESSITEMS")." p on pl.ID_PI = p.ID_PI
		WHERE
			ID_U_SEND = ? AND p.ID_O = ?
		";
		$r = $db->query($sql,array($user_id,$idhscv));
		if($r->rowCount()==1){
			$lastlog = $r->fetch();
			///return 0;
			if($lastlog['ISSPLIT']!=1 || $nodelete==true){
				$id_a_begin = $lastlog['ID_A_BEGIN'];
				$id_a_end = $lastlog['ID_A_END'];
				$id_p = $lastlog['ID_P'];
				$id_pi = $lastlog['ID_PI'];
				$id_pl = $lastlog['ID_PL'];
				$id_t = $lastlog['ID_T'];
				//update tre lai
				$lastpl = WFEngine::GetStartLogIdByEndAt($id_pi,$id_t);
				if(is_array($lastpl)){
					foreach($lastpl as $item){
						$db->update(QLVBDHCommon::Table("wf_processlogs"),array("TRE"=>NULL),"ID_PL=".$item['ID_PL']);
					}
				}
				//Xoa log
				$db->delete(QLVBDHCommon::Table("WF_PROCESSLOGS"),"ID_PL=".$id_pl);
				//Cap nhat lai process item
				$db->update(QLVBDHCommon::Table("WF_PROCESSITEMS"),array("ID_A"=>$id_a_begin,"ID_U"=>$user_id,"LASTCHANGE"=>date("Y-m-d H:i:s"),"IS_FINISH"=>0),"ID_PI=".$id_pi);
				//rollback lại log trạng thái hồ sơ
				//lay pre_seq trước
				$qr_presq = $db->query("select PRE_SEQ from ". QLVBDHCommon::Table("wf_trangthaihosologs")." where ID_HSCV = ? ORDER BY ID_TTHSLOG DESC ",array($idhscv));
				$pre_sqRe =  $qr_presq->fetch();
				$pre_sq = $pre_sqRe["PRE_SEQ"];
				$is_chobosung = $pre_sqRe["IS_CHOBOSUNG"];
				$db->delete(QLVBDHCommon::Table("wf_trangthaihosologs")," ACTIVE=1 and ID_HSCV=".$idhscv);
				$db->update(QLVBDHCommon::Table("wf_trangthaihosologs"), array("ACTIVE"=>1,"IS_CHOBOSUNG"=>$is_chobosung)," PRE_SEQ=".(int)($pre_sq-1)."  AND ID_HSCV=".$idhscv);
				WFEngine::updateTTHSMCBackward($idhscv,$id_t);
                return 1;



			}else{
				//xoa toan bo log
				$db->delete(QLVBDHCommon::Table("WF_PROCESSLOGS"),"ID_PI=".$lastlog['ID_PI']);
				//xoa item
				$db->delete(QLVBDHCommon::Table("WF_PROCESSITEMS"),"ID_PI=".$lastlog['ID_PI']);
				//xoa hscv
				$db->delete(QLVBDHCommon::Table("HSCV_HOSOCONGVIEC"),"ID_PI=".$lastlog['ID_PI']);
				return 2;
			}
		}else{
			return 0;
		}
	}
	static function CopyProcess($object_id,$name,$idreceive,$type){
		global $db;
		//copy hscv cu
		$sql = "
			SELECT
				*
			FROM ".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")."
			WHERE
				ID_HSCV = ?
		";
		$r = $db->query($sql,$object_id);
		$hscvold = $r->fetch();
		//Lay thong tin nguoi nhan
		$extra = "";
		if($type==WFEngine::$WFTYPE_USER){
			$sql="SELECT concat(emp.FIRSTNAME,' ',emp.LASTNAME) as FULLNAME FROM QTHT_USERS u INNER JOIN QTHT_EMPLOYEES emp on u.ID_EMP = emp.ID_EMP WHERE u.ID_U = ?";
			$r = $db->query($sql,$idreceive);
			$u = $r->fetch();
			$extra = $u['FULLNAME'];
		}else if($type==WFEngine::$WFTYPE_DEP){
			$sql="SELECT NAME FROM QTHT_DEPARTMENTS WHERE ID_DEP = ?";
			$r = $db->query($sql,$idreceive);
			$d = $r->fetch();
			$extra = $d['NAME'];
		}else if($type==WFEngine::$WFTYPE_GROUP){
			$sql="SELECT NAME FROM QTHT_GROUPS WHERE ID_G = ?";
			$r = $db->query($sql,$idreceive);
			$d = $r->fetch();
			$extra = $d['NAME'];
		}
		$db->insert(QLVBDHCommon::Table("HSCV_HOSOCONGVIEC"),array("ID_THUMUC"=>$hscvold['ID_THUMUC'],"ID_LOAIHSCV"=>$hscvold['ID_LOAIHSCV'],"NAME"=>$hscvold['NAME'],"EXTRA"=>$extra,"NGAY_BD"=>$hscvold['NGAY_BD'],"NGAY_KT"=>$hscvold['NGAY_KT']));
		$idhscv = $db->lastInsertId(QLVBDHCommon::Table("HSCV_HOSOCONGVIEC"));
		//copy process
		$sql = "
			SELECT
				*
			FROM ".QLVBDHCommon::Table("WF_PROCESSITEMS")."
			WHERE
				ID_PI = ?
		";
		$r = $db->query($sql,$hscvold['ID_PI']);
		$processold = $r->fetch();
		$db->insert(QLVBDHCommon::Table("WF_PROCESSITEMS"),array(
			"NAME"=>$name,
			"ID_O"=>$idhscv,
			"ID_A"=>$processold['ID_A'],
			"ID_P"=>$processold['ID_P'],
			"ID_U"=>$processold['ID_U'],
			"LASTCHANGE"=>$processold['LASTCHANGE'],
			"ID_G"=>$processold['ID_G'],
			"ID_DEP"=>$processold['ID_DEP']
		));
		$idpi = $db->lastInsertId(QLVBDHCommon::Table("WF_PROCESSITEMS"));
		//cap nhat lai hscv
		$db->update(QLVBDHCommon::Table("HSCV_HOSOCONGVIEC"),array("ID_PI"=>$idpi),"ID_HSCV=".$idhscv);
		//copy log
		$sql = "
			SELECT
				*
			FROM ".QLVBDHCommon::Table("WF_PROCESSLOGS")."
			WHERE
				ID_PI = ?
			ORDER BY ID_PL
		";
		$r = $db->query($sql,$processold['ID_PI']);
		$logold = $r->fetchAll();
		foreach($logold as $logitem){
			$db->insert(QLVBDHCommon::Table("WF_PROCESSLOGS"),array(
				"ID_U_SEND"=>$logitem['ID_U_SEND'],
				"ID_U_RECEIVE"=>$logitem['ID_U_RECEIVE'],
				"ID_PI"=>$idpi,
				"ID_T"=>$logitem['ID_T'],
				"DATESEND"=>$logitem['DATESEND'],
				"ID_A_BEGIN"=>$logitem['ID_A_BEGIN'],
				"ID_A_END"=>$logitem['ID_A_END'],
				"ID_P"=>$logitem['ID_P'],
				"HANXULY"=>$logitem['HANXULY'],
				"TRE"=>0,
				"NOIDUNG"=>$logitem['NOIDUNG'],
				"ID_G_RECEIVE"=>$logitem['ID_G_RECEIVE'],
				"ID_DEP_RECEIVE"=>$logitem['ID_DEP_RECEIVE']
			));
		}
		return $idhscv;
	}
	static function UpdateAfterCopy($idhscv){
		global $db;
		//Lay process item tu hscv
		$sql = "
			SELECT * FROM ".QLVBDHCommon::Table("WF_PROCESSITEMS")." WHERE ID_O = ?
		";
		$r = $db->query($sql,$idhscv);
		$pi = $r->fetch();
		$db->update(QLVBDHCommon::Table("WF_PROCESSLOGS"),array("ISSPLIT"=>1),"ID_PI=".$pi['ID_PI']);
	}
	static function UpdateExtra($idhscv,$idreceive,$type){
		global $db;
		$extra = "";
		if($type==WFEngine::$WFTYPE_USER){
			$sql="SELECT concat(emp.FIRSTNAME,' ',emp.LASTNAME) as FULLNAME FROM QTHT_USERS u INNER JOIN QTHT_EMPLOYEES emp on u.ID_EMP = emp.ID_EMP WHERE u.ID_U = ?";
			$r = $db->query($sql,$idreceive);
			$u = $r->fetch();
			$extra = $u['FULLNAME'];
		}else if($type==WFEngine::$WFTYPE_DEP){
			$sql="SELECT NAME FROM QTHT_DEPARTMENTS WHERE ID_DEP = ?";
			$r = $db->query($sql,$idreceive);
			$d = $r->fetch();
			$extra = $d['NAME'];
		}else if($type==WFEngine::$WFTYPE_GROUP){
			$sql="SELECT NAME FROM QTHT_GROUPS WHERE ID_G = ?";
			$r = $db->query($sql,$idreceive);
			$d = $r->fetch();
			$extra = $d['NAME'];
		}
		$db->update(QLVBDHCommon::Table("HSCV_HOSOCONGVIEC"),array("EXTRA"=>$extra),"ID_HSCV=".$idhscv);
	}
	static function IsFinishTransition($transition_id){
		global $db;
		//lay activity end
		$sql = "SELECT * FROM WF_TRANSITIONS WHERE ID_T = ? AND ISLAST=1";
		$r = $db->query($sql,$transition_id);
		if($r->rowCount()==0)return false;
		return true;
	}
	static function GetHanXuLy($transition_id){
		global $db;
		$r = $db->query("SELECT HANXULY FROM WF_TRANSITIONS WHERE ID_T=?",$transition_id);
		$hanxuly = $r->fetch();
		$hanxuly = $hanxuly['HANXULY'];
		return $hanxuly;
	}
	static function GetAccessUserFromTransition($transition_id){
		global $db;
		$r = $db->query("
			SELECT
				0 as TYPE, DEP.ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
			FROM
				WF_ACTIVITYACCESSES AC
				INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_BEGIN
				INNER JOIN QTHT_DEPARTMENTS DEP ON DEP.ID_DEP = AC.ID_DEP
				INNER JOIN QTHT_EMPLOYEES E ON E.ID_DEP = DEP.ID_DEP
				INNER JOIN QTHT_USERS U ON U.ID_EMP = E.ID_EMP
			WHERE
				TR.ID_T=?
			UNION ALL
			SELECT
				0 as TYPE, '-1' AS ID_DEP, U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
			FROM
				WF_ACTIVITYACCESSES AC
				INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_BEGIN
				INNER JOIN QTHT_USERS U ON AC.ID_U=U.ID_U
				INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
			WHERE TR.ID_T=?
			UNION ALL
			SELECT
				1 as TYPE, DEP.ID_DEP,U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
			FROM
				WF_ACTIVITYACCESSES AC
				INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_END
				INNER JOIN QTHT_DEPARTMENTS DEP ON DEP.ID_DEP = AC.ID_DEP
				INNER JOIN QTHT_EMPLOYEES E ON E.ID_DEP = DEP.ID_DEP
				INNER JOIN QTHT_USERS U ON U.ID_EMP = E.ID_EMP
			WHERE
				TR.ID_T=?
			UNION ALL
			SELECT
				1 as TYPE, '-1' AS ID_DEP, U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
			FROM
				WF_ACTIVITYACCESSES AC
				INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_END
				INNER JOIN QTHT_USERS U ON AC.ID_U=U.ID_U
				INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
			WHERE TR.ID_T=?
			ORDER BY NAME
		",array($transition_id,$transition_id,$transition_id,$transition_id));
		$user = $r->fetchAll();
		return $user;
	}
	static function GetAccessUserFromTransitionNoGroup($transition_id){
		global $db;
		$r = $db->query("
			SELECT
				'1' as type, U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
			FROM
				WF_ACTIVITYACCESSES AC
				INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_END
				INNER JOIN QTHT_USERS U ON AC.ID_U=U.ID_U
				INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
			WHERE TR.ID_T=?
			UNION
			SELECT
				'2' as type, U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
			FROM
				WF_ACTIVITYACCESSES AC
				INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_BEGIN
				INNER JOIN QTHT_DEPARTMENTS DEP ON DEP.ID_DEP = AC.ID_DEP
				INNER JOIN QTHT_EMPLOYEES E ON E.ID_DEP = DEP.ID_DEP
				INNER JOIN QTHT_USERS U ON U.ID_EMP = E.ID_EMP
			WHERE
				TR.ID_T=?
			UNION
			SELECT
				'2' as type, U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
			FROM
				WF_ACTIVITYACCESSES AC
				INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_BEGIN
				INNER JOIN QTHT_USERS U ON AC.ID_U=U.ID_U
				INNER JOIN QTHT_EMPLOYEES E ON E.ID_EMP = U.ID_EMP
			WHERE TR.ID_T=?
			UNION
			SELECT
				'2' as type, U.ID_U,CONCAT(E.FIRSTNAME , ' ' , E.LASTNAME) as NAME
			FROM
				WF_ACTIVITYACCESSES AC
				INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_END
				INNER JOIN QTHT_DEPARTMENTS DEP ON DEP.ID_DEP = AC.ID_DEP
				INNER JOIN QTHT_EMPLOYEES E ON E.ID_DEP = DEP.ID_DEP
				INNER JOIN QTHT_USERS U ON U.ID_EMP = E.ID_EMP
			WHERE
				TR.ID_T=?
			ORDER BY TYPE,NAME
		",array($transition_id,$transition_id,$transition_id,$transition_id));
		$user = $r->fetchAll();
		return $user;
	}
	static function GetAccessDepFromTransition($transition_id){
		global $db;
		$r = $db->query("
		SELECT
			0 as TYPE, DEP.ID_DEP,DEP.NAME,DEP.ID_U_DAIDIEN
		FROM
			WF_ACTIVITYACCESSES AC
			INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_BEGIN
			INNER JOIN QTHT_DEPARTMENTS DEP ON AC.ID_DEP=DEP.ID_DEP
		WHERE
			TR.ID_T=?
		UNION ALL
		SELECT
			1 as TYPE, DEP.ID_DEP,DEP.NAME,DEP.ID_U_DAIDIEN
		FROM
			WF_ACTIVITYACCESSES AC
			INNER JOIN WF_TRANSITIONS TR ON AC.ID_A=TR.ID_A_END
			INNER JOIN QTHT_DEPARTMENTS DEP ON AC.ID_DEP=DEP.ID_DEP
		WHERE
			TR.ID_T=?
		ORDER BY NAME
		",array($transition_id,$transition_id));
		$dep = $r->fetchAll();
		return $dep;
	}
	static function Test($x){
		if($x==1) return true;
		return false;
	}
	static function CanChuyenLaiForVTBP($object_id){
		global $db;
		$r = $db->query("
		SELECT
			count(*) as CNT
		FROM
			".QLVBDHCommon::Table("WF_PROCESSITEMS")." pi
			inner join ".QLVBDHCommon::Table("WF_PROCESSLOGS")." pl on pi.ID_PI = pl.ID_PI
		WHERE
			pi.ID_O = ?
		",array($object_id));
		$dep = $r->fetch();
		if($dep["CNT"]==3)return true;
		return false;
	}
	static function CanBP($object_id){
		global $db;
		$r = $db->query("
		SELECT
			count(*) as CNT
		FROM
			".QLVBDHCommon::Table("WF_PROCESSITEMS")." pi
			inner join ".QLVBDHCommon::Table("WF_PROCESSLOGS")." pl on pi.ID_PI = pl.ID_PI
		WHERE
			pi.ID_O = ?
		",array($object_id));
		$dep = $r->fetch();
		if($dep["CNT"]==2)return true;
		return false;
	}
	static function GetBPTransition($object_id){
		global $db;
		$r = $db->query("
		SELECT
			pl.*
		FROM
			".QLVBDHCommon::Table("WF_PROCESSITEMS")." pi
			inner join ".QLVBDHCommon::Table("WF_PROCESSLOGS")." pl on pi.ID_PI = pl.ID_PI
		WHERE
			pi.ID_O = ?
		ORDER BY ID_PL
		",array($object_id));
		$dep = $r->fetch();
		$dep = $r->fetch();
		return $dep["ID_T"];
	}
	static function GetBPInfo($object_id){
		global $db;
		$r = $db->query("
		SELECT
			pl.*
		FROM
			".QLVBDHCommon::Table("WF_PROCESSITEMS")." pi
			inner join ".QLVBDHCommon::Table("WF_PROCESSLOGS")." pl on pi.ID_PI = pl.ID_PI
		WHERE
			pi.ID_O = ?
		ORDER BY ID_PL
		",array($object_id));
		$dep = $r->fetch();
		$dep = $r->fetch();
		return $dep;
	}
	static function ChangeUBP($object_id,$idu){
		global $db;
		$db->update(QLVBDHCommon::Table("WF_PROCESSITEMS"),array("ID_U"=>$idu),"ID_O=".$object_id);
		$bpinfo = WFEngine::GetBPInfo($object_id);
		$db->update(QLVBDHCommon::Table("WF_PROCESSLOGS"),array("ID_U_RECEIVE"=>$idu),"ID_PL=".$bpinfo["ID_PL"]);
	}
	/*
	* trunglv
	*
	*/
	static function getAllTrangthaihosoByCurrentUser(){
		$auth = Zend_Registry::get('auth');
		$user = $auth->getIdentity();
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select * from wf_trangthaihoso tths
			inner join wf_trangthaihoso_group fk on tths.ID_TTHS = fk.ID_TTHS
			inner join fk_users_groups fk_ug on fk.ID_G = fk_ug.ID_G
			where fk_ug.ID_U = ?
		";
		$qr = $db->query($sql,array($user->ID_U));
		return $qr->fetchAll();
	}
	static function thongkesoluonghosotrentrangthaiByCurrentUser(){
		$auth = Zend_Registry::get('auth');
		$user = $auth->getIdentity();
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select count(*) as CNT, ID_TTHS
			from ".QLVBDHCommon::Table("wf_trangthaihosologs")." where ACTIVE=1 and ID_U = ?
			group by ID_TTHS
		";
		$qr = $db->query($sql,array($user->ID_U));
		$re = $qr->fetchAll();
		//var_dump($re);
		$array = array();
		$num_bs = 0;
		foreach($re as $it){
			$array[$it["ID_TTHS"]] = $it["CNT"];
		}
		//dem ho so cho bo sung
		$sql_chobosung = " select count(distinct ID_HSCV) as CNT
			from ".QLVBDHCommon::Table("wf_trangthaihosologs")." where IS_CHOBOSUNG=1 and ID_U = ?
			";
		$qr = $db->query($sql_chobosung,array($user->ID_U));
		$re = $qr->fetch();
		$arr_tths = WFEngine::getAllTrangthaihosoByCurrentUser();
		$count_bshs = 0;
		foreach($arr_tths as $it_tths){
			if($it_tths["LA_CHOBOSUNG"] && !$it_tths["THUOCTOMOTCUA"] ){
				$array[$it_tths["ID_TTHS"]] = $re["CNT"];
			}
			if($it_tths["LA_CHOBOSUNG"] && $it_tths["THUOCTOMOTCUA"]){
				if(!$count_bshs)
					$count_bshs = WFEngine::countBosunghoso();
				$array[$it_tths["ID_TTHS"]] = $count_bshs;
			}
		}
		//countBosunghoso()
		return $array;
	}
	static function countBosunghoso(){
		global $db;
		$auth = Zend_Registry::get('auth');
		$user = $auth->getIdentity();
		$sql = "
			SELECT count(distinct hscv.ID_HSCV) as DEM FROM
				".QLVBDHCommon::Table("HSCV_HOSOCONGVIEC")." hscv
			inner join ".  QLVBDHCommon::Table("MOTCUA_HOSO") ." mc on hscv.ID_HSCV =  mc.ID_HSCV
			inner join motcua_loai_hoso loaihs on mc.ID_LOAIHOSO = loaihs.ID_LOAIHOSO
			inner join ( select lv.* from motcua_linhvuc lv inner join motcua_linhvuc_quyen pq on lv.ID_LV_MC = pq.ID_LV_MC   where ID_U = ?)
			linhvuchs on loaihs.ID_LV_MC = linhvuchs.ID_LV_MC
			inner join ".  QLVBDHCommon::Table("HSCV_PHIEU_YEUCAU_BOSUNG") ." bs on hscv.ID_HSCV = bs.ID_HSCV
			WHERE hscv.IS_BOSUNG = 1
			$where
		";
		try{
			$r = $db->query($sql,array($user->ID_U));
			$result = $r->fetch();
		}catch(Exception $ex){
			return 0;
		}
		//return 0;
		return $result["DEM"];
	}
	static function getTrangthaihosoByIdhsv($id_hscv){
		$auth = Zend_Registry::get('auth');
		$user = $auth->getIdentity();
		$db = Zend_Db_Table::getDefaultAdapter();

		$sql = "
			select ID_TTHS, ACTIVE
			from ".QLVBDHCommon::Table("wf_trangthaihosologs")." where  ID_U = ?
			and ID_HSCV = ?
			ORDER BY ID_TTHSLOG
		";
		$qr = $db->query($sql,array($user->ID_U,$id_hscv));
		$re = $qr->fetchAll();
		if(count($re) == 0){
			return -2; //hồ sơ không qua user xử lý
		}else{
			foreach($re as $it){
				if($it["ACTIVE"] == 1)
					return $it["ID_TTHS"];
			}
		}
		return -1; // trạng thái đã xử lý
	}
	static function getCountHosoDaxulyByCurrentUser(){
		$auth = Zend_Registry::get('auth');
		$user = $auth->getIdentity();
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = "
			select ( count(distinct ID_HSCV) ) as CNT
			from ".QLVBDHCommon::Table("wf_trangthaihosologs")." where ACTIVE=0 and ID_U = ?
		";
		$qr = $db->query($sql,array($user->ID_U));
		$re = $qr->fetch();
		return $re["CNT"];
	}
	static function getListIdHscvVuaBS(){
		$user = Zend_Registry::get('auth')->getIdentity();
		$sql = " select distinct pyc.ID_HSCV from  ". QLVBDHCommon::Table("hscv_phieu_yeucau_bosung") ." pyc
		where pyc.NGUOIYEUCAU=? and pyc.NGUOIBOSUNG > 0 and  (  pyc.DA_XEM_HDBS is NULL or pyc.DA_XEM_HDBS  = 0 )
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($user->ID_U));
			return $qr->fetchAll();
		}catch(Exception $ex){
			return array();
		}
	}

    static function getTransitionInfo($id_w_t){
        try{
            $db = Zend_Db_Table::getDefaultAdapter();
            $stm = $db->query("select * from wf_transitions where ID_T=?",array($id_w_t));
            return $stm->fetch();
         }catch(Exception $ex){
            return array();
         }
    }

    //Cập nhật trạng thái hồ sơ
    // Cập nhật trạng thái một cửa
        /*
         Thuat toan luu log

            Nếu hồ sơ chưa kết thúc
                + So sanh ngay hien tai voi ngay hẹn tra :
                    => tinh trang thai dang xu ly ( tre, chua toi han)
                    => cap nhat trang thai ho so
            Neu ho so da ket thuc
                + So sanh ngay hen tra va ngay hien tai
                    => cap nhat tinh trang da xu ly
                    => cap nhat trang thai ho so
         Thuat toan rollback
            Neu ho so chua ket thuc
                + thoi khong thuc hien
            Neu ho so da giai quyet
                + Cập nhật lại trạng thái hồ sơ la dang xu ly
                Tinh lai trang thai cua dang xu ly
        */


    static function updateTTHSMCForward($id_hscv,$transition_id,$is_finish){
             if(isset($is_finish)){
                $is_finish = WFEngine::IsFinishTransition($transition_id);
             }
             //var_dump($mcinfo); exit;
             //lay du lieu cua ho so
             $db = Zend_Db_Table::getDefaultAdapter();
             $stmmc =  $db->query(" select NHAN_NGAY , NHANLAI_NGAY , TRA_NGAY from ". QLVBDHCommon::Table("motcua_hoso")." mc where ID_HSCV=? ", (int)$id_hscv );
             $mcinfo = $stmmc->fetch();
             // tinh han tre
             if($mcinfo["NHANLAI_NGAY"]){
                 $ngayconlai = (int)QLVBDHCommon::countdatesongayconlai(strtotime($mcinfo["NHANLAI_NGAY"]));
                 if($ngayconlai < 0)
                     $tre = 1;
                 else if($ngayconlai == 0)
                     $tre = 0;
                 else if($ngayconlai == 0)
                     $tre = -1;

                 if($is_finish){
                    $year = date("Y");
                    $month = date("m");
                    $db->update(QLVBDHCommon::Table("motcua_hoso"),array("THANG_TRAHOSO"=>$month,"NAM_TRAHOSO"=>$year,"IS_DAGIAIQUYET"=>(int)$is_finish,"SONGAYCONLAI"=>$ngayconlai,"TRE"=>$tre ),"ID_HSCV=".(int)$id_hscv);

                 }else{

                        $db->update(QLVBDHCommon::Table("motcua_hoso"),array("IS_DAGIAIQUYET"=>(int)$is_finish,"SONGAYCONLAI"=>$ngayconlai,"TRE"=>$tre), "ID_HSCV=".(int)$id_hscv);

                 }
              }



    }
    ///Rollback
    static function updateTTHSMCBackward($id_hscv,$transition_id,$is_finish){
         if(isset($is_finish)){
                $is_finish = WFEngine::IsFinishTransition($transition_id);
         }
         if($is_finish){
               WFEngine::updateForward($id_hscv,$transition_id,0);


         }

    }
}