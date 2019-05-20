<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$search_user_id = $request->GetInt('search_user_id');
	$search_supplier_id = $request->GetInt('search_supplier_id');
	$from_date=$request->getstr("from_date");
	$to_date=$request->getstr("to_date");
?>

	<div class="row">
		<div class="col-lg-12">
			<ul class="breadcrumb">
				<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
				<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Reports'); ?></li>
				<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Order Summary'); ?></li>
			</ul>
		</div>
	</div>

	<section class="panel MT10">
		<div class="panel-heading">
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_order_report')); ?>
				<div class="btn-group MB10 pull-right">
					<a href="report_order_summary_imei.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_service')); ?></a>
					<a href="report_order_summary_file.html"  class="btn btn-primary btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service')); ?></a>
					<a href="report_order_summary_server_log.html"  class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log')); ?> </a>
					<a href="#searchPanel" data-toggle="modal" class="btn btn-warning btn-xs"><i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?></a>
				</div>
		</div>

	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				  <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-remove"></i></button>
				  <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?></h4>
				</div>
				<div class="modal-body">
					<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_summary_file.html" method="get" name="frm_customer_edit_login" id="frm_customer_edit_login" class="formSkin">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6" data-date-format="dd-mm-yyyy">
									<label><?php echo $admin->wordTrans($admin->getUserLang(),'From Date'); ?></label>
									<input type="text" id="fdt" name="from_date" value="<?php echo $from_date; ?>" class="form-control dpd1"/>
								</div>
								<div class="col-sm-6" data-date-format="dd-mm-yyyy">
									<label><?php echo $admin->wordTrans($admin->getUserLang(),'To Date'); ?></label>
									<input type="text" id="ldt" name="to_date" value="<?php echo $to_date; ?>" class="form-control dpd2"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?> </label>
							<select name="search_user_id" class="form-control">
								<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_users')); ?> </option>
								<?php
									$sql_usr = 'select id, username from ' . USER_MASTER . ' order by username';
									$query_usr = $mysql->query($sql_usr);
									$rows_usr = $mysql->fetchArray($query_usr);
									foreach($rows_usr as $row_usr)
									{
										echo '<option ' . (($row_usr["id"] == $search_user_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
									}
								?>
							</select>
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reseller')); ?> </label>
							<select name="search_supplier_id" class="form-control">
								<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_suppliers')); ?> </option>
								<?php
									$sql_usr = 'select id, username from ' . SUPPLIER_MASTER . ' order by username';
									$query_usr = $mysql->query($sql_usr);
									$rows_usr = $mysql->fetchArray($query_usr);
									foreach($rows_usr as $row_usr)
									{
										echo '<option ' . (($row_usr["id"] == $search_supplier_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
									}
								?>
							</select>
						</div>
						<div class="form-group">
							<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?>" class="btn btn-success" />
						</div>
					 </form>
				</div>
			</div>
		</div>
	</div>
		
		<div class="clearfix"></div>
		<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
			<table class="table table-striped table-hover">
			<tr>
				<td width="10"></td>
				<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service')); ?></th>
				<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_orders')); ?> </th>
			</tr>
		<?php
			$i=0;
			$qType = ' where year(ofsm.date_time)=date_format(now(),"%Y") ';

			if($from_date != '' && $to_date != '')
			{
				$from_date_search=date('Ymd',strtotime($from_date));
				$to_date_search=date('Ymd',strtotime($to_date));
				$qType = ' where date(ofsm.date_time) between ' . $from_date_search . ' and ' . $to_date_search;
			}
	
			if($search_user_id != 0)
			{
				$qType .= (($qType == '') ? ' where ' : ' and ') . '  ofsm.user_id = ' . $search_user_id;
			}
	
			if($search_supplier_id != 0)
			{
				$qType .= (($qType == '') ? ' where ' : ' and ') . ' ofsm.supplier_id = ' . $search_supplier_id;
			}

			$sql='select 
						fsm.service_name,count(ofsm.id) as count,ofsm.supplier_id
						from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm
						left join ' . FILE_SERVICE_MASTER . ' fsm on(fsm.id=ofsm.file_service_id)
						'. $qType. '
						group by ofsm.file_service_id';
			$query=$mysql->query($sql);
			if($mysql->rowCount($query))
			{
				$rows=$mysql->fetchArray($query);
				foreach($rows as $row)
				{
					$i++;
		?>	
			<tr>
				<td class="text-right"><?php echo $i; ?></td>
				<td><?php echo $row['service_name']?></td>
				<td><?php echo $row['count']?></td>
			</tr>
		<?php
				}
			}
			else 
			{
				echo '<tr><td class="text-center" colspan="3">No Record Found</td></tr>';	
			}
				
		?>	
		</table>
	</section>
