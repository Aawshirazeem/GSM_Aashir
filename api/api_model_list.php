<?php
	require_once("_init.php");
	$req = new request();
	$mysql = new mysql();
	
	$result = array();
	
	$sql='select
					im.id,im.model,
					ib.brand as brandName
				from ' . IMEI_MODEL_MASTER . ' im
				left join '.IMEI_BRAND_MASTER.' ib on(im.brand=ib.id)
				order by ib.brand';
	
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$tempServices = $services = $group = array();
		$tempGroupName = '';
		foreach($rows as $row)
		{
			if($tempGroupName == '')
			{
				$tempGroupName = $row['brandName'];
			}
			if($row['brandName'] != $tempGroupName)
			{
				$group[$tempGroupName] = array('BRAND' => $tempGroupName, 'MODELS' => $tempServices);
				$tempServices = array();
				$tempGroupName = $row['brandName'];
			}
			$services = Array(
								'ID' => $row['id'],
								'BRAND' => $row['brandName'],
								'MODEL' => $row['model']
							);
			$tempServices[$row['id']] = $services;
		}
		$group[$tempGroupName] = array('BRAND' => $tempGroupName, 'MODELS' => $tempServices);
		$tempServices = array();
		$tempGroupName = $row['brandName'];
		
		
		$group = Array('MESSAGE' => 'IMEI Service List', 'LIST' => $group);
		$success1[] = $group;
		$result = Array('SUCCESS' => $success1, 'apiversion' => '2.0.0');
	}
	else
	{
		$result = Array('ERROR' => 'No Brand Found', 'apiversion' => '2.0.0');
	}
	
	echo json_encode($result);
?>