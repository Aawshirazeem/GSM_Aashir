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



$chat_code = $_POST['chat_code'];
//echo $chat_code;exit;
if ($chat_code == "")
    $chat_code = "";

$sql1 = 'update ' . SMTP_CONFIG . ' set chat_code=' . $mysql->quote($chat_code);
$mysql->query($sql1);
header("location:" . CONFIG_PATH_SITE_ADMIN . "custom_chat.html?reply=" . urlencode('done'));
exit();
