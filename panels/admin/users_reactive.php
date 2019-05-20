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
$id = $request->GetInt('id');
 $sql2 = 'update '.USER_MASTER.' set wrong_password_count=0,status=1 where id=' .$id;
 //echo $sql2;exit;
 $mysql->query($sql2);
 $sql = 'select a.email from nxt_user_master a where a.id='.$id;
  $query = $mysql->query($sql);
    if ($mysql->rowCount($query) > 0) {
        $rows = $mysql->fetchArray($query);
        $email=$rows[0]['email'];
    }
 
 
 // send email to the user
 $emailObj = new email();
$email_config = $emailObj->getEmailSettings();

$from = $email_config['system_email'];
$from_display = $email_config['system_from'];
$signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);
$subject='Account Reactive';
$body='Your Account has been Unblokced';
$sent = false;
    // echo $body;
    //exit;
    $objEmail = new email();
    $objEmail->setTo($email);
    $objEmail->setFrom($from);
    $objEmail->setFromDisplay($from_display);
    $objEmail->setSubject($subject);
    $objEmail->setBody($body . $signatures);
   // $objEmail->sendMail();
    $objEmail->queue();
    // email sent
 header("location:" . CONFIG_PATH_SITE_ADMIN . "users.html?reply=" . urlencode('reply_user_active'));
exit();