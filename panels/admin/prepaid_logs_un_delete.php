<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();

    $id = $request->GetInt('id');
    $prepaid_log_id = $request->GetInt('prepaid_log_id');

	$sql ='delete from ' . PREPAID_LOG_UN_MASTER . ' where id=' . $id;
	$mysql->query($sql);

	$mysql->query($sql);
	header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_un.html?id=" . $prepaid_log_id . "&reply=" . urlencode('reply_delete_success'));
?>