<?php

function checkAuthentication($username, $password){
	$sql = sprintf("
		select count(*) as DEM from adapter_users where USERNAME = '%s' and PASSWORD = '%s'
	",mysql_real_escape_string($username),mysql_real_escape_string($password));
	$result = query($sql);
	$row = mysql_fetch_assoc($result);
	$dem = $row["DEM"];
	if($dem)
		return true;
	else
		return false;
}

