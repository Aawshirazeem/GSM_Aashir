<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined("_VALID_ACCESS") or die("Restricted Access");
//$member->checkLogin();
//$member->reject();
require_once 'GoogleAuthenticator.php';
$ga = new PHPGangsta_GoogleAuthenticator();
$secret = $ga->createSecret();
$sqladd = 'update ' . ADMIN_MASTER . ' set google_auth_key=' . $mysql->quote($secret) . ',two_step_auth=0 where id=' . $admin->getUserId();

$mysql->query($sqladd);
header("location:" . CONFIG_PATH_SITE_ADMIN . "two_step_verify.html?reply=" . urlencode('reply_code_updated'));
exit();