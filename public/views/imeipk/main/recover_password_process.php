<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin = new admin();
	$request = new request();
	$mysql = new mysql();
	$member = new member();
	

    $username = $request->PostStr('username');
    $email = $request->PostStr('email');

		
	if($username == "")
	{
		header("location:" . CONFIG_PATH_SITE . "login.html?msg=" . urlencode("Please enter your username"));
		exit();
	}
	if($email == "")
	{
		header("location:" . CONFIG_PATH_SITE . "login.html?msg=" . urlencode("Please enter your email"));
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
		
		$sql = 'update ' . USER_MASTER . ' set password=' . $mysql->quote(md5($newPass)) . ' where id=' . $mysql->getInt($userId);
		$mysql->query($sql);
		
		$body = '
					<h2>New Password</h2>
					<p>Your new password is : <b>' . $newPass . '</b><p>';
		
		$emailObj = new email();
		$emailObj->setTo($email);
		$emailObj->setFrom(CONFIG_EMAIL);
		$emailObj->setFromDisplay(CONFIG_EMAIL);
		$emailObj->setSubject("New Password");
		$emailObj->setBody($body);
		//$emailObj->sendMail();
                 $save = $emailObj->queue();
		
		header('location:' . CONFIG_PATH_SITE . 'login.html?msg=' . urlencode('Your password has been reset. An e-mail has been sent on your registed email.'));
		exit();	
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE . 'login.html?msg=' . urlencode('Invalid username or email!.'));
		exit();	
	}
	
	
?>