<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$member->checkLogin();
	$member->reject();
	$validator->formValidateUser('user_pre_log_14d32233');

	$cookie = new cookie();
	$objImei = new imei();
	$objCredits = new credits();
	
	$crM = $objCredits->getMemberCredits();
	$prefix = $crM['prefix'];
	$suffix = $crM['suffix'];
	$rate = $crM['rate'];
	
	
    $prepaid_log = $request->PostInt('prepaid_log');
    $remarks = $request->PostStr('remarks');


	$sql_cr = '
				select
						slm.credits,
						slsc.credits as splCr,
						pd.credits as packageCr
				from ' . PREPAID_LOG_MASTER . ' slm
				left join ' . PREPAID_LOG_SPL_CREDITS . ' slsc on (slm.id = slsc.log_id and slsc.user_id = ' . $mysql->getInt($member->getUserId()) . ')
				left join ' . PACKAGE_PREPAID_LOG_DETAILS . ' pd on(slm.id = pd.prepaid_log_id and pd.package_id=(
					select package_id from ' . PACKAGE_USERS . ' pud where pud.user_id = ' . $member->getUserId() . '))
				where slm.id=' . $mysql->getInt($prepaid_log);

	$sql_cr = 'select
					plm.*,
					plgm.group_name,
					plad.amount,
					plsc.amount splCr,
                                        plscr.amount splres,
					pplm.amount as packageCr,
					cm.prefix, cm.suffix
				from ' . PREPAID_LOG_MASTER . ' plm
				left join ' . PREPAID_LOG_GROUP_MASTER . ' plgm on(plm.group_id = plgm.id)
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
				left join ' . PREPAID_LOG_AMOUNT_DETAILS . ' plad on(plad.log_id=plm.id and plad.currency_id = ' . $member->getCurrencyID() . ')
				left join ' . PREPAID_LOG_SPL_CREDITS . ' plsc on(plsc.user_id = ' . $member->getUserID() . ' and plsc.log_id=plm.id)
				left join ' . PREPAID_LOG_SPL_CREDITS_RESELLER . ' plscr on(plscr.user_id = ' . $member->getUserID() . ' and plscr.log_id=plm.id)
				left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserID() . ')
				left join ' . PACKAGE_PREPAID_LOG_DETAILS . ' pplm on(pplm.package_id = pu.package_id and pplm.currency_id = ' . $member->getCurrencyID() . ' and pplm.log_id = plm.id)
				where plm.id=' . $mysql->getInt($prepaid_log);
	$resultCredits = $mysql->getResult($sql_cr);
	$rowCr = $resultCredits['RESULT'][0];

	$amount = $mysql->getFloat($rowCr['amount']);
	$amountSpl = $mysql->getFloat($rowCr['splCr']);
	$amountPackage = $mysql->getFloat($rowCr['packageCr']);
	$amountDiscount = 0;
	$isSpl = false;
	if($rowCr["splres"]=="")
        {
	if($amountSpl > 0){
		$isSpl = true;
		$amountDiscount = $amount - $amountSpl;
		$amount = $amountSpl;
	}
	if($amountPackage >	 0){
		$isSpl = true;
		$amountDiscount = $amount - $amountPackage;
		$amount = $amountPackage;
	}
        }
        else 
        {
         $isSpl = false;
         $amount=$rowCr["splres"];
        }
	
	$sql_total = 'select count(id) as total from ' . PREPAID_LOG_UN_MASTER . ' where prepaid_log_id=' . $mysql->getInt($prepaid_log) . ' and status=0';
	$query_total = $mysql->query($sql_total);
	$rows_total = $mysql->fetchArray($query_total);
	$total = $rows_total[0]['total'];
	if($total == 0)
	{
		header('location:' . CONFIG_PATH_SITE_USER . 'prepaid_logs_submit.html?reply=' . urlencode('reply_not_available_prepaid') . '&type=error');
		exit();
	}

	$crAcc = 0;
	$sql_credits = 'select id, credits from ' . USER_MASTER . ' where id=' . $mysql->getInt($member->getUserId());
	$query_credits = $mysql->query($sql_credits);
	$row_credits = $mysql->fetchArray($query_credits);
	$crAcc = $row_credits[0]["credits"];
		
    if($crAcc >= $amount)
    {
		$ip = gethostbynamel($_SERVER['REMOTE_ADDR']);

		$sql = 'update ' . PREPAID_LOG_UN_MASTER . ' set
					user_id=' . $mysql->getInt($member->getUserId()) . ',
					ip = ' . $mysql->quote($ip[0]) . ',
					remarks=' . $mysql->quote($remarks) . ',
					date_order=now(),
					status=1
					where status=0
					and prepaid_log_id=' . $prepaid_log . '
					order by ID
					limit 1';
		$mysql->query($sql);
						
		
		$objCredits->cutOrderCreditsDirect(0, $amount, $member->getUserID(), 4);


		header('location:' . CONFIG_PATH_SITE_USER . 'prepaid_logs.html?reply=' . urlencode('reply_submit_pre_log_success'));
		exit();
	}
	else
	{
		header('location:' . CONFIG_PATH_SITE_USER . 'server_logs_submit.html?reply=' . urlencode('reply_insffi_credit') . '&type=error');
		exit();
	}
	

	
	header('location:' . CONFIG_PATH_SITE_USER . 'imei_submit.html');
	exit();
?>