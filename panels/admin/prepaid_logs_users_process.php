<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('prepaid_logs_users_98349t57hjng94750');
	
	$prepaid_log_id=$request->PostInt('prepaid_log_id');
	$check_user=$_POST['check_user'];
	
	$sql='delete from ' . PREPAID_LOG_USERS . ' where prepaid_log_id=' . $prepaid_log_id;
	$mysql->query($sql);
	
	
	foreach($check_user as $user_id)
	{
		$sql='insert into ' 	. PREPAID_LOG_USERS . ' (prepaid_log_id, user_id) values(' . $prepaid_log_id . ', ' . $user_id . ')';
		$mysql->query($sql);
	}
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_users.html?id=" . $prepaid_log_id . "&reply=" . urlencode('reply_users_updated_successfully'));
	exit();

?>