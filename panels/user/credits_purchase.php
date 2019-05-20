<?php
defined("_VALID_ACCESS") or die("Restricted Access");






$sql_curr = 'select cm.id, cm.prefix, cm.suffix, cm.rate,
    					cmd.id as defaultId, cmd.prefix as defaultPrefix, cmd.suffix as defaultSuffix
    					from ' . USER_MASTER . ' um 
    					left join ' . CURRENCY_MASTER . ' cm on (cm.id = um.currency_id)
    					left join ' . CURRENCY_MASTER . ' cmd on (cmd.is_default=1)
    					where um.id=' . $member->getUserId();
$sql_curr= 'select cm.id, cm.prefix, cm.suffix, cm.rate, cm.id as defaultId, cm.prefix as defaultPrefix, 
cm.suffix as defaultSuffix 
    					from ' . USER_MASTER . ' um 
    					left join ' . CURRENCY_MASTER . ' cm on (cm.id = um.currency_id)
    					
    					where um.id=' . $member->getUserId();
$currencies = $mysql->getResult($sql_curr);
$curr = $currencies['RESULT'][0];
//  echo '<pre>';
//  var_dump($curr);exit;
?>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init.js" ></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init_mmember.js" ></script>





<form action="<?php echo CONFIG_PATH_SITE_USER; ?>credits_purchase_confirm.html" method="post">
    <div class="row" style="">


        <div class="col-md-12">





            <?php
            $sql_pg_c = 'select * from ' . GATEWAY_DETAILS . ' where user_id=' . $member->getUserID();
            $query_pg_c = $mysql->query($sql_pg_c);
            $default = 1;
            if ($mysql->rowCount($query_pg_c) > 0) {
                ?>
                <div class="col-md-5">



                    <div class="col-md-7">
                        <h4 style="font-weight: bold; font-size: 24px; color: #666666;">Credits :</h4>
                        <p style="font-size: 11px; color: #999999;">Please enter here the number of credits you want to buy</p>
                    </div>
                    <div class="col-md-5">
                        <input id="credits"  name="credits" style="background-color: #FFFFFF; width: 155px; font-size: 30px; font-weight: bold; text-align: center; border: 1px solid #EEEEEE; color: #666666 !important;" placeholder="0" maxlength="5" onkeyup="" type="text">
                    </div>


                    <table class="table table-striped table-hover">
                        <tr id="trRange" class="hidden">
                            <td><b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_credit_range')); ?></b></td>
                            <td id="valRange">-<span class="label label-danger hidden" id="lblOutOfRange"><?php echo $admin->wordTrans($admin->getUserLang(), 'Out of Range'); ?></span></td>
                        </tr>

                        <tr>
                            <td><b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_credit_amount')); ?></b></td>
                            <td id="valCr" style="font-weight:bold">-</td>
                        </tr>
                        <tr>
                            <td><b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_charges')); ?></b></td>
                            <td id="valPer">-</td>
                        </tr>
                        <tr>
                            <td><b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_payment_charges')); ?></b></td>
                            <td id="valCharges" style="font-weight:bold">-</td>
                        </tr>
                        <tr>
                            <td><b><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_to_pay')); ?></b></td>
                            <td><b class="M0 P0" id="valToPay">-</b></td>
                        </tr>
                    </table>	

                    <input type="submit" class="btn btn-success" value="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_request_fund')); ?>" />



                </div>
				<div class="col-md-7">
                <div class="form-group">
    <!--                                    <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_enter_credits_to_request')); ?></label>
                    <input type="text" class="form-control" name="credits" id="credits" value="0"/>-->
                    <input type="hidden" name="prefix" id="prefix" value="<?php echo $curr['prefix'] ?>" >
                    <input type="hidden" name="suffix" id="suffix" value="<?php echo $curr['suffix'] ?>" >
                    <input type="hidden" name="defaultPrefix" id="defaultPrefix" value="<?php echo $curr['defaultPrefix'] ?>" >
                    <input type="hidden" name="defaultSuffix" id="defaultSuffix" value="<?php echo $curr['defaultSuffix'] ?>" >
                    <input type="hidden" name="rate" id="rate" value="<?php echo $curr['rate'] ?>" >
                    <input type="hidden" name="prefix_default" id="prefix_default" value="<?php echo $curr['prefix'] ?>" >
                    <input type="hidden" name="suffix_default" id="suffix_default" value="<?php echo $curr['suffix'] ?>" >
                </div>
                <?php
                $default = 0;
                $sql_gw = 'select gm.*,um.pg_paypal,um.pg_moneybookers
												from ' . GATEWAY_DETAILS . ' gd
												left join ' . GATEWAY_MASTER . ' gm on (gm.id = gd.gateway_id)
												left join ' . USER_MASTER . ' um on (um.id = gd.user_id)
												where gm.m_id in(1,2,5,6,7,8) and  gd.user_id=' . $member->getUserID();

                //  echo $sql_gw;
                $query_gw = $mysql->query($sql_gw);
                $rows_gw = $mysql->fetchArray($query_gw);
                $i = 0;
                foreach ($rows_gw as $row_gw) {
                    $i++;
                    echo '
										<div class="col-sm-12">
												<label class="alert alert-success" style="width:100%;font-weight:normal">
													<input type="hidden" value="' . $row_gw['min'] . '" name="min" id="min' . $row_gw['id'] . '" class="creditsTransferType checkbox-inline"  />
													
													<input type="hidden" value="' . $row_gw['max'] . '" name="max" id="max' . $row_gw['id'] . '" class="creditsTransferType checkbox-inline"  />
													<input type="radio"  name="creditType" class="creditsTransferType checkbox-inline" value="' . $row_gw['id'] . '" ' . (($i == 1) ? ('checked="checked"') : '') . ' onclick="setval(' . $row_gw['m_id'] . ');" />
													
                                                                                                         <img width="90px" height="auto" alt="NA" src="' . CONFIG_PATH_SITE . 'images/' . $row_gw['logo'] . '"/>
                                                                                                       
													<span style="float:right; color:#7d7d7d">Payment fee : <em style="color: #ff6600; font-size: 13px; font-weight: bold; font-style:normal">+ ' . $row_gw['charges'] . ' %</em></span>
													<p style="color:#404040;"> ' . $row_gw['details'] . ' </p>
													';
                    switch ($row_gw['id']) {
                        case 1:
                            echo '<input type="hidden" name="charges' . $row_gw['id'] . '"  value="' . (($row_gw['pg_paypal'] != '' and $row_gw['pg_paypal'] != 0) ? $row_gw['pg_paypal'] : $row_gw['charges']) . '" >';
                            break;
                        case 3:
                            echo '<input type="hidden" name="charges' . $row_gw['id'] . '" value="' . (($row_gw['pg_moneybookers'] != '' and $row_gw['pg_moneybookers'] != 0) ? $row_gw['pg_moneybookers'] : $row_gw['charges']) . '" >';
                            break;
                        default:
                            echo '<input type="hidden" name="charges' . $row_gw['id'] . '" value="' . $row_gw['charges'] . '" />';
                    }
                    echo ' 
												</label>
											</div>
										';
                }
                echo ' <input type="hidden" value="" name="creditType2" id="creditType2" />';
            }
            else
                echo '<h3>NO Payment Gateway Is Enable</h3>';
            ?>






</div>



        </div>

    </div>
</form>

<script type="text/javascript">
 
    
setval($('input[name=creditType]:checked').val());
console.log( $("#creditType2").val());
    function setval(a)
    {
        // alert(a);
        // $("#creditType2").val(a);
        $("input[id=creditType2]").val(a);
    }

</script>