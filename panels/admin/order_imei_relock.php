<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	
	$objCredits = new credits();
	$admin->checkLogin();
	$admin->reject();

	
	$id=$request->GetInt('id');
	
	
			
	$sql = '
					update 
						' . ORDER_IMEI_MASTER . ' im, ' . USER_MASTER . ' um
						set
							im.status=1, 
							um.credits = um.credits - im.credits,
							um.credits_inprocess = um.credits_inprocess + im.credits
						where im.status=3 and um.id=im.user_id and im.id =' . $id . ';
						
					
					insert into ' . CREDIT_TRANSECTION_MASTER . '
						(user_id, date_time, credits, credits_acc, credits_acc_process, credits_acc_used,
						credits_after, credits_after_process, credits_after_used, order_id_imei, info, trans_type)
						
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
								' . $mysql->quote("IMEI RELOCKED") . ',
								2
							from ' . ORDER_IMEI_MASTER . ' oim 
							left join ' . USER_MASTER . ' um on (oim.user_id = um.id)
							left join ' . IMEI_TOOL_MASTER . ' itm on (oim.tool_id = itm.id)
						where oim.id =' . $id;
	$mysql->multi_query($sql);
		
	
	header("location:" .CONFIG_PATH_SITE_ADMIN ."order_imei.html?type=" . $type . (($pString!='') ? ('&' . $pString) : '') . "&reply=" .urlencode('reply_imei_update_success'));
	exit();
?>