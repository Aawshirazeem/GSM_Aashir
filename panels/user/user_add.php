<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetUser('user_add_64565646428');
$reply = $request->GetStr('reply');
if ($reply != '') {
    ?>

    <div class="alert danger alert-dismissable col-md-10 div_center" style="background-color: red;">

        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="color:red">x</button>
        <b> <?php echo $reply; ?></b>
    </div>
    <?php
}
?>

<div class="form-group">
    <label for="emailAddress"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_user')); ?></label>
    
</div>
<form action="<?php echo CONFIG_PATH_SITE_USER; ?>user_add_process.do" method="post" name="frm_customers_edit" id="frm_customers_edit" class="formSkin">


    <div class="row">
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-heading"><?php //$lang->prints('lbl_add_new_user'); ?></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>
                            <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_name')); ?>
                            <div class="hidden pull-right ML10" id="usernameWait"><i class="icon-refresh icon-spin"></i></div>
                            <div class="hidden pull-right ML10" id="usernameAvail"><i class="icon-ok text-success"></i></div>
                            <div class="hidden pull-right ML10" id="usernameNotAvail"><i class="icon-remove text-danger"></i></div>
                        </label>
                        <input name="username" type="text" class="form-control checkUserName" id="username" value="" required />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_password')); ?></label>
                        <input name="password" type="password" class="form-control" id="password" required />
                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_password_for_the_above_login_email')); ?></p>
                    </div>
                    <div class="form-group">
                        <label>
                            <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email')); ?>
                            <div class="hidden pull-right ML10" id="emailWait"><i class="icon-refresh icon-spin"></i></div>
                            <div class="hidden pull-right ML10" id="emailAvail"><i class="icon-ok text-success"></i></div>
                            <div class="hidden pull-right ML10" id="emailNotAvail"><i class="icon-remove text-danger"></i></div>
                        </label>
                        <!--input name="email" type="text" class="form-control checkEmail" id="email" value="" required /-->

                        <input type="email" name="email" parsley-trigger="change" required placeholder="Enter email" class="form-control" id="emailAddress">

                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_note:_nobody_can_change_it_once_registered')); ?></p>
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></label>
                        <!--input name="credits" type="text" class="form-control" id="credits" value="" required /-->

                        <input name="credits" data-parsley-type="number" type="text" class="form-control" required placeholder="Enter Amount" />




                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits_you_want_to_transfer_to_this_user_account')); ?></p>
                    </div>


                    <?php if ($service_imei == 0) { ?>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_access')); ?></label>
                            <input type="radio" name="service_imei" value="1" checked="checked"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?>
                            <input type="radio" name="service_imei" value="0"> <?php $lang->prints('com_no'); ?>
                            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_is_user_can_access_imei_unlocking_services?')); ?></p>
                        </div>
                    <?php } ?>

                    <?php if ($service_file == 0) { ?>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_system_access')); ?></label>
                            <input type="radio" name="service_file" value="1" checked="checked"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?>
                            <input type="radio" name="service_file" value="0"> <?php $lang->prints('com_no'); ?>
                            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_is_user_can_access_file_services?')); ?></p>
                        </div>
                    <?php } ?>


                    <?php if ($service_logs == 0) { ?>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs_access')); ?></label>
                            <input type="radio" name="service_logs" value="1" checked="checked"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?>
                            <input type="radio" name="service_logs" value="0"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?>
                            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_is_user_can_access_server_logs?')); ?></p>
                        </div>
                    <?php } ?>



                    <div class="form-group hidden">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_online_store_access')); ?></label>
                        <input type="radio" name="service_shop" value="1" checked="checked"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?>
                        <input type="radio" name="service_shop" value="0"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?>
                        <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_is_user_can_purchase_any_services/products_from_online_store?')); ?></p>
                    </div>


                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_status')); ?></label>
                        <input type="radio" name="status" value="1" checked="checked"> <i style="color:#006600"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></i>
                        <input type="radio" name="status" value="0"> <i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></i>
                    </div>
                </div> <!-- / panel-body -->
            </div> <!-- / panel -->
            <a href="<?php echo CONFIG_PATH_SITE_USER; ?>users.html" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_back_to_users')); ?></a>
            <button type="submit" class="btn btn-success"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_create_account')); ?></button>
        </div> <!-- / col-lg-6 -->


        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_other_details_[optional]')); ?></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_first_name')); ?></label>
                        <input name="first_name" type="text" class="form-control" id="first_name" value="" />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_last_name')); ?></label>
                        <input name="last_name" type="text" class="form-control" id="last_name" value="" />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_company')); ?></label>
                        <input name="company" type="text" class="form-control" id="company" value="" />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_address')); ?></label>
                        <textarea name="address" class="form-control" id="address" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_city')); ?></label>
                        <input name="city" type="text" class="form-control" id="city" value="" />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_language')); ?></label>
                        <select name="language" class="form-control" id="language">
                            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_language')); ?></option>
                            <?php
                            $sql_language = 'select * from ' . LANGUAGE_MASTER . ' order by id';
                            $query_language = $mysql->query($sql_language);
                            $rows_language = $mysql->fetchArray($query_language);
                            foreach ($rows_language as $row_language) {
                                echo '<option value="' . $row_language['id'] . '">' . $row_language['language'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_time_zone')); ?></label>
                        <select name="timezone"  id="timezone" class="selectpicker" data-live-search="true"  data-style="btn-white" required>
                            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_time_zone')); ?></option>
                            <?php
                            $sql_timezone = 'select * from ' . TIMEZONE_MASTER . ' order by timezone';
                            $query_timezone = $mysql->query($sql_timezone);
                            $rows_timezone = $mysql->fetchArray($query_timezone);
                            foreach ($rows_timezone as $row_timezone) {
                                echo '<option value="' . $row_timezone['id'] . '">' . $row_timezone['timezone'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?></label>
                        <select name="country"  id="country" class="selectpicker" data-live-search="true"  data-style="btn-white" required>
                            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_country')); ?></option>
                            <?php
                            $sql_country = 'select * from ' . COUNTRY_MASTER . ' order by countries_name';
                            $query_country = $mysql->query($sql_country);
                            $rows_country = $mysql->fetchArray($query_country);
                            foreach ($rows_country as $row_country) {
                                echo '<option value="' . $row_country['id'] . '">' . $row_country['countries_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_phone')); ?></label>
                        <input name="phone" type="text" class="form-control" id="phone" value="" />
                    </div>
                    <p class="field">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_mobile')); ?></label>
                        <input name="mobile" type="text" class="form-control" id="mobile" value="" />
                </div>
                <div>
                </div>
            </div> <!-- / panel-body -->
        </div> <!-- / panel -->
    </div> <!-- / col-lg-6 -->
</div>
</form>
