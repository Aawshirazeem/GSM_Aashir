<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$res = $admin->login($_SESSION['tempUsername'], $_SESSION['tempPassword']);

if ($res == true) {

    unset($_SESSION['tempUsername']);
    unset($_SESSION['tempPassword']);
    unset($_SESSION['tempTextPassword']);
    header("location:" . CONFIG_PATH_SITE_ADMIN . "dashboard.html");
    exit();
} elseif ($res == false) {
    $email_config = $objEmail->getEmailSettings();

    $admin_email = $email_config['admin_email'];
    $system_from = $email_config['system_email'];
    $from_display = $email_config['system_from'];
    $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);


    $body = '
				<p><font color="red">Someone tried to login administrator panel with following credentials:</font></p>' .
            '<p>Username: <b>' . $_SESSION['tempUsername'] . '</b></p>' .
            '<p>Password: <b>' . $_SESSION['tempTextPassword'] . '</b></p>';

    $body .= $signatures;

    $objEmail->setTo($admin_email);
    $objEmail->setFrom($system_from);
    $objEmail->setFromDisplay($from_display);
    $objEmail->setSubject("Admin Login Attempt Failure!");
    $objEmail->setBody($body);
    //$objEmail->sendMail();
    $save = $objEmail->queue();

    unset($_SESSION['tempUsername']);
    unset($_SESSION['tempPassword']);
    unset($_SESSION['tempTextPassword']);


    header("location:" . CONFIG_PATH_SITE_ADMIN . "index.html?e_type=0&&reply=" . urlencode('reply_invalid_pass'));
    exit();
}
?>