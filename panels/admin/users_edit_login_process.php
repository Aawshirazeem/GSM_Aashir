<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();

$id = $request->PostInt('id');
$username = $request->PostStr('username');
$password = $request->PostStr('password');
$email = $request->PostStr('email');
$email_cc = $request->PostStr('email_cc');
$user_type = $request->PostInt('user_type');
// $api_access = $request->PostInt('api_access');
$service_imei = $request->PostInt('service_imei');
$service_file = $request->PostInt('service_file');
$service_logs = $request->PostInt('service_logs');
$service_prepaid = $request->PostInt('service_prepaid');
$service_shop = $request->PostInt('service_shop');
$status = $request->PostInt('status');
$over_d_limit = $request->PostFloat('ovd_c_limit');
$account_suspension_days = $request->PostInt('account_suspension_days');
$credits_transaction_limit = $request->PostFloat('credits_transaction_limit');
//$old_api_access	=	$request->PostInt('old_api_access');


$qPassword = (trim($password) != '') ? 'password = ' . $mysql->quote($objPass->generate($password)) . ',' : '';

$sql = 'update ' . USER_MASTER . '
			set 
			' . $qPassword . '
			user_type = ' . $mysql->getInt($user_type) . ',
			email = ' . $mysql->quote($email) . ',
			email_cc = ' . $mysql->quote($email_cc) . ',
			account_suspension_days = ' . $mysql->getInt($account_suspension_days) . ',
			user_credit_transaction_limit = ' . $mysql->getInt($credits_transaction_limit) . ',
			
			service_imei = ' . $mysql->getInt($service_imei) . ',
			service_file = ' . $mysql->getInt($service_file) . ',
			service_logs = ' . $mysql->getInt($service_logs) . ',
			service_prepaid = ' . $mysql->getInt($service_prepaid) . ',
			service_shop = ' . $mysql->getInt($service_shop) . ',
			status = ' . $mysql->getInt($status) . ',
                            ovd_c_limit = ' . $mysql->getFloat($over_d_limit) . '
			where id = ' . $mysql->getInt($id);
$mysql->query($sql);

if ($user_type == 0) {
    $user_type = 'user';
} else {
    $user_type = 'reseller';
}
$password = '-';
$objEmail = new email();
$email_config = $objEmail->getEmailSettings();
$from_admin = $email_config['system_email'];
$admin_from_disp = $email_config['system_from'];

//$api_access 	= $mysql->getInt($api_access);
//$old_api_access = $mysql->getInt($old_api_access);

$args = array(
    'to' => $email,
    'from' => $from_admin,
    'fromDisplay' => $admin_from_disp,
    'user_id' => $id,
    'save' => '1',
    'username' => $username,
    'password' => $password,
    'user_type' => $user_type,
    'site_admin' => $admin_from_disp,
    'send_mail' => true
);
$objEmail->sendEmailTemplate('admin_user_edit_login_details', $args);

header("location:" . CONFIG_PATH_SITE_ADMIN . "users_edit.html?id=" . $id . "&reply=" . urlencode('reply_user_edit_login'));

exit();
?>