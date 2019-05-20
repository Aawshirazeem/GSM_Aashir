<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	$request = new request();
	
	$file = stripslashes($request->getStr('file'));
	$size = $request->getStr('size');
	$thumb= new thumbnail($file, $size);
 ?>