<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetUser('user_pre_log_14d32233');

	/* Get package id for the user */
	$package_id = 0;
	$sql = 'select * from ' . PACKAGE_USERS . ' where user_id=' . $member->getUserId();
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$package_id = $rows[0]['package_id'];
	}
	

	if($service_prepaid == "0")
	{
		echo "<h1>You are authorize to view this page!</h1>";
		return;
	}
	$crM = $objCredits->getMemberCredits();
	$prefix = $crM['prefix'];
	$suffix = $crM['suffix'];
	$rate = $crM['rate'];
?>
<form action="<?php echo CONFIG_PATH_SITE_USER;?>prepaid_logs_submit_process.do" method="post">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_new_prepaid_log')); ?></div>
				<div class="panel-body">
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_prepaid_log')); ?>
							 <div class="hidden pull-right ML10" id="loadIndTool"><i class="icon-refresh icon-spin"></i></div>
						</label>
						<select name="prepaid_log" class="form-control" id="prepaid_log">
							<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_prepaid_log')); ?></option>
							<?php
									$sql = 'select
												slm.*,
												slsc.credits as splCr,
												pd.credits as packageCr,
												slgm.group_name
										from ' . PREPAID_LOG_MASTER . ' slm
										left join ' . PREPAID_LOG_GROUP_MASTER . ' slgm on (slm.group_id=slgm.id)
										left join ' . PREPAID_LOG_SPL_CREDITS . ' slsc on (slm.id = slsc.log_id and slsc.user_id = ' . $member->getUserId() . ')
										left join ' . PACKAGE_PREPAID_LOG_DETAILS . ' pd on(slm.id = pd.prepaid_log_id and pd.package_id='.$package_id.')
										where slm.status=1 and slgm.status=1 and
															slm.id not in (
																			select distinct(prepaid_log_id) from ' . PREPAID_LOG_USERS . ' where prepaid_log_id not in(
																					select distinct(prepaid_log_id) from ' . PREPAID_LOG_USERS . ' where user_id = ' . $member->getUserId() . ')
																		)
										order by slgm.display_order';
										
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
								$rows = $mysql->fetchArray($query);
								$tempGroupName = "";
								$tempGroupID = 0;
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


									if($row['group_name'] != $tempGroupName)
									{
										echo ($tempGroupID == 0) ? '</optgroup>' : '';
										echo '<optgroup label="' . $row['group_name'] . '">';
										$tempGroupName = $row['group_name'];
										$tempGroupID++;
									}
									
									echo '<option ' . (($isSpl == true) ? 'style="color:#FF7000"' : '') . ' value="' . $row['id'] . '">' . $row['prepaid_log_name'] . ' [' . $objCredits->printCredits($amountDisplay, $prefix, $suffix) . ']</option>';
								}
								echo '</optgroup>';
							?>
						</select>
					</div>
					
					<div id="toolDetails" class="hidden"></div>

					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_comments')); ?></label>
						<input name="remarks" id="remarks" type="text" class="form-control" value="" />
					</div>
				</div> <!-- / panel-body -->
				<div class="panel-footer">
					<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_submit_&_continue')); ?>" class="btn btn-success"/>
				</div> <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
</form>
