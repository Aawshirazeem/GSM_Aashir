<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


$keyword = new keyword();

//$admin->checkLogin();
//$admin->reject();
//$subject = $request->postStr('subject');
$subject = $_REQUEST['subject'];
// $body = ($_POST['body']);
$body = $_REQUEST['body'];
$iddd = $_REQUEST['IDD'];

$sql = 'select * from nxt_mail_history a where a.user_id=777777 and a.subject=' . $mysql->quote($subject) . ' and a.content=' . $mysql->quote($body) . '';
$result = $mysql->getResult($sql);
if ($result['COUNT'] == 0) {


    $sql = 'insert into ' . MAIL_HISTORY . '(user_id, date_time, subject, content, plain_mail) values(

					' . $mysql->getInt(777777) . ',

					now(),

					' . $mysql->quote($subject) . ',

					' . $mysql->quote($body) . ',

					' . $mysql->quote($body) . '

					)';

    $mysql->query($sql);
}

//$body = htmlentities(trim($_POST['body']), ENT_QUOTES, 'UTF-8');;
//$emails = $_POST['emails'];
$emails = $_REQUEST['emails'];
// echo '<pre>';
// var_dump($emails);
//echo 'here';
//echo $emails;
//exit;
error_log($_REQUEST['body'], 3, CONFIG_PATH_LOGS_ABSOLUTE . "/email_queue.log");

$emailObj = new email();
$email_config = $emailObj->getEmailSettings();

$from = $email_config['system_email'];
$from_display = $email_config['system_from'];
$signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

$sent = false;
foreach ($emails as $email) {
    // echo $body;
    //exit;
    $objEmail = new email();
    $objEmail->setTo($email);
    $objEmail->setFrom($from);
    $objEmail->setFromDisplay($from_display);
    $objEmail->setSubject($subject);
    $objEmail->setBody($body . $signatures);
   // $sent = $objEmail->sendMail();
    $objEmail->queue();
}
// now save that 

$objEmail->setUserID(777);
//  $objEmail->setSubject($subject);
$objEmail->setBody($body);
$objEmail->setPlainBody($body);
//$objEmail->saveMail();
echo $iddd;
exit;
?>