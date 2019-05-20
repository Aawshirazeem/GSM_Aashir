<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// check the code 

if (count($_POST) > 0 && trim($_POST['code']) != "") {
    //echo '<pre>';
    // var_dump($_POST);
    $u_id = $_POST['user'];
    $code = $_POST['code'];
    //header("location:" . CONFIG_PATH_SITE_USER . "dashboard.html");
    $sql = 'select um.*, cm.countries_iso_code_2, lm.file_name as language, tm.timezone
							from ' . USER_MASTER . ' um
							left join ' . COUNTRY_MASTER . ' cm on (um.country_id = cm.id)
							left join ' . LANGUAGE_MASTER . ' lm on (um.language_id = lm.id) 
							left join ' . TIMEZONE_MASTER . ' tm on (um.timezone_id = tm.id)
							where um.id=' . $mysql->quote($u_id);
    //echo $sql;exit;
    $query = $mysql->query($sql);

    if ($mysql->rowCount($query) != 0) {
        $row = $mysql->fetchArray($query);
        $googlestr = $row[0]['google_auth_key'];
        // echo $googlestr;
        require_once 'GoogleAuthenticator.php';
        $ga = new PHPGangsta_GoogleAuthenticator();
        $checkResult = $ga->verifyCode($googlestr, $code, 2);    // 2 = 2*30sec clock tolerance
        if ($checkResult) {


            $member->setUserName($mysql->prints($row[0]["username"]));
            $member->setFullName($mysql->prints($row[0]["first_name"]) . ' ' . $mysql->prints($row[0]["last_name"]));
            $member->setUserId($row[0]["id"]);
            $member->setCurrencyId($row[0]["currency_id"]);
           // $member->setAPIAccess($row[0]["api_access"]);
            $member->setUserType($row[0]["user_type"]);
            $member->setPassword($row[0]["password"]);
            $member->setEmail($row[0]["email"]);
            $member->setLang($row[0]["language"]);
            
            
            $sql233 = 'update '.USER_MASTER.'  set wrong_password_count=0 where id=' . $mysql->quote($u_id);
            $mysql->query($sql233);
            
            header("location:" . CONFIG_PATH_SITE_USER . "dashboard.html");
            exit();
            // echo 'OK';
        } else {
            //  echo 'FAILED';
            header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode('reply_invalid_google_auth_code'));
            exit;
            //break;
        }
        //var_dump($row);
        exit;
    } else {
        header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode('reply_invalid_user_info'));
        exit;
    }
} else {
    header("location:" . CONFIG_PATH_SITE . "user/index.html?reply=" . urlencode('reply_code_empty'));
    exit;
}