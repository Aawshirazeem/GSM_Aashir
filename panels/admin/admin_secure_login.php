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
							from ' . ADMIN_MASTER . ' um
							left join ' . COUNTRY_MASTER . ' cm on (um.country_id = cm.id)
							left join ' . LANGUAGE_MASTER . ' lm on (um.language_id = lm.id) 
							left join ' . TIMEZONE_MASTER . ' tm on (um.timezone_id = tm.id)
							where um.id=' . $mysql->quote($u_id);
   $sql='select um.*, lm.file_name as language
from ' . ADMIN_MASTER . ' um 
left join ' . LANGUAGE_MASTER . ' lm on (um.language_id = lm.id) 
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

           // echo $checkResult;exit;
          //  echo 'password:'.$password;
            $admin->setUserId($row[0]["id"]);
            $admin->setUserName($row[0]["username"]);
            $admin->setLang($row[0]["language"]);
            $admin->setPassword($row[0]["password"]);
            //echo $admin;
            $ip = $_SERVER['REMOTE_ADDR'];
            $sql = 'UPDATE ' . ADMIN_MASTER . ' set
								ip=' . $mysql->quote($ip) . ',
                                                                    online=1,
								session_id=' . $mysql->quote($admin->getSessionID()) . ',
								last_action=' . time() . '
							where id=' . $row[0]["id"];
        
            $mysql->query($sql);
            
            
            $sql = 'insert into ' . STATS_ADMIN_LOGIN_MASTER . ' (username, success, ip, date_time) 
							values(' . $mysql->quote($row[0]["username"]) . ', 1, ' . $mysql->quote($ip) . ', now())';
					$mysql->query($sql);
          //  $admin->checkLogin();
            header("location:" . CONFIG_PATH_SITE_ADMIN . "dashboard.html");
            exit();
            // echo 'OK';
        } else {
            //  echo 'FAILED';
            header("location:" . CONFIG_PATH_SITE . "admin/index.html?reply=" . urlencode('reply_invalid_google_auth_code'));
            exit;
            //break;
        }
        //var_dump($row);
        exit;
    } else {
        header("location:" . CONFIG_PATH_SITE . "admin/index.html?reply=" . urlencode('reply_invalid_user_info'));
        exit;
    }
} else {
    header("location:" . CONFIG_PATH_SITE . "admin/index.html?reply=" . urlencode('reply_code_empty'));
    exit;
}