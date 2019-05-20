<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();

    $display_order = $request->GetInt('display_order');
    $type = $request->GetStr('type');
    
    if($type == 'up')
    {
		$sql = 'update ' . IMEI_GROUP_MASTER . ' set display_order = 0 where display_order = ' . $display_order;
		//echo $sql . "<br />";
		$mysql->query($sql);
		$sql = 'update ' . IMEI_GROUP_MASTER . ' set display_order = display_order + 1 where display_order = ' . $mysql->getInt(($display_order-1));
		//echo $sql . "<br />";
		$mysql->query($sql);
		$sql = 'update ' . IMEI_GROUP_MASTER . ' set display_order = ' . $mysql->getInt(($display_order - 1)) . ' where display_order = 0';
		//echo $sql . "<br />";
		$mysql->query($sql);
	}
    else if($type == 'down')
    {
		$sql = 'update ' . IMEI_GROUP_MASTER . ' set display_order = 0 where display_order = ' . $mysql->getInt($display_order);
		//echo $sql . "<br />";
		$mysql->query($sql);
		$sql = 'update ' . IMEI_GROUP_MASTER . ' set display_order = display_order - 1 where display_order = ' . $mysql->getInt($display_order+1);
		//echo $sql . "<br />";
		$mysql->query($sql);
		$sql = 'update ' . IMEI_GROUP_MASTER . ' set display_order = ' . $mysql->getInt($display_order + 1) . ' where display_order = 0';
		//echo $sql . "<br />";
		$mysql->query($sql);
	}
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "services_imei_group.html?reply=" . urlencode('reply_ord_success'));
	
	
	
?>