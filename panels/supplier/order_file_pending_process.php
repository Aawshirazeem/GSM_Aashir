<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$supplier->checkLogin();
	$supplier->reject();
	$validator->formValidateSupplier('supplier_key_33455gkgk5d2');


	$qStrIds = "";
	$Ids = isset($_POST['Ids']) ? $_POST['Ids'] : '';
	$type = $request->postStr('type');
	
	if($Ids == '')
	{
		header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_file.html?reply=" .urlencode('reply_n_imi'));
	}

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
						' . ORDER_FILE_SERVICE_MASTER . ' ofsm
						set ofsm.status=-1, 
							ofsm.supplier_id=' . $supplier->getUserId() . ',
							ofsm.credits_purchase= (select credits_purchase from ' . FILE_SUPPLIER_DETAILS . ' fsd where fsd.supplier_id=' . $supplier->getUserId() . ' and fsd.service_id = ofsm.file_service_id)
						where ofsm.id in (' . $qStrIds . ')';
		$mysql->query($sql);
			header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_file.html?reply=" .urlencode('reply_imi_succ'));
	}
	else
	{
			header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_file.html?reply=" .urlencode('reply_n_imi'));
	}

	exit();
?>