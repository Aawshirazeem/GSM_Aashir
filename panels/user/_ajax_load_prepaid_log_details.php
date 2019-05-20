<?php
	// Set flag that this is a parent file
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$member->checkLogin();
	$member->reject();

	$prepaid_log = $request->getInt('prepaid_log');
	
	$crM = $objCredits->getMemberCredits();
	$prefix = $crM['prefix'];
	$suffix = $crM['suffix'];
	$rate = $crM['rate'];
	
	$package_id = 0;
	$sql = 'select * from ' . PACKAGE_USERS . ' where user_id=' . $member->getUserId();
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$package_id = $rows[0]['package_id'];
	}
	
	$sql = 'select
					slm.*,
					slsc.credits as splCr,
					pd.credits as packageCr
				from ' . PREPAID_LOG_MASTER . ' slm
				left join ' . PREPAID_LOG_SPL_CREDITS . ' slsc on (slm.id = slsc.log_id and slsc.user_id = ' . $member->getUserId() . ')
				left join ' . PACKAGE_PREPAID_LOG_DETAILS . ' pd on(slm.id = pd.prepaid_log_id and pd.package_id='.$package_id.')
				where slm.id=' . $mysql->getInt($prepaid_log);

	$sql = 'select
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
				where plm.id=' . $prepaid_log . '
				order by plgm.group_name, plm.prepaid_log_name';
	$result = $mysql->getResult($sql);

	if($result['COUNT'] == 0)
	{
		echo "";
	}
	else
	{
		$row = $result['RESULT'][0];
		$prefix = $row['prefix'];
		$suffix = $row['suffix'];
		$amount = $mysql->getFloat($row['amount']);
		$amountSpl = $mysql->getFloat($row['splCr']);
		$amountPackage = $mysql->getFloat($row['packageCr']);
		$amountDisplay = $amountDisplayOld = $amount;

		$isSpl = false;
		 if($row["splres"]==""){
									if($amountSpl > 0){
										$isSpl = true;
										$amountDisplay = $amountSpl;
									}
									if($amountPackage >	 0){
										$isSpl = true;
										$amountDisplay = $amountPackage;
									}
                                                                        }
                                                                        else 
                                                                        {
                                                                          $isSpl = false;
                                                                          $amountDisplay = $mysql->getFloat($row["splres"]);
                                                                            
                                                                        }
		
?>
		<div class="clear"></div>
			<div>
				<?php
					$msgCr = 'Price: ' . (($isSpl == true) ? '<del>' . $objCredits->printCredits($amountDisplayOld, $prefix, $suffix) . '</del> <b>' . $objCredits->printCredits($amountDisplay, $prefix, $suffix) . '</b>' : $objCredits->printCredits($amountDisplay, $prefix, $suffix));
					$graphics->messagebox($msgCr);
				?>
			</div>
			<div  style="width:80%;margin:0px auto;" class="<?php echo (($row['info'] == '') ? ' hidden' : ''); ?>">
				<div class="ui-widget">
					<br />
					<div class="ui-state-highlight ui-corner-all" style="padding:5px 5px 5px 5px;"> 
						<?php echo nl2br($row['info']);?>
					</div>
				</div>
			</div>
<?php
	}
?>
