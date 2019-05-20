<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$member->checkLogin();
$member->reject();
$validator->formValidateUser('user_add_64565646428');
// echo '<pre>';
//var_dump($_POST);exit;
$username = $request->PostStr('username');
$password = $request->PostStr('password');
$email = $request->PostStr('email');
$credits = $request->PostInt('credits');
$service_imei = $request->PostInt('service_imei');
$service_file = $request->PostInt('service_file');
$service_logs = $request->PostInt('service_logs');
$service_shop = $request->PostInt('service_shop');
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

$result = $objCredits->getCredits();
$currency_id = ($result['currency_id'] != 0) ? $result['currency_id'] : $result['default_currency_id'];

if ($username == "") {
    header("location:" . CONFIG_PATH_SITE_USER . "user_add.html?reply=" . urlencode('Error:reply_user_miss'));
    exit();
}
if ($password == "") {
    header("location:" . CONFIG_PATH_SITE_USER . "user_add.html?reply=" . urlencode('Error:reply_pass_miss'));
    exit();
}
if ($email == "") {
    header("location:" . CONFIG_PATH_SITE_USER . "user_add.html?reply=" . urlencode('Error:reply_email_miss'));
    exit();
}

$sql = 'select * from ' . USER_MASTER . ' where username=' . $mysql->quote($username);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    header("location:" . CONFIG_PATH_SITE_USER . "user_add.html?reply=" . urlencode('Error:reply_duplicate_name'));
    exit();
}


$sql = 'select username from ' . USER_MASTER . ' where email=' . $mysql->quote($email);
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    header("location:" . CONFIG_PATH_SITE_USER . "user_add.html?reply=" . urlencode('Error:reply_duplicate_email'));
    exit();
}

$crAcc = 0;
$sql_credits = 'select id, credits,user_credit_transaction_limit from ' . USER_MASTER . ' where id=' . $member->getUserId();
$query_credits = $mysql->query($sql_credits);
if ($mysql->rowCount($query_credits) > 0) {
    $row_credits = $mysql->fetchArray($query_credits);
    $crAcc = $row_credits[0]["credits"];
    $credit_limit = $row_credits[0]['user_credit_transaction_limit'];

    if ($crAcc < $credits) {
        header('location:' . CONFIG_PATH_SITE_USER . 'user_add.html?reply=' . urlencode('Error:reply_insuff_credits'));
        exit();
    }
    if ($credit_limit < $credits && $credit_limit > 0) {
        header('location:' . CONFIG_PATH_SITE_USER . 'user_add.html?reply=' . urlencode('Error:reply_above_transact_limit'));
        exit();
    }
}

$keyword = new keyword();
$key = $request->GetStr('key');

$keyNew = $keyword->generate(4) . '-';
$keyNew .= $keyword->generate(4) . '-';
$keyNew .= $keyword->generate(4) . '-';
$keyNew .= $keyword->generate(4);
$keyNew = strtoupper($keyNew);
$loginKey = $keyword->generate(20);

$sql = 'insert into ' . USER_MASTER . '
			(username, password, api_key, login_key, email, reseller_id, user_type, creation_date,
				service_imei, service_file, service_logs, service_shop, 
				credits, status, first_name, last_name, company, address, country_id,
				currency_id,city,phone,mobile,timezone_id,language_id)
			values(
			' . $mysql->quote($username) . ',
			' . $mysql->quote($objPass->generate($password)) . ', 
			' . $mysql->quote($keyNew) . ',
                            ' . stripslashes($mysql->quote($loginKey)) . ',
			' . $mysql->quote($email) . ', 
			' . $mysql->getInt($member->getUserId()) . ', 
			0, 
			now(),
			' . $mysql->getInt($service_imei) . ', 
			' . $mysql->getInt($service_file) . ' , 
			' . $mysql->getInt($service_logs) . ', 
			' . $mysql->getInt($service_shop) . ',
			' . $mysql->getInt($credits) . ', 
			' . $mysql->getInt($status) . ', 
			' . $mysql->quote($first_name) . ', 
			' . $mysql->quote($last_name) . ', 
			' . $mysql->quote($company) . ', 
			' . $mysql->quote($address) . ', 
			' . $mysql->getInt($country) . ',
			' . $currency_id . ',
                            ' . $mysql->quote($city) . ', 
                                ' . $mysql->quote($phone) . ', 
                                     ' . $mysql->quote($mobile) . ', 
                                    ' . $mysql->getInt($timezone) . ',
                                        ' . $mysql->getInt($language) . '
			)';
// echo $sql;exit;
$mysql->query($sql);

$newUserId = $mysql->insert_id();

$objCredits->transfer($member->getUserID(), $newUserId, $credits, 'Account Created');
$sql = 'update ' . USER_MASTER . ' um2
				set
				um2.credits=um2.credits - ' . $mysql->getFloat($credits) . ',
				um2.credits_used=um2.credits_used + ' . $mysql->getFloat($credits) . ' 
				where um2.id=' . $mysql->getInt($member->getUserId());
$mysql->query($sql);

header("location:" . CONFIG_PATH_SITE_USER . "users.html?reply=" . urlencode('reply_add_success'));
exit();
?>