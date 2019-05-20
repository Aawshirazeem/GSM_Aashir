<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$mysql = new mysql();

	

	$username = $request->GetStr("username");

	$email = $request->GetStr("email");

	

	$displaySearch = false;

	if($username != '' or $email != '')

	{

		$displaySearch = true;

	}

?>



<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_users')); ?></a></li>

            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_suppliers')); ?></a></li>

        </ol>

    </div>

</div>



	<div class="row">

		<div class="col-sm-8">

			<div class="">

				<h4 class="m-b-20">

					<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_supplier_payment')); ?>

				</h4>

				<table class="table table-striped table-hover">

					<tr>

						<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_date')); ?></th>

						<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_amount')); ?></th>

						<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_comments')); ?></th>

					</tr>

					<?php



						$sql = 'select * from '. SUPPLIER_PAYMENT .' order by id desc';

						$query = $mysql->query($sql);

						$i = 1;

						if($mysql->rowCount($query) > 0)

						{

							$rows = $mysql->fetchArray($query);

							foreach($rows as $row)

							{

								echo '<tr>';

								echo '<td>' . $mysql->prints($row['date_time']) . '</td>';

								echo '<td>' . $mysql->prints($row['credits_paid']) . '</td>';

								echo '<td>' . $mysql->prints($row['comments']) . '</td>';

								echo '</tr>';

							}

						}

						else

						{

							echo '<tr><td colspan="7" class="no_record">'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';

						}

					?>

				</table>

			</div>

		</div>

	</div>

