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
if ($id != "") {


    $sql = 'delete  from  ' . CURRENCY_MASTER . '  where  id=' . $id;
    $mysql->query($sql);

    header('location:' . CONFIG_PATH_SITE_ADMIN . 'currency.html?reply=' . urlencode('lbl_currency_delete'));
    exit();
}
header('location:' . CONFIG_PATH_SITE_ADMIN . 'currency.html?reply=' . urlencode('lbl_currency_missing'));
exit();
