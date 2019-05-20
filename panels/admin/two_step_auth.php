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

//$member->checkLogin();
//$member->reject();
//var_dump($_POST);
//exit;
$code = trim($_POST['code']);
$type = trim($_POST['type']);
if ($type == 0 && $code == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "two_step_verify.html?reply=" . urlencode('reply_failed_code_empty'));
    exit();
} else if ($type == "") {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "two_step_verify.html?reply=" . urlencode('reply_failed'));
    exit();
} else {
    
}

if ($type == 0) {

    // echo 'enable it';exit;
    // first check if user has auth key or not
    $sql = 'select a.id from ' . ADMIN_MASTER . ' a where a.google_auth_key="NA" and a.id=' . $admin->getUserId();
    $query = $mysql->query($sql);
    if ($mysql->rowCount($query) > 0) {
        // generate a new auth key for that user
        require_once 'GoogleAuthenticator.php';
        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();
        // echo "Secret is: " . $secret . "\n\n";
        $qrCodeUrl = $ga->getQRCodeGoogleUrl(CONFIG_SITE_NAME, $secret);
        echo '<img src="' . $qrCodeUrl . '" alt="Mountain View" style="width:atuo;height:auto;"><br /><br />';

        echo '<a href=' . CONFIG_PATH_SITE_USER . 'account_details.html class="btn btn-inverse">Go Back To Profile</a><br />';
        // add that auth key to user table

        $sqladd = 'update ' . USER_MASTER . ' set google_auth_key=' . $mysql->quote($secret) . ', two_step_auth=1 where id=' . $member->getUserId();
        $mysql->query($sqladd);
        header("location:" . CONFIG_PATH_SITE_USER . "account_details.html?reply=" . urlencode('reply_success'));
        exit();
    } else {
        // get the old auth key and make qr cide
        // $sql = 'select a.id from '.USER_MASTER.' a where a.google_auth_key="NA" and a.id=' . $member->getUserId();
        $sql2 = 'select a.google_auth_key from ' . ADMIN_MASTER . ' a where a.id=' . $admin->getUserId();
        $query = $mysql->query($sql2);
        if ($mysql->rowCount($query) > 0) {
            $rows = $mysql->fetchArray($query);
            $googlekey = $rows[0]['google_auth_key'];

            if ($googlekey != "") {
                require_once 'GoogleAuthenticator.php';
                $ga = new PHPGangsta_GoogleAuthenticator();
                //$secret = $ga->createSecret();
                //  echo "Secret is: " . $googlekey . "\n\n";
                //   $qrCodeUrl = $ga->getQRCodeGoogleUrl(CONFIG_SITE_NAME, $googlekey);
                //  echo '<img src="' . $qrCodeUrl . '" alt="Mountain View" style="width:atuo;height:auto;"><br /><br />';


                $checkResult = $ga->verifyCode($googlekey, $code, 2);    // 2 = 2*30sec clock tolerance
                if ($checkResult) {
                    $sqladd = 'update ' . ADMIN_MASTER . ' set google_auth_key=' . $mysql->quote($googlekey) . ', two_step_auth=1 where id=' . $admin->getUserId();
                    $mysql->query($sqladd);
                    header("location:" . CONFIG_PATH_SITE_ADMIN . "two_step_verify.html?reply=" . urlencode('reply_success_on'));
                    exit();
                    // echo 'OK';
                } else {
                    header("location:" . CONFIG_PATH_SITE_ADMIN . "two_step_verify.html?reply=" . urlencode('reply_failed'));
                    exit();
                }




                // echo '<a href=' . CONFIG_PATH_SITE_USER . 'account_details.html class="btn btn-inverse">Go Back To Profile</a><br />';
            }
        }
    }
} else if ($type == 1) {
    // echo 'disable it';
    $sqladd = 'update ' . ADMIN_MASTER . ' set two_step_auth=0 where id=' . $admin->getUserId();
    $mysql->query($sqladd);
    header("location:" . CONFIG_PATH_SITE_ADMIN . "two_step_verify.html?reply=" . urlencode('reply_success_off'));
    exit();
    exit;
} else {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "two_step_verify.html?reply=" . urlencode('reply_failed'));
    exit();
}
?>
