<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$objCredits = new credits();

$admin->checkLogin();
$admin->reject();

$Ids = $_POST['Ids'];
$type = $request->PostStr('type');
$supplier_id = $request->PostStr('supplier_id');
$file_service_id = $request->PostInt('file_service_id');
$user_id = $request->PostInt('user_id');
$ip = $request->PostStr('ip');
$code="";

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
    $sql = 'select * from ' . ORDER_FILE_SERVICE_MASTER . ' oim where id=' . $mysql->getInt($id);
    $query = $mysql->query($sql);
    $rows = $mysql->fetchArray($query);

    if (isset($_POST['accept_' . $id])) {
        $code=$_POST['unlock_code_' . $id];
        $action = "ACCEPT";
        $objCredits->processFile($id, $rows[0]['user_id'], $rows[0]['credits']);

        $sql = 'update 
						' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=1, 
                                                im.unlock_code = ' . $mysql->quote($_POST['unlock_code_' . $id]) . ',
						im.reply_date_time=now(),
						um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
						where im.status=-1 and um.id = im.user_id and im.id=' . $mysql->getInt($id);

        $mysql->query($sql);
        $sql = 'select (c.amount-b.amount) as profit,d.reseller_id, (select credits from nxt_user_master where id=d.reseller_id) as credits 
 from nxt_order_file_service_master as a
 left join nxt_file_service_amount_details as b
 on a.file_service_id=b.service_id
left join nxt_file_spl_credits_reseller as c
on a.file_service_id=c.service_id
left join nxt_user_master as d
on a.user_id=d.id
where a.id=' . $id . '
and b.currency_id=d.currency_id';
        $query = $mysql->query($sql);
        $rows1 = $mysql->fetchArray($query);
        $profit = $rows1[0]["profit"];
        $reseller_id = $rows1[0]["reseller_id"];
        $credits_after = $rows1[0]["credits"];
        $credits_after = $credits_after + $profit;
        if ($reseller_id != 0) {
            $sqlAvail .= 'update ' . USER_MASTER . ' um
									set um.credits =um.credits + ' . $profit . '
									where um.id = ' . $reseller_id . ';
                                                                            ';

            $sqlAvail .= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
									(user_id, date_time, credits,credits_after,order_id_imei,info, trans_type, ip)
								         values(
									              ' . $reseller_id . ',
											now(),
											' . $profit . ',
                                                                                        ' . $credits_after . ',
                                                                                        ' . $id . ',    
											' . $mysql->quote("Reseller Profit from File Order") . ',
											1,
											' . $mysql->quote($ip) . '
										);
                                                                                ';
            $mysql->multi_query($sqlAvail);
        }
    }
    if (isset($_POST['reject_' . $id])) {
        $action = "REJECT";
        if (!isset($_POST['accept_' . $id])) {
            $objCredits->returnFile($id, $rows[0]['user_id'], $rows[0]['credits']);

            $sql = 'update 
							' . ORDER_FILE_SERVICE_MASTER . ' im, ' . USER_MASTER . ' um
							set
							im.status=2, 
                                                        im.reply_by=' . $admin->getUserId() . ', 
							im.reply_date_time=now(),
							im.reply=' . $mysql->quote($_POST['un_remarks_' . $id]) . ',
							um.credits = um.credits + im.credits, um.credits_inprocess = um.credits_inprocess - im.credits
							where im.status=-1 and um.id = im.user_id and im.id=' . $mysql->getInt($id);
            $mysql->query($sql);
        }
    }

    //Check and send email as per the status
    if ($action != "") {
        $order_id = $id;
        $user_id = $request->PostStr('user_id_' . $id);
        $email = $request->PostStr('email_' . $id);
        $successmailyn = $request->PostStr('sucmail_' . $id);
        $rejectmailyn = $request->PostStr('rejmail_' . $id);
        $username = $request->PostStr('username_' . $id);
        $file_service = $request->PostStr('file_service_' . $id);
        $credits = $request->PostStr('credits_' . $id);
        switch ($action) {
            case "REJECT":
                $objEmail = new email();
                $args = array(
                    'to' => $email,
                    'from' => CONFIG_EMAIL,
                    'fromDisplay' => CONFIG_SITE_NAME,
                    'user_id' => $user_id,
                    'file_name'=>$rows[0]['f_name'],
                    'save' => '1',
                    'username' => $username,
                    'order_id' => $order_id,
                    'file_service' => $file_service,
                    'credits' => $credits,
                    'site_admin' => CONFIG_SITE_NAME
                );
                if ($rejectmailyn == "1")
                    $objEmail->sendEmailTemplate('admin_user_order_file_reject', $args, TRUE);
                break;
            case "ACCEPT":
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
                     'codee' => $code,
                    'credits' => $credits,
                    'site_admin' => CONFIG_SITE_NAME
                );
                if ($successmailyn == "1")
                    $objEmail->sendEmailTemplate('admin_user_order_file_update', $args, TRUE);
                break;
        }
    }
}

header("location:" . CONFIG_PATH_SITE_ADMIN . "order_file.html?type=" . $type . (($pString != '') ? ('&' . $pString) : '') . "&reply=" . urlencode('reply_success_order'));
exit();
?>