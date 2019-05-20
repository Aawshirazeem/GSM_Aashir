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
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Master'); ?></li>
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_banners.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_home_page_banner')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Add Banner'); ?></li>
		</ul>
	</div>
</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_banners_add_process.do" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_add_news')); ?></div>
				<div class="panel-body">
					<div class="form-group">
						<label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_banner')); ?></label>
						<input name="image" type="file" class="form-control" id="image" value="" />
						<p class="help-block">Width: 1140px & Height: 500px</p>
					</div>
					<div class="form-group">
						<label class="checkbox-inline"> <input type="checkbox" name="publish" checked="checked" value="0" /> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_publish_news')); ?> </label>
					</div>
					<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>config_banners.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
					<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_submit')); ?>" class="btn btn-success btn-sm" />
				</div> <!-- / panel-body -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
</form>
