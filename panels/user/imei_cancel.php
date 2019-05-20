<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	
	$member->checkLogin();
	$member->reject();

	$cookie = new cookie();
	$objImei = new imei();
	$objCredits = new credits();
	
	//Check and make IMEI Array
    $id = $request->GetStr('id');
	
	$sql = 'select * from ' . ORDER_IMEI_MASTER . ' where user_id=' . $member->getUserId() . ' and status=0 and id=' . $mysql->getInt($id);
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$sql = 'update 
					' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
					set
					im.status=3, 
					im.reply_date_time=now(),
					im.remarks = "Cancled by user",
					um.credits = um.credits + im.credits,
					um.credits_inprocess = um.credits_inprocess - im.credits
					where um.id = im.user_id and im.id=' . $mysql->getInt($id);
		$mysql->query($sql);
		
		$objCredits->returnIMEI($id, $rows[0]['user_id'], $rows[0]['credits']);
			
		header('location:' . CONFIG_PATH_SITE_USER . 'imei.html?reply=' . urlencode('reply_update_success'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_USER . 'imei.html?reply=' . urlencode('reply_not_authorized_cancel_imei'));
		exit();
	}

	
?>