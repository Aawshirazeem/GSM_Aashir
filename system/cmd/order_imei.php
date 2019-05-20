<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	
	/*
	$sql = 'update nxt_order_imei_master 
					set 
							ip= case 
							when id=1 then "111"
							when id=2 then "222"
							end 
					where id in (1,2)
							';
	$mysql->query($sql);
	echo $sql;
	exit();
	*/
	
	$limit = $request->getInt('limit');	
	$type = $request->GetStr('type');
	$supplier_id = $request->GetInt('supplier_id');
	$imei = $request->GetStr('imei');
	$no_paging = $request->GetInt('no_paging');
	
	$search_tool_id = $request->GetInt('search_tool_id');
	$search_user_id = $request->GetInt('search_user_id');
	$search_supplier_id = $request->GetInt('search_supplier_id');
	$ip=$request->GetStr('ip');
	$user_id=$request->GetInt('user_id');
	
	$hide_user = $request->GetInt('hide_user');
	
	
	//split IMEI in new line
	$imeis = explode("&#13;&#10;", $imei);
	$txtImeis = "";
	foreach($imeis as $im)
	{
		if(is_numeric($im))
		{
			$txtImeis .= $im . ',';
		}
		else
		{
			if($im != '')
			{
				$graphics->messagebox($im . ': Not a valid IMEI Number!');
			}
		}
	}
	$txtImeis = rtrim($txtImeis,',');
	
	$sqlCount = 'select

				(select count(id) as total from ' . ORDER_IMEI_MASTER . ' im where (im.status=2 and im.verify=1)) as verificationIMEI
				
				from ' . ADMIN_MASTER . ' am where id=' . $mysql->getInt($admin->getUserId());
	$queryCount = $mysql->query($sqlCount);
	$rowCount = 0;
	if($mysql->rowCount($queryCount) > 0)
	{
		$rowsCount = $mysql->fetchArray($queryCount);
		$rowCount = $rowsCount[0];
	}
	
	$verifyCount = ($rowCount['verificationIMEI'] > 0) ? (' [' . $rowCount['verificationIMEI'] . ']') : '';
	

	$paging = new paging();
	$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
	if($limit=='')
	{
		$limit = 100;
	}
	
	$pStringLimit='';
	$pString='';
	if($type != '')
	{
		$pStringLimit .= (($pStringLimit != '') ? '&' : '' ) . 'type=' . $type;
	}
	if($supplier_id != 0)
	{
		$pStringLimit .= (($pStringLimit != '') ? '&' : '' ) . 'supplier_id=' . $supplier_id;
		$pString .= (($pString != '') ? '&' : '' ) . 'supplier_id=' . $supplier_id;
	}
	if($ip != '')
	{
		$pStringLimit .= (($pStringLimit != '') ? '&' : '') . 'ip=' . $ip;
	}
	if($user_id != 0)
	{
		$pStringLimit .= (($pStringLimit != '') ? '&' : '') . 'user_id=' . $user_id;
	}
	if($search_tool_id != 0)
	{
		$pStringLimit .= (($pStringLimit != '') ? '&' : '') . 'search_tool_id=' . $search_tool_id;
	}
	$pStringLimit = trim($pStringLimit, '&');
	
	
	if($limit != 0 && $no_paging != 0)
	{
		$pString .= (($pString != '') ? '&' : '' ) . 'limit=' . $limit;
	}
	if($ip != '')
	{
		$pString .= (($pString != '') ? '&' : '') . 'ip=' . $ip;
	}
	if($user_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '') . 'user_id=' . $user_id;
	}
	if($search_tool_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '') . 'search_tool_id=' . $search_tool_id;
	}
	$pString = trim($pString, '&');
	
	$qLimit = " limit $offset,$limit";
	$extraURL = '&type=' . $type . '&supplier_id=' . $supplier_id . '&search_tool_id=' . $search_tool_id . '&search_user_id=' . $search_user_id . '&search_supplier_id=' . $search_supplier_id . '&user_id=' . $user_id. '&ip=' . $ip;

?>

<h1><?php $lang->prints('lbl_imei_jobs'); ?> </h1>
	
	<div class="toolbarSkin toolbarBig TA_L FL">
		<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html<?php echo ($pString != '') ? ('?' . $pString) : ''?>" <?php echo ($type== '') ? 'class="active"' : ''; ?>><?php $lang->prints('com_all_orders'); ?> </a><a
			href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?type=pending<?php echo (($pString!='') ? ('&' . $pString ) : '');?>" <?php echo ($type== 'pending') ? 'class="active"' : ''; ?>><?php $lang->prints('com_pending'); ?> </a><a
			href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?type=locked<?php echo (($pString!='') ? ('&' . $pString ) : '');?>" <?php echo ($type== 'locked') ? 'class="active"' : ''; ?>><?php $lang->prints('com_locked'); ?> </a><a
			href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?type=avail<?php echo (($pString!='') ? ('&' . $pString ) : '');?>" <?php echo ($type== 'avail') ? 'class="active"' : ''; ?>><?php $lang->prints('com_available'); ?> </a><a 
			href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?type=rejected<?php echo (($pString!='') ? ('&' . $pString ) : '');?>" <?php echo ($type== 'rejected') ? 'class="active"' : ''; ?>><?php $lang->prints('com_rejected'); ?> </a><?php
				if($rowCount['verificationIMEI'] > 0)
				{
					echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei.html?type=verify' . (($supplier_id != '') ? ('&supplier_id=' . $supplier_id) : '') . '" ' . (($type== 'verify') ? 'class="active"' : '') . '>' . $lang->get('com_verification') . ' ' . $verifyCount . '</a>';
				}
			?>
	</div>
	<div class="toolbarSkin toolbarBig TA_R FR">
		<a href="javascript:void();" class="toggleSearch showDialog"><img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/search.png" width="10" height="10" alt="" /> <?php $lang->prints('com_search'); ?> </a>
	</div>
	<div class="clear"></div>
	
	<div id="showDialogPanel" class="hidden">
		  <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html" method="get" name="frm_customer_edit_login" id="frm_customer_edit_login" class="formSkin">
			<fieldset>
			<legend></legend>
				<p class="field">
					<label><?php $lang->prints('com_imei'); ?> </label>
					<textarea name="imei" class="textbox_fix" id="imei" rows="5"><?php echo $imei;?></textarea>
					<input type="hidden" name="type" value="<?php echo $type;?>">
					<input type="hidden" name="supplier_id" value="<?php echo $supplier_id;?>">
					<input type="hidden" name="ip" value="<?php echo $ip;?>">
					<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
					<input type="hidden" name="limit" value="<?php echo $limit;?>">
				</p>
				<p class="field">
					<label><?php $lang->prints('com_unlocking_tool'); ?> </label>
					<select name="search_tool_id" class="textbox_fix combo">
						<option value="0"><?php $lang->prints('com_all_tools'); ?> </option>
						<?php
							$sql_tool = 'select 
												itm.id as tool_id, itm.tool_name, itm.group_id, itm.credits, itm.delivery_time, itm.status,
												igm.group_name
										from ' . IMEI_TOOL_MASTER . ' itm
										left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
										order by itm.group_id, itm.tool_name';
							$query_tool = $mysql->query($sql_tool);
							$rows_tool = $mysql->fetchArray($query_tool);
							$groupName = $groupName2 = '';
							foreach($rows_tool as $row_tool)
							{
								if($groupName != $row_tool['group_name'])
								{
									echo ($groupName != '') ? '</optgroup>' : '';
									echo '<optgroup label="' . $row_tool['group_name'] . '" style="font-weight:bold;">';
									$groupName = $row_tool['group_name'];
								}
								echo '<option ' . (($row_tool['tool_id'] == $search_tool_id) ? 'selected="selected"' : '') . ' value="' . $row_tool['tool_id'] . '">' . $mysql->prints($row_tool['tool_name']) . '</option>';
							}
							echo ($groupName != '') ? '</optgroup>' : '';
						?>
					</select>
				</p>
				<p class="field">
					<label><?php $lang->prints('com_username'); ?> </label>
					<select name="search_user_id" class="textbox_fix combo">
						<option value="0"><?php $lang->prints('com_all_users'); ?> </option>
						<?php
							$sql_usr = 'select id, username from ' . USER_MASTER . ' order by username' ;
							$query_usr = $mysql->query($sql_usr);
							$rows_usr = $mysql->fetchArray($query_usr);
							foreach($rows_usr as $row_usr)
							{
								echo '<option ' . (($row_usr["id"] == $search_user_id || $row_usr["id"] == $user_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
							}
						?>
					</select>
				</p>
				<p class="field">
					<label><?php $lang->prints('com_supplier'); ?> </label>
					<select name="search_supplier_id" class="textbox_fix combo">
						<option value="0"><?php $lang->prints('com_all_suppliers'); ?> </option>
						<?php
							$sql_usr = 'select id, username from ' . SUPPLIER_MASTER . ' order by username';
							$query_usr = $mysql->query($sql_usr);
							$rows_usr = $mysql->fetchArray($query_usr);
							foreach($rows_usr as $row_usr)
							{
								echo '<option ' . (($row_usr["id"] == $search_supplier_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';
							}
						?>
					</select>
				</p>
				<p class="butSkin" style="text-align:center">
					<input type="submit" value="<?php $lang->prints('com_search'); ?>" />
				</p>
			</fieldset>
		  </form>
	</div>
	
	<?php
		if(trim($imei) != '' || $search_tool_id != 0 || $search_user_id != 0 || $search_supplier_id != 0)
		{
			echo $graphics->messageBox('<a href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei.html'. (($type!='' ) ? ('?type=' . $type) : '')  . '">'.$lang->get('lbl_click_here_to_clear_filter') .'</a>');
		}
	?>
	<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei_<?php echo ($type != '') ? $type : 'all'; ?>_process.do" enctype="multipart/form-data" method="post" name="frm_imeis" id="frm_imeis" class="formSkin">
	
		<input type="hidden" name="imei" value="<?php echo $imei;?>">
		<input type="hidden" name="type" value="<?php echo $type;?>">
		<input type="hidden" name="supplier_id" value="<?php echo $supplier_id;?>">
		<input type="hidden" name="search_tool_id" value="<?php echo $search_tool_id;?>">
		<input type="hidden" name="ip" value="<?php echo $ip;?>">
		<input type="hidden" name="search_user_id" value="<?php echo $search_user_id;?>">
		<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
		<input type="hidden" name="limit" value="<?php echo $limit;?>">
	
	<?php
		ob_flush();
		ob_start(); ?>
	
	<div class="clear"></div>
	<table class="MT5 details text_12 ui-corner-all ui-widget-content extraImeis">
	<tr class="ui-widget-header ui-state-default">
		<th width="16"></th>
		<th width="16"></th>
		<th><?php $lang->prints('com_imei'); ?></th>
      	<?php echo ($type == 'verify') ? '<th width="60"><label>Veify<input type="checkbox" value="" id="Verify" class="selectAllBoxes" /></label></th>' : '';?>
		<th><a href="#" class="toggle" id="code"><?php $lang->prints('com_unlock_code'); ?> </a><textarea name="" id="codeBox" class="textbox_small hidden autoFillText" style="width:100px;" rows="2"></textarea></th>
		<th><?php $lang->prints('lbl_service/supplier'); ?> </th>
		<th><?php $lang->prints('com_date_time'); ?> </th>
		<th><?php $lang->prints('com_credits'); ?> </th>
		<?php echo ($type == "avail") ? '<th style="text-align:center">Return</th>' : ''; ?>
		<?php echo ($supplier_id != 0 and $type == 'avail') ? '<th style="text-align:center"><label><input type="checkbox" value="" id="Pay" class="selectAllBoxes" />' . $lang->get('com_pay') . '</label></th>' : ''; ?>
		
		<th width="16"></th>
	</tr>
	<?php
		
		
		$qType = '';
		
		switch($type)
		{
			case 'pending':
				$qType = ' im.status=0 ';
				break;
			case 'locked':
				$qType = ' im.status=1 ';
				break;
			case 'avail':
				$qType = ' im.status=2 ';
				break;
			case 'rejected':
				$qType = ' im.status=3 ';
				break;
			case 'verify':
				$qType = ' im.status=2 and im.verify=1 ';
				break;
		}
		
		if($supplier_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.supplier_id = ' . $supplier_id;
		}
		if(trim($txtImeis) != '')
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.imei in (' . $txtImeis . ') ';
		}
		if($search_tool_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.tool_id = ' . $search_tool_id;
		}
		if($search_user_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.user_id = ' . $search_user_id;
		}
		
		if($search_supplier_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.supplier_id = ' . $search_supplier_id;
		}
		if($ip != '')
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.ip = ' . $mysql->quote($ip);
		}
		if($user_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' um.id = ' . $mysql->getInt($user_id) ;
		}
		
		$qType = ($qType == '') ? '' : ' where ' . $qType;
		
		$strUserFields = $strUserTbl = '';
		if($hide_user == 1)
		{
			//echo "hideUsers";
		}
		
		$sql = 'select im.*, im.id as imeiID,
					im.api_name, im.message,
					DATE_FORMAT(im.date_time, "%d-%b-%Y %k:%i") as dtDateTime,
					DATE_FORMAT(im.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,
					um.username as username,
					um.email as email,
					tm.tool_name as tool_name, 
					tm.tool_alias as tool_alias, 
					sm.username as supplier,
					DATE_FORMAT(im.supplier_paid_on, "%d-%b-%Y %k:%i") as dtSupplier
					from ' . ORDER_IMEI_MASTER . ' im
					left join ' . USER_MASTER . ' um on(im.user_id = um.id)
					left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
					left join ' . SUPPLIER_MASTER . ' sm on(im.supplier_id = sm.id)
					' . $qType . '
					order by im.id DESC';
	
		
		$query = $mysql->query($sql . (($imei == '' && $no_paging == '0') ? $qLimit : ''));
		$strReturn = "";
		
		$pCode = '';
		if($imei == '' && $no_paging == '0')
		{
			$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_ADMIN . 'order_imei.html',$offset,$limit,$extraURL);
		}
		
		$i = $offset;
		$totalRows = $mysql->rowCount($query);
		
		if($totalRows > 0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				$i++;
				echo '<tr>';
				
				//echo '<input type="hidden" name=username_' . $row['id'] . '  value=' . $row['username'] . '>'; // to send usernames of users to processing page
				//echo '<input type="hidden" name=imei_' . $row['id'] . '  value=' . $row['imei'] . '>'; // to send imeis  of different users
				echo '<input type="hidden" name=Ids[]" value=' . $row['id'] . '>';  // to send ids of users to processing page
				//echo '<input type="hidden" name=email_' . $row['id'] . '  value=' . $row['email'] . '>'; // to send emails of users to processing page
				//echo '<input type="hidden" name=user_id_' . $row['id'] . ' value=' . $row['user_id'] . '>'; // to send emails of users to processing page
				//echo '<input type="hidden" name=tool_name_' . $row['id'] . ' value=' . $row['tool_name'] . '>'; // to send tools  used by different users
				//echo '<input type="hidden" name=credits_' . $row['id'] . '  value=' . $row['credits'] . '>'; // to send credits of users to processing page

				
				echo '<td><input type="checkbox" class="subSelectLock" name="locked_' . $row['id'] . '"></td>';
				echo '<td class="text_center">
						' . $i . '<br />
						<small>im-' . $row['id'] . '</small><br />
						' . (($row['extern_id'] != '0') ? '<small style="color:#DD0000">' . $row['extern_id'] . '/' . $row['api_name'] . '</small>' : '') . '
					</td>';
			    echo '<td>';
							switch($row['status'])
							{
								case 0:
									echo '<small style="color:#888888">' . $lang->get('com_pending'). '</small>';
									break;
								case 1:
									echo '<small>' . $lang->get('com_locked') . '</small>';
									break;
								case 2:
									echo '<small style="color:#00AA00">' .$lang->get('com_available') .'</small>';
									break;
								case 3:
									echo '<small style="color:#FF0000">' . $lang->get('com_unavailable') . '</small>';
									break;
							}
							echo '<h1 style="font-size:26px;">' . $row['imei'] . '</h1>';
							echo ($row['username'] != '') ? ('<a href="users_edit.html?id=' . $row['user_id'] . '" target="user">' . $mysql->prints($row['username']) . '</a>') : '';
							echo ' <small style="color:#000077">' . $mysql->prints($row['ip']) . '</small>';
				echo '</td>';
				echo ($type == "verify") ? '<td>' . (($row['status'] == "2" && $row['verify'] == "1") ? '<input type="checkbox" class="subSelectVerify" name="verify_' . $row['id'] . '">' : '') . '</td>' : '';
				echo '<td style="position:relative">
						';
						if (defined("DEMO"))
						{
							echo '*****Demo*****' ;
						}
						else
						{
							if(($type == 'locked' and $row['status']=='1') or $type == 'avail' or $type == 'verify')
							{
								//echo ($type != "avail") ? '<div class="divCode" id="code_' . $row['id'] . '">' . nl2br(base64_decode($row['reply'])) . '</div>' : '';
								echo '<textarea name="unlock_code_' . $row['id'] . '" id="unlock_code_' . $row['id'] . '" class="textbox_small txtCode codeBoxFill" rows="7" style="width:400px">' . base64_decode($row['reply']) . '</textarea>';
								echo '<textarea name="unlock_code_' . $row['id'] . '_2" class="textbox_small" style="width:100px;display:none">' . base64_decode($row['reply']) . '</textarea>';
							}
							else
							{
								echo ($row['reply'] != '') ? nl2br(base64_decode($row['reply'])) : '';
							}
							echo ($row['message'] != '') ? '<div class="dim" style="background-color:#FFEEEE; padding:5px; margin:5px;"><b>' . $lang->get('com_admin_note') . '</b>: ' . $row['message'] . '</div>' : '';
							echo ($row['remarks'] != '') ? '<div class="dim" style="background-color:#EEFFEE; padding:5px; margin:5px;"><b>' . $lang->get('com_customer_note') . '</b>: ' . $row['remarks'] . '</div>' : '';
						}
						echo '<br />';
						echo ($type == 'locked' or $type == "verify") ? '' . (($row['status'] == '1' or $type == 'verify') ? '<input type="checkbox" name="unavailable_' . $row['id'] . '" id="check' . $row['id'] . '" class="subSelectUnavail toggleOnCheck"> <input type="text" name="un_remarks_' . $row['id'] . '" id="check' . $row['id'] . 'Hide" value="" class="textbox_small unTextBoxFill hidden">' : '') . '' : '';
				echo '</td>';
				echo '<td>' . $mysql->prints($row['tool_name']) . (($row['supplier'] != '') ? ('<br><small><b>' . $mysql->prints($row['supplier']) . '</b></small>') : '') . '</td>';
				echo '<td><small>' . $row['dtDateTime'] . '<br /><b>' . $row['dtReplyDateTime'] . '</b></small></td>';
				echo '<td><b>' . $row['credits'] . '</b>';
				if($row['credits_purchase'] != 0)
				{
					echo ' - <small>' . $row['credits_purchase'] . '</small>';
				}
				echo '</td>';
				echo ($type == "avail") ? '<td class="text_center"><input type="checkbox" name="return_' . $row['id'] . '" class="subSelectReturn"><br /><input type="text" name="return_remarks_' . $row['id'] . '" value="" class="textbox_small"></td>' : '';
				if($supplier_id != 0 and $type == 'avail')
				{
					echo '<td class="text_center">';
					if($row['supplier_paid'] == 0)
					{
						echo '<input type="checkbox" class="subSelectPay" name="pay_' . $row['id'] . '" id="pay_' . $row['id'] . '">';
					}
					else
					{
						echo '<small><b>' . $row['dtSupplier'] . '</b></small>';
					}
					echo '</td>';
				}
				echo '<td class="TA_C"><a href="order_imei_detail.html?id=' . urlencode($row['id']) . (($type!='' ) ? ('&type=' . $type . '&' . $pString) : ( '&' . $pString))  .'" >' . $graphics->icon('search') . '</a> </td>';				
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="20" class="no_record">' . $lang->get('com_no_record_found') . '</td></tr>';
		}
	?>
	</table>
	<div id="last_msg_loader"></div>
	<div class="FL PT5 PB5 PL5 text_11 text_black">
	<img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/arrow_ltr.png" width="38" height="22" alt="" />
	<a href="#" value="Lock" class="selectAllBoxesLink">Check All</a> / 
	<a href="#" value="Lock" class="unselectAllBoxesLink">Uncheck All</a>
	<span class="butSkin">
	<?php
		echo '';
		if($totalRows > 0 and ($type == 'pending' || $type == 'locked' || $type == 'avail' || $type == 'verify'))
		{
			echo '<input type="submit" name="process" value="' . $lang->get('lbl_process_imeis') . '" />';
			if($type == 'pending')
			{
				echo '<input type="submit" name="process_all" value="' . $lang->get('lbl_process_imeis_all') . '" />';
			}
			echo ' | ';
		}
		echo '<input type="submit" name="download" value="Download" /> <b>All/Selected IMEIs</b>';
	?>
		<label class="text_11"><?php $lang->prints('com_tool'); ?><input type="checkbox" value="1" id="copyTool" name="copyTool" class="copyTool CopyAllIMEIs" /></label> |
		<label class="text_11"><?php $lang->prints('com_alias'); ?><input type="checkbox" value="1" id="copyAlias" name="copyAlias" class="copyAlias CopyAllIMEIs" /></label> |
		<label class="text_11"><?php $lang->prints('com_username'); ?><input type="checkbox" value="1" id="copyUsername" name="copyUsername" class="copyUsername CopyAllIMEIs" /></label> |
		<label class="text_11">.csv<input type="checkbox" value="1" id="file_ext" name="file_ext" /></label>
	</span>
	</div>
	<div class="toolbarSkin text_right float_right PT10">
		<span class="text_11 PT5">Item/Page &raquo;</span>
					<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?limit=100<?php echo  (($pStringLimit)?('&' . $pStringLimit) :'') ; ?>">100</a><a 
						href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?limit=200<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>">200</a><a 
						href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?limit=500<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>">500</a><a 
						href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?limit=1000<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>">1000</a><a 
						href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?no_paging=1<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>">All</a>
	</div>
	</form>

	<div class="TA_C navigation"><?php if($imei == '') { echo $pCode; } ?></div>
	<?php ob_flush(); ?>