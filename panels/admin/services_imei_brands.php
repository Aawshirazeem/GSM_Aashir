<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	

	$group_id = $request->getInt('group_id');

	

	$sql='select ibm.*,(select count(id) from ' . IMEI_MODEL_MASTER . ' imm where imm.brand = ibm.id) as totalModels from ' . IMEI_BRAND_MASTER . ' ibm';

	$query = $mysql->query($sql);

	$i = 1;

	$count = $mysql->rowCount($query);

?>



<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Utilities')); ?></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_brand_master')); ?></li>

        </ol>

    </div>

</div>



	<div class="row">

		<h4 class="panel-heading m-b-20">

			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_brand_master')); ?>

			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_brands_add.html" class="btn btn-danger btn-sm pull-right"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_brand')); ?> </a>

		</h4>
		
	<div class="table-responsive">	

		<table class="table table-hover table-striped">

			<tr>

				<th width="16"></th>

				<th width="16"></th>

				<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_brands')); ?> </th>

				<th width="150"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_total_models')); ?> </th>

				<th width="200"></th>

			</tr>

		    <?php

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

						echo '<td>' . $row['brand'] . '</td>';

						echo '<td>' . (($row['totalModels'] == "0") ? '-' : $row['totalModels']) . '</td>';

						echo '<td class="text-right">

								<div class="btn-group">

									<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_brands_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm"> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>

									<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_models.html?brand_id=' . $row['id'] . '" class="btn btn-default btn-sm">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_models')).'</a>

								</div>

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

