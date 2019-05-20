<?php
	class currency{	
		
		public function getCurrencies($select = "")
		{
			$mysql = new mysql();
			
			$sql= "select * from " . CURRENCY_MASTER;
			$query = $mysql->query($sql);
			$strReturn = "";
			if($mysql->rowCount($query) > 0)
			{
				$rows = $mysql->fetchArray($query);
				foreach($rows as $row)
				{
					if($select == 0 || $select == "")
					{
						$strReturn .= '<option ' . (($row['is_default'] == "1") ? 'selected="selected"' : '') . ' value="' . $row['id'] . '">' . $row['currency'] . '</option>';
					}
					else
					{
						$strReturn .= '<option ' . (($select==$row['id']) ? 'selected="selected"' : '') . ' value="' . $row['id'] . '">' . $row['currency'] . '</option>';
					}
					
				}
			}
			return $strReturn;
		}
	}	
?>