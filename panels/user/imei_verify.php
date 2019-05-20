<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


$member->checkLogin();
$member->reject();

$cookie = new cookie();
$objImei = new imei();
$objCredits = new credits();

//Check and make IMEI Array
$id = $request->GetStr('id');
$id = $_POST['t_order'];
$reason = $_POST['v_remarks'];

if ($id != "" && $reason != "") {

    // chechk for supplier attach to thats server for email

    if ($id != '') {
        $sqlsup = 'select a.email from nxt_supplier_master a where a.id=(select a.supplier_id from nxt_order_imei_master a where a.id=' . $id . ')';
        $preCount = $mysql->rowCount($mysql->query($sqlsup));
        if ($preCount > 0) {
            $query_credits = $mysql->query($sqlsup);
            $row_credits = $mysql->fetchArray($query_credits);
            //$crAcc = $row_credits[0]["credits"];
            //$results = $mysql->getResult($sqlsup);
            //$row=$results[0];
            $supplieremail = $row_credits[0]["email"];
        }
    }







    $sql = 'select b.username,b.email,a.id as order_id, a.* from nxt_order_imei_master a, nxt_user_master b
			 where 
			 a.user_id = b.id
			 AND a.user_id=' . $member->getUserId() . ' and a.status=2 and a.id=' . $mysql->getInt($id);

    $query = $mysql->query($sql);

    if ($mysql->rowCount($query) > 0) {
        $rows = $mysql->fetchArray($query);

        $sql = 'update 
					' . ORDER_IMEI_MASTER . ' im
					set
					im.verify=1,
                                        im.v_remarks=' . $mysql->quote($reason).'
					where im.status=2 and im.id=' . $mysql->getInt($id);
        $mysql->query($sql);

        /// email sending script

        $body = '
				<h2>New Order Received For Verification</h2>
				<p><b>Username:</b>' . $rows[0]['username'] . '<p>
				<p><b>IMEI:</b>' . $rows[0]['imei'] . '<p>
				<p><b>Unlock Code:</b>' . base64_decode($rows[0]['reply']) . '<p>
				<p><b>Credits:</b>' . $rows[0]['credits'] . '<p>
				<p><b>Order ID :</b>' . $rows[0]['order_id'] . '<p>
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
        $emailObj->setSubject("New Order Received For Verification");
        $emailObj->setBody($body);
        //$sent = $emailObj->sendMail();
        $save = $emailObj->queue();

        ////////////////////////
        // set templete for supplier
        if ($supplieremail != '') {
            $body = '
				<h2>New Order Received For Verification</h2>
				<p><b>IMEI:</b>' . $rows[0]['imei'] . '<p>
				<p><b>Unlock Code:</b>' . base64_decode($rows[0]['reply']) . '<p>
				<p><b>Order ID :</b>' . $rows[0]['order_id'] . '<p>
				<p>--------------------------------------------------				
				';
            $emailObj->setBody($body);
            $emailObj->setTo($supplieremail);
            //   $sent = $emailObj->sendMail();
            $save = $emailObj->queue();
        }

        header('location:' . CONFIG_PATH_SITE_USER . 'imei.html?reply=' . urlencode('reply_send_verification'));
        exit();
    } else {
        header('location:' . CONFIG_PATH_SITE_USER . 'imei.html?reply=' . urlencode('reply_not_authorized_cancel_imei'));
        exit();
    }
} else {
    header('location:' . CONFIG_PATH_SITE_USER . 'imei.html?reply=' . urlencode('reply_reason_empty'));
    exit();
}
?>