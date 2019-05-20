<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetSupplier('supplier_key_33455gkgk5d2');

	$supplier->checkLogin();
	$supplier->reject();

	$type = $request->getStr('type');
	$all = $request->getInt('all');
	$all = 0;
	
	$showUser = $showCredits = 0;
	$sql = 'select * from ' . SUPPLIER_MASTER . ' where id=' . $supplier->getUserId();
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$showUser = $rows[0]['show_user'];
		$showCredits = $rows[0]['show_credits'];
	}
?>
<form action="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_file_<?php echo $type; ?>_process.do" enctype="multipart/form-data" method="post">
<div class="lock-to-top">
	<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_orders')); ?></h1>
	<div class="btn-group">
		<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_process_selected')); ?>" class="btn btn-success btn-sm btn-flat m-b-10" />
	</div>
</div>

	<div class="btn-group btn-group-sm m-b-10">
		<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_file.html?type=pending&all=<?php echo $all;?>" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_pending')); ?></a>
		<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_file.html?type=locked&all=<?php echo $all;?>" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_locked')); ?></a>
		<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_file.html?type=accepted&all=<?php echo $all;?>" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_available')); ?></a>
		<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_file.html?type=rejected&all=<?php echo $all;?>" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unavailable')); ?></a>
		<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_file.html?all=<?php echo $all;?>" class="btn btn-primary"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_orders')); ?></a>
	</div>
	<div class="btn-group pull-right" style="display:none">
		<?php
			if($all == 0)
			{
				echo '<a href="' . CONFIG_PATH_SITE_SUPPLIER . 'order_file.html?all=1">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_l_order')) . '</a>';
			}
			else
			{
				echo '<a href="' . CONFIG_PATH_SITE_SUPPLIER . 'order_file.html">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_my_order')) . '</a>';
			}
		?>
	</div>
	<div class="clear"></div>
	<input type="hidden" value="<?PHP echo $type?>" name="type" id="type">
	<input type="hidden" value="<?PHP echo $all?>" name="all" id="all">
	<table class="MT5 table table-striped table-hover panel">
	<tr>
		<th width="60"></th>
		<th width="16"></th>
		<?php echo ($type == "pending") ? '<th width="50" style="text-align:center; color:#000077">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_lock')) . '</th>' : ''; ?>
		<?php echo ($type == "locked") ? '<th width="50" style="text-align:center">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_accept')) . '</th>' : ''; ?>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_name')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_name')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unlock_code')); ?></th>
		<th></th>
		<th width="150"></th>
		<th width="90"></th>
	</tr>
	<tr class="searchPanel hidden">
		<td></td>
		<td></td>
		<td class="toolbarSkin text_center" style="text-align:center">
			<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?>" /><input type="button" class="showHideSearch" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?>" />
		</td>
	</tr>
	<?php
		$paging = new paging();
		$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
		$limit = 20;
		$qLimit = " limit $offset,$limit";
		$extraURL = '&type=' . $type . '&all=' . $all;
		
		
		$qType = '';
		

		switch($type)
		{
			case 'pending':
				$qType = ' and ofsm.status=0 ';
				break;
			case 'locked':
				$qType = ' and ofsm.status=-1 and supplier_id=' . $supplier->getUserId();
				break;
			case 'accepted':
				$qType = ' and ofsm.status=1 and supplier_id=' . $supplier->getUserId();
				break;
			case 'rejected':
				$qType = ' and ofsm.status=2 and supplier_id=' . $supplier->getUserId();
				break;
		}
		if($all != '1' && $type != 'pending')
		{
			$qType .= ' and supplier_id=' . $supplier->getUserId();
		}
		
		$sql = 'select ofsm.*,
					slm.service_name,um.username,um.email
					from ' . ORDER_FILE_SERVICE_MASTER . ' ofsm
					left join ' . USER_MASTER . ' um on(ofsm.user_id = um.id)
					left join ' . FILE_SERVICE_MASTER . ' slm on (slm.id = ofsm.file_service_id)
					where ofsm.file_service_id in (select service_id from ' . FILE_SUPPLIER_DETAILS . ' where supplier_id=' . $supplier->getUserId() . ')
					' . $qType . '
					order by ofsm.id DESC';
		//echo $sql;
		
		$query = $mysql->query($sql . $qLimit);
		$strReturn = "";
		
		$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_SUPPLIER . 'order_file.html',$offset,$limit,$extraURL);
		
		$i = $offset;

		if($mysql->rowCount($query) > 0)
		{
			
			$subtotal = $grandTotal = 0;
			
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				$i++;
				echo '<input type="hidden" name="Ids[]" value=' . $row['id'] . '>';
				echo '<input type="hidden" name=username_' . $row['id'] .  ' value=' . $row['username'] . '>';
				echo '<input type="hidden" name=email_' . $row['id'] .  ' value=' . $row['email'] . '>';
				echo '<input type="hidden" name=user_id_' . $row['id'] .  ' value=' . $row['user_id'] . '>';
				echo '<input type="hidden" name=service_name_' . $row['id'] .  ' value=' . $row['service_name'] . '>';
				echo '<input type="hidden" name=file_name_' . $row['id'] .  ' value=' . $row['fileask'] . '>';
				echo '<input type="hidden" name=credits_' . $row['id'] .  ' value=' . $row['credits'] . '>';
				echo '<tr>';
				echo '<td>' . $i . '</td>';
				echo '<td>';
					switch($row['status'])
					{
						case 0:
							echo '<span class="label label-default">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_pending')).'</span>';
							break;
						case -1:
							echo '<span class="label label-primary">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_locked')).'</span>';
							break;
						case 1:
							echo '<span class="label label-success">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_available')).'</span>';
							break;
						case 2:
							echo '<span class="label label-danger">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_unavailable')).'</span>';
							break;
					}
					
				echo '</td>';
				echo ($type == "pending") ? '<td class="text-center"><input type="checkbox" class="checkbox-inline" name="locked_' . $row['id'] . '"></td>' : '';
				echo ($type == "locked") ? '<td><input type="checkbox" class="checkbox-inline" name="accept_' . $row['id'] . '"></td>' : '';
			    echo '<td>';
					echo '<h2>' . $row['service_name'] . '</h2>';
					echo ($showUser == 1) ? '<span class="text-warning">' . $row['username'] . '</span>' : '';
				echo '</td>';
				echo '<td><small>' . $row['fileask'] . '<br />' . $row['filerpl'] . '</small></td>';
				echo '<td>' . (($row['unlock_code'] !='' and $row['unlock_code'] != '0') ? nl2br($row['unlock_code']) : '') . '</td>';
				echo '<td>';
					echo ($showCredits == 1) ? '<span class="label label-info">' . $row['credits'] . '</span>' : '';
					if($type == 'accepted')
					{
						$grandTotal += $row['credits_purchase'];
						echo '<span class="text-danger">' . $row['credits_purchase'] . '</span>';
					}
				echo '</td>';
				echo ($type == "locked") ? '<td width="100" class="text-center"><input type="checkbox" class="checkbox-inline" name="reject_' . $row['id'] . '"><br /><input type="text" name="un_remarks_' . $row['id'] . '" value="" class="form-control"></td>' : '';
				echo '<td>
					<div class="btn-group">';
					$ask = CONFIG_PATH_EXTRA_ABSOLUTE . "file_service/" . $mysql->prints($row['fileask']);
					$rpl = CONFIG_PATH_EXTRA_ABSOLUTE . "file_service/" . $mysql->prints($row['filerpl']);				
					
					if(file_exists($ask) and $row['fileask'] != "")
					{
						echo '<a href="' . CONFIG_PATH_SITE . 'cmd/download.do?type=askrpl&file_name=' . $row['fileask'] . '" class="btn btn-default btn-sm"><i class="icon-download"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_ask')) . '</a>';
					}
					if(file_exists($rpl) and $row['filerpl'] != "")
					{
						echo '<a href="' . CONFIG_PATH_SITE . 'cmd/download.do?type=askrpl&file_name=' . $row['filerpl'] . '" class="btn btn-default btn-sm"><i class="icon-download"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_rpl')) . '</a>';
					}
				echo '	</div>
					</td>';
				echo '<td class="text-center">';
				if($row['status'] == '-1')
				{
					echo '<a href="' . CONFIG_PATH_SITE_SUPPLIER . 'order_file_update.html?id=' . $row['id'] . '&user_id=' . $row['user_id'] . '" class="btn btn-success btn-sm"><i class="icon-ok"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_update')) . '</a>';
				}
				echo '</td>';
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="9" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '!</td></tr>';
		}
	?>
	</table>
	<?php
		if($type == 'accepted')
		{
			echo '<h3>'.$admin->wordTrans($admin->getUserLang(),'Total Profit').':' . $grandTotal . '</h3>';
		}
		if($type == 'pending' || $type == 'locked')
		{
			echo '<div class="btn-group"><input type="submit" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_process_selected')) . '" class="btn btn-success"/></div>';
		}
	?>

	</form>
	<?php echo $pCode;?>