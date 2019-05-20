<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$type = $request->getStr('type');
	
	$package_id = 0;
	$sql = 'select * from ' . PACKAGE_USERS . ' where user_id=' . $member->getUserId();
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$package_id = $rows[0]['package_id'];
	}
	$crM = $objCredits->getMemberCredits();
	$prefix = $crM['prefix'];
	$suffix = $crM['suffix'];
	$rate = $crM['rate'];
?>

<div class="panel">
	<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_log_status')); ?></div>
	<table class="table table-striped table-hover">
		<tr>
			<th width="60"></th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_prepaid_log')); ?></th>
			<th class="text-right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_price')); ?></th>
			<th width="50" class="text_center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?></th>
		</tr>
		<?php
			$sql = 'select
							plm.*,
							plgm.group_name,
							plad.amount,
							plsc.amount splCr,
							pplm.amount as packageCr,
							cm.prefix, cm.suffix
						from ' . PREPAID_LOG_MASTER . ' plm
						left join ' . PREPAID_LOG_GROUP_MASTER . ' plgm on(plm.group_id = plgm.id)
						left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
						left join ' . PREPAID_LOG_AMOUNT_DETAILS . ' plad on(plad.log_id=plm.id and plad.currency_id = ' . $member->getCurrencyID() . ')
						left join ' . PREPAID_LOG_SPL_CREDITS . ' plsc on(plsc.user_id = ' . $member->getUserID() . ' and plsc.log_id=plm.id)
						left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserID() . ')
						left join ' . PACKAGE_PREPAID_LOG_DETAILS . ' pplm on(pplm.package_id = pu.package_id and pplm.currency_id = ' . $member->getCurrencyID() . ' and pplm.log_id = plm.id)
						order by plgm.group_name, plm.prepaid_log_name';

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
						echo '<tr><th colspan="5">' . $row['group_name'] . '</th></tr>';
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
					echo '<td>' . $row['prepaid_log_name'] . '</td>';
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