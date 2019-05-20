<?php
	// Set flag that this is a parent file
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$member->checkLogin();
	$member->reject();

	$file_service = $request->getInt('file_service');
	

	
	

	$sql = 'select
					fsm.*,
					fsad.amount,
					fssc.amount splCr,
                                        fsscr.amount splres,
					pfm.amount as packageCr,
					cm.prefix, cm.suffix
				from ' . FILE_SERVICE_MASTER . ' fsm
				left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
				left join ' . FILE_SERVICE_AMOUNT_DETAILS . ' fsad on(fsad.service_id=fsm.id and fsad.currency_id = ' . $member->getCurrencyID() . ')
				left join ' . FILE_SPL_CREDITS . ' fssc on(fssc.user_id = ' . $member->getUserID() . ' and fssc.service_id=fsm.id)
				left join ' . FILE_SPL_CREDITS_RESELLER . ' fsscr on(fsscr.user_id = ' . $member->getUserID() . ' and fsscr.service_id=fsm.id)
				left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserID() . ')
				left join ' . PACKAGE_FILE_DETAILS . ' pfm on(pfm.package_id = pu.package_id and pfm.currency_id = ' . $member->getCurrencyID() . ' and pfm.service_id = fsm.id)
				where fsm.id= ' . $file_service . '
				order by fsm.service_name';
	$result = $mysql->getResult($sql);
	if($result['COUNT'] == 0)
	{
		echo '--';
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
		<div  style="width:80%;margin:0px auto;">
			<?php
				$msgCr = 'Price: ' . (($isSpl == true) ? '<del>' . $objCredits->printCredits($amountDisplayOld, $prefix, $suffix) . '</del> <b>' . $objCredits->printCredits($amountDisplay, $prefix, $suffix) . '</b>' : $objCredits->printCredits($amountDisplay, $prefix, $suffix));
				$msgCr .= (($row['delivery_time'] != '') ? ' | ' . $row['delivery_time'] : '');
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
