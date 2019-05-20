<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$id=$request->getInt('id');

	

	$type=$request->GetStr('type');

	$file_name=$request->GetStr('file_name');

	$supplier_id=$request->GetInt('supplier_id');

	$file_service_id=$request->GetInt('file_service_id');

	$user_id=$request->GetInt('user_id');

	$ip=$request->GetStr('ip');

	

	$pString='';

	if($supplier_id != 0)

	{

		$pString .= (($pString != '') ? '&' : '' ) . 'supplier_id=' . $supplier_id;

	}

	if($ip != '')

	{

		$pString .= (($pString != '') ? '&' : '') . 'ip=' . $ip;

	}

	if($user_id != 0)

	{

		$pString .= (($pString != '') ? '&' : '') . 'user_id=' . $user_id;

	}

	if($file_service_id != 0)

	{

		$pString .= (($pString != '') ? '&' : '') . 'file_service_id=' . $file_service_id;

	}

	$pString = trim($pString, '&');

	

	$sql = 'select ofsm.*,

					DATE_FORMAT(ofsm.date_time, "%d-%b-%Y %k:%i") as dtDateTime,

					DATE_FORMAT(ofsm.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,

					um.username,um.email,

					slm.service_name,

					sm.username as supplier,

					DATE_FORMAT(ofsm.supplier_paid_on, "%d-%b-%Y %k:%i") as dtSupplier

					from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm

					left join ' . USER_MASTER . ' um on(ofsm.user_id = um.id)

					left join ' . FILE_SERVICE_MASTER . ' slm on (slm.id = ofsm.file_service_id)

					left join ' . SUPPLIER_MASTER . ' sm on(ofsm.supplier_id = sm.id)

		             where ofsm.id=' . $id . '

					order by ofsm.id DESC';

	$query=$mysql->query($sql);

	$rows=$mysql->fetchArray($query);

	$row = $rows[0];

?>

	

    

<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),'Orders'); ?></li>           

             <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html?type=<?php echo $type; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_orders')); ?></a></li>

             <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),'Order File Details'); ?></li>

        </ol>

    </div>

</div>

<?php

	switch($row['status'])

	{

		case -1:

			$status = $admin->wordTrans($admin->getUserLang(),$lang->get('com_available'));

			break;

		case 0:

			$status = $admin->wordTrans($admin->getUserLang(),$lang->get('com_pending'));

			break;

		case 1:

			$status = $admin->wordTrans($admin->getUserLang(),$lang->get('com_available'));

			break;

		case 2:

			$status = $admin->wordTrans($admin->getUserLang(),$lang->get('com_unavailable'));

			break;

	}

?>

		<div class="row">

			<div class="col-sm-8">

				<div class="">

					<h4 class="panel-heading m-d-20">

					<?php echo $mysql->prints($row['fileask']); ?> : <?php echo $status;?>

					</h4>

					<table class="table table-striped table-hover">

						<?php

							if($mysql->rowCount($query)>0)

							{

								echo '<tr><td width="40%">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_order_number'))  . '</td><td>fl-' . str_pad($row['id'], 6, '0', STR_PAD_LEFT) . '</td></tr>';

								echo '<tr><td>' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_file_service_name')) . '</td><td>' . $mysql->prints($row['service_name']) . '</td></tr>';

								echo ($row['unlock_code'] != '') ? '<tr><td>' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_unlock_code')) . '</td><td>' . base64_decode($row['unlock_code']) . '</td></tr>' : '';	

								echo ($row['dtDateTime'] != '') ? '<tr><td>' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_order_date_time')) . '</td><td>' . $mysql->prints($row['dtDateTime']) . '</td></tr>' : '';	

								echo ($row['dtReplyDateTime'] != 0) ? '<tr><td>' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_reply_date_time')) . '</td><td>' . $mysql->prints($row['dtReplyDateTime']) . '</td></tr>' : '';		

								echo ($row['credits'] != '') ? '<tr><td>' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_credits')) . '</td><td>' . $mysql->prints($row['credits']) . '</td></tr>' : '';	

								echo ($row['credits_purchase'] != '') ? '<tr><td>' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_credits_purchase')) . '</td><td>' . $mysql->prints($row['credits_purchase']) . '</td></tr>' : '';	

								echo ($row['credits_discount'] != '') ? '<tr><td>' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_credits_discount')) . '</td><td>' . $mysql->prints($row['credits_discount']) . '</td></tr>' : '';	

								echo  '<tr><td>' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_admin_note')) . '</td><td>' .(($row['message'] != '') ? $mysql->prints($row['message']) : '-')  .'</td></tr>' ;	

						?>

					</table>

			

					<?php

						if($row['api_id'] != '0')

						{

					?>



					<div class="panel-heading">

						<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_api')); ?>

					</div>

					<table class="table table-striped table-hover">

						<?php

							echo ($row['api_name'] != '0') ? '<tr><td width="40%">API Name</td><td>' . $mysql->prints($row['api_name']) . '</td></tr>' : '';

							echo ($row['extern_id'] != '0') ? '<tr><td width="40%">External Id</td><td>' . $mysql->prints($row['extern_id']) . '</td></tr>' : '';

						?>

					</table>

					<?php

						}

					?>



					<div class="panel-heading">

						<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_user')); ?>

					</div>

					<table class="table table-striped table-hover">

						<?php

							echo '<tr><td width="40%">Username</td><td>' . $mysql->prints($row['username']) . '</td></tr>';

							echo  '<tr><td>Email</td><td>' . (($row['email'] != '')? $mysql->prints($row['email']) : '-' ). '</td></tr>';

							echo '<tr><td>Mobile</td><td>' . (($row['mobile'] != '') ? $mysql->prints($row['mobile']) : '-' ) . '</td></tr>' ;		

							echo  '<tr><td>Ip</td><td>' . (($row['ip'] != '') ?  $mysql->prints($row['ip']) : '-' ) . '</td></tr>';	

							echo  '<tr><td>Customer Note</td><td>' . (($row['remarks'] != '') ?  $mysql->prints($row['remarks']) : '-' ) . '</td></tr>';

						?>

					</table>

		

			

					<?php

						if($row['supplier_id']!=0)

						{

					?>



					<div class="panel-heading">

						<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_supplier')); ?>

					</div>

					<table class="table table-striped table-hover">

					<?php

							echo '<tr><td width="40%">Supplier</td><td>' . $mysql->prints($row['supplier']) . '</td></tr>';

							echo '<tr><td>Paid</td><td>' . (($row['supplier_paid'] == 0) ? 'Not-Paid' : 'Paid') . '</td></tr>';

							echo '<tr><td>Date Of Supplier Payment</td><td>' . (($row['supplier_paid'] == 1) ? $mysql->prints($row['supplier_paid_on']) : '--') . '</td></tr>';

						}

					}

					?>

					</table>

				</div>

					<div class="form-group">

						<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_file.html?type=<?php echo $type; ?>" class=" btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?></a>

					</div>

			</div>

		</div>