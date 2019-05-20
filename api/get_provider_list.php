<?php
	require_once("_init.php");
	$req = new request();
	$mysql = new mysql();
	$xml = new xml();
	
	$api_key = $req->PostStr('api_key');
	
	$sql='select
					inm.id,inm.network,ic.countries_name as country
				from ' . IMEI_NETWORK_MASTER . ' inm
				left join '.COUNTRY_MASTER.' ic on(inm.country=ic.id)
				order by ic.countries_name';
	
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
				$tempGroupName = $row['country'];
			}
			if($row['country'] != $tempGroupName)
			{
				$group[$tempGroupName] = array('COUNTRY' => $tempGroupName, 'NETWORKS' => $tempServices);
				$tempServices = array();
				$tempGroupName = $row['country'];
			}
			$services = Array(
								'ID' => $row['id'],
								'COUNTRY' => $row['country'],
								'NETWORK' => $row['network']
							);
			$tempServices[$row['id']] = $services;
		}
		$group[$tempGroupName] = array('COUNTRY' => $tempGroupName, 'NETWORKS' => $tempServices);
		$tempServices = array();
		$tempGroupName = $row['country'];
		
		
		$group = Array('MESSAGE' => 'Provider List', 'LIST' => $group);
		$success1[] = $group;
		$result = Array('SUCCESS' => $success1, 'apiversion' => '2.0.0');
	}
	else
	{
		$result = Array('ERROR' => 'No Provider Found', 'apiversion' => '2.0.0');
	}
	
	echo json_encode($result);
?>