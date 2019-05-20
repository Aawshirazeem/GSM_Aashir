<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetUser('user_server_log_78665448');
	
	if($service_logs == "0")
	{
	//	echo "<h1>You are authorize to view this page!</h1>";
                
                echo "<h1>" . $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_you_are_NOT_authorize_to_access_this_page_please_contact_with_admin')) . "</h1>";

		return;
	}
	
        if(isset($_GET['replys']))
            $reply='Error:'.$_GET['replys'];
	/* Get package id for the user */
	$package_id = 0;
	$sql = 'select * from ' . PACKAGE_USERS . ' where user_id=' . $member->getUserId();
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$package_id = $rows[0]['package_id'];
	}
	$crM = $objCredits->getMemberCredits();
	$prefix = $crM['prefix'];
	$suffix = $crM['suffix'];
	$rate = $crM['rate'];
        
        // get user mater pin status
$masterpin = 0;
$sqll = 'select a.master_pin from nxt_user_master a

where a.id=' . $member->getUserId();
$query = $mysql->query($sqll);

if ($mysql->rowCount($query) != 0) {
    $row = $mysql->fetchArray($query);
    $masterpin = $row[0]['master_pin'];
}

$sql_2 = 'select a.value,a.field finfo from '.CONFIG_MASTER.' a
where a.field in ("USER_NOTES","ADMIN_NOTES")
order by a.id';
$query_2 = $mysql->query($sql_2);
$rows_2 = $mysql->fetchArray($query_2);

$a_notes=$rows_2[1]['value'];
$u_notes=$rows_2[0]['value'];
?>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init.js" ></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init_mmember.js" ></script>

<form action="<?php echo CONFIG_PATH_SITE_USER;?>server_logs_submit_process.do" method="post">
<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default">
                           
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_order_new_server_log')); ?></div>
				<div class="panel-body">
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_server_log')); ?>
							<div class="hidden pull-right ML10" id="loadIndTool"><i class="icon-refresh icon-spin"></i></div>
						</label>
<!--						<select name="server_log" class="form-control" id="server_log">-->
                                                     <select name="server_log" id="server_log"  class="selectpicker" data-live-search="true"  data-style="btn-white">
							<option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_server_log')); ?></option>
							<?php
								$sql = 'select
												slm.*,
												slsc.credits as splCr,
												pd.credits as packageCr,
												slgm.group_name
										from ' . SERVER_LOG_MASTER . ' slm
										left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on (slm.group_id=slgm.id)
										left join ' . SERVER_LOG_SPL_CREDITS . ' slsc on (slm.id = slsc.log_id and slsc.user_id = ' . $member->getUserId() . ')
										left join ' . PACKAGE_SERVER_LOG_DETAILS . ' pd on(slm.id = pd.server_log_id and pd.package_id='.$package_id.')
										where slm.status=1 and
											slm.id not in (
															select distinct(log_id) from ' . SERVER_LOG_USERS . ' where log_id not in(
																	select distinct(log_id) from ' . SERVER_LOG_USERS . ' where user_id = ' . $member->getUserId() . ')
														)
										order by slgm.display_order'; 
										
								$sql = 'select
												slm.*,
												slgm.group_name,
												slad.amount,
												slsc.amount splCr,
                                                                                                slscr.amount splres,
												pslm.amount as packageCr,
												cm.prefix, cm.suffix
											from ' . SERVER_LOG_MASTER . ' slm
											left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)
											left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $member->getCurrencyID() . ')
											left join ' . SERVER_LOG_AMOUNT_DETAILS . ' slad on(slad.log_id=slm.id and slad.currency_id = ' . $member->getCurrencyID() . ')
											left join ' . SERVER_LOG_SPL_CREDITS . ' slsc on(slsc.user_id = ' . $member->getUserID() . ' and slsc.log_id=slm.id)
											left join ' . SERVER_LOG_SPL_CREDITS_RESELLER . ' slscr on(slscr.user_id = ' . $member->getUserID() . ' and slscr.log_id=slm.id)
											left join ' . PACKAGE_USERS . ' pu on(pu.user_id = ' . $member->getUserID() . ')
											left join ' . PACKAGE_SERVER_LOG_DETAILS . ' pslm on(pslm.package_id = pu.package_id and pslm.currency_id = ' . $member->getCurrencyID() . ' and pslm.log_id = slm.id)
											order by slm.server_log_name';
								$query = $mysql->query($sql);
								$rows = $mysql->fetchArray($query);
								$tempGroupName = "";
								$tempGroupID = 0;
								foreach($rows as $row)
								{
									
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
									if($row['group_name'] != $tempGroupName)
									{
										echo ($tempGroupID == 0) ? '</optgroup>' : '';
										echo '<optgroup label="' . $row['group_name'] . '">';
										$tempGroupName = $row['group_name'];
										$tempGroupID++;
									}
									
									$delivery_time = $row['delivery_time'];
									$delivery_time = ($delivery_time != '') ? (' : ' . $delivery_time) : '';
									echo '<option ' . (($isSpl == true) ? 'style="color:#FF7000"' : '') . ' value="' . $row['id'] . '-1">' . $row['server_log_name'] . ' [' . $objCredits->printCredits($amountDisplay, $prefix, $suffix) . ']' . $delivery_time . '</option>';
								}
								echo '</optgroup>';
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
												order by plgm.group_name, plm.prepaid_log_name';
								$query = $mysql->query($sql);
								$rows = $mysql->fetchArray($query);
								$tempGroupName = "";
								$tempGroupID = 0;
								foreach($rows as $roww)
								{
									$prefix = $roww['prefix'];
									$suffix = $roww['suffix'];
									$amount = $mysql->getFloat($roww['amount']);
									$amountSpl = $mysql->getFloat($roww['splCr']);
									$amountPackage = $mysql->getFloat($roww['packageCr']);
									$amountDisplay = $amountDisplayOld = $amount;

									$isSpl = false;
                                                                      if($roww["splres"]==""){
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
                                                                          $amountDisplay = $mysql->getFloat($roww["splres"]);
                                                                            
                                                                        }


									if($roww['group_name'] != $tempGroupName)
									{
										echo ($tempGroupID == 0) ? '</optgroup>' : '';
										echo '<optgroup label="' . $roww['group_name'] . '">';
										$tempGroupName = $roww['group_name'];
										$tempGroupID++;
									}
									$delivery_time2 = $roww['delivery_time'];
									$delivery_time2 = ($delivery_time2 != '') ? (' : ' . $delivery_time2) : '';
									echo '<option ' . (($isSpl == true) ? 'style="color:#FF7000"' : '') . ' value="' . $roww['id'] . '-2">' . $roww['prepaid_log_name'] . ' [' . $objCredits->printCredits($amountDisplay, $prefix, $suffix) . '] '. $delivery_time2 . '</option>';
								}
								echo '</optgroup>';
							?>
						</select>
					</div>
					  <div id="toolDetails" class="">
							<div class="form-group<?php echo (($row['imei_type'] == '0') ? ' hidden' : ''); ?>">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_field')); ?></label>
								<input type="text" name="custom" id="custom" class="form-control" value="">
							</div>
                                                        
					  </div>
					<div class="form-group<?php echo (($row['imei_type'] == '0') ? ' hidden' : ''); ?>">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_custom_field')); ?></label>
						<input type="text" name="custom2" id="custom2" class="form-control" value="">
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-4">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_mobile')); ?></label>
								<input name="moible" id="moible" type="text" class="form-control" value="" />
							</div>
                                                           
                             <?php if ($a_notes==1)
                    {
                        ?>
							<div class="col-sm-4">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_admin_notes')); ?></label>
								<input name="message" id="message" type="text" class="form-control" value="" />
							</div>
                                                <?php
                    }?>     
                                                           
                             <?php if ($u_notes==1)
                    {
                        ?>
							<div class="col-sm-4">
								<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_notes')); ?></label>
								<input name="remarks" id="remarks" type="text" class="form-control" value="" />
							</div>
                                                     <?php
                    }?>
						</div>
					</div>
				</div> <!-- / panel-body -->
				<div class="panel-footer">
                                    <div id="game">
                        <?php
                        if ($masterpin == 1) {
                            ?>
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Enter Master Pin To Submit Order'); ?></label>
                            <input name="m_pin" id="m_pin" type="text" class="form-control" value="" required=""/><br>
                            <input type="button" class="btn btn-inverse" value="Authorize Pin" onclick="checkpin()">
                             <span style="display:none" class="text-danger" id="loadIndTool2"><i class="fa fa-spinner fa-pulse"></i></span>
                        <?php } else {
                            ?>

                            <button type="submit" class="btn btn-success"><i class="icon-ok-sign"></i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Submit_&_Continue')); ?></button>

                        <?php } ?>
                    </div>
<!--                                    <input type="submit" onclick="return validation();" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_submit_&_continue')); ?>" class="btn btn-success"/>-->
				</div> <!-- / panel-footer -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
       
      </form>
<script type="text/javascript">
     setPathsMember('','<?php echo CONFIG_PATH_SITE_USER; ?>');
     function validation()
     {
//         var custom=document.getElementById('custom').value;
//         var amount=document.getElementById('amount').value;
//         if(custom == "")
//         {
//             alert("please Enter Username");
//             document.getElementById('custom').focus();
//             return false;
//         }
//         else if(amount == "")
//         {
//             alert("please Enter Amount");
//             document.getElementById('amount').focus();
//             return false;
//         }
     }
     
     function calc()
     {
            var price=document.getElementById('price').value;
            var amount=document.getElementById('amount').value;
           document.getElementById('result').style.display="block";
            var result=price * amount;
            result=parseFloat(result).toFixed(2);
            document.getElementById('result').innerHTML="Total Amount: "+result;
            document.getElementById('total_amount').value=result;
           // alert(result);
     }
    </script>
    
<?php
$url1=CONFIG_PATH_SITE_USER."_ajax_check_master_pin.do";
?>
<script type="text/javascript">
     // setPathsMember('', '<?php echo CONFIG_PATH_SITE_USER; ?>');
    function checkpin()
    {
       
        var pin =document.getElementById("m_pin").value;
        // alert(pin);
        if(pin!="")
        {
        //alert(pin);
        $("#loadIndTool2").show();
        $.ajax({
            type: "GET",
            url:'<?php echo $url1;?>',
            data: "pin=" + pin,
                    error: function () {

                    },
            success: function (msg) {
                if(msg==1)
                {
                    $("#game").html('<button type="submit" class="btn btn-success"><i class="icon-ok-sign"></i>Submit_&_Continue</button>');
                }
                else
                {
                    alert('PIN IS INCORRECT');
                     $("#loadIndTool2").hide();
                    
                }

               

            }
        });

        }
        else
        {
            alert('enter master pin');
        }
    }
    </script>