<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	
	$id=$request->getInt('id');
	
	$sql = 'delete from ' . IMEI_TOOL_MASTER . ' where id=' . $id;
	$query = $mysql->query($sql);

	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_tools.html?reply=" . urlencode("reply_update_success"));
	exit();
