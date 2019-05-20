<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	$admin->checkLogin();
	$admin->reject();


	$id = 0;
	
	$sql_users ='SELECT count(id) as total, DATE_FORMAT(creation_date, "%Y-%m-%d") as creation_date
						FROM ' . USER_MASTER . '
					where month(creation_date) = month(CURDATE()) and year(creation_date) = year(CURDATE())
					GROUP BY day(creation_date)';
	$query_users = $mysql->query($sql_users);
	if($mysql->rowCount($query_users) > 0)
	{
		$users = $mysql->fetchArray($query_users);
		foreach($users as $user)
		{
			$total = $user['total'];
			$title = ($total > 1) ? ($total . ' users') : ($total . ' user');
			$resultNewUsers[] = array(
					'id' => $id++,
					'title' => $title,
					'start' => $user['creation_date']);
		}
	}
	
	$sql_users ='SELECT sum(credits) as total, DATE_FORMAT(date_time, "%Y-%m-%d") as date_time
						FROM ' . CREDIT_TRANSECTION_MASTER . '
					where
						(admin_id!=0 or admin_id2 !=0) and
						month(date_time) = month(CURDATE()) and year(date_time) = year(CURDATE())
					GROUP BY day(date_time)';
	$query_users = $mysql->query($sql_users);
	if($mysql->rowCount($query_users) > 0)
	{
		$users = $mysql->fetchArray($query_users);
		foreach($users as $user)
		{
			$total = $user['total'];
			$title = ($total > 1) ? ($total . ' credits') : ($total . ' credit');
			$resultNewUsers[] = array(
					'id' => $id++,
					'title' => $title,
					'start' => $user['date_time']);
		}
	}
	

	echo json_encode($resultNewUsers);

?>
