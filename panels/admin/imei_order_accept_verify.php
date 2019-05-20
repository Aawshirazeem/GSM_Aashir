<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$objCredits = new credits();
$admin->checkLogin();
$admin->reject();
$type = "verify";
$id = $request->GetInt('id');
$check = $request->GetInt('check');
if ($id != "" && $check != "") {


    if ($check == 1) {
        $sql = 'update 
						' . ORDER_IMEI_MASTER . ' im
						set im.v_check=1 where   im.id =' . $id;
        $mysql->query($sql);
    } else {
        $sql = 'update 
						' . ORDER_IMEI_MASTER . ' im
						set im.v_check=2,
                                                im.verify=0
                                                
where   im.id =' . $id;
        $mysql->query($sql);
    }
    header("location:" . CONFIG_PATH_SITE_ADMIN . "order_imei.html?type=" . $type . (($pString != '') ? ('&' . $pString) : '') . "&reply=" . urlencode('reply_imei_update_success'));
    exit();
}
else
{
    header("location:" . CONFIG_PATH_SITE_ADMIN . "order_imei.html?type=" . $type . (($pString != '') ? ('&' . $pString) : '') . "&reply=" . urlencode('reply_imei_update_failed'));
    exit();
}
?>