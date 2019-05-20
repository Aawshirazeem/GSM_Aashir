<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('user_group_add_54964566f45');
	
	$group_id = $request->PostInt('group_id');
    $group_name = $request->PostStr('group_name');
    $status = $request->PostCheck('status');
		
	if($group_name == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "users_group_add.html?reply=" . urlencode('reply_group_missing'));
		exit();
	}
	
	$sql = 'select group_name from ' . USER_GROUP_MASTER . ' where group_name=' . $mysql->quote($group_name);
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "users_group_add.html?reply=" . urlencode('reply_group_user_duplicate'));
		exit();
	}
	

	$keyword = new keyword();
    $key = $request->GetStr('key');
    
    $keyNew = $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4);
	$keyNew = strtoupper($keyNew);
	
	$sql = 'insert into ' . USER_GROUP_MASTER . '
			(group_name,status)
		values(
			' . $mysql->quote($group_name) . ',
				status=' . $status .')';

	$mysql->query($sql);
	
/*	$id = $mysql->insert_id();
	
	$objEmail = new email();
	$args = array(
				'to' => $email,
				'from' => CONFIG_EMAIL,
				'fromDisplay' => CONFIG_SITE_NAME,
				'user_id' => $id,
				'save' => '1',
				'username' => $username,
				'password' => $password,
				'site_admin' => CONFIG_SITE_NAME
				);

	$objEmail->sendEmailTemplate('admin_supplier_add', $args);
	*/
	header("location:" . CONFIG_PATH_SITE_ADMIN . "users_group.html?reply=" . urlencode('lbl_add_success'));
	exit();ss	
?>