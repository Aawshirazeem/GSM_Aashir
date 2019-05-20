<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	

    $username = $request->PostStr('username');
    $email = $request->PostStr('email');

		
	if($username == "")
	{
		header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode('reply_user_miss'));
		exit();
	}
	if($email == "")
	{
		header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode('reply_email_miss'));
		exit();
	}
	$keyword = new keyword();
	$newPass = $keyword->generate(5);
	
	$mysql = new mysql();
	
	$sql = 'select id from ' . USER_MASTER . ' where username=' . $mysql->quote($username) . ' and email=' . $mysql->quote($email);
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$userId = $rows[0]['id'];
		
		$sql = 'update ' . USER_MASTER . ' set password=' . $mysql->quote($objPass->generate($newPass)) . ' where id=' . $mysql->getInt($userId);
		$mysql->query($sql);
		
		$body = '
					<h2>New Password</h2>
					<p>Your new password is : <b>' . $newPass . '</b><p>';
		
		$emailObj = new email();
		$email_config 		= $emailObj->getEmailSettings();
		$from_admin 		= $email_config['system_email'];
		$admin_from_disp	= $email_config['system_from'];
		
		$emailObj->setTo($email);
		$emailObj->setFrom($from_admin);
		$emailObj->setFromDisplay($admin_from_disp);
		$emailObj->setSubject("New Password");
		$emailObj->setBody($body);
		$emailObj->sendMail();
		
		header('location:' . CONFIG_PATH_SITE . 'user/index.html?reply=' . urlencode('reply_pass_reset'));
		exit();	
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE . 'user/index.html?reply=' . urlencode('reply_invalid_user'));
		exit();	
	}
	
	
?>