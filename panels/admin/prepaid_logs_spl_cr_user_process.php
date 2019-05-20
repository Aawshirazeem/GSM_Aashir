<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('prepaid_logs_spl_cr_user_list_7895971255d2');
	
	$id=$request->PostInt('id');
	$user_ids=$_POST['user_ids'];
	
	$check_user= (isset($_POST['check_user'])) ? $_POST['check_user'] : array();
	
	$sql='delete from ' . PREPAID_LOG_SPL_CREDITS . ' where log_id=' . $id;
	$mysql->query($sql);
	
	$sqlInsert = $sqlDel = '';
	foreach($user_ids as $user_id)
	{
		$splCr=$_POST['spl_' . $user_id];
		
		if($splCr!='' && !in_array($user_id, $check_user))
		{
			$sqlInsert .= 'insert into ' . PREPAID_LOG_SPL_CREDITS . '
						(amount, log_id, user_id)
						values(' . $mysql->getfloat($splCr) . ', ' . $id . ', ' . $user_id .');';
			$sqlDel .= 'delete from ' . PACKAGE_USERS . ' where user_id='. $user_id  . ';';
		}
	}
	if($sqlInsert != '')
	{
		$mysql->multi_query($sqlInsert);
	}
	if($sqlDel != '')
	{
		$mysql->multi_query($sqlDel);
	}
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "prepaid_logs_spl_cr_user_list.html?id=" . $id . "&reply=" . urlencode('reply_prepaid_log_update_success'));
	exit();

?>