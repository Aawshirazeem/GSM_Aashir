<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	

	$group_id = $request->getInt('group_id');

?>

  



<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>

            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_group_manager')); ?></li>

        </ol>

    </div>

</div>

	

	<div class="row">

		<div class="col-sm-8">

			<div class="">

					<h4 class="panel-heading">

						<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_group_manager')); ?>

						<div class="btn-group pull-right m-b-20">

							<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>server_logs_group_add.html?group_id=<?php echo $group_id?>" class="btn btn-danger btn-sm"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_server_log_group')); ?> </a>

						</div>

					</h4>

				<table class="table table-hover table-striped">

				<tr>

					<th width="16"></th>

					<th width="16"></th>

					<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log_group_name')); ?> </th>

					<th width="80"></th>

				</tr>

				<?php

					$sql= 'select slm.* from ' . SERVER_LOG_GROUP_MASTER . ' slm order by slm.id';

					$query = $mysql->query($sql);

					$strReturn = "";

					if($mysql->rowCount($query) > 0)

					{

						$rows = $mysql->fetchArray($query);

						$i = 0;

						foreach($rows as $row)

						{

							$i++;

							echo '<tr>';

							echo '<td>' . $i . '</td>';

							echo '<td>' . $graphics->status($row['status']) . '</td>';

							echo '<td>' . $mysql->prints($row['group_name']) . '</td>';

							echo '<td class="text-right">

									<a href="' . CONFIG_PATH_SITE_ADMIN . 'server_logs_group_edit.html?id=' . $row['id'] . '&group_id=' . $group_id . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>

								  </td>';

							echo '</tr>';

						}

					}

					else

					{

						echo '<tr><td colspan="8" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';

					}

				?>

				</table>

			</div>

		</div>

	</div>