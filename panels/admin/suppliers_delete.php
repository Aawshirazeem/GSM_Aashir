<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	
	$id=$request->getInt('id');
	
	$sql = 'delete from ' . SUPPLIER_MASTER . ' where id=' . $id;
	$query = $mysql->query($sql);

	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers.html?reply=" . urlencode("reply_delete_success"));
	exit();
