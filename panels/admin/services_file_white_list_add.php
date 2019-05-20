<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('service_file_white_list_add_84utjktr9ju');
        $s_id=$request->getInt('s_id');

?>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file_white_list_add_process.do" method="post">
    <input type="hidden" name="ser_id" value="<?php echo $s_id;?>">

<div class="lock-to-top">

	<h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_file_extension')); ?></h4>

	<div class="btn-group">

	</div>

</div>

	<div class="row">

		<div class="col-md-6">

			<div class="">

				<?php /*?><h4 class="m-b-20"><?php $lang->prints('lbl_add_file_extension'); ?></h4><?php */?>

				<div class="panel-body">

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_extensions')); ?></label>

						<div class="input-group">

							<span class="input-group-addon"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_name')); ?>.</span>

							<input name="file_ext" type="text" class="form-control" maxlength="5"  />

						</div>

					</div>

					<div class="form-group">

						<label class="checkbox-inline c-input c-checkbox"> <input name="status" type="checkbox" checked="checked"  /> <span class="c-indicator c-indicator-warning"></span><span class="c-input-text"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?> </span> </label>

					</div>

				</div> <!-- / panel-body -->

				<div class="panel-footer">

					<input type="submit" class="btn btn-success" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_extension')); ?>" />

				</div> <!-- / panel-footer -->
				
				
				
				
				
				
				
				

			</div> <!-- / panel -->

		</div> <!-- / col-lg-6 -->
		<div class="col-md-12">
				<br>
				<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_file_white_list.html?s_id=<?php echo $s_id;?>"> << <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Back')); ?></a>
		</div> 
	</div> <!-- / row -->

	

	

	

    <fieldset>



    </fieldset>

  </form>

