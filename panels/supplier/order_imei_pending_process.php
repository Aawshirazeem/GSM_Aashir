<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$supplier->checkLogin();
	$supplier->reject();
	$validator->formValidateSupplier('supplier_order_imei_33455gk3dd4d2');
	
	$search_tool_id = $request->PostInt('search_tool_id');
	if($search_tool_id == 0)
	{
		$search_tool_id = $request->GetInt('search_tool_id');
	}
	$lock_all = $request->getInt('lock_all');
	
	//// if IMEI Download button is pressed ////
	$download=$request->PostStr('download');
	if($download == 'Download')
	{
		include(CONFIG_PATH_SUPPLIER_ABSOLUTE . 'order_imei_download.php');
		exit();
	}
	////////////////////////////////////////////
	
	$objCredits = new credits();
	
	
	$qStrIds = "";
	$Ids = isset($_POST['Ids']) ? $_POST['Ids'] : array();
	$type = $request->postStr('type');
	
	
	$tempIds = '';
	$count = 1;
	$proIds = '';
	
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
								im.supplier_id=' . $supplier->getUserId() . ',
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
				$proIds .= '&id[]=' . base64_encode($row['id']);
			}
			
			
			$sql_update = 'update
						' . ORDER_IMEI_MASTER . ' im
						set
							status=1,
							im.supplier_id=' . $supplier->getUserId() . ',
							credits_purchase= (select credits_purchase from nxt_imei_tool_master itm where itm.id = im.tool_id)						
						where im.id in (' . $tempIds . ') and im.status=0;';
			$mysql->query($sql_update);
		}
	}
	header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_imei_pending_final.html?1=1" . $proIds);
	exit();
?>