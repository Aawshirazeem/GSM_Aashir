<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	
	$member->checkLogin();
	$member->reject();
	$validator->formValidateUser('user_edit_64565646428');	
	
    $id = $request->PostInt('id');
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


	$qPassword = (trim($password) != '') ? 'password = ' . $mysql->quote($objPass->generate($password)) . ',' : '';
	
	$sql = 'update ' . USER_MASTER . '
			set 
			' . $qPassword . '
			service_imei = ' . $mysql->getInt($service_imei) . ',
			service_file = ' . $mysql->getInt($service_file) . ' ,
			service_logs = ' . $mysql->getInt($service_logs) . ',
			service_shop = ' . $mysql->getInt($service_shop) . ',
			status = ' . $mysql->getInt($status) . ',
			first_name = ' . $mysql->quote($first_name) . ',
			last_name = ' . $mysql->quote($last_name) . ',
			company = ' . $mysql->quote($company) . ',
			address = ' . $mysql->quote($address) . ',
                            city = ' . $mysql->quote($city) . ',
                                phone = ' . $mysql->quote($phone) . ',
                                    mobile = ' . $mysql->quote($mobile) . ',
                                        timezone_id = ' . $mysql->getInt($timezone) . ',
                                            language_id = ' . $mysql->getInt($language) . ',
			country_id = ' . $mysql->getInt($country) . '
			where id = ' . $mysql->getInt($id);
    //    echo $sql;exit;
	$mysql->query($sql);
	
	header("location:" . CONFIG_PATH_SITE_USER . "users.html?reply=" . urlencode('reply_update_success'));
	exit();
?>