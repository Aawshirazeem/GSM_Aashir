<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$type = $request->getStr('type');
	
	if($service_prepaid == "0")
	{
		echo "<h1>You are authorize to view this page!</h1>";
		return;
	}
	
?>
<h3><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_prepaid_log_orders')); ?></h3>
<div class="row">
	<div class="col-sm-8"></div>
	<div class="col-sm-4 text-right">
		<div class="btn-group">
			<a href="<?php echo CONFIG_PATH_SITE_USER;?>prepaid_logs_submit.html" class="btn btn-success"><i class="icon icon-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_new_prepaid_log')); ?></a>
		</div>
	</div>
</div>
	
<table class="MT5 table table-striped table-hover panel">
	<tr>
		<th width="16"></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_prepaid_logs')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username/password')); ?></th>
		<th></th>
	</tr>
	<?php
		$paging = new paging();
		$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
		$limit = 20;
		$qLimit = " limit $offset,$limit";
		$extraURL = '&type=' . $type;

		
		$sql= 'select
					plum.*, um.username as uname, plm.prepaid_log_name,
					DATE_FORMAT(plum.date_order, "%d-%b-%Y %k:%i") as dtOrder
				from ' . PREPAID_LOG_UN_MASTER . ' plum
				left join ' . PREPAID_LOG_MASTER . ' plm on (plum.prepaid_log_id = plm.id)
				left join ' . USER_MASTER . ' um on (plum.user_id = um.id)
				where plum.status!=0 and plum.user_id=' . $member->getUserId() . '
				order by plum.id DESC';
		//echo $sql;
		
		$query = $mysql->query($sql . $qLimit);
		$strReturn = "";
		
		$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_USER . 'order_imei.html',$offset,$limit,$extraURL);
		
		$i = $offset;

		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				$i++;
				echo '<tr>';
				echo '<td>' . $i . '</td>';
			    echo '<td>' . $row['prepaid_log_name'] . '</td>';
			    echo '<td>' . $row['username'] . '</td>';
			    echo '<td>' . $row['dtOrder'] . '</td>';
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="6" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '!</td></tr>';
		}
	?>
</table>
<?php echo $pCode;?>