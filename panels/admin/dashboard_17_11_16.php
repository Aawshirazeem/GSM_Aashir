<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	
	/*************************************************************
								IMEI Stats
	**************************************************************/
	$sqlImeiToday = 'SELECT count(id) as imeiTodayNew FROM '.ORDER_IMEI_MASTER .' where date(date_time)=CURDATE() and status=0';
	$queryImeiToday = $mysql->query($sqlImeiToday);
	$imeiTodayNew = $imeiTodayAccepted = $imeiTodayAvail = $imeiTodayRejected =0;
	if($mysql->rowCount($queryImeiToday) > 0)
	{
		$rowsImeiToday = $mysql->fetchArray($queryImeiToday);
		$imeiTodayNew = $rowsImeiToday[0]['imeiTodayNew'];
	}
	//IMEI:Today Income
	$sqlImeiTodayIncome = 'select sum(credits-credits_purchase) as imeiTodayIncome
								from '.ORDER_IMEI_MASTER .' 
								where status = 2 and date(date_time)=CURDATE()';
	$queryImeiTodayIncome = $mysql->query($sqlImeiTodayIncome);
	$imeiTodayIncome = 0;
	if($mysql->rowCount($queryImeiTodayIncome) > 0)
	{
		$rowsImeiTodayIncome = $mysql->fetchArray($queryImeiTodayIncome);
		$imeiTodayIncome = $rowsImeiTodayIncome[0]['imeiTodayIncome'];
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	$sql = '
			SELECT
					id, day(date_time) days,
					count(id) totalJobs
			from ' . ORDER_IMEI_MASTER . '
			WHERE datediff(date_time, now()) > 7
			GROUP BY day(date_time )
			order by day(date_time )';
	//echo $sql;
	$sqlCount = 'select

				(select count(id) as total from ' . ORDER_IMEI_MASTER . ' im where (status=0)) as pendingIMEI,
				(select count(id) as total from ' . ORDER_FILE_SERVICE_MASTER . ' fsm where (status=0)) as pendingFiles,
				(select count(id) as total from ' . ORDER_SERVER_LOG_MASTER . ' slm where (status=0)) as pendingServerLogs
				
				from ' . ADMIN_MASTER . ' am where id=' . $mysql->getInt($admin->getUserId());
	$queryCount = $mysql->query($sqlCount);
	if($mysql->rowCount($queryCount) == 0)
	{
		echo '<h1>Invalid configuration! Please relogin...</h1>';
		exit();
	}
	$rowsCount = $mysql->fetchArray($queryCount);
	$rowCount = $rowsCount[0];
	
	
	//echo $curMonth;
	for($i=1; $i<=4; $i++)
	{
		$date = Date("Y-m-d", strtotime(date("Y-m-d") . " -" . $i . " Month"));
		$objDate = new DateTime($date);
		$month[] = $objDate->format('n');
		
	} 
	$d1 = sort($month);
	$d = implode(',',$month);
	
	
	$sql_total = 'SELECT
						admin_id, sum(credits) as total
					from ' . CREDIT_TRANSECTION_MASTER . '
					where admin_id!=0 or admin_id2!=0
					group by admin_id order by admin_id';
	$query_total = $mysql->query($sql_total);
	$totalAllot= $totalRevoke = $totalActual = 0;
	if($mysql->rowCount($query_total) > 0)
	{
		$rows_total = $mysql->fetchArray($query_total);
		$totalAllot = round($rows_total[0]['total'], 2);
		$totalRevoke = isset($rows_total[1]['total']) ? round($rows_total[1]['total'], 2) : 0;
		$totalActual = $totalAllot - $totalRevoke;
	}
	
	$sqlUsers = 'select count(id) as total from ' . USER_MASTER . ' where status=1';
	$queryUsers = $mysql->query($sqlUsers);
	$totalUsers = 0;
	if($mysql->rowCount($queryUsers) > 0)
	{
		$rowsUsers = $mysql->fetchArray($queryUsers);
		$totalUsers = $rowsUsers[0]['total'];
	}
	
	

	
?>

     
              
	<div class="col-sm-12">
		<div class="row">
			<div class="col-md-4 col-sm-4 col-xs-6 samllpadding">
				<a href="#ItemsIMEISPanel" data-toggle="modal" class="btn btn-danger btn-block">
					<i class="fa fa-lock fa-4x"></i><br />IMEI Orders
					<span class="badge bg-inverse"><?php echo $rowCount['pendingIMEI']; ?></span>
				</a>
			</div>
			
			<div class="col-md-4 col-sm-4 col-xs-6 samllpadding">
				<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html" class="btn btn-white btn-block">
				<i class="fa fa-folder-open-o fa-4x"></i><br />IMEI Services
				<span class="badge bg-important">20</span></a>
			</div>

			<div class="col-md-4 col-sm-4 col-xs-6 samllpadding"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html" class="btn btn-white btn-block">
				<i class="fa fa-group fa-4x"></i><br />Users
				<span class="badge bg-important"><?php echo $totalUsers; ?></span></a>
			</div>

			<div class="col-md-2 col-sm-4 col-xs-6 samllpadding"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_add.html" class="btn btn-primary btn-block"><i class="fa fa-user-plus fa-4x"></i><br />New User</a></div>
			<div class="col-md-2 col-sm-4 col-xs-6 samllpadding"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>suppliers.html" class="btn btn-white btn-block"><i class="fa fa-user fa-4x"></i><br />Suppliers</a></div>
			<div class="col-md-2 col-sm-4 col-xs-6 samllpadding"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>package.html" class="btn btn-white btn-block"><i class="fa fa-gift fa-4x"></i><br />Credit Packages</a></div>
			<div class="col-md-2 col-sm-4 col-xs-6 samllpadding"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_list.html" class="btn btn-white btn-block"><i class="fa fa-link fa-4x"></i><br />API Master</a></div>
			<div class="col-md-2 col-sm-4 col-xs-6 samllpadding"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>password_change.html" class="btn btn-white btn-block"><i class="fa fa-key fa-4x"></i><br />Change Password</a></div>
			<div class="col-md-2 col-sm-4 col-xs-6 samllpadding"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>email_user_list.html" class="btn btn-white btn-block"><i class="fa fa-envelope fa-4x"></i><br />Mass. Mail</a></div>
		</div>
	</div>


	<!-- POPUP Pending IMEI Jobs -->
    <div class="modal" id="ItemsIMEISPanel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title"><?php $lang->prints('lbl_pending_imei_jobs'); ?></h4>
              </div>
              <div class="modal-body">
                    <?php
                        $sql = 'select tool_name,count(tool_id) as count,tool_id
                                    from ' . ORDER_IMEI_MASTER . ' im
                                    left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
                                    where im.status=0 group by tool_id order by im.id DESC';
                        $query = $mysql->query($sql);
                        if($mysql->rowCount($query) > 0)
                        {
                    ?>
                            <table class="table table-striped">
                                <?php
                                    $rows = $mysql->fetchArray($query);
                                    $i = 1;
                                    foreach($rows as $row)
                                    {
                                ?>
                                        <tr>
                                            <td class="text-left"><a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?<?php echo 'search_tool_id=' . $row['tool_id'] . '&type=pending'?>"><?php echo $mysql->prints($row['tool_name']); ?></a></td>
                                            <td class="text-right"><span class="badge bg-inverse"><?php echo $row['count']; ?></span></td>
                                            <td class="text-right" width="67">
                                                <a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei_download.do?<?php echo 'search_tool_id=' . $row['tool_id'] . '&type=pending'?>" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                                <a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei_pending_process.do?lock_all=1&<?php echo 'search_tool_id=' . $row['tool_id'] . '&type=pending'?>" class="btn btn-danger btn-xs prompt" title="Are you sure you want to lock all the '<?php echo $mysql->prints($row['tool_name']); ?>' IMEIs?"><i class="fa fa-lock"></i></a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                ?>
                            </table>
                    <?php
                        }
                        else
                        {
                            echo '<br /><br /><br /><br /><h2 class="TA_C">No IMEI order pending.</h2>';
                        }
                    ?>
                    <div class="btn-group btn-group-justified">
                    	<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=pending" class="btn btn-danger">New Orders</a>
                    	<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=locked" class="btn btn-white">Pending Orders</a>
                    	<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html?type=locked" class="btn btn-white">Available Orders</a>
                    </div>
                    
              </div>
          </div>
        </div>
    </div>
    <!-- END: POPUP Pending IMEI Jobs -->

	<!-- POPUP Pending File Jobs -->
    <div class="modal fade" id="ItemsFilePanel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title"><?php $lang->prints('lbl_pending_file_service_jobs'); ?></h4>
              </div>
              <div class="modal-body">
                <?php
                    $sql = 'select service_name,count(file_service_id) as count,file_service_id
                                from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm
                                left join ' . FILE_SERVICE_MASTER . ' slm on (slm.id = ofsm.file_service_id)
                                    where ofsm.status=0 group by file_service_id order by ofsm.id DESC';
                    $query = $mysql->query($sql);
                    if($mysql->rowCount($query) > 0)
                    {
                    ?>
                        <h2>File Orders</h2>
                        <table class="MT5 details text_12 ui-corner-all ui-widget-content">
                            <?php
                                $rows = $mysql->fetchArray($query);
                                $i = 1;
                                foreach($rows as $row)
                                {
                            ?>
                                    <tr>
                                        <td class="text-left"><a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_file.html?file_service_id=<?php echo $row['file_service_id'];?>&type=pending"><?php echo $mysql->prints($row['service_name']); ?></a></td>
                                        <td class="text-right"><?php echo $row['count']; ?></td>
                                    </tr>
                            <?php
                                }
                            ?>
                        </table>
                    <?php
                    }
                    else
                    {
                        echo '<h2 class="text-center">No file order pending.</h2>';
                    }
                ?>
              </div>
              <div class="modal-footer">
                  <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
              </div>
          </div>
        </div>
    </div>
    <!-- END: POPUP Pending File Jobs -->


	<!-- POPUP Pending Server Log Jobs -->
    <div class="modal fade" id="ItemsServerLogPanel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title"><?php $lang->prints('lbl_server_credits_orders'); ?></h4>
              </div>
              <div class="modal-body">
                <?php
                    $sql = 'select slm.server_log_name,count(ofsm.server_log_id) as count,ofsm.server_log_id	
                            from ' . ORDER_SERVER_LOG_MASTER . ' ofsm
                            left join ' . SERVER_LOG_MASTER . ' slm on (slm.id = ofsm.server_log_id)
                                where ofsm.status=0 group by ofsm.server_log_id order by ofsm.id DESC';
                    $query = $mysql->query($sql);
                    if($mysql->rowCount($query) > 0)
                    {
                ?>
                        <table class="MT5 details text_12 ui-corner-all ui-widget-content">
                            <?php
                                $rows = $mysql->fetchArray($query);
                                $i = 1;
                                foreach($rows as $row)
                                {
                            ?>
                                    <tr>
                                        <td class="text-left"><a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_server_log.html?server_log_id=<?php echo $row['server_log_id']?>&type=pending"><?php echo $mysql->prints($row['server_log_name']); ?></a></td>
                                        <td class="text-right"><?php echo $row['count']; ?></td>
                                    </tr>
                            <?php
                                }
                            ?>
                        </table>
                <?php
                    }
                    else
                    {
                        echo '<h2 class="text-center">No server log order pending.</h2>';
                    }
                ?>
              </div>
              <div class="modal-footer">
                  <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
              </div>
          </div>
        </div>
    </div>
    <!-- END: POPUP Pending Server Log Jobs -->




	<div class="row MT10">
	  <div class="col-lg-6">
		  <!--user info table start-->
		  <section class="panel">
			  <div class="panel-body">
				  <div class="task-progress">
					  <h1><?php $lang->prints('lbl_trans'); ?></h1>
					  <p>[<?php echo $totalAllot?> - <?php echo $totalRevoke?> = <b><?php echo $totalActual?></b>]</p>
				  </div>

				  <div class="task-option">
					  <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_transections.html" class="btn btn-warning btn-sm"><?php $lang->prints('com_view_all'); ?></a>
				  </div>
			  </div>
			  <table class="table table-hover personal-task">
				  <tbody>
					<?php
						$sql = 'select
									ctm.date_time, ctm.credits, ctm.views, 
									um.username as username1, 
									um2.username as username2
								from ' . CREDIT_TRANSECTION_MASTER . ' ctm
								left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)
								left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)
								where (admin_id!=0 or admin_id2 !=0)
								order by ctm.id DESC
								limit 8';
						$query = $mysql->query($sql);
						if($mysql->rowCount($query) > 0)
						{
							$transs = $mysql->fetchArray($query);
							foreach($transs as $trans)
							{
					?>
								<tr <?php echo ($trans['views'] < 5) ? 'style="color:red;font-weight:bold"' : '';?>>
									<td><?php if($trans['username2'] != ''){ echo '<i class="icon-arrow-left"></i>'; } ?></td>
									<td><?php echo date("d-M Y H:i", strtotime($trans['date_time']));?></td>
									<td><?php echo $trans['username1']; echo $trans['username2'];?></td>
									<td class="text-right"><?php echo $trans['credits'];?> <?php $lang->prints('com_cr'); ?> </td>
								</tr>
					<?php
							}
						}
						else
						{
					?>
					<tr>
						<td colspan="" class="text-right"><?php //echo $rowCount['rejectedServerLogs'];?></td>
					</tr>
					<?php
						}
						// Update Last viewed credtis
						$sql = 'update ' . CREDIT_TRANSECTION_MASTER . ' set views=(views+1)
								where 
										(admin_id = ' . $mysql->getInt($admin->getUserId()) . ' or 
										admin_id2 = ' . $mysql->getInt($admin->getUserId()) . ')
										and views < 5';
						$query = $mysql->query($sql);
					?>
				  </tbody>
			  </table>
		  </section>
		  <!--user info table end-->
	  </div>
	  <div class="col-lg-6">
		  <!--work progress start-->
		  <section class="panel">
			  <div class="panel-body progress-panel">
				  <div class="task-progress">
					  <h1><?php $lang->prints('lbl_new_users'); ?></h1>
				  </div>
			  </div>
			  <table class="table table-hover personal-task">
				  <tbody>
					<?php
						$sql = 'select um.username, um.credits, rm.username as reseller,
									date_format(um.creation_date,"%d %M, %Y") as lastLoginDate
									from ' . USER_MASTER . ' um
									left join ' . USER_MASTER . ' rm on (um.reseller_id = rm.id)
									order by um.id DESC limit 8';
						$query = $mysql->query($sql);
						if($mysql->rowCount($query) > 0)
						{
							$transs = $mysql->fetchArray($query);
							foreach($transs as $trans)
							{
					?>
					<tr>
						<td><?php echo $trans['lastLoginDate'];?></td>
						<td><?php echo $trans['username'];?></td>
						<td><?php echo $trans['reseller'];?></td>
						<td class="text-right">
							<span class="label <?php echo ($trans['credits'] == 0) ? 'label-danger' : 'label-default';?>"><?php echo $trans['credits'];?><?php $lang->prints('com_cr'); ?></sapn>
						</td>
					</tr>
					<?php
							}
						}
						else
						{
					?>
					<tr>
						<td colspan="" class="text-right"><?php echo $rowCount['rejectedServerLogs'];?></td>
					</tr>
					<?php
						}
					?>
				  </tbody>
			  </table>
		  </section>
		  <!--work progress end-->
	  </div>
	</div>
<div class="row">
	<div class="col-md-4">
	
	
			<div class="panel">
				<?php
					$sql_reg = 'select count(id) as total from ' . USER_REGISTER_MASTER;
					$query_reg = $mysql->query($sql_reg);
					$rows_reg = $mysql->fetchArray($query_reg);
					$newReg = $rows_reg[0]['total'];
				?>
				<div class="panel-heading">New Registration [<?php echo $newReg; ?>]</div>
					<?php

					$sql_userReg = 'select * from ' . USER_REGISTER_MASTER . ' order by id desc limit 5';
					$query_userReg = $mysql->query($sql_userReg);
					$rows_userReg = $mysql->fetchArray($query_userReg);
					?>
					<table class="table table-striped table-hover">
						<?php
							for($i=0;$i<5;$i++)
							{
								$username = isset($rows_userReg[$i]['username']) ? $mysql->prints($rows_userReg[$i]['username']) : '';
						?>
							<tr>
								<td width="16"><?php echo $i+1; ?></td>
								<td><?php echo $username; ?></td>
								<td width="20"><a href="<?php echo CONFIG_PATH_SITE_ADMIN . 'users_add.html?id=' . $rows_userReg[$i]['id'] ?>" class="btn btn-default btn-xs"><?php echo $lang->get('com_accept')?></a></td>
							</tr>
						<?php
							}
						?>
					</table>
			</div> <!-- / panel -->
			
			
	
	</div>
	<div class="col-md-4">
	
	
			<div class="panel">
				<?php
						$sql_ticket='select
							count(id) as totalRequests
							from ' . INVOICE_REQUEST . ' tm
							where status=0';
						$query_ticket=$mysql->query($sql_ticket);
						if($mysql->rowCount($query_ticket))
						{
							$rows_ticket=$mysql->fetchArray($query_ticket);
							$crRequest=$rows_ticket[0]['totalRequests'];
						}
				?>
				<div class="panel-heading">Credit Request[<?php echo $crRequest; ?>]</div>
					<?php
					$sql_crReq = 'select im.*,um.username, cm.prefix
										from ' . INVOICE_REQUEST . ' im
										left join '.USER_MASTER.' um on (im.user_id = um.id)
										left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)
										where im.status=0
									order by im.id DESC limit 5';
					$query_crReq = $mysql->query($sql_crReq);
					if($mysql->rowCount($query_crReq) > 0)
					{
					?>
						<table class="table table-striped table-hover">
							<?php
								$rows_crReq = $mysql->fetchArray($query_crReq);
								for($i=0;$i<5;$i++)
								{
									$username = isset($rows_crReq[$i]['username']) ? $mysql->prints($rows_crReq[$i]['username']) : '';
									$amount = isset($rows_crReq[$i]['amount']) ?  $rows_crReq[$i]['prefix'] . ' ' . $rows_crReq[$i]['amount'] : '';
									$user_id = isset($rows_crReq[$i]['user_id']) ?  $rows_crReq[$i]['user_id'] : '';
									$req_id = isset($rows_crReq[$i]['id']) ?  $rows_crReq[$i]['id'] : '';
									$credits = isset($rows_crReq[$i]['credits']) ?  $rows_crReq[$i]['credits'] : '';
							?>
								<tr>
									<td width="16"><?php echo $i+1; ?></td>
									<td><?php echo $username; ?></td>
									<td><?php echo $amount;  ?></td>
									<td width="20"><a href="<?php echo CONFIG_PATH_SITE_ADMIN ?>users_credits.html?id=<?php echo $user_id ?>&rid=<?php echo $req_id ?>&credits= <?php echo $credits ?>" class="btn btn-default btn-xs" >Accept</a></td>
								</tr>
							<?php
								}
								
							?>
						</table>
					<?php
					}
					?>
			</div> <!-- / panel -->
		</div>
			
	<div class="col-md-4">
	
	
			<div class="panel">
				<?php
						$sql_unCr='select
							count(id) as totalRequests
							from ' . INVOICE_REQUEST . ' tm
							where status=0';
						$query_unCr=$mysql->query($sql_unCr);
						if($mysql->rowCount($query_unCr))
						{
							$rows_unCr=$mysql->fetchArray($query_unCr);
							$crUnpaid=$rows_unCr[0]['totalRequests'];
						}
				?>
				<div class="panel-heading">Unpaid Invoices[<?php echo $crUnpaid;?>]</div>
					<?php
					$sql_unpaid = 'select im.*,um.username, cm.prefix
								from ' . INVOICE_MASTER . ' im
								left join '.USER_MASTER.' um on (im.user_id = um.id)
								left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)
								where im.status=0 and im.paid_status=0
							order by im.id DESC limit 5';
					$query_unpaid = $mysql->query($sql_unpaid);
					if($mysql->rowCount($query_unpaid) > 0)
					{
					?>
						<table class="table table-striped table-hover">
							<?php
								$row_unpaid = $mysql->fetchArray($query_unpaid);
								for($i=0;$i<5;$i++)
								{
									$txn_id = isset($row_unpaid[$i]['txn_id']) ? $mysql->prints($row_unpaid[$i]['txn_id']) : '';
									$username = isset($row_unpaid[$i]['username']) ? $mysql->prints($row_unpaid[$i]['username']) : '';
									$amount = isset($row_unpaid[$i]['amount']) ? $row_unpaid[$i]['prefix'] . ' ' . $row_unpaid[$i]['amount'] : '';
							?>
								<tr>
									<td width="16"><?php echo $i+1; ?></td>
									<td><?php echo $txn_id ?></td>
									<td><?php echo $username; ?></td>
									<td><?php echo $amount; ?></td>
									<td></td>
								</tr>
							<?php
								}
							?>
						</table>
					<?php
					}
					?>
			</div> <!-- / panel -->

			
			
	
	</div>
</div>


<div class="row">
	<div class="col-md-6">
		<div class="panel">
			<div class="panel-heading"><?php $lang->prints('lbl_top_seller'); ?></div>
			<?php
			$sql_top = 'select
								oim.id, count(oim.id) as total,
								itm.tool_name
							from '.ORDER_IMEI_MASTER .' oim
							left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
							group by oim.tool_id
							order by total DESC
							limit 5
							'; 
			$query_top = $mysql->query($sql_top);
			if($mysql->rowCount($query_top) > 0)
			{
			?>
				<table class="table table-bordered table-striped table-hover">
					<?php
						$rows_top = $mysql->fetchArray($query_top);
						$i = 1;
						foreach($rows_top as $row_top)
						{
					?>
						<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo $mysql->prints($row_top['tool_name']); ?></td>
							<td class="text-right"><?php echo $row_top['total']; ?></td>
						</tr>
					<?php
						}
					?>
				</table>
			<?php
			}
			?>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel">
			<div class="panel-heading"><?php $lang->prints('lbl_total_top_seller'); ?></div>
			<?php
			$sql_top = 'select
								oim.id, sum(oim.credits-oim.credits_purchase) as total,
								itm.tool_name
							from '.ORDER_IMEI_MASTER .' oim
							left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
							group by oim.tool_id
							order by total DESC
							limit 5
							'; 
			$query_top = $mysql->query($sql_top);
			if($mysql->rowCount($query_top) > 0)
			{
			?>
				<table class="table table-bordered table-striped table-hover">
					<?php
						$rows_top = $mysql->fetchArray($query_top);
						$i = 1;
						foreach($rows_top as $row_top)
						{
					?>
						<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo $mysql->prints($row_top['tool_name']); ?></td>
							<td class="text-right"><?php echo number_format($row_top['total']); ?></td>
						</tr>
					<?php
						}
					?>
				</table>
			<?php
			}
			?>
		</div>
	</div>
</div>


	<script src="<?php echo CONFIG_PATH_PANEL; ?>js/jquery.sparkline.js" type="text/javascript"></script>
	<script src="<?php echo CONFIG_PATH_PANEL; ?>js/sparkline-chart.js"></script>
	<script src="<?php echo CONFIG_PATH_PANEL; ?>js/owl.carousel.js" ></script>
	<script>
		//owl carousel
		$(document).ready(function() {
		  $("#owl-demo").owlCarousel({
			  navigation : true,
			  slideSpeed : 300,
			  paginationSpeed : 400,
			  singleItem : true

		  });
		});
	</script>