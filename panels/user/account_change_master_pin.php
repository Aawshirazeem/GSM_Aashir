<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$member->checkLogin();
$member->reject();

$sql = 'select * from ' . USER_MASTER . ' where id=' . $mysql->getInt($member->getUserId());
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
$rows = $mysql->fetchArray($query);
$row = $rows[0];

if (isset($_POST['m_pin_renew']) && isset($_POST['m_pin'])) {
    // do both

    $user_pin = rand(1111, 999999);
    $user_pin_hash = md5($user_pin);
    $sql = 'update ' . USER_MASTER . '
					set
					master_pin=1,
                                        pin= ' . $mysql->quote($user_pin_hash) . '
					where id=' . $mysql->getInt($member->getUserId());
    $mysql->query($sql);
    // send email
    $emailObj = new email();
    $email_config = $emailObj->getEmailSettings();
    $admin_email = $email_config['admin_email'];
    $from_admin = $email_config['system_email'];
    $admin_from_disp = $email_config['system_from'];
    $support_email = $email_config['support_email'];
    $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);
    $body = '
				<h4>Dear ' . $username . '</h4>
				<p>Congratulations! Your Master Pin has been updated</p>
                                <p>=============================</p>
				<p><b>Username:</b>' . $username . '</p>
                                <p><b>Your New Master Pin :</b>' . $user_pin . '</p>
                                <p>(Note: Remember that pin and never disclose pin with anyone. Use pin where system ask you Only)</p>
                                <p>=============================</p>
                                <p>Kind Regards</p>
                                <p>' . $admin_from_disp . '</p>
				';

    $emailObj->setTo($row['email']);
    $emailObj->setFrom($from_admin);
    $emailObj->setFromDisplay($admin_from_disp);
    $emailObj->setSubject("Master Pin");
    $emailObj->setBody($body);
    $sent = $emailObj->sendMail();
    if ($sent != false)
        $emailObj->saveMail();
    header("location:" . CONFIG_PATH_SITE_USER . "account_change_password.html?reply=" . urlencode('reply_master_pin_set_change'));
    exit();
}


if (isset($_POST['m_pin_renew'])) {
    // renew master pin
    $user_pin = rand(1111, 999999);
    $user_pin_hash = md5($user_pin);
    $sql = 'update ' . USER_MASTER . '
					set
				
                                        pin= ' . $mysql->quote($user_pin_hash) . '
					where id=' . $mysql->getInt($member->getUserId());
    $mysql->query($sql);
    // send email
    $emailObj = new email();
    $email_config = $emailObj->getEmailSettings();
    $admin_email = $email_config['admin_email'];
    $from_admin = $email_config['system_email'];
    $admin_from_disp = $email_config['system_from'];
    $support_email = $email_config['support_email'];
    $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);
    $body = '
				<h4>Dear ' . $username . '</h4>
				<p>Congratulations! Your Master Pin has been updated</p>
                                <p>=============================</p>
				<p><b>Username:</b>' . $username . '</p>
                                <p><b>Your New Master Pin :</b>' . $user_pin . '</p>
                                <p>(Note: Remember that pin and never disclose pin with anyone. Use pin where system ask you Only)</p>
                                <p>=============================</p>
                                <p>Kind Regards</p>
                                <p>' . $admin_from_disp . '</p>
				';

    $emailObj->setTo($row['email']);
    $emailObj->setFrom($from_admin);
    $emailObj->setFromDisplay($admin_from_disp);
    $emailObj->setSubject("Master Pin");
    $emailObj->setBody($body);
    $sent = $emailObj->sendMail();
    if ($sent != false)
        $emailObj->saveMail();
    header("location:" . CONFIG_PATH_SITE_USER . "account_change_password.html?reply=" . urlencode('reply_master_pin_updated'));
    exit();
}
if (isset($_POST['m_pin'])) {
    // update on off
    $sql = 'update ' . USER_MASTER . '
					set
					master_pin=1
					where id=' . $mysql->getInt($member->getUserId());
    $mysql->query($sql);
    header("location:" . CONFIG_PATH_SITE_USER . "account_change_password.html?reply=" . urlencode('reply_master_pin_change'));
    exit();
} else {

    if (isset($_POST['m_pin_txt']) && ($_POST['m_pin_txt'] != "")) {



        $m_pin = trim($_POST['m_pin_txt']);
        $m_pin = md5($m_pin);



        $sql = 'select * from ' . USER_MASTER . ' where id=' . $mysql->getInt($member->getUserId()) . ' and  pin= ' . $mysql->quote($m_pin);
        //echo $sql;exit;
        $query = $mysql->query($sql);
        $rowCount = $mysql->rowCount($query);

        if ($rowCount > 0) {
            $sql = 'update ' . USER_MASTER . '
					set
					master_pin=0
					where id=' . $mysql->getInt($member->getUserId());
            $mysql->query($sql);
            header("location:" . CONFIG_PATH_SITE_USER . "account_change_password.html?reply=" . urlencode('reply_master_pin_change'));
            exit();
        } else {
            header("location:" . CONFIG_PATH_SITE_USER . "account_change_password.html?reply=" . urlencode('reply_incorrect_master_pin'));
            exit();
        }
    } else {
        header("location:" . CONFIG_PATH_SITE_USER . "account_change_password.html?reply=" . urlencode('reply_no_master_pin'));
        exit();
    }
}

header("location:" . CONFIG_PATH_SITE_USER . "account_change_password.html?reply=" . urlencode('reply_no_change'));
exit();
