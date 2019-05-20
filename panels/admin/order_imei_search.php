<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$type = $request->GetStr('type');
	$imei = $request->GetStr('imei');
	$userid = $request->GetInt('userid');
	$supplierid = $request->GetInt('supplierid');
	
	$exact = $request->GetCheck('exact');
	
	$sqlCount = 'select

				(select count(id) as total from ' . ORDER_IMEI_MASTER . ' im where (im.status=2 and im.verify=1)) as verificationIMEI
				
				from ' . ADMIN_MASTER . ' am where id=' . $mysql->getInt($admin->getUserId());
	$queryCount = $mysql->query($sqlCount);
	$rowsCount = $mysql->fetchArray($queryCount);
	$rowCount = $rowsCount[0];
	
	$verifyCount = ($rowCount['verificationIMEI'] > 0) ? (' [' . $rowCount['verificationIMEI'] . ']') : '';
	
?>

<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_advanced_imei_job_search')); ?> </h1>

	<div class="clear"></div>
	<?php
		if($imei != '')
		{
			echo '<div class="clearSearch"><a href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei.html?type=' . $type . '"> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_click_here_to_clear_filter')) . ' </a></div>';
		}
	?>
	
		  <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei_search.html" method="get" name="frm_customer_edit_login" id="frm_customer_edit_login" class="formSkin">
			<fieldset>
			<legend></legend>
				<p class="form-group">
					<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei')); ?> </label>
					<input name="imei" type="text" class="form-control" id="imei" value="<?php echo $imei;?>" />
					<input type="hidden" name="type" value="<?php echo $type;?>">
					<input type="checkbox" name="exact" <?php echo (($exact == 1) ? 'checked="checked"' : '')?>><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_exact')); ?>
               </p>
				<p class="form-group">
					<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?> </label>
					<select name="userid" class="textbox_big">
						<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_users')); ?> </option>
						<?php
							$sql_usr = 'select id, username from ' . USER_MASTER . ' order by username';
							$query_usr = $mysql->query($sql_usr);
							$rows_usr = $mysql->fetchArray($query_usr);
							foreach($rows_usr as $row_usr)
							{
								echo '<option ' . (($row_usr["id"] == $userid) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
							}
						?>
					</select>
				</p>
				<p class="form-group">
					<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_supplier')); ?> </label>
					<select name="supplierid" class="textbox_big">
						<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_users')); ?> </option>
						<?php
							$sql_usr = 'select id, username from ' . SUPPLIER_MASTER . ' order by username';
							$query_usr = $mysql->query($sql_usr);
							$rows_usr = $mysql->fetchArray($query_usr);
							foreach($rows_usr as $row_usr)
							{
								echo '<option ' . (($row_usr["id"] == $supplierid) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
							}
						?>
					</select>
				</p>
				<p class="butSkin" style="text-align:center">
					<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?>" />
				</p>
			</fieldset>
		  </form>

	<input type="hidden" value="<?PHP echo $type?>" name="type" id="type">
	<input type="hidden" value="<?PHP echo $supplier_id?>" name="supplier_id" id="supplier_id">
	<?php
		$qType = '';
		
		
		if($supplierid != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.supplier_id=' . $mysql->getInt($supplierid) . ' ';
		}
		if($imei != '')
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.imei like ' . (($exact == 1) ? $mysql->quote($imei) : $mysql->quoteLike($imei) ) . ' ';
		}
		if($userid != '')
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.user_id = ' . $mysql->getInt($userid) . ' ';
		}
		if($qType != '')
		{
	?>
	<table class="MT5 details text_12 ui-corner-all ui-widget-content">
	<tr>
		<th width="60"></th>
		<th width="16"></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei')); ?> </th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unlock_code')); ?> </th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_service/supplier')); ?> </th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_time')); ?> </th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?> </th>
	</tr>
	<?php
		$paging = new paging();
		$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
		$limit = 20;
		$qLimit = " limit $offset,$limit";
		$extraURL = '&type=' . $type . '&imei=' . $imei . '&userid=' . $userid . '&supplierid=' . $supplierid;
		
		
			$qType = ($qType == '') ? '' : ' where ' . $qType;
			
			
			$sql = 'select im.*, im.id as imeiID,
						DATE_FORMAT(im.date_time, "%d-%b-%Y %k:%i") as dtDateTime,
						DATE_FORMAT(im.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,
						um.username as username,
						tm.tool_name as tool_name, 
						cm.country as country_name, 
						nm.network as network_name,
						mm.model as model_name, 
						bm.brand as brand_name,
						sm.username as supplier,
						DATE_FORMAT(im.supplier_paid_on, "%d-%b-%Y %k:%i") as dtSupplier
						from ' . ORDER_IMEI_MASTER . ' im
						left join ' . USER_MASTER . ' um on(im.user_id = um.id)
						left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
						left join ' . IMEI_COUNTRY_MASTER . ' cm on(im.country_id = cm.id)
						left join ' . IMEI_NETWORK_MASTER . ' nm on(im.network_id = nm.id)
						left join ' . IMEI_MODEL_MASTER . ' mm on(im.model_id = mm.id)
						left join ' . IMEI_BRAND_MASTER . ' bm on(im.brand_id = bm.id)
						left join ' . SUPPLIER_MASTER . ' sm on(im.supplier_id = sm.id)
						' . $qType . '
						order by im.id DESC';
			
			$query = $mysql->query($sql);
			$strReturn = "";
			
			$i = 0;

			if($mysql->rowCount($query) > 0)
			{
				$rows = $mysql->fetchArray($query);
				foreach($rows as $row)
				{
					$i++;
					echo '<tr>';
					echo '<td>' . $i . '</td>';
					echo '<td>';
						switch($row['status'])
						{
							case 0:
								echo '<small style="font-weight:bold">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_pending')). '</small>';
								break;
							case 1:
								echo '<small style="color:#888888">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_locked')) . '</small>';
								break;
							case 2:
								echo '<small style="color:#00AA00">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_available')) .' </small>';
								break;
							case 3:
								echo '<small style="color:#FF0000">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_unavailable')) . '</small>';
								break;
						}
					echo '</td>';
					echo '<td>';
								echo '<b>' . $row['imei'] . '</b>';
								echo '<br>' . $mysql->prints($row['username']);
					echo '</td>';
					echo '<td>';
							echo nl2br(base64_decode($row['reply']));
					echo '</td>';
					echo '<td>' . $mysql->prints($row['tool_name']) . (($row['supplier'] != '') ? ('<br><small><b>' . $mysql->prints($row['supplier']) . '</b></small>') : '') . '</td>';
					echo '<td><small>' . $row['dtDateTime'] . '<br /><b>' . $row['dtReplyDateTime'] . '</b></small></td>';
					echo '<td>' . $row['credits'] . '</td>';
					echo '</tr>';
				}
			}
			else
			{
				echo '<tr><td colspan="20" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
			}
		?>
		</table>

		<?php
		}
		else
		{
			echo '<center><h2>--</h2></center>';
		}
	?>