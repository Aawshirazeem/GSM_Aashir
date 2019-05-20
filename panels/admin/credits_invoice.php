<?php
	defined("_VALID_ACCESS") or die("Restricted Access");

	$id = $request->getInt('id');
	$firstC = $request->GetStr('firstC');
    $limit = $request->GetInt('limit');
    $offset = $request->GetInt('offset');
    $username = $request->GetStr('username');
	
	$getString = "";
	if($firstC != '')
	{
		$getString .= '&firstC='. $firstC;
	}
	if($limit != 0)
	{
		$getString .= '&limit='. $limit;
	}
	if($offset != 0)
	{
		$getString .= '&offset='. $offset;
	}
	if($username != '')
	{
		$getString .= '&username='. $username;
	}
	$getString = trim($getString, '&');
?>

	<div class="row">
		<div class="col-lg-12">
			<ul class="breadcrumb">
				<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),'Dashboard'); ?></a></li>
				<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html"><?php echo $admin->wordTrans($admin->getUserLang(),'Users'); ?></a></li>
				<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Add Credits'); ?></li>
			</ul>
		</div>
	</div>


<div class="panel">
	<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),'Credit Invoices'); ?></div>
	<table class="table table-striped table-hover">
	<tr>
		<th width="80"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_invoice_id')); ?> </th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_date')); ?> </th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_amount')); ?> </th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?> </th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_gateway')); ?> </th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_status')); ?> </th>
		<th width="80"></th>
	</tr>
	<tr class="searchPanel hidden">
		<td></td>
		<td></td>
		<td><input type="text" class="textbox_small" name="username" id="username" value="" /></td>
		<td class="toolbarSkin text_center" style="text-align:center">
			<input type="submit" value="Search" /><input type="button" class="showHideSearch" value="Cancel" />
		</td>
	</tr>
	<?php
		$paging = new paging();
		$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
		$limit = 40;
		$qLimit = " limit $offset,$limit";
		$extraURL = '&id=' . $id;
		
		
		$sql = 'select im.*, cm.prefix, gm.gateway
					from ' . INVOICE_MASTER . ' im
					left join ' . CURRENCY_MASTER . ' cm on (im.currency_id = cm.id)
					left join ' . GATEWAY_MASTER . ' gm on (im.gateway_id = gm.id)
					where user_id=' . $mysql->getInt($id) . '
				order by im.id DESC';
		$query = $mysql->query($sql . $qLimit);
		$strReturn = "";
		
		$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_ADMIN . 'credits_history.html',$offset,$limit,$extraURL);
		
		$i = $offset;

		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				$i++;
				echo '<tr>';
				echo '<td>INV #' . str_pad($row['id'],4,'0',0) . '</td>';
				echo '<td>' . date("d-M Y H:i", strtotime($row['date_time'])) . '</td>';
				echo '<td>' . $row['prefix'] . ' ' . $row['amount'] . '</td>';
				echo '<td>' . $row['credits'] . '</td>';
				echo '<td>' . $row['gateway'] . '</td>';
				echo '<td>';
					switch($row['status'])
					{
						case '0':
							echo 'Pending';
							break;
						case '1':
							echo 'Done';
							break;
						case '2':
							echo 'Canceled';
							break;
					}
				echo '</td>';
				echo '<td>
						<a href="' . CONFIG_PATH_SITE_ADMIN . 'invoice.html?id=' . $row['id'] . '" class="btn btn-primary" >' .  $admin->wordTrans($admin->getUserLang(),$lang->get('com_view')) . '</a>
					  </td>';
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="11" class="no_record">No record found!</td></tr>';
		}
	?>
	</table>
</div>
	<?php echo $pCode;?>