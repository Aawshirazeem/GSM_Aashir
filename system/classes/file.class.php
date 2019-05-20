<?php
class file{
		public function checkFile( $id, $Checksum = false )
		{
			$Checksum = false;
			if (is_string($id))
			{
				if (preg_match('/^[0-9]{15}$/', $id))
				{
					if (! $Checksum) return true;

					for ($i = 0, $Sum = 0; $i < 14; $i++)
					{
						$Tmp = $id[$i] * ( ($i % 2) + 1 );
						$Sum += ($Tmp % 10) + intval($Tmp / 10);
					}
					
					return ( ( ( 10 - ( $Sum % 10 ) ) % 10 ) == $id[14] );
				}
			}
			
			return false;
		}
}
?>