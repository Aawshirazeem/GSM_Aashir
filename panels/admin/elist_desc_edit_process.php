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

$emailid2 = $_REQUEST['emailid2'];
//$desc = $request->PostStr('desc');
$subject = $_REQUEST['subject'];
$body = $_REQUEST['editor1'];
$status = $request->PostInt('status');


if ($emailid == "" || $emailid =="0" || $body=="" || $emailid2 == "" || $emailid2 =="0") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "elist.html?reply=" . urlencode('repl_something_missing'));
    exit();
}


$sql = 'update nxt_elistdetail set subject=' . $mysql->quote($subject) . ',body=' . $mysql->quote($body) . ',status='.$status.'  where id=' . $mysql->getInt($emailid);
$mysql->query($sql);

header('location:' . CONFIG_PATH_SITE_ADMIN . 'elist_desc.html?id='.$emailid2);
exit();