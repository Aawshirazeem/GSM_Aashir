<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	//$validator->formValidateAdmin('user_edit_789971255d2');
	
    
	$all_gateways = $request->PostCheck('all_gateways');
	$id = $request->PostInt('id');	
	$pg_paypal = $request->PostStr('charges_1');	
	$pg_moneybookers = $request->PostStr('charges_3');	
	
	$auto_pay = $request->PostCheck('auto_pay'); 
    
	if($auto_pay==1)
	{
		$sql = 'update '.USER_MASTER.' set auto_pay=1 where id='.$id;
		
	}
	else
	{
		$sql = 'update '.USER_MASTER.' set auto_pay=0 where id='.$id;
	}
	$mysql->query($sql);
	 
	$sql = 'delete from ' . GATEWAY_DETAILS . ' where user_id = ' . $mysql->getInt($id);
	$mysql->query($sql);
	
	if($all_gateways == 0)
	{
		$gateway_ids = $_POST['gateway_ids'];
		foreach($gateway_ids as $gateway_id)
		{
			$charges = $request->PostStr('charges_'.$gateway_id);	
			$sql = 'insert into ' . GATEWAY_DETAILS . ' (user_id, gateway_id) values(' . $id . ',' . $gateway_id . ')';
			$query = $mysql->query($sql);
			
			if($query)
			{
			$sql1 = 'update '.USER_MASTER.' set pg_paypal='.$mysql->quote($pg_paypal).' ,pg_moneybookers='.$mysql->quote($pg_moneybookers).' where id='.$mysql->getInt($id); 
			$mysql->query($sql1);
			}
			
		}
	}
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "users_edit.html?id=" . $id . "&reply=" . urlencode('reply_payment_gateway'));
	exit();	
?>