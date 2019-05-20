<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
?>


<div class="panel">
	<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_status')); ?></div>
	<table class="table table-striped table-hover">
		<tr>
			<th width="60"></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service')); ?></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_delivery_time')); ?></th>
			<th class="text-right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_price')); ?></th>
			<th width="50" class="text_center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?></th>
		</tr>
		<?php

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
			
			$i = 0;
			if($mysql->rowCount($query) > 0)
			{
				$rows = $mysql->fetchArray($query);
				foreach($rows as $row)
				{
					$i++;
					
					
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
					echo '<td><b>' . $row['service_name'] . '</b></td>';
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
				echo '<tr><td colspan="6" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_no_record_found')) . '!</td></tr>';
			}
		?>
	</table>
</div>