<?php

	define("_VALID_ACCESS",1);
	require_once("../../_init.php");
	$request = new request();
	$mysql = new mysql();
	
	$type = $request->getStr('type');
	$file_name = stripslashes($request->getStr('file_name'));
	switch($type)
	{
		case 'database':
			$file_name = CONFIG_PATH_SITE_ABSOLUTE . "extra/db_backup/" . $file_name;
			break;
		case 'askrpl':
			$file_name = CONFIG_PATH_SITE_ABSOLUTE . "extra/file_service/" . $file_name;
			break;
	}

	// make sure it's a file before doing anything!
	if(is_file($file_name))
	{
	
		/*
			Do any processing you'd like here:
			1.  Increment a counter
			2.  Do something with the DB
			3.  Check user permissions
			4.  Anything you want!
		*/
	
		// required for IE
		if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off');	}
	
		// get the file mime type using the file extension
		switch(strtolower(substr(strrchr($file_name,'.'),1)))
		{
			case 'pdf': $mime = 'application/pdf'; break;
			case 'zip': $mime = 'application/zip'; break;
			case 'jpeg':
			case 'jpg': $mime = 'image/jpg'; break;
			default: $mime = 'application/force-download';
		}
		header('Pragma: public'); 	// required
		header('Expires: 0');		// no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.filesize($file_name));	// provide file size
		readfile($file_name);		// push it out
		exit();
	}
	else
	{
		echo "File Not Found";
		exit();
	}

?>
