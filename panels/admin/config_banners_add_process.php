<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	if (defined("DEMO"))
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "config_news_add.html?reply=" . urlencode('reply_com_demo'));
		exit();
	}
	
	$admin = new admin();
	$request = new request();
	$mysql = new mysql();
    
    $publish = $request->PostCheck('publish');
	
	$imgName = '';
	
	$keyword = new keyword();
	$key = $keyword->generate(20);

	
	//Upload Licence
	if($_FILES['image']['name'] != "")
	{
		// Generate File name
		$path = $_FILES['image']['name'];
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$imgName = $key. '.' . $ext;
		
		
		// Upload file path
		$uploadfile = CONFIG_PATH_THEME_ABSOLUTE . "images/banners/" . $imgName;
		
		// Remove previous file
		/*$previous_file = CONFIG_PATH_THEME_ABSOLUTE . "images/banners/" . $files['file_name'];
		if(file_exists($previous_file) && $files['file_licence'] != ''){
			unlink($previous_file);
		}*/
		
		
		if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) 
		{
			header("location:" . CONFIG_PATH_SITE_ADMIN . "config_banners_add.html?reply=" . urlencode('reply_cannot_upload_licence'));
			exit();
		}
	}
	//Upload Licence
	
	$sql = 'insert into ' . BANNER_MASTER . ' (file_name, status)
			values(' . $mysql->quote($imgName) . ', ' . $publish . ')';
	$mysql->query($sql);
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "config_banners.html?msg=" . urlencode("News Added Successfully"));
	
	
	
?>