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

$admin->checkLogin();
$admin->reject();
//echo '<pre>';
//var_dump($_POST);
//exit;
//Variables to save ids, unavailable message and unlock codes
$Ids = isset($_POST['locked']) ? $_POST['locked'] : array();
$UnAvailIds = $unavailMsg = '';
$AvailIds = $AvailCode = '';
$ip = "";
$sqlAvail = '';
$sqlUnavail = '';

$tempAvails = $tempUnavail = 0;

// Iterate all the items from last page
if (is_array($Ids)) {
    foreach ($Ids as $id) {
        if (isset($_POST['unavailable_' . $id])) {
            $tempAvails++;
            $UnAvailIds .= $id . ',';
            $UnAvailRemarks = $request->PostStr('unlock_code_' . $id);

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
									where im.status in (0,1) and um.id=im.user_id and im.id =' . $id . ';
									
								
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
									where oim.id =' . $id . ';
							
							
							';
        } else {
            $tempUnavail++;
            $unlock_code = $request->PostStr('unlock_code_' . $id);

            if ($unlock_code != '') {
                $AvailIds .= $id . ',';
                //get reseller profit

                $sql = 'select (c.amount-b.amount) as profit,d.reseller_id, (select credits from nxt_user_master where id=d.reseller_id) as credits  from nxt_order_imei_master as a
                                       left join nxt_imei_tool_amount_details as b
                                       on a.tool_id=b.tool_id
                                       left join nxt_imei_spl_credits_reseller as c
                                       on a.tool_id=c.tool_id
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


                //
                // Update all Unlock codes and change the order status
                $sqlAvail .= '
									update 
											' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
										set
											im.status=2,
											im.reply=' . $mysql->quote(base64_encode($unlock_code)) . ',
											im.admin_id_done=' . $admin->getUserId() . ',
											im.reply_date_time=now(),
											um.credits_inprocess = um.credits_inprocess - im.credits,
											um.credits_used = um.credits_used + im.credits
										where im.status=1 and um.id = im.user_id and im.id=' . $id . ';
									
									    
									';
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
											' . $mysql->quote("Reseller Profit From IMEI Order") . ',
											1,
											' . $mysql->quote($ip) . '
										);
                                                                                ';
                }

                /* insert into ' . CREDIT_TRANSECTION_MASTER . '
                  (user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
                  credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)

                  select
                  oim.user_id,
                  now(),
                  oim.credits,
                  um.credits,
                  um.credits_inprocess + oim.credits,
                  um.credits_used - oim.credits,
                  um.credits,
                  um.credits_inprocess,
                  um.credits_used,
                  oim.id,
                  ' . $mysql->quote("IMEI Processed") . ',
                  1,
                  ' . $mysql->quote($ip) . '
                  from ' . ORDER_IMEI_MASTER . ' oim
                  left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
                  left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
                  where oim.id =' . $id . ';'; */
                $doneIMEIs .= $id . ',';
                //  echo $sqlAvail;exit;
                //$mysql->query($sql);
            }
        }

        if ($tempAvails >= 10 && $sqlUnavail != '') {
            $mysql->multi_query($sqlUnavail);
            $sqlUnavail = '';
            $tempAvails = $tempUnavail = 0;
        }
        if ($tempUnavail >= 10 && $sqlAvail != '') {
            $mysql->multi_query($sqlAvail);
            $sqlAvail = '';
            $tempAvails = $tempUnavail = 0;
        }
    }
}

if ($sqlUnavail != '') {
    $mysql->multi_query($sqlUnavail);
}
if ($sqlAvail != '') {
    $mysql->multi_query($sqlAvail);
}




//-----------------------------------------------------email send logic same as before------------------------

/* * ********************************************************
  START: Available
 * ******************************************************** */
$ip = $_SERVER['REMOTE_ADDR'];

$email_config = $objEmail->getEmailSettings();
$from_admin = $email_config['system_email'];
$admin_from_disp = $email_config['system_from'];
$signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

if ($AvailIds != '') {
    $AvailIds = trim($AvailIds, ',');



    $sql = 'select
							um.username, um.email,
							oim.id as oid, oim.user_id uid, oim.reply,
							oim.imei, itm.tool_name, oim.credits
						from ' . ORDER_IMEI_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
					where oim.id in (' . $AvailIds . ')  and um.imei_suc_noti=1
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
                'unlock_code' => base64_decode($row['reply']),
                'order_id' => $row['oid'],
                'tool_name' => $row['tool_name'],
                'credits' => $row['credits'],
                'site_admin' => $admin_from_disp,
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
                    $simple_email_body .= '<h2>Your Unlock Codes</h2>';

                    foreach ($service_order as $order) {
                        $simple_email_body .= '
									<p><b>Service Name:</b>' . $order['tool_name'] . '<p>
									<p><b>IMEI:</b>' . $order['imei'] . '<p>
									<p><b>Unlock Code:</b>' . $order['unlock_code'] . '<p>
									<p><b>Credits:</b>' . $order['credits'] . '<p>
									<p><b>Order ID :</b>' . $order['order_id'] . '<p>
									<p>-------------------------------------------------- 
									<p>--------------------------------------------------
									';
                        $to_user = $order['to'];
                        $user_id = $order['user_id'];
                    }
                    // echo $user_id;
                    //  echo '<pre>';
                    //var_dump($service_order);
                    //exit;
                    $objEmail->setTo($to_user);
                    $objEmail->setFrom($from_admin);
                    $objEmail->setFromDisplay($admin_from_disp);
                    $objEmail->setSubject("Unlock Codes");
                    $objEmail->setBody($simple_email_body . $signatures);
                  //  $sent = $objEmail->sendMail();
                    $save = $objEmail->queue();
                    //echo $save;exit;
                    // SEND Simple Email
                    $simple_email_body = '';
                } else {
                    $objEmail->sendMultiEmailTemplate('admin_user_imei_avail', $service_order);
                }
            }
        }

        ////////./
    }
}

/* * ********************************************************
  END: Available
 * ******************************************************** */


/* * ********************************************************
  START: Unavail
 * ******************************************************** */
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
									<p><b>Reson:</b>' .$order['reason'] . '<p>
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
                  //  $sent = $objEmail->sendMail();
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
/* * ********************************************************
  END: Unavail
 * ******************************************************** */




header("location:" . CONFIG_PATH_SITE_ADMIN . "imei_bulk_reply.html?reply=" . urlencode('reply_success'));
exit();

?>