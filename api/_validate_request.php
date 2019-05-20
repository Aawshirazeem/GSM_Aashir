<?php
	require_once("_init.php");
	$req = new request();
	$mysql = new mysql();
	$xml = new xml();
	
	$api_key = $req->PostStr('api_key');

	
	if($api_key == '')
	{
		$sql = 'select api_key from ' . USER_MASTER . ' where api_key like ' . $mysql->quote($api_key);
		$query = $mysql->query($sql);
		if($mysql->rowCount($query) == 0 || $api_key == '')
		{
			echo '<result>';
			echo $xml->error('0', 'Invalid API Key', '00001', false);
			exit();
		}
	}
	else
	{
			echo '<result>';
			echo $xml->error('0', 'Invalid API Key', '00001', false);
			exit();
	}
	
?>