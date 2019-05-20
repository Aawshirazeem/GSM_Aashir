<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$cookie = new cookie();

$admin->checkLogin();
$admin->reject();

$objImei = new imei();
$objCredits = new credits();

$id = $request->PostInt('id');
$type = $request->PostStr('type');
$supplier_id = $request->PostInt('supplier_id');
$user_id = $request->PostInt('user_id');
$ip = $request->PostStr('ip');
$code = $request->PostStr('code');

$tempName = '';

$pString = '';
if ($supplier_id != 0) {
    $pString .= (($pString != '') ? '&' : '' ) . 'supplier_id=' . $supplier_id;
}
if ($ip != '') {
    $pString .= (($pString != '') ? '&' : '') . 'ip=' . $ip;
}
if ($user_id != 0) {
    $pString .= (($pString != '') ? '&' : '') . 'user_id=' . $user_id;
}
$pString = trim($pString, '&');

$sql_detail = 'select ofsm.*,
					um.username,
					um.email,
					slm.service_name
					from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm
					left join ' . USER_MASTER . ' um on(ofsm.user_id = um.id)
					left join ' . FILE_SERVICE_MASTER . ' slm on (slm.id = ofsm.file_service_id)
					where um.id= ' . $user_id . '
					and ofsm.id= ' . $id . '
					order by ofsm.id DESC';
$query_detail = $mysql->query($sql_detail);
// Check is file set
if (isset($_FILES['file'])) {
    // Get file name and check is it empty
    $tempName = $_FILES['file']['name'];
    if ($code != "" or $tempName != "") {
        if ($mysql->rowCount($query_detail)) {
            $rows_detail = $mysql->fetchArray($query_detail);
            $row_detail = $rows_detail[0];
            $file_service = $row_detail['service_name'];
            $username = $row_detail['username'];
            $email = $row_detail['email'];
            $order_id = $id;
            $credits = $row_detail['credits'];
            $objEmail = new email();
            $args = array(
                'to' => $email,
                'from' => CONFIG_EMAIL,
                'fromDisplay' => CONFIG_SITE_NAME,
                'user_id' => $user_id,
                'save' => '1',
                'username' => $username,
                'order_id' => $order_id,
                'file_service' => $file_service,
                'credits' => $credits,
                'site_admin' => CONFIG_SITE_NAME
            );

            $objEmail->sendEmailTemplate('admin_user_order_file_update', $args);
        }
    }
}
$uploadfile = "";
if ($tempName != "") {
    $uploadfile = CONFIG_PATH_EXTRA_ABSOLUTE . "file_service/" . $tempName;
    if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
        header('location:' . CONFIG_PATH_SITE_ADMIN . 'order_file.html?' . (($type != '') ? ('&type=' . $type . '&' . $pString . '&') : ('&' . $pString . '&')) . 'reply=' . urlencode('reply_not_upload'));
        exit();
    }
    if ($uploadfile == "") {
        header('location:' . CONFIG_PATH_SITE_ADMIN . 'order_file.html?' . (($type != '') ? ('&type=' . $type . '&' . $pString . '&') : ('&' . $pString . '&')) . 'reply=' . urlencode('reply_not_upload'));
        exit();
    }

    /* Update Database */
    $sql = 'update 
					' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
					set
					im.filerpl = ' . $mysql->quote($tempName) . ',
					im.status=1,
					um.credits_inprocess = um.credits_inprocess - im.credits_amount,
					um.credits_used = um.credits_used + im.credits_amount
					where im.status!=1 and um.id = im.user_id and im.id=' . $mysql->getInt($id);
    $mysql->query($sql);


    header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?" . (($type != '') ? ('&type=' . $type . '&' . $pString . '&') : ('&' . $pString . '&')) . "reply=" . urlencode('reply_success_order'));
    exit();
} else {
    if ($code != "") {
        /* Update Database */
        $sql = 'update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.unlock_code = ' . $mysql->quote($code) . ',
						im.status=1,
						um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
						where im.status!=1 and um.id = im.user_id and im.id=' . $mysql->getInt($id);
        $mysql->query($sql);

        header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?" . (($type != '') ? ('&type=' . $type . '&' . $pString . '&') : ('&' . $pString . '&')) . "reply=" . urlencode('reply_success_order'));
        exit();
    } else {
        header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?" . (($type != '') ? ('&type=' . $type . '&' . $pString . '&') : ('&' . $pString . '&')) . "id=" . $id . "&reply=" . urlencode('reply_code'));
        exit();
    }
}
header('location:' . CONFIG_PATH_SITE_ADMIN . 'order_file.html?$type=error&reply=' . urlencode('unknown_error'));
exit();
?>