<?php
	class template{
		
		public function getInvoice($invoice_id)
		{
			$mysql = new mysql();
			
			
			$sql = 'select * from ' . TEMPLATE_MASTER . ' where tag=' . $mysql->quote('INVOICE');
			$query = $mysql->query($sql);
			$rows = $mysql->fetchArray($query);
			$template = $rows[0]['template'];
			
			$sql_in = 'select im.*, cm.prefix, gm.gateway
							from ' . INVOICE_MASTER . ' im
							left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)
							left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)
								where
									im.id=' . $mysql->getInt($invoice_id);
			$query_in = $mysql->query($sql_in);
			$rows_in = $mysql->fetchArray($query_in);
			$row_in = $rows_in[0];
			
			
			$template = $this->replaceCommon($template);
			$template = $this->replaceUser($template, $row_in['user_id']);
			$template = str_replace('{INVOICE}', 'INV #' . str_pad($row_in['id'],4,'0',0), $template);
			$template = str_replace('{DATE}', date("d-M Y", strtotime($row_in['date_time'])), $template);
			
			$template = str_replace('{DESCRIPTION}', 'Purchase of ' . $row_in['credits'] . ' Cr', $template);
			$template = str_replace('{AMOUNT}', $row_in['prefix'] . ' ' . $row_in['amount'], $template);
	
			return $template;

		}
		
		private function replaceCommon($str)
		{
			$str = $this->repSiteDetails($str);
			$str = $this->repURL($str);
			return $str;
		}
		
		private function replaceUser($str, $user_id)
		{
			$mysql = new mysql();
			$sql = 'select * from ' . USER_MASTER . ' where id=' . $mysql->getInt($user_id);
			$query = $mysql->query($sql);
			$rows = $mysql->fetchArray($query);
			$row = $rows[0];
			
			$str = str_replace('{USERNAME}', $row['username'], $str);
			$str = str_replace('{NAME}', $row['first_name'] . ' ' . $row['last_name'], $str);
			$str = str_replace('{COMPANY}', $row['company'], $str);
			$str = str_replace('{EMAIL}', $row['email'], $str);
			$str = str_replace('{ADDRESS}', nl2br($row['address']), $str);
			
			return $str;
		}
		
		private function repSiteDetails($str)
		{
			return str_replace("{SITE_DETAILS}", CONFIG_SITE_NAME . ' [' . CONFIG_SITE_TITLE . ']', $str);
		}
		
		private function repURL($str)
		{
			return str_replace("{URL}", CONFIG_DOMAIN, $str);
		}
	}
	
	
	
?>