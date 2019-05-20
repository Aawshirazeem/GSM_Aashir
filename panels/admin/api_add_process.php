<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('service_imei_file_edit_14832342');

$id = $request->PostInt('id');
$server_id = $request->PostInt('server_id');
$api_server = $request->PostStr('api_server');
$url = $request->PostStr('url');
$key = $request->PostStr('key');
$username = $request->PostStr('username');

if ($api_server == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "api_add.html?reply=" . urlencode('reply_server_name_missing'));
    exit();
}

$sql_chk = 'select * from ' . API_MASTER . '
					where api_server=' . $mysql->quote($api_server);


$sql_chk ='select * from ' . API_MASTER . '
					where status=1 and is_visible=1 and api_server=' . $mysql->quote($api_server);
	
$query_chk = $mysql->query($sql_chk);
if ($mysql->rowCount($query_chk) == 0) {

    $sqlQ = '';
    if (isset($_POST['url'])) {
        $sqlQ .= '`url`=' . $mysql->quote($url) . ',';
    }
    if (isset($_POST['key'])) {
        $sqlQ .= '`key`=' . $mysql->quote($key) . ',';
    }
    if (isset($_POST['username'])) {
        $sqlQ .= 'username=' . $mysql->quote($username) . ',';
    }

    $sql = 'insert into ' . API_MASTER . ' (server_id, api_server, url, url_edit, username, username_edit, `key`, key_edit, requires_sync, status, is_visible)
					values(
					' . $server_id . ',
					' . $mysql->quote($api_server) . ',
					' . $mysql->quote($url) . ',
					1,
					' . $mysql->quote($username) . ',
					1,
					' . $mysql->quote($key) . ',
					1,
					1,
					1,
					1)';
    $sql2 = 'insert into ' . API_MASTER . ' (server_id, api_server, password, password_edit, username, username_edit, `key`, key_edit, requires_sync, status, is_visible)
					values(
					' . $server_id . ',
					' . $mysql->quote($api_server) . ',
					' . $mysql->quote("") . ',
					1,
					' . $mysql->quote($username) . ',
					1,
					' . $mysql->quote($key) . ',
					1,
					1,
					1,
					1)';
    if ($server_id != 13)
        $mysql->query($sql);
    else
        $mysql->query($sql2);

    $id = "SELECT LAST_INSERT_ID();";
    $id = $mysql->query($id);
    $result = $mysql->fetchArray($id);
    $id = $result[0]['LAST_INSERT_ID()'];

    header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_edit.html?id=' . $id);
    exit();
}
else {
    header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_list.html?reply=' . urlencode('reply_service_imei_api_duplicate'));
    exit();
}
?>