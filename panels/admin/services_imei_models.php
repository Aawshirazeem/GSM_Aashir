<?php

	defined("_VALID_ACCESS") or die("Restricted Access");



	$brand_id = $request->getInt('brand_id');

	

	$sql='select * from ' . IMEI_MODEL_MASTER . ' where brand=' . $mysql->getInt($brand_id);

	$query = $mysql->query($sql);

	$i = 1;

	$count = $mysql->rowCount($query);

?>



<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Utilities')); ?></li>

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_brands.html"><i class="fa fa-book"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_brand_master')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Models')); ?></li>

        </ol>

    </div>

</div>



	<div class="row">

		<h4 class="m-b-20">

			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_model_manager')); ?>

		<div class="btn-group pull-right">

			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_brands.html" class="btn btn-default btn-sm"> <i class="fa fa-arrow-left"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_back')); ?></a>

			<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>services_imei_models_add.html?brand_id=<?php echo $brand_id;?>" class="btn btn-danger btn-sm"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_model')); ?></a>

		</div>

		</h4>



	<table class="table table-hover table-striped">

		<tr>

			<th width="16"> </th>

			<th width="16"></th>

			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Models')); ?> </th>

			<th width="90"></th>

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

					echo '<td>' . $row['model'] . '</td>';

					echo '<td class="text-right">

							<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_models_edit.html?id=' . $row['id'] . '&brand_id=' . $brand_id . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')). '</a>

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

	

