<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$member->checkLogin();
	$member->reject();
	
    $email = $request->GetStr('email');
	
	$sql = 'select username from ' . USER_MASTER . '
				where email=' . $mysql->quote($email);
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		echo "NotAvail";
	}
	else
	{
		echo "Avail";
	}
	
?>