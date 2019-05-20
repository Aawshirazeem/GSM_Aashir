<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

    $objCredits = new credits();
	
	
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('email_template_edit_784549971255d2');

    $id = $request->PostStr('id');
    $subject = $request->PostStr('subject');
    $email_by_id = $request->PostInt('email_by_id');
    $mailbody = $_POST['mailbody'];
    $status = $request->PostCheck('status');

	if($subject == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "email_template_edit.html?id=" . $id ."reply=". urlencode('reply_subject_missing'));
		exit();
	}
	if($mailbody == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "email_template_edit.html?id=" . $id ."reply=" . urlencode('reply_mailbody_missing'));
		exit();
	}
	
	$keyword = new keyword();
    $key = $request->GetStr('key');
    
    $keyNew = $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4);
	$keyNew = strtoupper($keyNew);
	
    $loginKey = $keyword->generate(20);

	$sql = 'update ' . EMAIL_TEMPLATES . '
			set subject=' . $mysql->quote($subject) . ',
			    email_by_id=' . $email_by_id . ',
			    mailbody=' . $mysql->quote($mailbody) . ',
				status='	.  $status . ' where id=' . $id;
	$mysql->query($sql);
	header("location:" . CONFIG_PATH_SITE_ADMIN . "email_template_list.html?reply=" . urlencode('reply_update_success'));
	exit();
?>