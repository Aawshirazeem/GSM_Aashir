<?php
	class credits{

		public function getCredits(){
			$mysql = new mysql();
			$member = new member();
			$member->checkLogin();


			$sql= 'select
							um.credits, um.credits_inprocess, um.credits_used, um.currency_id,
							cm.prefix, cm.suffix, cm.rate,
							cmd.id as default_currency_id, cmd.prefix default_prefix, cmd.suffix default_suffix, cmd.rate default_rate
						from ' . USER_MASTER . ' um
						left join ' . CURRENCY_MASTER . ' cm on (um.currency_id = cm.id)
						left join ' . CURRENCY_MASTER . ' cmd on (cmd.is_default = 1)
						where um.id=' . $member->getUserID();
			$result = $mysql->getResult($sql);
			return $result['RESULT'][0];
		}
		
		public function getMemberCredits()
		{
			$mysql = new mysql();
			$member = new member();
			$member->checkLogin();
			
			if($member->isLogedin() == true)
			{
				$sql= 'select
								um.credits, um.credits_inprocess, um.credits_used, um.currency_id,
								cm.prefix, cm.suffix, cm.rate
							from ' . USER_MASTER . ' um
							left join ' . CURRENCY_MASTER . ' cm on (um.currency_id = cm.id)
							where um.id=' . $member->getUserID();
				$query = $mysql->query($sql);
				$rows = $mysql->fetchArray($query);
				$row = $rows[0];
				
				$prefix = $suffix = '';
				$rate = 0;
				$default = 0;
				if($row['currency_id'] != 0)
				{
					$prefix = $row['prefix'];
					$suffix = $row['suffix'];
					$rate = $row['rate'];
					$default = 0;
				}
				else
				{
					$sql_curr ='select * from ' . CURRENCY_MASTER . ' where `is_default`=1';
					$query_curr = $mysql->query($sql_curr);
					$rows_curr = $mysql->fetchArray($query_curr);
					$prefix = $rows_curr[0]['prefix'];
					$suffix = $rows_curr[0]['suffix'];
					$rate = $rows_curr[0]['rate'];
					$default = 1;
				}
				
				
				$return['credits'] = $this->printCredits($row['credits'], $prefix, $suffix);
				$return['process'] = $this->printCredits($row['credits_inprocess'], $prefix, $suffix);
				$return['used'] = $this->printCredits($row['credits_used'], $prefix, $suffix);
				$return['default'] = $default;
				$return['prefix'] = $prefix;
				$return['suffix'] = $suffix;
				$return['rate'] = $rate;
				return $return;
			}
			else
			{
				$return = array('credits'=>0,'process'=>0,'used'=>0);
				return $return;
			}
		}
		
		public function getMemberCreditsAPI($id)
		{
			$mysql = new mysql();
			
			$sql= 'select
							um.credits, um.credits_inprocess, um.credits_used, um.currency_id,
							cm.prefix, cm.suffix, cm.rate
						from ' . USER_MASTER . ' um
						left join ' . CURRENCY_MASTER . ' cm on (um.currency_id = cm.id)
						where um.id=' . $id;
			$query = $mysql->query($sql);
			$rows = $mysql->fetchArray($query);
			$row = $rows[0];
			
			$prefix = $suffix = '';
			$rate = 0;
			$default = 0;
			if($row['currency_id'] != 0)
			{
				$prefix = $row['prefix'];
				$suffix = $row['suffix'];
				$rate = $row['rate'];
				$default = 0;
			}
			else
			{
				$sql_curr ='select * from ' . CURRENCY_MASTER . ' where `is_default`=1';
				$query_curr = $mysql->query($sql_curr);
				$rows_curr = $mysql->fetchArray($query_curr);
				$prefix = $rows_curr[0]['prefix'];
				$suffix = $rows_curr[0]['suffix'];
				$rate = $rows_curr[0]['rate'];
				$default = 1;
			}
			
			
			$return['credits'] = $prefix . ' ' . $row['credits'] . ' ' . $suffix;
			$return['process'] = $prefix . ' ' . $row['credits_inprocess'] . ' ' . $suffix;
			$return['used'] = $prefix . ' ' . $row['credits_used'] . ' ' . $suffix;
			$return['default'] = $default;
			$return['prefix'] = $prefix;
			$return['suffix'] = $suffix;
			$return['rate'] = $rate;
			return $return;
		}
		
		public function printCredits($credits, $prefix, $suffix)
		{
			//return '**' . $prefix . ' ' . $credits . ' ' . $suffix;
			return $prefix . ' ' . number_format($credits, 2) . ' ' . $suffix;
		}
		
		public function getUserCredits($user_id)
		{
			$mysql = new mysql();
			
			$return = array('credits'=>0,'process'=>0,'used'=>0);
			
			$sql= 'select
							credits, credits_inprocess, credits_used
						from ' . USER_MASTER . '
						where id=' . $mysql->getInt($user_id);
						
			$query = $mysql->query($sql);
			$rows = $mysql->fetchArray($query);
			$row = $rows[0];
			$return['credits'] = $row['credits'];
			$return['process'] = $row['credits_inprocess'];
			$return['used'] = $row['credits_used'];
			return $return;
		}
		
		
		/********************************
			File Service Details Start
		*********************************/
		public function processFile($order_id, $user_id, $credits)
		{
			/*$mysql = new mysql();
			
			
			$user_cr = $this->getUserCredits($user_id);
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
					(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
					credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)
					values(
						' . $mysql->getInt($user_id) . ',
						now(),
						' . $mysql->getFloat($credits) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat($user_cr['process']) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat(($user_cr['process']-$credits)) . ',
						' . $mysql->getFloat(($user_cr['used']+$credits)) . ',
						' . $mysql->getInt($order_id) . ',
						' . $mysql->quote("File Service Processed") . ',
						3,
						' . $mysql->quote($ip) . '
					);';
			$query = $mysql->query($sql);*/
		}
		
		
		public function returnFile($order_id, $user_id, $credits)
		{
			$mysql = new mysql();
			
			
			$user_cr = $this->getUserCredits($user_id);
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
					(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
					credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)
					values(
						' . $mysql->getInt($user_id) . ',
						now(),
						' . $mysql->getFloat($credits) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat($user_cr['process']) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getFloat(($user_cr['credits']+$credits)) . ',
						' . $mysql->getFloat(($user_cr['process']-$credits)) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getInt($order_id) . ',
						' . $mysql->quote("File Service Return") . ',
						1,
						' . $mysql->quote($ip) . '
					);';
			$query = $mysql->query($sql);	
		}
		/***********************************
			End: File Service Details Start
		************************************/

		
		/********************************
			IMEI Service Details Start
		*********************************/
		public function returnIMEI($order_id, $user_id, $credits)
		{
			$mysql = new mysql();
			
			$user_cr = $this->getUserCredits($user_id);
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
					(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
					credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)
					values(
						' . $mysql->getInt($user_id) . ',
						now(),
						' . $mysql->getFloat($credits) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat($user_cr['process']) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getFloat(($user_cr['credits']+$credits)) . ',
						' . $mysql->getFloat(($user_cr['process']-$credits)) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getInt($order_id) . ',
						' . $mysql->quote("IMEI Return") . ',
						1,
						' . $mysql->quote($ip) . '
					);';
			$query = $mysql->query($sql);	
		}
		
		
		public function refundIMEI($order_id, $user_id, $credits)
		{
			$mysql = new mysql();
			
			
			$user_cr = $this->getUserCredits($user_id);
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
					(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
					credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)
					values(
						' . $mysql->getInt($user_id) . ',
						now(),
						' . $mysql->getFloat($credits) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat($user_cr['process']) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getFloat(($user_cr['credits']+$credits)) . ',
						' . $mysql->getFloat($user_cr['process']) . ',
						' . $mysql->getFloat(($user_cr['used']-$credits)) . ',
						' . $mysql->getInt($order_id) . ',
						' . $mysql->quote("IMEI Canceled") . ',
						1,
						' . $mysql->quote($ip) . '
					);';
			$query = $mysql->query($sql);	
		}

		
		public function processIMEI($order_id, $user_id, $credits)
		{
			/*$mysql = new mysql();
			
			$user_cr = $this->getUserCredits($user_id);
			$ip = $_SERVER['REMOTE_ADDR'];

			$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
					(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
					credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)
					values(
						' . $mysql->getInt($user_id) . ',
						now(),
						' . $mysql->getFloat($credits) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat($user_cr['process']) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat(($user_cr['process']-$credits)) . ',
						' . $mysql->getFloat(($user_cr['used']+$credits)) . ',
						' . $mysql->getInt($order_id) . ',
						' . $mysql->quote("IMEI Processed") . ',
						3,
						' . $mysql->quote($ip) . '
					);';
			$query = $mysql->query($sql);*/	
		}
		/************************************
			End: IMEI Service Details Start
		*************************************/
		
		
		/********************************
			File Service Details Start
		*********************************/
		public function processServerLog($order_id, $user_id, $credits)
		{
			$mysql = new mysql();
			
			
			$user_cr = $this->getUserCredits($user_id);
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
					(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
					credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)
					values(
						' . $mysql->getInt($user_id) . ',
						now(),
						' . $mysql->getFloat($credits) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat($user_cr['process']) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat(($user_cr['process']-$credits)) . ',
						' . $mysql->getFloat(($user_cr['used']+$credits)) . ',
						' . $mysql->getInt($order_id) . ',
						' . $mysql->quote("Server Log Processed") . ',
						3,
						' . $mysql->quote($ip) . '
					);';
			$query = $mysql->query($sql);
		}
		
		
		public function returnServerLog($order_id, $user_id, $credits)
		{
			$mysql = new mysql();
			
			
			$user_cr = $this->getUserCredits($user_id);
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
					(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
					credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)
					values(
						' . $mysql->getInt($user_id) . ',
						now(),
						' . $mysql->getFloat($credits) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat($user_cr['process']) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getFloat(($user_cr['credits']+$credits)) . ',
						' . $mysql->getFloat(($user_cr['process']-$credits)) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getInt($order_id) . ',
						' . $mysql->quote("Server Log Return") . ',
						1,
						' . $mysql->quote($ip) . '
					);';
			$query = $mysql->query($sql);	
		}
		/***********************************
			End: File Service Details Start
		************************************/
		
		
		/********************************
			Prepaid Log Process
		*********************************/
		public function processPrepaidLog($credits, $user_id)
		{
			/*$mysql = new mysql();
			
			
			$user_cr = $this->getUserCredits($user_id);
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
					(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
					credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)
					values(
						' . $mysql->getInt($user_id) . ',
						now(),
						' . $mysql->getFloat($credits) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat($user_cr['process']) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat(($user_cr['process']-$credits)) . ',
						' . $mysql->getFloat(($user_cr['used']+$credits)) . ',
						' . $mysql->getInt($order_id) . ',
						' . $mysql->quote("Server Log Processed") . ',
						3,
						' . $mysql->quote($ip) . '
					);';
			$query = $mysql->query($sql);*/	
		}



		
		// deduct credits on new order
		// ACCOUNT => PROCESSED
		public function cutOrderCredits($order_id, $credits, $user_id, $OrderType)
		{
			$mysql = new mysql();
			
			
			switch($OrderType)
			{
				case '1':
					$QOrderType = "order_id_imei";
					$Info = "IMEI Order";
					break;
				case '2':
					$QOrderType = "order_id_file";
					$Info = "File Order";
					break;
				case '3':
					$QOrderType = "order_id_server";
					$Info = "Server Log Order";
					break;
				case '4':
					$QOrderType = "order_id_prepaid";
					$Info = "Prepaid Log Order";
					break;
			}
			
			$user_cr = $this->getUserCredits($user_id);
			
			$ip = $_SERVER['REMOTE_ADDR'];

			$sql = "update " . USER_MASTER . ' set credits=credits-' . $credits . ', credits_inprocess=credits_inprocess+' . $credits . ' where id=' . $user_id;
			$mysql->query($sql);	
			
			$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
					(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
					credits_after, credits_after_process, credits_after_used, ' . $QOrderType . ', info, trans_type, ip)
					values(
						' . $mysql->getInt($user_id) . ',
						now(),
						' . $mysql->getFloat($credits) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat($user_cr['process']) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getFloat(($user_cr['credits']-$credits)) . ',
						' . $mysql->getFloat(($user_cr['process']+$credits)) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getInt($order_id) . ',
						' . $mysql->quote($Info) . ',
						2,
						' . $mysql->quote($ip) . '
					);';
			$query = $mysql->query($sql);
			
		}

		
		// move credits from account to used
		// ACCOUNT => USED
		public function cutOrderCreditsDirect($order_id, $credits, $user_id, $OrderType)
		{
			$mysql = new mysql();
			
			
			switch($OrderType)
			{
				case '1':
					$QOrderType = "order_id_imei";
					$Info = "IMEI Order";
					break;
				case '2':
					$QOrderType = "order_id_file";
					$Info = "File Order";
					break;
				case '3':
					$QOrderType = "order_id_server";
					$Info = "Server Log Order";
					break;
				case '4':
					$QOrderType = "order_id_prepaid";
					$Info = "Prepaid Log Order";
					break;
			}
			
			$user_cr = $this->getUserCredits($user_id);
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
					(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
					credits_after, credits_after_process, credits_after_used, ' . $QOrderType . ', info, trans_type, ip)
					values(
						' . $mysql->getInt($user_id) . ',
						now(),
						' . $mysql->getFloat($credits) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat($user_cr['process']) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getFloat(($user_cr['credits']-$credits)) . ',
						' . $mysql->getFloat($user_cr['process']) . ',
						' . $mysql->getFloat(($user_cr['used']+$credits)) . ',
						' . $mysql->getInt($order_id) . ',
						' . $mysql->quote($Info) . ',
						3,
						' . $mysql->quote($ip) . '
					);';
			$query = $mysql->query($sql);
			
			$sql = "update " . USER_MASTER . ' set credits=credits-' . $credits . ', credits_used=credits_used+' . $credits . ' where id=' . $user_id;
			$mysql->query($sql);	
		}

		
		
		public function transfer($user_id, $user_id2, $credits, $msg)
		{
			$objCredits = new credits();
			$mysql = new mysql();
			
						
			$user_cr = $objCredits->getUserCredits($user_id);
			$user_cr2 = $objCredits->getUserCredits($user_id2);
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
					(user_id, user_id2, date_time, credits,
					credits_acc, credits_acc_process, credits_acc_used,
					credits_after, credits_after_process, credits_after_used,
					credits_acc_2, credits_acc_process_2, credits_acc_used_2,
					credits_after_2, credits_after_process_2, credits_after_used_2,
					info, trans_type, ip)
					values(
						' . $mysql->getInt($user_id) . ',
						' . $mysql->getInt($user_id2) . ',
						now(),
						' . $mysql->getFloat($credits) . ',
						' . $mysql->getFloat($user_cr['credits']) . ',
						' . $mysql->getFloat($user_cr['process']) . ',
						' . $mysql->getFloat($user_cr['used']) . ',
						' . $mysql->getFloat(($user_cr['credits']-$credits)) . ',
						' . $mysql->getFloat(($user_cr['process'])) . ',
						' . $mysql->getInt($user_cr['used']+$credits) . ',
						
						' . $mysql->getFloat($user_cr2['credits']) . ',
						' . $mysql->getFloat($user_cr2['process']) . ',
						' . $mysql->getFloat($user_cr2['used']) . ',
						' . $mysql->getFloat(($user_cr2['credits']+$credits)) . ',
						' . $mysql->getFloat(($user_cr2['process'])) . ',
						' . $mysql->getInt($user_cr2['used']) . ',
						
						' . $mysql->quote($msg) . ',
						6,
						' . $mysql->quote($ip) . '
					);';
			$query = $mysql->query($sql);
		}
		
		public function transferAdmin($inOut, $admin_id, $user_id, $credits, $admin_note, $user_note)
		{
			$objCredits = new credits();
			$mysql = new mysql();
			$txt_id = '';
						
			$user_cr = $objCredits->getUserCredits($user_id);
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
			if($inOut == 1)
			{
				$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
						(user_id, admin_id2, date_time, credits,
						credits_acc, credits_acc_process, credits_acc_used,
						credits_after, credits_after_process, credits_after_used,
						info, trans_type,admin_note, user_note, ip)
						values(
							' . $mysql->getInt($user_id) . ',
							' . $mysql->getInt($admin_id) . ',
							now(),
							' . $mysql->getFloat($credits) . ',

							' . $mysql->getFloat($user_cr['credits']) . ',
							' . $mysql->getFloat($user_cr['process']) . ',
							' . $mysql->getFloat($user_cr['used']) . ',
							' . $mysql->getFloat(($user_cr['credits']+$credits)) . ',
							' . $mysql->getFloat(($user_cr['process'])) . ',
							' . $mysql->getInt($user_cr['used']) . ',
														
							' . $mysql->quote("Credits Added by Admin") . ',
							6,
							' . $mysql->quote($admin_note) . ',
							' . $mysql->quote($user_note) . ',
							' . $mysql->quote($ip) . '
						);';
			}
			else
			{
				$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
						(admin_id, user_id2, date_time, credits,
						credits_acc_2, credits_acc_process_2, credits_acc_used_2,
						credits_after_2, credits_after_process_2, credits_after_used_2,
						info, trans_type, ip)
						values(
							' . $mysql->getInt($admin_id) . ',
							' . $mysql->getInt($user_id) . ',
							now(),
							' . $mysql->getFloat($credits) . ',
							
							' . $mysql->getFloat($user_cr['credits']) . ',
							' . $mysql->getFloat($user_cr['process']) . ',
							' . $mysql->getFloat($user_cr['used']) . ',
							' . $mysql->getFloat(($user_cr['credits']-$credits)) . ',
							' . $mysql->getFloat(($user_cr['process'])) . ',
							' . $mysql->getInt($user_cr['used']) . ',
							
							
							' . $mysql->quote("Credits Revoked by Admin") . ',
							6,
							' . $mysql->quote($ip) . '
						);';
			}
			$query = $mysql->query($sql);
			$this->txn_id =$mysql->insert_id($query); 
			return $this->txn_id;
		}
		public function transferAutoPay($inOut, $admin_id, $user_id, $credits, $admin_note, $user_note)
		{
			$objCredits = new credits();
			$mysql = new mysql();
			
						
			$user_cr = $objCredits->getUserCredits($user_id);
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
			if($inOut == 1)
			{
				$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
						(user_id, admin_id2, date_time, credits,
						credits_acc, credits_acc_process, credits_acc_used,
						credits_after, credits_after_process, credits_after_used,
						info, trans_type,admin_note, user_note, ip)
						values(
							' . $mysql->getInt($user_id) . ',
							' . $mysql->getInt($admin_id) . ',
							now(),
							' . $mysql->getFloat($credits) . ',

							' . $mysql->getFloat($user_cr['credits']) . ',
							' . $mysql->getFloat($user_cr['process']) . ',
							' . $mysql->getFloat($user_cr['used']) . ',
							' . $mysql->getFloat(($user_cr['credits']+$credits)) . ',
							' . $mysql->getFloat(($user_cr['process'])) . ',
							' . $mysql->getInt($user_cr['used']) . ',
														
							' . $mysql->quote("Credits Added by Auto Pay") . ',
							6,
							' . $mysql->quote($admin_note) . ',
							' . $mysql->quote($user_note) . ',
							' . $mysql->quote($ip) . '
						);';
			}
			else
			{
				$sql= 'insert into ' . CREDIT_TRANSECTION_MASTER . '
						(admin_id, user_id2, date_time, credits,
						credits_acc_2, credits_acc_process_2, credits_acc_used_2,
						credits_after_2, credits_after_process_2, credits_after_used_2,
						info, trans_type, ip)
						values(
							' . $mysql->getInt($admin_id) . ',
							' . $mysql->getInt($user_id) . ',
							now(),
							' . $mysql->getFloat($credits) . ',
							
							' . $mysql->getFloat($user_cr['credits']) . ',
							' . $mysql->getFloat($user_cr['process']) . ',
							' . $mysql->getFloat($user_cr['used']) . ',
							' . $mysql->getFloat(($user_cr['credits']-$credits)) . ',
							' . $mysql->getFloat(($user_cr['process'])) . ',
							' . $mysql->getInt($user_cr['used']) . ',
							
							
							' . $mysql->quote("Credits Revoked by Auto Pay") . ',
							6,
							' . $mysql->quote($ip) . '
						);';
			}
			$query = $mysql->query($sql);
		}


		
		
		
	}	
?>