<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('suppliers_add_549883whh2');

         $input = $_SERVER['HTTP_HOST'];
    $input = trim($input, '/');
    if (!preg_match('#^http(s)?://#', $input)) {
        $input = 'http://' . $input;
    }
    $urlParts = parse_url($input);
    $domain = preg_replace('/^www\./', '', $urlParts['host']);

        $con = mysqli_connect("185.27.133.16","gsmunion_upuser","S+OXupg8lqaW","gsmunion_upload");

$qry_check='select * from tbl_users where  domain LIKE "%'.$domain.'%"  and supplier_panel=0';



  $result = $con->query($qry_check);



if ($result->num_rows > 0) { 

                                     echo("<script>location.href = '../un_authrize.php';</script>");
                                    // echo 'Sorry You cannot access this page.'

                                        exit;

                                  }

?>

<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>           

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_supp')); ?></li>

        </ol>

    </div>

</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers_add_process.do" method="post">

	<div class="row m-b-20">

		<div class="col-md-8">

			<div class="">

				<h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_login_details')); ?></h4>

				<div class="panel-body">

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></label>

						<input name="username" type="text" class="form-control checkUserNameSupplier" id="username" value="" />

						<img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="usernameWaitSupplier" />

						<img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/ok_16.png" alt="Available" height="16" width="16" class="hidden" id="usernameAvailSupplier" />

						<img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/cross_16.png" alt="Not Available" height="16" width="16" class="hidden" id="usernameNotAvailSupplier" />

					</div>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_password')); ?></label>

						<input name="password" type="text" class="form-control" id="password" />

						<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_password_for_the_above_login_email')); ?></p>

					</div>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?></label>

						<input name="email" type="text" class="form-control checkEmailSupplier" id="email" value="" />

						<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_note:_nobody_can_change_it_once_registered')); ?></p>

						<img src="<?php echo CONFIG_PATH_IMAGES; ?>wait.gif" alt="Please wait..." height="16" width="16" class="hidden" id="emailWaitSupplier" />

						<img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/ok_16.png" alt="Available" height="16" width="16" class="hidden" id="emailAvailSupplier" />

						<img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/cross_16.png" alt="Not Available" height="16" width="16" class="hidden" id="emailNotAvailSupplier" />

					</div>

					<legend><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_access_rights')); ?></legend>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service_access')); ?></label>

						<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_is_supplier_can_access_imei_unlocking_services?')); ?></p>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="service_imei" value="1"> <span class="c-indicator c-indicator-success"></span> <span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?></span></label>                  		<label class="checkbox-inline c-input c-radio"><input type="radio" name="service_imei" value="0" checked="checked"> <span class="c-indicator c-indicator-success"></span> <span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?></span></label>

					</div>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_system_access')); ?></label>

						<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_is_supplier_can_access_file_services?')); ?></p>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="service_file" value="1"><span class="c-indicator c-indicator-success"></span> <span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?></span></label>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="service_file" value="0" checked="checked"><span class="c-indicator c-indicator-success"></span> <span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?></span></label>

					</div>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs_access')); ?></label>

						<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_is_supplier_can_access_server_logs?')); ?></p>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="service_logs" value="1"><span class="c-indicator c-indicator-success"></span> <span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?></span></label>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="service_logs" value="0" checked="checked"><span class="c-indicator c-indicator-success"></span> <span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?></span></label>

					</div>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_online_store_access')); ?></label>

						<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_is_supplier_can_purchase_any_services/products_from_online_store?')); ?></p>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="service_shop" value="1"><span class="c-indicator c-indicator-success"></span> <span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?></span></label>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="service_shop" value="0" checked="checked"><span class="c-indicator c-indicator-success"></span> <span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?></span></label>

					</div>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_status')); ?></label>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="1" checked="checked"> <span class="c-indicator c-indicator-success"></span><i style="color:#006600"> <span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></span></i></label>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="0"> <span class="c-indicator c-indicator-success"></span> <i><span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></i></span></label>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="2" disabled="disabled" ><span class="c-indicator c-indicator-success"></span>  <i style="color:#888888"><span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_suspend')); ?></i></span></label>

					</div>

					<div class="form-group">

						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>suppliers.html" class="btn btn-danger btn-sm"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_create_account')); ?>" class="btn btn-success btn-sm"/>

					</div>

				</div> <!-- / panel-body --> <!-- / panel-footer -->

			</div> <!-- / panel -->

		</div> <!-- / col-lg-6 -->

	</div> <!-- / row -->



  </form>

