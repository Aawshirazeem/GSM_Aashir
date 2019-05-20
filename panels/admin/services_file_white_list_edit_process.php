<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$objCredits = new credits();

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('user_add_59905855d2');

$id = $request->PostStr('id');
$ser_id = $request->PostStr('ser_id');
$file_ext = $request->PostStr('file_ext');
$status = $request->PostCheck('status');

if ($file_ext == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file_white_list_edit.html?id=" . $id . "&reply=" . urlencode('reply_miss_extension'));
    exit();
}

$sql = 'select file_ext from ' . FILE_EXTENSIONS . ' 
									where file_ext=' . $mysql->quote($file_ext) .
        ' and id!=' . $id.' and s_id='.$ser_id;
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file_white_list_edit.html?s_id=".$ser_id."&id=" . $id . "&reply=" . urlencode('reply_duplicate_extension'));
    exit();
}


$sql = 'update ' . FILE_EXTENSIONS . '
			set file_ext=' . $mysql->quote($file_ext) . ',
				status=' . $status . ' where id=' . $id;
$mysql->query($sql);
header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file_white_list.html?s_id=".$ser_id."&reply=" . urlencode('reply_update_success'));
exit();
?>