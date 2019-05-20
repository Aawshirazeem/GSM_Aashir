<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$objCredits = new credits();

	$supplier->checkLogin();
	$supplier->reject();
	
	$qStrIds = "";
	$Ids = (isset($_POST['Ids']))? $_POST['Ids'] : array() ;
	$type=$request->PostStr('type');
	$supplier_id=$request->PostInt('supplier_id');
	$limit=$request->PostInt('limit');
	$user_id=$request->PostInt('user_id');
	$ip=$request->PostStr('ip');
	
	
	//// if IMEI Download button is pressed ////
	$download=$request->PostStr('download');
	if($download == 'Download')
	{
		include(CONFIG_PATH_SUPPLIER_ABSOLUTE . 'order_imei_download.php');
		exit();
	}
	////////////////////////////////////////////
	
	$pString='';
	if($supplier_id != 0)
	{
		$pString .= (($pString != '') ? '&' : '' ) . 'supplier_id=' . $supplier_id;
	}
	if($limit != 0)
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
	$pString = trim($pString, '&');
	
	$sql_1='select * from ' . SUPPLIER_MASTER . ' where id=' . $supplier_id;
	$query_1=$mysql->query($sql_1);
	
	foreach($Ids as $id)
	{
		if(isset($_POST['pay_' . $id]))
		{
			$imei=$request->PostStr('imei_' . $id);
			$order_id=$id;
			$tool_name=$request->PostStr('tool_name_' . $id);
			$credits=$request->PostStr('credits_' . $id);			
			
			$sql = 'update ' . ORDER_IMEI_MASTER . ' set supplier_paid=1, supplier_paid_on=now() where status=2 and id=' . $mysql->getInt($id);
			$query = $mysql->query($sql);
			if($mysql->rowCount($query_1)>0)
			{
				$rows=$mysql->fetchArray($query_1);
				$username=$rows[0]['username'];
				$email=$rows[0]['email'];
				$objEmail = new email();
				$args = array(
						'to' => $email,
						'from' => CONFIG_EMAIL,
						'fromDisplay' => CONFIG_SITE_NAME,
						'username' => $username,
						'order_id'=>	$order_id,
						'tool_name'=>	$tool_name,
						'imei'=>	$imei,
						'credits'=>	$credits,
						'site_admin' => CONFIG_SITE_NAME
						);
				$objEmail->sendEmailTemplate('admin_pay_supplier_imei_supplier', $args);
				$objEmail = new email();
				$args = array(
						'to' => CONFIG_EMAIL,
						'from' => CONFIG_EMAIL,
						'fromDisplay' => CONFIG_SITE_NAME,
						'username' => $username,
						'order_id'=>	$order_id,
						'tool_name'=>	$tool_name,
						'imei'=>	$imei,
						'credits'=>	$credits,
						'site_admin' => CONFIG_SITE_NAME
						);
				$objEmail->sendEmailTemplate('admin_pay_supplier_imei_admin', $args);
			}
		}
		if(isset($_POST['return_' . $id]))
		{
			$sql = 'select * from ' . ORDER_IMEI_MASTER . ' oim where id=' . $mysql->getInt($id);
			$query = $mysql->query($sql);
			$rows = $mysql->fetchArray($query);
			
			$objCredits->refundIMEI($id, $rows[0]['user_id'], $rows[0]['credits']);
			
			$sql = 'update 
						' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
						set 
						im.status=3,
						im.reply="", 
						im.reply_date_time=now(),
						im.message=' . $mysql->quote(base64_encode($_POST['return_remarks_' . $id])) . ',
						um.credits = um.credits + im.credits, um.credits_used = um.credits_used - im.credits
						where im.status=2 and um.id = im.user_id and im.id=' . $mysql->getInt($id);
			$mysql->query($sql);
		}
		else
		{
			$unlock_code = $request->PostStr('unlock_code_' . $id);
			$unlock_code2 = $request->PostStr('unlock_code_' . $id . '_2');
			if($unlock_code != $unlock_code2)
			{
				$sql = 'update ' . ORDER_IMEI_MASTER . ' set reply_date_time=now(), reply=' . $mysql->quote(base64_encode($unlock_code)) . ' where status=2 and id=' . $mysql->getInt($id);
				$mysql->query($sql);
			}
			
		}
	}

	$qStrIds = substr($qStrIds, 0, strlen($qStrIds)-1);
	
	header("location:" .CONFIG_PATH_SITE_SUPPLIER ."order_imei.html?type=" . $type . (($pString!='') ? ('&' . $pString) : '') . "&reply=" .urlencode('reply_imei_update_success'));
	exit();
?>
