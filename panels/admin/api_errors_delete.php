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

$id = $request->GetInt('id');
$api_id = $request->GetInt('api_id');

if (id == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "api_errors_add.html?id" . $api_id . "?reply=" . urlencode('reply_invalid_id'));
    exit();
}
//$sql = 'update nxt_api_details set service_name=' . $mysql->quote($name) . ' ,info=' . $mysql->quote($url) . ' where id=' . $mysql->getInt($api_id);
//$sql = 'update nxt_api_errors set reason=' . $mysql->quote($api_error) . ',reply=' . $mysql->quote($reply) . ' where id=' . $mysql->getInt($id);
$sql='delete FROM nxt_api_errors

where id=' . $mysql->getInt($id).' and api_id=' . $mysql->getInt($api_id);
$mysql->query($sql);
//header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_custom.html');
header("location:" . CONFIG_PATH_SITE_ADMIN . "api_errors_add.html?id=".$api_id."&?reply=" . urlencode('reply_done'));
exit();
