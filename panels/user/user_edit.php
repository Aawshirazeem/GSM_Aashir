<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetUser('user_edit_64565646428');

	$id = $request->GetInt('id');

	$sql ='select * from ' . USER_MASTER . ' where id=' . $mysql->getInt($id) . ' and reseller_id=' . $mysql->getInt($member->getUserId());
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	if($rowCount == 0)
	{
		header("location:" . CONFIG_PATH_SITE_USER . "users.html?reply=" . urlencode('reply_invalid_login'));
		exit();
	}
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	
?>
<div class="row">
		<div class="col-lg-12">
			<ul class="breadcrumb">
				<li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Dashboard')); ?></a></li>
                                <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>users.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Users')); ?></a></li>
				<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_User_Edit')); ?></li>
			</ul>
		</div>
	</div>
<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_user')); ?></h1>
  <form action="<?php echo CONFIG_PATH_SITE_USER; ?>user_edit_process.do" method="post" name="frm_customers_edit" id="frm_customers_edit" class="formSkin">
		<div class="row">
			<div class="col-lg-6">
				<div class="panel">
					<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_login_details')); ?></div>
					<div class="panel-body">
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_name')); ?></label>
							<input name="username" type="text" readonly class="form-control" id="username" value="<?php echo $row['username']?>" />
							<input name="id" type="hidden" id="id" value="<?php echo $row['id']?>" />
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_password')); ?></label>
							<input name="password" type="text" class="form-control" id="password" />
							<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_password_for_the_above_login_email')); ?></p>
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email')); ?></label>
							<input name="email" type="text" readonly class="form-control" id="email" value="<?php echo $row['email']?>" />
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_access')); ?></label>
							<input type="radio" name="service_imei" value="1" <?php echo (($row['service_imei'] == '1') ? 'checked="checked"' : '');?> > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?>
							<input type="radio" name="service_imei" value="0" <?php echo (($row['service_imei'] == '0') ? 'checked="checked"' : '');?> > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?>
							<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_is_user_can_access_imei_unlocking_services?')); ?></p>
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_system_access')); ?></label>
							<input type="radio" name="service_file" value="1" <?php echo (($row['service_file'] == '1') ? 'checked="checked"' : '');?> > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?>
							<input type="radio" name="service_file" value="0" <?php echo (($row['service_file'] == '0') ? 'checked="checked"' : '');?> > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?>
							<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_is_user_can_access_file_services?')); ?></p>
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs_access')); ?></label>
							<input type="radio" name="service_logs" value="1" <?php echo (($row['service_logs'] == '1') ? 'checked="checked"' : '');?> > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?>
							<input type="radio" name="service_logs" value="0" <?php echo (($row['service_logs'] == '0') ? 'checked="checked"' : '');?> > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?>
							<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_is_user_can_access_server_logs?')); ?></p>
						</div>
<!--						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_online_store_access')); ?></label>
							<input type="radio" name="service_shop" value="1" <?php echo (($row['service_shop'] == '1') ? 'checked="checked"' : '');?> > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?>
							<input type="radio" name="service_shop" value="0" <?php echo (($row['service_shop'] == '0') ? 'checked="checked"' : '');?> > <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?>
							<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_is_user_can_purchase_any_services/products_from_online_store?')); ?></p>
						</div>-->
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_status')); ?></label>
							<input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : '');?> > <i style="color:#006600"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></i>
							<input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : '');?> > <i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></i>
						</div>
					</div> <!-- / panel-body -->
				</div> <!-- / panel -->
				<a href="<?php echo CONFIG_PATH_SITE_USER;?>users.html" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_back_to_users')); ?></a>
				<button type="submit" class="btn btn-success"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_account')); ?></button>
			</div> <!-- / col-lg-6 -->
			
			
			<div class="col-lg-6">
				<div class="panel">
					<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_other_details_[optional]')); ?></div>
					<div class="panel-body">
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_first_name')); ?></label>
							<input name="first_name" type="text" class="form-control" id="first_name" value="<?php echo $row['first_name']?>" />
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_last_name')); ?></label>
							<input name="last_name" type="text" class="form-control" id="last_name" value="<?php echo $row['last_name']?>" />
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_company')); ?></label>
							<input name="company" type="text" class="form-control" id="company" value="<?php echo $row['company']?>" />
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_address')); ?></label>
							<textarea name="address" class="form-control" id="address" rows="4"><?php echo $row['address']?></textarea>
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_city')); ?></label>
							<input name="city" type="text" class="form-control" id="city" value="<?php echo $row['city']?>" />
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_language')); ?></label>
							<select name="language" class="form-control" id="language">
							<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_language')); ?></option>
							<?php
                                                      //  echo $row['language_id'];
								$sql_language = 'select * from ' . LANGUAGE_MASTER . ' order by id';
								$query_language = $mysql->query($sql_language);
								$rows_language = $mysql->fetchArray($query_language);
								foreach($rows_language as $row_language)
								{
									echo '<option ' . (($row_language['id'] == $row['language_id']) ? 'selected="selected"' : '') . ' value="' . $row_language['id'] . '">' . $row_language['language'] . '</option>';
								}
							?>
							</select>
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_time_zone')); ?></label>
							<select name="timezone" class="form-control" id="timezone">
							<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_time_zone')); ?></option>
							<?php
								$sql_timezone = 'select * from ' . TIMEZONE_MASTER . ' order by timezone';
								$query_timezone = $mysql->query($sql_timezone);
								$rows_timezone = $mysql->fetchArray($query_timezone);
								foreach($rows_timezone as $row_timezone)
								{
									echo '<option ' . (($row_timezone['id'] == $row['timezone_id']) ? 'selected="selected"' : '') . ' value="' . $row_timezone['id'] . '">' . $row_timezone['timezone'] . '</option>';
								}
                                                             
							?>
							</select>
						</div>
						<div class="form-group">
                                                    
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?></label>
							<select name="country" class="form-control" id="country">
							<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_country')); ?></option>
							<?php
								$sql_country = 'select * from ' . COUNTRY_MASTER . ' order by countries_name';
                                                               
                                                                $query_country = $mysql->query($sql_country);
								$rows_country = $mysql->fetchArray($query_country);
                                                            
								foreach($rows_country as $row_country)
								{
									echo '<option ' . (($row_country['id'] == $row['country_id']) ? 'selected="selected"' : '') . ' value="' . $row_country['id'] . '">' . $row_country['countries_name'] . '</option>';
								}
							?>
							</select>
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_phone')); ?></label>
							<input name="phone" type="text" class="form-control" id="phone" value="<?php echo $row['phone']?>" />
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_mobile')); ?></label>
							<input name="mobile" type="text" class="form-control" id="mobile" value="<?php echo $row['mobile']?>" />
						</div>
					</div> <!-- / panel-body -->
				</div> <!-- / panel -->
			</div> <!-- / col-lg-6 -->
		</div>
  </form>
