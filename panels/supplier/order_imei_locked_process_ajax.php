<?php exit;
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	
	$objCredits = new credits();
	$supplier->checkLogin();
	$supplier->reject();

	$qStrIds = "";
	$Ids = isset($_POST['Ids']) ? $_POST['Ids'] : array();
	$type=$request->PostStr('type');
	$supplier_id=$request->PostInt('supplier_id');
	$limit=$request->PostInt('limit');
	$user_id=$request->PostInt('user_id');
	$ip=$request->PostStr('ip');
	
	$doneIMEIs = '';
	
	
	//// if IMEI Download button is pressed ////
	$download=$request->PostStr('download');
	if($download == 'Download')
	{
		include(CONFIG_PATH_SUPPLIER_ABSOLUTE . 'order_imei_download.php');
		exit();
	}
	////////////////////////////////////////////
	
	$pString='';
	if($supplier_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '' ) . 'supplier_id=' . $supplier_id;
	}
	if($limit != 0)
	{
		$pString .= (($pString != '') ? '&' : '' ) . 'limit=' . $limit;
	}
	if($ip != '')
	{
		$pString .= (($pString != '') ? '&' : '') . 'ip=' . $ip;
	}
	if($user_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '') . 'user_id=' . $user_id;
	}
	$pString = trim($pString, '&');
	
	//Variables to save ids, unavailable message and unlock codes
	$UnAvailIds = $unavailMsg = '';
	$AvailIds = $AvailCode = '';
	
	$sqlAvail = '';
	$sqlUnavail = '';
	
	$tempAvails = $tempUnavail = 0;
	
	// Iterate all the items from last page
	if(is_array($Ids))
	{
		foreach($Ids as $id)
		{
			if(isset($_POST['unavailable_' . $id]))
			{
				$tempAvails++;
				$UnAvailIds .= $id . ',';
				$UnAvailRemarks = $request->PostStr('un_remarks_' . $id);
				
				$sqlUnavail .= '
								update 
									' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
									set
										im.status=3, 
										im.supplier_id_done=' . $supplier->getUserId() . ',
										im.reply_date_time=now(),
										im.message = ' . $mysql->quote(base64_encode($UnAvailRemarks)) . ',
										um.credits = um.credits + im.credits_amount,
										um.credits_inprocess = um.credits_inprocess - im.credits_amount
									where im.status=1 and um.id=im.user_id and im.id =' . $id . ';
									
								
								insert into ' . CREDIT_TRANSECTION_MASTER . '
									(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
									credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)
									
									select 
											oim.user_id,
											now(),
											oim.credits_amount,
											um.credits - oim.credits_amount,
											um.credits_inprocess + oim.credits_amount,
											um.credits_used,
											um.credits,
											um.credits_inprocess,
											um.credits_used,
											oim.id,
											' . $mysql->quote("IMEI Return") . ',
											0,
											' . $mysql->quote($ip) . '
										from ' . ORDER_IMEI_MASTER . ' oim 
										left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
										left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
									where oim.id =' . $id . ';
							
							
							';
			}
			else
			{
				$tempUnavail++;
				$unlock_code = $request->PostStr('unlock_code_' . $id);
				
				if($unlock_code != '')
				{
					$AvailIds .= $id . ',';
					
					// Update all Unlock codes and change the order status
					$sqlAvail .= '
									update 
											' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
										set
											im.status=2,
											im.reply=' . $mysql->quote(base64_encode($unlock_code)) . ',
											im.supplier_id_done=' . $supplier->getUserId() . ',
											im.reply_date_time=now(),
											um.credits_inprocess = um.credits_inprocess - im.credits_amount,
											um.credits_used = um.credits_used + im.credits_amount
										where im.status=1 and um.id = im.user_id and im.id=' . $id . ';
									
									
									
									insert into ' . CREDIT_TRANSECTION_MASTER . '
										(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
										credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)
									
										select
												oim.user_id,
												now(),
												oim.credits,
												um.credits,
												um.credits_inprocess + oim.credits_amount,
												um.credits_used - oim.credits_amount,
												um.credits,
												um.credits_inprocess,
												um.credits_used,
												oim.id,
												' . $mysql->quote("IMEI Processed") . ',
												1,
												' . $mysql->quote($ip) . '
											from ' . ORDER_IMEI_MASTER . ' oim 
											left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
											left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
										where oim.id =' . $id . ';';
					$doneIMEIs .= $id . ',';
					//$mysql->query($sql);
				}
			}
			
			if($tempAvails >= 10  && $sqlUnavail != '')
			{
				$mysql->multi_query($sqlUnavail);
				$sqlUnavail = '';
				$tempAvails = $tempUnavail = 0;
			}
			if($tempUnavail >= 10 && $sqlAvail != '')
			{
				$mysql->multi_query($sqlAvail);
				$sqlAvail = '';
				$tempAvails = $tempUnavail = 0;
			}
			
		}
	}
	
	if($sqlUnavail != '')
	{
		$mysql->multi_query($sqlUnavail);
	}
	if($sqlAvail != '')
	{
		$mysql->multi_query($sqlAvail);
	}

	
	
	
	
	
	

	/**********************************************************
						START: Available
	**********************************************************/
	$ip = $_SERVER['REMOTE_ADDR'];
	if($AvailIds != '')
	{
		$AvailIds = trim($AvailIds, ',');
		
		
		
		$sql = 'select
							um.username, um.email,
							oim.id as oid, oim.user_id uid, oim.reply,
							oim.imei, itm.tool_name, oim.credits
						from ' . ORDER_IMEI_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
					where oim.id in (' . $AvailIds . ')';
		$query = $mysql->query($sql);
		
		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			$argsAll = array();
			foreach($rows as $row)
			{
				$args = array(
								'to' => $row['email'],
								'from' => CONFIG_EMAIL,
								'fromDisplay' => CONFIG_SITE_NAME,
								'user_id' => $row['uid'],
								'save' => '1',
								'username' => $row['username'],
								'imei' => $row['imei'],
								'unlock_code' => base64_decode($row['reply']),
								'order_id' => $row['oid'],
								'tool_name' => $row['tool_name'],
								'credits'=>	$row['credits'],
								'site_admin' => CONFIG_SITE_NAME
							);
				array_push($argsAll, $args);
			}
			$objEmail->sendMultiEmailTemplate('admin_user_imei_avail', $argsAll);
		}
		
	}
	/**********************************************************
						END: Available
	**********************************************************/
	
	
	/**********************************************************
						START: Unavail
	**********************************************************/
	if($UnAvailIds != '')
	{
		$UnAvailIds = trim($UnAvailIds, ',');

		
		$sql = 'select
							um.username, um.email,
							oim.id as oid, oim.user_id uid, oim.reply,
							oim.imei, itm.tool_name, oim.credits
						from ' . ORDER_IMEI_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
					where oim.id in (' . $UnAvailIds . ')';
		$query = $mysql->query($sql);
		
		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			$argsAll = array();
			foreach($rows as $row)
			{
				$args = array(
								'to' => $row['email'],
								'from' => CONFIG_EMAIL,
								'fromDisplay' => CONFIG_SITE_NAME,
								'user_id' => $row['uid'],
								'save' => '1',
								'username' => $row['username'],
								'imei' => $row['imei'],
								'order_id' => $row['oid'],
								'tool_name' => $row['tool_name'],
								'credits'=>	$row['credits'],
								'site_admin' => CONFIG_SITE_NAME
							);
				array_push($argsAll, $args);
			}
			$objEmail->sendMultiEmailTemplate('admin_user_imei_unavail', $argsAll);
		}
	}
	/**********************************************************
						END: Unavail
	**********************************************************/
	
	
	echo json_encode(array(
			'ids' => $doneIMEIs,
			'result' => "Done"
		));
	
	
	//header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_imei.html?type=" . $type . (($pString!='') ? ('&' . $pString) : '') . "&reply=" .urlencode('reply_imei_update_success'));
	//exit();
?>