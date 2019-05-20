<?php
	class supplier{

		var $preCookie = "gsmSupplier";
		var $propIsLogedIn = false;
		
		//Property UserName
		public function getUserName()
		{
			$cookie = new cookie();
			return $cookie->getCookie($this->preCookie . 'Member');
		}
		public function setUserName($value)
		{
			$cookie = new cookie();
			$cookie->setCookie($this->preCookie . 'Member',$value);
		}
		
		//Property UserName
		public function getFullName()
		{
			$cookie = new cookie();
			return $cookie->getCookie($this->preCookie . 'MemberName');
		}
		public function setFullName($value)
		{
			$cookie = new cookie();
			$cookie->setCookie($this->preCookie . 'MemberName',$value);
		}
		
		//Property UserId
		public function getUserId()
		{
			$cookie = new cookie();
			return $cookie->getCookie($this->preCookie . 'MemberId');
		}
		public function setUserId($value)
		{
			$cookie = new cookie();
			$cookie->setCookie($this->preCookie . 'MemberId',$value);
		}
		
		//Property Password
		public function getPassword()
		{
			$cookie = new cookie();
			return $cookie->getCookie($this->preCookie . 'MemberPass');
		}
		public function setPassword($value)
		{
			$cookie = new cookie();
			$cookie->setCookie($this->preCookie . 'MemberPass',$value);
		}
		
		
		//Property UserType
		public function getUserType()
		{
			$cookie = new cookie();
			return $cookie->getCookie($this->preCookie . 'MemberType');
		}
		public function setUserType($value)
		{
			$cookie = new cookie();
			$cookie->setCookie($this->preCookie . 'MemberType',$value);
		}
		
		//Property UserType
		public function getIsAdminLogin()
		{
			$cookie = new cookie();
			return $cookie->getCookie($this->preCookie . 'IsAdminLogin');
		}
		public function setIsAdminLogin($value)
		{
			$cookie = new cookie();
			$cookie->setCookie($this->preCookie . 'IsAdminLogin',$value);
		}
		
		//Property Email
		public function getEmail()
		{
			$cookie = new cookie();
			return $cookie->getCookie($this->preCookie . 'MemberEmail');
		}
		public function setEmail($value)
		{
			$cookie = new cookie();
			$cookie->setCookie($this->preCookie . 'MemberEmail',$value);
		}
		
		
		// Check Login
		public function reject()
		{
			if($this->propIsLogedIn == false)
			{
				echo "You are not authorized to view this page!";
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
			
			
			if($username != '' and $password != '' and $user_id != 0)
			{
				$sql= 'select id
							from ' . SUPPLIER_MASTER . '
							where username=' . stripslashes($mysql->quote($username)) . '
							and password=' . $mysql->quote($password) . '
							and id=' . $user_id . '
							and status=1';
				$query = $mysql->query($sql);


				if($mysql->rowCount($query) > 0)
				{
					$this->propIsLogedIn = true;
				}
				else
				{
					$this->logout();
					$this->propIsLogedIn = false;
				}
			}
			else
			{
				$this->propIsLogedIn = false;
			}
			
		}
		// Member Login
		public function loginKey($key)
		{
			$mysql = new mysql();
			
			$sql= "select * from " . SUPPLIER_MASTER . " where login_key='" . $mysql->getStr($key) . "'";
			$query = $mysql->query($sql);

			if($mysql->rowCount($query) != 0)
			{
				$row = $mysql->fetchArray($query);
				$this->setUserName($row[0]["username"]);
				$this->setFullName($row[0]["first_name"] . ' ' . $row[0]["last_name"]);
				$this->setUserId($row[0]["id"]);
				$this->setUserType($row[0]["user_type"]);
				$this->setPassword($row[0]["password"]);
				$this->setIsAdminLogin("Yes");
				return true;
			}
			else
			{
				return false;
			}
		}


		// Member Login
		public function login($username,$password)
		{
			$mysql = new mysql();
			$objPass = new password();
			
			$sql= "select * from " . SUPPLIER_MASTER . " where username='" . stripslashes($mysql->getStr($username)) . "' and password='" . $password . "' and status=1";
		
			$query = $mysql->query($sql);
			
			$ip = $_SERVER['REMOTE_ADDR'];

			if($mysql->rowCount($query) != 0)
			{
				$row = $mysql->fetchArray($query);

				$sql = 'update ' . SUPPLIER_MASTER . ' set last_login_time=current_login_time, last_ip=current_ip where id=' . $row[0]["id"];
				$mysql->query($sql);
				
				$sql = 'update ' . SUPPLIER_MASTER . ' set current_login_time=now(), current_ip=' . $mysql->quote($ip) . ' where id=' . $row[0]["id"];
				$mysql->query($sql);
				
				$sql = 'insert into ' . STATS_USER_LOGIN_MASTER . ' (username, success, ip, date_time) 
						values(' . $mysql->quote($username) . ', 1, ' . $mysql->quote($ip) . ', now())';
				$mysql->query($sql);
					

				$this->setUserName($row[0]["username"]);
				$this->setFullName($row[0]["first_name"] . ' ' . $row[0]["last_name"]);
				$this->setUserId($row[0]["id"]);
				$this->setUserType($row[0]["user_type"]);
				$this->setPassword($row[0]["password"]);
				return true;
			}
			else
			{
				$sql = 'insert into ' . STATS_USER_LOGIN_MASTER . ' (username, success, ip, date_time) 
						values(' . $mysql->quote($username) . ', 0, ' . $mysql->quote($ip) . ', now())';
				$mysql->query($sql);

				return false;
			}
		}


		//Logout
		public function logout()
		{
			$cookie = new cookie();
			$cookie->deleteCookie($this->preCookie . 'Member');
			$cookie->deleteCookie($this->preCookie . 'MemberId');
			$cookie->deleteCookie($this->preCookie . 'MemberType');
			$cookie->deleteCookie($this->preCookie . 'MemberPassword');
			$cookie->deleteCookie($this->preCookie . 'IsAdminLogin');
		}
		
		public function changePassword($id, $password)
		{
			$mysql = new mysql();
			$objPass = new password();
			$sql = "update " . USER_MASTER . " set password = '" . $objPass->generate($password) . "' where user_id=$id";
			$query = $mysql->query($sql);
		}
		
	}
	
	
	
?>