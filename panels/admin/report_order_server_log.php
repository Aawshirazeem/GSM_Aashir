<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$from = $request->GetStr('from');
	
	$to = $request->GetStr('to');
	
	$search_user_id=$request->getInt('search_user_id');
	
	$qstr = '';
	
	$clearSearch = false;	
	
	if($from != '' && $to == '')
	{
		$from = date('Y-m-d', strtotime($from));
		$qstr = ' where oslm.date_time = ' . $mysql->quote($from);
		$clearSearch = true;
	}
	else if ($from != '' && $to != '')
	{
		$from = date('Y-m-d', strtotime($from));
		$to = date('Y-m-d', strtotime($to));
		$qstr = ' where oslm.date_time>= ' . $mysql->quote($from) .' and oslm.date_time<=' . $mysql->quote($to);
		$clearSearch = true;
	}
	if($search_user_id!=0)
	{
		$qstr.=(($qstr=='')?' where ' : ' and ').' oslm.user_id='.$search_user_id;
		$clearSearch = true;
	}
	$sql = 'select oslm.*, slm.server_log_name
			from ' . ORDER_SERVER_LOG_MASTER .' oslm
			left join ' . SERVER_LOG_MASTER  . ' slm on(oslm.server_log_id=slm.id)
			 ' . $qstr .' group by oslm.server_log_id';
	$query = $mysql->query($sql);
	
	
?>




<?php
	if($from != '')
	{
?>
		<div class="row">
			<div class="col-md-8">
				<label class="text-info"><?php echo $admin->wordTrans($admin->getUserLang(),'From '); ?>: </label> <?php echo $from; ?>
				<label class="text-info"><?php echo $admin->wordTrans($admin->getUserLang(),'To '); ?>: </label> <?php echo $to; ?><br/>
			</div>
		</div>
<?php
	}
?>
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">
	  <div class="modal-dialog">
		  <div class="modal-content">
			  <div class="modal-header">
				  <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-remove"></i></button>
				  <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?></h4>
			  </div>
			  <div class="modal-body">
				<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_server_log.html" method="get">
					<fieldset>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_from')); ?> </label>
									<input type="text" name="from" value="<?php echo $from; ?>" class="form-control"> 
								</div>
								<div class="col-sm-6">
									<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_to')); ?> </label>
									<input type="text" name="to" value="<?php echo $to; ?>" class="form-control"> 
								</div>
							</div>
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?> </label>
							<select name="search_user_id" class="form-control">
								<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_users')); ?> </option>
								<?php
									$sql_usr = 'select id, username from ' . USER_MASTER . ' order by username' ;
									$query_usr = $mysql->query($sql_usr);
									$rows_usr = $mysql->fetchArray($query_usr);
									foreach($rows_usr as $row_usr)
									{
										echo '<option ' . (($row_usr["id"] == $search_user_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
									}
								?>
							</select>
						</div>
						
						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?>" class="btn btn-success" />
					</fieldset>
				  </form>
			  </div>
		  </div>
	  </div>
	</div>
<div class="row m-b-20">

    <div class="col-xs-12">

        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Reports')); ?></li>           

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Server_log_Orders')); ?></li>

        </ol>

    </div>

</div>
<div class="btn-group btn-group-sm extra">
    <div class="dropdown pull-left btn-group-sm"> 
    </div>


    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>combine_reports.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Top_IMEI_Services')); ?> </a>

    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_imei_daywise.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_IMEI_Daywise')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_userwise.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_IMEI_Userwise')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_imei.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_IMEI_Orders')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_file.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_FILE_Orders')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_order_server_log.html" class="btn btn-primary"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Server_log_Orders')); ?> </a>
    <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_orders_users.html" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Users_Orders')); ?> </a>

</div>

<hr>
<div class="row">
	<div class="col-md-8">
		<div class="m-t-10">
		<h4 class="panel-heading m-b-20">
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_server_log')); ?>
			<div class="btn-group pull-right">
<!--				<a href="report_order_file.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_report_order_file')); ?></a>
				<a href="report_order_imei.html" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_report_order_imei')); ?></a>
				<a href="report_order_server_log.html" class="btn btn-primary btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_report_order_server_log')); ?></a>-->
				<a href="#searchPanel" data-toggle="modal" class="btn btn-warning btn-xs"><i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?> </a>
				<?php if($clearSearch == true){ ?><a href="<?php echo CONFIG_PATH_SITE_ADMIN?>report_order_server_log.html" class="btn btn-danger btn-xs"><i class="fa fa-undo"></i></a> <?php } ?>
			</div>
		</h4>
	<div class="table-responsive">	
		<table class="table table-striped table-hover">
			<tr>
				<th width="16">#</th>
				<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_name')); ?></th>
				<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?></th>
				<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_orders')); ?></th>
			</tr>
			<?php
				if($mysql->rowCount($query)>0)
				{ 
					$rows = $mysql->fetchArray($query);
					$i=1;
					$date_time = '';
					foreach($rows as $row)
					{
						$sql_order = 'select count(id) as total, sum(credits) as credits 
								from ' . ORDER_SERVER_LOG_MASTER .'
							where server_log_id=' . $row['id'];
						$query_order = $mysql->query($sql_order);
						echo '<tr>';
							echo '<td>' . $i++ . ' </td>';
							echo '<td>' . $row['server_log_name'] . ' </td>';
							if($mysql->rowCount($query_order)>0)
							{ 
								$rows_order = $mysql->fetchArray($query_order);
								
								echo '<td>' . $rows_order[0]['credits'] . ' </td>';
								echo '<td>' . $rows_order[0]['total'] . ' </td>';
							}
							else
							{
								echo '<td></td>';
								echo '<td></td>';
								echo '<td></td>';
							}
						echo '</tr>';
					}
				}
			?>
		</table>
	</div>	
		</div>
	</div>
</div>
