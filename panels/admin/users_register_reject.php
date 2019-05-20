<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	
    
    $id = $request->PostInt('id');
    $email = $request->PostStr('email');
    $username = $request->PostStr('username');
    
    $sql = 'delete from  ' . USER_REGISTER_MASTER . ' where id=' . $mysql->getInt($id);
    $query = $mysql->query($sql);
	
	$objEmail = new email();
	
	$email_config 		= $objEmail->getEmailSettings();
	$from_admin 		= $email_config['system_email'];
	$admin_from_disp	= $email_config['system_from'];

	
	$args = array(
			'to' => $email,
			'from' => $from_admin ,
			'fromDisplay' => $admin_from_disp,
			'username' => $username,
			'site_admin' => $admin_from_disp,
			'send_mail' => true
			);
	$objEmail->sendEmailTemplate('admin_user_register_reject', $args);
	
	
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "users_register.html?reply=" . urlencode('reply_ragister'));
	exit();
    
    
	
?>