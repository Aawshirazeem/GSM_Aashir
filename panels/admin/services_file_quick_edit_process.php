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

// update tools namess
if (is_array($Ids)) {
    foreach ($Ids as $id) {



        $tmpname = trim($_POST['tool_name_' . $id]);
        $tmptime = trim($_POST['tool_time_' . $id]);

        if ($tmpname != "" && $tmptime != "") {
            $sql = 'update ' . FILE_SERVICE_MASTER . '

set service_name=' . $mysql->quote($tmpname) . ',delivery_time=' . $mysql->quote($tmptime) . '

where id=' . $id;

            $mysql->query($sql);
        }
    }

    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file_quick_edit.html?reply=" . urlencode('reply_update_success'));
    exit();
} else {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "services_file_quick_edit.html?reply=" . urlencode('reply_update_error'));
    exit();
}
