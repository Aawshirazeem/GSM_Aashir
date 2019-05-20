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

$objCredits = new credits();

$admin->checkLogin();
$admin->reject();
$type = $request->PostStr('type');

$qStrIds = "";
//$Ids = $_POST['Ids'];
$Ids = $_POST['locked'];
if ($Ids != "") {

    if (isset($_POST['auth'])) {
        foreach ($Ids as $id) {
# Auth-button was clicked
            $sql = 'update 
						' . ORDER_IMEI_MASTER . ' im
						set im.api_auth_yn=1 where im.api_auth=1 and  im.api_auth_yn=0 and im.id =' . $id;
            $mysql->query($sql);
            $type = "api_auth";
        }
    } elseif (isset($_POST['normal'])) {
# Normal-button was clicked
        foreach ($Ids as $id) {
            $sql = 'update 
						' . ORDER_IMEI_MASTER . ' im
						set im.api_id=0,
                                                im.api_service_id=0,
                                                im.api_auth=0
                                                
where im.api_auth=1 and  im.api_auth_yn=0 and   im.id =' . $id;
            $mysql->query($sql);
            $type = "pending";
        }
    }
    header("location:" . CONFIG_PATH_SITE_ADMIN . "order_imei.html?type=" . $type . (($pString != '') ? ('&' . $pString) : '') . "&reply=" . urlencode('reply_imei_update_success'));
    exit();
}