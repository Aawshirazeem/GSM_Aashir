<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$phpmailer = new phpmailer();

	//require("phpmailer/class.phpmailer.php");
	$mail = new phpmailer();
	$mail->IsSMTP(); // send via SMTP
	//IsSMTP(); // send via SMTP
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = GMAIL_USERNAME; // SMTP username
	$mail->Password = GMAIL_PASSWORD; // SMTP password
	$webmaster_email = GMAIL_USERNAME; //Reply to this email ID
	$email="info@nxtdesigns.com"; // Recipients email ID
	$name="name"; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "Webmaster";
	$mail->AddAddress($email,$name);
	$mail->AddReplyTo($webmaster_email,"Webmaster");
	$mail->WordWrap = 50; // set word wrap
	//$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	$mail->IsHTML(true); // send as HTML
	$mail->Subject = "This is the subject";
	$mail->Body = "Hi,
	This is the HTML BODY "; //HTML Body
	$mail->AltBody = "This is the body when user views in plain text format"; //Text Body
	
	if(!$mail->Send())
	{
		echo "Mailer Error: " . $mail->ErrorInfo;
	}
	else
	{
		echo "Message has been sent";
	}
?>