<?php
	class password
	{
		public function generate($strPass)
		{
			return crypt($strPass, CONFIG_SALT);
		}
	}	
?>