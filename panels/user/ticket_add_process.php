<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$member->checkLogin();
$member->reject();
$validator->formValidateUser('user_tick_add_98374428');


if ($request->PostInt('trans_id') > 0) {
    $trans_id = $request->PostInt('trans_id');
} else {
    $trans_id = 0;
}
$subject = $request->PostStr('subject');
$details = $request->PostStr('details');


if ($subject == "") {
    header('location:' . CONFIG_PATH_SITE_USER . 'ticket_add.html?reply=' . urlencode('reply_ticket_name_miss'));
    exit();
}

if ($details == "") {
    header("location:" . CONFIG_PATH_SITE_USER . "ticket_add.html?reply=" . urlencode('reply_empty_ticket'));
    exit();
}


$sql = 'insert into ' . TICKET_MASTER . '
			(user_id, trans_id, subject, date_time, last_ticket, status, awating)
			values(
			' . $member->getUserId() . ',
			' . $mysql->getInt($trans_id) . ',
			' . $mysql->quote($subject) . ', 
			now(), now(),
			1, 1)';
$mysql->query($sql);
$newid = $mysql->insert_id();

$sql = 'insert into ' . TICKET_DETAILS . '
			(ticket_id,comments, user_admin, date_time)
			values(
			' . $mysql->getInt($newid) . ',
			' . $mysql->quote($details) . ', 
			0, now())';
$mysql->query($sql);

$user_id = $member->getUserId();
$usernamee=$member->getFullName();
$useremailid=$member->getEmail();

$sql_email = 'select username,email from ' . USER_MASTER . ' where id=' . $user_id;
$query_email = $mysql->query($sql_email);
if ($mysql->rowCount($query_email) > 0) {
    $emails = $mysql->fetchArray($query_email);
    $email = $emails[0]['email'];
    $username = $emails[0]['username'];
    $ticket_subject = $subject;
    // email sending script
    $objEmail = new email();  
     $email_config = $objEmail->getEmailSettings();
            $admin_email = $email_config['admin_email'];
            $system_from = $email_config['system_email'];
            $from_display = $email_config['system_from'];
    $args = array(
        'to' => $admin_email,
        'from' => $system_from,
        'fromDisplay' => $from_display,
        'user_id' => $user_id,
        'save' => 1,
        'username' => $usernamee,
        'ticket_subject' => $ticket_subject,
        'site_admin' => 'Admin',
        'send_mail'  => true
    );
    $objEmail->sendEmailTemplate('user_add_support_ticket_user', $args);
    // email sent
}
header("location:" . CONFIG_PATH_SITE_USER . "ticket_details.html?id=" . $newid . "&reply=" . urlencode('reply_post_ticket_success'));
exit();
?>