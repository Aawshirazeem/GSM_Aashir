<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
?>
<div class="row">
	<div class="col-sm-10 col-sm-offset-1">
		<div class="panel">	
			<div class="panel-heading">
				<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_duplicate_IMEI')); ?>
			</div>
			<table class="table table-striped table-hover">
				<tr>
					<th width="16"></th>
					<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei')); ?></th>
					<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_unlock_code')); ?></th>
					<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_service')); ?> </th>
					<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date_time')); ?> </th>
					<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?> </th>
					<th width="16"></th>
				</tr>
				<?php
					$sql = '
							select
								imei
							from nxt_order_imei_master
							where status=0 or status=1 or status=2
							group by imei having count(id) > 1';
					$query = $mysql->query($sql);
					$rows = $mysql->fetchArray($query);
					$imeisDup = '';
					foreach($rows as $row)
					{
						$imeisDup .= $row['imei'] . ', ';
					}
					if($imeisDup == '')
					{
						echo 'No IMEI Found';
						exit();
					}
					else
					{
						$imeisDup = trim($imeisDup, ', ');
						$imeisDup = '  im.imei in (' . $imeisDup . ') ';
					}
		
					$sql = 'select
									im.*, im.id as imeiID,
									im.api_name, im.message,
									im2.status as status2, im2.reply as reply2,
									DATE_FORMAT(im.date_time, "%d-%b-%Y %k:%i") as dtDateTime,
									DATE_FORMAT(im.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,
									tm.tool_name as tool_name, 
									tm.tool_alias as tool_alias
								from ' . ORDER_IMEI_MASTER . ' im
								left join ' . ORDER_IMEI_MASTER . ' im2 on (im.imei = im2.imei)
								left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
								where ' . $imeisDup . ' and (im.status=0 or im.status=1)';
		
		
					$query = $mysql->query($sql);
					$i=0;
					$totalRows = $mysql->rowCount($query);
		
					if($totalRows > 0)
					{
						$rows = $mysql->fetchArray($query);
						$tempIMEI = '';
						$code = $graphics->random_color();
						$codeInverse = $graphics->inverseHex($code);

						foreach($rows as $row)
						{
							$i++;

							if($tempIMEI != $row['imei']){
								$code = $graphics->random_color();
								//$code = '00FF00';
								$codeInverse = $graphics->inverseHex($code);
								$tempIMEI = $row['imei'];
							}

							echo '<tr>';
							echo '<td class="text_center">' . $i . '</td>';
							echo '<td style="background-color:#' . $code . ';color:#' . $codeInverse . '">' . $row['imei'] . '</td>';
							echo '<td>' . base64_decode($row['reply']) . '' . base64_decode($row['reply2']) . '</td>';
							echo '<td>' . $mysql->prints($row['tool_name']) . '</td>';
							echo '<td><small>' . $row['dtDateTime'] . '<br /><b>' . $row['dtReplyDateTime'] . '</b></small></td>';
							echo '<td><b>' . $row['credits'] . '</b>';
							echo '<td class="text-right">';
								$status = 0;
								if($row['status'] != $row['status2'])
								{
									$status = $row['status2'];
								}
								else
								{
									$status = $row['status'];
								}
								switch($status)
								{
									case 0:
										echo '<span class="label label-default">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_pending')). '</span>';
										break;
									case 1:
										echo '<span class="label label-primary">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_locked')). '</span>';
										break;
									case 2:
										echo '<span class="label label-success">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_available')). '</span>';
										break;
									case 3:
										echo '<span class="label label-danger">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_unavailable')). '</span>';
										break;
								}
							echo '</td>';
							if($row['credits_purchase'] != 0)
							{
								echo ' - <small>' . $row['credits_purchase'] . '</small>';
							}
							echo '</td>';
							echo '</tr>';
						}
					}
					else
					{
						echo '<tr><td colspan="20" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_no_record_found')) . '</td></tr>';
					}
				?>
			</table>
		</div>
	</div>
<div>
