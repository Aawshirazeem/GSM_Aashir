<?php
// Set flag that this is a parent file
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


// check whch one to show..
$temptype = $_GET['server_log'];
$temptype = preg_split('[-]', $temptype);
$mytpe = $temptype[1];
//   echo $mytpe; if my type is equal to 1 then server log and if 2 then file log if empty nothing
// var_dump($temptype);
//  exit;
$member->checkLogin();
$member->reject();

if ($mytpe == 1) {

    $server_log = $request->getInt('server_log');



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
				where slm.id=' . $server_log . '
				order by slm.server_log_name';
    $result = $mysql->getResult($sql);
    if ($result['COUNT'] == 0) {
        ?>
        <p class="field hidden">
            <label>Custom Field</label>
            <input type="text" name="custom" id="custom" class="textbox_fix" value="">
        </p>
        <?php
    } else {
        $row = $result['RESULT'][0];
        $prefix = $row['prefix'];
        $suffix = $row['suffix'];
        $amount = $mysql->getFloat($row['amount']);
        $amountSpl = $mysql->getFloat($row['splCr']);
        $amountPackage = $mysql->getFloat($row['packageCr']);
        $amountDisplay = $amountDisplayOld = $amount;

        $isSpl = false;
        if ($row["splres"] == "") {
            if ($amountSpl > 0) {
                $isSpl = true;
                $amountDisplay = $amountSpl;
            }
            if ($amountPackage > 0) {
                $isSpl = true;
                $amountDisplay = $amountPackage;
            }
        } else {
            $isSpl = false;
            $amountDisplay = $mysql->getFloat($row["splres"]);
        }

        $delivery_time = $row['delivery_time'];
        ?>
        <div class="clear"></div>
        <div>
            <?php
            $msgCr = 'Price: ' . (($isSpl == true) ? '<del>' . $objCredits->printCredits($amountDisplayOld, $prefix, $suffix) . '</del> <b>' . $objCredits->printCredits($amountDisplay, $prefix, $suffix) . '</b>' : $objCredits->printCredits($amountDisplay, $prefix, $suffix));
            $msgCr .= (($row['delivery_time'] != '') ? ' | ' . $row['delivery_time'] : '');
            $graphics->messagebox($msgCr);
            ?>
        </div>
        <div class="<?php echo (($row['info'] == '') ? ' hidden' : ''); ?>">
            <?php $graphics->messagebox(nl2br($row['info'])); ?>
        </div>
        <p class="form-group<?php echo (($row['custom_field_name'] == '') ? ' hidden' : ''); ?>">
            <label><?php echo $row['custom_field_name'] ?></label>
            <input type="text" name="custom" id="custom" class="form-control" value="<?php echo $row['custom_field_value'] ?>">
        <p class="help-block"><?php echo $row['custom_field_message'] ?></p>
        </p> 
        <div class="form-group<?php echo (($row['chimera'] == '0') ? ' hidden' : ''); ?>">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),'Amount'); ?></label>
            <input type="text" name="amount" id="amount" onkeyup="calc();" class="form-control" value="" >
            <input type="hidden" name="chimera" id="custom" class="form-control" value="<?php echo $row['chimera']; ?>">
            <input type="hidden" name="price" id="price" value="<?php echo $amountDisplay; ?>" />
            <input type="hidden" name="total_amount" id="total_amount" />
        </div>                                         <label id="result" style="display:none;color:red;"><?php echo $admin->wordTrans($admin->getUserLang(),'Total Amount'); ?>:</label>
        <?php
        if ($row['is_custom'] == 1) {
            
            echo '<div id="c_fields">';
            // show some custom fields now
            $sql1 = 'select * from nxt_custom_fields a where a.s_type=2 and a.s_id='.$server_log;
            $result = $mysql->getResult($sql1);
            $f_temp = 1;
            foreach ($result['RESULT'] as $row2) {

                if ($row2['f_type'] == 1) {
                    // text box
                    echo '<br><label>' . $row2['f_name'] . '</label>';
                    echo '<input type="hidden" name="custom_name_' . $f_temp . '" value="' . $row2['f_name'] . '" />';
                    echo ' <input type="text" name="custom_' . $f_temp . '"  class="form-control" ' . (($row2['f_req'] == 1) ? 'required' : '') . ' placeholder="' . $row2['f_desc'] . '"/><br>';
                }
                if ($row2['f_type'] == 2) {

                    $ops = $row2['f_opt'];
                    $ops = explode(',', $ops);
                    // text box
                    echo '<br><label>' . $row2['f_name'] . '</label>';
                    echo '<input type="hidden" name="custom_name_' . $f_temp . '" value="' . $row2['f_name'] . '" />';
                    echo '<select ' . (($row2['f_req'] == 1) ? 'required' : '') . ' class="form-control"  name="custom_' . $f_temp . '">';
                    echo ' <option value="">' . $row2['f_desc'] . '</option>';
                    for ($a = 0; $a < count($ops); $a++) {
                        echo ' <option value="' . $ops[$a] . '">' . $ops[$a] . '</option>';
                    }

                    echo '</select><br>';
                    //echo  ' <input type="text" name="custom_1"  class="form-control" ' . (($row2['f_req'] == 1) ? 'required' : '') . ' placeholder="'.$row2['f_desc'].'"/>';
                }
                
                if($row2['f_type'] == 3)
                {
                    echo '<br><label>' . $row2['f_name'] . '</label><br>';
                    echo '<input type="hidden" name="custom_name_' . $f_temp . '" value="' . $row2['f_name'] . '" />';
                    echo '<label>' . $row2['f_desc'] . '</label>';
                    echo ' <input type="checkbox" ' . (($row2['f_req'] == 1) ? 'required' : '') . ' name="custom_' . $f_temp . '"/><br>';
                }
                $f_temp++;
            }
            echo '</div>';
        }
    }
} elseif ($mytpe == 2) {
    $prepaid_log = $request->getInt('server_log');

    $crM = $objCredits->getMemberCredits();
    $prefix = $crM['prefix'];
    $suffix = $crM['suffix'];
    $rate = $crM['rate'];

    $package_id = 0;
    $sql = 'select * from ' . PACKAGE_USERS . ' where user_id=' . $member->getUserId();
    $query = $mysql->query($sql);
    if ($mysql->rowCount($query) > 0) {
        $rows = $mysql->fetchArray($query);
        $package_id = $rows[0]['package_id'];
    }

    $sql = 'select
					slm.*,
					slsc.credits as splCr,
					pd.credits as packageCr
				from ' . PREPAID_LOG_MASTER . ' slm
				left join ' . PREPAID_LOG_SPL_CREDITS . ' slsc on (slm.id = slsc.log_id and slsc.user_id = ' . $member->getUserId() . ')
				left join ' . PACKAGE_PREPAID_LOG_DETAILS . ' pd on(slm.id = pd.prepaid_log_id and pd.package_id=' . $package_id . ')
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

    if ($result['COUNT'] == 0) {
        echo "";
    } else {
        $row = $result['RESULT'][0];
        $prefix = $row['prefix'];
        $suffix = $row['suffix'];
        $amount = $mysql->getFloat($row['amount']);
        $amountSpl = $mysql->getFloat($row['splCr']);
        $amountPackage = $mysql->getFloat($row['packageCr']);
        $amountDisplay = $amountDisplayOld = $amount;

        $isSpl = false;
        if ($row["splres"] == "") {
            if ($amountSpl > 0) {
                $isSpl = true;
                $amountDisplay = $amountSpl;
            }
            if ($amountPackage > 0) {
                $isSpl = true;
                $amountDisplay = $amountPackage;
            }
        } else {
            $isSpl = false;
            $amountDisplay = $mysql->getFloat($row["splres"]);
        }
        ?>
        <div class="clear"></div>
        <div>
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
            <?php echo nl2br($row['info']); ?>
                </div>
            </div>
        </div>
            <?php
        }
    } else {
        
    }
    ?>
