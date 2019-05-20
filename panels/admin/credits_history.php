<?php
	defined("_VALID_ACCESS") or die("Restricted Access");

	$id = $request->getInt('id');
	$firstC = $request->GetStr('firstC');
    $limit = $request->GetInt('limit');
    $offset = $request->GetInt('offset');
    $username = $request->GetStr('username');
	$imei = $request->GetStr('imei');
	
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
	if($imei != '')
	{
		$getString .= '&imei='. $imei;
	}
	$getString = trim($getString, '&');
?>
	<div class="row">
		<div class="col-lg-12">
			<ul class="breadcrumb">
				<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> Dashboard</a></li>
				<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html">Users</a></li>
				<li class="active">Transections</li>
			</ul>
		</div>
	</div>


<div class="panel">
	<div class="panel-heading">Credit Invoices</div>
	<table class="table table-striped table-hover">
	<thead>
			<tr>
			<th width="60"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_trans._id')); ?> </th>
			<th width="60"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_order_id')); ?> </th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_transection')); ?> </th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_note')); ?> </th>
			<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_time')); ?> </th>
			<th width="50" style="text-align:center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cr')); ?> </th>
			<th width="50" style="text-align:center"></th>
			<th width="50" style="text-align:center"></th>
		</tr>
	</thead>
	<?php
		$paging = new paging();
		$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
		$limit = 40;
		$qLimit = " limit $offset,$limit";
		$extraURL = '&id=' . $id . '&imei=' . $imei;
		
		$qStr = '';
		if($imei != '')
		{
			$qStr .= ' and oim.imei like ' . $mysql->quote($imei);
		}
		
		
		$sql = 'select
					ctm.* , 
					um.username as username1, 
					um2.username as username2,
					oim.imei
				from ' . CREDIT_TRANSECTION_MASTER . ' ctm
				left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)
				left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)
				left join ' . ORDER_IMEI_MASTER . ' oim on (ctm.order_id_imei = oim.id)
				where (ctm.user_id=' . $mysql->getInt($id) . ' or ctm.user_id2=' . $mysql->getInt($id) . ')
				' . $qStr . '
				order by ctm.id DESC';
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
				echo '<td align="center"><b>' . $row['id'] . '</b><br /><small>' . $i . '</small></td>';
				echo '<td align="center">';
					echo ($row['order_id_imei'] != '0') ? $row['order_id_imei'] : '';
					echo ($row['order_id_file'] != '0') ? $row['order_id_file'] : '';
					echo ($row['order_id_server'] != '0') ? $row['order_id_server'] : '';
				echo '</td>';
				echo '<td><b>' . $row['info'] . '</b>';
				echo ($row['order_id_imei'] != '0') ? '<br /><small>' . $row['imei'] . '</small>': '';
				echo '<br />';
				switch($row['trans_type'])
				{
					case 6:
						echo (($id == $row['user_id']) ? $row['username2'] : $row['username1']);
						break;
				}
				echo '</td>';
				echo '<td>';
					echo (($row['admin_note'] != '') ? ('<b>Admin: ' . $row['admin_note'] . '</b><br />') : '');
					echo (($row['user_note'] != '') ? ('<i>User: ' . $row['user_note'] . '</i>') : '');
				echo '</td>';
				echo '<td>' . date("d-M Y H:i", strtotime($row['date_time'])) . '</td>';
				
				echo '<td align="center">';
					switch($row['trans_type'])
					{
						case 0:
							echo '<img src="' . CONFIG_PATH_IMAGES . 'skin/status_add_16.png" width="16" height="16" alt="" />';
							break;
						case 1:
							echo '<img src="' . CONFIG_PATH_IMAGES . 'skin/status_add_16.png" width="16" height="16" alt="" />';
							break;
						case 2:
							echo '<img src="' . CONFIG_PATH_IMAGES . 'skin/status_away_16.png" width="16" height="16" alt="" />';
							break;
						case 3:
							echo '<img src="' . CONFIG_PATH_IMAGES . 'skin/status_busy_16.png" width="16" height="16" alt="" />';
							break;
						case 6:
							if($id == $row['user_id'])
							{
								echo '<img src="' . CONFIG_PATH_IMAGES . 'skin/forward_16.png" width="16" height="16" alt="" />';
							}
							else
							{
								echo '<img src="' . CONFIG_PATH_IMAGES . 'skin/back_16.png" width="16" height="16" alt="" />';
							}
							break;
					}
				echo '</td>';
				
				echo '<td align="center"><b>' . $row['credits'] . '</b></td>';
				echo '<td align="center">
						<b>' . (($id == $row['user_id']) ? $row['credits_after'] : $row['credits_after_2']) . '</b>
						<br />
							<small>
								<u>'. (($id == $row['user_id']) ? $row['credits_after_process'] : $row['credits_after_process_2']) . '</u>
							/ ' . (($id == $row['user_id']) ? $row['credits_after_used'] : $row['credits_after_used_2']) . '
							</small>
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
	<?php echo $pCode;?>
	<div class="toolbarSkin">
	<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>users.html<?php echo (($getString!='') ? ('?'. $getString) : '') ?>" style="float:left"><?php echo '<i class="icon-arrow-left"></i>'; ?> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_back')); ?> </a>
	</div>