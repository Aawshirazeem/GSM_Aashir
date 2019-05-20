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

$sql = 'update nxt_notifications set display=0';
$query = $mysql->query($sql);

header("location:" . CONFIG_PATH_SITE_ADMIN . "dashboard.html");
exit;
