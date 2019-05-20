<?php
	class mysql_connection2
	{
		var $_mysql_connection = '';
		public function __construct()
		{
			$validator = new validator();
			if (version_compare(phpversion(), '5.0.0', '<'))
			{
				$validator->goOffline("PHP version 5.0 & above is required","ERROR");
			}
			else
			{
				$this->_mysql_connection = new mysqli(CONFIG_DB_HOST2, CONFIG_DB_USER2, CONFIG_DB_PASS2, CONFIG_DB_NAME2);
				if ($this->_mysql_connection->connect_errno)
				{
					$validator->goOffline("Failed to connect to MySQL: (" . $this->_mysql_connection->connect_errno . ") ","ERROR");
				}
			}
			/* change character set to utf8 */
			if (!$this->_mysql_connection->set_charset("utf8")) {
				printf("Error loading character set utf8: %s\n", $this->_mysql_connection->error);
			}
		}
		public function getConnection()
		{
			return $this->_mysql_connection;
		}
		public function __destruct()
		{
			$this->_mysql_connection->close();
		}
	}
?>