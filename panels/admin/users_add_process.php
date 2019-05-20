<?php

// this comment add to commit this file
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$objCredits = new credits();


$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('user_add_59905855d2');
$firstC = $request->PostStr("firstC");
$limit = $request->PostInt("limit");
$offset = $request->PostInt("offset");
$getString = "";
if ($firstC != '') {
    $getString .= '&firstC=' . $firstC;
}
if ($limit != 0) {
    $getString .= '&limit=' . $limit;
}
if ($offset != 0) {
    $getString .= '&offset=' . $offset;
}
$getString = trim($getString, '&');


$reg_id = $request->PostInt('reg_id');
$username = $request->PostStr('username');
$password = $request->PostStr('password');
$email = $request->PostStr('email');

$credits = $request->PostFloat('credits');
$currency = $request->PostInt('currency');
$admin_note = $request->PostStr('admin_note');
$user_note = $request->PostStr('user_note');

$user_type = $request->PostInt('user_type');
$service_imei = $request->PostInt('service_imei');
$service_file = $request->PostInt('service_file');
$service_logs = $request->PostInt('service_logs');
$service_prepaid = $request->PostInt('service_prepaid');
$service_shop = $request->PostInt('service_shop');
$api_access = $request->PostInt('api_access');
$status = $request->PostInt('status');
$first_name = $request->PostStr('first_name');
$last_name = $request->PostStr('last_name');
$company = $request->PostStr('company');
$address = $request->PostStr('address');
$city = $request->PostStr('city');
$language = $request->PostInt('language');
$timezone = $request->PostInt('timezone');
$country = $request->PostInt('country');
$phone = $request->PostStr('phone');
$mobile = $request->PostStr('mobile');
$account_suspension_days = $request->PostInt('account_suspension_days');
$credits_transaction_limit = $request->PostFloat('credits_transaction_limit');


if ($username == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "users_add.html?reply=" . urlencode('reply_user_missing') . '&' . $getString);
    exit();
}
if ($password == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "users_add.html?reply=" . urlencode('reply_user_password_missing') . '&' . $getString);
    exit();
}
if ($email == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "users_add.html?reply=" . urlencode('reply_user_email_missing') . '&' . $getString);
    exit();
}

$sql = 'select username from ' . USER_MASTER . ' where username=' . $mysql->quote($username);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "users_add.html?reply=" . urlencode('reply_user_user_duplicate') . '&' . $getString);
    exit();
}


$sql = 'select username from ' . USER_MASTER . ' where email=' . $mysql->quote($email);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "users_add.html?reply=" . urlencode('reply_user_email_duplicate') . '&' . $getString);
    exit();
}


$sql = 'select id from ' . ADMIN_MASTER . ' where email = ' . $mysql->quote($email);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    //header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("duplicate_email"));
    header("location:" . CONFIG_PATH_SITE_ADMIN . "users_add.html?reply=" . urlencode('reply_user_email_duplicate') . '&' . $getString);
    // echo 'duplicate_email';
    exit();
}

$keyword = new keyword();

$keyNew = $keyword->generate(4) . '-';
$keyNew .= $keyword->generate(4) . '-';
$keyNew .= $keyword->generate(4) . '-';
$keyNew .= $keyword->generate(4);
$keyNew = strtoupper($keyNew);

$loginKey = $keyword->generate(20);
// custome fieldss
$custom_1 = $request->PostStr('custom_1');
$custom_2 = $request->PostStr('custom_2');
$custom_3 = $request->PostStr('custom_3');
$custom_4 = $request->PostStr('custom_4');
$custom_5 = $request->PostStr('custom_5');

// user pin

$user_pin = rand(1111, 999999);
$user_pin_hash = md5($user_pin);

$sql = 'insert into ' . USER_MASTER . '
			(username, password, api_key, login_key, email, user_type, creation_date,
				service_imei, service_file, service_logs, service_prepaid, service_shop, 
				currency_id,timezone_id,language_id,user_credit_transaction_limit, api_access, status, first_name, last_name,custom_1,custom_2,custom_3,custom_4,custom_5,pin, company, city,address,phone, country_id ,account_suspension_days)
			values(
			' . stripslashes($mysql->quote($username)) . ',
			' . $mysql->quote($objPass->generate($password)) . ', 
			' . $mysql->quote($keyNew) . ',
			' . stripslashes($mysql->quote($loginKey)) . ',
			' . stripslashes($mysql->quote($email)) . ', 
			' . $mysql->getInt($user_type) . ', 
			now(),
			' . $mysql->getInt($service_imei) . ', 
			' . $mysql->getInt($service_file) . ' , 
			' . $mysql->getInt($service_logs) . ', 
			' . $mysql->getInt($service_prepaid) . ', 
			' . $mysql->getInt($service_shop) . ',
		
			' . $currency . ',' . $timezone . ',' . $language . ',
			' . $mysql->getFloat($credits_transaction_limit) . ',
			' . $mysql->getFloat($api_access) . ',
			' . $mysql->getInt($status) . ', 
			' . $mysql->quote($first_name) . ', 
			' . $mysql->quote($last_name) . ', 
                        ' . $mysql->quote($custom_1) . ',
                        ' . $mysql->quote($custom_2) . ',
                        ' . $mysql->quote($custom_3) . ',
                        ' . $mysql->quote($custom_4) . ',
                        ' . $mysql->quote($custom_5) . ',
                        ' . $mysql->quote($user_pin_hash) . ',
			' . $mysql->quote($company) . ', 
			' . $mysql->quote($city) . ', 
			' . $mysql->quote($address) . ', 
			' . $mysql->quote($phone) . ', 
			' . $mysql->getInt($country) . ', 
			' . $mysql->getInt($account_suspension_days) . ')';
//      echo $sql;exit;
$mysql->query($sql);

$id = $mysql->insert_id();


// credit add
if ($credits > 0) {
    $objCredits->transferAdmin(1, $admin->getUserID(), $id, $credits, $admin_note, $user_note);
    $sql = 'update ' . USER_MASTER . '
						set credits=credits + ' . $mysql->getFloat($credits) . '
					where id=' . $mysql->getInt($id);
    $mysql->query($sql);
}

$NewName = "";
if ($first_name != '' or $last_name != '') {
    $newName = $first_name . ' ' . $last_name;
} else {
    $newName = $username;
}
// old data del
if ($reg_id != 0) {
    $sql = 'delete from  ' . USER_REGISTER_MASTER . ' where id=' . $reg_id;
    $query = $mysql->query($sql);
}



// email send new template

$emailObj = new email();
$email_config = $emailObj->getEmailSettings();
$admin_email = $email_config['admin_email'];
$from_admin = $email_config['system_email'];
$admin_from_disp = $email_config['system_from'];
$support_email = $email_config['support_email'];
$signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);
$body = '
				<h4>Dear ' . $username . '</h4>
				<p>Congratulations! Your account has been approved and activated by our Sales Team</p>
                                <p>=============================</p>
				<p><b>Username:</b>' . $username . '</p>
                                <p><b>Password:</b>*****************</p>
                                <p><b>Pin:</b>' . $user_pin . '</p>
                                <p>(Note: Remember that pin and never disclose pin with anyone. Use pin where system ask you Only)</p>
                                <p>=============================</p>
                                <p>You need to buy credits in order to use our services. Kindly contact our sales team to purchase credits</p>
                                <p>Kind Regards</p>
                                <p>' . $admin_from_disp . '</p>
				';

$emailObj->setTo($email);
$emailObj->setFrom($from_admin);
$emailObj->setFromDisplay($admin_from_disp);
$emailObj->setSubject("You are added successfully");
$emailObj->setBody($body);
//$sent = $emailObj->sendMail();
$save = $emailObj->queue();

// new email temp end
//--------------------------------------------- 


/*
  $objEmail = new email();

  $email_config 		= $objEmail->getEmailSettings();
  $from_admin 		= $email_config['system_email'];
  $admin_from_disp	= $email_config['system_from'];


  $args = array(
  'to' => $email,
  'from' => $from_admin,
  'fromDisplay' => $admin_from_disp,
  'user_id' => $id,
  'save' => '1',
  'username' => $username,
  'password' => $password,
  'site_admin' => $admin_from_disp,
  'send_mail' => true
  );

  $objEmail->sendEmailTemplate('admin_user_add', $args);
 * 
 */
// attach all the active payment gateways to that user
$new_user_id = $id;

$sql_gw = 'select id from ' . GATEWAY_MASTER . ' gm where gm.status=1 and gm.m_id in(1,2,5,6,7,8)';
$query_gw = $mysql->query($sql_gw);
if ($mysql->rowCount($query_gw) > 0) {
    $rows_gw = $mysql->fetchArray($query_gw);
    foreach ($rows_gw as $row_gw) {

        $temp_g_id = $row_gw['id'];
        $sql = 'insert into ' . GATEWAY_DETAILS . ' (user_id, gateway_id) values(' . $new_user_id . ',' . $temp_g_id . ')';
        $query = $mysql->query($sql);
    }
}

header("location:" . CONFIG_PATH_SITE_ADMIN . "users.html?reply=" . urlencode('reply_success_add') . '&' . $getString);
exit();
?>