<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$supplier->checkLogin();
	$supplier->reject();
	$validator->formValidateSupplier('supplier_server_log_33245d3345d2');

	$qStrIds = "";
	$Ids = (isset($_POST['Ids']))? $_POST['Ids'] : array();
	$type = $request->postStr('type');
	
	foreach($Ids as $id)
	{
		if(isset($_POST['locked_' . $id]))
		{
			$qStrIds .= $mysql->getInt($id) . ',';
		}
	}

	$qStrIds = substr($qStrIds, 0, strlen($qStrIds)-1);
	
	if($qStrIds != '')
	{
		$sql = 'update
					' . ORDER_SERVER_LOG_MASTER . ' oslm
						set oslm.status=-1,
						oslm.supplier_id=' . $supplier->getUserId() . ',
						oslm.credits_purchase= (select credits_purchase from ' . SERVER_LOG_SUPPLIER_DETAILS . ' slsd where slsd.supplier_id=' . $supplier->getUserId() . ' and slsd.log_id = oslm.server_log_id)
					where oslm.id in (' . $qStrIds . ')';
		$mysql->query($sql);
		header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_server_log.html?type=" . $type . "&reply=" .urlencode('reply_imi_s_succ'));
	}
	else
	{
			header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_server_log.html?type=pending&reply=" .urlencode('reply_n_imi'));
			exit();
	}
	exit();
?>
