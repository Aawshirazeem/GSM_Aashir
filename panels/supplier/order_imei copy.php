<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$limit = $request->getInt('limit');	

	$no_paging = $request->GetInt('no_paging');
	if(isset($_POST['supplier_id']))
		$supplier_id = $request->PostStr('supplier_id');
	else
		$supplier_id = $request->GetStr('supplier_id');

	if(isset($_POST['imei_prefix']))
		$imei_prefix = $request->PostCheck('imei_prefix');
	else
		$imei_prefix = $request->GetCheck('imei_prefix');

	if(isset($_POST['type']))
		$type = $request->PostStr('type');
	else
		$type = $request->GetStr('type');
	
	if(isset($_POST['imei']))
		$imei = $request->PostStr('imei');
	else
		$imei = $request->GetStr('imei');
	
	if(isset($_POST['imei_code']))
	{
		$imei_code = $request->PostStr('imei_code');
		$no_paging = 2;
	}
	else
	{
		$imei_code = $request->GetStr('imei_code');
	}

	$search_tool_id = $request->GetInt('search_tool_id');
	$ip=$request->GetStr('ip');
	$user_id=$request->GetInt('user_id');
	
	$hide_user = $request->GetInt('hide_user');
	
	//split IMEI in new line
	if($imei_code != '')
	{
		$imei = '';
	}
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
	
	if($imei_code != '')
	{
		//split IMEI in new line
		$imeis = explode("&#13;&#10;", $imei_code);
		$txtImeis = "";
		foreach($imeis as $im)
		{
			if($imei_prefix == 1)
			{
				$imItems = explode(' ', $im);
				$chkIMEI = false;
				foreach($imItems as $item)
				{
					if(preg_match('/^\d{15}$/', $item) && $chkIMEI == false)
					{
						$im = substr($im, strpos($im, $item));
						$chkIMEI = true;
					}
				}
			}
			$tempIMEI = strstr($im, ' ', true);
			if(is_numeric($tempIMEI))
			{
				$txtImeis .= $tempIMEI . ',';
				$imeiCodes[strstr($im, ' ', true)] = trim(strstr($im, ' '));
			}
		}
		$txtImeis = rtrim($txtImeis,',');
	}
	
	
	$showUser = $showCredits = 0;
	$sql = 'select * from ' . SUPPLIER_MASTER . ' where id=' . $supplier->getUserId();
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$showUser = $rows[0]['show_user'];
		$showCredits = $rows[0]['show_credits'];
	}
	
	$newTotal = 0;
	$sqlCount = 'select im.status, count(im.id) as total
					from ' . ORDER_IMEI_MASTER . ' im
					where im.status = 0
					group by im.status';
	$queryCount = $mysql->query($sqlCount);
	if($mysql->rowCount($queryCount) > 0)
	{
		$rows = $mysql->fetchArray($queryCount);
		foreach($rows as $row)
		{
			$newTotal = $row['total'];
		}
	}
	$pendingTotal = 0;
	$sqlCount = 'select im.status, count(im.id) as total
					from ' . ORDER_IMEI_MASTER . ' im
					where im.status = 1
					
					group by im.status';
	$queryCount = $mysql->query($sqlCount);
	if($mysql->rowCount($queryCount) > 0)
	{
		$rows = $mysql->fetchArray($queryCount);
		foreach($rows as $row)
		{
			$pendingTotal = $row['total'];
		}
	}
	
	$sqlCount = 'select count(id) as verificationIMEI
						from ' . ORDER_IMEI_MASTER . ' im
						where (im.status=2 and im.verify=1) ';
	
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
	$extraURL = '&type=' . $type . '&supplier_id=' . $supplier_id . '&search_tool_id=' . $search_tool_id . '&ip=' . $ip;

?>
<div class="row hidden" id="loadingPanel">
	<div class="col-md-8 col-md-offset-2" style="margin-top:15%;margin-bottom:15%;">
	
		<!-- Progress Bar -->
		<h1 class="text-center" id="h1Wait"><i class="icon-refresh icon-large icon-spin"></i> <?php echo $admin->wordTrans($admin->getUserLang(),'Please wait...'); ?></h1>
		<div class="progress progress-striped active hiddenf" id="pBarSubmit">	
			<div class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
				<span class="sr-only"><?php echo $admin->wordTrans($admin->getUserLang(),'0% Complete'); ?></span>
			</div>
		</div>
		<!-- / Progress Bar -->	
		
		
		<!-- Message after successfull submission -->
		<h1 class="text-center hidden" id="h1Done"><i class="icon-ok icon-large text-success"></i><?php echo $admin->wordTrans($admin->getUserLang(),'Done'); ?></h1>
		<div class="text-center hidden" id="panelButtons">
			<form action="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei_pending_final.html" method="post" name="frmDownload" id="frmDownload">
				<div id="tempDownloadIMEIS"></div>
				<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER?>order_imei.html?type=<?php echo $type?>" class="btn btn-default"><i class="icon-arrow-left"></i><?php echo $admin->wordTrans($admin->getUserLang(),'Go Back'); ?></a>
				<button type="submit" name="process" class="btn btn-primary"><i class="icon-download-alt"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_download')); ?></button>
			</form>
		</div>
		<!-- / Message after successfull submission -->
		
		
		<!-- Error Message -->
		<div class="alert alert-danger hidden" id="h1Error"><i class="icon-remove icon-large"></i> <span id="h1ErrorText"><?php echo $admin->wordTrans($admin->getUserLang(),'There is some unexpected error!'); ?></span></div>
		<div class="text-center hidden" id="panelButtonsCredits">
			<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER?>order_imei.html?type=<?php echo $type?>" class="btn btn-danger"><i class="icon-arrow-left"></i> <?php echo $admin->wordTrans($admin->getUserLang(),'There is some error! Go Back'); ?></a>
		</div>
		<!-- / Error Message -->
		
		
		
	</div>
</div>
	
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				  <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="icon-remove"></i></button>
				  <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?></h4>
				</div>
				<div class="modal-body">
		
		  <form action="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei.html" method="get">
			<fieldset>
					<input type="hidden" name="type" value="<?php echo $type;?>">
					<input type="hidden" name="supplier_id" value="<?php echo $supplier_id;?>">
					<input type="hidden" name="ip" value="<?php echo $ip;?>">
					<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
					<input type="hidden" name="limit" value="<?php echo $limit;?>">
				<div class="form-group">
					<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unlocking_tool')); ?> </label>
					<select name="search_tool_id" class="form-control">
						<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_tools')); ?> </option>
						<?php
							$sql_tool = 'select 
												itm.id as tool_id, itm.tool_name, itm.group_id, itm.credits, itm.delivery_time, itm.status,
												igm.group_name
										from ' . IMEI_TOOL_MASTER . ' itm
										left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
										where  itm.id in (select tool from ' . IMEI_SUPPLIER_DETAILS . ' where supplier_id=' . $supplier->getUserId() . ')
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
				</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei')); ?> </label>
								<textarea name="imei" class="form-control" id="imei" rows="6"><?php echo $imei;?></textarea>
								<input type="hidden" name="type" value="<?php echo $type;?>">
								<input type="hidden" name="supplier_id" value="<?php echo $supplier_id;?>">
								<input type="hidden" name="ip" value="<?php echo $ip;?>">
								<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
								<input type="hidden" name="limit" value="<?php echo $limit;?>">
							</div>
							<div class="form-group col-md-6">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_with_code')); ?></label>
								<textarea name="imei_code" class="form-control" id="imei_code" rows="6"><?php echo $imei_code;?></textarea>
								<label><input type="checkbox" name="imei_prefix" /> <?php echo $admin->wordTrans($admin->getUserLang(),'Remove everything before IMEI number'); ?></label>
							</div>
						</div>
				<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?>" class="btn btn-success" />
			</fieldset>
		  </form>
				</div>
			</div>
		</div>
	</div>





<div class="lock-to-top">
	<h1 class="pull-left"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_jobs')); ?> </h1>
	<div class="btn-group">
		<?php
			if($type == 'pending' || $type == 'locked') // || $type == 'avail' || $type == 'verify')
			{
				echo '
					<form action="' . CONFIG_PATH_SITE_SUPPLIER . 'order_imei_' . (($type != '') ? $type : 'all') . '_process_ajax.do" enctype="multipart/form-data" method="post" name="frmAjaxOrderSub" id="frmAjaxOrderExtra">
						<div id="tempFields"></div>
						<input type="submit" name="process" class="btn btn-success" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_process_imeis')) . '" />
					</form>';
			}
		?>
	</div>
</div>

<form action="<?php echo CONFIG_PATH_SITE_SUPPLIER; ?>order_imei_<?php echo ($type != '') ? $type : 'all'; ?>_process.do" enctype="multipart/form-data" method="post" name="frmAjaxOrder" id="frmAjaxOrder">
	<div class="clear"></div>
	
	<div class="btn-group extra <?php echo ($view == 'locked') ? 'hidden' : '';?>">
		<button type="button" value="Lock" class="selectAllBoxesLink btn btn-default"><i class="icon-check-empty"></i></button>
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a href="#" value="Lock" class="selectAllBoxesLink"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_check_all')); ?></a></li>
			<li><a href="#" value="Lock" class="unselectAllBoxesLink"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_uncheck_all')); ?></a>
		</ul>
	</div>
	<div class="btn-group extra">
		<div class="btn-group extra">
		<?php
			echo '
			<button type="button" class="btn ' . (($type== 'pending') ? 'btn-primary dropdown-toggle' : 'btn-default dropdown-toggle') . '" data-toggle="dropdown">
				<i class="icon-asterisk icon-large"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_pending')) . ' ' . (($newTotal != '0') ? ' <span class="badge">' . $newTotal : '') . '</span>
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">';
				echo '<li><a href="order_imei.html?type=pending">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_all_new_orders')) . '</a></li>';
				echo '<li role="presentation" class="divider"></li>';
				$sqlPending = 'select tm.id as id, count(im.id) as total,
							tm.tool_name as tool_name
							from ' . ORDER_IMEI_MASTER . ' im
							left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
							where im.status=0
							group by im.tool_id';
				$queryPending = $mysql->query($sqlPending);
				if($mysql->rowCount($queryPending) > 0)
				{
					$rows = $mysql->fetchArray($queryPending);
					foreach($rows as $row)
					{
						echo '<li><a href="order_imei.html?type=pending&search_tool_id=' . $row['id'] . '"><span class="badge bg-danger pull-right">' . $row['total'] . '</span>' . $row['tool_name'] . '</a></li>';
					}
				}
			echo '</ul>';
		?>
		</div>
		
		<div class="btn-group extra">
		<?php
			echo '
			<button type="button" class="btn ' . (($type== 'locked') ? 'btn-primary dropdown-toggle' : 'btn-default dropdown-toggle') . '" data-toggle="dropdown">
				' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_locked')) . ' ' . (($pendingTotal != '0') ? ' <span class="badge">' . $pendingTotal : '') . '</span>
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">';
				echo '<li><a href="' . CONFIG_PATH_SITE_SUPPLIER . 'order_imei.html?type=locked">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_all_pending_orders')) . '</a></li>';						
				echo '<li role="presentation" class="divider"></li>';
				$sqlPending = 'select tm.id as id, count(im.id) as total,
							tm.tool_name as tool_name
							from ' . ORDER_IMEI_MASTER . ' im
							left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
							where im.status=1
							group by im.tool_id';
				$queryPending = $mysql->query($sqlPending);
				if($mysql->rowCount($queryPending) > 0)
				{
					$rows = $mysql->fetchArray($queryPending);
					foreach($rows as $row)
					{
						echo '<li><a href="order_imei.html?type=locked&search_tool_id=' . $row['id'] . '"><span class="badge bg-danger pull-right">' . $row['total'] . '</span>' . $row['tool_name'] . '</a></li>';
					}
				}
			echo '</ul>';
		?>
		</div>
		<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_imei.html?type=avail<?php echo (($pString!='') ? ('&' . $pString ) : '');?>" class="btn <?php echo ($type== 'avail') ? 'btn-primary' : 'btn-default'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_available')); ?> </a>
		<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_imei.html?type=rejected<?php echo (($pString!='') ? ('&' . $pString ) : '');?>" class="btn <?php echo ($type== 'rejected') ? 'btn-primary' : 'btn-default'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_rejected')); ?> </a>
		<?php
				if($rowCount['verificationIMEI'] > 0)
				{
					echo '<a href="' . CONFIG_PATH_SITE_SUPPLIER . 'order_imei.html?type=verify' . (($supplier_id != '') ? ('&supplier_id=' . $supplier_id) : '') . '" class="btn ' . (($type== 'verify') ? 'btn-primary' : 'btn-default') . '">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_verification')) . ' ' . $verifyCount . '</a>';
				}
			?>
		<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_imei.html<?php echo ($pString != '') ? ('?' . $pString) : ''?>" class="btn <?php echo ($type== '') ? 'btn-primary' : 'btn-default'; ?>"><?php $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_orders')); ?> </a>
		<a href="#searchPanel" data-toggle="modal" class="btn btn-warning"><i class="icon-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?> </a>
	</div>
	
	<div class="clear"></div>
	
	<?php
		if(trim($imei) != '' || $search_tool_id != 0)
		{
			echo '<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<a href="' . CONFIG_PATH_SITE_SUPPLIER . 'order_imei.html'. (($type!='' ) ? ('?type=' . $type) : '')  . '">'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_click_here_to_clear_filter')) .'</a>
				</div>';
		}
	?>

	
	
		<input type="hidden" name="imei" value="<?php echo $imei;?>">
		<input type="hidden" name="type" value="<?php echo $type;?>">
		<input type="hidden" name="reqeustType" value="<?php echo $type;?>">
		<input type="hidden" name="search_tool_id" value="<?php echo $search_tool_id;?>">
		<input type="hidden" name="ip" value="<?php echo $ip;?>">
		<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
		<input type="hidden" name="limit" value="<?php echo $limit;?>">
	
	<?php
		ob_flush();
		ob_start(); ?>
	
	<div class="CL"></div>
	
	<table class="MT5 table table-striped table-hover panel">
	<tr>
		<?php echo ($type != 'locked') ? '<th width="16"></th>' : ''; ?>
		<th width="16"></th>
		<th width="16"></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_tool')); ?></th>
      	<?php echo ($type == 'verify') ? '<th width="60"><label>Veify<input type="checkbox" value="" id="Verify" class="selectAllBoxes" /></label></th>' : '';?>
		<?php
			if($type != 'pending')
			{
				echo '<th>
							<a href="#" class="toggle" id="code">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_unlock_code')) . ' <i class="icon-info-sign"></i></a><br />
							<input type="text" name="" id="codeBox" class="form-control hidden autoFillText" />
						</th>';
			}
			if($type == 'locked' or $type == 'verify')
			{
				echo '
					<th width="100">
						<a href="#" class="toggle" id="unText">Unavail <i class="icon-info-sign"></i></a>
						<input type="text" name="" value="" id="unTextBox" class="form-control hidden autoFillText">
						<br />
						<span class="text_11 text_black">
							<a href="#" value="Unavail" class="selectAllBoxesLink">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_check_all')) . '</a> / 
							<a href="#" value="Unavail" class="unselectAllBoxesLink">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_uncheck_all')) . '</a>
						</span>
					</th>';
			}
		?>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_date_time')); ?> </th>
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
				$qType .= ' and im.supplier_id=' . $supplier->getUserId();
				break;
			case 'avail':
				$qType = ' im.status=2 ';
				$qType .= ' and im.supplier_id=' . $supplier->getUserId();
				break;
			case 'rejected':
				$qType = ' im.status=3 ';
				$qType .= ' and im.supplier_id=' . $supplier->getUserId();
				break;
			case 'verify':
				$qType = ' im.status=2 and im.verify=1 ';
				$qType .= ' and im.supplier_id=' . $supplier->getUserId();
				break;
			default:
				$qType = (($qType != '') ? ' and ' : '') . ' im.supplier_id=' . $supplier->getUserId();
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
		if($ip != '')
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.ip = ' . $mysql->quote($ip);
		}
		if($user_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' um.id = ' . $mysql->getInt($user_id) ;
		}
		
		$qType = ($qType == '') ? '' : ' and ' . $qType;
		
		$strUserFields = $strUserTbl = '';
		
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
					where 1
					' . $qType . '
					order by im.id DESC';
		$query = $mysql->query($sql . (($no_paging == '0') ? $qLimit : ''));
		$strReturn = "";
		
		$pCode = '';
		if($no_paging == '0')
		{
			$pCode = $paging->recordsetNav($sql,CONFIG_PATH_SITE_SUPPLIER . 'order_imei.html',$offset,$limit,$extraURL);
		}
		
		$i = $offset;
		$totalRows = $mysql->rowCount($query);
		
		if($totalRows > 0)
		{
			$subtotal = $grandTotal = 0;

			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				$i++;
				echo '<tr>';
				
				//echo '<input type="hidden" name=Ids[]" value=' . $row['id'] . '>';  // to send ids of users to processing page

				//if($type != 'locked')
				{
					echo '<td>';
					echo '<input type="checkbox" class="subSelectLock" name="locked[]" value="' . $row['id'] . '">';
					echo ($row['api_id'] != 0) ? '<span class="label label-danger">API</span>' : '';
					echo '</td>';
				}
				echo '<td class="text_center">
						' . $i . '<br />
						<small>im-' . $row['id'] . '</small><br />';
							switch($row['status'])
							{
								case 0:
									echo '<span class="label label-default">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_pending')). '</span>';
									break;
								case 1:
									echo '<span class="label label-primary">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_locked')). '</span>';
									break;
								case 2:
									echo '<span class="label label-success">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_available')). '</span>';
									break;
								case 3:
									echo '<span class="label label-danger">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_unavailable')). '</span>';
									break;
							}
							echo (($row['extern_id'] != '0') ? '<br /><small style="color:#DD0000">' . $row['extern_id'] . ': ' . $row['api_name'] . '</small>' : '');
				echo '</td>';
			    echo '<td>';
							echo '<h2 style="margin:0px; padding:0px;">' . $row['imei'] . '</h2>';
							echo ($showUser == 1) ? '<span class="text-warning">' . $row['username'] . '</span>' : '';
				echo '</td>';
				
			    echo '<td>' . $row['tool_name'];
				echo ($showCredits == 1) ? '<br /><span class="label label-info">' . $row['credits'] . '</span>' : '';
				echo '</td>';
				if($type == "verify")
				{
					echo '<td>' . (($row['status'] == "2" && $row['verify'] == "1") ? '<input type="checkbox" class="subSelectVerify" name="verify_' . $row['id'] . '">' : '') . '</td>';
				}
				if($type != "pending")
				{
					echo '<td>
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
									$UnlockCode = '';
									if(isset($imeiCodes[$row['imei']]))
									{
										$UnlockCode = $imeiCodes[$row['imei']];
									}
									else
									{
										$UnlockCode = base64_decode($row['reply']);
									}
									echo '<input name="unlock_code_' . $row['id'] . '" id="unlock_code_' . $row['id'] . '" class="form-control codeBoxFill txtCode" style="display:inline" value="' . $UnlockCode . '" />';
									echo '<input name="unlock_code_' . $row['id'] . '_2" class="form-contol" style="width:100px;display:none" value="' . $UnlockCode . '" />';
								}
								else
								{
									echo ($row['reply'] != '') ? nl2br(base64_decode($row['reply'])) : '';
								}
							}
							echo '<br />';
							
					echo '&nbsp;</td>';
				}
				if($type == 'locked' or $type == "verify")
				{
					echo '<td>';
					echo  (($row['status'] == '1' or $type == 'verify') ? '<input type="checkbox" name="unavailable_' . $row['id'] . '" id="check' . $row['id'] . '" class="subSelectUnavail toggleOnCheck"> <input type="text" name="un_remarks_' . $row['id'] . '" id="check' . $row['id'] . 'Hide" value="" class="form-control unTextBoxFill hidden">' : '');
					echo '</td>';
				}
				echo '<td>';
					echo ($row['dtDateTime'] != '') ? ('<small>' . $row['dtDateTime'] . '</small>') : '';
					echo ($row['dtReplyDateTime'] != '') ? ('<br /><small><b>' . $row['dtReplyDateTime'] . '</b></small>') : '';
					if($type == 'avail')
					{
						$grandTotal += $row['credits_purchase'];
						echo '<br /><span class="label label-danger">' . $row['credits_purchase'] . '</span>';
					}
				echo '</td>';
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="20" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
		}
	?>
	<tr>
		<td colspan="20">
			<?php
				if($type == 'pending')
				{
					echo '<img src="' . CONFIG_PATH_IMAGES . 'skin/arrow_ltr.png" width="38" height="22" alt="" />';
					echo '<a href="#" value="Lock" class="selectAllBoxesLink">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_check_all')) . '</a> / ';
					echo '<a href="#" value="Lock" class="unselectAllBoxesLink">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_uncheck_all')) . '</a> ';
				}
				if($totalRows > 0 and ($type == 'pending' || $type == 'locked' || $type == 'avail' || $type == 'verify'))
				{
					//echo '<input type="submit" name="process" value="' . $lang->get('lbl_process_imeis') . '" class="btn btn-success" />';
					if($type == 'pending')
					{
						//echo ' <input type="submit" name="process_all" value="' . $lang->get('lbl_process_imeis_all') . '" class="btn btn-danger" />';
					}
				}
				//if($type != 'locked')
				{
					echo ' <input type="submit" name="download" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_download')) . '" class="btn btn-default" /> <b>' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_all_selected_imeis')) . '</b>';
					echo '<label class="checkbox-inline">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_tool')) . '<input type="checkbox" value="1" id="copyTool" name="copyTool" class="copyTool CopyAllIMEIs" /></label>';
					echo '<label class="checkbox-inline">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_alias')) . '<input type="checkbox" value="1" id="copyAlias" name="copyAlias" class="copyAlias CopyAllIMEIs" /></label>';
					echo '<label class="checkbox-inline">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_username')) . '<input type="checkbox" value="1" id="copyUsername" name="copyUsername" class="copyUsername CopyAllIMEIs" /></label>';
					echo '<label class="checkbox-inline">.csv<input type="checkbox" value="1" id="file_ext" name="file_ext" /></label>';
				}
				
				
			?>
		</td>
	</tr>
	</table>
	<?php
		if($type == 'avail')
		{
			echo '<h3>Total Profit:' . $grandTotal . '</h3>';
		}
	?>
	<div id="last_msg_loader"></div>
	<div class="btn-group pull-right">
		<span class="text_11 PT2 pull-left"><?php echo $admin->wordTrans($admin->getUserLang(),'Item/Page &raquo;'); ?></span>
		<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_imei.html?limit=100<?php echo  (($pStringLimit)?('&' . $pStringLimit) :'') ; ?>" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),'100'); ?></a>
		<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_imei.html?limit=200<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),'200'); ?></a>
		<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_imei.html?limit=500<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),'500'); ?></a>
		<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_imei.html?limit=1000<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),'1000'); ?></a>
		<a href="<?php echo CONFIG_PATH_SITE_SUPPLIER;?>order_imei.html?no_paging=1<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),'All'); ?></a>
	</div>

	<div class="TA_C navigation"><?php if($imei == '') { echo $pCode; } ?></div>
	<?php ob_flush(); ?>
	
	</form>
	

  
	<script>
		$(document).ready(function () 
		{
			imeiOrders();
		});
	</script>