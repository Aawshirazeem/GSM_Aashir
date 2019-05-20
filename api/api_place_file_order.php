<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("_init.php");
// $member1->checkLogin();
//$member1->reject();
$request = new request();
$mysql = new mysql();
$xml = new xml();
$api = new api();
$objImei = new imei();
$objCredits = new credits();
$temp_torders=0;

$parameters = html_entity_decode($_POST['parameters']);
$params = $xml->XMLtoARRAY(trim($parameters));

//error_log(print_r($response, true), 3, CONFIG_PATH_SITE_ABSOLUTE . "dhru.log");

$tool = $params['PARAMETERS']['ID'];


//$file_service = $params['PARAMETERS']['ID'];
// $custom = $request->PostStr('custom');
$oldorder = $params['PARAMETERS']['IMEI'];
$mobile = $params['PARAMETERS']['MOBILE'];
$message = $params['PARAMETERS']['MESSAGE'];
$remarks = $params['PARAMETERS']['REMARKS'];
$fileask = $params['PARAMETERS']['FILENAME'];
$filpath = $params['PARAMETERS']['FILEDATA'];
$filpath=  base64_decode($filpath);
$filsize = $params['PARAMETERS']['SIZE'];
$filtype = $params['PARAMETERS']['TYPE'];




$sql = 'select id from ' . FILE_SERVICE_MASTER . ' where id=' . $tool . ' and status=1';
$query = $mysql->query($sql);
// Return if there no IMEI supplied
if ($mysql->rowCount($query) == 0) {
    $error = array(Array('MESSAGE' => 'Invalid FILE tool', 'FULL_DESCRIPTION' => 'Invalid FILE SERVICE tool.'));
    $result = Array('ID' => $tool, 'IMEI' => $oldorder, 'ERROR' => $error, 'apiversion' => '2.0.0');
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

//if ($rate = 0) {
//    $error = array(Array('MESSAGE' => 'Invalid FILE tool.', 'FULL_DESCRIPTION' => 'Invalid FILE SERVICE tool.'));
//    $result = Array('ID' => $tool, 'IMEI' => $oldorder, 'ERROR' => $error, 'apiversion' => '2.0.0');
//    echo json_encode($result);
//    exit();
//}



/* * **********************************************
 * ****    check Pack and Special Credits    *****
 * ********************************************** */

$sql = 'select
					fsm.*,
					fsad.amount,
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
				where fsm.id=' . $mysql->getInt($tool);


$result_cr = $mysql->getResult($sql);
$rowCr = $result_cr['RESULT'][0];

$cr = $rowCr['amount'];
$email_notification = $rowCr['is_send_noti'];

//if ($cr != 0) {
//    $error = array(Array('MESSAGE' => 'Invalid FILE tool.', 'FULL_DESCRIPTION' => 'Invalid FILE SERVICE tool.'));
//    $result = Array('ID' => $tool, 'IMEI' => $oldorder, 'ERROR' => $error, 'apiversion' => '2.0.0');
//    echo json_encode($result);
//    exit();
//}




$fileCount = 0;
if ($fileask != "") {
    $fileCount = 1;
    $tool_name = $rowCr['service_name'];
    $amount = $mysql->getFloat($rowCr['amount']);
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
} else {
    $error = array(Array('MESSAGE' => 'No File Selected', 'FULL_DESCRIPTION' => 'No File Selected'));
    $result = Array('ID' => $tool, 'IMEI' => $oldorder, 'ERROR' => $error, 'apiversion' => '2.0.0');
    echo json_encode($result);
    exit();
}



$crAcc = 0;
$crTotal = 0;
$sql_credits = 'select id, ovd_c_limit,credits from ' . USER_MASTER . ' where id=' . $member->getUserId();
$query_credits = $mysql->query($sql_credits);
$row_credits = $mysql->fetchArray($query_credits);
$crAcc = $row_credits[0]["credits"];
$crAcc_over = $row_credits[0]["ovd_c_limit"];
$crTotal = $fileCount * $amount;

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
// Processing credits should not less be then credits in account
//if ($crAcc < $crTotal) {
//    $error = array(Array('MESSAGE' => 'Insufficient Credits', 'FULL_DESCRIPTION' => ''));
//    $result = Array('ID' => $tool, 'IMEI' => $oldorder, 'ERROR' => $error, 'apiversion' => '2.0.0');
//    echo json_encode($result);
//    exit();
//}
// Process if we have suffcient credits
//if ($crAcc >= $crTotal) {
if ($crAcc >= $crTotal || ($crTotal - $crAcc) <= $crAcc_over) {
    $extern_id = 0;
    $ip = gethostbynamel($_SERVER['REMOTE_ADDR']);
    //$ip = $ip[0];
    //Update Database
    $sql = 'insert into ' . ORDER_FILE_SERVICE_MASTER . ' 
								(file_service_id,api_id,api_name,api_service_id, fileask, user_id, ip, date_time, credits, credits_discount,
									mobile, message, remarks, status,f_name,f_type,f_size,f_content) values(
								' . $mysql->getInt($tool) . ',
                                                                    ' . $mysql->getInt($a_api_id) . ',
                                                                        ' . $mysql->quote($a_api_name) . ',
                                                                        ' . $mysql->getInt($a_service_id) . ',
								' . $mysql->quote($fileask) . ',
								' . $member->getUserId() . ',
								' . $mysql->quote($ip[0]) . ',
								now(),
								' . $mysql->getFloat($amount) . ',
								' . $mysql->getFloat($amountDiscount) . ',
								' . $mysql->quote($mobile) . ',
								' . $mysql->quote($message) . ',
								' . $mysql->quote($remarks) . ',
								0,
                                                                    ' . $mysql->quote($fileask) . ',
                                                                      ' . $mysql->quote($filtype) . ',
                                                                     ' . $mysql->getInt($filsize) . ',
                                                                         ' . $mysql->quote($filpath) . '
								)';

//    $error = array(Array('MESSAGE' => $sql, 'FULL_DESCRIPTION' => ''));
//    $result = Array('ID' => $tool, 'IMEI' => $imei, 'ERROR' => $error, 'apiversion' => '2.0.0');
//    echo json_encode($result);
//    exit();
//
//    $uploaddir = realpath('../assets/file_service') . '/';
//    $uploadfile = $uploaddir . basename($_FILES['file_contents']['name']);
//    if (!move_uploaded_file($_FILES['file_contents']['tmp_name'], $uploadfile)) {
//        $error = array(Array('MESSAGE' => 'Upload Fail!' . $uploadfile, 'FULL_DESCRIPTION' => ''));
//        $result = Array('ID' => $tool, 'IMEI' => $oldorder, 'ERROR' => $error, 'apiversion' => '2.0.0');
//        echo json_encode($result);
//        exit();
//    } else {
//        
//    }
//

    $query = $mysql->query($sql);
    $newOrderID = $mysql->insert_id();
    
      $objCredits->cutOrderCredits($newOrderID, $amount, $member->getUserID(), 2);
          $temp_torders=$temp_torders+1;
    if ($newOrderID) {


        // send email

        $body = '
				<h4>Dear Admin</h4>
				<p>New File order(s) Received:</p>
                                <p>Orders details<p>
				<p><b>FILE(S):</b><br>' . $fileask . '<p>
				<p><b>Order(s) ID:</b><br>' . $newOrderID . '</p>
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
         if ($email_notification == "1")
         $save = $emailObj->queue();
     //   $sent = $emailObj->sendMail();

        // end mail
        //
  if($temp_torders>0)
    {
        $sql_ins='insert into nxt_notifications (service_id,order_type,total_orders,display) values( ' . $mysql->getInt($file_service) . ',2,' . $mysql->getInt($temp_torders) . ',1)';
        $mysql->query($sql_ins);
        
    }

        $group = Array('MESSAGE' => 'Order received', 'REFERENCEID' => $newOrderID);
        $success1[] = $group;
        $result = Array('ID' => $tool, 'IMEI' => $oldorder, 'SUCCESS' => $success1, 'apiversion' => '2.0.0');
        echo json_encode($result);
        exit();
    } else {
        $error = array(Array('MESSAGE' => 'SQL QRY ISSUE!', 'FULL_DESCRIPTION' => ''));
        $result = Array('ID' => $tool, 'IMEI' => $oldorder, 'ERROR' => $error, 'apiversion' => '2.0.0');
        echo json_encode($result);
        exit();
    }
} else {
    $error = array(Array('MESSAGE' => 'Insufficient Credits!', 'FULL_DESCRIPTION' => ''));
    $result = Array('ID' => $tool, 'IMEI' => $oldorder, 'ERROR' => $error, 'apiversion' => '2.0.0');
    echo json_encode($result);
    exit();
}
