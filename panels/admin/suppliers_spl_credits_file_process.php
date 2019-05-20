<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('suppliers_edit_54964566hh2');

	$supplier_id = $request->PostInt('id');
	
	/* Clear previous entries */
	$sql = 'delete from ' . FILE_SUPPLIER_DETAILS . ' where supplier_id=' . $mysql->getInt($supplier_id); 
	$mysql->query($sql);
	
	
	/* if some imei service selected */
	if(isset($_POST['ids']))
	{
		$ids = $_POST['ids'];
		
		/* Iterate all selected services */
		foreach($ids as $id)
		{
			$credits = $_POST['spl_'.$id];
			$sql = 'insert into ' . FILE_SUPPLIER_DETAILS . '
						(supplier_id, service_id, credits_purchase)
						values('
							. $mysql->getInt($supplier_id) . ','
							. $mysql->getInt($id) . ','
							. $mysql->getFloat($credits) . '
						)';
			$mysql->query($sql);
		}
	}
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "suppliers_edit.html?id=" . $supplier_id . "&reply=" . urlencode('reply_success_suppliers_credits_file'));
	exit();
?>