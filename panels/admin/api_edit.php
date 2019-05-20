<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('service_imei_file_edit_14832342');

	

	$id = $request->GetInt('id');

	

	$sql ='select * from ' . API_MASTER . ' where id=' . $mysql->getInt($id) . ' and is_visible=1';

	$query = $mysql->query($sql);

	$rowCount = $mysql->rowCount($query);

	if($rowCount == 0)

	{

		header("location:" . CONFIG_PATH_SITE_ADMIN . "api_list.html?reply=" . urlencode('reply_invalid_id'));

		exit();

	}

	$rows = $mysql->fetchArray($query);

	$row = $rows[0];

?>

<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_list.html"><i class="fa fa-book"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_API_Master')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_edit_api')); ?></li>

        </ol>

    </div>

</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_edit_process.do" method="post">

	<div class="row">

    	<div class="col-md-12">

        	<h4 class="m-b-20 col-md-12"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_edit_api_details')); ?></h4>

            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_api_server_name')); ?> </label>

                <input name="api_server" type="text" class="form-control" id="api_server" value="<?php echo $row['api_server']?>" />

                <input name="id" type="hidden" id="id" value="<?php echo $row['id']?>" />

            </div>

            <?php if($row['url_edit'] == '1'){ ?>

            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_url')); ?> </label>

                <input name="url" type="text" class="form-control required" id="url" value="<?php echo $row['url']?>" />

            </div>

            <?php } ?>

            <?php if($row['key_edit'] == '1'){ ?>

            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_api_key')); ?> </label>

                <input name="key" type="text" class="form-control required" id="key" value="<?php echo $row['key']?>" />

            </div>

            <?php } ?>

            <?php if($row['username_edit'] == '1'){ ?>

            <div class="form-group col-md-6">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?> </label>

                <input name="username" type="text" class="form-control required" id="username" value="<?php echo $row['username']?>" />

            </div>

            <?php } ?>

            <?php if($row['password_edit'] == '1'){ ?>

            <div class="form-group col-md-4">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_password_*')); ?> </label>

                <input name="password" type="password" class="form-control" required="" id="password" value="<?php echo $row['password']?>" />

            </div>

            <?php } ?>

            <div class="form-group col-md-3">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_generate_extra_crons')); ?> </label>

                <label class="c-input c-checkbox">

                	<input type="checkbox" name="is_special" value="1" <?php echo (($row['is_special'] == '1') ? 'checked="checked"' : '');?> />

                	<span class="c-indicator c-indicator-default"></span>

                </label>

            </div>

            <div class="form-group col-md-4">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?></label>

                <label class="c-input c-radio">

                	<input name="status" type="radio" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : '');?> />

                    <span class="c-indicator c-indicator-success"></span>

                    <span class="c-input-text color-success"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?> </span>

                </label>

                

                <label class="c-input c-radio">

                	<input name="status" type="radio" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : '');?> />

                    <span class="c-indicator c-indicator-primary"></span>

                    <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?> </span>

                </label>               

            </div>

            <div class="form-group">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Sync_file_service')); ?></label>

                <label class="c-input c-radio">

                	<input name="file_service" type="radio" value="1" <?php echo (($row['file_service'] == '1') ? 'checked="checked"' : '');?> />

                    <span class="c-indicator c-indicator-default"></span>

                    <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_yes')); ?> </span>

                </label>

                <label class="c-input c-radio">

                	<input name="file_service" type="radio" value="0" <?php echo (($row['file_service'] == '0') ? 'checked="checked"' : '');?> />

                    <span class="c-indicator c-indicator-default"></span>

                    <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_no')); ?> </span>

                </label>

            </div>

            <?php if($row['requires_sync'] == 1){ ?>

            <div class="form-group hidden">

                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_synchronize')); ?></label>

                <div class="clearfix"></div>

                <label class="c-input c-checkbox"><input type="checkbox" name="chk_services" value="1" checked="checked" /><span class="c-indicator c-indicator-default"></span><span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_synchronize_all_services')); ?> </span></label>

                <div class="clearfix"></div>

                

                <label class="c-input c-checkbox"><input type="checkbox" name="chk_brand" value="1" /><span class="c-indicator c-indicator-default"></span><span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_synchronize_brand/model_list')); ?>* </span></label>

                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_delete_all_my_current_brand/model_list')); ?></p>

                

                <label class="c-input c-checkbox"><input type="checkbox" name="chk_country" value="1" /><span class="c-indicator c-indicator-default"></span><span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_synchronize_country/network_list')); ?>^ </span></label><br />

                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_delete_all_my_country/network_list')); ?></p>

                

                <label class="c-input c-checkbox"><input type="checkbox" name="chk_mep" value="1" /><span class="c-indicator c-indicator-default"></span><span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_synchronize_mep_list')); ?> </span></label>

                <?php } ?>                

            </div>

            <div class="form-group">

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>api_list.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

                <?php

                    if($row['requires_sync'] == 1)

                    {

                        echo '<input type="submit" name="submit" value="'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_save_&_sync')).'" class="btn btn-default btn-sm" />';

                    }

                ?>

                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_save')); ?>" name="submit" class="btn btn-success btn-sm" />

            </div>

        </div>

    </div>

</form>