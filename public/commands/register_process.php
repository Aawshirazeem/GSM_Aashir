<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	$admin = new admin();
	$request = new request();
	$mysql = new mysql();
	$cookie = new cookie();
	
	
	$keyword = new keyword();
        
        
    $keyNew = $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4);
	$keyNew = strtoupper($keyNew);
	
	
    $first_name = $request->PostStr('first_name');
    $last_name = $request->PostStr('last_name');
    $username = $request->PostStr('username');
    $email = $request->PostStr('email');
    $password_new = $request->PostStr('password_new');
    $password_confim = $request->PostStr('password_confim');
	
	$country_id = $request->PostStr('country_id');
	$city = $request->PostStr('city');
	$phone = $request->PostStr('phone');
	
	
	$captchaCode = $request->PostStr('captchaCode');
	$codeToCheckCaptcha = $_SESSION['codeToCheckCaptcha'];
	if($captchaCode != $codeToCheckCaptcha)
	{
		header("location:" . CONFIG_PATH_SITE . "index.php?page=register&reply=" . urlencode("invalid_verification_code"));
		exit();
	}
	
	
	if($first_name == "")
	{
		header("location:" . CONFIG_PATH_SITE . "index.php?page=register&reply=" . urlencode("name_missing"));
		exit();
	}
	if($password_new == "")
	{
		header("location:" . CONFIG_PATH_SITE . "index.php?page=register&reply=" . urlencode("password_missing"));
		exit();
	}
	if($username == "")
	{
		header("location:" . CONFIG_PATH_SITE . "index.php?page=register&reply=" . urlencode("username_missing"));
		exit();
	}
	if($email == "")
	{
		header("location:" . CONFIG_PATH_SITE . "index.php?page=register&reply=" . urlencode("email_missing"));
		exit();
	}
        
        
        
         // for checking both username and email exist in db in one time for non-active users usersssss
        
        $sql11 = 'select id from ' . USER_REGISTER_MASTER . ' where username = ' . $mysql->quote($username);
        $sql22 = 'select id from ' . USER_REGISTER_MASTER . ' where email = ' . $mysql->quote($email);
        $query11 = $mysql->query($sql11);
        $query22 = $mysql->query($sql22);
        if(($mysql->rowCount($query11) > 0) && ($mysql->rowCount($query22) > 0))
	{
		header("location:" . CONFIG_PATH_SITE . "register.html?reply=" . urlencode("duplicate_email_username"));
		exit();
	}
	
	$sql = 'select id from ' . USER_REGISTER_MASTER . ' where username = ' . $mysql->quote($username);
	
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		header("location:" . CONFIG_PATH_SITE . "register.html?reply=" . urlencode("duplicate_username"));
		exit();
	}

	$sql = 'select id from ' . USER_REGISTER_MASTER . ' where email = ' . $mysql->quote($email);
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		header("location:" . CONFIG_PATH_SITE . "index.php?page=register&reply=" . urlencode("duplicate_email"));
		exit();
	}

        // end checking active users in db
        
        
        
        
        
        
        
        
        
        
         // for checking both username and email exist in db in one time for active usersssss
        
        $sql11 = 'select id from ' . USER_MASTER . ' where username = ' . $mysql->quote($username);
        $sql22 = 'select id from ' . USER_MASTER . ' where email = ' . $mysql->quote($email);
        $query11 = $mysql->query($sql11);
        $query22 = $mysql->query($sql22);
        if(($mysql->rowCount($query11) > 0) && ($mysql->rowCount($query22) > 0))
	{
		header("location:" . CONFIG_PATH_SITE . "register.html?reply=" . urlencode("duplicate_email_username"));
		exit();
	}
	
	$sql = 'select id from ' . USER_MASTER . ' where username = ' . $mysql->quote($username);
	
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		header("location:" . CONFIG_PATH_SITE . "register.html?reply=" . urlencode("duplicate_username"));
		exit();
	}

	$sql = 'select id from ' . USER_MASTER . ' where email = ' . $mysql->quote($email);
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		header("location:" . CONFIG_PATH_SITE . "index.php?page=register&reply=" . urlencode("duplicate_email"));
		exit();
	}

        // end checking active users in db

	$sql = 'insert into ' . USER_REGISTER_MASTER . '
			(first_name, last_name, username, email, password, activation_code,country_id,city,phone)
			values(
			' . $mysql->quote($first_name) . ',
			' . $mysql->quote($last_name) . ', 
			' . $mysql->quote($username) . ',
			' . $mysql->quote($email) . ',
			' . $mysql->quote($password_new) . ',
			' . $mysql->quote($keyNew) . ',
			' . $mysql->quote($country_id) . ',
			' . $mysql->quote($city) . ',
			' . $mysql->quote($phone) . '
			)';
	$mysql->query($sql);
	
	
	$body = '
				<h2>New Registration</h2>
				<p><b>First Name:</b>' . $first_name . '<p>
				<p><b>Last Name:</b>' . $last_name . '<p>
				<p><b>Username/Email:</b>' . $email . '<p>
				<p><b>Password:</b>' . $password_new . '<p>
				';

	$emailObj = new email();
	$email_config = $emailObj->getEmailSettings();
	//echo '<pre>'; print_r($email_config);echo '</pre>';
	
	$admin_email 			= $email_config['admin_email'];
	$from_admin 		= $email_config['system_email'];
	$admin_from_disp	= $email_config['system_from'];
	$support_email		= $email_config['support_email'];
	$signatures			= "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);
	
	$emailObj->setTo($admin_email);
	$emailObj->setFrom($from_admin);
	$emailObj->setFromDisplay($admin_from_disp);
	$emailObj->setSubject("New Registration");
	$emailObj->setBody($body);
	//$sent = $emailObj->sendMail();
         $save = $emailObj->queue();
		

	
	$sql = 'select value_int as val from ' . CONFIG_MASTER . ' WHERE field=\'AUTO_REGISTRATION\'';
	$query = $mysql->query($sql);
	$rows = $mysql->fetchArray($query);
	$enabled = $rows[0]['val'];
	if($enabled == 1)
	{
		$args = array(
						'to' => $email,
						'from' => $from_admin,
						'fromDisplay' => $admin_from_disp,
						'url' => (CONFIG_PATH_SITE . 'activate.do?username=' . $username . '&key=' . $keyNew),
						'key' => $keyNew,
						'username' => $username,
						'site_admin' => $admin_from_disp,
						'send_mail'  => true);
						
		$emailObj->sendEmailTemplate('reg_confirm', $args);
	}
	else
	{
		$args = array(
						'to' => $email,
						'admin_mail' => $admin_email,
						'from' => $from_admin,
						'fromDisplay' => $admin_from_disp,
						'username' => $username,
						'site_admin' => $admin_from_disp,
						'send_mail'  => true);
						
		$emailObj->sendEmailTemplate('reg_notification', $args);
	}
	
	
	$body = '
				<h2>Welcome to ' . CONFIG_SITE_NAME . '</h2>
				<p>Hi ' . $last_name . '</p>
				<p>Thanks for joining ' . CONFIG_SITE_NAME . '. We will revert back to you soon!</p>
				<p>For more assistance plz mail us at <b>' . $from_admin . '<b></p>
				';

	$emailObj = new email();
	$emailObj->setTo($email);
	$emailObj->setFrom($from_admin);
	$emailObj->setFromDisplay($admin_from_disp);
	$emailObj->setSubject("Welcome to " . CONFIG_SITE_NAME);
	$emailObj->setBody($body.$signatures);
	//$emailObj->sendMail();
	 $save = $emailObj->queue();
	header('location:' . CONFIG_PATH_SITE . 'index.php?page=register&reply=' . urlencode('thanks'));
	exit();	
	
?>