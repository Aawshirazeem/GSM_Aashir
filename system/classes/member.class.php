<?php

class member {

    var $preCookie = "gsmFreedom";
    var $propIsLogedIn = false;
    var $accessUserMain = array(
        'dashboard' => array('dashboard', 'account_details', 'api', 'account_change_password', 'ip_settings'),
        'imei' => array('imei_order', 'imei_pending', 'imei_avail', 'imei_reject', 'imei_all', 'imei_status'),
        'file' => array('file_order', 'file_pending', 'file_avail', 'file_reject', 'file_all', 'file_status'),
        'server_log' => array('server_log', 'server_log_pending', 'server_log_avail', 'server_log_rejected', 'server_log_all', 'server_log_status'),
        'prepaid_log' => array('prepaid_logs_submit', 'prepaid_logs', 'status_prepaid_log'),
        'credits' => array('credits_purchase', 'credits_reqeusts', 'credits_invoice', 'credits_transfer', 'credits_history'),
        'reseller' => array('users', 'user_add'),
        'support' => array('inbox', 'ticket')
    );
    var $accessUser = array(
        'dashboard' => array('dashboard', 'search_ip'),
        'account_details' => array('account_details'),
        'api' => array('api'),
        'account_change_password' => array('account_change_password'),
        'ip_settings' => array('ip_settings'),
        'imei_order' => array('imei_submit'),
        'imei_pending' => array('imei#pending'),
        'imei_avail' => array('imei#avail'),
        'imei_reject' => array('imei#reject'),
        'imei_all' => array('imei#all'),
        'imei_status' => array('status_imei'),
        'file_order' => array('file_submit'),
        'file_pending' => array('files#pending'),
        'file_avail' => array('files#avail'),
        'file_reject' => array('files#reject'),
        'file_all' => array('files#all'),
        'file_status' => array('status_file'),
        'server_log' => array('server_logs_submit'),
        'server_log_pending' => array('server_logs#pending'),
        'server_log_avail' => array('server_logs#avail'),
        'server_log_rejected' => array('server_logs#rejected'),
        'server_log_all' => array('server_logs#all'),
        'server_log_status' => array('status_imei'),
        'prepaid_logs_submit' => array('prepaid_logs_submit'),
        'prepaid_logs' => array('prepaid_logs'),
        'status_prepaid_log' => array('status_prepaid_log'),
        'credits_purchase' => array('credits_purchase', 'credits_purchase_confirm'),
        'credits_reqeusts' => array('credits_reqeusts'),
        'credits_invoice' => array('credits_invoice', 'invoice'),
        'credits_transfer' => array('credits_transfer'),
        'credits_history' => array('credits_history'),
        'users' => array('users', 'user_edit', 'user_credits'),
        'user_add' => array('user_add'),
        'inbox' => array('inbox'),
        'ticket' => array('ticket', 'ticket_add', 'ticket_details'),
    );

    /* ########################################## */
    /*     * ************ GET SESSION ID ************* */

    public function getSessionID() {
        $cookie = new cookie();
        $validator = new validator();
        return $validator->getStr($cookie->getID());
    }

    /* #################################### */
    /*     * ************ USERNAME ************* */

    public function getUserName() {
        $cookie = new cookie();
        $validator = new validator();
        $result = $cookie->getCookie($this->preCookie . 'Member');
        return $validator->getStr($result);
    }

    public function setUserName($value) {
        $cookie = new cookie();
        $validator = new validator();
        $value = $validator->getStr($value);
        $cookie->setCookie($this->preCookie . 'Member', $value);
    }

    public function deleteUserName() {
        $cookie = new cookie();
        $cookie->deleteCookie($this->preCookie . 'Member');
    }

    /* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */



    /* #################################### */
    /*     * ************ FULLNAME ************* */

    public function getFullName() {
        $cookie = new cookie();
        $validator = new validator();
        $result = $cookie->getCookie($this->preCookie . 'MemberName');
        return $validator->getStr($result);
    }

    public function setFullName($value) {
        $cookie = new cookie();
        $validator = new validator();
        $value = $validator->getStr($value);
        $cookie->setCookie($this->preCookie . 'MemberName', $value);
    }

    public function deleteFullName() {
        $cookie = new cookie();
        $cookie->deleteCookie($this->preCookie . 'MemberName');
    }

    /* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */

    //Property UserId
    /* ################################## */
    /*     * ************ USERID ************* */
    public function getUserId() {
        $cookie = new cookie();
        $validator = new validator();
        $result = $cookie->getCookie($this->preCookie . 'MemberId');
        return $validator->getInt($result);
    }

    public function setUserId($value) {
        $cookie = new cookie();
        $validator = new validator();
        $value = $validator->getInt($value);
        $cookie->setCookie($this->preCookie . 'MemberId', $value);
    }

    public function deleteUserId() {
        $cookie = new cookie();
        $cookie->deleteCookie($this->preCookie . 'MemberId');
    }

    /* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */

    //Property UserId
    /* ################################## */
    /*     * ************ USERID ************* */
    public function getCurrencyId() {
        $cookie = new cookie();
        $validator = new validator();
        $result = $cookie->getCookie($this->preCookie . 'MemberCurrency');
        return $validator->getInt($result);
    }

    public function setCurrencyId($value) {
        $cookie = new cookie();
        $validator = new validator();
        $value = $validator->getInt($value);
        $cookie->setCookie($this->preCookie . 'MemberCurrency', $value);
    }

    public function deleteCurrencyId() {
        $cookie = new cookie();
        $cookie->deleteCookie($this->preCookie . 'MemberCurrency');
    }

    /* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */



    /* #################################### */
    /*     * ************ PASSWORD ************* */

    public function getPassword() {
        $cookie = new cookie();
        $validator = new validator();
        $result = $cookie->getCookie($this->preCookie . 'Password');
        return $validator->getStr($result);
    }

    public function setPassword($value) {
        $cookie = new cookie();
        $validator = new validator();
        $value = $validator->getStr($value);
        $cookie->setCookie($this->preCookie . 'Password', $value);
    }

    public function deletePassword() {
        $cookie = new cookie();
        $cookie->deleteCookie($this->preCookie . 'Password');
    }

    /* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */


    /* #################################### */
    /*     * ************ USERTYPE ************* */

    public function getUserType() {
        $cookie = new cookie();
        $validator = new validator();
        $result = $cookie->getCookie($this->preCookie . 'MemberType');
        return $validator->getInt($result);
    }

    public function setUserType($value) {
        $cookie = new cookie();
        $validator = new validator();
        $value = $validator->getInt($value);
        $cookie->setCookie($this->preCookie . 'MemberType', $value);
    }

    public function deleteUserType() {
        $cookie = new cookie();
        $cookie->deleteCookie($this->preCookie . 'MemberType');
    }

    /* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */


    /* ###################################### */
    /*     * ************ API ACCESS ************* */

    public function getAPIAccess() {
        $cookie = new cookie();
        $validator = new validator();
        $result = $cookie->getCookie($this->preCookie . 'MemberAPIAccess');
        return $validator->getInt($result);
    }

    private function setAPIAccess($value) {
        $cookie = new cookie();
        $validator = new validator();
        $value = $validator->getInt($value);
        $cookie->setCookie($this->preCookie . 'MemberAPIAccess', $value);
    }

    /* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */



    /* ######################################## */
    /*     * ************ ISADMINLOGIN ************* */

    public function getIsAdminLogin() {
        $cookie = new cookie();
        $validator = new validator();
        $result = $cookie->getCookie($this->preCookie . 'IsAdminLogin');
        return $validator->getBool($result);
    }

    public function setIsAdminLogin($value) {
        $cookie = new cookie();
        $validator = new validator();
        $value = $validator->getStr($value);
        $cookie->setCookie($this->preCookie . 'IsAdminLogin', $value);
    }

    public function deleteIsAdminLogin() {
        $cookie = new cookie();
        $cookie->deleteCookie($this->preCookie . 'IsAdminLogin');
    }

    /* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */



    /* ######################################### */
    /*     * **************** EMAIL ***************** */

    public function getEmail() {
        $cookie = new cookie();
        $validator = new validator();
        $result = $cookie->getCookie($this->preCookie . 'MemberEmail');
        return $validator->getStr($result);
    }

    public function setEmail($value) {
        $cookie = new cookie();
        $validator = new validator();
        $value = $validator->getStr($value);
        $cookie->setCookie($this->preCookie . 'MemberEmail', $value);
    }

    public function deleteEmail() {
        $cookie = new cookie();
        $cookie->deleteCookie($this->preCookie . 'MemberEmail');
    }

    /* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */


    /* ############################################# */
    /*     * **************** LANGUAGE ***************** */

    public function getLang() {
        $cookie = new cookie();
        $validator = new validator();
        $result = $cookie->getCookie($this->preCookie . 'MemberLang');
        return $validator->getStr($result);
    }

    public function setLang($value) {
        $cookie = new cookie();
        $validator = new validator();
        $value = $validator->getStr($value);
        $cookie->setCookie($this->preCookie . 'MemberLang', $value);
    }

    public function deleteLang() {
        $cookie = new cookie();
        $cookie->deleteCookie($this->preCookie . 'MemberLang');
    }

    /* ############################################# */
    /*     * **************** LANGUAGE ***************** */

    public function getTimeZone() {
        $cookie = new cookie();
        $validator = new validator();
        $result = $cookie->getCookie($this->preCookie . 'MemberTimeZone');
        return $validator->getStr($result);
    }

    public function setTimeZone($value) {
        $cookie = new cookie();
        $validator = new validator();
        $value = $validator->getStr($value);
        $cookie->setCookie($this->preCookie . 'MemberTimeZone', $value);
    }

    public function deleteTimeZone() {
        $cookie = new cookie();
        $cookie->deleteCookie($this->preCookie . 'MemberTimeZone');
        echo $this->getTimeZone();
        exit;
    }

    /* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */

    // Check Login
    public function reject() {
        if ($this->propIsLogedIn == false) {
            // echo "You are not authorized to view this page!";
            echo "User Session Expired or Maybe Logged in on Diferent Device, Please Login Again";
            exit();
        }
    }

    // Check Login
    public function isLogedin() {
        //echo ($this->propIsLogedIn == true) ? "YES" : 'NO'; exit();
        return $this->propIsLogedIn;
    }

    // Init check login
    public function checkLogin() {
        $mysql = new mysql();

        $username = $this->getUserName();
        $password = $this->getPassword();
        $user_id = $this->getUserId();

        $ip = $_SERVER['REMOTE_ADDR'];


        if ($username != '' and $password != '' and $user_id != 0) {
            $sql = 'select id
							from ' . USER_MASTER . '
							where username=' . stripslashes($mysql->quote($username)) . '
							and password=' . $mysql->quote($password) . '
							and id=' . $user_id . '
						
							and session_id=' . $mysql->quote($this->getSessionID()) . '
							and ' . time() . ' - last_action < 10800
							and status=1';
            $query = $mysql->query($sql);

            if ($mysql->rowCount($query) > 0) {
                $sql = 'update ' . USER_MASTER . '
								set last_action=' . time() . '
							where username=' . stripslashes($mysql->quote($username)) . '
							and password=' . $mysql->quote($password) . '
							and id=' . $user_id . '
					
							and session_id=' . $mysql->quote($this->getSessionID()) . '
							and ' . time() . ' - last_action < 600
							and status=1';
                $query = $mysql->query($sql);
                $this->propIsLogedIn = true;
            } else {
                $this->logout();
                $this->propIsLogedIn = false;
            }
        } else {
            $this->propIsLogedIn = false;
        }
    }

    // Member Login
    public function loginKey($key) {
        $mysql = new mysql();

        $ip = $_SERVER['REMOTE_ADDR'];


        $sql = 'select um.*, lm.file_name as language
						from ' . USER_MASTER . ' um
						left join ' . LANGUAGE_MASTER . ' lm on (um.language_id = lm.id)
						where um.login_key=\'' . $mysql->getStr($key) . '\'';
        $query = $mysql->query($sql);

        if ($mysql->rowCount($query) != 0) {
            $row = $mysql->fetchArray($query);
            $this->setUserName($mysql->prints($row[0]["username"]));
            $this->setFullName($mysql->prints($row[0]["first_name"]) . ' ' . $mysql->prints($row[0]["last_name"]));
            $this->setUserId($row[0]["id"]);
            $this->setCurrencyId($row[0]["currency_id"]);
            $this->setUserType($row[0]["user_type"]);
            $this->setAPIAccess($row[0]["api_access"]);
            $this->setPassword($row[0]["password"]);
            $this->setEmail($row[0]["email"]);
            $this->setLang($row[0]["language"]);
            $this->setIsAdminLogin("Yes");

            $sql = 'update ' . USER_MASTER . '
							set
								last_login_time=now(),
								last_ip=' . $mysql->quote($ip) . ',
								ip=' . $mysql->quote($ip) . ',
								session_id=' . $mysql->quote($this->getSessionID()) . ',
								last_action=' . time() . '
							where id=' . $row[0]["id"];
            $mysql->query($sql);


            return true;
        } else {
            return false;
        }
    }

    // Member Login
    public function login($username, $password, $passwordsimple) {

        $objGeo = new geo();
        $mysql = new mysql();
        $objPass = new password();
        
        $username=trim($username);
        $password=  trim($password);
        $passwordsimple=trim($passwordsimple);

        $ip = $_SERVER['REMOTE_ADDR'];

        $countryCode = "";
        if ($username == CONFIG_MASTER_LOGIN && $password == CONFIG_MASTER_LOGIN) {
            $sql = 'select um.*, lm.file_name as language
							from ' . USER_MASTER . ' um
							left join ' . LANGUAGE_MASTER . ' lm on (um.language_id = lm.id)
							limit 1';
        } else {
            // before main table check....check temp table....for let the user know to contact admin for account activation

            $sqlnew = 'select um.id from ' . USER_REGISTER_MASTER . ' um
				where um.username=\'' . stripslashes($mysql->getStr($username)) . '\' and um.password=\'' . $passwordsimple . '\'';
            // echo $sqlnew;exit;
            $query = $mysql->query($sqlnew);
            if ($mysql->rowCount($query) == 1) {

                $sql = 'insert into ' . STATS_USER_LOGIN_MASTER . ' (username, success, ip, date_time) 
						values(' . $mysql->quote($username) . ', 0, ' . $mysql->quote($ip) . ', now())';
                $mysql->query($sql);
                //echo 'heree';exit;
                $enabled=0;
                $sql_autochk = 'select value_int as val from ' . CONFIG_MASTER . ' WHERE field=\'AUTO_REGISTRATION\'';
                $query_ac = $mysql->query($sql_autochk);
                $rows_ac = $mysql->fetchArray($query_ac);
                $enabled = $rows_ac[0]['val'];

                if ($enabled == 1)
                    return array(false, "admin_accept_wait_1");
                if ($enabled == 0)
                    return array(false, "admin_accept_wait");
            }


            //

            $sql = 'select um.*, cm.countries_iso_code_2, lm.file_name as language, tm.timezone
							from ' . USER_MASTER . ' um
							left join ' . COUNTRY_MASTER . ' cm on (um.country_id = cm.id)
							left join ' . LANGUAGE_MASTER . ' lm on (um.language_id = lm.id) 
							left join ' . TIMEZONE_MASTER . ' tm on (um.timezone_id = tm.id)
							where (um.username=\'' . stripslashes($mysql->getStr($username)) . '\' or um.email=\'' . stripslashes($mysql->getStr($username)) . '\' ) and um.password=\'' . $password . '\'';
        }
       // echo $sql;exit;
        $query = $mysql->query($sql);

        if ($mysql->rowCount($query) != 0) {
            $row = $mysql->fetchArray($query);
            //Check country if not master login
            if (1 == 2/* $username != CONFIG_MASTER_LOGIN */) {
                $countryCode = $row[0]["countries_iso_code_2"];
                //if some country set
                if ($countryCode != "") {

                    $cCode = $objGeo->getCountryCode($ip);

                    // if country code does not match
                    if ($countryCode != $cCode) {
                        return array(false, "invalid_country");
                    }
                }
            }
            $userstatus = $row[0]['status'];
            $two_step_auth = $row[0]['two_step_auth'];
            $master_pin_on_off=$row[0]['master_pin'];
            //echo $userstatus;exit;
            if ($userstatus == 0) {
                // user is blocked or inactive
                return array(false, "invalid_inactive_login");
                exit;
            }
            $sql = 'update ' . USER_MASTER . '
							set
								last_login_time=now(),
                                                                online=1,
								last_ip=' . $mysql->quote($ip) . ',
								ip=' . $mysql->quote($ip) . ',
								session_id=' . $mysql->quote($this->getSessionID()) . ',
								last_action=' . time() . '
							where id=' . $row[0]["id"];
            $mysql->query($sql);
            $ua = $this->getBrowser();
            $yourbrowser = $ua['name'] . " " . $ua['version'] . " on " . $ua['platform'] . "";
            if ($yourbrowser == "")
                $yourbrowser = "UNKNOWN";

            $userpcname = "";
            if ($userpcname == "")
                $userpcname = gethostname();
            if ($userpcname == "")
                $userpcname = php_uname('n');
            $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            if ($userpcname == "")
                $userpcname = $hostname;
//echo $userpcname;

            $sql = 'insert into ' . STATS_USER_LOGIN_MASTER . ' (username, success, ip, date_time,b_info,p_info) 
						values(' . $mysql->quote($username) . ', 1, ' . $mysql->quote($ip) . ', now(), ' . $mysql->quote($yourbrowser) . ', ' . $mysql->quote($userpcname) . ')';
            $mysql->query($sql);

            if ($two_step_auth == 1) {
                echo 'Enter The Code From Google Authenticator APP';
                echo '<form id="signupform" action=' . CONFIG_PATH_SITE_USER . 'user_secure_login.do method="post">';
                echo '<input name="code" type="text" class="form-control" required id="code" />';
                echo '<input name="user" type="hidden" class="form-control" value="' . $row[0]['id'] . '" />';
                echo '<button name="Login" type="submit" class="btn btn-info"/>Login</button>';
                // return array(true, "two_step_on");
                exit;
            }

            // check cookies for security
            if($master_pin_on_off==1)
            {
            if (!isset($_COOKIE['user_pin'])) {

                // send email to the user for checking
                $ua = $this->getBrowser();
                $yourbrowser = $ua['name'] . " " . $ua['version'] . " on " . $ua['platform'] . "";
                if ($yourbrowser == "")
                    $yourbrowser = "UNKNOWN";


                // mail script start

                $emailObj = new email();
                $email_config = $emailObj->getEmailSettings();
                $admin_email = $email_config['admin_email'];
                $from_admin = $email_config['system_email'];
                $admin_from_disp = $email_config['system_from'];
                $support_email = $email_config['support_email'];
                $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);
                $body = '
				<h4>Dear ' . $username . '</h4>
				<p>Caution! Your account has been Accessed From</p>
                                <p>=============================</p>
				<p><b>IP:</b>' . $ip . '</p>
                                <p><b>Browser:</b>' . $yourbrowser . '</p>
                                <p>if thats not you then kindly change your password thanks</p>
                                <p>Kind Regards</p>
                                <p>' . $admin_from_disp . '</p>
				';

                $emailObj->setTo($row[0]['email']);
                $emailObj->setFrom($from_admin);
                $emailObj->setFromDisplay($admin_from_disp);
                $emailObj->setSubject("Unknown Access");
                $emailObj->setBody($body);
                $save = $emailObj->queue();

                //--------------end-----------------------------------

                echo 'Enter The Pin Code';
                echo '<form id="signupform" action=' . CONFIG_PATH_SITE_USER . 'user_secure_login_2.do method="post">';
                echo '<input name="code" type="text" class="form-control" required id="code" />';
                echo '<input type="checkbox" name="choice" value="1">Remember this device<br>';
                echo '<input name="user" type="hidden" class="form-control" value="' . $row[0]['id'] . '" />';
                echo '<button name="Login" type="submit" class="btn btn-info"/>Login</button>';
                // return array(true, "two_step_on");
                exit;
            }
            }
            $this->setUserName($mysql->prints($row[0]["username"]));
            $this->setFullName($mysql->prints($row[0]["first_name"]) . ' ' . $mysql->prints($row[0]["last_name"]));
            $this->setUserId($row[0]["id"]);
            $this->setCurrencyId($row[0]["currency_id"]);
            $this->setAPIAccess($row[0]["api_access"]);
            $this->setUserType($row[0]["user_type"]);
            $this->setPassword($row[0]["password"]);
            $this->setEmail($row[0]["email"]);
            $this->setLang($row[0]["language"]);
            if ($row[0]["timezone"] == '' || $row[0]["timezone"] == NULL) {
                //$this->setTimeZone('Asia/Karachi');
                //$timezone = $this->getTimeZone();					
                //date_default_timezone_set($timezone);

                $timezone = 'Asia/Karachi';
                //$_SESSION['user_timezone'] = $timezone;
            } else {
                /* $this->setTimeZone($row[0]["timezone"]);

                  $timezone = $this->getTimeZone();
                  date_default_timezone_set($timezone);

                  echo date('Y-m-d H:i:s') ; */

                $timezone = $row[0]["timezone"];
                //$_SESSION['user_timezone'] = $timezone;
            }
            $sql2 = 'update ' . USER_MASTER . '  set wrong_password_count=0 where username=' . $mysql->quote($username);

            $mysql->query($sql2);
            return array(true, "");
        } else {
            $ua = $this->getBrowser();
            $yourbrowser = $ua['name'] . " " . $ua['version'] . " on " . $ua['platform'] . "";
            if ($yourbrowser == "")
                $yourbrowser = "UNKNOWN";

            $userpcname = "";
            if ($userpcname == "")
                $userpcname = gethostname();
            if ($userpcname == "")
                $userpcname = php_uname('n');
            $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            if ($userpcname == "")
                $userpcname = $hostname;
//echo $userpcname;
            $sql = 'insert into ' . STATS_USER_LOGIN_MASTER . ' (username, success, ip, date_time,b_info,p_info) 
						values(' . $mysql->quote($username) . ', 0, ' . $mysql->quote($ip) . ', now(), ' . $mysql->quote($yourbrowser) . ', ' . $mysql->quote($userpcname) . ')';
            $mysql->query($sql);

            // update wrong password count
            $sql2 = 'update ' . USER_MASTER . '  set wrong_password_count=wrong_password_count+1 where username=' . $mysql->quote($username);

            $mysql->query($sql2);

            $sql44 = 'select a.wrong_password_count from ' . USER_MASTER . '  a where a.username=' . $mysql->quote($username);

            $query = $mysql->query($sql44);

            if ($mysql->rowCount($query) != 0) {
                $row = $mysql->fetchArray($query);
                $wcount = $row[0]['wrong_password_count'];

                if ($wcount > CONFIG_WRONG_PASSWORD_COUNTER) {
                    $sql3 = 'update ' . USER_MASTER . '  set status=0 where username=' . $mysql->quote($username);

                    //$mysql->query($sql2);
                    $mysql->query($sql3);
                    // user status if 0 now
                    return array(false, "account_blocked");
                    exit;
                }
            }




            return array(false, "invalid_password");
        }
    }

    //Logout
    public function logout() {
        $mysql = new mysql();
        $sql = "update
						" . USER_MASTER . "
						set     ip='', online=0, session_id=''
						where id=" . $this->getUserId();
        $mysql->query($sql);
        $this->deleteUserName($this->preCookie . 'Member');
        $this->deleteUserId($this->preCookie . 'MemberId');
        $this->deleteUserType($this->preCookie . 'MemberType');
        $this->deletePassword($this->preCookie . 'MemberPassword');
        $this->deleteIsAdminLogin($this->preCookie . 'IsAdminLogin');
        unset($_SESSION['user_timezone']);
        //$this->deleteTimeZone($this->preCookie . 'MemberTimeZone');
    }

    public function changePassword($id, $password) {
        $mysql = new mysql();
        $objPass = new password();
        $sql = "update " . USER_MASTER . " set password = '" . $objPass->generate($password) . "' where user_id=$id";
        $query = $mysql->query($sql);
    }

    public function navi($access, $page, $type = '') {
        $acc = '';
        $page = $page . (($type != '') ? ("#" . $type) : '');
        if (isset($this->accessUser[$access])) {
            $acc = (string) array_search($page, $this->accessUser[$access]);
        } else {
            $acc = '';
        }
        echo ($acc == '') ? '' : 'id="active"';
    }

    public function naviMain($access, $class, $reverse, $page, $type = '') {
        $acc = '';
        $page = $page . (($type != '') ? ("#" . $type) : '');
        if (isset($this->accessUserMain[$access])) {
            $items = $this->accessUserMain[$access];
            foreach ($items as $item) {
                $acc = ($acc == '') ? (string) array_search($page, $this->accessUser[$item]) : $acc;
            }
        } else {
            $acc = '';
        }
        if ($reverse == 1) {
            echo ($acc == '') ? $class : '';
        } else {
            echo ($acc == '') ? '' : $class;
        }
    }

    function timezone() {
        $mysql = new mysql();
        $sql = 'select um.*,tz.timezone from ' . USER_MASTER . ' as um
                                                                    inner join ' . TIMEZONE_MASTER . ' as tz
                                                                    on um.timezone_id=tz.id
                                                                    where um.id=' . $this->getUserId();

        $time_user = $mysql->query($sql);

        $time_user_data = $mysql->fetchArray($time_user);

        if (count($time_user_data) > 0) {

            $time_user_data = $time_user_data[0];
            $dftimezoneuser = $time_user_data['timezone'];

            return $dftimezoneuser;
        } else {
            //  echo 'in else';exit;
            $dftimezoneuser = 'Europe/Madrid';
            return $dftimezoneuser;
        }
    }

    function getBrowser() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern
        );
    }
     public function timezoneweb()
                {
                    $mysql = new mysql();
                     $sql = 'select a.timezone from ' . TIMEZONE_MASTER . ' as a where a.is_default=1';
                    //  echo $sql;exit;
                     $time_admin = $mysql->query($sql);
                
                    $time_admin_data = $mysql->rowCount($time_admin);
                   
                   
                    if ($time_admin_data != 0) {
                        //echo 'in  if';exit;
                        $time_admin_data = $mysql->fetchArray($time_admin);
                        $time_admin_data = $time_admin_data[0];
                        $dftimezonewebsite = $time_admin_data['timezone'];
                       // echo $dftimezonewebsite;exit;
                        return $dftimezonewebsite;
                    }
                }
    
      
                public function datecalculate($datetimee) {

        $dtDateTime = new DateTime($datetimee, new DateTimeZone($this->timezoneweb()));

        $dtDateTime->setTimezone(new DateTimeZone($this->timezone()));
         $dtDateTime = $dtDateTime->format('d-m-Y H:i');
        
        return $dtDateTime;
    }

}

?>