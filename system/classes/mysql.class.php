<?php
class mysql
{
	var $_mysqli;
	public function __construct()
	{
		global $mysql_connection;
		$this->_mysqli = $mysql_connection;
		//print_r($this->_mysqli);
		//$this->_mysqli = new mysqli(CONFIG_DB_HOST, CONFIG_DB_USER, CONFIG_DB_PASS, CONFIG_DB_NAME);
		//error_log(time() . "\n", 3, CONFIG_PATH_SITE_ABSOLUTE . "/query.log");
		//if ($this->_mysqli->connect_errno) {
		//	echo "Failed to connect to MySQL: (" . $this->_mysqli->connect_errno . ") " . $this->_mysqli->connect_error;
		//}
	}
	
	public function query($sql)
	{
		//echo '<div class="alert alert-danger">' . $sql . '</div>';
		//error_log($sql . " \n \n *********************************************** \n \n", 3, CONFIG_PATH_SITE_ABSOLUTE . "/query.log");
		if($this->_mysqli->more_results())
		{
			while(@$this->_mysqli->next_result())
			{
				if($l_result = $this->_mysqli->store_result())
				{
					$l_result->free();
				}
			}
		}
                 $this->_mysqli->query("SET SQL_BIG_SELECTS=1", MYSQLI_STORE_RESULT);
		$query = $this->_mysqli->query($sql, MYSQLI_STORE_RESULT) or die('<div class="alert alert-danger"><h3>SQL ERROR</h3>' . $this->failure_handler($sql, $this->_mysqli->error) . '</div>');
		return $query;
		
	}
	
	public function multi_query($sql)
	{
		//error_log($sql . " \n \n *********************************************** \n \n", 3, CONFIG_PATH_SITE_ABSOLUTE . "/query.log");
		if($this->_mysqli->more_results())
		{
			while(@$this->_mysqli->next_result())
			{
				if($l_result = $this->_mysqli->store_result())
				{
					$l_result->free();
				}
			}
		}
                 $this->_mysqli->query("SET SQL_BIG_SELECTS=1", MYSQLI_STORE_RESULT);
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
		error_log($msg, 3, CONFIG_PATH_LOGS_ABSOLUTE . "/sql_error.log");
		if (defined("debug"))
		{
			return $msg;
		}
		else
		{
			return '<h2 class="text-danger">Requested page is temporarily unavailable, please try again later.</h2>';
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
	
	
	public function getResult($sql, $test = false, $limit = 0, $offset = 0, $url = '', $params = array()){
		$result = array(
			'RESULT' => array(),
			'COUNT' => 0,
			'PAGINATION' => ''
		);

		if($limit > 0){
			$paging = new paging();
			$qLimit = " limit $offset,$limit";
			$query = $this->query($sql . $qLimit);
			$extraURL = '&';
			$result['PAGINATION'] = $paging->recordsetNav($sql, $url ,$offset,$limit,$extraURL);
		}
		else{
			$query = $this->query($sql);
		}
		

		$rows = array();
		$rowCount = $this->rowCount($query);
		if($rowCount){
			$rows = $this->fetchArray($query);
		}
		
		
		$result['RESULT'] = $rows;
		$result['COUNT'] = $rowCount;
		
		if($test){
			echo '<div class="alert alert-warning"><h3>QUERY</h2>' . $sql .'</div>';
			echo '<div class="alert alert-warning"><h3>PARAMS FORMAT</h2>SQL - TEST(true/false) - LIMIT(0), OFFSET(0) - URL("") - PARAMS(array)<br /><span class="label label-danger">To enable pagination pur some value for LIMIT param</span></div>';
			echo '<div class="alert alert-warning"><h3>PARAMS VALUES</h2>
									<b>$sql</b>: ' . $sql .'<hr />
									<b>$limit</b>: ' . $limit .'<hr />
									<b>$offset</b>: ' . $offset .'<hr />
									<b>$url</b>: ' . $url .'<hr />
									<b>$param</b>: <pre class="alert alert-info">' . print_r($params, true) .'</pre>
								</div>';
			echo '<div class="alert alert-warning"><h3>OUTPUT FORMAT</h2>RESULT - COUNT(0) - PAGINATION("")</div>';
			echo '<div class="alert alert-warning"><h3>OUTPUT VALUES</h2><b>$result</b>: <pre class="alert alert-info">' . print_r($result, true) .'</pre></div>';
			exit();
		}

		return $result;
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
	
	public function parseDate($format, $date){
		//$tempDate = DateTime::createFromFormat('m-d-Y', $today);
		$tempDate = DateTime::createFromFormat($format, $date);
		return date_format($tempDate, 'Y-m-d');

	}
	
	public function getStr($str)
	{
		if(get_magic_quotes_gpc())
		{
			$str = stripslashes(trim($str));
		}
		//$str = addcslashes(mysql_real_escape_string($str), "%_");
		$str = $this->_mysqli->real_escape_string($str);
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
