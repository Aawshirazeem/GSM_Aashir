<?php
class mysql2
{
	var $_mysqli;
	public function __construct()
	{
		global $mysql_connection2;
		$this->_mysqli = $mysql_connection2;
		//print_r($this->_mysqli);
		//$this->_mysqli = new mysqli(CONFIG_DB_HOST, CONFIG_DB_USER, CONFIG_DB_PASS, CONFIG_DB_NAME);
		//error_log(time() . "\n", 3, CONFIG_PATH_SITE_ABSOLUTE . "/query.log");
		//if ($this->_mysqli->connect_errno) {
		//	echo "Failed to connect to MySQL: (" . $this->_mysqli->connect_errno . ") " . $this->_mysqli->connect_error;
		//}
	}
	
	public function query($sql)
	{
		//error_log($sql . " \n \n *********************************************** \n \n", 3, CONFIG_PATH_SITE_ABSOLUTE . "/query.log");
		if($this->_mysqli->more_results())
		{
			while($this->_mysqli->next_result())
			{
				if($l_result = $this->_mysqli->store_result())
				{
					$l_result->free();
				}
			}
		}
		$query = $this->_mysqli->query($sql, MYSQLI_STORE_RESULT) or die($this->failure_handler($sql, $this->_mysqli->error));
		return $query;
		
	}
	
	public function multi_query($sql)
	{
		//error_log($sql . " \n \n *********************************************** \n \n", 3, CONFIG_PATH_SITE_ABSOLUTE . "/query.log");
		if($this->_mysqli->more_results())
		{
			while($this->_mysqli->next_result())
			{
				if($l_result = $this->_mysqli->store_result())
				{
					$l_result->free();
				}
			}
		}
		$this->_mysqli->multi_query($sql) or die($this->failure_handler($sql, $this->_mysqli->error));
	}
	
	
	private function failure_handler($query, $error)
	{
		$trackUser = "";
		
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$member = new member();
		if($member->isLogedIn())
		{
			$trackUser .= 'User:' . $member->getUsername() . ' : ' . $member->getUserId() . "\n";
		}
		
		$admin = new admin();
		if($admin->isLogedIn())
		{
			$trackUser .= 'Admin:' . $admin->getUsername() . ' : ' . $admin->getUserId() . "\n";
		}
		
		$trackUser .=  'IP:' . $ip;
		$msg = htmlspecialchars("Failed Query: {$query} \n\n SQL Error: {$error}") . "\n********************\n\n";
		$msg = $trackUser . "\n" . $msg;
		error_log($msg, 3, CONFIG_PATH_SITE_ABSOLUTE . "/sql_error.log");
		if (defined("debug"))
		{
			return $msg;
		}
		else
		{
			return "Requested page is temporarily unavailable, please try again later.";
		}
	}
	
	public function fetchAssoc($query)
	{
		$Row= $query->fetch_assoc();
		return $Row;
	}
	
	public function insert_id()
	{
		$id= $this->_mysqli->insert_id;
		return $id;
	}
	
	function fetchArray($query)
	{
		$result_array = array();
		for ($count=0; $row = $this->fetchAssoc($query) ; $count++)
		{
			$result_array[$count] = $row;
		}
		return $result_array;
	}
		
	function fetchResult($query) {
		return mysql_result($query);
	}

	public function rowCount($query)
	{
		$count = $query->num_rows;
		return $count;
	}
	
	public function rowAffected($query)
	{
		$count = $query->affected_rows;
		return $count;
	}
	
	

	public function optimize()
	{
		$alltables = mysql_query("SHOW TABLES");
		while ($table = mysql_fetch_assoc($alltables))
		{
		   foreach ($table as $db => $tablename)
		   {
			   mysql_query("OPTIMIZE TABLE `{$tablename}`") or die(mysql_error());
		   }
		}
	}
	
	public function getDateTime()
	{
		return "'" . date("Y-m-d H:i:s") . "'";
	}
	
	public function getStr($str)
	{
		if(get_magic_quotes_gpc())
		{
			$str = stripslashes(trim($str));
		}
		//$str = addcslashes(mysql_real_escape_string($str), "%_");
		//$str = $this->_mysqli->real_escape_string($str);
		return $str;
	}
	
	public function getInt($val)
	{
		return (int)((isset($val)) ? $val : 0);
	}
	
	public function getFloat($val)
	{
		return (float)((isset($val)) ? $val : 0);
	}
	
	public function quote($str)
	{
		return '\'' . $this->getStr($str) . '\'';
	}
	
	public function quoteLike($str)
	{
		return '\'%' . $this->getStr($str) . '%\'';
	}
	
	public function prints($str)
	{
		return('' . stripslashes($str));
	}
}
?>
