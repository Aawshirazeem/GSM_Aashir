<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('services_imei_tools_spl_cr_user_list_789971255d2');
	
	$log_id=$request->getInt('id');
	
	$sql = 'select
				um.id, um.username, um.currency_id,
				itad.amount,
				isc.amount spl_amount,
				cm.currency, cm.prefix, cm.suffix
			from ' . USER_MASTER . ' um 
			left join ' . PREPAID_LOG_SPL_CREDITS . ' isc on(um.id=isc.user_id and isc.log_id=' . $log_id. ')
			left join ' . CURRENCY_MASTER . ' cm on(um.currency_id=cm.id)
			left join ' . PREPAID_LOG_AMOUNT_DETAILS . ' itad on(itad.log_id=' . $log_id. ' and um.currency_id = itad.currency_id)
			where um.reseller_id = 0 
                        order by um.username ';	
	$result = $mysql->getResult($sql);
	$i = 1;
?>
<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
			<li class="active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_logs')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_edit_sp_credit')); ?></li>
		</ul>
	</div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs_spl_cr_user_process.do" enctype="multipart/form-data" method="post" name="frm_inquiry" id="frm_inquiry">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_edit_sp_credit')); ?></div>
					<input type="hidden" name="id" value="<?php echo $log_id;?>" >
					<table class="table table-striped table-hover">
						<tr>
							  <th></th>
							  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?></th>
							  <th width="160"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_special_credits')); ?></th>
							  <th width="160"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_special_credits')); ?></th>
							  <th width="16"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_remove')); ?></th>
						</tr>
					<?php
						if($result['COUNT'])
						{
							foreach($result['RESULT'] as $row)
							{
								echo '<tr class="' . (($row['spl_amount'] != '') ? 'success' : '') . '">';
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
		



				<div class="panel-footer">
					<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_tools.html" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
					<input type="submit" name="update" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update')); ?>" class="btn btn-success" >
				</div> <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->



	</form>