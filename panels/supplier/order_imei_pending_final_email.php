<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}		
	
	
	$imei = $request->PostStr('imei2');
	$imei = str_replace("&#13;&#10;", "<br />", $imei);
	$supplier_id = $request->PostInt('supplier_id');
	$email = $request->PostStr('email');
	
	if($email == '' && $supplier_id == 0)
	{
		echo "Please select supplier or enter some email!";
		exit();
	}
	
	if($email == '' && $supplier_id != 0)
	{
		$sql_usr = 'select id, email from ' . SUPPLIER_MASTER . ' where id=' . $supplier_id;
		$query_usr = $mysql->query($sql_usr);
		if($mysql->rowCount($query_usr) > 0)
		{
			$rows_usr = $mysql->fetchArray($query_usr);
			$email = $rows_usr[0]['email'];
		}
	}
	$objEmail = new email();
	
	
	$objEmail->setTo($email);
	$objEmail->setFrom(CONFIG_EMAIL);
	$objEmail->setFromDisplay(CONFIG_SITE_NAME);
	$objEmail->setSubject(CONFIG_SITE_NAME . " - NEW IMEI ORDERS");
	$objEmail->setBody('You got new IMEI orders from ' . CONFIG_SITE_NAME . "<br /><br /><br /><br />" . $imei);
	//$objEmail->sendMail();
        $objEmail->queue();
?>	
<h1><?php echo $admin->wordTrans($admin->getUserLang(),'Email sent successfully'); ?>!</h1>