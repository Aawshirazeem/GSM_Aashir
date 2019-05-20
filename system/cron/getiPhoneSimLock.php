<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$iPhoneSimLockCheck = new iPhoneSimLockCheck();
	
	$mysql = new mysql();
	
	
	
	$sql = 'select id, imei, user_id, credits from ' . ORDER_IMEI_MASTER . ' where
					status=0 and tool_id=33 limit 10';
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$ids = '';
		foreach($rows as $row)
		{
			$ids .= $row['id'] . ',';
		}
		$ids = trim($ids, ',');
		
		$sql = 'update ' . ORDER_IMEI_MASTER . ' set status=1 where id in(' . $ids . ')';
		$mysql->query($sql);
		foreach($rows as $row)
		{
			echo $row['imei'] . '<hr />';
			$result = $iPhoneSimLockCheck->getReply($row['imei']);
			
			$sql = 'update 
						' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
						set
						im.status=2,
						reply_by=3,
						reply=' . $mysql->quote(base64_encode($result)) . ',
						im.reply_date_time=now(),
						um.credits_inprocess = um.credits_inprocess - im.credits, um.credits_used = um.credits_used + im.credits
						where im.status=1 and um.id = im.user_id and im.id=' . $row['id'];
			$mysql->query($sql);
			//if($mysql->rowCount($query) > 0)
			{
				$objCredits = new credits();
				$objCredits->processIMEI($mysql->getInt($row['id']), $row['user_id'], $row['credits']);
			}
		}
	}
	echo "IMEI Processed";
	
	
	
?>