<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('services_imei_brand_148332412');

?>



<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Utilities')); ?></li>

            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_brands.html"><i class="fa fa-book"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_brand_master')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_brand')); ?></li>

        </ol>

    </div>

</div>



<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_brands_add_process.do" method="post">

	<div class="row">

		<div class="col-md-6">

			<div class="panel">

<!--				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_brand_details')); ?></div>-->

				<div class="panel-body">

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_brand_name')); ?> </label>

						<input name="brand" type="text" class="form-control" id="brand" value="" />

					</div>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?> </label>

                        

                        

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="1" checked="checked"/><span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></label>

						<label class="checkbox-inline c-input c-radio"><input type="radio" name="status" value="0" /><span class="c-indicator c-indicator-success"></span><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></label>

					</div>

					<div class="form-group">

						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_brands.html" class="btn btn-danger btn-sm"> <i class="icon-arrow-left"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_brand')); ?>" class="btn btn-success btn-sm" />

					</div> 

				</div> <!-- / panel-body -->

				<!-- / panel-footer -->

			</div> <!-- / panel -->

		</div> <!-- / col-lg-6 -->

	</div> <!-- / row -->

</form>

