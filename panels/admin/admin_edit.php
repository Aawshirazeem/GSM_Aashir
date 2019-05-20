<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetAdmin('suppliers_edit_54964566hh2');

$id = $request->GetInt('id');
$sql = 'select am.*,tz.timezone from ' . ADMIN_MASTER . ' as am
inner join ' . TIMEZONE_MASTER . ' as tz
on am.timezone_id=tz.id
where am.id=' . $mysql->getInt($id);

//$sql ='select * from ' . ADMIN_MASTER . ' where id=' . $mysql->getInt($id);
//  echo $sql;exit;
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
if ($rowCount == 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "admin.html?reply=" . urlencode('reply_invalid_login'));
    exit();
}
$rows = $mysql->fetchArray($query);
$row = $rows[0];
?>
<div class="row">
	<div class="col-xs-9">
    	<h4 class="m-b-20 col-md-12">
        	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_login_details')); ?>
        </h4>
        <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>admin_edit_process.do" method="post">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group col-md-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></label>
                        <input name="username" type="text" readonly class="form-control" id="username" value="<?php echo $mysql->prints($row['username']) ?>" />
                        <input name="id" type="hidden" class="textbox_fix" id="id" value="<?php echo $row['id'] ?>" />
                    </div>
                   
                    <div class="form-group col-md-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Nick')); ?></label>
                        <input name="nick" type="text" class="form-control" id="nick" value="<?php echo $mysql->prints($row['nname']) ?>"  required=""/>
                    </div>
                    <div class="form-group col-md-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_first_name')); ?></label>
                        <input name="fname" type="text" class="form-control" id="fname" value="<?php echo $mysql->prints($row['fname']) ?>" />
                    </div>
                    <div class="form-group col-md-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Last_Name')); ?></label>
                        <input name="lname" type="text" class="form-control" id="lname" value="<?php echo $mysql->prints($row['lname']) ?>" />
                    </div>
                    <div class="form-group col-md-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_phone_number')); ?></label>
                        <input name="pnumber" type="text" class="form-control" id="pnumber" value="<?php echo $mysql->prints($row['pnumber']) ?>"  required=""/>
                    </div>
                    <div class="form-group col-md-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_address')); ?></label>
                        <input name="address" type="text" class="form-control" id="address" value="<?php echo $mysql->prints($row['address']) ?>" />
                    </div>                        
                    <div class="form-group col-md-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?></label>
                        <input name="email" type="text" class="form-control" id="email" value="<?php echo $mysql->prints($row['email']) ?>" required="" />
                        <input type="hidden" name="old_email" value="<?php echo $mysql->prints($row['email']) ?>"/>
                    </div>                        
                    <div class="form-group col-md-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?></label>
                        <select name="country" class="form-control" id="country">
                            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_country')); ?></option>
                            <?php
                            $sql_country = 'select * from ' . COUNTRY_MASTER . ' order by countries_name';
                            $query_country = $mysql->query($sql_country);
                            $rows_country = $mysql->fetchArray($query_country);
                            foreach ($rows_country as $row_country) {
                                echo '<option ' . (($row_country['id'] == $row['country']) ? 'selected="selected"' : '') . ' value="' . $row_country['id'] . '">' . $mysql->prints($row_country['countries_name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
					
					<br style="clear:both">
                        
                    <div class="form-group col-md-6">
                    	<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_time_zone')); ?></label>
                        <select name="timezone" class="form-control" id="timezone">
                        	<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_time_zone')); ?></option>
                            <?php
							$sql_timezone = 'select * from ' . TIMEZONE_MASTER . ' order by timezone';
							$query_timezone = $mysql->query($sql_timezone);
							$rows_timezone = $mysql->fetchArray($query_timezone);
							foreach ($rows_timezone as $row_timezone) {
								echo '<option ' . (($row_timezone['id'] == $row['timezone_id']) ? 'selected="selected"' : '') . ' value="' . $row_timezone['id'] . '">' . $mysql->prints($row_timezone['timezone']) . '</option>';
							}
							?>
                      	</select>
                    </div>
                    <div class="form-group col-md-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_language')); ?></label>
                        <select name="language" class="form-control" id="language">
                            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_language')); ?></option>
                            <?php
                            $sql_language = 'select * from ' . LANGUAGE_MASTER . ' order by id';
                            $query_language = $mysql->query($sql_language);
                            $rows_language = $mysql->fetchArray($query_language);
                            foreach ($rows_language as $row_language) {
                                echo '<option ' . (($row_language['id'] == $row['language_id']) ? 'selected="selected"' : '') . ' value="' . $row_language['id'] . '">' . $mysql->prints($row_language['language']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_status')); ?></label>
                        <label class="c-input c-radio">
                        	<input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : ''); ?> >
                            <span class="c-indicator c-indicator-success"></span>
                            <span class="c-input-text color-success"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?> </span>
                        </label>
                        <label class="c-input c-radio">
                        	<input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : ''); ?> >
                            <span class="c-indicator c-indicator-primary"></span>
                            <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?> </span>
                        </label>
                    </div>
                    <div class="btn-group col-md-12">
                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>admin.html" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?></a>
                        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_login_details')); ?>" class="btn btn-success"/>
                    </div> <!-- / panel-footer -->

                </div> <!-- / col-lg-6 -->
            </div> <!-- / row -->
        </form>
    </div>
</div>