<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_group_add_54009475632');

    $id = $request->PostInt('id');
    $group = $request->PostStr('group');
    $status = $request->PostInt('status');

	$sql = 'insert into ' . IMEI_GROUP_MASTER . ' (group_name, status)
			 values
			 (' . $mysql->quote($group) . ',
			 ' . $mysql->getInt($status) . ')';
	$mysql->query($sql);
	
	$sql = 'update ' . SERVER_LOG_GROUP_MASTER . ' set display_order=id where display_order=0';
	$mysql->query($sql);
	$imei_group=$group;
	$args = array(
			'to' => CONFIG_EMAIL,
			'from' => CONFIG_EMAIL,
			'fromDisplay' => CONFIG_SITE_NAME,
			'imei_group'=>$imei_group,
			'site_admin' => CONFIG_SITE_NAME
			);
	$objEmail->sendEmailTemplate('admin_add_imei_group', $args);
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_group.html?reply=" . urlencode('reply_success_add'));
	exit();
?>