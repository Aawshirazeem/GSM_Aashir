<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
// require_once '../';
//echo '<pre/>';
//var_dump($_POST);
//exit;
$member->checkLogin();
$member->reject();
$validator->formValidateUser('user_server_log_78665448');

$cookie = new cookie();
$objImei = new imei();
$objCredits = new credits();
$temp_torders=0;
$amount_purchase=0;
$temptype = $_POST['server_log'];
$temptype = preg_split('[-]', $temptype);
$mytpe = $temptype[1];
if ($mytpe == 1) {
    $server_log = $request->PostInt('server_log');

    $custom = $request->PostStr('custom');

    $custom_1 = $request->PostStr('custom_1');
    $custom_name_1 = $request->PostStr('custom_name_1');
    if ($custom_1 != "" || $custom_name_1 != "") {

        $custom_1 = $custom_name_1 . ':' . $custom_1;
    }
    $custom_2 = $request->PostStr('custom_2');
    $custom_name_2 = $request->PostStr('custom_name_2');
    if ($custom_2 != "" || $custom_name_2 != "") {

        $custom_2 = $custom_name_2 . ':' . $custom_2;
    }

    $custom_3 = $request->PostStr('custom_3');
    $custom_name_3 = $request->PostStr('custom_name_3');
    if ($custom_3 != "" || $custom_name_3 != "") {

        $custom_3 = $custom_name_3 . ':' . $custom_3;
    }
    $custom_4 = $request->PostStr('custom_4');
    $custom_name_4 = $request->PostStr('custom_name_4');
    if ($custom_4 != "" || $custom_name_4 != "") {

        $custom_4 = $custom_name_4 . ':' . $custom_4;
    }
    $custom_5 = $request->PostStr('custom_5');
    $custom_name_5 = $request->PostStr('custom_name_5');
    if ($custom_5 != "" || $custom_name_5 != "") {

        $custom_5 = $custom_name_5 . ':' . $custom_5;
    }

    $mobile = $request->PostStr('mobile');
    $message = $request->PostStr('message');
    $remarks = $request->PostStr('remarks');
     $chimera = $request->PostStr('chimera');
    if($chimera=="1"){
    $chimera_amount = $request->PostStr('amount');
    $chimera_amount_log = $request->PostStr('total_amount');}
    else
    $chimera_amount = $request->PostStr('price');    
    $chimera_email = $request->PostStr('email');
   
    //  $chimera_price = $request->PostStr('price');
    //   echo $chimera_amount;exit;


    if ($chimera == "1") {
        //   echo 'chimra run';
        //  exit;
        // echo $custom;
        // exit;
          $mysql->query('SET SQL_BIG_SELECTS=1');
        $sql_cr = 'select
					slm.*,
					slgm.group_name,
					slad.amount,slad.amount_purchase,
					slsc.amount splCr,
                                        slscr.amount splres,
					pslm.amount as packageCr,
					cm.prefix, cm.suffix
				from ' . SERVER_LOG_MASTER . ' slm
				left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
				left join ' . SERVER_LOG_AMOUNT_DETAILS . ' slad on(slad.log_id=slm.id and slad.currency_id = ' . $member->getCurrencyID() . ')
				left join ' . SERVER_LOG_SPL_CREDITS . ' slsc on(slsc.user_id = ' . $member->getUserID() . ' and slsc.log_id=slm.id)
				left join ' . SERVER_LOG_SPL_CREDITS_RESELLER . ' slscr on(slscr.user_id = ' . $member->getUserID() . ' and slscr.log_id=slm.id)
				left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserID() . ')
				left join ' . PACKAGE_SERVER_LOG_DETAILS . ' pslm on(pslm.package_id = pu.package_id and pslm.currency_id = ' . $member->getCurrencyID() . ' and pslm.log_id = slm.id)
				where slm.id=' . $mysql->getInt($server_log);
        $resultCredits = $mysql->getResult($sql_cr);
        $rowCr = $resultCredits['RESULT'][0];

        $prefix = $rowCr['prefix'];
        $suffix = $rowCr['suffix'];
        $amount = $mysql->getFloat($rowCr['amount']);
        $amount_purchase = $mysql->getFloat($rowCr['amount_purchase']);
        $amountSpl = $mysql->getFloat($rowCr['splCr']);
        $amountPackage = $mysql->getFloat($rowCr['packageCr']);
        $amountDiscount = 0;
        $isSpl = false;
        if ($rowCr["splres"] == "") {
            if ($amountSpl > 0) {
                $isSpl = true;
                $amountDiscount = $amount - $amountSpl;
                $amount = $amountSpl;
            }
            if ($amountPackage > 0) {
                $isSpl = true;
                $amountDiscount = $amount - $amountPackage;
                $amount = $amountPackage;
            }
        } else {
            $isSpl = false;
            $amount = $rowCr["splres"];
        }
        $crAcc = 0;
        $sql_credits = 'select id, credits from ' . USER_MASTER . ' where id=' . $mysql->getInt($member->getUserId());
        $query_credits = $mysql->query($sql_credits);
        $row_credits = $mysql->fetchArray($query_credits);
        $crAcc = $row_credits[0]["credits"];

        if ($crAcc >= $chimera_amount) {

            // transferCredit example
            $chimera_user_id = $rowCr["chimera_user_id"];
            $api_key = $rowCr["chimera_api_key"];
            $url = "https://chimeratool.com/rapi/transfer?userId=" . $custom . '&amount=' . $chimera_amount . '&username=' . $chimera_user_id . '&apikey=' . $api_key;

//            $curl = curl_init();
//            curl_setopt($curl, CURLOPT_URL, $url);
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($curl, CURLOPT_HEADER, false);
//            $response = curl_exec($curl);
//            var_dump($response);exit;

            $chimera = new ChimeraApi($chimera_user_id, $api_key);
            // transfering 100 credit to user identified by ID 6
            $response = $chimera->checkUser($custom);
            if ($response['success']) {
                $target_user_id = $response['userId'];
                //  echo $target_user_id;exit;
                $response = $chimera->transferCredit($target_user_id, $chimera_amount);
                //echo '<pre/>';
                //var_dump($response);exit;
                if ($response['success'] == 1) {

                    $ip = gethostbynamel($_SERVER['REMOTE_ADDR']);

                    $sql = 'insert into ' . ORDER_SERVER_LOG_MASTER . ' 
					(server_log_id, user_id, ip, date_time, credits,b_rate, credits_discount, 
						custom_value,custom_1,custom_2,custom_3,custom_4,custom_5, mobile, message, remarks, status) values(
					' . $mysql->getInt($server_log) . ',
					' . $mysql->getInt($member->getUserId()) . ',
					' . $mysql->quote($ip[0]) . ',
					now(),
					' . $mysql->getFloat($chimera_amount_log) . ',
                                            ' . $mysql->getFloat($amount_purchase) . ',
					' . $mysql->getFloat($amountDiscount) . ',
					' . $mysql->quote($custom) . ',
                                            ' . $mysql->quote($custom_1) . ',
                                                ' . $mysql->quote($custom_2) . ',
                                                    ' . $mysql->quote($custom_3) . ',
                                                        ' . $mysql->quote($custom_4) . ',
                                                            ' . $mysql->quote($custom_5) . ',
					' . $mysql->quote($mobile) . ',
					' . $mysql->quote($message) . ',
					' . $mysql->quote($remarks) . ',
					1
					)';
                    $mysql->query($sql);

                    $newOrderID = $mysql->insert_id();

                    $objCredits->cutOrderCredits($newOrderID, $chimera_amount, $member->getUserID(), 3);
                    echo("<script>location.href = '" . CONFIG_PATH_SITE_USER . "server_logs.html?code=1&reply=" . $response['message'] . "';</script>");
                    exit();
                } else {
                    echo("<script>location.href = '" . CONFIG_PATH_SITE_USER . "server_logs.html?code=0&reply=" . $response['message'] . "';</script>");

                    exit();
                }
            } else {
                echo("<script>location.href = '" . CONFIG_PATH_SITE_USER . "server_logs.html?code=0&reply=Cannot Transfer To This User';</script>");
                exit();
            }
            //var_dump($response);exit;
        } else {
            echo("<script>location.href = '" . CONFIG_PATH_SITE_USER . "server_logs.html?code=0&reply=" . urlencode('reply_insffi_credit') . "';</script>");

            exit();
        }
    }
    // echo $amount.'-'.$chimera;
    //exit;

    if ($server_log == 0) {
        header('location:' . CONFIG_PATH_SITE_USER . 'server_logs_submit.html?reply=' . urlencode('reply_service_missing'));
        exit();
    }

  $mysql->query('SET SQL_BIG_SELECTS=1');
    $sql_cr = '
				select
						slm.credits,
						slsc.credits as splCr,
						pd.credits as packageCr,
						cm.prefix, cm.suffix
				from ' . SERVER_LOG_MASTER . ' slm
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
				left join ' . SERVER_LOG_SPL_CREDITS . ' slsc on (slm.id = slsc.log_id and slsc.user_id = ' . $member->getUserId() . ')
				left join ' . SERVER_LOG_AMOUNT_DETAILS . ' slad on(slad.log_id=slm.id and slad.currency_id = ' . $member->getCurrencyID() . ')
				left join ' . SERVER_LOG_SPL_CREDITS . ' slsc on(slsc.user_id = ' . $member->getUserID() . ' and slsc.log_id=slm.id)
				left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserID() . ')
				left join ' . PACKAGE_SERVER_LOG_DETAILS . ' pslm on(pslm.package_id = pu.package_id and pslm.currency_id = ' . $member->getCurrencyID() . ' and pslm.log_id = slm.id)
				where slm.id=' . $mysql->getInt($server_log);

    $sql_cr = 'select
					slm.*,
					slgm.group_name,
					slad.amount,slad.amount_purchase,
					slsc.amount splCr,
                                        slscr.amount splres,
					pslm.amount as packageCr,
					cm.prefix, cm.suffix
				from ' . SERVER_LOG_MASTER . ' slm
				left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
				left join ' . SERVER_LOG_AMOUNT_DETAILS . ' slad on(slad.log_id=slm.id and slad.currency_id = ' . $member->getCurrencyID() . ')
				left join ' . SERVER_LOG_SPL_CREDITS . ' slsc on(slsc.user_id = ' . $member->getUserID() . ' and slsc.log_id=slm.id)
				left join ' . SERVER_LOG_SPL_CREDITS_RESELLER . ' slscr on(slscr.user_id = ' . $member->getUserID() . ' and slscr.log_id=slm.id)
				left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserID() . ')
				left join ' . PACKAGE_SERVER_LOG_DETAILS . ' pslm on(pslm.package_id = pu.package_id and pslm.currency_id = ' . $member->getCurrencyID() . ' and pslm.log_id = slm.id)
				where slm.id=' . $mysql->getInt($server_log);
    $resultCredits = $mysql->getResult($sql_cr);
    $rowCr = $resultCredits['RESULT'][0];
    //var_dump($rowCr);exit;
    $prefix = $rowCr['prefix'];
    $suffix = $rowCr['suffix'];
    $email_notification_server = $rowCr['is_send_noti'];
    $tool_name = $rowCr['server_log_name'];
    $amount = $mysql->getFloat($rowCr['amount']);
    $amount_purchase = $mysql->getFloat($rowCr['amount_purchase']);
    $amountSpl = $mysql->getFloat($rowCr['splCr']);
    $amountPackage = $mysql->getFloat($rowCr['packageCr']);
    $amountDiscount = 0;
    $isSpl = false;
    if ($rowCr["splres"] == "") {
        if ($amountSpl > 0) {
            $isSpl = true;
            $amountDiscount = $amount - $amountSpl;
            $amount = $amountSpl;
        }
        if ($amountPackage > 0) {
            $isSpl = true;
            $amountDiscount = $amount - $amountPackage;
            $amount = $amountPackage;
        }
    } else {
        $isSpl = false;
        $amount = $rowCr["splres"];
    }
    $crAcc = 0;
    $sql_credits = 'select id, ovd_c_limit,credits from ' . USER_MASTER . ' where id=' . $mysql->getInt($member->getUserId());
    $query_credits = $mysql->query($sql_credits);
    $row_credits = $mysql->fetchArray($query_credits);
    $crAcc = $row_credits[0]["credits"];
    $crAcc_over=$row_credits[0]["ovd_c_limit"];

    if ($crAcc >= $amount || ($amount-$crAcc)<=$crAcc_over ) {
        $ip = gethostbynamel($_SERVER['REMOTE_ADDR']);

        $sql = 'insert into ' . ORDER_SERVER_LOG_MASTER . ' 
					(server_log_id, user_id, ip, date_time, credits,b_rate, credits_discount, 
						custom_value,custom_1,custom_2,custom_3,custom_4,custom_5, mobile, message, remarks, status) values(
					' . $mysql->getInt($server_log) . ',
					' . $mysql->getInt($member->getUserId()) . ',
					' . $mysql->quote($ip[0]) . ',
					now(),
					' . $mysql->getFloat($chimera_amount) . ',
                                            ' . $mysql->getFloat($amount_purchase) . ',
					' . $mysql->getFloat($amountDiscount) . ',
					' . $mysql->quote($custom) . ',
                                            ' . $mysql->quote($custom_1) . ',
                                                ' . $mysql->quote($custom_2) . ',
                                                    ' . $mysql->quote($custom_3) . ',
                                                        ' . $mysql->quote($custom_4) . ',
                                                            ' . $mysql->quote($custom_5) . ',
					' . $mysql->quote($mobile) . ',
					' . $mysql->quote($message) . ',
					' . $mysql->quote($remarks) . ',
					0
					)';
        $mysql->query($sql);

        $newOrderID = $mysql->insert_id();

        $objCredits->cutOrderCredits($newOrderID, $amount, $member->getUserID(), 3);
         $temp_torders=$temp_torders+1;
        // email send logic
        
          if ($newOrderID) {

        $body = '
				<h4>Dear Admin</h4>
				<p>New Server Log orderReceived:</p>
                                <p>Orders details<p>
				<p><b>TOOL NAME:</b><br>' . $tool_name . '<p>
				<p><b>Order ID:</b><br>' . $newOrderID . '</p>
				<p>--------------------------------------------------				
				';

        $emailObj = new email();
        $email_config = $emailObj->getEmailSettings();
        //echo '<pre>'; print_r($email_config);echo '</pre>';

        $admin_email = $email_config['admin_email'];
        $from_admin = $email_config['system_email'];
        $admin_from_disp = $email_config['system_from'];
        $support_email = $email_config['support_email'];
        $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

        $emailObj->setTo($admin_email);
        $emailObj->setFrom($from_admin);
        $emailObj->setFromDisplay($admin_from_disp);
        $emailObj->setSubject("New SERVER LOG Order");
        $emailObj->setBody($body);
        if ($email_notification_server=="1") {
           // $sent = $emailObj->sendMail();
             $save = $emailObj->queue();
        }


        // end mail
    }
   if($temp_torders>0)
    {
        $sql_ins='insert into nxt_notifications (service_id,order_type,total_orders,display) values( ' . $mysql->getInt($server_log) . ',3,' . $mysql->getInt($temp_torders) . ',1)';
        $mysql->query($sql_ins);
        
    }
        
        

        header('location:' . CONFIG_PATH_SITE_USER . 'server_logs.html?reply=' . urlencode('reply_submit_success'));
        exit();
    } else {
        header('location:' . CONFIG_PATH_SITE_USER . 'server_logs_submit.html?reply=' . urlencode('reply_insffi_credit'));
        exit();
    }



    header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html');
    exit();
} elseif ($mytpe == 2) {
    $crM = $objCredits->getMemberCredits();
    $prefix = $crM['prefix'];
    $suffix = $crM['suffix'];
    $rate = $crM['rate'];


    $prepaid_log = $request->PostInt('server_log');
    $remarks = $request->PostStr('remarks');

  $mysql->query('SET SQL_BIG_SELECTS=1');
    $sql_cr = '
				select
						slm.credits,
						slsc.credits as splCr,
						pd.credits as packageCr
				from ' . PREPAID_LOG_MASTER . ' slm
				left join ' . PREPAID_LOG_SPL_CREDITS . ' slsc on (slm.id = slsc.log_id and slsc.user_id = ' . $mysql->getInt($member->getUserId()) . ')
				left join ' . PACKAGE_PREPAID_LOG_DETAILS . ' pd on(slm.id = pd.prepaid_log_id and pd.package_id=(
					select package_id from ' . PACKAGE_USERS . ' pud where pud.user_id = ' . $member->getUserId() . '))
				where slm.id=' . $mysql->getInt($prepaid_log);

    $sql_cr = 'select
					plm.*,
					plgm.group_name,
					plad.amount,plad.amount_purchase,
					plsc.amount splCr,
                                        plscr.amount splres,
					pplm.amount as packageCr,
					cm.prefix, cm.suffix
				from ' . PREPAID_LOG_MASTER . ' plm
				left join ' . PREPAID_LOG_GROUP_MASTER . ' plgm on(plm.group_id = plgm.id)
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
				left join ' . PREPAID_LOG_AMOUNT_DETAILS . ' plad on(plad.log_id=plm.id and plad.currency_id = ' . $member->getCurrencyID() . ')
				left join ' . PREPAID_LOG_SPL_CREDITS . ' plsc on(plsc.user_id = ' . $member->getUserID() . ' and plsc.log_id=plm.id)
				left join ' . PREPAID_LOG_SPL_CREDITS_RESELLER . ' plscr on(plscr.user_id = ' . $member->getUserID() . ' and plscr.log_id=plm.id)
				left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserID() . ')
				left join ' . PACKAGE_PREPAID_LOG_DETAILS . ' pplm on(pplm.package_id = pu.package_id and pplm.currency_id = ' . $member->getCurrencyID() . ' and pplm.log_id = plm.id)
				where plm.id=' . $mysql->getInt($prepaid_log);
    $resultCredits = $mysql->getResult($sql_cr);
    $rowCr = $resultCredits['RESULT'][0];
    $email_notification_plog = $rowCr['is_send_noti'];
    $tool_name = $rowCr['prepaid_log_name'];
    $amount = $mysql->getFloat($rowCr['amount']);
    $amount_purchase = $mysql->getFloat($rowCr['amount_purchase']);
    $amountSpl = $mysql->getFloat($rowCr['splCr']);
    $amountPackage = $mysql->getFloat($rowCr['packageCr']);
    $amountDiscount = 0;
    $isSpl = false;
    if ($rowCr["splres"] == "") {
        if ($amountSpl > 0) {
            $isSpl = true;
            $amountDiscount = $amount - $amountSpl;
            $amount = $amountSpl;
        }
        if ($amountPackage > 0) {
            $isSpl = true;
            $amountDiscount = $amount - $amountPackage;
            $amount = $amountPackage;
        }
    } else {
        $isSpl = false;
        $amount = $rowCr["splres"];
    }
    $sql_total = 'select count(id) as total from ' . PREPAID_LOG_UN_MASTER . ' where prepaid_log_id=' . $mysql->getInt($prepaid_log) . ' and status=0';
    $query_total = $mysql->query($sql_total);
    $rows_total = $mysql->fetchArray($query_total);
    $total = $rows_total[0]['total'];
    if ($total == 0) {
        header('location:' . CONFIG_PATH_SITE_USER . 'prepaid_logs_submit.html?reply=' . urlencode('reply_not_available_prepaid') . '&type=error');
        exit();
    }

    $crAcc = 0;
    $sql_credits = 'select id,ovd_c_limit, credits from ' . USER_MASTER . ' where id=' . $mysql->getInt($member->getUserId());
    $query_credits = $mysql->query($sql_credits);
    $row_credits = $mysql->fetchArray($query_credits);
    $crAcc = $row_credits[0]["credits"];
    $crAcc_over = $row_credits[0]["ovd_c_limit"];

    if ($crAcc >= $amount || ($amount-$crAcc)<=$crAcc_over ) {
        $ip = gethostbynamel($_SERVER['REMOTE_ADDR']);
        $random_no = rand(1, 5630400);
        $sql = 'update ' . PREPAID_LOG_UN_MASTER . ' set
					user_id=' . $mysql->getInt($member->getUserId()) . ',
					ip = ' . $mysql->quote($ip[0]) . ',
					remarks=' . $mysql->quote($remarks) . ',
					date_time=now(),
					status=1,
                                        credit=' . $amount . ',
                                             b_rate=' . $amount_purchase . ',
                                        random_no=' . $random_no . '    
                                        
					where status=0
					and prepaid_log_id=' . $prepaid_log . '
					order by ID
					limit 1';
        $mysql->query($sql);
        
        
        // send an email to admin about this order
        
        
        
        $ok = 'select id from ' . PREPAID_LOG_UN_MASTER . ' where random_no=' . $random_no;
        $ok = $mysql->query($ok);
        $ok = $mysql->fetchArray($ok);
        $pre_id = $ok[0]['id'];
        
        
        
        // var_dump($ok);exit;
        //distribute profit of prepaid log
        //
		$objCredits->cutOrderCreditsDirect(0, $amount, $member->getUserID(), 4);
                  $mysql->query('SET SQL_BIG_SELECTS=1');
        $sql = 'select (c.amount-b.amount) as profit,d.reseller_id, (select credits from nxt_user_master where id=d.reseller_id) as credits 
 from nxt_prepaid_log_un_master as a
 left join nxt_prepaid_log_amount_details as b
 on a.prepaid_log_id=b.log_id
left join nxt_prepaid_log_spl_credits_reseller as c
on a.prepaid_log_id=c.log_id
left join nxt_user_master as d
on a.user_id=d.id
where a.id=' . $pre_id . '
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
                                                                                        ' . $pre_id . ',    
											' . $mysql->quote("Reseller Profit from Prepaid Log Order") . ',
											1,
											' . $mysql->quote($ip) . '
										);
                                                                                ';
            $mysql->multi_query($sqlAvail);
        }
        
        
        // send email to admin about that
        if($pre_id!="")
        {
        $body = '
				<h4>Dear Admin</h4>
				<p>New Prepaid Log order Received:</p>
                                <p>Orders details<p>
				<p><b>TOOL NAME:</b><br>' . $tool_name . '<p>
				<p><b>Order ID:</b><br>' . $pre_id . '</p>
				<p>--------------------------------------------------				
				';

        $emailObj = new email();
        $email_config = $emailObj->getEmailSettings();
        //echo '<pre>'; print_r($email_config);echo '</pre>';

        $admin_email = $email_config['admin_email'];
        $from_admin = $email_config['system_email'];
        $admin_from_disp = $email_config['system_from'];
        $support_email = $email_config['support_email'];
        $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

        $emailObj->setTo($admin_email);
        $emailObj->setFrom($from_admin);
        $emailObj->setFromDisplay($admin_from_disp);
        $emailObj->setSubject("New PREPAID LOG Order");
        $emailObj->setBody($body);
        if ($email_notification_plog=="1") {
          //  $sent = $emailObj->sendMail();
             $save = $emailObj->queue();
        }
        }
        
        
        
        header('location:' . CONFIG_PATH_SITE_USER . 'server_logs.html?reply=' . urlencode('reply_submit_success'));
        exit();

        //	header('location:' . CONFIG_PATH_SITE_USER . 'prepaid_logs.html?reply=' . urlencode('reply_submit_pre_log_success'));
        //exit();
    } else {
        header('location:' . CONFIG_PATH_SITE_USER . 'server_logs_submit.html?reply=' . urlencode('reply_insffi_credit') . '&type=error');
        exit();
    }



    header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html');
    exit();
} else {
    
}
?>