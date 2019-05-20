<?php
	class geo{
		
		//Property UserName
		public function getCountryCode($ip)
		{
			include(CONFIG_PATH_SYSTEM_ABSOLUTE . 'geo/geoip.inc');
			$gi = geoip_open(CONFIG_PATH_SYSTEM_ABSOLUTE . 'geo/GeoIP.dat', GEOIP_STANDARD);
			$ip = $_SERVER['REMOTE_ADDR'];
			$code = geoip_country_code_by_addr($gi, $ip);
			//$code = geoip_country_code_by_addr($gi, '46.36.198.121');
			geoip_close($gi);
			return $code;
		}
		
	}
	
	
	
?>