<?php
	defined("_VALID_ACCESS") or die("Restricted Access");

	$type = $request->getStr('type');
	
?>
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_invoice_history')); ?></h1>
</div>
<div class="clear"></div>
<div class="table-responsive">
	<table class="MT5 table table-striped table-hover table-bordered panel">
	<tr>
		<th width="120"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_invoice_id')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_date')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_amount')); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Credit')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_gateway')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?></th>
		<th width="80"></th>
	</tr>
	<?php
		$paging = new paging();
		$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
		$limit = 40;
		$qLimit = " limit $offset,$limit";
		$extraURL = '&type=' . $type;
		
		
		$sql = 'select im.*, cm.prefix, gm.gateway
					from ' . INVOICE_MASTER . ' im
					left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)
					left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)
					where user_id=' . $member->getUserID() . '
				order by im.id DESC';
		$query = $mysql->query($sql . $qLimit);
		$strReturn = "";
		
		$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_USER . 'credits_invoice.html',$offset,$limit,$extraURL);
		
		$i = $offset;

		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				$i++;
				echo '<tr>';
				echo '<td>INV #' . str_pad($row['id'],4,'0',0) . '</td>';
                                 //timezone                  
                                         $dtDateTime = new DateTime($row['date_time'] , new DateTimeZone($admin->timezone()));
                                         $dtDateTime->setTimezone(new DateTimeZone($member->timezone()));
                                         $dtDateTime=$dtDateTime->format('d-M-Y H:i');
                                         //end
                                           $finaldate2 = $member->datecalculate($row['date_time']);
				echo '<td>' . $finaldate2 . '</td>';
				echo '<td>' . $row['amount'] . '</td>';
                                echo '<td>' . $row['credits'] . '</td>';
				echo '<td>' . $row['gateway'] . '</td>';
				echo '<td>';
					switch($row['paid_status'])
					{
						case '0':
							echo '<span class="label label-default">' .  $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_unpaid')) . '</span>';
							break;
						case '1':
							echo '<span class="label label-success">' .  $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_paid')) . '</span>';
							break;
						case '2':
							echo '<span class="label label-danger">' .  $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_canceled')) . '</span>';
							break;
                                                 case '3':
								echo '<span class="label label-primary">Refunded</span>';
								break;      
					}
				echo '</td>';
				echo '<td class="text-right">
						<div class="btn-group">
							' . (($row['paid_status'] == 1 ) ? '<a href="' . CONFIG_PATH_SITE_USER . 'users_credit_invoices_detail.html?id=' . $row['id'] . '&type=1" class="btn btn-default btn-sm" >'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_view')).'</a>' : '<a href="' . CONFIG_PATH_SITE_USER . 'users_credit_invoices_detail.html?id=' . $row['id'] . '&type=0" class="btn btn-default btn-sm" >'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_view'))). '</a>
						</div>
					  </td>';
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="11" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
		}
	?>
	</table>
</div>
	<?php echo $pCode;?>