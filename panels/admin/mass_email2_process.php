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

$admin->checkLogin();
$admin->reject();


$check_mailto = 2;
$in = "";
$out = "";
if (isset($_POST['mail_to']))
    $check_mailto = $_POST['mail_to'];


// user get
if (isset($_POST['u_list'])) {
    foreach ($_POST['u_list'] as $a)
        $in.=$a . ',';

    $in = rtrim($in, ',');

    // $sqluser = 'select um.username,um.email from ' . USER_MASTER . ' um where um.id in (' . $in . ')';
    $sqluser = 'select * from nxt_ulistdetail ud where ud.`status`=1 and  ud.u_id in (' . $in . ')';
} else
//   $sqluser = 'select um.username,um.email from ' . USER_MASTER . ' um where um.id in (0)';
    $sqluser = 'select * from nxt_ulistdetail ud where ud.`status`=1 and  ud.u_id in (0)';

$result = $mysql->getResult($sqluser);
if ($result['COUNT']) {


    if ($check_mailto == 2) {


        // elist

        if (isset($_POST['e_list'])) {
            foreach ($_POST['e_list'] as $b)
                $out.=$b . ',';

            $out = rtrim($out, ',');

            // $sqluser = 'select um.username,um.email from ' . USER_MASTER . ' um where um.id in (' . $in . ')';
            $sqlemail = 'select * from nxt_elistdetail ud where ud.`status`=1 and  ud.e_id in (' . $out . ')';
        } else
//   $sqluser = 'select um.username,um.email from ' . USER_MASTER . ' um where um.id in (0)';
            $sqlemail = 'select * from nxt_elistdetail ud where ud.`status`=1 and  ud.e_id in (0)';

        $result2 = $mysql->getResult($sqlemail);
        if ($result2['COUNT']) {
            // email has
            foreach ($result2['RESULT'] as $emailtemp) {

                $subject = $emailtemp['subject'];
                $body = $emailtemp['body'];
                $emailObj = new email();
                $email_config = $emailObj->getEmailSettings();

                $from = $email_config['system_email'];
                $from_display = $email_config['system_from'];
                $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);



                foreach ($result['RESULT'] as $umail) {

                    $objEmail = new email();
                    $objEmail->setTo($umail['email']);
                    $objEmail->setFrom($from);
                    $objEmail->setFromDisplay($from_display);
                    $objEmail->setSubject($subject);
                    $objEmail->setBody($body . $signatures);
                    // $sent = $objEmail->sendMail();
                    $objEmail->queue();
                }
            }
        }
    } else {

        $subject = $_REQUEST['subject'];
        $body = $_REQUEST['editor1'];
        $emailObj = new email();
        $email_config = $emailObj->getEmailSettings();

        $from = $email_config['system_email'];
        $from_display = $email_config['system_from'];
        $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);


        foreach ($result['RESULT'] as $umail) {

            $objEmail = new email();
            $objEmail->setTo($umail['email']);
            $objEmail->setFrom($from);
            $objEmail->setFromDisplay($from_display);
            $objEmail->setSubject($subject);
            $objEmail->setBody($body . $signatures);
            // $sent = $objEmail->sendMail();
            $objEmail->queue();
        }

        //echo 'use manula email';
    }

    
}
header('location:' . CONFIG_PATH_SITE_ADMIN . 'mass_email2.html?reply='.urlencode('repl_mail(s)_Sent'));
exit();
//echo 'ALL DONE';