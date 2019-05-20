<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	$id = $request->PostInt('id');
	if (defined("DEMO"))
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "config_news_edit.html?id=" . $id. "&reply=" . urlencode('reply_com_demo'));
		exit();
	}	
	$admin = new admin();
	$request = new request();
	$mysql = new mysql();
	
    
	$id = $request->getInt('id');
	$delete = $request->getInt('delete');

	$sql = 'select * from ' . BANNER_MASTER . ' where id=' . $id;
	$result = $mysql->getResult($sql);
	$files = $result['RESULT'][0];	
	
	/* DELETE BANNER */
	if($delete == 1){
		
		$sql = 'delete from ' . BANNER_MASTER . ' where id=' . $id;
		$mysql->query($sql);
		// Remove previous file
		$previous_file = CONFIG_PATH_THEME_ABSOLUTE . "images/banners/" . $files['file_name'];
		if(file_exists($previous_file)){
			unlink($previous_file);
		}

	}
	header("location:" . CONFIG_PATH_SITE_ADMIN . "config_banners.html?msg=" . urlencode("Banner deleted Successfully"));
	exit();
	
	
?>