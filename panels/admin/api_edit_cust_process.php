<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
//$validator->formValidateAdmin('service_imei_file_edit_14832342');

$api_id = $request->PostInt('api_id');
$name = $request->PostStr('api_server');
$url = $request->PostStr('url');

if ($api_id == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "api_edit_cust.html?id" . $id . "?reply=" . urlencode('reply_invalid_id'));
    exit();
}
$sql = 'update nxt_api_details set service_name=' . $mysql->quote($name) . ' ,info=' . $mysql->quote($url) . ' where id=' . $mysql->getInt($api_id);
$mysql->query($sql);
//header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_custom.html');
header("location:" . CONFIG_PATH_SITE_ADMIN . "api_custom.html?reply=" . urlencode('reply_done'));
exit();