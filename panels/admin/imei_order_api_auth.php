<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$objCredits = new credits();
$admin->checkLogin();
$admin->reject();
$type = "pending";
//$id = $request->PostInt('id');
//$check = $request->PostInt('check');
$id = $_POST['id'];
$check = $_POST['check'];
if ($id != "" && $check != "") {


    if ($check == 1) {
        $sql = 'update 
						' . ORDER_IMEI_MASTER . ' im
						set im.api_auth_yn=1 where   im.id =' . $id;
        $mysql->query($sql);
        $type = "api_auth";
    } else {
        $sql = 'update 
						' . ORDER_IMEI_MASTER . ' im
						set im.api_id=0,
                                                im.api_service_id=0,
                                                im.api_auth=0
                                                
where   im.id =' . $id;
        $mysql->query($sql);
        $type = "pending";
    }
   // header("location:" . CONFIG_PATH_SITE_ADMIN . "order_imei.html?type=" . $type . (($pString != '') ? ('&' . $pString) : '') . "&reply=" . urlencode('reply_imei_update_success'));
    echo $type;
    exit();
} 
?>