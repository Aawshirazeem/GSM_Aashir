<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$admin = new admin();
	$mysql = new mysql();
?>
<div class="row">
	<div class="col-lg-12">
		<ul class="breadcrumb">
			<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Master'); ?></li>
			<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Home Page Banner'); ?></li>
		</ul>
	</div>
</div>
<section class="panel MT10">
		<div class="panel-heading">
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_home_page_banner')); ?>
			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>config_banners_add.html" class="btn btn-danger btn-xs pull-right"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_add_banner')); ?> </a>
		</div>
</section>
<div class="row">
		<?php
			$sql= 'select * from ' . BANNER_MASTER . '';
			$banners = $mysql->getResult($sql);
			if($banners['COUNT'] > 0)
			{
				foreach($banners['RESULT'] as $banner)
				{
					$imgName = $banner['file_name'];
					$filePathAbsolute = CONFIG_PATH_THEME_ABSOLUTE . 'images/banners/' . $imgName;
					$filePath = CONFIG_PATH_THEME . 'images/banners/' . $imgName;
					$filePathNotFound = CONFIG_PATH_THEME . 'images/banners/driver_avatar.jpg';
					$img = '';
					if(file_exists($filePathAbsolute))
					{
						$img = '<img src="' . $filePath . '" width="100%">';
					}
					else
					{
						$img = '<img src="' . $filePathNotFound . '" width="100%">';
					}
					
					
					echo '
						<div class="col-sm-4">
							<div class="panel text-center">
								<div class="panel-body">
									' . $img .'
								</div>
								<div class="panel-body">
									<div class="btn-group btn-group-justified">
										<a href="' . CONFIG_PATH_SITE_ADMIN . 'config_banners_edit.html?id=' . $banner['id'].'" class="btn btn-white btn-sm"><i class="icon-edit"></i> Update</a>
										<a href="' . CONFIG_PATH_SITE_ADMIN . 'config_banners_remove.do?id=' . $banner['id'].'&delete=1" class="btn btn-danger btn-sm"><i class="icon-remove"></i> Remove</a>
									</div>
								</div>
							</div>
						</div>
					';
				}
			}
		?>
	<div class="col-sm-4">
		<div class="panel text-center">
			<div class="panel-body">
				<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_banners_add.html" class='btn btn-white' style="padding:84px 119px;"><i class="icon-plus"></i><br/><?php echo $admin->wordTrans($admin->getUserLang(),'Add New Banner'); ?></a>
			</div>
		</div>
	</div>

</div>
