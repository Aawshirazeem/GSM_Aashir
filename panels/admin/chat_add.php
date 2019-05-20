<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$current_time = time();
$admin_id = $request->PostStr('a_id');
$user_id = $request->PostStr('u_id');
$msg = $request->PostStr('msg');
$sql = 'insert into ' . Chat_Box . ' (admin_id,user_id,msg,isadmin,isview,entry_type)
values (' . $mysql->getInt($admin_id) . ',' . $mysql->getInt($user_id) . ',' . $mysql->quote($msg) . ',0,1,' . $mysql->quote(admin) . '),(' . $mysql->getInt($admin_id) . ',' . $mysql->getInt($user_id) . ',' . $mysql->quote($msg) . ',0,0,' . $mysql->quote(user) . ')';
//echo $sql;
$mysql->query($sql);


// check if user offline then send an email..
$sql = 'select a.online,a.notify,a.email,a.last_active_time from ' . USER_MASTER . ' a where a.id=' . $user_id;
//echo $sql;
//$result = $mysql->getResult($sql);
$qrydata = $mysql->query($sql);
//var_dump($qrydata);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
  //  var_dump($rows);
    $userchatstatus = $rows[0]['online'];
    $useremail = $rows[0]['email'];
    $userlastactivetime= $rows[0]['last_active_time'];
    $timestamp = strtotime($userlastactivetime);
    $latest = $current_time - $timestamp;
    $latest = $latest / 60;
    
    
}
//echo 'usre'.$userchatstatus;
if ($userchatstatus != 1) {
    //send an email   

    $body = '
				<h4>Dear USER</h4>
				<p>You have recieved message from '.CONFIG_SITE_NAME.' chat support. please login and check</p>
				<p>--------------------------------------------------				
				';

    $emailObj = new email();
    $email_config = $emailObj->getEmailSettings();
    //echo '<pre>'; print_r($email_config);echo '</pre>';

   // $admin_email = $email_config['admin_email'];
    $from_admin = $email_config['system_email'];
    $admin_from_disp = $email_config['system_from'];
    $support_email = $email_config['support_email'];
    $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

    $emailObj->setTo($useremail);
    $emailObj->setFrom($from_admin);
    $emailObj->setFromDisplay($admin_from_disp);
    $emailObj->setSubject("New Chat Message");
    $emailObj->setBody($body);
    
    //var_dump($emailObj);exit;
 //   $sent = $emailObj->sendMail();
       $save = $emailObj->queue();
    if($sent==TRUE)
    echo "mail send";exit;
}
?>