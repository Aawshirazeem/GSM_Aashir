<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetUser('user_acc_ip_148143438');


    $id = $request->GetInt('id');

	$sql ='select * from ' . USER_MASTER . ' where id=' . $mysql->getInt($member->getUserId());
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
?>
<form action="<?php echo CONFIG_PATH_SITE_USER; ?>account_ip_settings_process.do" method="post">
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ip_settings')); ?></h1>
	<div class="btn-group">
		<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_ranges')); ?>" />
	</div>
</div>


	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ip_settings')); ?></div>
				<div class="panel-body">
					<input type="hidden"  name="username"  value="<?php echo $row['username'];?>" maxlength="15" style="width:137px" />
					<input type="hidden"  name="email"  value="<?php echo $row['email'];?>" maxlength="15" style="width:137px" />
					<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_login_ip_range_help_you_to_protect_your_account_from_hackers')); ?></p>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ip_range_1')); ?></label>
						<input type="text"  class="form-control" name="ip1a" id="ip1a" value="<?php echo $row['ip1a'];?>" maxlength="15" style="width:137px" /> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_to')); ?>
						<input type="text"  class="form-control" name="ip1b" id="ip1b" value="<?php echo $row['ip1b'];?>" maxlength="15" style="width:137px" />
					</div>
					<div class="form-group">
					 	<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ip_range_2')); ?></label>
						<input type="text" class="form-control" name="ip2a" id="ip2a" value="<?php echo $row['ip2a'];?>" maxlength="15" style="width:137px" /> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_to')); ?>
						<input type="text"  class="form-control" name="ip2b" id="ip2b" value="<?php echo $row['ip2b'];?>" maxlength="15" style="width:137px" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ip_range_3')); ?></label>
						<input type="text" class="form-control" name="ip3a" id="ip3a" value="<?php echo $row['ip3a'];?>" maxlength="15" style="width:137px" /> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_to')); ?>
						<input type="text"  class="form-control" name="ip3b" id="ip3b" value="<?php echo $row['ip3b'];?>" maxlength="15" style="width:137px" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ip_range_4')); ?></label>
						<input type="text"  class="form-control" name="ip4a" id="ip4a" value="<?php echo $row['ip4a'];?>" maxlength="15" style="width:137px" /> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_to')); ?>
						<input type="text"  class="form-control" name="ip4b" id="ip4b" value="<?php echo $row['ip4b'];?>" maxlength="15" style="width:137px" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ip_range_5')); ?></label>
						<input type="text"  class="form-control" name="ip5a" id="ip5a" value="<?php echo $row['ip5a'];?>" maxlength="15" style="width:137px" /> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_to')); ?>
						<input type="text"  class="form-control" name="ip5b" id="ip5b" value="<?php echo $row['ip5b'];?>" maxlength="15" style="width:137px" />
					</div> 
				</div> <!-- / panel-body -->
				<div class="panel-footer">
					<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_ranges')); ?>" />
				</div> <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
  </form>
