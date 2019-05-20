<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}		
	
	
	$imei = $request->PostStr('imei');
	$imei = str_replace("&#13;&#10;", "\r\n", $imei);
	
	$download = new download();
	$download->downloadContent($imei, 'imei.txt');
?>	