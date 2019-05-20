<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$request = new request();
	$mysql = new mysql();
	
    
    
	$id = $request->GetInt('id');

	
	$sql ='select * from ' . BANNER_MASTER . ' where id=' . $mysql->getInt($id);
	$banners = $mysql->getResult($sql);
	if($banners['COUNT'] == 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "config_news.html?msg=" . urlencode('Invalid news ID, Please try again!') . "&msgType=" . urlencode('error'));
		exit();
	}
	$banner = $banners['RESULT'][0];
	
	$imgName = $banner['file_name'];
	$filePathAbsolute = CONFIG_PATH_THEME_ABSOLUTE . 'images/banners/' . $imgName;
	$filePath = CONFIG_PATH_THEME . 'images/banners/' . $imgName;
	$filePathNotFound = CONFIG_PATH_THEME . 'images/banners/driver_avatar.jpg';
	$img = '';
	if(file_exists($filePathAbsolute))
	{
		$img = '<img src="' . $filePath . '" height="120">';
	}
	else
	{
		$img = '<img src="' . $filePathNotFound . '" height="120">'; 
	}
	
?>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_banners_edit_process.do" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_banner')); ?></div>
				<div class="panel-body">
					<div class="form-group">
						<label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_news_title')); ?> </label>
						<div class="row">
							<div class="col-sm-6">
								<?php echo $img;?>
							</div>
							<div class="col-sm-6">
								<input name="image" type="file" class="form-control" id="image" />
								<input name="id" type="hidden" id="id" value="<?php echo $banner['id']?>" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="checkbox-inline"> <input type="checkbox" name="publish" value="0" <?php echo (($banner['status'] == '1') ? 'checked="checked"' : '');?> /> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_publish')); ?> </label>
					</div>
					<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>config_banners.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
					<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_submit')); ?>" class="btn btn-success btn-sm" />
				</div> <!-- / panel-body -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->

  </form>
