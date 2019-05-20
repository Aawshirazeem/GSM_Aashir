<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('user_group_edit_54964566h34');
	
    $group_id = $request->PostInt('group_id');
    $group_name = $request->PostStr('group_name');
    $status = $request->PostCheck('status');
	
	
	if($group_name == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "users_group_edit.html?group_id=" . $group_id . "&reply=" . urlencode('reply_group_missing'));
		exit();
	}

	
	$sql='update ' . USER_GROUP_MASTER . ' 
					set 
						group_name=' . $mysql->quote($group_name) . ', 
						status=' . $status . '
					where id=' . $group_id;
	$mysql->query($sql);

	header("location:" . CONFIG_PATH_SITE_ADMIN . "users_group.html?reply=" . urlencode('lbl_edit_success'));
	exit();	
?>