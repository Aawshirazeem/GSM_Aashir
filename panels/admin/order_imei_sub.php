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

<h1><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_jobs')); ?> </h1>

		  <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html" method="get" name="frm_customer_edit_login" id="frm_customer_edit_login" class="formSkin hidden">
			<fieldset>
			<legend></legend>
				<p class="form-group">
					<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei')); ?> </label>
					<textarea name="imei" class="form-control" id="imei" rows="5"><?php echo $imei;?></textarea>
					<input type="hidden" name="type" value="<?php echo $type;?>">
					<input type="hidden" name="supplier_id" value="<?php echo $supplier_id;?>">
					<input type="hidden" name="ip" value="<?php echo $ip;?>">
					<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
					<input type="hidden" name="limit" value="<?php echo $limit;?>">
				</p>
				<p class="form-group">
					<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unlocking_tool')); ?> </label>
					<select name="search_tool_id" class="form-control combo">
						<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_tools')); ?> </option>
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
				<p class="butSkin" style="text-align:center">
					<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?>" />
				</p>
			</fieldset>
		  </form>
	
	<?php
		if(trim($imei) != '' || $search_tool_id != 0 || $search_user_id != 0 || $search_supplier_id != 0)
		{
			$msg=$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_click_here_to_clear_filter'));
			echo $graphics->messageBox('<a href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei.html'. (($type!='' ) ? ('?type=' . $type) : '')  . '">'. $msg.'</a>');
		}
	?>
	<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei_<?php echo ($type != '') ? $type : 'all'; ?>_process.do" enctype="multipart/form-data" method="post" name="frm_imeis" id="frm_imeis" class="formSkin">
	
		<input type="hidden" name="imei" value="<?php echo $imei;?>">
		<input type="hidden" name="type" value="<?php echo $type;?>">
		<input type="hidden" name="ip" value="<?php echo $ip;?>">
		<input type="hidden" name="search_user_id" value="<?php echo $search_user_id;?>">
		<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
	
	<?php
		ob_flush();
		ob_start(); ?>
	
	<div class="clear"></div>
	<table class="MT5 details text_12 ui-corner-all ui-widget-content extraImeis">
	<tr>
		<th width="16"></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),'Unlocking Tool'); ?></th>
		<th></th>
	</tr>
	<?php
		
		$qType = ' where im.status=1 ';
		if(trim($txtImeis) != '')
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.imei in (' . $txtImeis . ') ';
		}
		if($search_tool_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.tool_id = ' . $search_tool_id;
		}
		
		
		$strUserFields = $strUserTbl = '';
		if($hide_user == 1)
		{
			//echo "hideUsers";
		}
		
		$sql = 'select im.*, im.id as imeiID,
					im.api_name, im.message,
					DATE_FORMAT(im.date_time, "%d-%b-%Y %k:%i") as dtDateTime,
					DATE_FORMAT(im.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime
					from ' . ORDER_IMEI_MASTER . ' im
					
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
				echo '<tr id="tr' . $row['id'] . '">';
				
				echo '<input type="hidden" name="Ids[]" value=' . $row['id'] . '>';  // to send ids of users to processing page
					echo '<td>' . $i . '</td>';
					echo '<td><h2>' . $row['imei'] . '</h2></td>';
					echo '<td style="position:relative">';
							if (defined("DEMO"))
							{
								echo '*****Demo*****' ;
							}
							else
							{
									echo '<textarea name="unlock_code_' . $row['id'] . '" id="unlock_code_' . $row['id'] . '" class="form-control txtCode" rows="6" cols="60">' . base64_decode($row['reply']) . '</textarea>';
									echo '<textarea name="unlock_code_' . $row['id'] . '_2" class="textbox_small" style="width:100px;display:none">' . base64_decode($row['reply']) . '</textarea>';
							}
					echo '</td>';
					echo '<td class="TA_C">';
						echo '<input type="checkbox" name="unavailable_' . $row['id'] . '" id="check' . $row['id'] . '" class="subSelectUnavail toggleOnCheck"><br />';
						echo '<input type="text" name="un_remarks_' . $row['id'] . '" id="check' . $row['id'] . 'Hide" value="" class="textbox_small unTextBoxFill hidden">';
						echo '</td>';
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="20" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
		}
	?>
	</table>
	<div id="last_msg_loader"></div>
	<div class="FL PT5 PB5 PL5 text_11 text_black">
	<img src="<?php echo CONFIG_PATH_IMAGES; ?>skin/arrow_ltr.png" width="38" height="22" alt="" />
	<a href="#" value="Lock" class="selectAllBoxesLink"><?php echo $admin->wordTrans($admin->getUserLang(),'Check All'); ?></a> / 
	<a href="#" value="Lock" class="unselectAllBoxesLink"><?php echo $admin->wordTrans($admin->getUserLang(),'Uncheck All'); ?></a>
	<span class="butSkin">
	<?php
		echo '';
		if($totalRows > 0 and ($type == 'pending' || $type == 'locked' || $type == 'avail' || $type == 'verify'))
		{
			echo '<input type="submit" name="process" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_process_imeis')) . '" />';
			if($type == 'pending')
			{
				echo '<input type="submit" name="process_all" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_process_imeis_all')) . '" />';
			}
			echo ' | ';
		}
		echo '<input type="submit" name="download" value="Download" /> <b>All/Selected IMEIs</b>';
	?>
		<label class="text_11"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_tool')); ?><input type="checkbox" value="1" id="copyTool" name="copyTool" class="copyTool CopyAllIMEIs" /></label> |
		<label class="text_11"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_alias')); ?><input type="checkbox" value="1" id="copyAlias" name="copyAlias" class="copyAlias CopyAllIMEIs" /></label> |
		<label class="text_11"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?><input type="checkbox" value="1" id="copyUsername" name="copyUsername" class="copyUsername CopyAllIMEIs" /></label> |
		<label class="text_11">.csv<input type="checkbox" value="1" id="file_ext" name="file_ext" /></label>
	</span>
	</div>
	<div class="toolbarSkin text_right float_right PT10">
		<span class="text_11 PT5"><?php echo $admin->wordTrans($admin->getUserLang(),'Item/Page'); ?> &raquo;</span>
					<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?limit=100<?php echo  (($pStringLimit)?('&' . $pStringLimit) :'') ; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),'100'); ?></a><a 
						href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?limit=200<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),'200'); ?></a><a 
						href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?limit=500<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),'500'); ?></a><a 
						href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?limit=1000<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),'1000'); ?></a><a 
						href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?no_paging=1<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),'All'); ?></a>
	</div>
	</form>

	<div class="TA_C navigation"><?php if($imei == '') { echo $pCode; } ?></div>
	<?php ob_flush(); ?>