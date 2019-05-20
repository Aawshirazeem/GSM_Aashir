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
$user_id = $_POST['u_id'];
if ($user_id != "") {

    $sql = 'select * from ' . USER_MASTER . ' where id=' . $user_id;
    $query = $mysql->query($sql);

    if ($mysql->rowCount($query) > 0) {
        $rows = $mysql->fetchArray($query);
        $email = $rows[0]['email'];
        $username = $rows[0]['username'];
        $length = 10;

        $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        $sql = 'update ' . USER_MASTER . '
					set
					password=' . $mysql->quote($objPass->generate($password)) . '
					where id=' . $user_id;
        $mysql->query($sql);


        $objEmail = new email();
        $args = array(
            'to' => $email,
            'from' => CONFIG_EMAIL,
            'fromDisplay' => CONFIG_SITE_NAME,
            'user_id' => $member->getUserid(),
            'save' => '1',
            'username' => $username,
            'password' => $password,
            'site_admin' => CONFIG_SITE_NAME
        );

        $objEmail->sendEmailTemplate('user_edit_login_details', $args, $send_mail = TRUE);

        //$member->logout();
        //header("location:" . CONFIG_PATH_SITE_USER . "index.html?reply=" . urlencode('reply_pass_update'));
        echo "done";
        exit();
    } else {
        echo "error";
        exit();
    }
} else {
    echo "error";
    exit();
}