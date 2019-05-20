<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$request = new request();
$mysql = new mysql();
$admin = new admin();
?>
<div class="row">
    <div class="col-lg-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="active">CMS</li>
            <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>cms_pages.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_cms_pages')); ?></a></li>
            <li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_page')); ?></li>
        </ul>
    </div>
</div>