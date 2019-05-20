<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_tools_users_1812jdf18196');
	
	$tool_id=$request->PostInt('tool_id');
	if(isset($_POST['check_user'])){
		$check_user=$_POST['check_user'];
	} else {
		$check_user= array();
	}
	
	
	$sql='delete from ' . IMEI_TOOL_USERS . ' where tool_id=' . $tool_id;
	$mysql->query($sql);
	
	if(is_array($check_user))
	{
		foreach($check_user as $user_id)
		{
			$sql='insert into ' 	. IMEI_TOOL_USERS . ' (tool_id, user_id) values(' . $tool_id . ', ' . $user_id . ')';
			$mysql->query($sql);
		}
	}
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_tools_users.html?id=" . $tool_id . "&reply=" . urlencode('reply_users_updated_successfully'));
	exit();

?>