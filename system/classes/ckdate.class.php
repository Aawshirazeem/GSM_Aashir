<?php
	class ckdate
	{
		public function get_time_difference( $start, $end )
		{
			if( $end >= $start )
			{
				$years_s = date("Y",strtotime($start));
				$years_e = date("Y",strtotime($end));
				$years_diff = 0;
				
				$months_s = date("m",strtotime($start));
				$months_e = date("m",strtotime($end));
				$months_diff = 0;
				
				$days_s = date("d",strtotime($start));
				$days_e = date("d",strtotime($end));
				$days_diff = 0;
				
				$hours_s = date("H",strtotime($start));
				$hours_e = date("H",strtotime($end));
				$hours_diff = 0;
				
				$min_s = date("i",strtotime($start));
				$min_e = date("i",strtotime($end));
				$min_diff = 0;
				
				$sec_s = date("s",strtotime($start));		   
				$sec_e = date("s",strtotime($end));
				
				$mili_sec_s = date("B",strtotime($start));		   
				$mili_sec_e = date("B",strtotime($end));


				$mili_sec_diff = 0;
				if($mili_sec_s > $mili_sec_e)
				{
					$sec_e--;
					$mili_sec_e += 1000;
				}
				$mili_sec_diff = $mili_sec_e - $mili_sec_s; 
				
				$sec_diff = 0;
				if($sec_s > $sec_e)
				{
					$min_e--;
					$sec_e += 60;
				}
				$sec_diff = $sec_e - $sec_s; 
				
				if($min_s > $min_e)
				{
					$hours_e--;
					$min_e += 60;
				}
				$min_diff = $min_e - $min_s; 
				
				if($hours_s > $hours_e)
				{
					$days_e--;
					$hours_e += 24;
				}
				$hours_diff = $hours_e - $hours_s; 
				
				if($days_s > $days_e)
				{
					$months_e--;
					switch($months_s)
					{
						case 1;
							$days_add = 31;
							break;
						case 2;
							if(date("L",$years_s))
							{
								$days_add = 29;
							}
							else
							{
								$days_add = 28;
							}
							break;
						case 3;
							$days_add = 31;
							break;
						case 4;
							$days_add = 30;
							break;
						case 5;
							$days_add = 31;
							break;
						case 6;
							$days_add = 30;
							break;
						case 7;
							$days_add = 31;
							break;
						case 8;
							$days_add = 31;
							break;
						case 9;
							$days_add = 30;
							break;
						case 10;
							$days_add = 31;
							break;
						case 11;
							$days_add = 30;
							break;
						case 12;
							$days_add = 31;
							break;
					}
					$days_e += $days_add;
				}
				$days_diff = $days_e - $days_s; 
				
				
				if($months_s > $months_e)
				{
					$years_e--;
					$months_e += 12;
				}
				$months_diff = $months_e - $months_s; 
				
				$years_diff = $years_e - $years_s; 
				
				return(array('years'=>$years_diff, 'months'=>$months_diff, 'days'=>$days_diff, 'hours'=>$hours_diff, 'minutes'=>$min_diff, 'seconds'=>$sec_diff, 'miliseconds'=>$mili_sec_diff));
			}
			else
			{
				//trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
			}
		}
		
		public function microtime_float()
		{
			list($usec, $sec) = explode(" ", microtime());
			return ((float)$usec + (float)$sec);
		}
	}
?>