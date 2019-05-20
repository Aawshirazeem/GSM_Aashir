<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$mysql = new mysql();
	$xml = new xml();
	$body = "";
	$imei = "";
	
	$sql = 'select
				id, imei,
				DATE_FORMAT(date_time, "%k:%i") as dtDateTime
				from ' . ORDER_IMEI_MASTER . ' where status=0 limit 100';
	$query = $mysql->query($sql);
		
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		foreach($rows as $row)
		{
			$body = $xml->element("order_id",htmlspecialchars($row['id']));
			$body .= $xml->element("imei",htmlspecialchars($row['imei']));
			$imei .= $xml->parent("imei",$body);
		}
	}
	$body = $xml->parent("IMEIs",$imei);
	$body = $xml->start() . $body;
	
	echo $body;

	
?>