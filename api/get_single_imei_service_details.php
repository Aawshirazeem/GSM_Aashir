<?php
	require_once("_init.php");
	$req = new request();
	$mysql = new mysql();
	
	$group_id = $req->PostInt('group_id');
	$id = $req->PostInt('id');
	
	$strID = '';
	$strID .= ($group_id != 0) ? (' group_id=' . $group_id . ' and ') : '';
	$strID .= ($id != 0) ? (' id=' . $id . ' and ') : '';
	
	$member = new member_api($api_key);
	
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
						tm.id, tm.tool_name, tm.tool_alias, tm.credits, tm.delivery_time, tm.info, tm.verification,
						tm.country_id, tm.brand_id, tm.field_pin, tm.field_kbh, tm.field_mep,  tm.field_prd, tm.field_type,
						gtm.group_name,uscm.credits as splCr,pd.credits as packageCr
					from ' . IMEI_TOOL_MASTER . ' tm
					left join ' . IMEI_GROUP_MASTER . ' gtm on (gtm.id = tm.group_id)
					left join ' . IMEI_SPL_CREDITS . ' uscm on (tm.id = uscm.tool and uscm.user_id = ' . $member->getUserId() . ')
					left join ' . PACKAGE_IMEI_DETAILS . ' pd on(tm.id = pd.tool_id and pd.package_id=' . $package_id . ')
					where ' . $strID . '
						tm.status=1 and
						tm.id not in (
									select distinct(tool_id) from ' . IMEI_TOOL_USERS . ' where tool_id not in(
											select distinct(tool_id) from ' . IMEI_TOOL_USERS . ' where user_id = ' . $member->getUserId() . ')
								)
					order by gtm.group_name, tm.tool_name';

	$sql = 'select
					tm.id as tid, tm.tool_name, tm.delivery_time,
					itad.amount,
					isc.amount splCr,
					pim.amount as packageCr,
					igm.group_name,
					cm.prefix, cm.suffix
				from ' . IMEI_TOOL_MASTER . ' tm
				left join ' . IMEI_GROUP_MASTER . ' igm on(tm.group_id = igm.id)
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
				left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(itad.tool_id=tm.id and itad.currency_id = ' . $member->getCurrencyID() . ')
				left join ' . IMEI_SPL_CREDITS . ' isc on(isc.user_id = ' . $member->getUserId() . ' and isc.tool_id=tm.id)
				left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserId() . ')
				left join ' . PACKAGE_IMEI_DETAILS . ' pim on(pim.package_id = pu.package_id and pim.currency_id = ' . $member->getCurrencyID() . ' and pim.tool_id = tm.id)
				where ' . $strID . ' tm.status=1';
	
	$query = $mysql->query($sql);
	$rows = $mysql->fetchArray($query);
	$opt = '';
	$result = array();
	if($mysql->rowCount($query) > 0)
	{
		$tempServices = $services = $group = array();
		$tempGroupName = '';
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
				$tempGroupName = $row['group_name'];
			}
			if($row['group_name'] != $tempGroupName)
			{
				$group[$tempGroupName] = array('GROUPNAME' => $tempGroupName, 'SERVICES' => $tempServices);
				$tempServices = array();
				$tempGroupName = $row['group_name'];
			}
			
			$services = Array(
								'SERVICEID' => $row['id'],
								'SERVICENAME' => $row['tool_name'],
								'CREDIT' => $cr,
								'TIME' => $row['delivery_time'],
								'INFO' => $row['info'],
								'Requires.Network' => ($row['country_id'] = 0) ? 'None' : 'YES',
								'Requires.Mobile' => ($row['brand_id'] = 0) ? 'None' : 'YES',
								'Requires.Provider' => ($row['country_id'] = 0) ? 'None' : 'YES',
								'Requires.PIN' => $row['field_pin'],
								'Requires.KBH' => $row['field_kbh'],
								'Requires.MEP' => $row['field_mep'],
								'Requires.PRD' => $row['field_prd'],
								'Requires.Type' => $row['field_type'],
								'Requires.Locks' => 'None',
								'Requires.Reference' => 'None'
							);
			$tempServices[$row['id']] = $services;
		}
		//if($row['group_name'] != $tempGroupName)
		{
			$group[$tempGroupName] = array('GROUPNAME' => $tempGroupName, 'SERVICES' => $tempServices);
			$tempServices = array();
			$tempGroupName = $row['group_name'];
		}
		$group = Array('MESSAGE' => 'IMEI Service List', 'LIST' => $group);
		$success1[] = $group;
		$result = Array('SUCCESS' => $success1, 'apiversion' => '2.0.0');
		
	}
	else
	{
		$result = Array('ERROR' => 'No tool found', 'apiversion' => '2.0.0');
	}
	
	
	echo json_encode($result);
?>