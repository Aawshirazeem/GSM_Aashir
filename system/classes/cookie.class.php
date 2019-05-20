<?php
	class cookie
	{
		var $useCookie = true;
		public function getID()
		{
			//$this->deleteCookie("useCookies");
			if(isset($_COOKIE["useCookies"]) or $this->useCookie == true)
			{
				return '';
			}
			else
			{
				return session_id();
			}
		}
		public function useCookie()
		{
			//$this->useCookie = true;
			setcookie('useCookies','1', time()+60*60*6, CONFIG_PATH_SITE);
			//$this->setCookie("useCookies", "1");
		}
		
		
		public function useSession()
		{
			//$this->useCookie = false;
			$this->deleteCookie("useCookies");
		}
		
		public function setCookie($name,$value)
		{
			setcookie($name,$value, time()+60*60*6, CONFIG_PATH_SITE);
			/*if(isset($_COOKIE["useCookies"]) or $this->useCookie == true)
			{
				setcookie($name,$value, time()+60*60*3, CONFIG_PATH_SITE);
			}
			else
			{
				$_SESSION[$name] = $value;
			}*/
		}
		
		public function getCookie($name)
		{
			if(isset($_COOKIE["useCookies"]) or $this->useCookie == true)
			{
				//echo "C";
				if(isset($_COOKIE[$name]))
				{
					return $_COOKIE[$name];
				}
			}
			else
			{
				//echo "S";
				if(isset($_SESSION[$name]))
				{
					return $_SESSION[$name];
				}
			}
		}
		public function deleteCookie($name)
		{
			setcookie('useCookies','', time()+60*60*6, CONFIG_PATH_SITE);
			setcookie($name, "", time()+60*60*6, CONFIG_PATH_SITE);
			unset($_SESSION[$name]);
		}
	}
?>