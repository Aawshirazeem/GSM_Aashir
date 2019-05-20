<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$objCredits = new credits();
	
	//$admin->checkLogin();
	//$admin->reject();
	
	$proIds = '';
	
	
	$qStrIds = "";
	$Ids = isset($_POST['locked']) ? $_POST['locked'] : array();
	$doneIMEIs = '';
	$tempIds = '';
	$count = 0;
	foreach($Ids as $id)
	{
		$tempIds .= $mysql->getInt($id) . ',';
		$count++;
		if($count % 50 == 0)
		{
			$tempIds = trim($tempIds, ',');
			if($tempIds != '')
			{
				$sql_update = 'update
							' . ORDER_IMEI_MASTER . ' im
							set
								status=1,
								im.admin_id=' . $admin->getUserId() . ',
								credits_purchase= (select credits_purchase from nxt_imei_tool_master itm where itm.id = im.tool_id)						
							where im.id in (' . $tempIds . ') and im.status=0';
				$mysql->query($sql_update);
			}
			$doneIMEIs .= $tempIds;
			$tempIds = '';
		}
	}

	// process IMEIS left after compilition of above loop
	if($tempIds != '')
	{
		$tempIds = trim($tempIds, ',');
		if($tempIds != '')
		{
			$sql_update = 'update
						' . ORDER_IMEI_MASTER . ' im
						set
							status=1,
							im.admin_id=' . $admin->getUserId() . ',
							credits_purchase= (select credits_purchase from nxt_imei_tool_master itm where itm.id = im.tool_id)						
						where im.id in (' . $tempIds . ') and im.status=0';
			$mysql->query($sql_update);
			$doneIMEIs .= $tempIds;
		}
	}
	
	echo json_encode(array(
			'ids' => $doneIMEIs,
			'result' => "Done"
		));
?>