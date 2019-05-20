<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$validator->formSetAdmin('con_server_log__edit_148654312');



	$id = $request->GetInt('id');



	

	$sql ='select * from ' . SERVER_LOG_GROUP_MASTER . ' where id=' . $mysql->getInt($id);

	$query = $mysql->query($sql);

	$rowCount = $mysql->rowCount($query);

	if($rowCount == 0)

	{

		header("location:" . CONFIG_PATH_SITE_ADMIN . "server_logs_group.html?reply=" . urlencode('reply_invalid_id'));

		exit();

	}

	$rows = $mysql->fetchArray($query);

	$row = $rows[0];

?>



<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>

            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs')); ?></a></li>

            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs_group.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_group_manager')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_group_details')); ?></li>

        </ol>

    </div>

</div>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs_group_edit_process.do" method="post">

	<div class="row">

		<div class="col-md-6">

			<div class="">

				<h4 class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log_group_details')); ?></h4>

				<div class="panel-body">

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_group_name')); ?> </label>

						<input name="group" type="text" class="form-control" id="group" value="<?php echo $mysql->prints($row['group_name'])?>" />

						<input name="id" type="hidden" class="form-control" id="id" value="<?php echo $row['id']?>" />

					</div>

					<div class="form-group">

						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_group_status')); ?> </label>

						<select name="status" id="status" class="form-control">

							<option value="0" <?php echo (($row['status'] == '0') ? 'selected="selected"' : '');?> ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?> </option>

							<option value="1" <?php echo (($row['status'] == '1') ? 'selected="selected"' : '');?> ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?> </option>

						</select>

					</div>

					<div class="form-group">

						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>server_logs_group.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_update_group')); ?>" class="btn btn-success btn-sm" />

					</div>

				</div> <!-- / panel-body -->

<!-- / panel-footer -->

			</div> <!-- / panel -->

		</div> <!-- / col-lg-6 -->

	</div> <!-- / row -->

</form>

