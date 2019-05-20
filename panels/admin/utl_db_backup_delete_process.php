<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$fileName = $request->GetStr('file_name');
	$download = CONFIG_PATH_EXTRA_ABSOLUTE . "db_backup/" . $mysql->prints($fileName);
	
	if(file_exists($download))
	{
		unlink(CONFIG_PATH_EXTRA_ABSOLUTE . 'db_backup/' . $fileName);
	}
	header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_db_backup.html?reply=" . urlencode('repl_delete_done'));
?>