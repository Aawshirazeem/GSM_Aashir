<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


$keyword = new keyword();

$admin->checkLogin();
$admin->reject();

$ids = $_GET['ids'];

foreach ($ids as $id) {
    $keyNew = $keyword->generate(6);
    $pass = strtoupper($keyNew);
    $keyNew = $objPass->generate($pass);

    $sql = 'update ' . USER_MASTER . '
						set password=' . $mysql->quote($keyNew) . '
					where id=' . $mysql->getInt($id);
    $query = $mysql->query($sql);

    $sql = 'select * from ' . USER_MASTER . ' where id=' . $mysql->getInt($id);
    $query = $mysql->query($sql);
    $row = $mysql->fetchArray($query);

    // email to every user

    $emailObj = new email();
    $email_config = $emailObj->getEmailSettings();
    $admin_email = $email_config['admin_email'];
    $from_admin = $email_config['system_email'];
    $admin_from_disp = $email_config['system_from'];
    $support_email = $email_config['support_email'];
    $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

    // $row = $mysql->fetchArray($query);
    // $keyNew = $row[0]['api_key'];
    $email = $row[0]['email'];
    $username = $row[0]['username'];
    $args = array(
        'to' => $email,
        'from' => $from_admin,
        'fromDisplay' => $admin_from_disp,
        'user_id' => $id,
        'save' => '1',
        'username' => $username,
        'password' => $pass,
        'site_admin' => $admin_from_disp,
        'send_mail' => true
    );
    $emailObj->sendEmailTemplate('password_change', $args);
    

    //
//
//    $objEmail = new email();
//    $args = array(
//        'to' => $rows[0]['email'],
//        'from' => CONFIG_EMAIL,
//        'fromDisplay' => CONFIG_SITE_NAME,
//        'user_id' => $id,
//        'save' => '1',
//        'username' => $rows[0]['username'],
//        'site_admin' => CONFIG_SITE_NAME,
//        'password' => $pass
//    );
//
//    $objEmail->sendEmailTemplate('password_change', $args);
}

echo 'all done';
exit;
?>