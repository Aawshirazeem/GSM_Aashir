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
$s_id = $request->GetInt('s_id');
if ($id != "" && $s_id!="" ) {


    $sql = 'delete  from  ' . FILE_EXTENSIONS . '  where  id=' . $id.' and s_id='.$s_id;
    $mysql->query($sql);

    header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_file_white_list.html?s_id='.$s_id.'&reply=' . urlencode('lbl_currency_delete'));
    exit();
}
header('location:' . CONFIG_PATH_SITE_ADMIN . 'services_file_white_list.html?s_id='.$s_id.'&reply=' . urlencode('lbl_currency_missing'));
exit();
