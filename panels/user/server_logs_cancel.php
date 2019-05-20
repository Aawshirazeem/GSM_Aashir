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
	
	$sql = 'select * from ' . ORDER_SERVER_LOG_MASTER . ' where user_id=' . $mysql->getInt($member->getUserId()) . ' and status=0 and id=' . $mysql->getInt($id);
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$objCredits->returnServerLog($id, $rows[0]['user_id'], $rows[0]['credits']);
		
		$sql = 'update 
					' . ORDER_SERVER_LOG_MASTER . ' im, ' . USER_MASTER . ' um
					set
					im.status=2, 
					im.message="Canceled by user",
					um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
					where um.id = im.user_id and im.id=' . $mysql->getInt($id);
		$mysql->query($sql);
		
		header('location:' . CONFIG_PATH_SITE_USER . 'server_logs.html?reply=' . urlencode('reply_cancel_success'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_USER . 'imei.html?reply=' . urlencode('reply_not_authorized_cancel_imei'));
		exit();
	}

	
?>