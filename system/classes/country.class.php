<?php
//include("geo/geoip.inc");
//echo getcwd() . "\n";exit;
require_once 'system/geo/geoip.inc';
	class country{	
		
		public function getCountries($select = "")
		{
			$mysql = new mysql();
			
			$sql= "select * from " . COUNTRY_MASTER;
			$query = $mysql->query($sql);
			$strReturn = "";
			if($mysql->rowCount($query) > 0)
			{
				$strReturn .= '<option value="0">Select Country</option>';
				$rows = $mysql->fetchArray($query);
				foreach($rows as $row)
				{
					$strReturn .= '<option ' . (($select==$row['id']) ? 'selected="selected"' : '') . ' value="' . $row['id'] . '">' . $row['countries_name'] . '</option>';
				}
			}
			return $strReturn;
		}
                public function getccode($ip)
                {
                   // echo getcwd() . "\n";exit;
                  //  echo $ip;exit;
                    $df="PK";
                    if($ip !="::1")
                    {
                    $gi = geoip_open('system/geo/GeoIP.dat', GEOIP_STANDARD);
                     
                   return geoip_country_code_by_addr($gi, $ip);
                   //geoip_country_name_by_addr($gi, $ip) . "\n";

                    //geoip_close($gi);
                    }
                  else if($ip =="127.0.0.1")
                      
                   {
                        return $df;
                   }
                   else 
                   {
                        return $df;
                   }
                }
	}
?>