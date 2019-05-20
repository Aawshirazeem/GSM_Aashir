<?php

	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$member->checkLogin();
	$member->reject();
	
	$filename = isset($_POST["filename"]) ? $_POST["filename"] : 'undefined.txt';
	$content = isset($_POST["content"]) ? $_POST["content"] : '';
	
	$download = new download();
	$download->downloadContent($content, $filename);
?>
