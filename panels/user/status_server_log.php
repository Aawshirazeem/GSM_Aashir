<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
?>

<div class="panel">
	<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_status')); ?></div>
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
							slm.*,
							slgm.group_name,
							slad.amount,
							slsc.amount splCr,
							pslm.amount as packageCr,
							cm.prefix, cm.suffix
						from ' . SERVER_LOG_MASTER . ' slm
						left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)
						left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
						left join ' . SERVER_LOG_AMOUNT_DETAILS . ' slad on(slad.log_id=slm.id and slad.currency_id = ' . $member->getCurrencyID() . ')
						left join ' . SERVER_LOG_SPL_CREDITS . ' slsc on(slsc.user_id = ' . $member->getUserID() . ' and slsc.log_id=slm.id)
						left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserID() . ')
						left join ' . PACKAGE_SERVER_LOG_DETAILS . ' pslm on(pslm.package_id = pu.package_id and pslm.currency_id = ' . $member->getCurrencyID() . ' and pslm.log_id = slm.id)
						order by slm.server_log_name';
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
						echo '<tr><td colspan="5"><b>' . $row['group_name'] . '</b></td></tr>';
						$groupName = $row['group_name'];
					}
					
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


					echo '<tr>';
					echo '<td>' . $i . '</td>';
					echo '<td><b>' . $row['server_log_name'] . '</b></td>';
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