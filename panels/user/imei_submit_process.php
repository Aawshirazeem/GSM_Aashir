<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


$member->checkLogin();
$member->reject();
$validator->formValidateUser('user_imei_sub_292377338');


/* Objects */
$cookie = new cookie();
$objImei = new imei();
$objCredits = new credits();


$temp_torders=0;
$ip = $_SERVER['REMOTE_ADDR'];


$tempDoneIMEIS = $tempInvalidIMEIs = $tempDuplicateIEMIs = "";


// custome fieldss

$amount_purchase=0;
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


/* Check and make IMEI Array */
$imeis = $request->PostStr('imeis');
$imei = $request->PostStr('imei');
$imeilastdigit = $request->PostStr('imeilastdigit');
if ($imei != '' && $imeis == '') {
    $imeis = $imei . $imeilastdigit;
}
$imeis = str_replace(PHP_EOL, ",", $imeis);
$tempImeiList = explode(",", $imeis);
$singleim = $imei . $imeilastdigit;
if ($imei != '' && $imeis != '' && $tempImeiList[0] != $singleim) {
    array_push($tempImeiList, $singleim);
    //$imeis = $imei . $imeilastdigit;	
}
$count = 0;
$imeiList = array();
foreach ($tempImeiList as $tempImei) {
    if ($tempImei != "") {
        $imeiList[$count] = trim(trim($tempImei), PHP_EOL);
        $count++;
    }
}

/* Return if no IMEI entered */
if (($imeis == "" or $imeis == "0") && $imei == "") {
    $result = 'reply_imei_miss';
    //header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html?reply='  . urlencode('reply_imei_miss'));
    //exit();
}


$tool = $request->PostInt('tool');

// check if thats tool's auto success in on or off 

if ($tool != '') {
    // $sqlsup='select a.email from nxt_supplier_master a where a.id=(select b.supplier_id from nxt_imei_supplier_details b where b.tool='.$tool.')';
    $sqlsup = 'select a.auto_success,a.duplicate_yn from ' . IMEI_TOOL_MASTER . ' a where a.id=' . $tool;
    $preCount = $mysql->rowCount($mysql->query($sqlsup));
    if ($preCount > 0) {
        $query_credits = $mysql->query($sqlsup);
        $row_credits = $mysql->fetchArray($query_credits);
        //$crAcc = $row_credits[0]["credits"];
        //$results = $mysql->getResult($sqlsup);
        //$row=$results[0];
        $auto_successchk = $row_credits[0]["auto_success"];
        $duplicate_yn = $row_credits[0]["duplicate_yn"];
        // $suppcredit=$row_credits[0]["credits_purchase"];
    }
}


if ($auto_successchk == 0) {









    // for get the supplier email id 
    if ($tool != '') {
        // $sqlsup='select a.email from nxt_supplier_master a where a.id=(select b.supplier_id from nxt_imei_supplier_details b where b.tool='.$tool.')';
        $sqlsup = 'select a.email,b.credits_purchase from ' . SUPPLIER_MASTER . ' a inner join nxt_imei_supplier_details b on a.id=b.supplier_id and b.tool=' . $tool;
        $preCount = $mysql->rowCount($mysql->query($sqlsup));
        if ($preCount > 0) {
            $query_credits = $mysql->query($sqlsup);
            $row_credits = $mysql->fetchArray($query_credits);
            //$crAcc = $row_credits[0]["credits"];
            //$results = $mysql->getResult($sqlsup);
            //$row=$results[0];
            $supplieremail = $row_credits[0]["email"];
            $suppcredit = $row_credits[0]["credits_purchase"];
        }
    }

    //
    $brand = $request->PostInt('brand');
    $model = $request->PostInt('model');
    $country = $request->PostInt('country');
    $network = $request->PostInt('network');
    $mep = $request->PostInt('mep');
    $pin = $request->PostStr('pin');
    $prd = $request->PostStr('prd');
    $itype = $request->PostStr('itype');
    $custom_value = $request->PostStr('custom_value');

    $email = $request->PostStr('email');
    $mobile = $request->PostStr('mobile');
    $message = $request->PostStr('message');
    $remarks = $request->PostStr('remarks');
    $a_note = $request->PostStr('admin_note');

    /* check Pack and Special Credits */
    $mysql->query('SET SQL_BIG_SELECTS=1');
    $sql_cr = 'select
					tm.id as tid, tm.imei_field_alpha,tm.is_send_noti, tm.imei_field_length,tm.custom_range,tm.imei_type,
					tm.tool_name, tm.custom_field_name, tm.api_service_id,
					tm.accept_duplicate, tm.verify_checksum,tm.api_auth,
					am.id as api_id, am.server_id, am.api_server,
					ad.model, ad.provider, ad.network,
					itad.amount,
                                        itad.amount_purchase,
					isc.amount splCr,
                                        iscr.amount splCre,
					pim.amount as packageCr,
					igm.group_name,
					cm.prefix, cm.suffix
				from ' . IMEI_TOOL_MASTER . ' tm
				left join ' . IMEI_GROUP_MASTER . ' igm on(tm.group_id = igm.id)
				left join ' . API_MASTER . ' am on (tm.api_id = am.id)
				left join ' . API_DETAILS . ' ad on (ad.service_id = tm.api_service_id and ad.id = tm.api_id)
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
				left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(itad.tool_id=tm.id and itad.currency_id = ' . $member->getCurrencyID() . ')
				left join ' . IMEI_SPL_CREDITS . ' isc on(isc.user_id = ' . $member->getUserId() . ' and isc.tool_id=tm.id)
				left join ' . IMEI_SPL_CREDITS_RESELLER . ' iscr on(iscr.user_id = ' . $member->getUserId() . ' and iscr.tool_id=tm.id)
                                left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserId() . ')
				left join ' . PACKAGE_IMEI_DETAILS . ' pim on(pim.package_id = pu.package_id and pim.currency_id = ' . $member->getCurrencyID() . ' and pim.tool_id = tm.id)
				where tm.id=' . $tool;
    $mysql->query('SET SQL_BIG_SELECTS=1');
    $resultCredits = $mysql->getResult($sql_cr);
    $rowCr = $resultCredits['RESULT'][0];

    $tool_name = $rowCr['tool_name'];
    $email_notification = $rowCr['is_send_noti'];
    $api_auth = $rowCr['api_auth'];
    $api_auth_yn=1;
    if($api_auth==1)
        $api_auth_yn=0;
    $amount = $mysql->getFloat($rowCr['amount']);
      $amount_purchase = $mysql->getFloat($rowCr['amount_purchase']);
    $amountSpl = $rowCr['splCr'];
    $amountPackage = $rowCr['packageCr'];
    $amountDiscount = 0;
    $isSpl = false;
    if ($rowCr["splCre"] == "") {
        if ($amountPackage != '') {
            $isSpl = true;
            $amountPackage = $mysql->getFloat($amountPackage);
            $amountDiscount = $amount - $amountPackage;
            $amount = $amountPackage;
        }
        if ($amountSpl != '') {
            $isSpl = true;
            $amountSpl = $mysql->getFloat($amountSpl);
            $amountDiscount = $amount - $amountSpl;
            $amount = $amountSpl;
        }
    } else {
        $isSpl = false;
        $amount = $mysql->getFloat($rowCr["splCre"]);
    }

    $custom_range = $rowCr['imei_field_length'];
    $imei_type = $rowCr['imei_type'];
    $minCustomRange = 0;
    $maxCustomRange = 0;

    $custom_range = preg_split('[-]', $custom_range);
    $double_check_imei_range = false;


    if (sizeof($custom_range) > 1) {
        //print_r($custom_range);
        $minCustomRange = $custom_range[0];

        $maxCustomRange = $custom_range[1];
        $double_check_imei_range = true;
    } else {
        $minCustomRange = 0;
        $maxCustomRange = ($custom_range[0] == '0' or $custom_range[0] == '') ? 15 : $custom_range[0];
    }

    $alpha = ($rowCr['imei_field_alpha'] == '0') ? false : true;
    //$size = ($rowCr['imei_field_length'] == '0' or $rowCr['imei_field_length'] == '') ? 15 : $rowCr['imei_field_length'];
    // 0 or blank field is considered 15 Length

    $accept_duplicate = $rowCr['accept_duplicate'];
    $verify_checksum = $rowCr['verify_checksum'];

    $crAcc = 0;
    $sql_credits = 'select id,ovd_c_limit,imei_suc_noti, credits from ' . USER_MASTER . ' where id=' . $member->getUserId();
    $query_credits = $mysql->query($sql_credits);
    $row_credits = $mysql->fetchArray($query_credits);
    $crAcc = $row_credits[0]["credits"];
    $crAcc = round($crAcc, 2);
    $crAcc_over = $row_credits[0]["ovd_c_limit"];
    $email_notification_user = $row_credits[0]['imei_suc_noti'];
    $crAcc_over = round($crAcc_over, 2);
    $crTotal = $amount * count($imeiList);
    $crTotal = round($crTotal, 2);



    if ($crAcc < $crTotal) {
        $result = 'reply_insuff_credits';
    }

    $newOrderID = false;

    if ($crAcc >= $crTotal || ($crTotal - $crAcc) <= $crAcc_over) {
        $api_id = $rowCr['api_id'];
        $api_name = $rowCr['api_server'];
        $api_service_id = $rowCr['api_service_id'];

        $all_imeis = '';
        foreach ($imeiList as $imei) {
            $imei = trim($imei);
            $all_imeis .= $imei . '<br>';
            if ($imei != "") {
                // check for valid IMEIs
                $verify_checksum = ($verify_checksum == "1") ? true : false;
                if ($double_check_imei_range) {
                    if ($imei_type != 2) {
                        if ((!$objImei->checkIMEI($imei, $verify_checksum, $alpha, $minCustomRange)) && (!$objImei->checkIMEI($imei, $verify_checksum, $alpha, $maxCustomRange))) {
                            $tempInvalidIMEIs .= $imei . ', ';
                            continue;
                        }
                    }
                } else {
                    if ($imei_type != 2) {
                        if (!$objImei->checkIMEI($imei, $verify_checksum, $alpha, $maxCustomRange)) {
                            $tempInvalidIMEIs .= $imei . ', ';
                            continue;
                        }
                    }
                }


                if ($accept_duplicate == 0) {
                    $sql = 'select id
								from ' . ORDER_IMEI_MASTER . '
								where imei = ' . $mysql->quote($imei) . '
								and user_id = ' . $member->getUserId() . '
								and tool_id = ' . $mysql->getInt($tool) . '
								and status in (0,1,2)
								limit 1';
                    $preCount = $mysql->rowCount($mysql->query($sql));
                    if ($preCount >= 1) {
                        $tempDuplicateIEMIs .= $imei . ', ';
                        continue;
                    }
                }
                //$now=date('Y-m-d H:i:s');
                // check imei in old orders

                if ($duplicate_yn == 1) {

                    $sqlchkimei = 'select oim.imei,oim.reply from ' . ORDER_IMEI_MASTER . ' oim where oim.`status`=2 and oim.tool_id='.$tool.' and oim.imei=' . $mysql->quote($imei) . '

                            order by oim.date_time desc limit 1';
                    $qrydata = $mysql->query($sqlchkimei);
                    if ($mysql->rowCount($qrydata) > 0) {
                        $rows = $mysql->fetchArray($qrydata);
                        $imeireplyy = $rows[0]['reply'];


                        // add to db with this reply
  $sql = 'insert into ' . ORDER_IMEI_MASTER . '
							(api_id, api_name, api_service_id, tool_id, user_id,
								ip, imei, date_time, credits,b_rate,b_rate_main, credits_discount,
								brand_id, model_id, country_id, network_id,
								mep_id, pin, prd, itype, email, mobile, custom_value,custom_1,custom_2,custom_3,custom_4,custom_5, message,admin_note, remarks) values(
							' . $mysql->getInt($api_id) . ',
							' . $mysql->quote($api_name) . ',
							' . $mysql->getInt($api_service_id) . ',
							' . $mysql->getInt($tool) . ',
							' . $member->getUserId() . ',
							' . $mysql->quote($ip) . ',
							' . $mysql->quote(trim($imei)) . ',
							now(),
							' . $mysql->getFloat($amount) . ',
                                                            ' . $mysql->getFloat($amount_purchase) . ',
                                                                     ' . $mysql->getFloat($amount_purchase) . ',
							' . $mysql->getFloat($amountDiscount) . ',
							' . $mysql->getInt($brand) . ',
							' . $mysql->getInt($model) . ',
							' . $mysql->getInt($country) . ',
							' . $mysql->getInt($network) . ',
							' . $mysql->getInt($mep) . ',
							' . $mysql->quote($pin) . ',
							' . $mysql->quote(($prd == 'PRD-XXXXX-XXX') ? '' : $prd) . ',
							' . $mysql->quote($itype) . ',
							' . (($member->getEmail() == $email) ? '""' : $mysql->quote($email)) . ',
							' . $mysql->quote($mobile) . ',
							' . $mysql->quote($custom_value) . ',
                                                             ' . $mysql->quote($custom_1) . ',
                                                ' . $mysql->quote($custom_2) . ',
                                                    ' . $mysql->quote($custom_3) . ',
                                                        ' . $mysql->quote($custom_4) . ',
                                                            ' . $mysql->quote($custom_5) . ',
							' . $mysql->quote($message) . ',
                                                            ' . $mysql->quote($a_note) . ',
							' . $mysql->quote($remarks) . '
							)';
                        $mysql->query($sql);
                        $newOrderID = $mysql->insert_id();
                        $objCredits->cutOrderCredits($newOrderID, $amount, $member->getUserID(), 1);
                        
                        $sqlAvail= '
									update 
											' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
										set
											im.status=2,
											im.reply=' . $mysql->quote($imeireplyy) . ',
											im.admin_id_done=1,
											im.reply_date_time=now(),
											um.credits_inprocess = um.credits_inprocess - im.credits,
											um.credits_used = um.credits_used + im.credits
										where um.id = im.user_id and im.id=' . $newOrderID . ';
									
									    
									';
 $query = $mysql->query($sqlAvail);
                        // mail send
                        if ($newOrderID) {
                            // send success mail
                            $email_config = $objEmail->getEmailSettings();
                            $from_admin = $email_config['system_email'];
                            $admin_from_disp = $email_config['system_from'];
                            $argsAll = array();

                            $args = array(
                                'to' => $member->getEmail(),
                                'from' => $from_admin,
                                'fromDisplay' => $admin_from_disp,
                                'user_id' => $member->getUserID(),
                                'save' => '1',
                                'username' => $member->getUserName(),
                                'imei' => $imei,
                                'unlock_code' => base64_decode($imeireplyy),
                                'order_id' => $newOrderID,
                                'tool_name' => $tool_name,
                                'credits' => $amount,
                                'site_admin' => $admin_from_disp,
                                'send_mail' => true
                            );
                            array_push($argsAll, $args);
                            if ($email_notification_user == "1") {
                                $objEmail->sendMultiEmailTemplate('admin_user_imei_avail', $argsAll);
                            }
                        }
                    } else {

                        $sql = 'insert into ' . ORDER_IMEI_MASTER . '
							(api_id, api_name, api_service_id, tool_id, user_id,
								ip, imei, date_time, credits,b_rate,b_rate_main, credits_discount,
								brand_id, model_id, country_id, network_id,api_auth,api_auth_yn,
								mep_id, pin, prd, itype, email, mobile, custom_value,custom_1,custom_2,custom_3,custom_4,custom_5, message,admin_note, remarks) values(
							' . $mysql->getInt($api_id) . ',
							' . $mysql->quote($api_name) . ',
							' . $mysql->getInt($api_service_id) . ',
							' . $mysql->getInt($tool) . ',
							' . $member->getUserId() . ',
							' . $mysql->quote($ip) . ',
							' . $mysql->quote(trim($imei)) . ',
							now(),
							' . $mysql->getFloat($amount) . ',
                                                            ' . $mysql->getFloat($amount_purchase) . ',
                                                                     ' . $mysql->getFloat($amount_purchase) . ',
							' . $mysql->getFloat($amountDiscount) . ',
							' . $mysql->getInt($brand) . ',
							' . $mysql->getInt($model) . ',
							' . $mysql->getInt($country) . ',
							' . $mysql->getInt($network) . ',
                                                            ' . $mysql->getInt($api_auth) . ',
                                                                  ' . $mysql->getInt($api_auth_yn) . ',
							' . $mysql->getInt($mep) . ',
							' . $mysql->quote($pin) . ',
							' . $mysql->quote(($prd == 'PRD-XXXXX-XXX') ? '' : $prd) . ',
							' . $mysql->quote($itype) . ',
							' . (($member->getEmail() == $email) ? '""' : $mysql->quote($email)) . ',
							' . $mysql->quote($mobile) . ',
							' . $mysql->quote($custom_value) . ',
                                                             ' . $mysql->quote($custom_1) . ',
                                                ' . $mysql->quote($custom_2) . ',
                                                    ' . $mysql->quote($custom_3) . ',
                                                        ' . $mysql->quote($custom_4) . ',
                                                            ' . $mysql->quote($custom_5) . ',
							' . $mysql->quote($message) . ',
                                                            ' . $mysql->quote($a_note) . ',
							' . $mysql->quote($remarks) . '
							)';
                        $mysql->query($sql);
                        $newOrderID = $mysql->insert_id();
                        $objCredits->cutOrderCredits($newOrderID, $amount, $member->getUserID(), 1);
                        $temp_torders=$temp_torders+1;
                    }
                } else {
                    $sql = 'insert into ' . ORDER_IMEI_MASTER . '
							(api_id, api_name, api_service_id, tool_id, user_id,
								ip, imei, date_time, credits,  b_rate,b_rate_main,credits_discount,
								brand_id, model_id, country_id, network_id,api_auth,api_auth_yn,
								mep_id, pin, prd, itype, email, mobile, custom_value,custom_1,custom_2,custom_3,custom_4,custom_5, message,admin_note, remarks) values(
							' . $mysql->getInt($api_id) . ',
							' . $mysql->quote($api_name) . ',
							' . $mysql->getInt($api_service_id) . ',
							' . $mysql->getInt($tool) . ',
							' . $member->getUserId() . ',
							' . $mysql->quote($ip) . ',
							' . $mysql->quote(trim($imei)) . ',
							now(),
							' . $mysql->getFloat($amount) . ',
                                                                 ' . $mysql->getFloat($amount_purchase) . ',
                                                            ' . $mysql->getFloat($amount_purchase) . ',
							' . $mysql->getFloat($amountDiscount) . ',
							' . $mysql->getInt($brand) . ',
							' . $mysql->getInt($model) . ',
							' . $mysql->getInt($country) . ',
							' . $mysql->getInt($network) . ',
                                                            ' . $mysql->getInt($api_auth) . ',
                                                                    ' . $mysql->getInt($api_auth_yn) . ',
							' . $mysql->getInt($mep) . ',
							' . $mysql->quote($pin) . ',
							' . $mysql->quote(($prd == 'PRD-XXXXX-XXX') ? '' : $prd) . ',
							' . $mysql->quote($itype) . ',
							' . (($member->getEmail() == $email) ? '""' : $mysql->quote($email)) . ',
							' . $mysql->quote($mobile) . ',
							' . $mysql->quote($custom_value) . ',
                                                             ' . $mysql->quote($custom_1) . ',
                                                ' . $mysql->quote($custom_2) . ',
                                                    ' . $mysql->quote($custom_3) . ',
                                                        ' . $mysql->quote($custom_4) . ',
                                                            ' . $mysql->quote($custom_5) . ',
							' . $mysql->quote($message) . ',
                                                            ' . $mysql->quote($a_note) . ',
							' . $mysql->quote($remarks) . '
							)';
                    $mysql->query($sql);
                    $newOrderID = $mysql->insert_id();
                    $objCredits->cutOrderCredits($newOrderID, $amount, $member->getUserID(), 1);
                    $temp_torders=$temp_torders+1;
                }
                $tempDoneIMEIS .= $imei . ', ';
            }
        }
        if ($newOrderID) {
            $objEmail = new email();
            $email_config = $objEmail->getEmailSettings();
            $admin_email = $email_config['admin_email'];
            $system_from = $email_config['system_email'];
            $from_display = $email_config['system_from'];


            $args = array(
                'to' => $admin_email,
                'from' => $system_from,
                'fromDisplay' => $from_display,
                'username' => $member->getUsername(),
                'order_id' => $newOrderID,
                'imei' => $all_imeis,
                'tool_name' => $tool_name,
                'credits' => $amount,
                'send_mail' => true
            );
            $args2 = array(
                'to' => $supplieremail,
                'from' => $system_from,
                'fromDisplay' => $from_display,
                'username' => $member->getUsername(),
                'order_id' => $newOrderID,
                'imei' => $all_imeis,
                'tool_name' => $tool_name,
                'credits' => $suppcredit,
                'send_mail' => true
            );

            // check if service email notification is on ot off
            if ($email_notification == 1) {
                $objEmail->sendEmailTemplate('admin_order_imei_new', $args);
            }
            // send email to supplier if that serive is attach to one
            if ($supplieremail != '') {
                $objEmail->sendEmailTemplate('admin_order_imei_new_supp', $args2);
            }
        }
        $result = 'reply_submit_success';
        //header('location:' . CONFIG_PATH_SITE_USER . 'imei.html?reply=' . urlencode('reply_submit_success'));
        //exit();
    } else {
        $result = 'reply_insuff_credits';
        //header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html?reply=' . urlencode('reply_insuff_credits'));
        //exit();
    }
    //echo "asdf";
    
    // here add for notification
    
    if($temp_torders>0)
    {
        $sql_ins='insert into nxt_notifications (service_id,order_type,total_orders,display) values( ' . $mysql->getInt($tool) . ',1,' . $mysql->getInt($temp_torders) . ',1)';
        $mysql->query($sql_ins);
        
    }
    
    echo json_encode(array(
        'done' => $tempDoneIMEIS,
        'duplicate' => $tempDuplicateIEMIs,
        'invalid' => $tempInvalidIMEIs,
        'result' => $result
    ));

    //echo 'imei_submit';
    //header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html');
    exit();
}
// ----------------------------if auto succsees is on then this--------------------------
else {
    // auto success is on

    if ($tool != '') {
        // $sqlsup='select a.email from nxt_supplier_master a where a.id=(select b.supplier_id from nxt_imei_supplier_details b where b.tool='.$tool.')';
        $sqlsup = 'select a.email,b.credits_purchase from ' . SUPPLIER_MASTER . ' a inner join nxt_imei_supplier_details b on a.id=b.supplier_id and b.tool=' . $tool;
        $preCount = $mysql->rowCount($mysql->query($sqlsup));
        if ($preCount > 0) {
            $query_credits = $mysql->query($sqlsup);
            $row_credits = $mysql->fetchArray($query_credits);
            //$crAcc = $row_credits[0]["credits"];
            //$results = $mysql->getResult($sqlsup);
            //$row=$results[0];
            $supplieremail = $row_credits[0]["email"];
            $suppcredit = $row_credits[0]["credits_purchase"];
        }
    }

    //
    $brand = $request->PostInt('brand');
    $model = $request->PostInt('model');
    $country = $request->PostInt('country');
    $network = $request->PostInt('network');
    $mep = $request->PostInt('mep');
    $pin = $request->PostStr('pin');
    $prd = $request->PostStr('prd');
    $itype = $request->PostStr('itype');
    $custom_value = $request->PostStr('custom_value');

    $email = $request->PostStr('email');
    $mobile = $request->PostStr('mobile');
    $message = $request->PostStr('message');
    $remarks = $request->PostStr('remarks');
    $a_note = $request->PostStr('admin_note');

    /* check Pack and Special Credits */
    $mysql->query('SET SQL_BIG_SELECTS=1');
    $sql_cr = 'select
					tm.id as tid, tm.imei_field_alpha,tm.is_send_noti, tm.imei_field_length,tm.custom_range,
					tm.tool_name, tm.custom_field_name, tm.api_service_id,
					tm.accept_duplicate, tm.verify_checksum,
					am.id as api_id, am.server_id, am.api_server,
					ad.model, ad.provider, ad.network,
					itad.amount,
					isc.amount splCr,
                                        iscr.amount splCre,
					pim.amount as packageCr,
					igm.group_name,
					cm.prefix, cm.suffix
				from ' . IMEI_TOOL_MASTER . ' tm
				left join ' . IMEI_GROUP_MASTER . ' igm on(tm.group_id = igm.id)
				left join ' . API_MASTER . ' am on (tm.api_id = am.id)
				left join ' . API_DETAILS . ' ad on (ad.service_id = tm.api_service_id and ad.id = tm.api_id)
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
				left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(itad.tool_id=tm.id and itad.currency_id = ' . $member->getCurrencyID() . ')
				left join ' . IMEI_SPL_CREDITS . ' isc on(isc.user_id = ' . $member->getUserId() . ' and isc.tool_id=tm.id)
				left join ' . IMEI_SPL_CREDITS_RESELLER . ' iscr on(iscr.user_id = ' . $member->getUserId() . ' and iscr.tool_id=tm.id)
                                left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserId() . ')
				left join ' . PACKAGE_IMEI_DETAILS . ' pim on(pim.package_id = pu.package_id and pim.currency_id = ' . $member->getCurrencyID() . ' and pim.tool_id = tm.id)
				where tm.id=' . $tool;

    $resultCredits = $mysql->getResult($sql_cr);
    $rowCr = $resultCredits['RESULT'][0];

    $tool_name = $rowCr['tool_name'];
    $email_notification = $rowCr['is_send_noti'];
    $amount = $mysql->getFloat($rowCr['amount']);
    $amountSpl = $rowCr['splCr'];
    $amountPackage = $rowCr['packageCr'];
    $amountDiscount = 0;
    $isSpl = false;
    if ($rowCr["splCre"] == "") {
        if ($amountPackage != '') {
            $isSpl = true;
            $amountPackage = $mysql->getFloat($amountPackage);
            $amountDiscount = $amount - $amountPackage;
            $amount = $amountPackage;
        }
        if ($amountSpl != '') {
            $isSpl = true;
            $amountSpl = $mysql->getFloat($amountSpl);
            $amountDiscount = $amount - $amountSpl;
            $amount = $amountSpl;
        }
    } else {
        $isSpl = false;
        $amount = $mysql->getFloat($rowCr["splCre"]);
    }

    $custom_range = $rowCr['imei_field_length'];
    $minCustomRange = 0;
    $maxCustomRange = 0;

    $custom_range = preg_split('[-]', $custom_range);
    $double_check_imei_range = false;


    if (sizeof($custom_range) > 1) {
        //print_r($custom_range);
        $minCustomRange = $custom_range[0];

        $maxCustomRange = $custom_range[1];
        $double_check_imei_range = true;
    } else {
        $minCustomRange = 0;
        $maxCustomRange = ($custom_range[0] == '0' or $custom_range[0] == '') ? 15 : $custom_range[0];
    }

    $alpha = ($rowCr['imei_field_alpha'] == '0') ? false : true;
    //$size = ($rowCr['imei_field_length'] == '0' or $rowCr['imei_field_length'] == '') ? 15 : $rowCr['imei_field_length'];
    // 0 or blank field is considered 15 Length

    $accept_duplicate = $rowCr['accept_duplicate'];
    $verify_checksum = $rowCr['verify_checksum'];

    $crAcc = 0;
    $sql_credits = 'select id, credits,imei_suc_noti from ' . USER_MASTER . ' where id=' . $member->getUserId();
    $query_credits = $mysql->query($sql_credits);
    $row_credits = $mysql->fetchArray($query_credits);
    $crAcc = $row_credits[0]["credits"];
    $email_notification_user = $row_credits[0]['imei_suc_noti'];
    $crTotal = $amount * count($imeiList);



    if ($crAcc < $crTotal) {
        $result = 'reply_insuff_credits';
    }

    $newOrderID = false;

    if ($crAcc >= $crTotal) {
        $api_id = $rowCr['api_id'];
        $api_name = $rowCr['api_server'];
        $api_service_id = $rowCr['api_service_id'];

        $all_imeis = '';
        foreach ($imeiList as $imei) {
            $imei = trim($imei);
            $all_imeis .= $imei . '<br>';
            if ($imei != "") {
                // check for valid IMEIs
                $verify_checksum = ($verify_checksum == "1") ? true : false;
                if ($double_check_imei_range) {
                    if ($imei_type != 2) {
                        if ((!$objImei->checkIMEI($imei, $verify_checksum, $alpha, $minCustomRange)) && (!$objImei->checkIMEI($imei, $verify_checksum, $alpha, $maxCustomRange))) {
                            $tempInvalidIMEIs .= $imei . ', ';
                            continue;
                        }
                    }
                } else {
                    if ($imei_type != 2) {
                        if (!$objImei->checkIMEI($imei, $verify_checksum, $alpha, $maxCustomRange)) {
                            $tempInvalidIMEIs .= $imei . ', ';
                            continue;
                        }
                    }
                }


                if ($accept_duplicate == 0) {
                    $sql = 'select id
								from ' . ORDER_IMEI_MASTER . '
								where imei = ' . $mysql->quote($imei) . '
								and user_id = ' . $member->getUserId() . '
								and tool_id = ' . $mysql->getInt($tool) . '
								and status in (0,1,2)
								limit 1';
                    $preCount = $mysql->rowCount($mysql->query($sql));
                    if ($preCount >= 1) {
                        $tempDuplicateIEMIs .= $imei . ', ';
                        continue;
                    }
                }
                //$now=date('Y-m-d H:i:s');
/*
                $sql = 'insert into ' . ORDER_IMEI_MASTER . '
							(api_id, api_name, api_service_id, tool_id, user_id,
								ip, imei, date_time, credits,b_rate, credits_discount,
								brand_id,status,reply, model_id, country_id, network_id,
								mep_id, pin, prd, itype, email, mobile, custom_value,custom_1,custom_2,custom_3,custom_4,custom_5, message,admin_note, remarks) values(
							' . $mysql->getInt($api_id) . ',
							' . $mysql->quote($api_name) . ',
							' . $mysql->getInt($api_service_id) . ',
							' . $mysql->getInt($tool) . ',
							' . $member->getUserId() . ',
							' . $mysql->quote($ip) . ',
							' . $mysql->quote(trim($imei)) . ',
							now(),
							' . $mysql->getFloat($amount) . ',
                                                            ' . $mysql->getFloat($amount_purchase) . ',
							' . $mysql->getFloat($amountDiscount) . ',
							' . $mysql->getInt($brand) . ',
                                                             ' . $mysql->getInt(2) . ',
                                                        ' . $mysql->quote(base64_encode('Unlocked')) . ',
                                                           
							' . $mysql->getInt($model) . ',
							' . $mysql->getInt($country) . ',
							' . $mysql->getInt($network) . ',
							' . $mysql->getInt($mep) . ',
							' . $mysql->quote($pin) . ',
							' . $mysql->quote(($prd == 'PRD-XXXXX-XXX') ? '' : $prd) . ',
							' . $mysql->quote($itype) . ',
							' . (($member->getEmail() == $email) ? '""' : $mysql->quote($email)) . ',
							' . $mysql->quote($mobile) . ',
							' . $mysql->quote($custom_value) . ',
                                                             ' . $mysql->quote($custom_1) . ',
                                                ' . $mysql->quote($custom_2) . ',
                                                    ' . $mysql->quote($custom_3) . ',
                                                        ' . $mysql->quote($custom_4) . ',
                                                            ' . $mysql->quote($custom_5) . ',
							' . $mysql->quote($message) . ',
                                                               ' . $mysql->quote($a_note) . ',
							' . $mysql->quote($remarks) . '
							)';
                $mysql->query($sql);

                $newOrderID = $mysql->insert_id();




//echo '<pre>';print_r($args);echo '</pre>';
                $objCredits->cutOrderCredits($newOrderID, $amount, $member->getUserID(), 1);*/
                
                
                
                  $sql = 'insert into ' . ORDER_IMEI_MASTER . '
							(api_id, api_name, api_service_id, tool_id, user_id,
								ip, imei, date_time, credits,b_rate, b_rate_main,credits_discount,
								brand_id, model_id, country_id, network_id,
								mep_id, pin, prd, itype, email, mobile, custom_value,custom_1,custom_2,custom_3,custom_4,custom_5, message,admin_note, remarks) values(
							' . $mysql->getInt($api_id) . ',
							' . $mysql->quote($api_name) . ',
							' . $mysql->getInt($api_service_id) . ',
							' . $mysql->getInt($tool) . ',
							' . $member->getUserId() . ',
							' . $mysql->quote($ip) . ',
							' . $mysql->quote(trim($imei)) . ',
							now(),
							' . $mysql->getFloat($amount) . ',
                                                                 ' . $mysql->getFloat($amount_purchase) . ',
                                                            ' . $mysql->getFloat($amount_purchase) . ',
							' . $mysql->getFloat($amountDiscount) . ',
							' . $mysql->getInt($brand) . ',
							' . $mysql->getInt($model) . ',
							' . $mysql->getInt($country) . ',
							' . $mysql->getInt($network) . ',
							' . $mysql->getInt($mep) . ',
							' . $mysql->quote($pin) . ',
							' . $mysql->quote(($prd == 'PRD-XXXXX-XXX') ? '' : $prd) . ',
							' . $mysql->quote($itype) . ',
							' . (($member->getEmail() == $email) ? '""' : $mysql->quote($email)) . ',
							' . $mysql->quote($mobile) . ',
							' . $mysql->quote($custom_value) . ',
                                                             ' . $mysql->quote($custom_1) . ',
                                                ' . $mysql->quote($custom_2) . ',
                                                    ' . $mysql->quote($custom_3) . ',
                                                        ' . $mysql->quote($custom_4) . ',
                                                            ' . $mysql->quote($custom_5) . ',
							' . $mysql->quote($message) . ',
                                                            ' . $mysql->quote($a_note) . ',
							' . $mysql->quote($remarks) . '
							)';
                        $mysql->query($sql);
                        $newOrderID = $mysql->insert_id();
                        $objCredits->cutOrderCredits($newOrderID, $amount, $member->getUserID(), 1);
                        
                        $sqlAvail= '
									update 
											' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
										set
											im.status=2,
											im.reply= ' . $mysql->quote(base64_encode('Unlocked')) . ',
											im.admin_id_done=1,
											im.reply_date_time=now(),
											um.credits_inprocess = um.credits_inprocess - im.credits,
											um.credits_used = um.credits_used + im.credits
										where um.id = im.user_id and im.id=' . $newOrderID . ';
									
									    
									';
 //$query = $mysql->query($sqlAvail);
                
                
                $tempDoneIMEIS .= $imei . ', ';

                // send user an email of orde success
                /// Email code
                if ($newOrderID) {
                    // send success mail
                    $email_config = $objEmail->getEmailSettings();
                    $from_admin = $email_config['system_email'];
                    $admin_from_disp = $email_config['system_from'];
                    $argsAll = array();

                    $args = array(
                        'to' => $member->getEmail(),
                        'from' => $from_admin,
                        'fromDisplay' => $admin_from_disp,
                        'user_id' => $member->getUserID(),
                        'save' => '1',
                        'username' => $member->getUserName(),
                        'imei' => $imei,
                        'unlock_code' => 'Unlocked',
                        'order_id' => $newOrderID,
                        'tool_name' => $tool_name,
                        'credits' => $amount,
                        'site_admin' => $admin_from_disp,
                        'send_mail' => true
                    );
                    array_push($argsAll, $args);
                    if ($email_notification_user == "1") {
                        //$objEmail->sendMultiEmailTemplate('admin_user_imei_avail', $argsAll);
                    }
                }
            }
        }
        if ($newOrderID) {
            $objEmail = new email();
            $email_config = $objEmail->getEmailSettings();
            $admin_email = $email_config['admin_email'];
            $system_from = $email_config['system_email'];
            $from_display = $email_config['system_from'];


            $args = array(
                'to' => $admin_email,
                'from' => $system_from,
                'fromDisplay' => $from_display,
                'username' => $member->getUsername(),
                'order_id' => $newOrderID,
                'imei' => $all_imeis,
                'tool_name' => $tool_name,
                'credits' => $amount,
                'send_mail' => true
            );
            $args2 = array(
                'to' => $supplieremail,
                'from' => $system_from,
                'fromDisplay' => $from_display,
                'username' => $member->getUsername(),
                'order_id' => $newOrderID,
                'imei' => $all_imeis,
                'tool_name' => $tool_name,
                'credits' => $suppcredit,
                'send_mail' => true
            );

            // check if service email notification is on ot off
            if ($email_notification == 1) {
                $objEmail->sendEmailTemplate('admin_order_imei_new', $args);
            }
            // send email to supplier if that serive is attach to one
            if ($supplieremail != '') {
                $objEmail->sendEmailTemplate('admin_order_imei_new_supp', $args2);
            }
        }
        $result = 'reply_submit_success';
        //header('location:' . CONFIG_PATH_SITE_USER . 'imei.html?reply=' . urlencode('reply_submit_success'));
        //exit();
    } else {
        $result = 'reply_insuff_credits';
        //header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html?reply=' . urlencode('reply_insuff_credits'));
        //exit();
    }
    //echo "asdf";
    echo json_encode(array(
        'done' => $tempDoneIMEIS,
        'duplicate' => $tempDuplicateIEMIs,
        'invalid' => $tempInvalidIMEIs,
        'result' => $result
    ));

    //echo 'imei_submit';
    //header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html');
    exit();


    exit;
}
?>