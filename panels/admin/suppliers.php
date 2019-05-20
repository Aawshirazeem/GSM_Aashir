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

          $con = mysqli_connect("185.27.133.16","gsmunion_upuser","S+OXupg8lqaW","gsmunion_upload");

$qry_check='select * from tbl_users where  domain="'.$_SERVER['HTTP_HOST'].'" and supplier_panel=0';



  $result = $con->query($qry_check);



if ($result->num_rows > 0) { 

                                     echo("<script>location.href = '../un_authrize.php';</script>");

                                        exit;

                                  }

?>



    <div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_suppliers')); ?></li>

        </ol>

    </div>

</div>



	<div class="MT10">

		<h4 class="panel-heading">

			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_suppliers')); ?>

			<div class="btn-group pull-right m-b-20">

				<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>suppliers_add.html" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_supplier')); ?></a>

			</div>

		</h4>

		<table class="table table-striped table-hover">

			<tr>

				<th width="16"></th>

				<th width="16"></th>

				<th></th>

				<th width="150" class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei_service')); ?></th>

				<th width="150" class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?></th>

				<th width="150" class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log')); ?></th>

				<th width="550" class="text-center"></th>

			</tr>

			<?php

			$qStr = "";

			if($username != "")

			{

				$qStr = (($qStr == '') ? '' : ' and ') . ' sm.username like ' . $mysql->quoteLike($username);

			}

			if($email != "")

			{

				$qStr = (($qStr == '') ? '' : ' and ') . ' sm.email like ' . $mysql->quoteLike($email) . ' or um.first_name like ' . $mysql->quoteLike($email) . ' or um.last_name like ' . $mysql->quoteLike($email);

			}

			$qStr = ($qStr == '') ? '' : ' where ' . $qStr;

			$sql = 'select *,

						(select count(id) from ' . ORDER_IMEI_MASTER . ' oim where oim.supplier_id=sm.id and oim.status=2) as totalIMEIs,

						(select count(id) from ' . ORDER_FILE_SERVICE_MASTER . ' oim where oim.supplier_id=sm.id and oim.status=1) as totalFiles,

						(select count(id) from ' . ORDER_SERVER_LOG_MASTER . ' oim where oim.supplier_id=sm.id and oim.status=1) as totalLogs



						from ' . SUPPLIER_MASTER . ' sm

						' . $qStr . '

						order by sm.username';





				$query = $mysql->query($sql);

				$strReturn = "";

				$i = 1;

				if($mysql->rowCount($query) > 0)

				{

					$rows = $mysql->fetchArray($query);

					foreach($rows as $row)

					{

						echo '<tr>';

						echo '<td>' . $i++ . '</td>';

						echo '<td>' . $graphics->status($row['status']) . '</td>';

						echo '<td>' . $mysql->prints($row['username']) . '</td>';

						echo '<td class="text-center toolbarSkin">' . (($row['service_imei'] == 1) ? '<i class="fa fa-check"></i>' : '') . '</td>';

						echo '<td class="text-center toolbarSkin">' . (($row['service_file'] == 1) ? '<i class="fa fa-check"></i>' : '') . '</td>';

						echo '<td class="text-center toolbarSkin">' . (($row['service_logs'] == 1) ? '<i class="fa fa-check"></i>' : '') . '</td>';

						echo '<td class="text-right">

								<div class="btn-group pull-right">

									<a href="' . CONFIG_PATH_SITE_ADMIN . 'suppliers_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')) . '</a>

									<a href="' . CONFIG_PATH_SITE_ADMIN . 'suppliers_delete.do?id=' . $row['id'] . '" class="btn btn-danger btn-sm" title="This will delete the Selected Supplier."><i class="fa fa-times"></i></a>



                                                                        <a href="' . CONFIG_PATH_SITE_ADMIN . 'suppliers_account.html?id=' . $row['id'] . '" class="btn btn-default btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Payment')) . '</a>

									<a href="' . CONFIG_PATH_SITE_ADMIN . 'suppliers_payment_detail.html?id=' . $row['id'] . '" class="btn btn-default btn-sm">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_payment_detail')) . '</a>

									<a href="' . CONFIG_PATH_SITE_SUPPLIER . 'login_key_process.do?key=' . $row['login_key'] . '" target="_blank" class="btn btn-default btn-sm">'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_login')).'</a>

                                                                        <a href="' . CONFIG_PATH_SITE_ADMIN . 'suppliers_order_details.html?user_id=' . $row['id'] . '" class="btn btn-default btn-sm">'. $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_orders')).'</a>

								</div>

							  </td>';

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

