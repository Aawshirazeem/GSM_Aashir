<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$objCredits = new credits();
$admin->checkLogin();
$admin->reject();
$type="locked";
$id = $request->GetInt('id');
$sql = '
					update 
						' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
						set
							im.status=1, 
                                                        im.reply="",
							um.credits_inprocess = um.credits_inprocess + im.credits,
											um.credits_used = um.credits_used - im.credits
						where im.status=2 and um.id=im.user_id and im.id =' . $id;
$mysql->query($sql);
header("location:" . CONFIG_PATH_SITE_ADMIN . "order_imei.html?type=" . $type . (($pString != '') ? ('&' . $pString) : '') . "&reply=" . urlencode('reply_imei_update_success'));
exit();
?>