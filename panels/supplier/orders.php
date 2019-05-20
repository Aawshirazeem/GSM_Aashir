<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$supplier->checkLogin();
	$supplier->reject();
?>

<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_s_manager')); ?></h1>
<br /><br /><br /><br />
<div class="butSkin" style="text-align:center;">
	<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_imei.html" style="padding:10px 10px 10px 10px;"><img src="<?php echo CONFIG_PATH_IMAGES;?>skin/tool_32.png" width="32" height="32" alt=""><br /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_order')); ?></a>
	&nbsp; <a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_file.html" style="padding:10px 10px 10px 10px;"><img src="<?php echo CONFIG_PATH_IMAGES;?>skin/file_32.png" width="32" height="32" alt=""><br /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_order')); ?></a>
	&nbsp; <a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_server_log.html" style="padding:10px 10px 10px 10px;"><img src="<?php echo CONFIG_PATH_IMAGES;?>skin/server_32.png" width="32" height="32" alt=""><br /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_s_order')); ?></a>
	&nbsp; <a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_shop.html" style="padding:10px 10px 10px 10px;"><img src="<?php echo CONFIG_PATH_IMAGES;?>skin/cart_32.png" width="32" height="32" alt=""><br /><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_online_shop')); ?></a>
</div>