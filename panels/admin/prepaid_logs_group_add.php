<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('con_pre_log__group_14887372');

?>



    

<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>

            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs.html"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_logs')); ?></a></li>

             <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_groups')); ?></li>

        </ol>

    </div>

</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>prepaid_logs_group_add_process.do" method="post">

	<div class="row">

		<div class="col-md-6">

			<div class="">

				<h4 class="panel-heading m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_log_group_details')); ?></h4>

				<div class="panel-body">

					

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_group_name')); ?> </label>

						<input name="group" type="text" class="form-control" id="group" />

						<input name="id" type="hidden" id="id" value="<?php echo $row['id']?>" />

					</div>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_group_status')); ?> </label>

						<select name="status" id="status" class="form-control">

							<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></option>

							<option value="1" selected="selected"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?> </option>

						</select>

					</div>

					<div class="from-groups">

						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>prepaid_logs_group.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_add_group')); ?>" class="btn btn-success btn-sm" />

					</div>

					

				</div> <!-- / panel-body -->

 <!-- / panel-footer -->

			</div> <!-- / panel -->

		</div> <!-- / col-lg-6 -->

	</div> <!-- / row -->



</form>

