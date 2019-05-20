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
		echo '<p class="text-danger">Invalid API...</p>';
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
	
	
	
	$response = $api->credits($server_id, $args);
        //var_dump($response);
	if($response['credits'] == -1 and $response['msg'] != '')
	{
		echo '<p class="text-danger">Connection Error! ' . $response['msg'] .  '</p>';
	}
	else
	{
                $usrcredit=$response['credits'];
                $usremail=$response['email'];
                $cursign=$response['currency'];
                if($usremail!="")
                {
		echo '<p class="text-success">Connected...</p>';
                echo '<p class="text-info">Email : '.$usremail.'</p>';
                 echo '<p class="text-info">Credits : '.$usrcredit.$cursign.'</p>';
                }
	}
	
?>