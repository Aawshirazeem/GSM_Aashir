<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$crM = $objCredits->getMemberCredits();
	
		$pendingIMEIS = $pendingFiles = $pendingServerLogs = '-';
		if($service_imei == "1")
		{
			$sqlCount = 'select count(id) as total from ' . ORDER_IMEI_MASTER . ' where user_id=' . $mysql->getInt($member->getUserId()) . ' and (status=0 or status=1)';
			$queryCount = $mysql->query($sqlCount);
			$rowsCount = $mysql->fetchArray($queryCount);
			$pendingIMEIS = ($rowsCount[0]['total'] > 0) ? $rowsCount[0]['total'] : '--';
		}
		if($service_file == "1")
		{
			$sqlCount = 'select count(id) as total from ' . ORDER_FILE_SERVICE_MASTER . ' where user_id=' . $mysql->getInt($member->getUserId()) . ' and status=0';
			$queryCount = $mysql->query($sqlCount);
			$rowsCount = $mysql->fetchArray($queryCount);
			$pendingFiles = ($rowsCount[0]['total'] > 0) ? $rowsCount[0]['total'] : '--';
		}
		if($service_logs == "1")
		{
			$sqlCount = 'select count(id) as total from ' . ORDER_SERVER_LOG_MASTER . ' where user_id=' . $mysql->getInt($member->getUserId()) . ' and status=0';
			$queryCount = $mysql->query($sqlCount);
			$rowsCount = $mysql->fetchArray($queryCount);
			$pendingServerLogs = ($rowsCount[0]['total'] > 0) ? $rowsCount[0]['total'] : '--';
		}
?>

<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_welcome')); ?>, <?php echo $member->getFullName();?>!</h1>

<div class="row dashboard_circles">
	<?php
		if($service_imei == "1")
		{
			echo '<div class="col-md-3"><a href="#" class="item1 item"><i class="icon-lock icon-large"></i><span class="total">' . $pendingIMEIS . '</span><p class="header">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_pending_imei_jobs')) . '</p></a></div>';
		}
		if($service_file == "1")
		{
			echo '<div class="col-md-3"><a href="#" class="item2 item"><i class="icon-file icon-large"></i><span class="total">' . $pendingFiles . '</span><p class="header">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_pending_file_service_jobs')) . '</p></a></div>';
		}
		if($service_logs == "1")
		{
			echo '<div class="col-md-3"><a href="#" class="item3 item"><i class="icon-shield icon-large"></i><span class="total">' . $pendingServerLogs . '</span><p class="header">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_server_credits_orders')) . '</p></a></div>';
		}
	?>
	<div class="col-md-3"><a href="#" class="item4 item"><i class="icon-money icon-large"></i><span class="total"><?php echo $crM['credits'];?></span><p class="header"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></p></a></div>
</div>


<div class="clear"></div>




<table class="MT5">
	<tr valign="top">
		<td width="25%">
			<h3><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_trans')); ?>
			</h3>
			<table class="MT5 table table-striped table-hover panel">
				<tr>
					<th width="10"></th>
					<th class="TA_C"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_date')); ?> </th>
					<th class="TA_C"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?> </th>
					<th class="TA_C"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?> </th>
				</tr>
				<?php
					$sql = 'select
								ctm.date_time, ctm.credits, ctm.views, 
								um.username as username1, 
								um2.username as username2
							from ' . CREDIT_TRANSECTION_MASTER . ' ctm
							left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)
							left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)
							where um.id='.$member->getUserId().'
							order by ctm.id DESC
							limit 10';
					$query = $mysql->query($sql);
					if($mysql->rowCount($query) > 0)
					{
						$transs = $mysql->fetchArray($query);
						foreach($transs as $trans)
						{
				?>
							<tr <?php echo ($trans['views'] < 5) ? 'style="color:red;font-weight:bold"' : '';?>>
								<td><?php if($trans['username2'] != ''){ echo $graphics->icon('back'); } ?></td>
								<td><?php echo date("d-M Y H:i", strtotime($trans['date_time']));?></td>
								<td><?php echo $trans['username1']; echo $trans['username2'];?></td>
								<td class="TA_R"><?php echo $trans['credits'];?> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cr')); ?> </td>
							</tr>
				<?php
						}
					}
					else
					{
				?>
				<tr>
					<td colspan="" class="TA_R"><?php echo $rowCount['rejectedServerLogs'];?></td>
				</tr>
				<?php
					}
					// Update Last viewed credtis
					$sql = 'update ' . CREDIT_TRANSECTION_MASTER . ' set views=(views+1)
							where 
									user_id='.$member->getUserId().' or user_id2='.$member->getUserId().'
									and views < 5';
					$query = $mysql->query($sql);
				?>
			</table>
			
			
			<?php

				
				$sql_total = 'select count(id) as total from ' . USER_MASTER;
				$query_total = $mysql->query($sql_total);
				$rows_total = $mysql->fetchArray($query_total);
				$totalUsers = $rows_total[0]['total'];
			?>

			
			
		</td>
		<!--
			Row #3
		-->
		<td width="25%">
			<h3><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_login_failer')); ?></h3>
			<?php
			$sql_lf = 'select stu.username, stu.ip, stu.date_time
						from ' . STATS_USER_LOGIN_MASTER . ' stu 
						left join '.USER_MASTER.' um on (um.username=stu.username)
						where stu.success=0 and um.id='.$member->getUserId().' order by stu.id DESC limit 10';
			$query_lf = $mysql->query($sql_lf);
			if($mysql->rowCount($query_lf) > 0)
			{
			?>
				<table class="MT5 table table-striped table-hover panel">
					<tr>
						<th class="TA_R" width="16"></th>
						<th class="TA_L"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?> </th>
						<th class="TA_L"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_ip')); ?> </th>
					</tr>
					<?php
						$rows_lf = $mysql->fetchArray($query_lf);
						$i = 1;
						foreach($rows_lf as $row_lf)
						{
					?>
						<tr>
							<td class="TA_R"><?php echo $i++; ?></td>
							<td><?php echo $mysql->prints($row_lf['username']); ?></td>
							<td><?php echo $row_lf['ip']; ?></td>
						</tr>
					<?php
						}
					?>
				</table>
			<?php
			}
			?>
		
		</td>
	</tr>
</table>






