<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('con_log_log_group_14887372');

    $id = $request->PostInt('id');
    $group = $request->PostStr('group');
    $status = $request->PostInt('status');

	$sql = 'insert into ' . SERVER_LOG_GROUP_MASTER . ' (group_name, status)
			 values
			 (' . $mysql->quote($group) . ', 
			 ' . $mysql->getInt($status) . ')';
	$mysql->query($sql);
	
	$sql = 'update ' . SERVER_LOG_GROUP_MASTER . ' set display_order=id where display_order=0';
	$mysql->query($sql);
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "server_logs_group.html?reply=" . urlencode('reply_add_success'));
	
	
	
?>