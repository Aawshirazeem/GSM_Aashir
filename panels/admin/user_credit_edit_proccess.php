<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
// echo $_REQUEST["gt"]; 
//var_dump($_REQUEST);
//exit;

$id = $request->GetInt('id');
$amnt = $request->GetFloat('amnt');
$cr = $request->GetFloat('cr');
$gt = $_REQUEST["gt"];
$type = $request->GetInt('type');
//  echo $gt;exit;
if ($gt == '') {
    $gt = 'Paid';
}
if ($amnt > 0) {
    $sql_in = 'update ' . INVOICE_MASTER . ' set amount=amount - ' . $amnt . ',date_time_paid=now()  where paid_status=0 and id=' . $id;
//echo $sql_in;exit;
    $mysql->query($sql_in);

    //entery into invoice log
    $inv_log = 'insert into ' . INVOICE_LOG . ' (inv_id,amount, credits, gateway_id,date_time,receiver,last_updated_by,remarks) values
		    				(' . $id . ', ' . $amnt . ', ' . $cr . ', "' . $gt . '", now(),' . $admin->getUserId() . ',' . $admin->getUserId() . '," Payment Received By Customer of ' . $cr . ' Cr ")';
    //echo 	$inv_log;exit;
    $query_inv = $mysql->query($inv_log);

    $get_unpain_amount = 'select inv.amount,usr.email from ' . INVOICE_MASTER . ' inv left join ' . USER_MASTER . ' usr on (inv.user_id=usr.id) where inv.id=' . $id;
    // echo $get_unpain_amount;exit;
    $unpain_amount = $mysql->query($get_unpain_amount);
    $unpain_amount = $mysql->fetchArray($unpain_amount);
    $u_email = $unpain_amount[0]["email"];
    //
    if ($type == 1 || $unpain_amount[0]["amount"] == 0) {
        $id = $request->GetInt('id');
        $sql_in = 'update ' . INVOICE_MASTER . ' set paid_status=1 where paid_status=0 and id=' . $id;
        $mysql->query($sql_in);

        //header("location:" . CONFIG_PATH_SITE_ADMIN . "users_credit_invoices.html?reply=" . urlencode('reply_invoice_accepted'));
        echo 'done';
        //exit();
    } else {

        //	echo $unpain_amount;
        echo $unpain_amount[0]["amount"];
    }

    //send maill code if unlock code unavailable

    $objEmail = new email();
    $email_config = $objEmail->getEmailSettings();
    $from_admin = $email_config['system_email'];
    $admin_from_disp = $email_config['system_from'];
    $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

    $simple_email_body = '';
    //  $simple_email_body .= '<h2>Your Unlock Code is Cancelled</h2>';
    $simple_email_body .= '
         <p>Dear Customer, Your Inoice #' . $id . ' has been Updated. 
         <p>You Can Check Your Invoice Detail.
         ';

    $objEmail->setTo($u_email);
    $objEmail->setFrom($from_admin);
    $objEmail->setFromDisplay($admin_from_disp);
    $objEmail->setSubject("Invoice Update");
    //  $objEmail->setBody($simple_email_body);
    $objEmail->setBody($simple_email_body . $signatures);
    //$sent = $objEmail->sendMail();
    $save = $objEmail->queue();
    // SEND Simple Email
}
?>