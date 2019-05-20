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
$id = $_POST['id'];
//echo $id;
if ($id != "") {
    if ($id == -1) {
        $sql = 'update nxt_notifications set display=0';
        $query = $mysql->query($sql);
    } else {
        $sql = 'update nxt_notifications set display=0 where id=' . $mysql->getInt($id);
        $query = $mysql->query($sql);
    }
}
//header("location:" . CONFIG_PATH_SITE_ADMIN . "dashboard.html");
exit;
?>