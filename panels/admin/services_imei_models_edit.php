<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('services_imei_model_edit_534g6ghh2');



	$id = $request->GetInt('id');

	$brand_id = $request->GetInt('brand_id');

	

	$sql ='select * from ' . IMEI_MODEL_MASTER . ' where id=' . $mysql->getInt($id);

	$query = $mysql->query($sql);

	$rowCount = $mysql->rowCount($query);

	if($rowCount == 0)

	{

		header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_models.html?reply=" . urlencode('reply_success_service_imei_models'));

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

            

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_brands.html"><i class="fa fa-book"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_brand_master')); ?></a></li>

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_models.html?brand_id=<?php echo $brand_id; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_models')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Setting')); ?></li>

        </ol>

    </div>

</div>





<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_models_edit_process.do" method="post">

	<div class="row">

		<div class="col-md-6">

			<div class="">

				<h4 class="panel-heading m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_model_details')); ?></h4>

				<div class="panel-body">



					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_model_name')); ?> </label>

						<input name="model" type="text" class="form-control" id="model" value="<?php echo $row['model']?>" />

						<input name="id" type="hidden" id="id" value="<?php echo $row['id']?>" />

						<input name="brand_id" type="hidden" id="brand_id" value="<?php echo $brand_id;?>" />

						<input name="filetype" type="hidden" id="filetype" value="html" />

					</div>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?> </label>

						<label class="checkbox-inline c-input c-radio" ><input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : '');?> /><span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></label>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : '');?> /><span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></label>

					</div>

				<div class="form-group">

					<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_models.html?brand_id=<?php echo $brand_id;?>" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

					<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_model')); ?>" class="btn btn-success btn-sm" />

				</div>

				

				</div> <!-- / panel-body -->

				 <!-- / panel-footer -->

			</div> <!-- / panel -->

		</div> <!-- / col-lg-6 -->

	</div> <!-- / row -->

</form>

