<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$objCredits = new credits();

$admin->checkLogin();
$admin->reject();

$qStrIds = "";
$Ids = $_POST['locked'];
$order_type = "avail";
//echo '<pre>';
//var_dump($_POST);

if ($_POST['reject_all'] == "Bulk Reject" && isset($_POST['locked'])) {
    // echo 'DO SOMETHIG';
    foreach ($Ids as $id) {

        $order_id = $id;
        $new_reason = "By Mistake";
        if ($_POST['b_reject_reason'] != "")
            $new_reason = trim($_POST['b_reject_reason']);;
        $sql = '
					update 
						' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
						set
							im.status=1, 
                                                        im.reply="",
                                                        im.reply_date_time="0000-00-00 00:00:00",
							um.credits_inprocess = um.credits_inprocess + im.credits,
											um.credits_used = um.credits_used - im.credits
						where im.status=2 and um.id=im.user_id and im.id =' . $order_id;
        $mysql->query($sql);

        // now from inprocess to reject

        $UnAvailIds .= $id . ',';
        $UnAvailRemarks = $new_reason;

        $sqlUnavail .= '
								update 
									' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
									set
										im.status=3, 
										im.admin_id_done=' . $admin->getUserId() . ',
										im.reply_date_time=now(),
										im.message = ' . $mysql->quote(base64_encode($UnAvailRemarks)) . ',
										um.credits = um.credits + im.credits,
										um.credits_inprocess = um.credits_inprocess - im.credits
									where im.status in (0,1) and um.id=im.user_id and im.id =' . $order_id . ';
									
								
								insert into ' . CREDIT_TRANSECTION_MASTER . '
									(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
									credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)
									
									select 
											oim.user_id,
											now(),
											oim.credits,
											um.credits - oim.credits,
											um.credits_inprocess + oim.credits,
											um.credits_used,
											um.credits,
											um.credits_inprocess,
											um.credits_used,
											oim.id,
											' . $mysql->quote("IMEI Return") . ',
											0,
											' . $mysql->quote($ip) . '
										from ' . ORDER_IMEI_MASTER . ' oim 
										left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
										left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
									where oim.id =' . $order_id . ';
							
							
							';

        if ($sqlUnavail != '') {
            $mysql->multi_query($sqlUnavail);
        }
    }


    /*     * ********************************************************
      START: Unavail
     * ******************************************************** */
    $ip = $_SERVER['REMOTE_ADDR'];

    $email_config = $objEmail->getEmailSettings();
    $from_admin = $email_config['system_email'];
    $admin_from_disp = $email_config['system_from'];
    $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);
    if ($UnAvailIds != '') {
        $UnAvailIds = trim($UnAvailIds, ',');


        $sql = 'select
							um.username, um.email,oim.message as reason,
							oim.id as oid, oim.user_id uid, oim.reply,
							oim.imei, itm.tool_name, oim.credits
						from ' . ORDER_IMEI_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
					where oim.id in (' . $UnAvailIds . ') and um.imei_rej_noti=1 
					order by uid,tool_name,oid';
        $query = $mysql->query($sql);

        if ($mysql->rowCount($query) > 0) {
            $rows = $mysql->fetchArray($query);
            $argsAll = array();
            foreach ($rows as $row) {
                $args = array(
                    'to' => $row['email'],
                    'from' => $from_admin,
                    'fromDisplay' => $admin_from_disp,
                    'user_id' => $row['uid'],
                    'save' => '1',
                    'username' => $row['username'],
                    'imei' => $row['imei'],
                    'order_id' => $row['oid'],
                    'tool_name' => $row['tool_name'],
                    'credits' => $row['credits'],
                    'site_admin' => $admin_from_disp,
                    'reason' => base64_decode($row['reason']),
                    'send_mail' => true
                );
                array_push($argsAll, $args);
            }

            ////////New Code for Same User / Same Service orders in 1 email
            $to_user = '';

            $new_orders_array = array();
            $simple_email_body = '';
            foreach ($argsAll as $args) {  // each iteration/order
                $new_orders_array[$args['user_id']][$args['tool_name']][] = $args;
            }

            foreach ($new_orders_array as $user) {
                foreach ($user as $service_order) {
                    if (sizeof($service_order) > 1) {
                        $simple_email_body .= '<h2>Your Unlock Codes are Cancelled</h2>';

                        foreach ($service_order as $order) {
                            $simple_email_body .= '
									<p><b>Service Name:</b>' . $order['tool_name'] . '<p>
									<p><b>IMEI:</b>' . $order['imei'] . '<p>
									<p><b>Reson:</b>' . $order['reason'] . '<p>
									<p><b>Credits:</b>' . $order['credits'] . '<p>
									<p><b>Order ID :</b>' . $order['order_id'] . '<p>
									<p>-------------------------------------------------- 
									<p>--------------------------------------------------
									';
                            $to_user = $order['to'];
                        }
                        $objEmail->setTo($to_user);
                        $objEmail->setFrom($from_admin);
                        $objEmail->setFromDisplay($admin_from_disp);
                        $objEmail->setSubject("Unlock Codes");
                        $objEmail->setBody($simple_email_body . $signatures);
                        //$sent = $objEmail->sendMail();
                        $save = $objEmail->queue();
                        // SEND Simple Email
                        $simple_email_body = '';
                    } else {
                        $objEmail->sendMultiEmailTemplate('admin_user_imei_unavail', $service_order);
                    }
                }
            }

            ////////./
        }
    }
    /*     * ********************************************************
      END: Unavail
     * ******************************************************** */

    header("location:" . CONFIG_PATH_SITE_ADMIN . "order_imei.html?status=done&type=" . $order_type . "");
    exit();
} else {
    //  echo 'NO ACTION';
    header("location:" . CONFIG_PATH_SITE_ADMIN . "order_imei.html?status=notdone&type=" . $order_type . "");
    exit();
}
?>