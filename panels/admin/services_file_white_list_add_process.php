<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('service_file_white_list_add_84utjktr9ju');

    $file_ext = $request->PostStr('file_ext');
     $ser_id = $request->PostStr('ser_id');
    $status = $request->PostCheck('status');

	if($file_ext == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file_white_list_add.html?reply=" . urlencode('reply_miss_extension'));
		exit();
	}

	$sql = 'select file_ext from ' . FILE_EXTENSIONS . ' where file_ext=' . $mysql->quote($file_ext).' and s_id='.$ser_id;
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file_white_list.html?s_id=".$ser_id."&reply=" . urlencode('reply_duplicate_extension'));
		exit();
	}

	$sql = 'insert into ' . FILE_EXTENSIONS . '
			(s_id,file_ext, status)
			values(
                        '.$ser_id.',
			' . $mysql->quote($file_ext) . ','
			  .  $status . ')';
	$mysql->query($sql);
	header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file_white_list.html?s_id=".$ser_id."&reply=" . urlencode('reply_add_success'));
	exit();
?>