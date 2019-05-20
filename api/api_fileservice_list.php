<?php
	require_once("_init.php");
	$req = new request();
	$mysql = new mysql();
	
	$group_id = $req->PostInt('group_id');
	$id = $req->PostInt('id');
	
	$strID = '';
	$strID .= ($id != 0) ? (' id=' . $id . ' and ') : '';
	
	//$member = new member_api($api_key);
	
	/* Get active package if any */
	$package_id = 0;
	$sql = 'select * from ' . PACKAGE_USERS . ' where user_id=' . $member->getUserId();
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$package_id = $rows[0]['package_id'];
	}
	
	
	$sql = 'select
									fm.*,
									fsc.credits as splCr,
									pm.credits as packageCr
								from ' . FILE_SERVICE_MASTER . ' fm 
								left join ' . FILE_SPL_CREDITS . ' fsc on (fm.id = fsc.service_id and fsc.user_id = ' . $member->getUserId() . ')
								left join ' . PACKAGE_FILE_DETAILS . ' pm on(fm.id = pm.file_service_id and pm.package_id='.$package_id.')
							  where fm.status=1 and
								fm.id not in (
												select distinct(service_id) from ' . FILE_SERVICE_USERS . ' where service_id not in(
														select distinct(service_id) from ' . FILE_SERVICE_USERS . ' where user_id = ' . $member->getUserId() . ')
											)';

	$sql = 'select
					fsm.*,
					fsad.amount,
					fssc.amount splCr,
					pfm.amount as packageCr,
					cm.prefix, cm.suffix
				from ' . FILE_SERVICE_MASTER . ' fsm
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
				left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' fsad on(fsad.service_id=fsm.id and fsad.currency_id = ' . $member->getCurrencyID() . ')
				left join ' . FILE_SPL_CREDITS . ' fssc on(fssc.user_id = ' . $member->getUserID() . ' and fssc.service_id=fsm.id)
				left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserID() . ')
				left join ' . PACKAGE_FILE_DETAILS . ' pfm on(pfm.package_id = pu.package_id and pfm.currency_id = ' . $member->getCurrencyID() . ' and pfm.service_id = fsm.id)
				order by fsm.service_name';
	
	$query = $mysql->query($sql);
	$rows = $mysql->fetchArray($query);
	$opt = '';
	if($mysql->rowCount($query) > 0)
	{
		$group_name = 'Defalt Group';
		foreach($rows as $row)
		{
			
			$prefix = $row['prefix'];
			$suffix = $row['suffix'];
			$amount = $mysql->getFloat($row['amount']);
			$amountSpl = $mysql->getFloat($row['splCr']);
			$amountPackage = $mysql->getFloat($row['packageCr']);
			$amountDisplay = $amountDisplayOld = $amount;

			$isSpl = false;
			if($amountSpl > 0){
				$isSpl = true;
				$amountDisplay = $amountSpl;
			}
			if($amountPackage >	 0){
				$isSpl = true;
				$amountDisplay = $amountPackage;
			}
			
			if($tempGroupName == '')
			{
				$tempGroupName = $group_name;
			}
			if($group_name != $tempGroupName)
			{
				$group[$tempGroupName] = array('GROUPNAME' => $tempGroupName, 'SERVICES' => $tempServices);
				$tempServices = array();
				$tempGroupName = $group_name;
			}
			
			$services = Array(
								'SERVICEID' => $row['id'],
								'SERVICENAME' => $row['service_name'],
								'CREDIT' => $amount,
								'TIME' => $row['delivery_time']
							);
			$tempServices[$row['id']] = $services;
		}
		$group[$group_name] = array('GROUPNAME' => $tempGroupName, 'SERVICES' => $tempServices);
		
		
		$group = Array('MESSAGE' => 'IMEI Service List', 'LIST' => $group);
		//$tempServices = array();
                $success1[] = $group;
		$result = Array('SUCCESS' => $success1, 'apiversion' => '2.0.0');
	}
	else
	{
		$result = Array('ERROR' => 'No tool found', 'apiversion' => '2.0.0');
	}
	
	echo json_encode($result);

?>