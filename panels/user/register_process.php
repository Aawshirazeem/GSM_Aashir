<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$admin = new admin();
$request = new request();
$mysql = new mysql();
$cookie = new cookie();

// custome fieldss
//var_dump($_POST);
//exit;

$custom_1 = $request->PostStr('custom_1');
$custom_name_1 = $request->PostStr('custom_name_1');
if ($custom_1 != "" || $custom_name_1 != "") {

    $custom_1 = $custom_name_1 . ':' . $custom_1;
}
$custom_2 = $request->PostStr('custom_2');
$custom_name_2 = $request->PostStr('custom_name_2');
if ($custom_2 != "" || $custom_name_2 != "") {

    $custom_2 = $custom_name_2 . ':' . $custom_2;
}

$custom_3 = $request->PostStr('custom_3');
$custom_name_3 = $request->PostStr('custom_name_3');
if ($custom_3 != "" || $custom_name_3 != "") {

    $custom_3 = $custom_name_3 . ':' . $custom_3;
}
$custom_4 = $request->PostStr('custom_4');
$custom_name_4 = $request->PostStr('custom_name_4');
if ($custom_4 != "" || $custom_name_4 != "") {

    $custom_4 = $custom_name_4 . ':' . $custom_4;
}
$custom_5 = $request->PostStr('custom_5');
$custom_name_5 = $request->PostStr('custom_name_5');
if ($custom_5 != "" || $custom_name_5 != "") {

    $custom_5 = $custom_name_5 . ':' . $custom_5;
}


$keyword = new keyword();


$keyNew = $keyword->generate(4) . '-';
$keyNew .= $keyword->generate(4) . '-';
$keyNew .= $keyword->generate(4) . '-';
$keyNew .= $keyword->generate(4);
$keyNew = strtoupper($keyNew);


$first_name = $request->PostStr('first_name');
$last_name = $request->PostStr('last_name');
$username = $request->PostStr('username');
$email = $request->PostStr('email');
$password_new = $request->PostStr('password_new');
$password_confim = $request->PostStr('password_confim');

$country_id = $request->PostStr('country_id');
$lan_code = $request->PostStr('language');
if ($lan_code == "")
    $lan_code = 1;
// echo $country_id;exit;
$timezone_id = $request->PostStr('timezone');
if ($timezone_id == "") {
    //get the default time zone of the site

    $sql_timezone1 = 'select a.id from ' . TIMEZONE_MASTER . ' a where a.is_default=1';
    $query_timezone1 = $mysql->query($sql_timezone1);
    $rows_timezone1 = $mysql->fetchArray($query_timezone1);
    $timezone_id = $rows_timezone1[0]['id'];
}
$city = $request->PostStr('city');
$phone = $request->PostStr('phone');
$currency = $request->PostInt('currency');


//$captchaCode = $request->PostStr('captchaCode');
//$codeToCheckCaptcha = $_SESSION['codeToCheckCaptcha'];
//if ($captchaCode != $codeToCheckCaptcha) {
//    // header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("invalid_verification_code"));
//    echo 'invalid_verification_code';
//    exit();
//}


if ($first_name == "") {
    header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("name_missing"));
    exit();
}
if ($password_new == "") {
    header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("password_missing"));
    exit();
}
if ($username == "") {
    header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("username_missing"));
    exit();
}
if ($email == "") {
    header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("email_missing"));
    exit();
}

if ($country_id == 0) {
    header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("country_missing"));
    exit();
}


// for checking both username and email exist in db in one time for non-active users usersssss

$sql11 = 'select id from ' . USER_REGISTER_MASTER . ' where username = ' . $mysql->quote($username);
$sql22 = 'select id from ' . USER_REGISTER_MASTER . ' where email = ' . $mysql->quote($email);
$query11 = $mysql->query($sql11);
$query22 = $mysql->query($sql22);
if (($mysql->rowCount($query11) > 0) && ($mysql->rowCount($query22) > 0)) {
    //header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("duplicate_email_username"));
    echo 'duplicate_email_username';
    exit();
}

$sql = 'select id from ' . USER_REGISTER_MASTER . ' where username = ' . $mysql->quote($username);

$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    // header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("duplicate_username"));
    echo "duplicate_username";
    exit();
}

$sql = 'select id from ' . USER_REGISTER_MASTER . ' where email = ' . $mysql->quote($email);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    echo "duplicate_email";
    //header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("duplicate_email"));
    exit();
}
// end checking active users in db
// for checking both username and email exist in db in one time for active usersssss
$sql11 = 'select id from ' . USER_MASTER . ' where username = ' . $mysql->quote($username);
$sql22 = 'select id from ' . USER_MASTER . ' where email = ' . $mysql->quote($email);
$query11 = $mysql->query($sql11);
$query22 = $mysql->query($sql22);
if (($mysql->rowCount($query11) > 0) && ($mysql->rowCount($query22) > 0)) {
    //   header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("duplicate_email_username"));
    echo 'duplicate_email_username';
    exit();
}

$sql = 'select id from ' . USER_MASTER . ' where username = ' . $mysql->quote($username);

$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    // header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("duplicate_username"));
    echo 'duplicate_username';
    exit();
}

$sql = 'select id from ' . USER_MASTER . ' where email = ' . $mysql->quote($email);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    //header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("duplicate_email"));
    echo 'duplicate_email';
    //exit();
}
$sql = 'select id from ' . ADMIN_MASTER . ' where email = ' . $mysql->quote($email);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    //header("location:" . CONFIG_PATH_SITE . "login.html?reply=" . urlencode("duplicate_email"));
    echo 'duplicate_email';
    //exit();
}
// end checking active users in db
$hash = md5(rand(0, 1000));

$sql = 'insert into ' . USER_REGISTER_MASTER . '
			(first_name, last_name, username, email, password, activation_code,country_id,lang,timezone_id,currency_id,hash,custom_1,custom_2,custom_3,custom_4,custom_5,city,phone)
			values(
			' . $mysql->quote($first_name) . ',
			' . $mysql->quote($last_name) . ', 
			' . $mysql->quote($username) . ',
			' . $mysql->quote($email) . ',
			' . $mysql->quote($password_new) . ',
			' . $mysql->quote($keyNew) . ',
			' . $mysql->quote($country_id) . ',
                            ' . $mysql->quote($lan_code) . ',
                            ' . $mysql->quote($timezone_id) . ',
                            ' . $currency . ',
			' . $mysql->quote($hash) . ',
                                  ' . $mysql->quote($custom_1) . ',
                                                ' . $mysql->quote($custom_2) . ',
                                                    ' . $mysql->quote($custom_3) . ',
                                                        ' . $mysql->quote($custom_4) . ',
                                                            ' . $mysql->quote($custom_5) . ',
                        ' . $mysql->quote($city) . ',
			' . $mysql->quote($phone) . '
			)';
//        echo $sql;exit;
$mysql->query($sql);


$body = '
				<h2>New Registration</h2>
				<p><b>First Name:</b>' . $first_name . '<p>
				<p><b>Last Name:</b>' . $last_name . '<p>
				<p><b>Username/Email:</b>' . $email . '<p>
				<p><b>Password:</b>************<p>
				';

$emailObj = new email();
$email_config = $emailObj->getEmailSettings();
//echo '<pre>'; print_r($email_config);echo '</pre>';

$admin_email = $email_config['admin_email'];
$from_admin = $email_config['system_email'];
$admin_from_disp = $email_config['system_from'];
$support_email = $email_config['support_email'];
$signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

$emailObj->setTo($admin_email);
$emailObj->setFrom($from_admin);
$emailObj->setFromDisplay($admin_from_disp);
$emailObj->setSubject("New Registration");
$emailObj->setBody($body);
//$sent = $emailObj->sendMail();
$save = $emailObj->queue();



$sql = 'select value_int as val from ' . CONFIG_MASTER . ' WHERE field=\'AUTO_REGISTRATION\'';
$query = $mysql->query($sql);
$rows = $mysql->fetchArray($query);
$enabled = $rows[0]['val'];
if ($enabled == 1) {
//    $args = array(
//        'to' => $email,
//        'from' => $from_admin,
//        'fromDisplay' => $admin_from_disp,
//        'url' => (CONFIG_PATH_SITE . 'activate.do?username=' . $username . '&key=' . $keyNew),
//        'key' => $keyNew,
//        'username' => $username,
//        'site_admin' => $admin_from_disp,
//        'send_mail' => true);
//
//    $emailObj->sendEmailTemplate('reg_confirm', $args);
//    
    // if auto 1

    $body = '
				<h2>Welcome to ' . CONFIG_SITE_NAME . '</h2>
				<p>Hi ' . $last_name . '</p>
				<p>Thanks for joining ' . CONFIG_SITE_NAME . '</p>
                                <p>Please click this link to activate your account:</p>
                                http://' . $_SERVER['SERVER_NAME'] . CONFIG_PATH_SITE . 'panels/user/acc_active.php?email=' . $email . '&hash=' . $hash . '
				<p>For more assistance plz mail us at <b>' . $support_email . '<b></p>
				';

    $emailObj = new email();
    $emailObj->setTo($email);
    $emailObj->setFrom($from_admin);
    $emailObj->setFromDisplay($admin_from_disp);
    $emailObj->setSubject("Welcome to " . CONFIG_SITE_NAME);
    $emailObj->setBody($body . $signatures);
    //   $emailObj->sendMail();
    $save = $emailObj->queue();
    //header('location:' . CONFIG_PATH_SITE . 'login.html?reply=' . urlencode('thanks'));
    echo 'thanks1';
    exit();
} else {
//    $args = array(
//        'to' => $email,
//        'admin_mail' => $admin_email,
//        'from' => $from_admin,
//        'fromDisplay' => $admin_from_disp,
//        'username' => $username,
//        'site_admin' => $admin_from_disp,
//        'send_mail' => true);
//
//    $emailObj->sendEmailTemplate('reg_notification', $args);

    $body = '
				<h2>Welcome to ' . CONFIG_SITE_NAME . '</h2>
				<p>Hi ' . $last_name . '</p>
				<p>Thanks for joining ' . CONFIG_SITE_NAME . '. We will revert back to you soon!</p>
				<p>For more assistance plz mail us at <b>' . $from_admin . '<b></p>
				';

    $emailObj = new email();
    $emailObj->setTo($email);
    $emailObj->setFrom($from_admin);
    $emailObj->setFromDisplay($admin_from_disp);
    $emailObj->setSubject("Welcome to " . CONFIG_SITE_NAME);
    $emailObj->setBody($body . $signatures);
    //$emailObj->sendMail();
    $save = $emailObj->queue();
     echo 'thanks';
   // header('location:' . CONFIG_PATH_SITE . 'login.html?reply=' . urlencode('thanks'));
    exit();
}


//	$body = '
//				<h2>Welcome to ' . CONFIG_SITE_NAME . '</h2>
//				<p>Hi ' . $last_name . '</p>
//				<p>Thanks for joining ' . CONFIG_SITE_NAME . '. We will revert back to you soon!</p>
//				<p>For more assistance plz mail us at <b>' . $from_admin . '<b></p>
//				';
//
//	$emailObj = new email();
//	$emailObj->setTo($email);
//	$emailObj->setFrom($from_admin);
//	$emailObj->setFromDisplay($admin_from_disp);
//	$emailObj->setSubject("Welcome to " . CONFIG_SITE_NAME);
//	$emailObj->setBody($body.$signatures);
//	$emailObj->sendMail();
//header('location:' . CONFIG_PATH_SITE . 'user/index.html?reply=' . urlencode('thanks'));
//exit();	
?>