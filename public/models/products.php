<?php
	$sql = 'select itm.tool_name, itm.group_id, itm.credits, itm.delivery_time, itm.status, igm.group_name
				from ' . IMEI_TOOL_MASTER . ' itm
				left join ' . IMEI_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
				order by itm.group_id, itm.tool_name';
	$arrayIMEIS = $mysql->getResult($sql);
	
	
	$sql = 'select itm.service_name, itm.credits, itm.delivery_time, itm.status
				from ' . FILE_SERVICE_MASTER . ' itm
				order by itm.service_name';
	$arrayFile = $mysql->getResult($sql);
	
	$sql = 'select slm.server_log_name, slm.group_id, slm.credits, slm.delivery_time, slm.status, slgm.group_name
				from ' . SERVER_LOG_MASTER . ' slm
				left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)
				order by slm.group_id, slm.server_log_name';
	$arrayServerLog = $mysql->getResult($sql);
	
	
	$sql = 'select slm.prepaid_log_name, slm.group_id, slm.credits, slm.status, slgm.group_name
				from ' . PREPAID_LOG_MASTER . ' slm
				left join ' . PREPAID_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)
				order by slm.group_id, slm.prepaid_log_name';
	$arrayPrepaidLog = $mysql->getResult($sql);
?>