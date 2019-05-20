<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	
    $username = $request->GetStr('username');
	
	$sql = 'select username from ' . USER_MASTER . ' where username=' . $mysql->quote($username);
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