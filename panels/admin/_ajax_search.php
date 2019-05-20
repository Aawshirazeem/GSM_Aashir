<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$search = $request->getStr('search');
	
	$sql='select
				um.id, um.username, um.credits, um.status,
				crm.prefix, crm.suffix
				from ' . USER_MASTER . ' um
				left join ' . CURRENCY_MASTER .' crm on (um.currency_id = crm.id)
				where um.username like ' . $mysql->quoteLike($search) . ' limit 3';
	$users=$mysql->getResult($sql);
	if($users['COUNT'])
	{
		echo '<li role="presentation" class="dropdown-header">Users</li>';
		foreach($users['RESULT']  as $user){
			echo '<li>
						<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_edit.html?id=' . $user['id'] . '">
								' . $graphics->status($user['status']) . ' ' . $user['username'] . '
								<span class="badge bg-inverse pull-right">' . $objCredits->printCredits($user['credits'], $user['prefix'], $user['suffix']) . '</span>
							</a>
					</li>';
		}
	}


	$sql = 'select im.*, im.id as imeiID,
				tm.api_id,
				DATE_FORMAT(im.date_time, "%d-%b-%Y %k:%i") as dtDateTime,
				DATE_FORMAT(im.date_time, "%h:%i %p") as timeSubmit,
				DATE_FORMAT(im.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,
				TIMESTAMPDIFF(SECOND, im.date_time, now()) as timediff,
				um.username as username,
				um.email as email,
				tm.tool_name as tool_name, 
				tm.tool_alias as tool_alias, 
				sm.username as supplier,
				DATE_FORMAT(im.supplier_paid_on, "%d-%b-%Y %k:%i") as dtSupplier,
				cm.prefix, cm.suffix
				from ' . ORDER_IMEI_MASTER . ' im
				left join ' . USER_MASTER . ' um on(im.user_id = um.id)
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = um.currency_id)
				left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
				left join ' . SUPPLIER_MASTER . ' sm on(im.supplier_id = sm.id)
				where imei like ' . $mysql->quoteLike($search) . '
				order by im.id DESC
				limit 5';

	$imeis=$mysql->getResult($sql);
	if($imeis['COUNT'])
	{
		echo '<li role="presentation" class="dropdown-header">IMEI Orders</li>';
		foreach($imeis['RESULT']  as $imei){
			echo '<li>
						<a href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei.html?imei=' . $imei['imei'] . '">
								' . $graphics->status($imei['status']) . ' ' . $imei['imei'] . '
								<span class="badge bg-inverse pull-right">' . $objCredits->printCredits($imei['credits'], $imei['prefix'], $imei['suffix']) . '</span>
								<br />
								<small>' . $imei['username'] . '</small>
								<small class="pull-right">' . $imei['dtDateTime'] . '</small>
							</a>
					</li>';
		}
	}


	$sql = 'select im.*, im.id as imeiID,
				tm.api_id,
				DATE_FORMAT(im.date_time, "%d-%b-%Y %k:%i") as dtDateTime,
				DATE_FORMAT(im.date_time, "%h:%i %p") as timeSubmit,
				DATE_FORMAT(im.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,
				TIMESTAMPDIFF(SECOND, im.date_time, now()) as timediff,
				um.username as username,
				um.email as email,
				tm.tool_name as tool_name, 
				tm.tool_alias as tool_alias, 
				sm.username as supplier,
				DATE_FORMAT(im.supplier_paid_on, "%d-%b-%Y %k:%i") as dtSupplier,
				cm.prefix, cm.suffix
				from ' . ORDER_IMEI_MASTER . ' im
				left join ' . USER_MASTER . ' um on(im.user_id = um.id)
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = um.currency_id)
				left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
				left join ' . SUPPLIER_MASTER . ' sm on(im.supplier_id = sm.id)
				where imei like ' . $mysql->quoteLike($search) . '
				order by im.id DESC
				limit 5';

		$sql= 'select itm.*, ibm.brand as BrandName, icm.countries_name as CountryName,
						igm.id as gid, igm.group_name, igm.status as groupStatus,
						am.api_server, itad.amount,
						cm.prefix, cm.suffix
				from ' . IMEI_TOOL_MASTER . ' itm
				left join ' . IMEI_BRAND_MASTER . ' ibm on (itm.brand_id = ibm.id)
				left join ' . API_MASTER . ' am on (itm.api_id = am.id)
				left join ' . COUNTRY_MASTER . ' icm on (itm.country_id = icm.id)
				left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
				left join ' . CURRENCY_MASTER . ' cm on(cm.is_default = 1)
				left join ' . IMEI_TOOL_AMOUNT_DETAILS . ' itad on(itm.id = itad.tool_id and cm.id = itad.currency_id)
				left join ' . IMEI_TOOL_USERS . ' itu on(itu.tool_id=itm.id)
				where itm.tool_name like ' . $mysql->quoteLike($search) . '
				order by igm.sort_order, itm.sort_order, itm.tool_name
				limit 5';

	$services=$mysql->getResult($sql);
	if($services['COUNT'])
	{
		echo '<li role="presentation" class="dropdown-header">IMEI Serivce</li>';
		foreach($services['RESULT']  as $service){
			echo '<li>
						<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_tools_edit.html?id=' . $service['id'] . '">
								' . $graphics->status($service['status']) . ' ' . $service['tool_name'] . '
								<span class="badge bg-success pull-right">' . $objCredits->printCredits($service['amount'], $service['prefix'], $service['suffix']) . '</span>
							</a>
					</li>';
		}
	}




	if($users['COUNT'] == 0 && $imeis['COUNT'] == 0 && $services['COUNT'] == 0)
	{
		echo '<li><a href="#" class="text-center"><span class="badge bg-important">NO RESULT FOUND</span></a></li>';
	}



	exit();
?>