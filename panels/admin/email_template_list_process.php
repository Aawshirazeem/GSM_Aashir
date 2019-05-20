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
	
	$newIds = $_POST['ids'];
	$newIds = implode(',',$newIds);
	$sqle = 'update ' . EMAIL_TEMPLATES . ' set status=0';
	$querye = $mysql->query($sqle);
	
	$sql = 'update ' . EMAIL_TEMPLATES . ' set status=1 where id in(' . $newIds . ')';
	$mysql->query($sql);
		
	header("location:" . CONFIG_PATH_SITE_ADMIN . "email_template_list.html?reply=" . urlencode('reply_update_success'));
	exit();
?>