<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
//$validator->formValidateAdmin('service_imei_file_edit_14832342');
//var_dump($_POST);exit;
$api_id = $request->PostInt('api_id');
$api_error = $request->PostStr('api_error');
$reply = $request->PostStr('reply');

if (isset($_POST['is_action'])) {
    $is_action = 1;
} else {
    $is_action = 0;
}

//$key = $request->PostStr('key');
//$username = $request->PostStr('username');
// get last service id of server 15

$sql = '
							insert into nxt_api_errors
								(api_id,reason,reply,action) 
								VALUES (
                                                                '.$mysql->getInt($api_id).',
									' . $mysql->quote($api_error) . ', 
									' . $mysql->quote($reply) . ',
                                                                            ' . $is_action . '
									)';
$mysql->query($sql);
//header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_custom.html');
header("location:" . CONFIG_PATH_SITE_ADMIN . "api_errors_add.html?id=".$api_id."&?reply=" . urlencode('reply_done'));
exit();
?>