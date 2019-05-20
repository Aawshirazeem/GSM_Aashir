<?php
	class helper{
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
		}
?>