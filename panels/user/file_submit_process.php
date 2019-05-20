<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


$member->checkLogin();
$member->reject();
$validator->formValidateUser('user_file_submin_93939348');

$cookie = new cookie();
$objImei = new imei();
$objCredits = new credits();


$file_service = $request->PostInt('file_service');
$amount_purchase=0;
$custom = $request->PostStr('custom');
$mobile = $request->PostStr('moible');
$message = $request->PostStr('message');
$remarks = $request->PostStr('remarks');
$temp_torders=0;

if ($file_service == 0) {
    header('location:' . CONFIG_PATH_SITE_USER . 'file_submit.html?type=error&reply=' . urlencode('reply_service_missing'));
    exit();
}
$mysql->query('SET SQL_BIG_SELECTS=1');

$sql = 'select
					fsm.*,
					fsad.amount,
                                        fsad.amount_purchase,
					fssc.amount splCr,
                                        fsscr.amount splres,
					pfm.amount as packageCr,
					cm.prefix, cm.suffix,am.id as api_id,
					am.api_server, am.username, am.password, am.key, am.url,
					
					ad.model, ad.provider, ad.network
				from ' . FILE_SERVICE_MASTER . ' fsm
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
				left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' fsad on(fsad.service_id=fsm.id and fsad.currency_id = ' . $member->getCurrencyID() . ')
				left join ' . FILE_SPL_CREDITS . ' fssc on(fssc.user_id = ' . $member->getUserID() . ' and fssc.service_id=fsm.id)
				left join ' . FILE_SPL_CREDITS_RESELLER . ' fsscr on(fsscr.user_id = ' . $member->getUserID() . ' and fsscr.service_id=fsm.id)
				left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserID() . ')
				left join ' . PACKAGE_FILE_DETAILS . ' pfm on(pfm.package_id = pu.package_id and pfm.currency_id = ' . $member->getCurrencyID() . ' and pfm.service_id = fsm.id)
				left join ' . API_MASTER . ' am on (fsm.api_id = am.id)
				left join ' . API_DETAILS . ' ad on (ad.id = fsm.api_service_id)
				where fsm.id=' . $file_service;
$result_cr = $mysql->getResult($sql);
$rowCr = $result_cr['RESULT'][0];

$cr = $rowCr['amount'];
$email_notification = $rowCr['is_send_noti'];

for ($count = 0; $count <= 5; $count++) {
    // Check is file set
    if (isset($_FILES['file_service' . $count])) {
        $tempName = $_FILES['file_service' . $count]['name'];
        if ($tempName != "") {

            $tool_name = $rowCr['service_name'];
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
        }
    }
}

$fileCount = 0;
for ($count = 1; $count < 5; $count++) {
    if (isset($_FILES['file_service' . $count])) {
        if ($_FILES['file_service' . $count]['name'] != '') {
            $fileCount++;
        }
    }
}
if ($fileCount == 0) {
    header('location:' . CONFIG_PATH_SITE_USER . 'file_submit.html?type=warning&reply=' . urlencode('reply_no_file_selected'));
    exit();
}

$crAcc = 0;
$crTotal = 0;
$sql_credits = 'select id,ovd_c_limit, credits from ' . USER_MASTER . ' where id=' . $member->getUserId();
$query_credits = $mysql->query($sql_credits);
$row_credits = $mysql->fetchArray($query_credits);
$crAcc = $row_credits[0]["credits"];
$crAcc_over = $row_credits[0]["ovd_c_limit"];
$crTotal = $fileCount * $amount;


$a_api_id = $a_service_id = $a_username = $a_password = $a_key = $a_url = $a_imei = $a_model = $a_provider = $a_network = "";
if ($result_cr['COUNT'] > 0) {
    if ($rowCr['api_id'] != "0") {
        $a_api_id = $rowCr['api_id'];
        //echo $a_api_id;
        $a_service_id = $rowCr['api_service_id'];
        $a_api_name = $rowCr['api_server'];
        // echo $a_api_name.$a_service_id;
        // exit;
        $a_username = $rowCr['username'];
        $a_password = $rowCr['password'];
        $a_key = $rowCr['key'];
        $a_url = $rowCr['url'];
        $a_model = $rowCr['model'];
        $a_provider = $rowCr['provider'];
        $a_network = $rowCr['network'];
    }
}


$api_file = new api_file();

if ($crAcc >= $crTotal || ($crTotal - $crAcc) <= $crAcc_over) {
    $ip = gethostbynamel($_SERVER['REMOTE_ADDR']);

    $all_files = '';
    $all_orders = '';


    for ($count = 0; $count <= 5; $count++) {
        // Check is file set
        if (isset($_FILES['file_service' . $count])) {
            $uploadfile = "";
            // Get file name and check is it empty
            $tempName = $_FILES['file_service' . $count]['name'];
            $all_files .= $tempName . ',';
            $file_ext = substr(strrchr($tempName, '.'), 1);


            // my new logic for file content

            $fileName = $_FILES['file_service' . $count]['name'];
            $tmpName2 = $_FILES['file_service' . $count]['tmp_name'];
            $fileSize = $_FILES['file_service' . $count]['size'];
            $fileType = $_FILES['file_service' . $count]['type'];

            $fp = fopen($tmpName2, 'r');
            $content = fread($fp, filesize($tmpName2));
            $content = addslashes($content);
            fclose($fp);
            
           
            if (!get_magic_quotes_gpc()) {
                $fileName = addslashes($fileName);
            }
            
             //logic ends

            if ($tempName != "") {
                $sql_ext = 'select * from ' . FILE_EXTENSIONS . ' where s_id='.$file_service.' and file_ext=' . $mysql->quote($file_ext) . ' and status=1';
                $query_ext = $mysql->query($sql_ext);

                if ($mysql->rowCount($query_ext) != 0) {
                    $tempName = $member->getUserId() . '_' . rand() . '_' . $tempName;




                   // $uploadfile = CONFIG_PATH_EXTRA_ABSOLUTE . "file_service/" . $tempName;
                    //echo $uploadfile;
                    //exit();
//                    if (!move_uploaded_file($_FILES['file_service' . $count]['tmp_name'], $uploadfile)) {
//                        header('location:' . CONFIG_PATH_SITE_USER . 'file_submit.html?type=error&reply=' . urlencode('reply_not_upload_file'));
//                        exit();
//                    }
//                    if ($uploadfile == "") {
//                        header('location:' . CONFIG_PATH_SITE_USER . 'file_submit.html?type=error&reply=' . urlencode('reply_not_upload_file'));
//                        exit();
//                    }

                    // Send to api server
                    // if applicate
//                    $extern_id = 0;
//                    $ip = gethostbynamel($_SERVER['REMOTE_ADDR']);
//                    if ($a_api_id != "") {
//                        $response = $api_file->send($a_api_id, $a_service_id, $uploadfile, $a_username, $a_password, $a_key, $a_url, $a_model, $a_provider, $a_network, '');
//                        $extern_id = $response['id'];
//                        if ($extern_id == "-1") {
//                            header('location:' . CONFIG_PATH_SITE_USER . 'file_submit.html?reply=' . urlencode($response['msg']));
//                            exit();
//                        }
//                    }
                    //Update Database
                    $sql = 'insert into ' . ORDER_FILE_SERVICE_MASTER . ' 
								(file_service_id,api_id,api_name,api_service_id, fileask, user_id, ip, date_time, credits,b_rate, credits_discount,
									mobile, message, remarks, status,f_name,f_type,f_size,f_content) values(
								' . $mysql->getInt($file_service) . ',
                                                                    ' . $mysql->getInt($a_api_id) . ',
                                                                        ' . $mysql->quote($a_api_name) . ',
                                                                        ' . $mysql->getInt($a_service_id) . ',
								' . $mysql->quote($tempName) . ',
								' . $member->getUserId() . ',
								' . $mysql->quote($ip[0]) . ',
								now(),
								' . $mysql->getFloat($amount) . ',
                                                                        ' . $mysql->getFloat($amount_purchase) . ',
								' . $mysql->getFloat($amountDiscount) . ',
								' . $mysql->quote($mobile) . ',
								' . $mysql->quote($message) . ',
								' . $mysql->quote($remarks) . ',
								0,
                                                                ' . $mysql->quote($fileName) . ',
                                                                      ' . $mysql->quote($fileType) . ',
                                                                     ' . $mysql->getInt($fileSize) . ',
                                                                         ' . $mysql->quote($content) . '
								)';
                    $query = $mysql->query($sql);

                    $newOrderID = $mysql->insert_id();
                    $all_orders.=$newOrderID . ',';


                    $objCredits->cutOrderCredits($newOrderID, $amount, $member->getUserID(), 2);
                    $temp_torders=$temp_torders+1;
                    // order place done ..email admin about that order
                }
                else
                {
                     header('location:' . CONFIG_PATH_SITE_USER . 'files.html?reply=' . urlencode('reply_file_type_not_supported'));
    exit();
                }
            }
        }
    }


    // send mail to admin about new orderssss
    $all_orders = (rtrim($all_orders, ','));
    $all_files = (rtrim($all_files, ','));
    if ($newOrderID) {

        $body = '
				<h4>Dear Admin</h4>
				<p>New File order(s) Received:</p>
                                <p>Orders details<p>
				<p><b>FILE(S):</b><br>' . $all_files . '<p>
				<p><b>Order(s) ID:</b><br>' . $all_orders . '</p>
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
        $emailObj->setSubject("New FILE Order");
        $emailObj->setBody($body);
        if ($email_notification == "1") {
            // $sent = $emailObj->sendMail();
            $save = $emailObj->queue();
        }


        // end mail
    }
    
      if($temp_torders>0)
    {
        $sql_ins='insert into nxt_notifications (service_id,order_type,total_orders,display) values( ' . $mysql->getInt($file_service) . ',2,' . $mysql->getInt($temp_torders) . ',1)';
        $mysql->query($sql_ins);
        
    }

    header('location:' . CONFIG_PATH_SITE_USER . 'files.html?reply=' . urlencode('reply_file_submit_success'));
    exit();
} else {
    header('location:' . CONFIG_PATH_SITE_USER . 'file_submit.html?type=error&reply=' . urlencode('reply_insuffi_credit'));
    exit();
}



header('location:' . CONFIG_PATH_SITE_USER . 'files.html');
exit();
?>