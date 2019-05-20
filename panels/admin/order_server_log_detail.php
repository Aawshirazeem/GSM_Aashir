<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$id=$request->getInt('id');
	$type=$request->GetStr('type');
	$supplier_id=$request->GetInt('supplier_id');
	$server_log_id=$request->GetInt('server_log_id');
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
	if($server_log_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '') . 'server_log_id=' . $server_log_id;
	}
	$pString = trim($pString, '&');
	
	
	$sql = 'select ofsm.*,
					DATE_FORMAT(ofsm.date_time, "%d-%b-%Y %k:%i") as dtDateTime,
					DATE_FORMAT(ofsm.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,
					um.username,
					um.email,
					slm.server_log_name,
					slm.custom_field_name,
					sm.username as supplier,
					DATE_FORMAT(ofsm.supplier_paid_on, "%d-%b-%Y %k:%i") as dtSupplier
					from ' . ORDER_SERVER_LOG_MASTER . ' ofsm
					left join ' . USER_MASTER . ' um on(ofsm.user_id = um.id)
					left join ' . SERVER_LOG_MASTER . ' slm on (slm.id = ofsm.server_log_id)
					left join ' . SUPPLIER_MASTER . ' sm on(ofsm.supplier_id = sm.id)
					where ofsm.id=' . $id . '
					order by ofsm.id DESC';
	$query=$mysql->query($sql);
	$rows=$mysql->fetchArray($query);
	$row=$rows[0];
?>
	<div class="row">
		<div class="col-lg-12">
			<ul class="breadcrumb">
				<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
				<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Orders'); ?></li>
				<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log.html?type=<?php echo $type; ?>"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_logs_orders')); ?></a></li>
				<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Order Server Log Details'); ?></li>
			</ul>
		</div>
	</div>
<?php
	switch($row['status'])
	{
		case -1:
			$status = $admin->wordTrans($admin->getUserLang(),$lang->get('com_locked'));
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
			<div class="col-sm-8 col-sm-offset-2">
				<div class="panel">
					<div class="panel-heading">
						<h4><?php echo $mysql->prints($row['server_log_name']); ?> : <?php echo $status;?></h4>
					</div>
					<table class="table table-striped table-hover table-bordered">
					<?php
					if($mysql->rowCount($query)>0)
					{
						echo '<tr><td width="40%">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_order_number')) . '</td><td>sc-' . $row['id'] . '</td></tr>';
						echo ($row['reply'] != '') ? '<tr><td>' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_unlock_code')) . '</td><td>' . base64_decode($row['reply']) . '</td></tr>' : '';	
						echo ($row['dtDateTime'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_order_date_time')).'</td><td>' . $mysql->prints($row['dtDateTime']) . '</td></tr>' : '';	
						echo ($row['dtReplyDateTime'] != 0) ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_reply_date_time')).'</td><td>' . $mysql->prints($row['dtReplyDateTime']) . '</td></tr>' : '';		
						echo ($row['credits'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_credits')).'</td><td>' . $mysql->prints($row['credits']) . '</td></tr>' : '';	
						echo ($row['credits_purchase'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_credits_purchase')).'</td><td>' . $mysql->prints($row['credits_purchase']) . '</td></tr>' : '';	
						echo ($row['credits_discount'] != '') ? '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_credits_discount')).'</td><td>' . $mysql->prints($row['credits_discount']) . '</td></tr>' : '';		
						echo ($row['custom_field_name']!='') ? '<tr><td>Customer Value[' . $mysql->prints($row['custom_field_name']) . ']</td><td>' . $mysql->prints($row['custom_value']) . '</td></tr>' : '';
						echo  '<tr><td>'.$lang->get('com_admin_note').'</td><td>' .(($row['message'] != '') ? $mysql->prints($row['message']) : '-')  .'</td></tr>' ;	
					?>
					</table>
					<div class="panel-heading">
						<h4><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_user')); ?></h4>
					</div>
					<table class="table table-striped table-hover table-bordered">
						<?php
							echo '<tr><td width="40%">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_username')).'</td><td>' . $mysql->prints($row['username']) . '</td></tr>';
							echo  '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_email')).'</td><td>' . (($row['email'] != '')? $mysql->prints($row['email']) : '-' ). '</td></tr>';
							echo '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_mobile')).'</td><td>' . (($row['mobile'] != '') ? $mysql->prints($row['mobile']) : '-' ) . '</td></tr>' ;		
							echo  '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_ip')).'</td><td>' . (($row['ip'] != '') ?  $mysql->prints($row['ip']) : '-' ) . '</td></tr>';	
							echo  '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_customer_note')).'</td><td>' . (($row['remarks'] != '') ?  $mysql->prints($row['remarks']) : '-' ) . '</td></tr>';
						?>
					</table>
                                    <label><?php echo $admin->wordTrans($admin->getUserLang(),'Custom Fields Data'); ?></label><br>
                                    <table class="table table-striped table-hover table-bordered">
						<?php
                                                
							if($row['custom_1']!="")
							echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $row['custom_1']))).'</td><td>' . $mysql->prints(substr($row['custom_1'], strpos($row['custom_1'], ":") + 1)) . '</td></tr>';
                                                        if($row['custom_2']!="")
                                                        echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $row['custom_2']))).'</td><td>' . $mysql->prints(substr($row['custom_2'], strpos($row['custom_2'], ":") + 1)) . '</td></tr>';
                                                        if($row['custom_3']!="")
                                                        echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $row['custom_3']))).'</td><td>' . $mysql->prints(substr($row['custom_3'], strpos($row['custom_3'], ":") + 1)) . '</td></tr>';
							 if($row['custom_4']!="")
                                                        echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $row['custom_4']))).'</td><td>' . $mysql->prints(substr($row['custom_4'], strpos($row['custom_4'], ":") + 1)) . '</td></tr>';
							 if($row['custom_5']!="")
                                                        echo '<tr><td width="40%">'.$mysql->prints(current(explode(":", $row['custom_5']))).'</td><td>' . $mysql->prints(substr($row['custom_5'], strpos($row['custom_5'], ":") + 1)) . '</td></tr>';

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
							echo '<tr><td width="40%">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_supplier_name')).'</td><td>' . $mysql->prints($row['supplier']) . '</td></tr>';
							echo '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_supplier_payment')).'</td><td>' . (($row['supplier_paid'] == 0) ? 'Not-Paid' : 'Paid') . '</td></tr>';
							echo '<tr><td>'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_date_of_supplier_payment')).'</td><td>' . (($row['supplier_paid'] == 1) ? $mysql->prints($row['supplier_paid_on']) : '--') . '</td></tr>';
						}
					}		
					?>
					</table>
				</div>
				<div class="form-group">
					<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_server_log.html?type=<?php echo $type; ?>" class=" btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?></a>
				</div>
			</div>
		</div>			

