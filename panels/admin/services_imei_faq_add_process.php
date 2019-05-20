<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('services_imei_faq_add_75566355454');

	$id = $request->PostInt('id');
	$question= $request->PostStr('question');
	$answer= $request->PostStr('answer');
	
	$status = $request->PostInt('status');    
	
	if(trim($question) == '')
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_faq_add.html?reply=' . urlencode('reply_service_imei_faq_missing'));
		exit();
	}
	$sql_chk ='select * from ' . IMEI_FAQ_MASTER . ' where question=' . $mysql->quote($question);
	$query_chk = $mysql->query($sql_chk);
	
	if($mysql->rowCount($query_chk) == 0)
	{
		$sql = 'insert into ' . IMEI_FAQ_MASTER . ' (question, answer, status)
					values(
					'. $mysql->quote($question) . ',
					'. $mysql->quote($answer) . ',
					' . $mysql->getInt($status) . ')';
		$mysql->query($sql);
		$args = array(
			'to' => CONFIG_EMAIL,
			'from' => CONFIG_EMAIL,
			'fromDisplay' => CONFIG_SITE_NAME,
			'question' => $question,
			'site_admin' => CONFIG_SITE_NAME
			);
		$objEmail->sendEmailTemplate('admin_add_faq', $args);
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_faq_add.html?reply=' . urlencode('reply_success'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_faq_add.html?reply=' . urlencode('reply_service_imei_faq_duplicate'));
		exit();
	}	
?>