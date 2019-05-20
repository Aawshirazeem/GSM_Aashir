<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
?>

	
<div class="panel">
	<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_status')); ?></div>
	<table class="table table-striped table-hover">
		<tr>
			<th width="16"></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_unlocking_tool')); ?></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_delivery_time')); ?></th>
			<th class="text-right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_price')); ?></th>
			<th width="16" class="text_center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?></th>
		</tr>
		<?php


			$sql = 'select
							tm.*,
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
						where tm.visible=1
						order by igm.group_name';

			$query = $mysql->query($sql);
			
			$i = 0;
			$groupName = "";
			if($mysql->rowCount($query) > 0)
			{
				$rows = $mysql->fetchArray($query);
				foreach($rows as $row)
				{
					$i++;
					if($groupName != $row['group_name'])
					{
						echo '<tr><th colspan="5"><b>' . $row['group_name'] . '</b></th></tr>';
						$groupName = $row['group_name'];
					}

					$prefix = $row['prefix'];
					$suffix = $row['suffix'];
					$amount = $mysql->getFloat($row['amount']);
					$amountSpl = $mysql->getFloat($row['splCr']);
					$amountPackage = $mysql->getFloat($row['packageCr']);
					$amountDisplay = $amountDisplayOld = $amount;

					$isSpl = false;
					if($amountPackage >	 0){
						$isSpl = true;
						$amountDisplay = $amountPackage;
					}
					if($amountSpl > 0){
						$isSpl = true;
						$amountDisplay = $amountSpl;
					}

					
					echo '<tr>';
					echo '<td>' . $i . '</td>';
					echo '<td><b>' . $row['tool_name'] . '</b></td>';
					echo '<td>' . $row['delivery_time'] . '</td>';
					if($isSpl == true){
						echo '<td class="text-right">
									<b>' . $objCredits->printCredits($amountDisplay, $prefix, $suffix) . '</b>
									<br /><small class="text-danger"><del>' . $objCredits->printCredits($amountDisplayOld, $prefix, $suffix) . '</del></small>
								</td>';
					} else {
						echo '<td class="text-right"><b>' . $objCredits->printCredits($amountDisplay, $prefix, $suffix) . '</b></td>';

					}
					echo '<td class="text-center">' . $graphics->status($row['status']) . '</td>';
					echo '</tr>';
				} 
			}
			else
			{
				echo '<tr><td colspan="6" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '!</td></tr>';
			}
		?>
	</table>
</div>