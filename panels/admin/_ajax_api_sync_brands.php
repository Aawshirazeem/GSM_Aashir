<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$api = new api();
	
	$id = $request->GetInt('id');
	
	$sql ='select * from ' . API_MASTER . ' where id=' . $mysql->getInt($id) . ' and is_visible=1';
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	if($rowCount == 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "api_list.html?reply=" . urlencode('reply_invalid_id'));
		exit();
	}
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	
	$args['id'] = $id;
	$server_id = $row['server_id'];
	$args['key'] = $row['key'];
	$args['username'] = $row['username'];
	$args['password'] = $row['password'];
	$args['url'] = $row['url'];
	
	
	
	//echo '<p>Synchronizing unlocking tools...</p>';
	$response = $api->sync_brands($server_id, $args);
	
?>