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

$request = new request();
$mysql = new mysql();
$api = new api();


//-------------------------------for auto success time-------------------------------------//
//=================================start===================================================//

$sql = 'select a.id orderid,b.tool_name,a.credits,a.imei,a.user_id,c.username,c.email,c.imei_suc_noti from ' . ORDER_IMEI_MASTER . ' a

inner join ' . IMEI_TOOL_MASTER . ' b on a.tool_id=b.id and b.auto_success=1
    
inner join ' . USER_MASTER . ' c on a.user_id=c.id

where a.`status`=0 and now()  > a.date_time + INTERVAL b.succ_time MINUTE ';
//   echo $sql;exit;
$query = $mysql->query($sql);

if ($mysql->rowCount($query) > 0) {
    $rows = $mysql->fetchArray($query);
    $email_config = $objEmail->getEmailSettings();
    $from_admin = $email_config['system_email'];
    $admin_from_disp = $email_config['system_from'];
    foreach ($rows as $row) {
        $newOrderID = $row['orderid'];
        $email_notification_user = $row['imei_suc_noti'];
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
        $query = $mysql->query($sqlAvail);
        // send success mail

        $argsAll = array();

        $args = array(
            'to' => $row['email'],
            'from' => $from_admin,
            'fromDisplay' => $admin_from_disp,
            'user_id' => $row['user_id'],
            'save' => '1',
            'username' => $row['username'],
            'imei' => $row['email'],
            'unlock_code' => 'Unlocked',
            'order_id' => $newOrderID,
            'tool_name' => $row['tool_name'],
            'credits' => $row['credits'],
            'site_admin' => $admin_from_disp,
            'send_mail' => true
        );
        array_push($argsAll, $args);
        if ($email_notification_user == "1") {
            $objEmail->sendMultiEmailTemplate('admin_user_imei_avail', $argsAll);
        }
    }
    echo 'DONE';
} else {
    echo 'NO ORDERS';
}


//-------------------------------for auto success time-------------------------------------//
//=================================+++END++++===================================================//
exit;
