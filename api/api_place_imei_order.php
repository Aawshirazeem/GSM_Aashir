<?php

require_once("_init.php");
// $member1->checkLogin();
//$member1->reject();
$request = new request();
$mysql = new mysql();
$xml = new xml();
$api = new api();
$objImei = new imei();
$objCredits = new credits();

$parameters = html_entity_decode($_POST['parameters']);
$params = $xml->XMLtoARRAY(trim($parameters));

//error_log(print_r($response, true), 3, CONFIG_PATH_SITE_ABSOLUTE . "dhru.log");

$tool = $params['PARAMETERS']['ID'];
$imeis = $params['PARAMETERS']['IMEI'];
$mep = $params['PARAMETERS']['MEP'];


$brand = $request->PostInt('brand');
$model = $request->PostInt('model');
$country = $request->PostInt('country');
$network = $request->PostInt('network');
//$mep = $request->PostStr('mep');
$pin = $request->PostStr('pin');
$prd = $request->PostStr('prd');
$itype = $request->PostStr('itype');
$custom_value = $request->PostStr('custom_value');

$email = $request->PostStr('email');
$mobile = $request->PostStr('mobile');
$message = $request->PostStr('message');
$remarks = $request->PostStr('remarks');
$newOrderID = 0;
$amount_purchase = 0;
$temp_torders = 0;
// check auto_success on of tool

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


/* * *****************************************
 * ****    Check and make IMEI Array    *****
 * ***************************************** */
$imeis = str_replace("\n", ",", $imeis);
$tempImeiList = explode(",", $imeis);
$count = 0;
foreach ($tempImeiList as $tempImei) {
    if ($tempImei != "") {
        $imeiList[$count] = trim($tempImei);
        $count++;
    }
}

$sql = 'select id from ' . IMEI_TOOL_MASTER . ' where id=' . $tool . ' and status=1';
$query = $mysql->query($sql);
// Return if there no IMEI supplied
if ($mysql->rowCount($query) == 0) {
    $error = array(Array('MESSAGE' => 'Invalid IMEI tool.', 'FULL_DESCRIPTION' => 'Invalid IMEI tool.'));
    $result = Array('ID' => $tool, 'IMEI' => $imei, 'ERROR' => $error, 'apiversion' => '2.0.0');
    echo json_encode($result);
    exit();
}
// Return if there no IMEI supplied
if (($imeis == "" or $imeis == "0") && $imei == "") {
    $error = array(Array('MESSAGE' => 'No IMEI found.', 'FULL_DESCRIPTION' => 'No IMEI found, please try again!'));
    $result = Array('ID' => $tool, 'IMEI' => $imei, 'ERROR' => $error, 'apiversion' => '2.0.0');
    echo json_encode($result);
    exit();
}

/* Get package id for the user */
$package_id = 0;
$sql = 'select * from ' . PACKAGE_USERS . ' where user_id=' . $member->getUserId();
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    $rows = $mysql->fetchArray($query);
    $package_id = $rows[0]['package_id'];
}


$crM = $objCredits->getMemberCreditsAPI($member->getUserId());
$prefix = $crM['prefix'];
$suffix = $crM['suffix'];
$rate = $crM['rate'];


/* * **********************************************
 * ****    check Pack and Special Credits    *****
 * ********************************************** */
$sql_cr = 'select
					tm.api_id, tm.api_service_id, tm.credits,
					tm.accept_duplicate, tm.verify_checksum,
					uscm.credits as splCr,
					pd.credits as packageCr
				from ' . IMEI_TOOL_MASTER . ' tm
				left join ' . IMEI_SPL_CREDITS . ' uscm on (tm.id = uscm.tool and uscm.user_id = ' . $member->getUserId() . ')
				left join ' . PACKAGE_IMEI_DETAILS . ' pd on(tm.id = pd.tool_id and pd.package_id=' . $package_id . ')
				where tm.id=' . $mysql->getInt($tool);
$mysql->query('SET SQL_BIG_SELECTS=1');
$sql_cr = 'select
					tm.id as tid, tm.tool_name, tm.delivery_time,tm.is_send_noti,
					tm.accept_duplicate, tm.verify_checksum,tm.api_auth,
					itad.amount,
                                        itad.amount_purchase,
					isc.amount splCr,
					pim.amount as packageCr,
					igm.group_name,
					cm.prefix, cm.suffix
				from ' . IMEI_TOOL_MASTER . ' tm
				left join ' . IMEI_GROUP_MASTER . ' igm on(tm.group_id = igm.id)
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
				left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(itad.tool_id=tm.id and itad.currency_id = ' . $member->getCurrencyID() . ')
				left join ' . IMEI_SPL_CREDITS . ' isc on(isc.user_id = ' . $member->getUserId() . ' and isc.tool_id=tm.id)
				left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserId() . ')
				left join ' . PACKAGE_IMEI_DETAILS . ' pim on(pim.package_id = pu.package_id and pim.currency_id = ' . $member->getCurrencyID() . ' and pim.tool_id = tm.id)
				where tm.id=' . $mysql->getInt($tool) . '
				order by igm.sort_order, tm.sort_order, tm.tool_name';

//error_log($sql_cr . "\n" . "\n" . "\n", 3, CONFIG_PATH_SITE_ABSOLUTE . "dhru.log");
$query_cr = $mysql->query($sql_cr);
$rows_cr = $mysql->fetchArray($query_cr);
$rowCr = $rows_cr[0];
$accept_duplicate = $rowCr['accept_duplicate'];
$verify_checksum = $rowCr['verify_checksum'];
 $api_auth = $rowCr['api_auth'];
 $api_auth_yn=1;
    if($api_auth==1)
        $api_auth_yn=0;

$tool_name = $rowCr['tool_name'];
$email_notification = $rowCr['is_send_noti'];
$amount = $mysql->getFloat($rowCr['amount']);
$amount_purchase = $mysql->getFloat($rowCr['amount_purchase']);
$amountSpl = ($rowCr['splCr']);
$amountSpl = ((isset($amountSpl) && $amountSpl !== '') ? $amountSpl : -1);

//$amountPackage = $mysql->getFloat($rowCr['packageCr']);
$amountPackage = $rowCr['packageCr'];
// $amountPackage = ((isset($amountPackage) && $amountPackage !== '')?$amountPackage:-1);
$amountDiscount = 0;
$isSpl = false;

//$myfile = fopen("newfile1.txt", "w") or die("Unable to open file!");
//$file_contents = "TEST".$amount." :: ";

if ($amountPackage != '') {
    $isSpl = true;
    $amountPackage = $mysql->getFloat($amountPackage);
    $amountDiscount = $amount - $amountPackage;
    $amount = $amountPackage;
    //$file_contents .= "KKKK".$amountPackage." :: ";
}
if ($amountSpl >= 0) {
    $isSpl = true;
    $amountDiscount = $amount - $amountSpl;
    $amount = $amountSpl;
    //$file_contents .= "ZZZZ".$amountSpl." :: ";
}

//fwrite($myfile, $file_contents);

fclose($myfile);

$crAcc = 0;
$sql_credits = 'select id,ovd_c_limit,email,imei_suc_noti, credits,username from ' . USER_MASTER . ' where id=' . $member->getUserId();
$query_credits = $mysql->query($sql_credits);
$row_credits = $mysql->fetchArray($query_credits);
$crAcc = $row_credits[0]["credits"];
$crAcc_over = $row_credits[0]["ovd_c_limit"];
$username = $row_credits[0]["username"];
$useremail = $row_credits[0]["email"];
$user_id = $row_credits[0]["id"];
$email_notification_user = $row_credits[0]['imei_suc_noti'];
$crTotal = $amount * count($imeiList);

// Processing credits should not less be then credits in account
//	if($crAcc < $crTotal)
//	{
//		$error = array(Array('MESSAGE' => 'Insufficient Credits', 'FULL_DESCRIPTION' => ''));
//		$result = Array('ID'=> $tool, 'IMEI' => $imei, 'ERROR' => $error, 'apiversion' => '2.0.0');
//		echo json_encode($result);
//		exit();
//	}
// Process if we have suffcient credits
//if($crAcc >= $crTotal)
if ($crAcc >= $crTotal || ($crTotal - $crAcc) <= $crAcc_over) {
//{
    $mysql->query('SET SQL_BIG_SELECTS=1');
    $sql_api = 'select 
							tm.api_id, tm.custom_field_name,
							am.api_server, am.username, am.password, am.key, am.url,
							ad.service_id as api_service_id,
							ad.model, ad.provider, ad.network
						from ' . IMEI_TOOL_MASTER . ' tm 
						left join ' . API_MASTER . ' am on (tm.api_id = am.id)
						left join ' . API_DETAILS . ' ad on (ad.id = tm.api_service_id)
						where tm.id=' . $mysql->getInt($tool) . ' and am.status=1';
    $query_api = $mysql->query($sql_api);
    $args = array();
    $api_id = 0;
    $api_service_id = 0;
    $api_name = '';
    if ($mysql->rowCount($query_api) > 0) {
        $rows_api = $mysql->fetchArray($query_api);
        if ($rows_api[0]['api_id'] != "0") {
            $api_id = $rows_api[0]['api_id'];
            $api_name = $rows_api[0]['api_server'];
            $args['service_id'] = $rows_api[0]['api_service_id'];
            $api_service_id = $rows_api[0]['api_service_id'];
            $args['username'] = $rows_api[0]['username'];
            $args['password'] = $rows_api[0]['password'];
            $args['key'] = $rows_api[0]['key'];
            $args['url'] = $rows_api[0]['url'];
            $args['model'] = $rows_api[0]['model'];
            $args['provider'] = $rows_api[0]['provider'];
            $args['network'] = $rows_api[0]['network'];
            $args[$rows_api[0]['custom_field_name']] = $custom_value;
        }
    }



    $api = new api();
    $count = 1;
    foreach ($imeiList as $imei) {
        if ($imei != "") {
            $imei = trim($imei);
            $all_imeis .= $imei . '<br>';
            // check for valid IMEIs
            /*
              $verify_checksum = ($verify_checksum == "1") ? true : false;
              if(!$objImei->checkIMEI($imei, $verify_checksum, $alpha, $size))
              {
              $error = array(Array('MESSAGE' => 'Invalid IMEI![' . $imei . ']', 'FULL_DESCRIPTION' => ''));
              $result = Array('ID'=> $tool, 'IMEI' => $imei, 'ERROR' => $error, 'apiversion' => '2.0.0');
              echo json_encode($result);
              exit();
              }
             */


            // check for dupliate IMEIs
            $args['imei'] = $imei;
            if ($accept_duplicate == 0) {
                $sql = 'select id
								from ' . ORDER_IMEI_MASTER . '
								where imei = ' . $mysql->quote($imei) . '
								and user_id = ' . $member->getUserId() . '
								and tool_id = ' . $mysql->getInt($tool) . '
								and status in (0,1)
								limit 1';
                $preCount = $mysql->rowCount($mysql->query($sql));
                if ($preCount >= 1) {
                    $error = array(Array('MESSAGE' => 'Duplicate IMEI[' . $imei . ']', 'FULL_DESCRIPTION' => ''));
                    $result = Array('ID' => $tool, 'IMEI' => $imei, 'ERROR' => $error, 'apiversion' => '2.0.0');
                    echo json_encode($result);
                    exit();
                }
            }

            //get IP address
            $ip = gethostbynamel($_SERVER['REMOTE_ADDR']);
            $ip = $ip[0];

            $mep_id = 0;
            if ($mep != '') {
                $sql_mep = 'select id from ' . IMEI_MEP_MASTER . ' where mep=' . $mysql->quote($mep);
                $query_mep = $mysql->query($sql_mep);
                if ($mysql->rowCount($query_mep) > 0) {
                    $rows = $mysql->fetchArray($query_mep);
                    $mep_id = $rows[0]['id'];
                }
            }


            if ($auto_successchk == 0) {


                if ($duplicate_yn == 1) {

                    $sqlchkimei = 'select oim.imei,oim.reply from ' . ORDER_IMEI_MASTER . ' oim where oim.`status`=2 and oim.tool_id=' . $tool . ' and oim.imei=' . $mysql->quote($imei) . '

                            order by oim.date_time desc limit 1';
                    $qrydata = $mysql->query($sqlchkimei);
                    if ($mysql->rowCount($qrydata) > 0) {
                        $rows = $mysql->fetchArray($qrydata);
                        $imeireplyy = $rows[0]['reply'];
                        // order found
                        // Insert imei and api details in the order table
                        /*
                          $sql = 'insert into ' . ORDER_IMEI_MASTER . '
                          (api_id, api_name, api_service_id, tool_id, user_id, ip, imei, date_time,reply_date_time,
                          reply,credits,b_rate, credits_discount,
                          brand_id, model_id, country_id, network_id,
                          mep_id, pin, prd, itype, email, mobile, message, remarks, status) values(
                          ' . $mysql->getInt($api_id) . ',
                          ' . $mysql->quote($api_name) . ',
                          ' . $mysql->getInt($api_service_id) . ',
                          ' . $tool . ',
                          ' . $member->getUserId() . ',
                          ' . $mysql->quote($ip) . ',
                          ' . $mysql->quote(trim($imei)) . ',
                          now(),now(),
                          ' . $mysql->quote($imeireplyy) . ',
                          ' . $amount . ',
                          ' . $mysql->getFloat($amount_purchase) . ',
                          ' . $amountDiscount . ',
                          ' . $brand . ',
                          ' . $model . ',
                          ' . $country . ',
                          ' . $network . ',
                          ' . $mep_id . ',
                          ' . $mysql->quote($pin) . ',
                          ' . $mysql->quote($prd) . ',
                          ' . $mysql->quote($itype) . ',
                          ' . $mysql->quote($email) . ',
                          ' . $mysql->quote($mobile) . ',
                          ' . $mysql->quote($message) . ',
                          ' . $mysql->quote($remarks) . ',
                          2


                          )';
                         */

                        $sql = 'insert into ' . ORDER_IMEI_MASTER . '
							(api_id, api_name, api_service_id, tool_id, user_id,
								ip, imei, date_time, credits,b_rate,b_rate_main, credits_discount,
								brand_id, model_id, country_id, network_id,
								mep_id, pin, prd, itype, email, mobile,message,remarks) values(
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
							' . $mysql->getInt($mep_id) . ',
							' . $mysql->quote($pin) . ',
							' . $mysql->quote($prd) . ',
							' . $mysql->quote($itype) . ',
							' . $mysql->quote($email) . ',
							' . $mysql->quote($mobile) . ',
							' . $mysql->quote($message) . ',
							' . $mysql->quote($remarks) . '
							)';
                        $mysql->query($sql);

                        $newOrderID = $mysql->insert_id();

                        $objCredits->cutOrderCredits($newOrderID, $amount, $member->getUserID(), 1);
                        $sqlAvail = '
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
                    } else {
                        // order not found
                        // Insert imei and api details in the order table
                        $sql = 'insert into ' . ORDER_IMEI_MASTER . ' 
							(api_id, api_name, api_service_id, tool_id, user_id, ip, imei, date_time,
								credits,b_rate, b_rate_main,credits_discount,
								brand_id, model_id, country_id, network_id,api_auth,api_auth_yn,
								mep_id, pin, prd, itype, email, mobile, message, remarks, status) values(
							' . $mysql->getInt($api_id) . ',
							' . $mysql->quote($api_name) . ',
							' . $mysql->getInt($api_service_id) . ',
							' . $tool . ',
							' . $member->getUserId() . ',
							' . $mysql->quote($ip) . ',
							' . $mysql->quote(trim($imei)) . ',
							now(),
							' . $amount . ',
                                                               ' . $mysql->getFloat($amount_purchase) . ',
                                                                      ' . $mysql->getFloat($amount_purchase) . ',
							' . $amountDiscount . ',
							' . $brand . ',
							' . $model . ',
							' . $country . ',
							' . $network . ',
                                                                ' . $mysql->getInt($api_auth) . ',
                                                                  ' . $mysql->getInt($api_auth_yn) . ',
							' . $mep_id . ',
							' . $mysql->quote($pin) . ',
							' . $mysql->quote($prd) . ',
							' . $mysql->quote($itype) . ',
							' . $mysql->quote($email) . ',
							' . $mysql->quote($mobile) . ',
							' . $mysql->quote($message) . ',
							' . $mysql->quote($remarks) . ',
							0
                                                        
                                                        
							)';
                        $mysql->query($sql);

                        $newOrderID = $mysql->insert_id();
                         $temp_torders=$temp_torders+1;
                        $objCredits->cutOrderCredits($newOrderID, $amount, $member->getUserID(), 1);
                    }
                } else {

                    // Insert imei and api details in the order table
                    $sql = 'insert into ' . ORDER_IMEI_MASTER . ' 
							(api_id, api_name, api_service_id, tool_id, user_id, ip, imei, date_time,
								credits, b_rate,b_rate_main,credits_discount,
								brand_id, model_id, country_id, network_id,api_auth,api_auth_yn,
								mep_id, pin, prd, itype, email, mobile, message, remarks, status) values(
							' . $mysql->getInt($api_id) . ',
							' . $mysql->quote($api_name) . ',
							' . $mysql->getInt($api_service_id) . ',
							' . $tool . ',
							' . $member->getUserId() . ',
							' . $mysql->quote($ip) . ',
							' . $mysql->quote(trim($imei)) . ',
							now(),
							' . $amount . ',
                                                               ' . $mysql->getFloat($amount_purchase) . ',
                                                                      ' . $mysql->getFloat($amount_purchase) . ',
							' . $amountDiscount . ',
							' . $brand . ',
							' . $model . ',
							' . $country . ',
							' . $network . ',
                                                                    ' . $mysql->getInt($api_auth) . ',
                                                                  ' . $mysql->getInt($api_auth_yn) . ',
							' . $mep_id . ',
							' . $mysql->quote($pin) . ',
							' . $mysql->quote($prd) . ',
							' . $mysql->quote($itype) . ',
							' . $mysql->quote($email) . ',
							' . $mysql->quote($mobile) . ',
							' . $mysql->quote($message) . ',
							' . $mysql->quote($remarks) . ',
							0
                                                        
                                                        
							)';
                    $mysql->query($sql);

                    $newOrderID = $mysql->insert_id();
                     $temp_torders=$temp_torders+1;
                    $objCredits->cutOrderCredits($newOrderID, $amount, $member->getUserID(), 1);
                }

                $count++;
                $group = Array('MESSAGE' => 'Order received', 'REFERENCEID' => $newOrderID);
            } else {

                // Insert imei and api details in the order table
                /*
                  $sql = 'insert into ' . ORDER_IMEI_MASTER . '
                  (api_id, api_name, api_service_id, tool_id, user_id, ip, imei, date_time,
                  credits,b_rate, credits_discount,
                  brand_id, model_id,reply, country_id, network_id,
                  mep_id, pin, prd, itype, email, mobile, message, remarks, status) values(
                  ' . $mysql->getInt($api_id) . ',
                  ' . $mysql->quote($api_name) . ',
                  ' . $mysql->getInt($api_service_id) . ',
                  ' . $tool . ',
                  ' . $member->getUserId() . ',
                  ' . $mysql->quote($ip) . ',
                  ' . $mysql->quote(trim($imei)) . ',
                  now(),
                  ' . $amount . ',
                  ' . $mysql->getFloat($amount_purchase) . ',
                  ' . $amountDiscount . ',
                  ' . $brand . ',
                  ' . $model . ',
                  ' . $mysql->quote(base64_encode('Unlocked')) . ',
                  ' . $country . ',
                  ' . $network . ',
                  ' . $mep_id . ',
                  ' . $mysql->quote($pin) . ',
                  ' . $mysql->quote($prd) . ',
                  ' . $mysql->quote($itype) . ',
                  ' . $mysql->quote($email) . ',
                  ' . $mysql->quote($mobile) . ',
                  ' . $mysql->quote($message) . ',
                  ' . $mysql->quote($remarks) . ',
                  2


                  )';
                 */
                $sql = 'insert into ' . ORDER_IMEI_MASTER . '
							(api_id, api_name, api_service_id, tool_id, user_id,
								ip, imei, date_time, credits,b_rate, b_rate_main,credits_discount,
								brand_id, model_id, country_id, network_id,
								mep_id, pin, prd, itype, email, mobile,message,remarks) values(
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
							' . $mysql->getInt($mep_id) . ',
							' . $mysql->quote($pin) . ',
							' . $mysql->quote($prd) . ',
							' . $mysql->quote($itype) . ',
							' . $mysql->quote($email) . ',
							' . $mysql->quote($mobile) . ',
							' . $mysql->quote($message) . ',
							' . $mysql->quote($remarks) . '
							)';
                $mysql->query($sql);

                $newOrderID = $mysql->insert_id();

                $objCredits->cutOrderCredits($newOrderID, $amount, $member->getUserID(), 1);

                $sqlAvail = '
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
              //  $query = $mysql->query($sqlAvail);


                $count++;
                $group = Array('MESSAGE' => 'Order received', 'REFERENCEID' => $newOrderID);
            }
        }
    }
    // add new email function
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
            'username' => $username,
            'order_id' => $newOrderID,
            'imei' => $all_imeis,
            'tool_name' => $tool_name,
            'credits' => $amount,
            'send_mail' => true
        );

        if ($email_notification == 1) {
            $objEmail->sendEmailTemplate('admin_order_imei_new', $args);
        }
    }
    //end email function
    
      if($temp_torders>0)
    {
        $sql_ins='insert into nxt_notifications (service_id,order_type,total_orders,display) values( ' . $mysql->getInt($tool) . ',1,' . $mysql->getInt($temp_torders) . ',1)';
        $mysql->query($sql_ins);
        
    }
    
    $success1[] = $group;
    $result = Array('ID' => $tool, 'IMEI' => $imei, 'SUCCESS' => $success1, 'apiversion' => '2.0.0');
    echo json_encode($result);
    exit();
} else {
    $error = array(Array('MESSAGE' => 'Insufficient Credits!', 'FULL_DESCRIPTION' => ''));
    $result = Array('ID' => $tool, 'IMEI' => $imei, 'ERROR' => $error, 'apiversion' => '2.0.0');
    echo json_encode($result);
    exit();
}
?>