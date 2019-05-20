<?php
class imei{
		public function checkIMEI( $IMEI, $Checksum = false, $alpha = false, $size = 15 )
		{
			//$Checksum = false;
			if (is_string($IMEI))
			{
				$patternAlpha = ($alpha == true) ? '[0-9,a-z,A-Z]' : '[0-9]';
				$patternSize = ($size == 15) ? '{15}' : '{' . $size . '}';
				if (preg_match('/^' . $patternAlpha . $patternSize . '$/', $IMEI))
				{
					if (! $Checksum) return true;

					for ($i = 0, $Sum = 0; $i < 14; $i++)
					{
						$Tmp = $IMEI[$i] * ( ($i % 2) + 1 );
						$Sum += ($Tmp % 10) + intval($Tmp / 10);
					}
					
					return ( ( ( 10 - ( $Sum % 10 ) ) % 10 ) == $IMEI[14] );
				}
			}
			
			return false;
		}
}
?>