<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//var_dump($_POST);exit;
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$admin->checkLogin();
$admin->reject();
$status = $request->PostInt('web_status');
$msg = $request->PostStr('msg');
$sql = 'update ' . Website_Maintinance . ' set msg = ' . $mysql->quote($msg) . ', admin = ' . $admin->getUserId() . ', status = ' . $mysql->getInt($status) . ' where id = ' . 1;
//echo $sql;exit;
$mysql->query($sql);
header("location:" . CONFIG_PATH_SITE_ADMIN . "web_maintinance.html?reply=" . urlencode('reply_success'));
exit();