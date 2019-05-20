<?php
	class member_api{

		var $user_id = 0;
		var $currency_id = 0;
		
		//Property UserId
		public function getUserId()
		{
			return $this->user_id;
		}
		public function setUserId($value)
		{
			$this->user_id = $value;
		}

		public function getCurrencyId()
		{
			return $this->currency_id;
		}
		public function setCurrencyId($value)
		{
			$this->currency_id = $value;
		}
		
		
		public function member_api($api_key,$un)
		{
			$mysql = new mysql();
			$sql = 'select id, currency_id from ' . USER_MASTER . ' where username='.$mysql->quote($un).' and api_key=' . $mysql->quote($api_key);
			
			$query = $mysql->query($sql);
			$row = $mysql->fetchArray($query);
			$this->setUserId($row[0]['id']);
			$this->setCurrencyId($row[0]["currency_id"]);
		}


		
	}
	
	
	
?>