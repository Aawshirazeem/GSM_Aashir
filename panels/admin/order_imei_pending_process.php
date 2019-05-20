<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$objCredits = new credits();
	
	
	$admin->checkLogin();
	$admin->reject();
	
	$proIds = '';
	
	
	$qStrIds = "";
	$Ids = isset($_POST['Ids']) ? $_POST['Ids'] : array();
	$type=$request->PostStr('type');
	$supplier_id=$request->PostInt('supplier_id');
	
	$search_tool_id = $request->PostInt('search_tool_id');
	if($search_tool_id == 0)
	{
		$search_tool_id = $request->GetInt('search_tool_id');
	}
	$lock_all = $request->getInt('lock_all');
	
	$limit=$request->PostInt('limit');
	$user_id=$request->PostInt('user_id');
	$ip=$request->PostStr('ip');
	
	
	
	
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
	if($search_tool_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '') . 'search_tool_id=' . $search_tool_id;
	}
	$pString = trim($pString, '&');
	
	$totalSelected = 0;
	foreach($Ids as $id)
	{
		if(isset($_POST['locked_' . $id]))
		{
			$totalSelected++;
		}
	}
	if($totalSelected == 0 and !isset($_POST['process_all']) and $lock_all == 0)
	{
		header("location:" .CONFIG_PATH_SITE_ADMIN ."order_imei.html?type=" . $type . (($pString!='') ? ('&' . $pString) : '') . "&reply=" .urlencode('reply_no_imei_seleted'));
		exit();
	}
	
	if(isset($_POST['process_all']) or $lock_all == 1)
	{
		$strLockTool = '';
		if($search_tool_id != 0)
		{
			$strLockTool = ' and im.tool_id=' . $search_tool_id;
		}
		
		$sql = 'select id from ' . ORDER_IMEI_MASTER . ' im where im.status=0 ' . $strLockTool;
		$query = $mysql->query($sql);
		$rows = $mysql->fetchArray($query);
		foreach($rows as $row)
		{
			$proIds .= '&id[]=' . base64_encode($row['id']);
		}
		
		$sql_update = 'update
							' . ORDER_IMEI_MASTER . ' im
							set
								status=1,
								credits_purchase= (select credits_purchase from nxt_imei_tool_master itm where itm.id = im.tool_id)
							where im.status=0 ' . $strLockTool;
		$mysql->query($sql_update);
		
	}
	
	
	
	$tempIds = '';
	$count = 1;
	foreach($Ids as $id)
	{
		$action = "";
		if(isset($_POST['process']))
		{
			if(isset($_POST['locked_' . $id]))
			{
				$tempIds .= $mysql->getInt($id) . ',';
				$count++;
			}
		}
		if($count % 50 == 0)
		{
			$tempIds = trim($tempIds, ',');
			if($tempIds != '')
			{
				$sql = 'select id from ' . ORDER_IMEI_MASTER . ' im where im.id in (' . $tempIds . ') and im.status=0';
				$query = $mysql->query($sql);
				$rows = $mysql->fetchArray($query);
				foreach($rows as $row)
				{
					$proIds .= '&id[]=' . base64_encode($row['id']);
				}
				
				$sql_update = 'update
							' . ORDER_IMEI_MASTER . ' im
							set
								status=1,
								credits_purchase= (select credits_purchase from nxt_imei_tool_master itm where itm.id = im.tool_id)						
							where im.id in (' . $tempIds . ') and im.status=0';
				$mysql->query($sql_update);
			}
			$tempIds = '';
		}
	}

	if($tempIds != '')
	{
		$tempIds = trim($tempIds, ',');
		
		if($tempIds != '')
		{
			$sql = 'select id from ' . ORDER_IMEI_MASTER . ' im where im.id in (' . $tempIds . ') and im.status=0';
			$query = $mysql->query($sql);
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				$proIds .= '&id[]=' . ($row['id']);
			}
			
			$sql_update = 'update
						' . ORDER_IMEI_MASTER . ' im
						set
							status=1,
							credits_purchase= (select credits_purchase from nxt_imei_tool_master itm where itm.id = im.tool_id)						
						where im.id in (' . $tempIds . ') and im.status=0';
			$mysql->query($sql_update);
			
		}
	}
	
	header("location:" .CONFIG_PATH_SITE_ADMIN ."order_imei.html?type=pending");
	exit();
	
?>