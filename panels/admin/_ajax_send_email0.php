<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	
	$keyword = new keyword();
    
	$admin->checkLogin();
	$admin->reject();
	
	$subject = $request->postStr('subject');
	$body = ($_POST['body']);
	//$body = htmlentities(trim($_POST['body']), ENT_QUOTES, 'UTF-8');;
	$emails = $_POST['emails'];
	error_log($_POST['body'], 3, CONFIG_PATH_LOGS_ABSOLUTE . "/email_queue.log");
	
	foreach($emails as $email)
	{
		$objEmail = new email();
		$objEmail->setTo($email);
		$objEmail->setFrom(CONFIG_EMAIL);
		$objEmail->getFromDisplay(CONFIG_EMAIL);
		$objEmail->setSubject($subject);
		$objEmail->setBody($body);
		
		$objEmail->queue();
		
	}
    
?>