<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('user_edit_789971255d2');

    $id = $request->GetInt('id');
    $enable = $request->GetInt('enable');
	

	// to update the status as enable or disable without allotment
	$sql = 'update ' . USER_MASTER . '
			set 
			service_prepaid = ' . $mysql->getInt($enable) . '
			where id = ' . $mysql->getInt($id);
	$mysql->query($sql);
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "users_edit.html?id=" . $id. "&reply=" . urlencode('reply_profile_update'));
	exit();
?>