<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	
	$list = $_POST['list'];
	$id = $request->PostInt('id');

	$list = json_decode($list, true);
	$i = 0;
	foreach($list as $item){
		$sql = 'update ' . IMEI_GROUP_MASTER . '
					set
						sort_order = ' . $i++ . '
					where id = ' . $item['id'];
		$mysql->query($sql);
	}

	header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_imei_group.html?id=' . $id . '&reply=' . urlencode('reply_success'));
	exit();	
?>