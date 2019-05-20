<?php
	require_once("_init.php");
	$req = new request();
	$mysql = new mysql();
	
	$result = array();
	
	$sql='select
					imm.id,imm.mep,
					ig.mep_group 
				from ' . IMEI_MEP_MASTER . ' imm
				left join '.IMEI_MEP_GROUP_MASTER.' ig on(imm.mep_group_id=ig.id)
				order by ig.mep_group';
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
				$tempGroupName = $row['mep_group'];
			}
			if($row['mep_group'] != $tempGroupName)
			{
				$group[$tempGroupName] = array('GROUPNAME' => $tempGroupName, 'SERVICES' => $tempServices);
				$tempServices = array();
				$tempGroupName = $row['mep_group'];
			}
			$services = Array(
								'ID' => $row['id'],
								'GROUP' => $row['mep_group'],
								'MEP' => $row['mep']
							);
			$tempServices[$row['id']] = $services;
		}
		$group[$tempGroupName] = array('GROUPNAME' => $tempGroupName, 'SERVICES' => $tempServices);
		$tempServices = array();
		$tempGroupName = $row['mep_group'];
		
		
		$group = Array('MESSAGE' => 'IMEI Service List', 'LIST' => $group);
		$success1[] = $group;
		$result = Array('SUCCESS' => $success1, 'apiversion' => '2.0.0');
	}
	else
	{
		$result = Array('ERROR' => 'No MEP Found', 'apiversion' => '2.0.0');
	}
	
	echo json_encode($result);
?>