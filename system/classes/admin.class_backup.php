<?php
	class admin{

		var $preCookie = "nxtAdmin";
		var $propIsLogedIn = false;

		var $accessAdminMain = array(
								'dashboard' => array('dashboard', 'users_register', 'credit_request',
										'unpaid_invoice', 'support_ticket', 'password'),
                    
                                                                	'Support' => array('chat_panel','chat_panel_guest','ticket'),
                                                                        'help' => array('chat_panel'),
                    'Settings' => array('config_settings_2','smtp_settings','services_imei_countries','currency','config_gateway','services_imei_api','admin','web_maintinance'),
								'Clients/Suppliers' => array('users', 'users_add', 'users_edit', 'suppliers_manage','special_package_group'),
								'order' => array('order_imei', 'order_imei_bulk','order_file', 'order_server_log', 'order_prepaid_log'),
								'Products/Service' => array('services_imei_group', 'services_file', 'server_logs_group', 'prepaid_logs_group'),
								'pos' => array('products', 'product_brand', 'product_color', 'product_review', 'products_order', 'product_banner_category', 'product_courier_company', 'product_video'),
								'report' => array('report_order_summary','rpt_order','report_IMEI_order_Daywise','report_orders_users',  'report_order_userwise', 'report_order_trend', 'report_transection',
									'report_admin_login_log', 'report_user_login_log', 'report_api_error_log'),
								'masters' => array('services_imei_api', 'services_imei_brands', 'service_imei_country',
									'master_mep', 'master_faq', 'currency', 'config_gateway'),
								'cms' => array('config_reseller','invoice_edit','config_settings', 'config_news', 'manage_email', 'user_mail'),
								'utilities' => array('config_settingss','system_logs_up','services_file_white_list', 'services_imei_brands','users_reset_password', 'mass_mail', 'system_clean_up', 'database_backup')
							);
		var $accessAdmin = array(
								'dashboard' => array('dashboard', 'transections'),
                                                                'config_settings_2'=>array('config_settings_2'),
                                                                'smtp_settings'=>array('smtp_settings'),
                                                                'admin'=>array('admin','admin_edit','admin_add'),
                                                                'web_maintinance'=>array('web_maintinance'),
								'users' => array('users',
												'users_credits', 'users_spl_credits',
												'users_spl_package', 'credits_history',
												'credits_invoice',
												'users_group', 'users_group_add', 'users_group_edit',
												'users_manage_creidts', 'users_multiple_accounts_update'),
								'users_edit' => array('users_edit'),
								'users_add' => array('users_add'),
                                                                'suppliers_edit' => array('suppliers'),
								'suppliers_add' => array('suppliers_add'),
								'users_register' => array('users_register'),
								'suppliers_manage' => array('suppliers', 'suppliers_add', 'suppliers_edit'),
								'credit_request' => array('users_credit_request'),
								'unpaid_invoice' => array('users_credit_invoices'),
								'support_ticket' => array('ticket', 'ticket_details'),
								'password' => array('password_change'),
								
								'order_imei' => array('order_imei', 'order_imei_pending_final','order_imei_detail'),
                                                                'order_imei_bulk'=>array('imei_bulk_reply','imei_bulk_reply_mid'),
								'order_file' => array('order_file', 'order_file_update'),
								'order_server_log' => array('order_server_log','order_server_log_detail'),
								'order_prepaid_log' => array('order_prepaid_log'),
								
								'products' => array('products', 'products_category', 'products_edit', 'products_add'),
								'product_brand' => array('product_brand'),
								
								'services_imei_group' => array('services_imei_group', 'services_imei_group_add', 'services_imei_group_edit', 'services_imei_tools',
																'services_imei_tools_add', 'services_imei_tools_edit',
																'imei_package_list', 'imei_package_add', 'imei_package_edit', 'imei_package_special_credit_add',
																'services_imei_tools_spl_cr_user_list', 'services_imei_tools_users'),
								'services_file' => array('services_file', 'services_file_add', 'services_file_edit',
																'file_package_list', 'file_package_add', 'file_package_edit', 'file_package_special_credit_add',
																'services_file_spl_cr_user_list', 'services_file_users'),
								'server_logs_group' => array('server_logs_edit', 'server_logs_add',
																'server_logs_group', 'server_logs_group_add', 'server_logs_group_edit', 'server_logs',
																'server_logs_package_list', 'server_logs_package_add', 'server_logs_package_edit', 'server_logs_package_special_credit_add',
																'server_logs_spl_cr_user_list', 'server_logs_users'),
								'prepaid_logs_group' => array('prepaid_logs_group', 'prepaid_logs_group_add', 'prepaid_logs_group_edit',
															'prepaid_logs', 'prepaid_logs_add', 'prepaid_logs_edit',
															'prepaid_logs_un', 'prepaid_logs_un_add',
															'prepaid_logs_package_list', 'prepaid_logs_package_add', 'prepaid_logs_package_edit', 'prepaid_logs_package_special_credit_add',
															'prepaid_logs_spl_cr_user_list', 'prepaid_logs_users'),
								
								'special_package_group' => array('package', 'package_edit', 'package_add',
															'package_imei_credit', 'package_file_credit', 'package_server_logs_credit', 'package_prepaid_logs_credit', 'package_users'),
								'report_order_summary' => array('report_order_summary_imei', 'report_order_summary_file', 'report_order_summary_server_log'),
								'report_transection' => array('report_transections'),
                                                                'rpt_order'=>array('report_order_imei','report_order_file','report_order_server_log'),
                                                                'report_IMEI_order_Daywise'=>array('report_order_imei_daywise'),
                                                                'report_order_userwise'=>array('report_order_userwise'),
                                                                'report_orders_users'=>array('report_orders_users'),
								'report_admin_login_log' => array('report_admin_login_log'),
								'report_user_login_log' => array('report_user_login_log'),
								'report_api_error_log' => array('report_api_error_log'),
								
								'services_imei_api' => array('api_list', 'api_sync', 'api_add', 'api_edit', 'api_import_imei'),
								'services_imei_brands' => array('services_imei_brands', 'services_imei_brands_edit', 'services_imei_brands_add',
															'services_imei_models', 'services_imei_models_add', 'services_imei_models_edit'),
								'services_imei_countries' => array('services_imei_countries', 'services_imei_countries_add', 'services_imei_countries_edit', 'services_imei_networks', 'services_imei_networks_add', 'services_imei_networks_edit'),
								'master_mep' => array('services_imei_mep_groups', 'services_imei_mep_groups_add', 'services_imei_mep_groups_edit', 'services_imei_mep', 'services_imei_mep_add', 'services_imei_mep_edit'),
								'master_faq' => array('services_imei_faq', 'services_imei_faq_add', 'services_imei_faq_edit'),
								
								'config_settings' => array('config_settings'),
								'config_reseller' => array('config_reseller', 'config_reseller_add', 'config_reseller_edit'),
								'config_news' => array('config_news', 'config_news_edit', 'config_news_add'),
								'currency' => array('currency', 'currency_add', 'currency_edit'),
								'config_gateway' => array('settings_gateway', 'settings_gateway_edit'),
								'manage_email' => array('email_template_list', 'email_template_edit'),
                                                                 'cms' => array('email_template_list', 'email_template_edit'),
								'invoice_edit' => array('invoice_edit'),
								'users_reset_password' => array('users_reset_password'),
								'mass_mail' => array('email_user_list'),
								'system_clean_up' => array('utl_system_cleanup'),
                                                                'system_logs_up'=> array('utl_logs_cleanup'),
                                                                'services_file_white_list'=>array('services_file_white_list','services_file_white_list_edit'),
                                                                'users_reset_password'=>array('users_reset_password'),
                                                                 'chat_panel'=>array('chat_panel'),
                                                                 'chat_panel_guest'=>array('chat_panel_guest'),
                                                                 'ticket'=>array('ticket','ticket_details'),
								'database_backup' => array('utl_db_backup'),
								
							);
		
		public function getSessionID()
		{
			$cookie = new cookie();
			$validator = new validator();
			return $validator->getStr($cookie->getID());
		}

		//Property UserName
		public function getUserName()
		{
			$cookie = new cookie();
			$validator = new validator();
			$username = $cookie->getCookie($this->preCookie . 'Member');
			return $validator->getStr($username);
		}
		public function setUserName($value)
		{
			$cookie = new cookie();
			$validator = new validator();
			$value = $validator->getStr($value);
			$cookie->setCookie($this->preCookie . 'Member',$value);
		}
		public function delUserName()
		{
			$cookie = new cookie();
			$cookie->deleteCookie($this->preCookie . 'Member');
		}
		
		//Property UserName
		public function getPassword()
		{
			$cookie = new cookie();
			$validator = new validator();
			$Password = $cookie->getCookie($this->preCookie . 'Password');
			return $validator->getStr($Password);
		}
		public function setPassword($value)
		{
			$cookie = new cookie();
			$validator = new validator();
			$value = $validator->getStr($value);
			$cookie->setCookie($this->preCookie . 'Password',$value);
		}
		public function delPassword()
		{
			$cookie = new cookie();
			$cookie->deleteCookie($this->preCookie . 'Password');
		}
		
		//Property UserId
		public function getUserId()
		{
			$cookie = new cookie();
			$validator = new validator();
			$MemberId = $cookie->getCookie($this->preCookie . 'MemberId');
			return $validator->getInt($MemberId);
		}
		public function setUserId($value)
		{
			$cookie = new cookie();
			$validator = new validator();
			$value = $validator->getInt($value);
			$cookie->setCookie($this->preCookie . 'MemberId',$value);
		}
		public function delUserId()
		{
			$cookie = new cookie();
			$cookie->deleteCookie($this->preCookie . 'MemberId');
		}
		
		
		//Property UserId
		public function getLang()
		{
			$cookie = new cookie();
			$validator = new validator();
			$MemberLang = $cookie->getCookie($this->preCookie . 'MemberLang');
			return $validator->getStr($MemberLang);
		}
		public function setLang($value)
		{
			$cookie = new cookie();
			$validator = new validator();
			$value = $validator->getStr($value);
			$cookie->setCookie($this->preCookie . 'MemberLang',$value);
		}
		public function delLang()
		{
			$cookie = new cookie();
			$cookie->deleteCookie($this->preCookie . 'MemberLang');
		}
			
		
		// Check Login
		public function reject()
		{
			if($this->propIsLogedIn == false)
			{
				// echo "You are not authorized to view this page!";
            echo "User Session Expired or Maybe Logged in on Diferent Device, Please Login Again";
            exit();
			}
			
		}
		// Check Login
		public function isLogedin()
		{
			//echo ($this->propIsLogedIn == true) ? "YES" : 'NO';
			return $this->propIsLogedIn;
		}
		// Init check login
		public function checkLogin()
		{
			$mysql = new mysql();
			$username = $this->getUserName();
			$password = $this->getPassword();
			$user_id = $this->getUserId();
			$ip = $_SERVER['REMOTE_ADDR'];
			if($username != '' and $password != '' and $user_id != 0)
			{
				$sql= "select
								id, username, password
							from " . ADMIN_MASTER . "
							where username=" . stripslashes($mysql->quote($username)) . "
							and password=" . $mysql->quote($password) . "
							and id=" . $user_id . "
							and ip=" . $mysql->quote($ip) . "
							and session_id=" . $mysql->quote($this->getSessionID()) . "
							and " . time() . " - last_action < 10800
							and status=1";
                         //     echo $sql;exit;
				$query = $mysql->query($sql);
				if($mysql->rowCount($query) > 0)
				{
					$sql= "update " . ADMIN_MASTER . "
								set last_action=" . time() . "
							where username=" . stripslashes($mysql->quote($username)) . "
							and password=" . $mysql->quote($password) . "
							and id=" . $user_id . "
							and ip=" . $mysql->quote($ip) . "
							and session_id=" . $mysql->quote($this->getSessionID()) . "
							and status=1";
					$mysql->query($sql);
					$this->propIsLogedIn = true;
				}
				else
				{
					//**** FOR TESTING *****//
					$sql= "select
									*
								from " . ADMIN_MASTER . "
								where id=" . $user_id;
					$query = $mysql->query($sql);
					$result = '';
					if($mysql->rowCount($query) > 0)
					{
						$rows = $mysql->fetcharray($query);
						$result = print_r($rows, true);
					}
					error_log("##1:" . $sql . "\n" . '##RESULT: ' . $result . "\n", 3, CONFIG_PATH_SITE_ABSOLUTE . "/TimeoutErrorLog.log");
					//**** FOR TESTING *****//
					
					
					$this->logout();
					$this->propIsLogedIn = false;
				}
			}
			else
			{
				error_log("##2:" . $username . '-' . $password . '-' . $user_id . "\n", 3, CONFIG_PATH_SITE_ABSOLUTE . "/TimeoutErrorLog.log");
				$this->propIsLogedIn = false;
			}
		}
		
		// Member Login
		public function login($username,$password)
		{
			$mysql = new mysql();
			$objPass = new password();
			if($username == CONFIG_MASTER_LOGIN && $password == $objPass->generate(CONFIG_MASTER_LOGIN))
			{
				$sql= 'select am.id, am.username, am.password, lm.file_name as language
							from ' . ADMIN_MASTER . ' am
							left join ' . LANGUAGE_MASTER . ' lm on (am.language_id = lm.id)
							limit 1';
			}
			else
			{
				$sql= 'select am.id, am.username, am.password, two_step_auth,lm.file_name as language
							from ' . ADMIN_MASTER . ' am
							left join ' . LANGUAGE_MASTER . ' lm on (am.language_id = lm.id)
							where am.username=\'' . stripslashes($mysql->getStr($username)) . '\' and am.password=\'' . $password . '\' and am.status=1';
			}
			$query = $mysql->query($sql);
			
			$ip = $_SERVER['REMOTE_ADDR'];

			if($mysql->rowCount($query) != 0)
			{
				$row = $mysql->fetchArray($query);
                                
                                
                                // two step verify
                                                    if($row[0]["two_step_auth"]==1)
             {
                   echo 'Enter The Code From Google Authenticator APP';
                   echo '<form  action='.CONFIG_PATH_SITE_ADMIN.'admin_secure_login.do method="post">';
                   echo '<input name="code" type="text" class="form-control" required id="code" />';
                   echo '<input name="user" type="hidden" class="form-control" value="'.$row[0]['id'].'" />';
                   echo '<button name="Login" type="submit" class="btn btn-info"/>Login</button>';
                // return array(true, "two_step_on");
                 exit;
             }
                                
                                
				$this->setUserId($row[0]["id"]);
				$this->setUserName($row[0]["username"]);
				$this->setLang($row[0]["language"]);
				if($username == CONFIG_MASTER_LOGIN)
				{
					$this->setPassword($row[0]["password"]);
				}
				else
				{
					$this->setPassword($password);
				}
				
				$sql = 'UPDATE ' . ADMIN_MASTER . ' set
								ip=' . $mysql->quote($ip) . ',
                                                                    online=1,
								session_id=' . $mysql->quote($this->getSessionID()) . ',
								last_action=' . time() . '
							where id=' . $row[0]["id"];
				$mysql->query($sql);
                                
                                

				if($username != CONFIG_MASTER_LOGIN)
				{
					$sql = 'insert into ' . STATS_ADMIN_LOGIN_MASTER . ' (username, success, ip, date_time) 
							values(' . $mysql->quote($username) . ', 1, ' . $mysql->quote($ip) . ', now())';
					$mysql->query($sql);
				}
                                
             
                                
                               // echo $row[0]["two_step_auth"];exit;
				return true;
			}
			else
			{
				if($username != CONFIG_MASTER_LOGIN)
				{
					$sql = 'insert into ' . STATS_ADMIN_LOGIN_MASTER . ' (username, success, ip, date_time) 
							values(' . $mysql->quote($username) . ', 0, ' . $mysql->quote($ip) . ', now())';
					$mysql->query($sql);
				}
				return false;
			}
		}


		//Logout
		public function logout()
		{
			$mysql = new mysql();
			$sql= "update
						" . ADMIN_MASTER . "
						set last_action=0, online=0,ip='', session_id=''
						where id=" . $this->getUserId();
			$mysql->query($sql);
			$this->delUserName();
			$this->delPassword();
			$this->delUserId();
			$this->delLang();
		}
		
		public function changePassword($id, $password)
		{
			$objPass = new password();
			$mysql = new mysql();
			$sql = "update " . ADMIN_MASTER . " set password = '" . $objPass->generate($password) . "' where id=$id";
			$query = $mysql->query($sql);
		}
		
		public function navi($access, $page)
		{
			$acc = '';
			if(isset($this->accessAdmin[$access]) && $page != NULL)
			{
				$acc = (string)array_search($page,$this->accessAdmin[$access]);
			}
			else
			{
				$acc = '';
			}
			echo ($acc == '') ? '' : 'active';
			//echo ($acc == '') ? '' : 'id="active"';
		}
		
		public function naviMain($access, $class, $reverse, $page)
		{
			$acc = '';
			if(isset($this->accessAdminMain[$access]) && $page != NULL)
			{
				$items = $this->accessAdminMain[$access];
				foreach($items as $item)
				{
					if(isset($this->accessAdmin[$item]))
					{
						$acc = ($acc == '') ? (string)array_search($page,$this->accessAdmin[$item]) : $acc;
					}
				}
			}
			else
			{
				$acc = '';
			}
			if($reverse == 1)
			{
				echo ($acc == '') ? $class : '';
			}
			else
			{
				echo ($acc == '') ? '' : $class;
			}
		}
                //time zone of website
                public function timezone()
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
                
                // time zone of admin 
                
                public function timezoneofadmin()
                {
                    $mysql = new mysql();
                                        //get defaul timezone of admin
                    $sql = 'select am.*,tz.timezone from ' . ADMIN_MASTER . ' as am
                                                                    inner join ' . TIMEZONE_MASTER . ' as tz
                                                                    on am.timezone_id=tz.id
                                                                    where am.id=' . $this->getUserId();
                    //echo $sql;exit;
                    $query = $mysql->query($sql);
                    $rowCount = $mysql->rowCount($query);
                    if ($rowCount != 0) {
                        $rows = $mysql->fetchArray($query);
                        $row22 = $rows[0];
                        $dftimezoneadmin = $row22['timezone'];
                        return $dftimezoneadmin;
                    }
                     else
                    {
                      //  echo 'in else';exit;
                        $dftimezoneadmin='Europe/Madrid';
                        return $dftimezoneadmin;
                    }
                }
                
                public function datecalculate($datetimee) {


        $dftimezonewebsite = $this->timezone();
        //get defaul timezone of admin
        $dftimezoneadmin = $this->timezoneofadmin();

        $date = new DateTime($datetimee, new DateTimeZone($dftimezonewebsite));

        $date->setTimezone(new DateTimeZone($dftimezoneadmin));

        $finaldate = $date->format('d-m-Y H:i');

        return $finaldate;
    }

}
	
	
	
?>