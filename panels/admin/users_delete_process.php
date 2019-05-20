<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$id = $request->PostInt('id');
	$text_compate = $request->PostStr('text_compate');
	$disclaimer = $request->PostStr('disclaimer');
	
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('user_delete_8478950686');
	$objCredits = new credits();
    
	
    
    if($text_compate != $disclaimer)
    {
		header('location:' . CONFIG_PATH_SITE_ADMIN . 'users_delete.html?id=' . $id . '&reply=' . urlencode('reply_invalid_disclaimer'));
		exit();
    }
	
	
		
	$sql = 'delete from ' . USER_MASTER . ' where id=' . $id;
	$mysql->query($sql);
		
	header('location:' . CONFIG_PATH_SITE_ADMIN . 'users.html?reply=' . urlencode('reply_user_deleted_successfully'));
	exit();
?>