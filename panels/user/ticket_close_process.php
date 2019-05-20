<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


$member->checkLogin();
$member->reject();

$id = $request->GetInt('id');
$details = $request->getStr('dt');
//echo $id.$details;

$user_id = $member->getUserId();
$usernamee = $member->getFullName();
$useremailid = $member->getEmail();

$sql = 'update ' . TICKET_MASTER . ' set status=0 where ticket_id=' . $mysql->getInt($id);
$mysql->query($sql);
$objEmail = new email();
  $email_config = $objEmail->getEmailSettings();
                    $from_admin = $email_config['system_email'];
                    $admin_from_disp = $email_config['system_from'];
$args = array(
    'to' => $from_admin,
    'from' => $useremailid,
    'fromDisplay' => $admin_from_disp,
    'username' => $id,
    'ticket_subject' => $details,
    'site_admin' => 'Admin',
    'send_mail' => true
);
var_dump($args);exit;
$objEmail->sendEmailTemplate('user_close_support_ticket_admin', $args);
header('location:' . CONFIG_PATH_SITE_USER . 'ticket.html?id=' . $id . '&reply=' . urlencode('reply_close_success'));
exit();
?>