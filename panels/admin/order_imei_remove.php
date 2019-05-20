<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	
	$id=$request->getInt('id');
	
	$sql = 'delete from ' . ORDER_IMEI_MASTER . ' where id=' . $id;
	$query = $mysql->query($sql);

	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "order_imei.html?reply=" . urlencode("reply_update_success"));
	exit();
