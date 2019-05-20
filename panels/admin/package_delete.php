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

$id = $request->PostInt('package_id');
$id = $request->GetInt('package_id');
//$text_compate = $request->PostStr('text_compate');
//$disclaimer = $request->PostStr('disclaimer');

$admin->checkLogin();
$admin->reject();
//$validator->formValidateAdmin('user_delete_8478950686');
//$objCredits = new credits();

if ($id == '') {
    header('location:' . CONFIG_PATH_SITE_ADMIN . 'package.html?id=' . $id . '&reply=' . urlencode('reply_invalid_id'));
    exit();
}



$sql = 'delete from ' . PACKAGE_MASTER . ' where id=' . $id;
$mysql->query($sql);

header('location:' . CONFIG_PATH_SITE_ADMIN . 'package.html?reply=' . urlencode('reply_package_deleted_successfully'));
exit();
?>