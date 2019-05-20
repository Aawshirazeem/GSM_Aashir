<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('service_imei_file_edit_14832342');
?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_list.html"><i class="fa fa-book"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_API_Master')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Add_New_API')); ?></li>
        </ol>
    </div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_add_process.do" method="post">
	<div class="row">
    	<div class="col-md-12">
        	<h4 class="m-b-20 col-md-12">
            	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Add_New_API')); ?>
            </h4>
            <div class="form-group col-md-6">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_api_server_duplicate')); ?> </label>
                <select name="server_id" class="form-control">
                    <option value="1">GsmUnion Fusion API</option>
                    <option value="8">Dhru Fusion API</option>
                    <option value="12">Codesk API</option>
                    <option value="9">UnlockBase API</option>
                    <option value="13">Ubox API</option>
                    
                </select>
            </div>
            <div class="form-group col-md-6">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_name')); ?></label>
                <input name="api_server" type="text" class="form-control required" id="api_server" value="" />
                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_for_refrence')); ?></p>
            </div>
            <div class="form-group col-md-6">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_url')); ?> </label>
                <input name="url" type="text" class="form-control required" id="url" value="" />
            </div>
            <div class="form-group col-md-6">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?> </label>
                <input name="username" type="text" class="form-control required" id="username" value="" />
            </div>
            <div class="form-group col-md-12">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_api_key')); ?> </label>
                <input name="key" type="text" class="form-control required" id="key" value="" />
            </div>
            <div class="form-group col-md-12">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>api_list.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_save')); ?>" name="submit" class="btn btn-success btn-sm" />
            </div>
        </div>
    </div>
</form>