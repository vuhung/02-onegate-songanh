<?php
$link = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DB_DATABASE) or die('Could not select database');
//mysql_query("SET character_set_results=utf8", $link);
//mysql_query("set names 'utf8'",$link);

function query($sql){
	return mysql_query($sql);
}