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
$desc = $request->PostStr('desc');

if ($desc == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "elist_add.html?reply=" . urlencode('reply_description_missing'));
    exit();
}

$sql = '
							insert into nxt_elist
								(name) 
								VALUES (
							
									' . $mysql->quote($desc) . '
							
								
									)';
$mysql->query($sql);
header('location:' . CONFIG_PATH_SITE_ADMIN . 'elist.html');
exit();
