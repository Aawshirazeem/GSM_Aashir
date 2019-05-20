<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('server_logs_users_jfiu47$9trj494r');
	
	$log_id=$request->PostInt('log_id');
	$check_user=$_POST['check_user'];
	
	$sql='delete from ' . SERVER_LOG_USERS . ' where log_id=' . $log_id;
	$mysql->query($sql);
	
	
	foreach($check_user as $user_id)
	{
		$sql='insert into ' 	. SERVER_LOG_USERS . ' (log_id, user_id) values(' . $log_id . ', ' . $user_id . ')';
		$mysql->query($sql);
	}
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "server_logs_users.html?id=" . $log_id . "&reply=" . urlencode('reply_users_updated_successfully'));
	exit();

?>