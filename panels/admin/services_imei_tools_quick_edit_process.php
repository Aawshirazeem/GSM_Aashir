<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


$objCredits = new credits();
$admin->checkLogin();
$admin->reject();
//echo '<pre>';
//var_dump($_POST);exit;
$Ids = isset($_POST['idss']) ? $_POST['idss'] : array();
//udpdate grps nameess

$Ids2 = isset($_POST['idss2']) ? $_POST['idss2'] : array();

if (is_array($Ids2)) {
    foreach ($Ids2 as $id) {



        $tmpgname = trim($_POST['grp_name_' . $id]);

        if ($tmpgname != "") {


            $sql = 'update ' . IMEI_GROUP_MASTER . '

set group_name=' . $mysql->quote($tmpgname) . '

where id=' . $id;
//echo $sql;exit;
            $mysql->query($sql);
        }
    }
}

// update tools namess
if (is_array($Ids)) {
    foreach ($Ids as $id) {



        $tmpname = trim($_POST['tool_name_' . $id]);
        $tmptime = trim($_POST['tool_time_' . $id]);

        if ($tmpname != "" && $tmptime != "") {
            $sql = 'update ' . IMEI_TOOL_MASTER . '

set tool_name=' . $mysql->quote($tmpname) . ',delivery_time=' . $mysql->quote($tmptime) . '

where id=' . $id;

            $mysql->query($sql);
        }
    }

    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_tools_quick_edit.html?reply=" . urlencode('reply_update_success'));
    exit();
} else {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_tools_quick_edit.html?reply=" . urlencode('reply_update_error'));
    exit();
}
