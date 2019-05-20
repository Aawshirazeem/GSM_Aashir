<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	
	$crM = $objCredits->getMemberCredits();
	$prefix = $crM['prefix'];
	$suffix = $crM['suffix'];
	$rate = $crM['rate'];
	
	$prefix_default = $suffix_default = "";
	
	$sql_curr_default = 'select * from ' . CURRENCY_MASTER . ' where `is_default`=1';
	$query_curr_default = $mysql->query($sql_curr_default);
	if($mysql->rowCount($query_curr_default) > 0)
	{
		$rows_curr_default = $mysql->fetchArray($query_curr_default);
		$row_curr_default = $rows_curr_default[0];
		$prefix_default = $row_curr_default['prefix'];
		$suffix_default = $row_curr_default['suffix'];
	}	
?>


<div class="col-lg-12">

        <div class="panel panel-color panel-inverse">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credit_purchase_requests')); ?></h3>
            </div>
            <div class="panel-body">
	
	
<table class="table table-striped table-hover panel">
	<tr>
		<th width="16">#</th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_gateway')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_date')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_amount')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_funds')); ?></th>
		<th class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>
	</tr>
	<?php
		$paging = new paging();
		$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
		$limit = 50;
		$qLimit = " limit $offset,$limit";
		$extraURL = '';
		
		
		$sql_req = 'select
						im.*,
						um.username,
						cm.prefix,cm.suffix,cm.rate,
						gm.gateway,
						tm.trans_id td_trans_id,tm.ticket_id
					from ' . INVOICE_REQUEST . ' im
					left join '.USER_MASTER.' um on (im.user_id = um.id)
					left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)
					left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)
					left join ' . TICKET_MASTER . ' tm on (im.id = tm.trans_id)
					where im.user_id=' . $member->getUserID() . '
				order by im.id DESC';
				
		$query_req = $mysql->query($sql_req.$qLimit);
		$strReturn = "";
		
		$pCode = $paging->recordsetNav($sql_req,CONFIG_PATH_SITE_USER . 'credits_reqeusts.html',$offset,$limit,$extraURL);
		
		$i = $offset;

		if($mysql->rowCount($query_req) > 0)
		{
			$rows_req = $mysql->fetchArray($query_req);
			foreach($rows_req as $row_req)
			{
				$i++;
				echo '<tr>';
					echo '<td>' . $i . '</td>';
					echo '<td>';
						switch($row_req['status'])
						{
							case '0':
								echo '<span class="label label-default">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_pending')) . '</span>';
								break;
							case '1':
								echo '<span class="label label-primary">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Accepted')) . '</span>';
								break;
							case '2':
								echo '<span class="label label-danger">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_canceled')) . '</span>';
								break;
                                                        case '3':
								echo '<span class="label label-success">Refunded</span>';
								break;    
						}
                                                echo '<td>' . $row_req['gateway']  . '</td>';
					echo '</td>';
                                        
                                          $finaldate2 = $member->datecalculate($row_req['date_time']);
                                        
					echo '<td>' . $finaldate2 . '</td>';
					echo '<td>' . $objCredits->printCredits($row_req['amount'], $prefix_default, $suffix_default) . '</td>';
					echo '<td>' . $objCredits->printCredits($row_req['credits'], $prefix, $suffix) . '</td>';
					echo '<td class="text-center"><div class="btn-group">';
						if($row_req['td_trans_id'] == '')
						{
							if($row_req['status'] == 0)
							{
								//echo '<a class="btn btn-primary" href="' . CONFIG_PATH_SITE_USER . 'credits_requests_process.do?id='.$row_req['id'].'&uid=' . $row_req['user_id'] . '&credits='.$row_req['credits'].'" class="active">Accept</a>';
								echo '<a class="btn btn-default btn-sm" href="' . CONFIG_PATH_SITE_USER . 'ticket_add.html?trans_id='.$row_req['id'].'" >Generate Ticket</a>';
							}
						}
						else
						{
							echo '<a class="btn btn-default btn-sm" href="' . CONFIG_PATH_SITE_USER . 'ticket_details.html?id='.$row_req['ticket_id'].'&trans_id='.$row_req['id'].'" >View Ticket</a>';
						}
						if($row_req['status'] == 0)
						{
							//echo '<a class="btn btn-primary" href="' . CONFIG_PATH_SITE_USER . 'credits_requests_process.do?id='.$row_req['id'].'&uid=' . $row_req['user_id'] . '&credits='.$row_req['credits'].'" class="active">Accept</a>';
							echo '<a class="btn btn-danger btn-sm" href="' . CONFIG_PATH_SITE_USER . 'credits_requests_cancel_process.do?trans_id='.$row_req['id'].'&trans_id='.$row_req['id'].'" >Cancel</a>';
						}
					echo	 ' </div></td>';	
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="7" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
		}
	?>
</table>
            </div>
        </div></div>
<?php echo $pCode;?>