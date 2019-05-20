<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_file_users_185158&9jnf8');
	
	$service_id=$request->PostInt('service_id');
	$check_user=$_POST['check_user'];
	
	$sql='delete from ' . FILE_SERVICE_USERS . ' where service_id=' . $service_id;
	$mysql->query($sql);
	
	
	foreach($check_user as $user_id)
	{
		$sql='insert into ' 	. FILE_SERVICE_USERS . ' (service_id, user_id) values(' . $service_id . ', ' . $user_id . ')';
		$mysql->query($sql);
	}
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file_users.html?id=" . $service_id . "&reply=" . urlencode('reply_users_updated_successfully'));
	exit();

?>