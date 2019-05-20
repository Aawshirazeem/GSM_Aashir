<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	
	$member->checkLogin();
	$member->reject();	
    
	$id = $request->GetInt('id');

	
	$sql = 'update ' . TICKET_MASTER . ' set status=1 where ticket_id=' . $mysql->getInt($id);
	$mysql->query($sql);
	
	header('location:' . CONFIG_PATH_SITE_USER . 'ticket.html?id=' . $id . '&reply=' . urlencode('reply_close_success'));
	
	
	
?>