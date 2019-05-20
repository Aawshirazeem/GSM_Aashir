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

$emailid = $_REQUEST['emailid'];


//$desc = $request->PostStr('desc');
//$subject = $_REQUEST['name'];
$body = $_REQUEST['mail'];
$status = $request->PostInt('status');


if ($emailid == "" || $emailid =="0" || $body=="") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "ulist_desc.html?reply=" . urlencode('repl_something_missing'));
    exit();
}


$sql = 'update nxt_ulistdetail2 set email=' . $mysql->quote($body) . ',status='.$status.'  where id=' . $mysql->getInt($emailid);
$mysql->query($sql);

header('location:' . CONFIG_PATH_SITE_ADMIN . 'ulist_desc.html?reply=' . urlencode('repl_Update_done'));
exit();