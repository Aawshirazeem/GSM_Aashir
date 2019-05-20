<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//var_dump($_REQUEST);
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

if (isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])) {
    echo 'Verify data';
    //header('location:' . CONFIG_PATH_SITE . 'user/index.html?reply=' . urlencode('Accont Activated Successfuu'));
    $email = mysql_escape_string($_GET['email']); // Set email variable
    $hash = mysql_escape_string($_GET['hash']); // Set hash variable
    $sql = 'select * from ' . USER_REGISTER_MASTER . ' where email = ' . $mysql->quote($email) . ' and hash=' . $mysql->quote($hash);
    $query = $mysql->query($sql);
    if ($mysql->rowCount($query) == 0) {
        header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode("link_broken"));
        exit();
    } else {
        // data is good so active the user

        $rows = $mysql->fetchArray($query);

        $old_id = $rows[0]['id'];
        $first_name = $rows[0]['first_name'];
        $last_name = $rows[0]['last_name'];
        $username = $rows[0]['username'];
        $email = $rows[0]['email'];
        $password = $rows[0]['password'];
        $country = $rows[0]['country_id'];
        $currency = $rows[0]['currency_id'];
        $city = $rows[0]['city'];
        $phone = $rows[0]['phone'];
        $timezone = $rows[0]['timezone_id'];
        $custom_1 = $rows[0]['custom_1'];
        $custom_2 = $rows[0]['custom_2'];
        $custom_3 = $rows[0]['custom_3'];
        $custom_4 = $rows[0]['custom_4'];
        $custom_5 = $rows[0]['custom_5'];
        //-----------------------
        $user_type = 0;
        $service_imei = 1;
        $service_file = 1;
        $service_logs = 1;
        $service_prepaid = 1;
        $service_shop = 0;
        $api_access = 0;
        $status = 1;
        $credits = 0;
        $language = 1;
        $credits_transaction_limit = 0;

        $company = "";
        $address = "";
        $account_suspension_days = 0;

        $keyword = new keyword();

        $keyNew = $keyword->generate(4) . '-';
        $keyNew .= $keyword->generate(4) . '-';
        $keyNew .= $keyword->generate(4) . '-';
        $keyNew .= $keyword->generate(4);
        $keyNew = strtoupper($keyNew);
        $loginKey = $keyword->generate(20);

        // user pin

        $user_pin = rand(1111, 999999);
        $user_pin_hash = md5($user_pin);
        // query to add data
        $sql = 'insert into ' . USER_MASTER . '
			(username, password, api_key, login_key, email, user_type, creation_date,
				service_imei, service_file, service_logs, service_prepaid, service_shop, 
				credits,currency_id,timezone_id,language_id,user_credit_transaction_limit, api_access, status, first_name, last_name,custom_1,custom_2,custom_3,custom_4,custom_5,pin, company, city,address,phone, country_id ,account_suspension_days)
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
			' . $mysql->getFloat($credits) . ',
			' . $currency . ',' . $timezone . ',' . $language . ',
			' . $mysql->getFloat($credits_transaction_limit) . ',
			' . $mysql->getFloat($api_access) . ',
			' . $mysql->getInt($status) . ', 
			' . $mysql->quote($first_name) . ', 
			' . $mysql->quote($last_name) . ', 
			' . $mysql->quote($company) . ', 
                        ' . $mysql->quote($custom_1) . ',
                        ' . $mysql->quote($custom_2) . ',
                        ' . $mysql->quote($custom_3) . ',
                        ' . $mysql->quote($custom_4) . ',
                        ' . $mysql->quote($custom_5) . ',
                        ' . $mysql->quote($user_pin_hash) . ',
			' . $mysql->quote($city) . ', 
			' . $mysql->quote($address) . ', 
			' . $mysql->quote($phone) . ', 
			' . $mysql->getInt($country) . ', 
			' . $mysql->getInt($account_suspension_days) . ')';
        //echo $sql;
        //exit;

        $mysql->query($sql);
        $id = $mysql->insert_id();
        if ($id != 0) {
            $sql = 'delete from  ' . USER_REGISTER_MASTER . ' where id=' . $old_id;
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
        $sent = $emailObj->sendMail();
        if ($sent != false)
            $emailObj->saveMail();
        
        // new email temp end
        //--------------------------------------------- 
        
        // send a email 
        /*
          $objEmail = new email();

          $email_config = $objEmail->getEmailSettings();
          $from_admin = $email_config['system_email'];
          $admin_from_disp = $email_config['system_from'];


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

          $objEmail->sendEmailTemplate('admin_user_add', $args); */
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

        header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode("account_activated"));
        exit();
    }
} else {

    header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode("Invalid_Approach"));
    exit();
    //exit;
}
//echo 'Your account is activited now';