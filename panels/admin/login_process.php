<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


$username = $request->PostStr('username');
$password = $request->PostStr('password');
if ($username != "" && $password != "") {
    $stay_signed_in = $request->PostCheck('stay_signed_in');
    if ($stay_signed_in == '1') {
        $cookie->useCookie();
    } else {
        $cookie->useSession();
    }

    $_SESSION['tempUsername'] = $username;
    $_SESSION['tempPassword'] = $objPass->generate($password);
    $_SESSION['tempTextPassword'] = $password;
    header("location:" . CONFIG_PATH_SITE_ADMIN . "login_process_secure.do");
} else {
    //  header("location:" . CONFIG_PATH_SITE_ADMIN . "login_process_secure.do");
    header('location:' . CONFIG_PATH_SITE_ADMIN . 'index.html?reply=' . urlencode('reply_session_expired'));
    exit;
}
?>