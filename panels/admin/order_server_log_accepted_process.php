<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	
	$qStrIds = "";
	$Ids = $_POST['Ids'];
	$type = $request->PostStr('type');
	$supplier_id = $request->PostStr('supplier_id');
	$user_id=$request->PostInt('user_id');
	$ip=$request->PostStr('ip');
	
	$pString='';
	if($supplier_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '' ) . 'supplier_id=' . $supplier_id;
	}
	if($ip != '')
	{
		$pString .= (($pString != '') ? '&' : '') . 'ip=' . $ip;
	}
	if($user_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '') . 'user_id=' . $user_id;
	}
	$pString = trim($pString, '&');
	
	foreach($Ids as $id)
	{
		if(isset($_POST['pay_' . $id]))
		{
			$sql = 'update ' . ORDER_SERVER_LOG_MASTER . '
						set supplier_paid=1, supplier_paid_on=now() where id=' . $mysql->getInt($id);
			$query = $mysql->query($sql);
		}
		
	}

	header("location:" .CONFIG_PATH_SITE_ADMIN ."order_server_log.html?type=" . $type . (($pString!='') ? ('&' . $pString) : '') . "&reply=" .urlencode('reply_server_log_update'));
	exit();
?>