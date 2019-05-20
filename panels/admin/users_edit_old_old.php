<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetAdmin('user_edit_789971255d2');

$id = $request->GetInt('id');



$sql_curr = 'select * from ' . CURRENCY_MASTER . ' where ';

$sql_curr = 'select * from ' . CURRENCY_MASTER . ' a where a.id=(select b.currency_id from ' . USER_MASTER . ' b where b.id=' . $id . ')';

$query_curr = $mysql->query($sql_curr);

$rows_curr = $mysql->fetchArray($query_curr);

$prefixD = $rows_curr[0]['prefix'];

$suffixD = $rows_curr[0]['suffix'];



$sql1 = 'select ip from ' . IP_POOL . ' where id=' . $mysql->getInt($id);

$query = $mysql->query($sql1);

$dataa = $mysql->fetchArray($query);



$textdata = '';

foreach ($dataa as $a) {

    $textdata.=$a['ip'] . "\n";
}



$sql = 'select um.*, date_format(creation_date,"%b %Y") as userCreated,

					cm.countries_name country, cm.countries_iso_code_2

					from ' . USER_MASTER . ' um

					left join ' . COUNTRY_MASTER . ' cm on (um.country_id = cm.id)

					where um.id=' . $mysql->getInt($id);

$query = $mysql->query($sql);

$rowCount = $mysql->rowCount($query);

if ($rowCount == 0) {

    header("location:" . CONFIG_PATH_SITE_ADMIN . "users.html?reply=" . urlencode('reply_invalid_id'));

    exit();
}

$rows = $mysql->fetchArray($query);



$row = $rows[0];

$chk = 1;

if ($row['credits_inprocess'] == 0 && $row['credits'] == 0 && $row['credits_used'] == 0)
    $chk = 0;



//check module

$is_access = 1;

///





$con = mysqli_connect("185.27.133.16", "gsmunion_upuser", "S+OXupg8lqaW", "gsmunion_upload");

$qry_check = 'select * from tbl_users where  domain="' . $_SERVER['HTTP_HOST'] . '" and reseller_panel=0';



$result = $con->query($qry_check);



if ($result->num_rows > 0) {

    $is_access = 0;
}

//end module
?>

<div class="row m-b-20">

    <div class="col-xs-12">

        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_users')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Edit User')); ?></li>

        </ol>

    </div>

</div>

<div class="user-profile">

    <div class="container-fluid">

        <div class="row">

            <div class="col-xs-12">

                <div class="stats pull-right m-t-30 w-600 color-white">

                    <div class="pull-left w-200 text-center">

                        <h3 class="text-bold"> <?php echo $objCredits->printCredits($row['credits'], $prefixD, $suffixD); ?> </h3>

                        <p class="text-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?></p>

                    </div>

                    <div class="pull-left w-200 text-center">

                        <h3 class="text-bold"> <?php echo $objCredits->printCredits($row['credits_inprocess'], $prefixD, $suffixD); ?> </h3>

                        <p class="text-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_in_process')); ?></p>

                    </div>

                    <div class="pull-left w-200 text-center">

                        <h3 class="text-bold"> <?php echo $objCredits->printCredits($row['credits_used'], $prefixD, $suffixD); ?> </h3>

                        <p class="text-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_used')); ?></p>

                    </div>

                </div>

                <div class="media"> 

                    <a class="media-left"> 

<?php if ($row['img'] != '') { ?>

                            <img class="media-object img-circle h-100 w-100" src="<?php echo CONFIG_PATH_SITE; ?>images/<?php echo $row['img']; ?>">

<?php } else { ?>

                            <img class="media-object img-circle h-100 w-100" src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-2.jpg">

<?php } ?>

                    </a>

                    <div class="media-body">

                        <h5 class="color-white media-heading"> <?php echo $mysql->prints($row['username']) ?> </h5>

                        <p class="text-muted m-b-5 color-white"> 

                            <span class="m-r-10 color-white"><?php echo $mysql->prints($row['email']) ?></span> 

                        </p>

                        <p></p>

                        <div class="profile-buttons">

<?php if ($row['login_key'] != '') { ?>

                                <a target="_blank" href="<?php echo CONFIG_PATH_SITE_USER; ?>login_key_process.do?key=<?php echo $row['login_key'] ?>" class="btn mini btn-success btn-sm btn-flat"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Login')); ?></a>

<?php } ?>

                            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_delete.html?id=<?php echo $id; ?>" class="btn mini btn-danger btn-sm btn-flat"><i class="fa fa-times"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Delete User')); ?> </i></a>             

                        </div>

                        <p></p>

                    </div>

                </div>

            </div>

            <div class="col-xs-12">

                <div class="btn-group btn-sm">

                    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html" class="btn btn-primary-100"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Go To Users')); ?></a>

                    <a href="<?php CONFIG_PATH_SITE_ADMIN; ?>users_credit_invoices_userwise.html?status=0&id=<?php echo $row['id']; ?>" class="btn btn-success-100"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Invoices')); ?></a>

                    <a href="<?php CONFIG_PATH_SITE_ADMIN; ?>users_credits.html?id=<?php echo $row['id']; ?>" class="btn btn-warning-100"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Credit')); ?></a>

<?php if ($row['status'] != 1) { ?>

                        <a href="<?php CONFIG_PATH_SITE_ADMIN; ?>users_reactive.html?id=<?php echo $row['id']; ?>" class="btn btn-success"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Unblock/Active')); ?></a>

<?php } ?>

                    <button class="btn btn-info-100" name="user_password" id="user_password"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Generate Password & Send Mail')); ?></button>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="row m-b-20">

    <div class="col-xs-12 col-lg-12">

        <div class="bs-nav-tabs nav-tabs-warning">

            <ul class="nav nav-tabs nav-animated-border-from-left">

                <li class="nav-item"> 

                    <a class="nav-link active" data-toggle="tab" data-target="#nav-tabs-0-1"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_overview')); ?></a> 

                </li>

                <li class="nav-item"> 

                    <a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-2"><i class="fa fa-lock"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_login')); ?></a> 

                </li>

                <li class="nav-item"> 

                    <a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-3"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_profile')); ?></a> 

                </li>

                <li class="nav-item"> 

                    <a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-4"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_payment')); ?></a> 

                </li>

                <li class="nav-item"> 

                    <a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-5"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei')); ?></a> 

                </li>

                <li class="nav-item"> 

                    <a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-6"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file')); ?></a> 

                </li>

                <li class="nav-item"> 

                    <a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-7"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log')); ?></a> 

                </li>

                <li class="nav-item"> 

                    <a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-8"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_prepaid_log')); ?></a> 

                </li>

                <li class="nav-item"> 

                    <a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-9"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_ip_setting')); ?></a> 

                </li>

                <li class="nav-item"> 

                    <a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-10"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Invoices')); ?></a> 

                </li>

                <li class="nav-item"> 

                    <a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-11"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email_log')); ?></a> 

                </li>

            </ul>

            <div class="tab-content">

                <div role="tabpanel" class="tab-pane in active" id="nav-tabs-0-1">

                    <div class="p-t-20">

                        <h5><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_summary')); ?></h5>

<?php //include("users_edit_report_order_summary.php");  ?>

                        <div class="col-md-5">

                            <canvas id="myChart" width="300" height="300"></canvas>

                        </div>

<?php //include("users_edit_report_order_trend.php");   ?>

<?php //include("users_edit_report_order_transactions.php");  ?>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-2">

                    <div class="p-t-20">

                        <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_edit_login_process.do" method="post" class="form-horizontal tasi-form">

                            <div class="form-group">

                                <div class="row">

                                    <div class="col-sm-6">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></label>

                                        <input name="username" type="text" readonly class="form-control" id="username" value="<?php echo $mysql->prints($row['username']) ?>" autocomplete="off" />

                                        <input name="id" type="hidden" class="textbox_fix" id="id" value="<?php echo $row['id'] ?>" />

                                    </div>

                                    <div class="col-sm-6">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_password')); ?></label>

                                        <input name="password" type="text"  class="form-control" id="password" autocomplete="off" />

                                    </div>

                                </div>

                            </div>

                            <div class="form-group">

                                <div class="row">

                                    <div class="col-sm-6">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?></label>

                                        <input name="email" type="text" class="form-control" id="email" value="<?php echo $mysql->prints($row['email']) ?>" />

                                    </div>

                                    <div class="col-sm-6">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_overdrive_limit')); ?></label>

                                        <input name="ovd_c_limit" type="text" class="form-control" maxlength="5" id="ovd_c_limit" value="<?php echo $mysql->prints($row['ovd_c_limit']) ?>" />

                                    </div>

                                    <!--                                        <div class="col-sm-6">
                                    
                                                                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_alternate_emails')); ?><br></label>
                                    
                                                                                <input name="email_cc" type="text" class="form-control" id="email_cc" value="<?php echo ($row['email_cc'] != '') ? $mysql->prints($row['email_cc']) : ''; ?>" maxlength="255" />
                                    
                                                                            </div>-->

                                </div>

                            </div>

                            <div class="form-group hidden">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_account_suspension_days')); ?><br></label>

                                <input name="account_suspension_days" type="text" class="form-control" id="email" value="<?php echo $row['account_suspension_days'] ?>" />

                                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_note:_these_are_the_days_upto_which_the_user_account_be_active')); ?></p>

                            </div>

                            <!--                                <div class="form-group">
                            
                                                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits_transaction_limit')); ?><br></label>
                            
                                                                <input name="credits_transaction_limit" type="text" class="form-control" id="credits_transaction_limit" value="<?php echo $row['user_credit_transaction_limit'] ?>" />
                            
                                                                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits_transaction_limit')); ?></p>
                            
                                                            </div>-->

                            <div class="form-group ">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_type')); ?><br></label>

                                <div class="checkboxes">





                                    <label class="c-input c-radio"><input type="radio" name="user_type" value="0" <?php echo (($row['user_type'] == '0') ? 'checked="checked"' : ''); ?> /> <i class="fa fa-user"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_user')); ?> [<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_can_order_any_service_as_per_user_rights')); ?>]<span class="c-indicator c-indicator-success"></span></label>

<?php if ($is_access == 1) { ?>

                                        <label class="c-input c-radio"><input type="radio" name="user_type" value="1" <?php echo (($row['user_type'] == '1') ? 'checked="checked"' : ''); ?>  /> <i class="fa fa-group"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_reseller')); ?> [<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_has_all_facilites_like_a_user,_but_can_create/manage_his_own_customers')); ?>]<span class="c-indicator c-indicator-success"></span></label>

    <?php
}
?>

                                </div>

                            </div>

                            <div class="form-group text-center">

                                <div class="row">

                                    <div class="col-sm-3">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_access')); ?></label>

                                        <div class="animated-switch">



                                            <input id="switch-success-1" name="service_imei" type="checkbox" value="1" <?php echo ($row['service_imei'] == '1') ? 'checked="checked"' : ''; ?>> 

                                                <!--input type="checkbox" <?php echo ($row['service_imei'] == '1') ? 'checked="checked"' : ''; ?> name="service_imei" value="1" /-->

                                            <label for="switch-success-1" class="label-success adjchk"></label>

                                        </div>                                            

                                    </div>

                                    <div class="col-sm-3">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_system_access')); ?></label>

                                        <div class="animated-switch">

                                            <input id="switch-success-2" name="service_file" type="checkbox" value="1" <?php echo ($row['service_file'] == '1') ? 'checked="checked"' : ''; ?>> 

                                                <!--input type="checkbox" <?php echo ($row['service_file'] == '1') ? 'checked="checked"' : ''; ?> name="service_file" value="1" /-->

                                            <label for="switch-success-2" class="label-success adjchk"></label>

                                        </div>

                                    </div>



                                    <div class="col-sm-3">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs_access')); ?></label>

                                        <div class="animated-switch">

                                            <input id="switch-success-3" name="service_logs" type="checkbox" value="1" <?php echo ($row['service_logs'] == '1') ? 'checked="checked"' : ''; ?>> 

                                                <!--input type="checkbox" <?php echo ($row['service_logs'] == '1') ? 'checked="checked"' : ''; ?> name="service_logs" value="1" /-->

                                            <label for="switch-success-3" class="label-success adjchk"></label>

                                        </div>                                            

                                    </div>

                                    <div class="col-sm-3">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_logs_access')); ?></label>

                                        <div class="animated-switch">

                                            <input id="switch-success-4" name="service_prepaid" type="checkbox" value="1" <?php echo ($row['service_prepaid'] == '1') ? 'checked="checked"' : ''; ?>> 

                                                <!--input type="checkbox" <?php echo ($row['service_prepaid'] == '1') ? 'checked="checked"' : ''; ?> name="service_prepaid" value="1" /-->

                                            <label for="switch-success-4" class="label-success adjchk"></label>

                                        </div>                                            

                                    </div>

                                </div>



                            </div>	

                            <div class="form-group hidden">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_online_store_access')); ?></label>

                                <label class="checkbox-inline c-input c-radio">



                                    <input type="radio" name="service_shop" value="1" <?php echo (($row['service_shop'] == '1') ? 'checked="checked"' : ''); ?> /> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?><span class="c-indicator c-indicator-success"></span>

                                </label>

                                <label class="checkbox-inline c-input c-radio">

                                    <input type="radio" name="service_shop" value="0" <?php echo (($row['service_shop'] == '0') ? 'checked="checked"' : ''); ?> /> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?><span class="c-indicator c-indicator-success"></span>

                                </label>

                                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_is_user_can_purchase_any_services/products_from_online_store?')); ?></p>

                            </div>

                            <div class="form-group text-center">

                                <!--                                    <div class="col-sm-6">
                                
                                                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_api_access')); ?><br></label>
                                
                                                                        <p>
                                
                                                                        <div class="switch" data-on-label="ON" data-off-label="OFF">
                                
                                                                             <input  type="checkbox" name="api_access" value="1"  id="" <?php echo ($row['api_access'] == '1') ? 'checked="checked"' : ''; ?> data-plugin="switchery" data-color="#A0D269" data-secondary-color="#ED5565" data-size="small"/>
                                
                                                                            input type="checkbox" <?php echo ($row['api_access'] == '1') ? 'checked="checked"' : ''; ?> name="api_access" value="1" /
                                
                                                                            <input type="hidden" name="old_api_access" value="<?php echo $row['api_access']; ?>" />
                                
                                                                        </div>
                                
                                                                        </p>
                                
                                                                    </div>-->

                                <div class="col-sm-6">

                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_status')); ?></label>

                                    <div class="animated-switch">

                                        <input id="switch-success" name="status" type="checkbox" value="1" <?php echo ($row['status'] == '1') ? 'checked="checked"' : ''; ?>> 

                                            <!--input type="checkbox" <?php echo ($row['status'] == '1') ? 'checked="checked"' : ''; ?> name="status" value="1" /-->

                                        <label for="switch-success" class="label-success adjchk"></label>

                                    </div>

                                    <label><?php //$lang->prints('lbl_account_status');  ?></label>

                                </div>

                            </div>

                            <div class="form-group">

                                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_login_details')); ?>" class="btn btn-success">

                            </div>

                        </form>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-3">

                    <div class="p-t-20">

                        <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_edit_profile_process.do" method="post">

                            <input name="email" type="hidden" class="textbox_fix" id="email" value="<?php echo $row['email'] ?>" />

                            <div class="form-group">

                                <div class="row">

                                    <div class="col-sm-6">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_first_name')); ?></label>

                                        <input name="first_name" type="text" class="form-control" id="first_name" value="<?php echo $mysql->prints($row['first_name']) ?>" />

                                        <input name="id" type="hidden" class="form-control" id="id" value="<?php echo $row['id'] ?>" />

                                    </div>

                                    <div class="col-sm-6">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_last_name')); ?></label>

                                        <input name="last_name" type="text" class="form-control" id="last_name" value="<?php echo $mysql->prints($row['last_name']) ?>" />

                                    </div>

                                </div>

                            </div>



                            <div class="form-group">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_company')); ?></label>

                                <input name="company" type="text" class="form-control" id="company" value="<?php echo $mysql->prints($row['company']) ?>" />

                            </div>

                            <div class="form-group">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_address')); ?></label>

                                <textarea name="address" class="form-control" id="address" rows="4"><?php echo $mysql->prints($row['address']) ?></textarea>

                            </div>

                            <div class="form-group">

                                <div class="row">

                                    <div class="col-sm-6">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_city')); ?></label>

                                        <input name="city" type="text" class="form-control" id="city" value="<?php echo $mysql->prints($row['city']) ?>" />

                                    </div>

                                    <div class="col-sm-6">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?></label>

                                        <select name="country" class="form-control" id="country">

                                            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_country')); ?></option>

<?php
$sql_country = 'select * from ' . COUNTRY_MASTER . ' order by countries_name';

$query_country = $mysql->query($sql_country);

$rows_country = $mysql->fetchArray($query_country);

foreach ($rows_country as $row_country) {

    echo '<option ' . (($row_country['id'] == $row['country_id']) ? 'selected="selected"' : '') . ' value="' . $row_country['id'] . '">' . $mysql->prints($row_country['countries_name']) . '</option>';
}
?>

                                        </select>

                                    </div>

                                </div>

                            </div>

                            <div class="form-group">

                                <div class="row">

                                    <div class="col-sm-4">

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

                                    <div class="col-sm-4">

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

                                    <div class="col-sm-4"  >

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_currency')); ?></label>

                                        <input type="hidden" name="currency" value="<?php echo $row['currency_id']; ?>">

                                        <select name="currency" class="form-control" id="currency" <?php echo ($chk != 0) ? 'disabled=""' : ''; ?>>



                                            <?php
                                            $sql_currency = 'select * from ' . CURRENCY_MASTER . ' order by currency';

                                            $query_currency = $mysql->query($sql_currency);

                                            $rows_currency = $mysql->fetchArray($query_currency);

                                            foreach ($rows_currency as $row_currency) {

                                                echo '<option ' . (($row_currency['id'] == $row['currency_id']) ? 'selected="selected"' : '') . ' value="' . $row_currency['id'] . '">' . $mysql->prints($row_currency['currency']) . '</option>';
                                            }
                                            ?>

                                        </select>

                                    </div>

                                </div>

                            </div>

                            <div class="form-group">

                                <div class="row">

                                    <div class="col-sm-6">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_phone')); ?></label>

                                        <input name="phone" type="text" class="form-control" id="phone" value="<?php echo $mysql->prints($row['phone']) ?>" />

                                    </div>

                                    <div class="col-sm-6">

                                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_mobile')); ?></label>

                                        <input name="mobile" type="text" class="form-control" id="mobile" value="<?php echo $mysql->prints($row['mobile']) ?>" />

                                    </div>

                                </div>

                            </div>
                            <div class="panel"  >

					<h4 class="panel-heading m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Custom_Fields_Data')); ?></h4>

            

                                    <table class="table table-bordered table-hover" style="color:green">
                                        
						<?php
                                                
                                                
                                                if($row['custom_1']=="" && $row['custom_2']!=="" && $row['custom_3']=="" && $row['custom_4']=="")
                                                 echo 'No Custom Field Attach';
                                                    else
                                                    
                                                    {
                                                        
                                                        echo '<th>Field Name</th>
                                        <th>Field Data</th>';
                                                        
                                                         if($row['custom_1']!="")

							echo '<tr><td width="">'.$mysql->prints(current(explode(":", $row['custom_1']))).'</td><td>' . $mysql->prints(substr($row['custom_1'], strpos($row['custom_1'], ":") + 1)) . '</td></tr>';

                                                        if($row['custom_2']!="")

                                                        echo '<tr><td width="">'.$mysql->prints(current(explode(":", $row['custom_2']))).'</td><td>' . $mysql->prints(substr($row['custom_2'], strpos($row['custom_2'], ":") + 1)) . '</td></tr>';

                                                        if($row['custom_3']!="")

                                                        echo '<tr><td width="">'.$mysql->prints(current(explode(":", $row['custom_3']))).'</td><td>' . $mysql->prints(substr($row['custom_3'], strpos($row['custom_3'], ":") + 1)) . '</td></tr>';

							 if($row['custom_4']!="")

                                                        echo '<tr><td width="">'.$mysql->prints(current(explode(":", $row['custom_4']))).'</td><td>' . $mysql->prints(substr($row['custom_4'], strpos($row['custom_4'], ":") + 1)) . '</td></tr>';

							 if($row['custom_5']!="")

                                                        echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $row['custom_5']))).'</td><td>' . $mysql->prints(substr($row['custom_5'], strpos($row['custom_5'], ":") + 1)) . '</td></tr>';
                                                }
                                              
                                                




						?>

                                    </table></div>

                            <div class="form-group">

                                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_profile')); ?>" class="btn btn-success"/>

                            </div>

                        </form>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-4">

                    <div class="p-t-20">

                        <div class="row m-b-20">

                            <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_edit_payment_gateway_process.do" method="post">

                                <input name="id" type="hidden" class="textbox_fix" id="id" value="<?php echo $row['id'] ?>" />

<?php
$sql_pg_u = 'select pg_paypal,pg_moneybookers,auto_pay from ' . USER_MASTER . ' where id=' . $id;

$resultPgUser = $mysql->getResult($sql_pg_u);

$pg_paypal = $resultPgUser['RESULT'][0]['pg_paypal'];

$pg_moneybookers = $resultPgUser['RESULT'][0]['pg_moneybookers'];

$auto_pay = $resultPgUser['RESULT'][0]['auto_pay'];
?>

                                <table class="table table-striped table-hover">

                                    <tr>

                                        <th width="16"></th>

                                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_gateway')); ?></th>

                                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_charges')); ?></th>

                                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_pay_charges')); ?></th>

                                        <th width="100" class="TA_C">

                                            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_edit_auto_pay.do?enable=<?php echo (($auto_pay == 1) ? 0 : 1 ); ?>&id=<?php echo $row['id']; ?>" class="btn btn-sm <?php echo ($auto_pay == 1) ? 'btn-danger' : 'btn-warning'; ?>">
											<?php if($auto_pay == 1){
												$admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Disable Auto-Pay'));
											}else{
												$admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Enable Auto-Pay')); }?></a></a>

                                        </th>

                                    </tr>

                                <?php
                                $sql_pg = 'select gm.*, gd.user_id uid

                                    from ' . GATEWAY_MASTER . ' gm

                                    left join ' . GATEWAY_DETAILS . ' gd on (gm.id = gd.gateway_id and gd.user_id=' . $id . ')';

                                $query_pg = $mysql->query($sql_pg);



                                //  echo $sql_pg;

                                $strReturn = "";

                                $i = 1;

                                if ($mysql->rowCount($query_pg) > 0) {

                                    $rows_pg = $mysql->fetchArray($query_pg);

                                    foreach ($rows_pg as $row_pg) {

                                        echo '<tr>';

                                        echo '<td>' . $graphics->status($row_pg['status']) . '</td>';

                                        echo '<td>' . $row_pg['gateway'] . '</td>';

                                        echo '<td>' . $row_pg['charges'] . '%</td>';

                                        echo '<td class="TA_C ' . (($pg_paypal != '' and $pg_paypal != 0) ? 'has-success' : '') . '">';

                                        echo '<input type="hidden" name="gm_id[]" value="' . $row_pg['id'] . '">';

                                        switch ($row_pg['id']) {

                                            case 1:

                                                echo '<input type="text" name="charges_' . $row_pg['id'] . '" value="' . (($pg_paypal != '' and $pg_paypal != 0) ? $pg_paypal : '') . '" class="form-control" style="width:60px;" size="5">';

                                                break;

                                            case 3:

                                                echo '<input type="text" name="charges_' . $row_pg['id'] . '" value="' . (($pg_moneybookers != '' and $pg_moneybookers != 0) ? $pg_moneybookers : '') . '" class="form-control ' . (($pg_moneybookers != '' and $pg_moneybookers != 0) ? 'textbox_highlight' : '') . '" style="width:60px;" size="5">';

                                                break;
                                        }

                                        echo '</td>';

                                        echo '<td class="TA_C"><input type="checkbox" name="gateway_ids[]" value="' . $row_pg['id'] . '" ' . (($row_pg['uid'] != '') ? 'checked="checked"' : '') . ' /></td>';

                                        echo '</tr>';
                                    }
                                } else {

                                    echo '<tr><td colspan="8" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
                                }
                                ?>

                                </table>

                                <div class="btn-group">

                                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_update')); ?>" class="btn btn-success"/>

                                </div>

                            </form>

                        </div>

                        <!--

                                ****************** PACKAGES *******************

                        -->

                        <div class="row m-b-20">

                            <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_edit_package_process.do" enctype="multipart/form-data" method="post">

                                <h3><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_package')); ?> </h3>

                                <input type="hidden" name="id" value="<?php echo $id; ?>" >

                                <table class="MT5 table table-striped table-hover panel">

                                    <tr>

                                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_view_pa_name')); ?></th>

                                        <th></th>

                                    </tr>

<?php
$package_id = 0;

$sql_check = 'select id from ' . PACKAGE_USERS . ' where user_id=' . $id;

$query_check = $mysql->query($sql_check);

$package_id_check = 0;

if ($mysql->rowCount($query_check) > 0) {

    $rows_check = $mysql->fetchArray($query_check);

    $package_id_check = $rows_check[0]['id'];
}





$sql_sp = 'select

                                        pu.package_id,pu.user_id,pm.package_name,pm.id

                                    from ' . PACKAGE_MASTER . ' pm

                                    left join ' . PACKAGE_USERS . ' pu on (pu.package_id = pm.id and pu.user_id = ' . $id . ')

                                    where pm.status=1';

$query_sp = $mysql->query($sql_sp);

if ($mysql->rowCount($query_sp) > 0) {





    $rows_sp = $mysql->fetchArray($query_sp);

    echo '<tr><td><b>' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_pa_none')) . '</b></td><td><label class="c-input c-radio"><input type="radio" class="checkbox-inline" name="package_id" value="0"' . (($package_id_check == 0) ? ' checked="checked"' : '') . '/><span class="c-indicator c-indicator-success"></span></td></tr>';

    foreach ($rows_sp as $row_sp) {

        if ($row_sp['package_id'] != '') {

            $package_id = $row_sp['package_id'];
        }

        echo '<tr>';

        echo '<td>' . $row_sp['package_name'] . '</td>';

        echo '<td><label class="c-input c-radio"><input type="radio" class="checkbox-inline" name="package_id" value=" ' . $row_sp['id'] . '" ' . (($row_sp['package_id'] != '') ? ' checked="checked"' : '') . '/><span class="c-indicator c-indicator-success"></span></td>';

        echo '</tr>';
    }
}
?>

                                </table> 

                                <div class="btn-group">

                                    <input type="submit" name="allot" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_pa_allot')); ?>" class="btn btn-success">

                                </div>

                            </form>

                        </div>



                        <div class="row m-b-20">

                            <!--

                            ****************** CREDIT REQUESTS *******************

                            -->

                            <h3><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Credit purchase request')); ?></h3>

                            <table class="MT5 table table-striped table-hover panel">

                                <tr>

                                    <th width="16"></th>

                                    <th ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></th>

                                    <th ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_date')); ?></th>

                                    <th width="100"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_amount')); ?></th>

                                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?></th>

                                    <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?></th>

                                    <th width="142"></th>

                                </tr>

<?php
$paging = new paging();

$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;

$limit = 40;

$qLimit = " limit $offset,$limit";

$extraURL = '';





$sql_req = 'select im.*,um.username, cm.prefix, gm.gateway

                                from ' . INVOICE_REQUEST . ' im

                                left join ' . USER_MASTER . ' um on (im.user_id = um.id)

                                left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)

                                left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)

                                where im.user_id=' . $row['id'] . '

                            order by im.id DESC';

$query_req = $mysql->query($sql_req);

$strReturn = "";



$pCode = $paging->recordsetNav($sql_req, CONFIG_PATH_SITE_ADMIN . 'users_credit_request.html', $offset, $limit, $extraURL);



$i = $offset;



if ($mysql->rowCount($query) > 0) {

    $rows_req = $mysql->fetchArray($query_req);

    foreach ($rows_req as $row_req) {

        $i++;

        echo '<tr>';

        echo '<td>' . $i . '</td>';

        echo '<td>' . $row_req['username'] . '</td>';

        echo '<td>' . date("d-M Y H:i", strtotime($row_req['date_time'])) . '</td>';

        echo '<td>' . $row_req['prefix'] . ' ' . $row_req['amount'] . '</td>';

        echo '<td>' . $row_req['credits'] . '</td>';
        ?>

                                        <td>

                                        <?php
                                        switch ($row_req['status']) {

                                            case '0':

                                                echo $admin->wordTrans($admin->getUserLang(), $lang->get('com_pending'));

                                                break;

                                            case '1':

                                                echo $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_done'));

                                                break;

                                            case '2':

                                                echo $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_canceled'));

                                                break;
                                        }
                                        ?>

                                        </td>

                                        <?php
                                        echo '<td>';

                                        if ($row_req['status'] == 0) {

                                            echo '<div class="btn-group pull-right">

                                            <a href="' . CONFIG_PATH_SITE_ADMIN . 'users_credits.html?id=' . $row_req['user_id'] . '&irid=' . $row_req['id'] . '&credits=' . $row_req['credits'] . '" class="btn btn-primary btn-xs">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Accept')) . '</a>

                                            <a href="' . CONFIG_PATH_SITE_ADMIN . 'invoice_request.html?inidr=' . $row_req['id'] . '" class="btn btn-default btn-xs" >' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Reject')) . '</a>

                                        </div>';
                                        }

                                        echo '</td>';





                                        echo '</tr>';
                                    }
                                } else {

                                    echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
                                }
                                ?>

                            </table>

                            <div class="row m-t-20">
                                <div class="col-md-6 p-l-0">
                                    <div class="TA_C navigation" id="paging">
                                    <?php echo $pCode; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-5">

                    <div class="p-t-20">

                                <?php
                                if ($row['service_imei'] == '1') {

                                    echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_edit_imei_process.do?id=' . $id . '&enable=0" class="btn btn-danger m-b-10">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_disable')) . '</a>';
                                } else {

                                    echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_edit_imei_process.do?id=' . $id . '&enable=1" class="btn btn-success btn-lg btn-block m-b-10">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_enable')) . '</a>';
                                }
                                ?>



                        <!--

                                ****************** IMEI LIST *******************

                        -->



                        <div id="imei_spl_credits" <?php echo (($row['service_imei'] != '1') ? 'style="display:none"' : ''); ?>>

                            <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_spl_credits_process.do" enctype="multipart/form-data" method="post">

                                <input type="hidden" name="user_id" value="<?php echo $id; ?>" >

                                <table class="MT5 table table-striped table-hover panel">

                                    <tr>

                                        <th></th>

                                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_tool')); ?></th>

                                        <th class="text-right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?></th>

                                        <th class="text-right" width="120"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_pack_cr')); ?></th>

                                        <th class="text-right" width="120"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_spl_cr')); ?></th>

                                    </tr>





                        <?php
                        $sql_spl_imei = 'select

											tm.*,

											itad.amount,

											isc.amount splCr,

											pim.amount as packageCr,

											igm.group_name

										from ' . IMEI_TOOL_MASTER . ' tm

										left join ' . IMEI_GROUP_MASTER . ' igm on(tm.group_id = igm.id)

										left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $row['currency_id'] . ')

										left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(itad.tool_id=tm.id and itad.currency_id = ' . $row['currency_id'] . ')

										left join ' . IMEI_SPL_CREDITS . ' isc on(isc.user_id = ' . $id . ' and isc.tool_id=tm.id)

										left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $id . ')

										left join ' . PACKAGE_IMEI_DETAILS . ' pim on(pim.package_id = pu.package_id and pim.currency_id = ' . $row['currency_id'] . ' and pim.tool_id = tm.id)

										order by igm.sort_order, igm.group_name, tm.sort_order, tm.tool_name';





//echo $sql_spl_imei;exit;

                        $mysql->query("SET SQL_BIG_SELECTS=1");

                        $query_spl_imei = $mysql->query($sql_spl_imei);

                        $strReturn = "";

                        $i = 1;

                        $groupName = "";

                        if ($mysql->rowCount($query_spl_imei) > 0) {

                            $rows_spl_imei = $mysql->fetchArray($query_spl_imei);

                            foreach ($rows_spl_imei as $row_spl_imei) {

                                if ($groupName != $row_spl_imei['group_name']) {

                                    echo '<tr><th colspan="5"><b>' . $row_spl_imei['group_name'] . '</b></th></tr>';

                                    $groupName = $row_spl_imei['group_name'];
                                }

                                echo '<tr class="' . (($row_spl_imei['splCr'] != '') ? (($row_spl_imei['splCr'] > $row_spl_imei['amount']) ? 'error' : 'success') : '') . '">';

                                echo '<td>' . $i++ . '</td>';

                                echo '<td>' . $mysql->prints($row_spl_imei['tool_name']) . '</td>';

                                echo '<td class="text-right">' . $row_spl_imei['amount'] . '</td>';

                                echo '<td class="text_right">' . $row_spl_imei['packageCr'] . '</td>';

                                echo '<td class="text_right">

									<input type="text" class="form-control" style="width:80px" name="spl_' . $row_spl_imei['id'] . '" value="' . $row_spl_imei['splCr'] . '" />

									<input type="hidden" name="org_' . $row_spl_imei['id'] . '" value="' . $row_spl_imei['amount'] . '" />

									<input type="hidden" name="ids[]" value="' . $row_spl_imei['id'] . '" />

								  </td>';

                                echo '</tr>';
                            }
                        } else {

                            echo '<tr><td colspan="6" class="no_record">' . $lang->get('com_no_record_found') . '</td></tr>';
                        }
                        ?>

                                </table>

                                <div class="btn-group">

                                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_credits')); ?>" class="btn btn-success"/>

                                </div>

                            </form>	

                        </div>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-6">

                    <div class="p-t-20">

                                    <?php
                                    if ($row['service_file'] == '1') {

                                        echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_edit_file_process.do?id=' . $id . '&enable=0" class="btn btn-danger m-b-10">' . $lang->get('com_disable') . '</a>';
                                    } else {

                                        echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_edit_file_process.do?id=' . $id . '&enable=1" class="btn btn-success btn-lg btn-block m-b-10">' . $lang->get('com_enable') . '</a>';
                                    }
                                    ?>

                        <div id="file_spl_credits" <?php echo (($row['service_file'] != '1') ? 'style="display:none"' : ''); ?>>







                            <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_spl_credits_file_process.do" enctype="multipart/form-data" method="post">

                                <input type="hidden" name="user_id" value="<?php echo $id; ?>" >

                                <table class="MT5 table table-striped table-hover panel">

                                    <tr>

                                        <th></th>

                                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?></th>

                                        <th class="text_right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_org._cr.')); ?></th>

                                        <th class="text_right" width="120"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_pack_cr')); ?></th>

                                        <th class="text_right" width="120"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_spl_cr')); ?></th>

                                    </tr>

<?php
$sql_spl_imei = 'select

										fsm.*,

										fsad.amount,

										fssc.amount splCr,

										pfm.amount as packageCr

									from ' . FILE_SERVICE_MASTER . ' fsm

									left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $row['currency_id'] . ')

									left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' fsad on(fsad.service_id=fsm.id and fsad.currency_id = ' . $row['currency_id'] . ')

									left join ' . FILE_SPL_CREDITS . ' fssc on(fssc.user_id = ' . $id . ' and fssc.service_id=fsm.id)

									left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $id . ')

									left join ' . PACKAGE_FILE_DETAILS . ' pfm on(pfm.package_id = pu.package_id and pfm.currency_id = ' . $row['currency_id'] . ' and pfm.service_id = fsm.id)

									order by fsm.service_name';



$query_spl_imei = $mysql->query($sql_spl_imei);

$strReturn = "";

$i = 1;

if ($mysql->rowCount($query_spl_imei) > 0) {

    $rows_spl_imei = $mysql->fetchArray($query_spl_imei);

    foreach ($rows_spl_imei as $row_spl_imei) {

        echo '<tr>';

        echo '<td>' . $i++ . '</td>';

        echo '<td>' . $mysql->prints($row_spl_imei['service_name']) . '</td>';

        echo '<td class="text_right">' . $row_spl_imei['amount'] . '</td>';

        echo '<td class="text_right">' . $row_spl_imei['packageCr'] . '</td>';

        echo '<td class="text_right">

								<input type="text" class="textbox_fix text_right noEffect form-control ' . (($row_spl_imei['splCr'] != '') ? 'textbox_highlight' . (($row_spl_imei['splCr'] > $row_spl_imei['amount']) ? 'R' : '') : '') . '" style="width:80px" name="spl_' . $row_spl_imei['id'] . '" value="' . $row_spl_imei['splCr'] . '" />

								<input type="hidden" name="org_' . $row_spl_imei['id'] . '" value="' . $row_spl_imei['amount'] . '" />

								<input type="hidden" name="ids[]" value="' . $row_spl_imei['id'] . '" />

							  </td>';

        echo '</tr>';
    }
} else {

    echo '<tr><td colspan="6" class="no_record">' . $lang->get('com_no_record_found') . '</td></tr>';
}
?>

                                </table>

                                <div class="btn-group">

                                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_credits')); ?>" class="btn btn-success"/>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-7">

                    <div class="p-t-20">

                                    <?php
                                    if ($row['service_logs'] == '1') {

                                        echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_edit_log_process.do?id=' . $id . '&enable=0" class="btn btn-danger m-b-10">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_disable')) . '</a>';
                                    } else {

                                        echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_edit_log_process.do?id=' . $id . '&enable=1" class="btn btn-success btn-lg btn-block m-b-10">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_enable')) . '</a>';
                                    }
                                    ?>

                        <div id="server_log_spl_credits" <?php echo (($row['service_logs'] != '1') ? 'style="display:none"' : ''); ?>>





                            <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_spl_credits_server_log_process.do" enctype="multipart/form-data" method="post">

                                <input type="hidden" name="user_id" value="<?php echo $id; ?>" >

                                <table class="MT5 table table-striped table-hover panel">

                                    <tr>

                                        <th></th>

                                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?></th>

                                        <th style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_org._cr.')); ?></th>

                                        <th style="text-align:right" width="120"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_pack_cr')); ?></th>

                                        <th style="text-align:right" width="120"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_spl_cr')); ?></th>

                                    </tr>

                                    <?php
                                    $sql_spl_imei = 'select

												slm.*,

												slgm.group_name,

												slad.amount,

												slsc.amount splCr,

												pslm.amount as packageCr

											from ' . SERVER_LOG_MASTER . ' slm

											left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)

											left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $row['currency_id'] . ')

											left join ' . SERVER_LOG_AMOUNT_DETAILS . ' slad on(slad.log_id=slm.id and slad.currency_id = ' . $row['currency_id'] . ')

											left join ' . SERVER_LOG_SPL_CREDITS . ' slsc on(slsc.user_id = ' . $id . ' and slsc.log_id=slm.id)

											left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $id . ')

											left join ' . PACKAGE_SERVER_LOG_DETAILS . ' pslm on(pslm.package_id = pu.package_id and pslm.currency_id = ' . $row['currency_id'] . ' and pslm.log_id = slm.id)

											order by slm.server_log_name';



                                    $query_spl_imei = $mysql->query($sql_spl_imei);

                                    $strReturn = "";

                                    $i = 1;

                                    $groupName = "";

                                    if ($mysql->rowCount($query_spl_imei) > 0) {

                                        $rows_spl_imei = $mysql->fetchArray($query_spl_imei);

                                        foreach ($rows_spl_imei as $row_spl_imei) {

                                            if ($groupName != $row_spl_imei['group_name']) {

                                                echo '<tr><th colspan="5">' . $row_spl_imei['group_name'] . '</th></tr>';

                                                $groupName = $row_spl_imei['group_name'];
                                            }

                                            echo '<tr>';

                                            echo '<td>' . $i++ . '</td>';

                                            echo '<td>' . $mysql->prints($row_spl_imei['server_log_name']) . '</td>';

                                            echo '<td class="text_right">' . $row_spl_imei['amount'] . '</td>';

                                            echo '<td class="text_right">' . $row_spl_imei['packageCr'] . '</td>';

                                            echo '<td class="text_right">

					      				<input type="text" class="textbox_fix text_right noEffect form-control ' . (($row_spl_imei['splCr'] != '') ? 'textbox_highlight' . (($row_spl_imei['splCr'] > $row_spl_imei['amount']) ? 'R' : '') : '') . '" style="width:80px" name="spl_' . $row_spl_imei['id'] . '" value="' . $row_spl_imei['splCr'] . '" />

					      				<input type="hidden" name="org_' . $row_spl_imei['id'] . '" value="' . $row_spl_imei['amount'] . '" />

					      				<input type="hidden" name="ids[]" value="' . $row_spl_imei['id'] . '" />

					      			  </td>';

                                            echo '</tr>';
                                        }
                                    } else {

                                        echo '<tr><td colspan="6" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
                                    }
                                    ?>

                                </table>

                                <div class="btn-group">

                                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_credits')); ?>" class="btn btn-success"/>

                                </div>



                            </form>

                        </div>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-8">

                    <div class="p-t-20">

                                    <?php
                                    if ($row['service_prepaid'] == '1') {

                                        echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_edit_prepaid_process.do?id=' . $id . '&enable=0" class="btn btn-danger m-b-10">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_disable')) . '</a>';
                                    } else {

                                        echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_edit_prepaid_process.do?id=' . $id . '&enable=1" class="btn btn-success btn-lg btn-block m-b-10">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_enable')) . '</a>';
                                    }
                                    ?>





                        <div id="prepaid_spl_credits" <?php echo (($row['service_prepaid'] != '1') ? 'style="display:none"' : ''); ?>>



                            <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_spl_credits_prepaid_log_process.do" enctype="multipart/form-data" method="post">

                                <input type="hidden" name="user_id" value="<?php echo $id; ?>" >

                                <table class="MT5 table table-striped table-hover panel">

                                    <tr>

                                        <th></th>

                                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?></th>

                                        <th style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_org._cr.')); ?></th>

                                        <th style="text-align:right" width="120"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_pack_cr')); ?></th>

                                        <th style="text-align:right" width="120"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_spl_cr')); ?></th>

                                    </tr>

                                    <?php
                                    $sql_spl_imei = 'select

												plm.*,

												plgm.group_name,

												plad.amount,

												plsc.amount splCr,

												pplm.amount as packageCr

											from ' . PREPAID_LOG_MASTER . ' plm

											left join ' . PREPAID_LOG_GROUP_MASTER . ' plgm on(plm.group_id = plgm.id)

											left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $row['currency_id'] . ')

											left join ' . PREPAID_LOG_AMOUNT_DETAILS . ' plad on(plad.log_id=plm.id and plad.currency_id = ' . $row['currency_id'] . ')

											left join ' . PREPAID_LOG_SPL_CREDITS . ' plsc on(plsc.user_id = ' . $id . ' and plsc.log_id=plm.id)

											left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $id . ')

											left join ' . PACKAGE_PREPAID_LOG_DETAILS . ' pplm on(pplm.package_id = pu.package_id and pplm.currency_id = ' . $row['currency_id'] . ' and pplm.log_id = plm.id)

											order by plm.prepaid_log_name';



                                    $query_spl_imei = $mysql->query($sql_spl_imei);

                                    $strReturn = "";

                                    $i = 1;

                                    $groupName = "";

                                    if ($mysql->rowCount($query_spl_imei) > 0) {

                                        $rows_spl_imei = $mysql->fetchArray($query_spl_imei);

                                        foreach ($rows_spl_imei as $row_spl_imei) {

                                            if ($groupName != $row_spl_imei['group_name']) {

                                                echo '<tr><th colspan="5">' . $row_spl_imei['group_name'] . '</th></tr>';

                                                $groupName = $row_spl_imei['group_name'];
                                            }

                                            echo '<tr>';

                                            echo '<td>' . $i++ . '</td>';

                                            echo '<td>' . $mysql->prints($row_spl_imei['prepaid_log_name']) . '</td>';

                                            echo '<td class="text_right">' . $row_spl_imei['amount'] . '</td>';

                                            echo '<td class="text_right">' . $row_spl_imei['packageCr'] . '</td>';

                                            echo '<td class="text_right">

					      				<input type="text" class="textbox_fix text_right noEffect form-control ' . (($row_spl_imei['splCr'] != '') ? 'textbox_highlight' . (($row_spl_imei['splCr'] > $row_spl_imei['amount']) ? 'R' : '') : '') . '" style="width:80px" name="spl_' . $row_spl_imei['id'] . '" value="' . $row_spl_imei['splCr'] . '" />

					      				<input type="hidden" name="org_' . $row_spl_imei['id'] . '" value="' . $row_spl_imei['amount'] . '" />

					      				<input type="hidden" name="ids[]" value="' . $row_spl_imei['id'] . '" />

					      			  </td>';

                                            echo '</tr>';
                                        }
                                    } else {

                                        echo '<tr><td colspan="6" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
                                    }
                                    ?>

                                </table>

                                <div class="btn-group">

                                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_credits')); ?>" class="btn btn-success"/>

                                </div>



                            </form>

                        </div>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-9">

                    <div class="p-t-20">

                        <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_edit_profile_process2.do" method="post">

                            <input name="email" type="hidden" class="textbox_fix" id="email" value="<?php echo $row['email'] ?>" />

                            <div class="panel-body">

                                <input name="id" type="hidden" class="form-control" id="id" value="<?php echo $row['id'] ?>" />

                                <div>

                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_api_access')); ?></label>

                                    <div class="animated-switch m-l-45">

                                        <input id="switch-success-api" name="api_access" type="checkbox" value="1" <?php echo ($row['api_access'] == '1') ? 'checked="checked"' : ''; ?>  onchange="change(this);"> 

                                        <!--input type="checkbox" <?php echo ($row['api_access'] == '1') ? 'checked="checked"' : ''; ?> name="api_access" value="1" /-->

                                        <label for="switch-success-api" class="label-success adjchk"></label>

                                        <input type="hidden" name="old_api_access" value="<?php echo $row['api_access']; ?>" />

                                    </div>

                                </div><br>

                                    <?php $apiacess = $row['api_access']; ?>

                                <div class="" id="api_key">

                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_API KEY')); ?></label>

                                    <input name="key" type="text" class="form-control" id="key" value="<?php echo $mysql->prints($row['api_key']) ?>" /><br/>

                                    <input type="button" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Regenerate API Key')); ?>" id="btn_reg" class="btn btn-success" onclick="reset_key();" />



                                </div>

                                <div>

                                    <br/><br/><label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_IP LIST')); ?></label><br/>

                                    <textarea name="ip_pool" class="form-control" id="ip_pool" rows="5" ><?php echo $textdata; ?></textarea>

                                </div>



                                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_profile')); ?>" class="btn btn-success"/>

                            </div> <!-- / panel-body -->

                        </form>

                    </div>

                </div>

                                    <?php $status = 0; ?>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-10">

                    <div class="p-t-20">

                        <h4><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_User Invoices')); ?> </h4>

                        <table class="table table-hover table-striped" id ="mytbl">

                            <tr>

                                <th width="50"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Status')); ?></th>

                                <th ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date')); ?></th>

                                <th width="180"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Admin Note')); ?></th>

<?php if ($status == 0) { ?>

                                    <th width="100"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_amount')); ?></th>

                                    <th width="40"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Edit')); ?></th>

<?php } ?>

                                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?></th>

<?php if ($status == 0 || $status == 1) { ?>

                                    <th width="180"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>

<?php } ?>

                            </tr>

<?php
$paging = new paging();

$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;

$limit = 40;

$qLimit = " limit $offset,$limit";

$extraURL = '';



if ($status == 0) {

    $sql = 'select

												im.*,um.username, cm.prefix, gm.gateway

											from ' . INVOICE_MASTER . ' im

											left join ' . USER_MASTER . ' um on (im.user_id = um.id)

											left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)

											left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)

											where im.status=0 ' . (($status != -1) ? '  and im.paid_status=' . $status : '') . ' and um.id=' . $id . '

										order by im.id DESC';
} elseif ($status == 1) {

    $sql = 'select 

                                         a.id, a.`txn_id`,um.`username`, a.`date_time_paid` as date_time,c.`prefix`,sum(b.`amount`) as amount,

      b.`credits`,a.`paid_status`,b.`gateway_id`

      from ' . INVOICE_MASTER . ' as a

      left join ' . INVOICE_LOG . ' as b

      on a.id=b.`inv_id`

      left join ' . CURRENCY_MASTER . ' as c

      on a.`currency_id`=c.`id`

      left join ' . USER_MASTER . ' um on (a.user_id = um.id)



                                       where a.`paid_status` =1

                                       group by b.`inv_id`';
}

//echo $sql;

$query = $mysql->query($sql);

$strReturn = "";

$pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'users_credit_request.html', $offset, $limit, $extraURL);



$i = $offset;

//get gateway

$get_gateway = 'select id,gateway from ' . GATEWAY_MASTER . ' where status =1';



$gateway = $mysql->query($get_gateway);

$gateways = $mysql->fetchArray($gateway);

//

if ($mysql->rowCount($query) > 0) {

    $rows = $mysql->fetchArray($query);

    // var_dump($rows);exit;

    foreach ($rows as $row) {

        $i++;

        echo '<tr>';

        echo '<td>';

        switch ($row['paid_status']) {

            case '0':

                echo '<span class="label label-info">Unpaid</span>';

                break;

            case '1':

                echo '<span class="label label-success">Paid</span>';

                break;

            case '2':

                echo '<span class="label label-danger">Rejected</span>';

                break;
        }

        echo '</td>';



        echo '<td width="30%">' . date("d-M Y H:i", strtotime($row['date_time'])) . '</td>';

        if ($row['paid_status'] == 0) {

            echo '<td><input  id="gt' . $row["id"] . '" name="gateway" class="form-control">';

//                                              foreach($gateways as $gt){
//                                                  echo '<option  value="' .$gt["id"].'">' .$gt["gateway"] . '</option>';          
//                                                             }

            echo '</td>';
        } else {
            ?>

                                        <td><?php echo $row["gateway_id"] ?> </td>

                                        <?php
                                    }

                                    if ($row['paid_status'] == 0) {

                                        echo '<td width="23%"><lable id="lbl' . $row['id'] . '">' . $row["amount"] . '</lable>-<input name="amount" id="amnt_inv_vo' . $row["id"] . '" maxlength="6" size="6"  value="" class="form-control" style="width:160px;display:inline-block;"  /></td>';



                                        echo '<td  id="' . $row['id'] . '"><a href="#" id="refresh_inv_vo' . $row["id"] . '" class="zmdi zmdi-refresh zmdi-hc-2x" onclick="edit_proccess(' . $row["id"] . ')"></lable></td>';
                                    }

                                    echo ($row['paid_status'] == 0 || $row['paid_status'] == 1 ) ? '<td><input type="hidden" name="credit" id="credit_inv_vo' . $row["id"] . '"  value="' . $row['credits'] . '"  />' . $row["credits"] . '</td>' : '<td>' . $row["credits"] . '</td>';

                                    //   echo '<td>' . $row['credits'] . '</td>';



                                    echo '<td><div class="btn-group"><a href="' . CONFIG_PATH_SITE_ADMIN . 'users_credit_invoices_detail.html?id=' . $row['id'] . '&type=0" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_View')) . '</a> </td>';

                                    // echo ($row['paid_status']==0)?'<td><div class="btn-group"><a href="#" class="btn btn-primary btn-sm">View</a></div></td>':'<td></td>';

                                    echo '</tr>';
                                }
                            } else {

                                echo '<tr><td colspan="8" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
                            }
                            ?>

                        </table>

                    </div>

                </div>

                <div role="tabpanel" class="tab-pane" id="nav-tabs-0-11">

                    <div class="p-t-20">

                            <?php $status = 0; ?>

                        <h4>

                            <?php //$lang->prints('lbl_transactions_summary'); ?>

                        </h4>

                            <?php include("user_mail_log.php"); ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

                            <?php $url1 = CONFIG_PATH_ADMIN . 'user_credit_edit_proccess.php' ?>

<script>

    var apiaccess =<?php echo $apiacess; ?>;
//alert(apiaccess);
    if (apiaccess == 0)
        $("#api_key").hide();
    function edit() {

        $("#amnt").css("background-color", "green");

        $("#amnt").prop("readonly", false);

    }



    function edit_proccess(r) {

        //var trid = $("#r1").

        //alert(r);



        var amount = $("#amnt_inv_vo" + r).val();

        var credit = $("#credit_inv_vo" + r).val();

        var gateway = $("#gt" + r).val();

        //  alert(gateway);

        // var id=

        amount = parseInt(amount);

        if (amount > 0) {

            $("#refresh_inv_vo" + r).attr('class', 'throbber-loader');

            $.ajax({
                url: '<?php echo $url1; ?>',
                data: {id: r, amnt: amount, cr: credit, gt: gateway, type: 0},
                success: function (data) {

                    if (data == '1') {

                        window.location = "users_credit_invoices.html?status=0"

                    } else {

                        $("#refresh_inv_vo" + r).attr('class', 'glyphicon glyphicon-refresh');

                        $("#amnt_inv_vo" + r).val('');

                        $("#gt" + r).val('');

                        $("#lbl" + r).text(data)

                    }

                }

                //change_log_id();

            });

        }

    }



    function edit_proccess_accept(r) {

        //var trid = $("#r1").

        //alert(r);

        var amount = $("#lbl" + r).text();

        var credit = $("#credit_inv_vo" + r).val();

        var gateway = $("#gt" + r).val();

        //  alert(gateway);

        // var id=

        amount = parseInt(amount);

        if (amount > 0) {

            $("#refresh_inv_vo" + r).attr('class', 'throbber-loader');

            $.ajax({
                url: '<?php echo $url1; ?>',
                data: {id: r, amnt: amount, cr: credit, gt: gateway, type: 1},
                success: function (data) {

                    window.location = "users_credit_invoices.html?status=0";

                }

            });

        }

    }

</script>

<?php $url1 = CONFIG_PATH_ADMIN . 'user_api_key_regenrate.php' ?>

<?php
$sql_imei = 'select 

									itm.tool_name,count(oim.id) as count

								from ' . ORDER_IMEI_MASTER . ' oim

								left join ' . IMEI_TOOL_MASTER . ' itm on(oim.tool_id=itm.id)

									where oim.user_id = ' . $id . '

									group by oim.tool_id

									order by count(oim.id) DESC

									limit 8';



//echo $sql_imei;

$sql_lf = 'select count(*) as total,if(oim.status=0,"Pending",if(oim.status=2,"Available",if(oim.status=3,"Reject","Locked"))) as orderstatus from nxt_order_imei_master oim 

        

    where oim.user_id= ' . $id . ' and oim.status!=1 group by oim.status';

//echo $sql_lf;exit;

$qrydata = $mysql->query($sql_lf);

if ($mysql->rowCount($qrydata) > 0) {

    $rows = $mysql->fetchArray($qrydata);

    // echo '<pre>';
    // var_dump($rows);exit;

    $Srvsname1 = $Srvsname2 = $Srvsname3 = 0;

//    
//    $Srvsname1 = $rows[0]['total'];
//    if($Srvsname1=="")
//        $Srvsname1=0;
//    $Srvsname2 = $rows[1]['total'];
//    if($Srvsname2=="")
//        $Srvsname2=0;
//    $Srvsname3 = $rows[2]['total'];
//    if($Srvsname3=="")
//        $Srvsname3=0;
    // new logic

    foreach ($rows as $row_lf) {

        if ($row_lf['orderstatus'] == 'Pending') {

            $Srvsname1 = $row_lf['total'];
        }





        if ($row_lf['orderstatus'] == 'Available') {

            $Srvsname3 = $row_lf['total'];

            //  echo $porders;
            // $avapercantage = $mysql->getInt(($aorders / $totalorders) * 100);
            //  echo $pendingpercantage;
        }



        if ($row_lf['orderstatus'] == 'Reject') {

            $Srvsname2 = $row_lf['total'];

            //  echo $porders;
            //$rejpercantage = $mysql->getInt(($rorders / $totalorders) * 100);
            //  echo $pendingpercantage;
        }
    }
}
?>



<script>

    function reset_key() {

        $("#btn_reg").val('Generating Key.....');

        $.ajax({
            url: '<?php echo $url1; ?>',
            data: {},
            success: function (data) {

                $("#key").val(data);

                $("#btn_reg").val('Regenerate API Key');

                //change_log_id();

            }

        });

    }

</script>
<script>
    function reset_key() {

        $("#btn_reg").val('Generating Key.....');

        $.ajax({
            url: '<?php echo $url1; ?>',
            data: {},
            success: function (data) {
                $("#key").val(data);
                $("#btn_reg").val('Regenerate API Key');
            }

        });
    }

</script>
<script>

    var _sfunc = function () {
        var data = {
            labels: [
                "Pending",
                "Rejected",
                "Available"

            ],
            datasets: [
                {
                    data: [<?php echo $Srvsname1; ?>, <?php echo $Srvsname2; ?>, <?php echo $Srvsname3; ?>],
                    backgroundColor: [
                        "#0F125F",
                        "#F7464A",
                        "#5cb85c"

                    ],
                    hoverBackgroundColor: [
                        "#FF5A5E",
                        "#FF5A5E",
                        "#81c868"

                    ]

                }]

        };



        var ctx = document.getElementById("myChart").getContext("2d");

// And for a doughnut chart

        new Chart(ctx, {
            type: "doughnut",
            data: data,
            animation: {
                animateScale: true

            }

        });
    }
    $(window).load(function () {

        _sfunc();

    });

    if (_winLoad == true)
        _sfunc();
// And for a doughnut chart
</script>

<script>
    $(document).on('click', '#user_password', function (e) {
        passwordrst();
    });
    var userid =<?php echo $id; ?>;

    function passwordrst()

    {

        //var url='<?php echo $page; ?>';

        //url=url.replace('#page','');



        //$('#loading').css('visibility','visible');

        //url='chat.html';

        $.ajax({
            type: "POST",
            url: "<?php echo CONFIG_PATH_SITE_ADMIN; ?>user_password_change.do",
            //data: 'page='+url,

            data: "&u_id=" + userid,
            //dataType: "html",

            success: function (msg) {

                if (msg == "done")
                    alert("Password reset successfully and email sent to the user");

                else
                    alert("Some error");

            }
        });
    }
    function change(p) {

        if ($(p).prop("checked") == true) {

            $("#api_key").show();

        } else {

            $("#api_key").hide();

        }
    }

</script>