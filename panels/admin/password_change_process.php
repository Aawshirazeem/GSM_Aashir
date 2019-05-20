<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	$id=$request->PostInt('id');
	if (defined("DEMO"))
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "password_change.html?reply=" . urlencode('reply_com_demo'));
		exit();
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('co_pass_change_9999923487');


    $admin_id = $request->PostStr('admin_id');
    $email = $request->PostStr('email');
    $username = $request->PostStr('username');
    $password_old = $request->PostStr('password_old');
    $password = $request->PostStr('password');
    $password2 = $request->PostStr('password2');
    //echo $email.'<br>';
    
    $sql = 'select * from ' . ADMIN_MASTER . ' where password=' . $mysql->quote($objPass->generate($password_old)) . ' and username=' . $mysql->quote($admin->getUsername());
    $query = $mysql->query($sql);
    
    if($mysql->rowCount($query) > 0)
    {
		$sql = 'update ' . ADMIN_MASTER . '
					set
					password=' . $mysql->quote($objPass->generate($password)) . '
					where id=' . $mysql->getInt($admin->getUserId());
		$mysql->query($sql);
		$objEmail = new email();
		$email_config = $objEmail->getEmailSettings();
		
		$system_from 	= $email_config['system_email'];
		$from_display 	= $email_config['system_from'];
		$signatures		= "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);
		
		/*$args = array(
					'to' => $email,
					'from' => $system_from,
					'fromDisplay' => $from_display,
					'admin_id'=>$admin_id,
					'save'=>1,
					'username' => $username,
					'password' => $password,
					'site_admin' => $from_display,
					'send_mail' => true
					);

		$objEmail->sendEmailTemplate('admin_password_change', $args);*/
		
		$body = '
				<h2>Password Changed</h2>
				<p>Your password has beend changed. New password is <b>' . $password . '</b></p>';
		$body .= $signatures;
		
		$objEmail->setTo($email);
		$objEmail->setFrom($system_from);
		$objEmail->setFromDisplay($from_display);
		$objEmail->setSubject("Admin user password change");
		$objEmail->setBody($body);
	//	$objEmail->sendMail();	
                $save = $objEmail->queue();
		
		$admin->logout();
		header("location:" . CONFIG_PATH_SITE_ADMIN . "index.html?reply=" . urlencode('reply_password_updated'));
		exit();
	}
	else
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "password_change.html?reply=" . urlencode('reply_invalid_password'));
		exit();
	}
	
?>