<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('services_imei_brands_edit_1483412');

	

	$mysql = new mysql();

	

	$id = $request->GetInt('id');



	$sql ='select * from ' . IMEI_BRAND_MASTER . ' where id=' . $mysql->getInt($id);

	$query = $mysql->query($sql);

	$rowCount = $mysql->rowCount($query);

	if($rowCount == 0)

	{

		header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_brands.html?reply=" . urlencode('msg_brand_update'));

		exit();

	}

	$rows = $mysql->fetchArray($query);

	$row = $rows[0];

?>





<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Utilities')); ?></li>

            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_brands.html"><i class="fa fa-book"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_brand_master')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Edit_Brand_Detail')); ?></li>

        </ol>

    </div>

</div>



<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_brands_edit_process.do" method="post">



	<div class="row">

		<div class="col-md-6">

			<div class="">

				<h4 class="panel-heading m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_edit_IMEI_brand_details')); ?></h4>

				<div class="panel-body">

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_brand_name')); ?> </label>

						<input name="brand" type="text" class="form-control" id="brand" value="<?php echo $row['brand']?>" />

						<input name="id" type="hidden" id="id" value="<?php echo $row['id']?>" />

					</div>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?> </label>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : '');?> /> <span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></label>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : '');?> /> <span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></label>

					</div>

					<div class="form-group">

						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_brands.html" class="btn btn-danger btn-sm"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

						<input type="submit" class="btn btn-success btn-sm" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_brand')); ?>" />

					</div> <!-- / panel-footer -->



				</div> <!-- / panel-body -->

			</div> <!-- / panel -->

		</div> <!-- / col-lg-6 -->

	</div> <!-- / row -->



</form>

