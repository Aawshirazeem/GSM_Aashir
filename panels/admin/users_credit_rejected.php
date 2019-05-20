<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$inr = $request->getStr('inr');
	if($inr>0)
	{
		$sql_in = 'update '.INVOICE_MASTER	.' set paid_status=2 where id='.$inr;
		$mysql->query($sql_in);
	}
?>
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_rejected_unpaid_invoices')); ?></h1>
	<div class="btn-group">
		<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_credit_unpaid.html" class="btn btn-success"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_unpaid_invoices')); ?></a>
	</div>
</div>


	<table class="MT5 table table-striped table-hover panel">
	<tr>
		<th width="60"></th>
		<th ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_txn_id')); ?></th>
		<th ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?></th>
		<th ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date')); ?></th>
		<th width="100"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_amount')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?></th>
		<th width="80"></th>
		
	</tr>
	<?php
		$paging = new paging();
		$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
		$limit = 40;
		$qLimit = " limit $offset,$limit";
		$extraURL = '';
		
		
		$sql = 'select im.*,um.username, cm.prefix, gm.gateway
					from ' . INVOICE_MASTER . ' im
					left join '.USER_MASTER.' um on (im.user_id = um.id)
					left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)
					left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)
					where im.status=0 and im.paid_status=2
				order by im.id DESC';
		$query = $mysql->query($sql);
		$strReturn = "";
		
		$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_ADMIN . 'users_credit_request.html',$offset,$limit,$extraURL);
		
		$i = $offset;

		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				$i++;
				echo '<tr>';
					echo '<td>' . $i . '</td>';
					echo '<td>'.$row['txn_id']. '</td>';
					echo '<td>'.$row['username']. '</td>';
					echo '<td>' . date("d-M Y H:i", strtotime($row['date_time'])) . '</td>';
					echo '<td><h2>' . $row['prefix'] . ' ' . $row['amount'] . '</h2></td>';
					echo '<td>' . $row['credits'] . '</td>';
					echo '<td class="btn-group">
						<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_credit_rejected_accept_process.do?id=' . $row['id'] . '" class="btn btn-primary" >'. $admin->wordTrans($admin->getUserLang(),'Accept').'</a>
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
	<?php echo $pCode;?>