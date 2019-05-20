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
//$server_id = $request->PostInt('server_id');
$api_server = $request->PostStr('api_server');
$url = $request->PostStr('url');
//$key = $request->PostStr('key');
//$username = $request->PostStr('username');
// get last service id of server 15
$sql = 'select count(*) k from ' . API_DETAILS . ' a
where a.api_id=' . $api_id;
$query = $mysql->query($sql);

$rows = $mysql->fetchArray($query);
$row = $rows[0];
$service_id = $row['k'];
$service_id=$service_id+1;

if ($api_server == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "api_add.html?reply=" . urlencode('reply_server_name_missing'));
    exit();
}

$sql = '
							insert into ' . API_DETAILS . '
								(api_id, group_name,service_id,service_name,type, info) 
								VALUES (
									' . $api_id . ',
									"Custom", 
							' . $service_id . ',
									' . $mysql->quote($api_server) . ', 
							
									1,
									' . $mysql->quote($url) . '
									)';
$mysql->query($sql);
header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_custom.html');
exit();
?>