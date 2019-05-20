<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetAdmin('service_imei_file_edit_14832342');



// add a server for customizd
$sql = 'select a.id,a.api_server,a.server_id from ' . API_MASTER . ' a

where a.api_server="Custom API"';

$query = $mysql->query($sql);

$rowCount = $mysql->rowCount($query);

if ($rowCount == 0) {

    $sql = 'insert into ' . API_MASTER . ' (server_id, api_server, url, url_edit, username, username_edit, `key`, key_edit, requires_sync, status, is_visible)
					values(
					' . 15 . ',
					"Custom API",
					' . $mysql->quote($url) . ',
					1,
					' . $mysql->quote($username) . ',
					1,
					' . $mysql->quote($key) . ',
					1,
					1,
					1,
					1)';

    $mysql->query($sql);
    $id = "SELECT LAST_INSERT_ID();";
    $id = $mysql->query($id);
    $result = $mysql->fetchArray($id);
    $s_id = $result[0]['LAST_INSERT_ID()'];
} else {
    $rows = $mysql->fetchArray($query);
    $row = $rows[0];
    $s_id = $row['id'];
}
?>
<div class="row m-b-20">
    <div class="col-xs-12">
        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_custom.html"><i class="fa fa-book"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_API_Custom')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Add_New_Customized_API')); ?></li>
        </ol>
    </div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_add_cust_process.do" method="post">
    <div class="row">
        <div class="col-md-6">
            <h4 class="m-b-20">
                <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Add_New')); ?>
            </h4>
            <input type="hidden" name="api_id" value="<?php echo $s_id; ?>"/>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_name')); ?></label>
                <input name="api_server" type="text" class="form-control required" id="api_server" value="" />

            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_url')); ?> </label>
                <input name="url" type="text" class="form-control required" id="url" value="" />
            </div>

            <div class="form-group">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_custom.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_save')); ?>" name="submit" class="btn btn-success btn-sm" />
            </div>
        </div>
    </div>
</form>