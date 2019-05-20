<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	$limit = $request->getInt('limit');
	$supplier_id = $request->GetInt('supplier_id');
	$no_paging = $request->GetInt('no_paging');
	
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
	
	if(isset($_POST['search_tool_id']))
		$search_tool_id = $request->PostInt('search_tool_id');
	else
		$search_tool_id = $request->GetInt('search_tool_id');
		
	if(isset($_POST['search_user_id']))
		$search_user_id = $request->PostInt('search_user_id');
	else
		$search_user_id = $request->GetInt('search_user_id');
		
	if(isset($_POST['search_supplier_id']))
		$search_supplier_id = $request->PostInt('search_supplier_id');
	else
		$search_supplier_id = $request->GetInt('search_supplier_id');

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

	
	$delimiter = $request->PostStr('delimiter');
	
	if($imei_code != '')
	{
		if($delimiter == '')
		{
			$delimiter = ' ';
		}
		//split IMEI in new line
		$imeis = explode("&#13;&#10;", $imei_code);
		//$imeis = explode("\n", $imei_code);
		$txtImeis = "";
		foreach($imeis as $im)
		{
			$tempIMEI = strstr($im, $delimiter, true);
			if(is_numeric($tempIMEI))
			{
				$txtImeis .= $tempIMEI . ',';
				$imeiCodes[$tempIMEI] = trim(strstr($im, $delimiter), $delimiter);
			}
		}
		$txtImeis = rtrim($txtImeis,',');
	}
	
	$sqlCount = 'select status, count(id) as total from ' . ORDER_IMEI_MASTER . ' where status in (0, 1) group by status';
	$queryCount = $mysql->query($sqlCount);
	if($mysql->rowCount($queryCount) > 0)
	{
		$rows = $mysql->fetchArray($queryCount);
		foreach($rows as $row)
		{
			$imeiCount[$row['status']] = $row['total'];
		}
	}
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
	
	$verifyCount = ($rowCount['verificationIMEI'] > 0) ? (' <span class="badge">' . $rowCount['verificationIMEI'] . '</span>') : '';
	

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
<div class="row hidden" id="loadingPanel">
	<div class="col-md-8 col-md-offset-2" style="margin-top:15%;margin-bottom:15%;">
	
		<!-- Progress Bar -->
		<h1 class="text-center" id="h1Wait"><i class="icon-refresh icon-large icon-spin"></i><?php echo $admin->wordTrans($admin->getUserLang(),'Please wait...'); ?></h1>
		<div class="progress progress-striped active hiddenf" id="pBarSubmit">	
			<div class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
				<span class="sr-only">0% <?php echo $admin->wordTrans($admin->getUserLang(),'Complete'); ?></span>
			</div>
		</div>
		<!-- / Progress Bar -->	
		
		
		<!-- Message after successfull submission -->
		<h1 class="text-center hidden" id="h1Done"><i class="icon-ok icon-large text-success"></i> <?php echo $admin->wordTrans($admin->getUserLang(),'Done'); ?></h1>
		<div class="text-center hidden" id="panelButtons">
			<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei_pending_final.html" method="post" name="frmDownload" id="frmDownload">
				<div id="tempDownloadIMEIS"></div>
				<a href="<?php echo CONFIG_PATH_SITE_ADMIN?>order_imei.html?type=<?php echo $type?>" class="btn btn-default"><i class="icon-arrow-left"></i> <?php echo $admin->wordTrans($admin->getUserLang(),'Go Back'); ?></a>
				<button type="submit" name="process" class="btn btn-primary"><i class="icon-download-alt"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_download')); ?></button>
			</form>
		</div>
		<!-- / Message after successfull submission -->
		
		
		<!-- Error Message -->
		<div class="alert alert-danger hidden" id="h1Error"><i class="icon-remove icon-large"></i> <span id="h1ErrorText"><?php echo $admin->wordTrans($admin->getUserLang(),'There is some unexpected error!'); ?></span></div>
		<div class="text-center hidden" id="panelButtonsCredits">
			<a href="<?php echo CONFIG_PATH_SITE_ADMIN?>order_imei.html?type=<?php echo $type?>" class="btn btn-danger"><i class="icon-arrow-left"></i><?php echo $admin->wordTrans($admin->getUserLang(),' There is some error! Go Back'); ?></a>
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
				<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei.html" method="post">
					<fieldset>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_unlocking_tool')); ?> </label>
							<select name="search_tool_id" class="form-control chosen-select">
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
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei')); ?> </label>
									<textarea name="imei" class="form-control" id="imei" rows="9"><?php echo $imei;?></textarea>
									<input type="hidden" name="type" value="<?php echo $type;?>">
									<input type="hidden" name="supplier_id" value="<?php echo $supplier_id;?>">
									<input type="hidden" name="ip" value="<?php echo $ip;?>">
									<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
									<input type="hidden" name="limit" value="<?php echo $limit;?>">
								</div>
								<div class="col-md-6">
									<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_imei_with_code')); ?> </label>
									<textarea name="imei_code" class="form-control" id="imei_code" rows="6"><?php echo $imei_code;?></textarea>
									<br />
									<div class="form-group">
										<select name="delimiter" class="form-control">
											<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),'[Space]'); ?></option>
											<option value=":">:</option>
											<option value=";">;</option>
											<option value=",">,</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="form-group col-md-6">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?> </label>
								<select name="search_user_id" class="form-control">
									<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_users')); ?> </option>
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
							</div>
							<div class="form-group col-md-6">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_supplier')); ?> </label>
								<select name="search_supplier_id" class="form-control">
									<option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_suppliers')); ?> </option>
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
							</div>
						</div>
						<input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?>" class="btn btn-success" />
					</fieldset>
				  </form>
			  </div>
		  </div>
	  </div>
	</div>


	

	<div class="clear"></div>
	
	<div class="row" id="btn-group-top">
		<div class="col-sm-10">
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
						<i class="icon-asterisk icon-large"></i> ' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_pending')) . ' ' . (isset($imeiCount[0]) ? ' <span class="badge">' . $imeiCount[0] : '') . '</span>
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
								echo '<li><a href="order_imei.html?type=pending&search_tool_id=' . $row['id'] . '"><span class="badge bg-danger">' . $row['total'] . '</span>' . $row['tool_name'] . '</a></li>';
							}
						}
					echo '</ul>';
				?>
				</div>
				
				<div class="btn-group extra">
				<?php
					echo '
					<button type="button" class="btn ' . (($type== 'locked') ? 'btn-primary dropdown-toggle' : 'btn-default dropdown-toggle') . '" data-toggle="dropdown">
						' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_locked')) . ' ' . (isset($imeiCount[1]) ? ' <span class="badge">' . $imeiCount[1] : '') . '</span>
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">';
						echo '<li><a href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei.html?type=locked">' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_all_pending_orders')) . '</a></li>';						
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
								echo '<li><a href="order_imei.html?type=locked&search_tool_id=' . $row['id'] . '"><span class="badge bg-danger">' . $row['total'] . '</span>' . $row['tool_name'] . '</a></li>';
							}
						}
					echo '</ul>';
				?>
				</div>
				<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?type=avail<?php echo (($pString!='') ? ('&' . $pString ) : '');?>" class="btn <?php echo ($type== 'avail') ? 'btn-primary' : 'btn-default'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_available')); ?> </a>
				<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?type=rejected<?php echo (($pString!='') ? ('&' . $pString ) : '');?>" class="btn <?php echo ($type== 'rejected') ? 'btn-primary' : 'btn-default'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_rejected')); ?> </a>
				<?php
						if($rowCount['verificationIMEI'] > 0)
						{
							echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei.html?type=verify' . (($supplier_id != '') ? ('&supplier_id=' . $supplier_id) : '') . '" class="btn ' . (($type== 'verify') ? 'btn-primary' : 'btn-default') . '">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_verification')). ' ' . $verifyCount . '</a>';
						}
					?>
				<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html<?php echo ($pString != '') ? ('?' . $pString) : ''?>" class="btn <?php echo ($type== '') ? 'btn-primary' : 'btn-default'; ?>"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_all_orders')); ?> </a>
				<a href="#searchPanel" data-toggle="modal" class="btn btn-warning"><i class="icon-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?> </a>
			</div>
		</div>
		<div class="col-sm-2 text-right">
			
				<?php
					if($type == 'pending' || $type == 'locked') // || $type == 'avail' || $type == 'verify')
					{
						echo '
							<form action="" enctype="multipart/form-data" method="post" name="frmAjaxOrderSub" id="frmAjaxOrderExtra">
								<div id="tempFields"></div>
								<input type="submit" name="process" class="btn btn-success" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_process_imeis')) . '" />
							</form>';
					}
				?>
			
		</div>
	</div>

	
	<div class="clear"></div>
	
	<?php
		if(trim($imei) != '' || $search_tool_id != 0 || $search_user_id != 0 || $search_supplier_id != 0)
		{
			echo '<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<a href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei.html'. (($type!='' ) ? ('?type=' . $type) : '')  . '">'.$lang->get('lbl_click_here_to_clear_filter') .'</a>
				</div>';
		}
	?>

<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>order_imei_<?php echo ($type != '') ? $type : 'all'; ?>_process.do" enctype="multipart/form-data" method="post" name="frmAjaxOrder" id="frmAjaxOrder">
	
		<input type="hidden" name="imei" value="<?php echo $imei;?>">
		<input type="hidden" name="type" value="<?php echo $type;?>">
		<input type="hidden" name="reqeustType" value="<?php echo $type;?>">
		<input type="hidden" name="supplier_id" value="<?php echo $supplier_id;?>">
		<input type="hidden" name="search_tool_id" value="<?php echo $search_tool_id;?>">
		<input type="hidden" name="ip" value="<?php echo $ip;?>">
		<input type="hidden" name="search_user_id" value="<?php echo $search_user_id;?>">
		<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
		<input type="hidden" name="limit" value="<?php echo $limit;?>">
	
	<?php
		ob_flush();
		ob_start(); ?>
	
	<div class="clearfix"></div>
	<section class="MT10 panel">
		<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI Jobs'); ?></div>	
	
	<table class="table table-striped table-hover">
	<tr>
		<?php echo ($type != 'locked') ? '<th width="16"></th>' : ''; ?>
		<th width="16"></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_imei')); ?></th>
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_tool')); ?></th>
      	<?php echo ($type == 'verify') ? '<th width="60"><label>Veify<input type="checkbox" value="" id="Verify" class="selectAllBoxes" /></label></th>' : '';?>
		<?php
			if($type != 'pending')
			{
				echo '<th>
							<a href="#" class="toggle" id="code">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_unlock_code')) . ' <i class="icon-folder-open"></i></a><br />
							<input type="text" name="" id="codeBox" class="form-control hidden autoFillText" />
						</th>';
			}
			if($type == 'locked' or $type == 'verify')
			{
				echo '
					<th width="100">
						<a href="#" class="toggle" id="unText">Unavail <i class="icon-folder-open"></i></a>
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
		<th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_credits')); ?> </th>
		<?php echo ($type == "avail") ? '<th style="text-align:center">Return</th>' : ''; ?>
		<?php echo ($supplier_id != 0 and $type == 'avail') ? '<th style="text-align:center"><label><input type="checkbox" value="" id="Pay" class="selectAllBoxes" />' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_pay')) . '</label></th>' : ''; ?>
		
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
					tm.api_id,
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
					

		$query = $mysql->query($sql . (($no_paging == '0') ? $qLimit : ''));
		$strReturn = "";
		
		$pCode = '';
		if($no_paging == '0')
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
				
				echo '<input type="hidden" name=Ids[]" value=' . $row['id'] . '>';  // to send ids of users to processing page

				if($type != 'locked')
				{
					echo '<td>';
					echo '<input type="checkbox" class="subSelectLock" name="locked[]" value="' . $row['id'] . '">';
					echo ($row['api_id'] != 0) ? '<span class="label label-danger">API</span>' : '';
					echo '</td>';
				}
				echo '<td class="text_center">
						' . $i . '<br />
						<small>im-' . $row['id'] . '</small><br />
					</td>';
			    echo '<td>';
							echo '<h2 style="margin:0px; padding:0px;">' . $row['imei'] . '</h2>';
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
							echo (($row['extern_id'] != '0') ? '<br /><small style="color:#DD0000">' . $row['extern_id'] . '</small>' : '');
				echo '</td>';
			    echo '<td>';
							echo $mysql->prints($row['tool_name']) . (($row['supplier'] != '') ? ('<br><small><b>' . $mysql->prints($row['supplier']) . '</b></small>') : '') . '<br />';
							echo ($row['username'] != '') ? ('<a href="users_edit.html?id=' . $row['user_id'] . '" class="various" data-fancybox-type="iframe">' . $mysql->prints($row['username']) . '</a>') : '';
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
								echo ($row['message'] != '') ? '<div class="dim" style="background-color:#FFEEEE; padding:5px; margin:5px;"><b>' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_admin_note')) . '</b>: ' . $row['message'] . '</div>' : '';
								echo ($row['remarks'] != '') ? '<div class="dim" style="background-color:#EEFFEE; padding:5px; margin:5px;"><b>' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_customer_note')) . '</b>: ' . $row['remarks'] . '</div>' : '';
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
					echo ($row['dtDateTime'] != '') ? ('<small>' . $row['dtDateTime'] . '</small><br />') : '';
					echo ($row['dtReplyDateTime'] != '') ? ('<small><b>' . $row['dtReplyDateTime'] . '</b></small>') : '';
					echo '<br />' . $mysql->prints($row['ip']);
					echo ($row['supplier'] != '') ? ('<br /><span class="label label-info">' . $mysql->prints($row['supplier']) . '</span>') : '';
				echo '</td>';
				echo '<td><b>' . $row['credits'] . '</b>';
				if($row['credits_purchase'] != 0)
				{
					echo ' - <small>' . $row['credits_purchase'] . '</small>';
				}
				echo '</td>';
				echo ($type == "avail") ? '<td class="text_center"><input type="checkbox" name="return_' . $row['id'] . '" class="subSelectReturn"><br /><input type="text" name="return_remarks_' . $row['id'] . '" value="" class="form-control"></td>' : '';
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
				echo '<td class="TA_C"><a class="various" data-fancybox-type="iframe" href="order_imei_detail.html?id=' . urlencode($row['id']) . (($type!='' ) ? ('&type=' . $type . '&' . $pString) : ( '&' . $pString))  .'" ><i class="icon-file-text"></i></a> </td>';				
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr><td colspan="20" class="no_record">';
			$msg=$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found'));
			echo $graphics->messagebox_warning($msg);
			echo '</td></tr>';
		}
		if($totalRows > 0 and ($type == 'avail' || $type == 'verify'))
		{
			echo '
				<tr><td colspan="20">
					<input type="submit" name="process" value="' . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_process_imeis')) . '" class="btn btn-danger" />
					</td></tr>';
			if($type == 'pending')
			{
				//echo ' <input type="submit" name="process_all" value="' . $lang->get('lbl_process_imeis_all') . '" class="btn btn-danger" />';
			}
			//echo ' <input type="submit" name="download" value="Download" class="btn btn-danger" />';
		}
	?>
	</table>
	</section>
	<div id="last_msg_loader"></div>
	<div class="btn-group pull-right">
		<span class="text_11 PT2 pull-left"><?php echo $admin->wordTrans($admin->getUserLang(),'Item/Page '); ?>&raquo;</span>
		<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?limit=100<?php echo  (($pStringLimit)?('&' . $pStringLimit) :'') ; ?>" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),'100'); ?></a>
		<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?limit=200<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),'200'); ?></a>
		<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?limit=500<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),'500'); ?></a>
		<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?limit=1000<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),'1000'); ?></a>
		<a href="<?php echo CONFIG_PATH_SITE_ADMIN;?>order_imei.html?no_paging=1<?php echo (($pStringLimit)?('&' . $pStringLimit):'') ; ?>" class="btn btn-default btn-xs"><?php echo $admin->wordTrans($admin->getUserLang(),'All'); ?></a>
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