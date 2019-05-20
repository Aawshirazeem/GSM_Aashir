<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$objCredits = new credits();

$admin->checkLogin();
$admin->reject();

$qStrIds = "";
$Ids = $_POST['Ids'];
$type = $request->PostStr('type');
$supplier_id = $request->PostStr('supplier_id');
$file_service_id = $request->PostInt('file_service_id');
$user_id = $request->PostInt('user_id');
$ip = $request->PostStr('ip');

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
if ($file_service_id != 0) {
    $pString .= (($pString != '') ? '&' : '') . 'file_service_id=' . $file_service_id;
}
$pString = trim($pString, '&');

foreach ($Ids as $id) {
    $unlock_code = $request->PostStr('unlock_code_' . $id);
    $action = "";

    if (isset($_POST['locked_' . $id])) {
        $qStrIds .= $id . ',';
    }
}

$qStrIds = substr($qStrIds, 0, strlen($qStrIds) - 1);
if ($qStrIds != '') {
    $sql = 'update ' . ORDER_FILE_SERVICE_MASTER . ' set status=-1 where id in (' . $qStrIds . ') and status=0';
    $mysql->query($sql);
}

header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $type . (($pString != '') ? ('&' . $pString) : '') . "&reply=" . urlencode('reply_success_order'));
exit();
?>