<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('prepaid_logs_un_edit_14887312');

    $id = $request->PostInt('id');
    $prepaid_log_id = $request->PostInt('prepaid_log_id');
    $username = $request->PostStr('username');
		
	if($username == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_un_edit.html?id=" . $id . "&prepaid_log_id=" . $prepaid_log_id . "&reply=" . urlencode('reply_prepaid_logs_missing'));
		exit();
	}
	
	$sql_chk ='select * from ' . PREPAID_LOG_UN_MASTER . ' where username=' . $mysql->quote($username) . ' and prepaid_log_id=' . $prepaid_log_id . ' and id!=' . $id;
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{
		$sql = 'update
					' . PREPAID_LOG_UN_MASTER . '
				set
					username = ' . $mysql->quote($username) . '
				where id=' . $mysql->getInt($id);
		$mysql->query($sql);
		header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_un.html?id=" . $prepaid_log_id . "&reply=" . urlencode('reply_update_success'));
	}
	else
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_edit.html?id=" . $id . "&prepaid_log_id=" . $prepaid_log_id . "&reply=" . urlencode('reply_prepaid_logs_duplicate'));
		exit();
	}
	
	
	
	
?>