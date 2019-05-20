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

$id = $request->PostInt('id');
$desc = $request->PostStr('desc');
//$name = $request->PostStr('api_server');
//$url = $request->PostStr('url');

if ($id == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "elist_edit.html?id=" . $id . "?reply=" . urlencode('repl_invalid_id'));
    exit();
}
$sql = 'update nxt_elist set name=' . $mysql->quote($desc) . '  where id=' . $mysql->getInt($id);
$mysql->query($sql);
//header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_custom.html');
header("location:" . CONFIG_PATH_SITE_ADMIN . "elist.html?reply=" . urlencode('repl_done'));
exit();