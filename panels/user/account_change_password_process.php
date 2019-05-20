<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	if (defined("DEMO"))
	{
		header("location:" . CONFIG_PATH_SITE_USER . "account_change_password.html?reply=" . urlencode('reply_com_demo'));
		exit();
	}
	
	
	$member->checkLogin();
	$member->reject();
	$validator->formValidateUser('user_pass_chan_148148548');

    $password_old = $request->PostStr('password_old');
    $password = $request->PostStr('password');
    $password2 = $request->PostStr('password2');
    
    
    $sql = 'select * from ' . USER_MASTER . ' where password=' . $mysql->quote($objPass->generate($password_old)) . ' and username=' . $mysql->quote($member->getUsername());
    $query = $mysql->query($sql);
    
    if($mysql->rowCount($query) > 0)
    {
		$rows=$mysql->fetchArray($query);
		$email=$rows[0]['email'];
		$username=$rows[0]['username'];
		$sql = 'update ' . USER_MASTER . '
					set
					password=' . $mysql->quote($objPass->generate($password)) . '
					where id=' . $mysql->getInt($member->getUserId());
		$mysql->query($sql);
		
		
		$objEmail = new email();
		$args = array(
						'to' => $email,
						'from' => CONFIG_EMAIL,
						'fromDisplay' => CONFIG_SITE_NAME,
						'user_id' => $member->getUserid(),
						'save' => '1',
						'username' => $username,
						'password' => $password,
						'site_admin' => CONFIG_SITE_NAME
						);
						
		$objEmail->sendEmailTemplate('user_edit_login_details', $args,$send_mail=TRUE);
		
		$member->logout();
		header("location:" . CONFIG_PATH_SITE_USER . "index.html?reply=" . urlencode('reply_pass_update'));
		exit();
	}
	else
	{
		header("location:" . CONFIG_PATH_SITE_USER . "account_change_password.html?reply=" . urlencode('reply_invalid_pass'));
		exit();
	}
	
?>