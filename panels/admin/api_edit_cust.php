<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined("_VALID_ACCESS") or die("Restricted Access");



$id = $request->GetInt('id');



//$sql = 'select * from ' . API_MASTER . ' where id=' . $mysql->getInt($id) . ' and is_visible=1';
$sql='select a.id,a.service_name,a.info from ' . API_DETAILS . ' a where a.id='.$id;

$query = $mysql->query($sql);

$rowCount = $mysql->rowCount($query);

if ($rowCount == 0) {

    header("location:" . CONFIG_PATH_SITE_ADMIN . "api_custom.html?reply=" . urlencode('reply_invalid_id'));

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
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_custom.html"><i class="fa fa-book"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Custom_API')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_edit_Customized_API')); ?></li>
        </ol>
    </div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_edit_cust_process.do" method="post">
    <div class="row">
        <div class="col-md-6">
            <h4 class="m-b-20">
                <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Edit')); ?>
            </h4>
            <input type="hidden" name="api_id" value="<?php echo $id; ?>"/>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_name')); ?></label>
                <input name="api_server" type="text" class="form-control required" id="api_server" value="<?php echo $row['service_name'];?>" />

            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_url')); ?> </label>
                <input name="url" type="text" class="form-control required" id="url" value="<?php echo $row['info'];?>" />
            </div>

            <div class="form-group">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_custom.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_save')); ?>" name="submit" class="btn btn-success btn-sm" />
            </div>
        </div>
    </div>
</form>