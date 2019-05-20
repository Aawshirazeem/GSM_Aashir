<?php
	class logs{	
		public function member($username,$password, $success)
		{
			$mysql = new mysql();
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$sql= 'insert into ' . LOG_USER_LOGIN_MASTER . '(username, success, ip, date_time) values(
				' . $mysql->quote($username) . ',
				' . $mysql->quote($password) . ',
				' . $mysql->quote($ip) . ',
				now()
				)';
			$query = $mysql->query($sql);
		}
	}	
?>