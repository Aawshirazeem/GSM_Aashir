<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('con_pre_log_log_un_148837312');

    $prepaid_log_un = $request->PostStr('prepaid_log_un');
    $prepaid_log_id = $request->PostInt('prepaid_log_id');

	if (defined("DEMO"))
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_un.html?id=" . $prepaid_log_id . "reply=" . urlencode('reply_com_demo'));
		exit();
	}
		
	if($prepaid_log_un == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_un_add.html?id=" . $prepaid_log_id . "&reply=" . urlencode('reply_prepaid_logs_un_missing'));
		exit();
	}
	
	$uns = explode("\n", $prepaid_log_un);

	foreach($uns as $un)
	{
		$un = trim($un);
		if($un != "")
		{
			$sql = 'insert into ' . PREPAID_LOG_UN_MASTER . '
					(prepaid_log_id, username, date_created, status)
					values(
					' . $mysql->getInt($prepaid_log_id) . ',
					' . $mysql->quote($un) . ',
					now(),
					0)';
			$mysql->query($sql);
		}
	}
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_un.html?id=" . $prepaid_log_id . "&reply=" . urlencode('reply_add_success'));
	exit();
?>