<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('server_logs_spl_cr_user_list_789297341255d2');
	
	$id=$request->getInt('id');
	
	$sql = 'select
				um.id, um.username, um.currency_id,
				slad.amount,
				slsc.amount spl_amount,
				cm.currency, cm.prefix, cm.suffix
			from ' . USER_MASTER . ' um 
			left join ' . SERVER_LOG_SPL_CREDITS . ' slsc on(um.id=slsc.user_id and slsc.log_id=' . $id. ')
			left join ' . CURRENCY_MASTER . ' cm on(um.currency_id=cm.id)
			left join ' . SERVER_LOG_AMOUNT_DETAILS . ' slad on(slad.log_id=' . $id. ' and um.currency_id = slad.currency_id)
			where um.reseller_id = 0 
                        order by um.username ';	
	$result = $mysql->getResult($sql);
	$result = $mysql->getResult($sql);
	$query = $mysql->query($sql);
	$i = 1;
?>
	<ul class="breadcrumb">
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
		<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs')); ?></a></li>
		<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_special_credit_user')); ?></li>
	</ul>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs_spl_cr_user_process.do" method="post">
	
	
		<input type="hidden" name="id" value="<?php echo $id;?>" >
	<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<section class="panel MT10">
					<table class="table table-hover table-striped">
						<div class="panel-heading">
							<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_special_credit_user')); ?>
						</div>
						<tr>
							  <th width="16"></th>
							  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?></th>
							  <th width="160"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_org._cr.')); ?></th>
							  <th width="160"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_special_credits')); ?></th>
							  <th width="60"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_remove')); ?></th>
						</tr>
					<?php
						if($mysql->rowCount($query) > 0)
						{
							$rows = $mysql->fetchArray($query);
							foreach($rows as $row)
							{
								echo '<tr class="' . (($row['spl_amount'] != '') ? 'danger' : '') . '">';
								echo '<td>' . $i++ . '</td>';
								echo '<td><b>' . $row['username'] . '</b></td>';
								echo '<td>' . $objCredits->printCredits($row['amount'], $row['prefix'], $row['suffix']) . '</td>';
								echo '<td>
										<div class="input-group">
											<span class="input-group-addon">' . $row['prefix'] . '</span>
											<input type="text" class="form-control ' . (($row['spl_amount'] != '') ? 'textbox_highlight'. (($row['spl_amount'] > $row['amount']) ? 'R noEffect' : '') : '') . '" style="width:80px" name="spl_' . $row['id'] . '" value="' . $row['spl_amount'] . '" />
										</div>
									  </td>';
								echo '<td class="text_right">
										<input type="checkbox"  name="check_user[]" ' . ' value="' . $row['id'] .	'"/>
										<input type="hidden" name="user_ids[]" value="' . $row['id'] . '" />
										<input type="hidden" name="currency_id' . $row['id'] . '" value="' . $row['currency_id'] . '" />
									</td>';
								echo '</tr>';
							}
						}
						else
						{
							echo '<tr><td colspan="6" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
						}
					?>
				</table>
			</section>
				<div class="form-group">
					<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>server_logs.html" class="btn btn-danger btn-sm"> <i class="icon-arrow-left"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?></a>
					<input type="submit" name="update" value="Update" class="btn btn-success btn-sm" >
				</div>
		</div>
	</div>
	</form>
</div>
