<?php

	defined("_VALID_ACCESS") or die("Restricted Access");

	$mysql = new mysql();

	

	$username = $request->GetStr("username");

	$email = $request->GetStr("email");

	

	$id = $request->GetInt('id');

	

	$displaySearch = false;

	if($username != '' or $email != '')

	{

		$displaySearch = true;

	}

	

	

		$sql = 'select

					*,

					(select sum(credits_purchase) from ' . ORDER_IMEI_MASTER . ' oim where oim.supplier_id=sm.id and oim.status=2) as totalIMEIs,

					(select sum(credits_purchase) from ' . ORDER_FILE_SERVICE_MASTER . ' oim where oim.supplier_id=sm.id and oim.status=1) as totalFiles,

					(select sum(credits_purchase) from ' . ORDER_SERVER_LOG_MASTER . ' oim where oim.supplier_id=sm.id and oim.status=1) as totalLogs,

					(select sum(credits_paid) from ' . SUPPLIER_PAYMENT . ' sp where sp.supplier_id=sm.id and sp.supplier_id='.$id.' ) as totalPayment

				from ' . SUPPLIER_MASTER . ' sm

				 where sm.id = '.$id.'

				order by sm.username';



		

                $sql='select *, 

                                (select round(sum(isd.credits_purchase),2) credits_purchase from ' . ORDER_IMEI_MASTER . ' oim

                                inner join '. IMEI_SUPPLIER_DETAILS .' isd

                                on isd.tool=oim.tool_id and isd.supplier_id=oim.supplier_id

                                where oim.supplier_id=sm.id and oim.status=2) as totalIMEIs, 

                                (select round(sum(fsd.credits_purchase),2) credits_purchase from ' . ORDER_FILE_SERVICE_MASTER . ' oim 

                                inner join '.FILE_SUPPLIER_DETAILS.' fsd

                                on fsd.service_id=oim.file_service_id

                                where oim.supplier_id=sm.id and oim.status=1) as totalFiles, 

                                (select round(sum(slsd.credits_purchase),2) credits_purchase from  ' . ORDER_SERVER_LOG_MASTER . ' oim 

                                inner join '.SERVER_LOG_SUPPLIER_DETAILS.' slsd

                                on slsd.log_id=oim.server_log_id

                                where oim.supplier_id=sm.id and oim.status=1) as totalLogs, 

                                (select sum(credits_paid) from ' . SUPPLIER_PAYMENT . ' sp 

                                where sp.supplier_id=sm.id and sp.supplier_id='.$id.' ) as totalPayment 

                                from ' . SUPPLIER_MASTER . ' sm 

                                where sm.id = '.$id.' order by sm.username';

                

                

            //   echo $sql;exit;

                

                

                

	$query = $mysql->query($sql);

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



<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers_account_process.html" method="post">



	  <?php

		if($mysql->rowCount($query)>0)

		{

			$rows = $mysql->fetchArray($query);

			$row = $rows[0];

			$amtTotal = $row['totalIMEIs'] + $row['totalFiles'] + $row['totalLogs'];

			$amtPaid = $row['totalPayment'];

			$payment = $row['totalPayment'];

			$amtToPay = $amtTotal - $payment;

	  ?>

	<input type="hidden" name="id" value="<?php echo $id; ?>">

	<div class="row">

		<div class="col-md-8">

			<div class="">

				<h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_supplier_account')); ?></h4>

				<table class="table table-striped table-hover">

					<tr>

							<td width="50%"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei_service')); ?></td>

							<td><?php echo round($row['totalIMEIs'], 2);?></td>

						</tr>

						<tr>

							<td><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service')); ?></td>

							<td><?php echo round($row['totalFiles'], 2);?></td>



						</tr>

						<tr>

							<td><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log')); ?></td>

							<td><?php echo round($row['totalLogs'], 2);?></td>

						</tr>

						<tr>

							<td><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_total_amount')); ?></td>

							<td><?php echo round($amtTotal, 2);?></td>

						</tr>

						<tr>

							<td><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_amount_paid')); ?></td>

							<td><?php echo round($amtPaid, 2); ?></td>

						</tr>

						<tr>

							<td><h2><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_amount_to_pay')); ?></h2></td>

							<td><?php echo ($amtToPay > 0) ? '<h2 class="text-success">' . round($amtToPay, 2) . '</h2>' : '<h2 class="text-danger">' . round($amtToPay, 2) . '</h2>';?></td>

						</tr>

						<tr>

							<td><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_pay_now')); ?></td>

							<td><input name="credits_to_pay" type="text" class="textbox_big" id="credits_to_pay" value="" style="width:195px;" /></td>

						</tr>

						<tr>

							<td><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_comments')); ?></td>

							<td><textarea name="comments" rows="5" cols="25" id="comments" class="textbox_fix"></textarea></td>

						</tr>

					</table>

					<div class="panel-body">

						<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>suppliers.html" class="btn btn-danger btn-sm"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>

						<input type="submit" name="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_submit')); ?>" class="btn btn-success btn-sm">

					</div>

			</div> <!-- / panel -->

		</div> <!-- / col-lg-6 -->

	</div> <!-- / row -->

</form>

	<?php

	}

	?>

