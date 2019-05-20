<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$supplier->checkLogin();
	$supplier->reject();
	
	
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
								supplier_id=' . $supplier->getUserId() . ',
								credits_purchase= (select credits_purchase from ' . IMEI_SUPPLIER_DETAILS . ' isd where isd.supplier_id=' . $supplier->getUserId() . ' and isd.tool = im.tool_id)
							where im.id in (' . $tempIds . ') and im.status=0';
				$mysql->query($sql_update);
			}
			$doneIMEIs .= $tempIds;
			$tempIds = '';
		}
	}

	if($tempIds != '')
	{
		$tempIds = trim($tempIds, ',');
		if($tempIds != '')
		{
			$sql_update = 'update
						' . ORDER_IMEI_MASTER . ' im
						set
							status=1,
							supplier_id=' . $supplier->getUserId() . ',
							credits_purchase= (select credits_purchase from ' . IMEI_SUPPLIER_DETAILS . ' isd where isd.supplier_id=' . $supplier->getUserId() . ' and isd.tool = im.tool_id)
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