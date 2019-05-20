<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	
	$request = new request();
	$mysql = new mysql();
	$api = new api();
	
	
	
	
	/*
		
		Algorithem 
		
	*/
	
	
	/*
		Get total number of orders per api */
        $service_id = $request->getInt('service_id');
        if($service_id != '')
        {
            
        
	$sql = 'select oim.*
					from ' . ORDER_IMEI_MASTER . ' oim
					
					where oim.status in (0,1) and oim.tool_id='.$service_id;
	$query = $mysql->query($sql);
	$arrOrders = $mysql->fetchArray($query);
        
     //  echo '<pre/>';
      //  var_dump($arrOrders);exit;
        $AvailIds='';
        $UnAvailIds='';
        for($i=0;$i<count($arrOrders);$i++)
        {
         $unlockcode='Unlock Code';
           
            $imei=$arrOrders[$i]['imei'];
           // $imei='357577053652216';
            $url = "http://www.yoigo.com/liberar-movil-yoigo/proceso-liberar.php?imei=".$imei;

 $curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);
  $response = curl_exec($curl);
  $pieces = explode("!", $response);
  //echo $pieces[0]; 
  $len=strlen($pieces[0]);
  //echo $len;
  //exit;
  //echo $pieces[1];
  $unlockcode = explode(":", $pieces[1]);
  $UnAvailRemarks='Not Found';
  //echo $unlockcode[1];
  //exit;
  //var_dump($pieces);exit;
  curl_close($curl);
        if($len==38)
        {
            $sqlAvail = '
									update 
											' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
										set
											im.status=2,
											im.reply=' . $mysql->quote(base64_encode($unlockcode[1])) . ',
											im.admin_id_done=' . $admin->getUserId() . ',
											im.reply_date_time=now(),
											um.credits_inprocess = um.credits_inprocess - im.credits,
											um.credits_used = um.credits_used + im.credits
										where  um.id = im.user_id and im.id=' . $arrOrders[$i]['id'] . '
									
									    
									';
           // echo $sqlAvail;exit;
            $mysql->query($sqlAvail);
            $AvailIds .=$arrOrders[$i]['id']. ',';
            echo 'order success';
        }
        else if ($len==33)
        {
            $sqlUnavail = '
								update 
									' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
									set
										im.status=3, 
										im.admin_id_done=' . $admin->getUserId() . ',
										im.reply_date_time=now(),
										im.message = ' . $mysql->quote(base64_encode($UnAvailRemarks)) . ',
										um.credits = um.credits + im.credits,
										um.credits_inprocess = um.credits_inprocess - im.credits
									where  um.id=im.user_id and im.id =' . $arrOrders[$i]['id'] . ';
									
								
								insert into ' . CREDIT_TRANSECTION_MASTER . '
									(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
									credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type, ip)
									
									select 
											oim.user_id,
											now(),
											oim.credits,
											um.credits - oim.credits,
											um.credits_inprocess + oim.credits,
											um.credits_used,
											um.credits,
											um.credits_inprocess,
											um.credits_used,
											oim.id,
											' . $mysql->quote("IMEI Return") . ',
											0,
											' . $mysql->quote($ip) . '
										from ' . ORDER_IMEI_MASTER . ' oim 
										left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
										left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
									where oim.id =' . $arrOrders[$i]['id'] . ';
							
							
							';
            $mysql->multi_query($sqlUnavail);
            $UnAvailIds .=$arrOrders[$i]['id']. ',';
            echo 'order reject';
        } 
        else 
        {
            
           echo 'proccess fail'; 
        }
        }
    
        
        /**********************************************************
						START: Available
	**********************************************************/
	$ip = $_SERVER['REMOTE_ADDR'];
	
	$email_config 		= $objEmail->getEmailSettings();
	$from_admin 		= $email_config['system_email'];
	$admin_from_disp	= $email_config['system_from'];
	$signatures			= "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);
	
	if($AvailIds != '')
	{
		$AvailIds = trim($AvailIds, ',');
		
	//	    echo '<pre/>';
      //  var_dump($AvailIds);
     //   echo $AvailIds;
     //   exit;
		
		$sql = 'select
							um.username, um.email,
							oim.id as oid, oim.user_id uid, oim.reply,
							oim.imei, itm.tool_name, oim.credits
						from ' . ORDER_IMEI_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
					where oim.id in (' . $AvailIds . ') 
					order by uid,tool_name,oid';
					
		$query = $mysql->query($sql);
		
		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			$argsAll = array();
			foreach($rows as $row)
			{
				$args = array(
								'to' => $row['email'],
								'from' => $from_admin,
								'fromDisplay' => $admin_from_disp,
								'user_id' => $row['uid'],
								'save' => '1',
								'username' => $row['username'],
								'imei' => $row['imei'],
								'unlock_code' => base64_decode($row['reply']),
								'order_id' => $row['oid'],
								'tool_name' => $row['tool_name'],
								'credits'=>	$row['credits'],
								'site_admin' => $admin_from_disp,
								'send_mail' => true
							);
				array_push($argsAll, $args);
			}
			////////New Code for Same User / Same Service orders in 1 email
			$to_user = '';
			
			$new_orders_array = array();
					$simple_email_body = '';
					foreach($argsAll as $args)  // each iteration/order
					{
						$new_orders_array[$args['user_id']][$args['tool_name']][] = $args;						
					}
					
					foreach($new_orders_array as $user)
					{
						foreach($user as $service_order)
						{
							if(sizeof($service_order) > 1)
							{
								$simple_email_body .= '<h2>Your Unlock Codes</h2>';
																	
								foreach($service_order as $order)
								{
									$simple_email_body .= '
									<p><b>Service Name:</b>' . $order['tool_name'] . '<p>
									<p><b>IMEI:</b>' . $order['imei'] . '<p>
									<p><b>Unlock Code:</b>' . $order['unlock_code'] . '<p>
									<p><b>Credits:</b>' . $order['credits']. '<p>
									<p><b>Order ID :</b>' . $order['order_id'] . '<p>
									<p>-------------------------------------------------- 
									<p>--------------------------------------------------
									';	
									$to_user = $order['to'];
								}
								$objEmail->setTo($to_user);
								$objEmail->setFrom($from_admin);
								$objEmail->setFromDisplay($admin_from_disp);
								$objEmail->setSubject("Unlock Codes");
								$objEmail->setBody($simple_email_body.$signatures);
								//$sent = $objEmail->sendMail();
                                                                 $objEmail->queue();
								// SEND Simple Email
								$simple_email_body = '';
							}
							else
							{
								$objEmail->sendMultiEmailTemplate('admin_user_imei_avail', $service_order);	
							}
						}
					}
			
			////////./
			
		}
		
	}
	
	/**********************************************************
						END: Available
	**********************************************************/
        
        /**********************************************************
						START: Unavail
	**********************************************************/
	if($UnAvailIds != '')
	{
		$UnAvailIds = trim($UnAvailIds, ',');

		
		$sql = 'select
							um.username, um.email,oim.message as reason,
							oim.id as oid, oim.user_id uid, oim.reply,
							oim.imei, itm.tool_name, oim.credits
						from ' . ORDER_IMEI_MASTER . ' oim 
						left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
						left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
					where oim.id in (' . $UnAvailIds . ') 
					order by uid,tool_name,oid';
		$query = $mysql->query($sql);
		
		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			$argsAll = array();
			foreach($rows as $row)
			{
				$args = array(
								'to' => $row['email'],
								'from' => $from_admin,
								'fromDisplay' => $admin_from_disp,
								'user_id' => $row['uid'],
								'save' => '1',
								'username' => $row['username'],
								'imei' => $row['imei'],
								'order_id' => $row['oid'],
								'tool_name' => $row['tool_name'],
								'credits'=>	$row['credits'],
								'site_admin' => $admin_from_disp,
								'reason'	=>  base64_decode($row['reason']),
								'send_mail' => true
							);
				array_push($argsAll, $args);
			}
			
			////////New Code for Same User / Same Service orders in 1 email
			$to_user = '';
			
			$new_orders_array = array();
					$simple_email_body = '';
					foreach($argsAll as $args)  // each iteration/order
					{
						$new_orders_array[$args['user_id']][$args['tool_name']][] = $args;						
					}
					
					foreach($new_orders_array as $user)
					{
						foreach($user as $service_order)
						{
							if(sizeof($service_order) > 1)
							{
								$simple_email_body .= '<h2>Your Unlock Codes are Cancelled</h2>';
																	
								foreach($service_order as $order)
								{
									$simple_email_body .= '
									<p><b>Service Name:</b>' . $order['tool_name'] . '<p>
									<p><b>IMEI:</b>' . $order['imei'] . '<p>
									<p><b>Reson:</b>'.base64_decode($order['reason']).'<p>
									<p><b>Credits:</b>' . $order['credits']. '<p>
									<p><b>Order ID :</b>' . $order['order_id'] . '<p>
									<p>-------------------------------------------------- 
									<p>--------------------------------------------------
									';	
									$to_user = $order['to'];
								}
								$objEmail->setTo($to_user);
								$objEmail->setFrom($from_admin);
								$objEmail->setFromDisplay($admin_from_disp);
								$objEmail->setSubject("Unlock Codes");
								$objEmail->setBody($simple_email_body.$signatures);
								//$sent = $objEmail->sendMail();
                                                                 $objEmail->queue();
								// SEND Simple Email
								$simple_email_body = '';
							}
							else
							{
								$objEmail->sendMultiEmailTemplate('admin_user_imei_unavail', $service_order);
							}
						}
					}
			
			////////./
			
		}
	}
	/**********************************************************
						END: Unavail
	**********************************************************/
	
        
        }
        else
        {
           echo 'No Service Select..'; 
        }
        ?>
	