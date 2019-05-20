<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$trans_id = $request->GetInt('trans_id'); 
	
	$member->checkLogin();
	$member->reject();
	$objCredits = new credits();
    	
		$sql = 'update ' . INVOICE_REQUEST . '
						set status=2
					where
						user_id=' . $member->getUserID() . ' and
						id=' . $mysql->getInt($trans_id);
		$mysql->query($sql);
		
		header("location:" . CONFIG_PATH_SITE_USER .  "credits_reqeusts.html?reply=" . urlencode('reply_success'));
		exit();
?>