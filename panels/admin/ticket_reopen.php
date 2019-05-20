<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();

$id = $request->GetInt('id');
$details = $request->getStr('dt');
$user_id = $member->getUserId();
$usernamee = $member->getFullName();
$useremailid = $member->getEmail();


$sql = 'update ' . TICKET_MASTER . ' set status=1 where ticket_id=' . $mysql->getInt($id);
$mysql->query($sql);
// email send script
$body = '
				<h3>Dear User admin reopend the Ticket</h3>
				<p><b>Ticket ID:</b>' . $id . '<p>
				<p><b>Subject:</b>' . $details . '<p>
				
				<p>--------------------------------------------------</p>
                                <h5>By<h5>Admin
				';

$emailObj = new email();
$email_config = $emailObj->getEmailSettings();
//echo '<pre>'; print_r($email_config);echo '</pre>';

$admin_email = $email_config['admin_email'];
$from_admin = $email_config['system_email'];
$admin_from_disp = $email_config['system_from'];
$support_email = $email_config['support_email'];
$signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

$emailObj->setTo($useremailid);
$emailObj->setFrom($from_admin);
$emailObj->setFromDisplay($admin_from_disp);
$emailObj->setSubject("Ticket Reopened");
$emailObj->setBody($body);
//$sent = $emailObj->sendMail();
 $save = $emailObj->queue();

///
header('location:' . CONFIG_PATH_SITE_ADMIN . 'ticket.html?id=' . $id . '&reply=' . urlencode('reply_tic_close'));
?>