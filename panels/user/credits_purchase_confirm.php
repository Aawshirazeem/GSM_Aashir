<?php
defined("_VALID_ACCESS") or die("Restricted Access");

$credits = $request->postFloat("credits");
$creditType = $request->postFloat("creditType");
$creditType2 = $request->postFloat("creditType2");


if ($credits == 0) {
    header('location:' . CONFIG_PATH_SITE_USER . 'credits_purchase.html?reply=' . urlencode('reply_fill_credits'));
    exit();
}

// if credit type is 7 then calculte bitcoin to pay

$sql_curr = 'select cm.id, cm.prefix, cm.suffix, cm.rate,cm.currency,cm.id,
    					cmd.id as defaultId, cmd.prefix as defaultPrefix, cmd.suffix as defaultSuffix
    					from ' . USER_MASTER . ' um 
    					left join ' . CURRENCY_MASTER . ' cm on (cm.id = um.currency_id)
    					left join ' . CURRENCY_MASTER . ' cmd on (cmd.is_default=1)
    					where um.id=' . $member->getUserId();
$currencies = $mysql->getResult($sql_curr);
$curr = $currencies['RESULT'][0];
$curr['rate'] = 1;
$prefix = $curr['prefix'];
$suffix = $curr['suffix'];
$cursign = $curr['currency'];
$defaultPrefix = $curr['defaultPrefix'];
$defaultSuffix = $curr['defaultSuffix'];
$curr_id=$curr['id'];

//calcuie staohse if


$sql_gw = 'select gm.*,um.pg_paypal,um.pg_moneybookers
								from ' . GATEWAY_MASTER . ' gm
								left join ' . GATEWAY_DETAILS . ' gd on (gm.id = gd.gateway_id)
								left join ' . USER_MASTER . ' um on (um.id = gd.user_id)
					where gm.id=' . $creditType;

$query_gw = $mysql->query($sql_gw);
$rows_gw = $mysql->fetchArray($query_gw);
$row_gw = $rows_gw[0];

$min = $row_gw['min'];
$max = $row_gw['max'];

$outOfRange = 0;
if (($min > 0 || $max > 0) && $credits > 0) {
    if (($min == 0 && $credits > $max) && $outOfRange == 0) {
        $outOfRange = 1;
    }
    if (($max == 0 && $credits < $min) && $outOfRange == 0) {
        $outOfRange = 2;
    }
    if (($credits > $max || $credits < $min) && $outOfRange == 0) {
        $outOfRange = 3;
    }
}
if ($outOfRange != 0) {
    header('location:' . CONFIG_PATH_SITE_USER . 'credits_purchase.html?reply=' . urlencode('reply_out_of_range'));
    exit();
}
//$per = $row_gw['charges'];
switch ($creditType2) {
    case 1:
        $per = (($row_gw['pg_paypal'] != '' and $row_gw['pg_paypal'] != 0) ? $row_gw['pg_paypal'] : $row_gw['charges']);
        break;
    case 3:
        $per = (($row_gw['pg_moneybookers'] != '' and $row_gw['pg_moneybookers'] != 0) ? $row_gw['pg_moneybookers'] : $row_gw['charges']);
        break;
    default:
        $per = $row_gw['charges'];
}


if ($creditType2 == 7) {
    $chargess = round(($credits / $curr['rate']) * $per / 100, 2);
    $creditsafterchares = $credits + $chargess;
    $url = "https://blockchain.info/tobtc";
    //$currency="EUR";
    //$moneyy=600;
    $parameters = "currency=" . $cursign . "&value=" . $creditsafterchares;
    $response = file_get_contents($url . '?' . $parameters);
    $object = json_decode($response);
    // echo $object.'<br>';
    $bitcoinamunt = round($object, 4);
}
?>

<div class="panel panel-default">
    <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credit_purchase_confirm')); ?></div>
    <table class="table table-striped table-hover">
        <tr>
            <th width="50%" style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_particulars')); ?></th>
            <th><?php $lang->prints('com_details'); ?></th>
        </tr>

        <tr>
            <td style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credit_amount')); ?></td>
            <td><b><?php
$tempRate = round($credits * $curr['rate'], 2);
echo $objCredits->printCredits($tempRate, $prefix, $suffix);
?></b></td>
        </tr>
        <tr>
            <td style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_charges')); ?></td>
            <td><b><?php echo $per . '%'; ?></b></td>
        </tr>

        <tr>
            <td style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_details')); ?></td>
            <td><b><?php echo nl2br($row_gw['details']); ?></b></td>
        </tr>

        <tr>
            <td style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_payment_charges')); ?></td>
            <td><b><?php
                    $charges = round(($credits / $curr['rate']) * $per / 100, 2);
                    echo $objCredits->printCredits($charges, $prefix, $suffix);
                    ?></b></td>
        </tr>
        <?php
        if ($creditType2 == 7) {
            ?>
            <tr>
                <td style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_amount_to_pay')); ?></td>
                <td><b><?php
                    $topay = $bitcoinamunt;
                    echo $bitcoinamunt.' bitcoin ';
                    ?></b></td>
            </tr>
            <?php
        } else {
            ?>
            <tr>
                <td style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_amount_to_pay')); ?></td>
                <td><big><?php
            $topay = round($charges + ($credits / $curr['rate']), 2);
            echo $objCredits->printCredits($topay, $prefix, $suffix);
            ?></big></td>
            </tr>
<?php } ?>
    </table>
    <center>
       
        <form method="post" action="<?php echo CONFIG_PATH_SITE_USER; ?>credits_purchase_confirm_payment.html">
            <input type="hidden" name="amount" value="<?php echo $mysql->getFloat($topay) ?>">
            <input type="hidden" name="credits" value="<?php echo $mysql->getFloat($credits) ?>">
            <input type="hidden" name="creditType" value="<?php echo $mysql->getInt($creditType) ?>">
             <input type="hidden" name="creditType2" value="<?php echo $mysql->getInt($creditType2) ?>">
            <input type="hidden" name="currency_id" value="<?php echo $mysql->getInt($curr_id) ?>">
            <div class="panel-footer">
                <a class="btn btn-danger" href="<?php echo CONFIG_PATH_SITE_USER; ?>credits_purchase.html"><i class="icon-remove"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cancel')); ?></a>
                <button type="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_confirm')); ?></button>
            </div>
        </form>
        <?php
        /* 		//Generate Invoice Number
          $sql_inv = 'insert into ' . INVOICE_REQUEST . ' (user_id, amount, credits, gateway_id, date_time, currency_id, status) values
          (' . $member->getUserId() . ', ' . $mysql->getFloat($topay) . ', ' . $mysql->getFloat($credits) . ', ' . $mysql->getInt($creditType) . ', now(), ' . $mysql->getInt($row_curr['id']) . ', 0)';
          $query_inv = $mysql->query($sql_inv);
          $invoice_id = $mysql->insert_id();

          $sql_gateway = 'select * from ' . GATEWAY_MASTER . ' where id =' . $mysql->getInt($creditType);
          $query_gateway = $mysql->query($sql_gateway);
          if($mysql->rowCount($query_gateway) > 0)
          {
          $rows_gateway = $mysql->fetchArray($query_gateway);
          $row_gateway = $rows_gateway[0];
          switch($creditType)
          {
          case "1":
          if($row_gateway['demo_mode'] == "0")
          {
          echo '
          <form name="paypalIPN" id="paypalIPN" method="post" action="https://www.paypal.com/cgi-bin/webscr" class="formSkin">';
          }
          else
          {
          echo '
          <form name="depositform" id="depositform" method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr" class="formSkin">';
          }
          echo '
          <input type="hidden" name="rm" value="2"/>
          <input type="hidden" name="cmd" value="_xclick"/>
          <input type="hidden" name="business" value="' . $row_gateway['gateway_id'] . '"/>
          <input type="hidden" name="item_name" value="PAY INVOICE No ' . $invoice_id . '"/>
          <input type="hidden" name="amount" value="' . $topay . '"/>
          <input type="hidden" name="no_shipping" value="1"/>
          <input type="hidden" name="return" value="' . CONFIG_PATH_SITE_USER . 'dashboard.html"/>
          <input type="hidden" name="notify_url" value="' . CONFIG_PATH_SITE_USER . 'paypal_api.do"/>
          <input type="hidden" name="cancel_return" value="' . CONFIG_PATH_SITE_USER . 'dashboard.html"/>
          <input type="hidden" name="custom" value="' . base64_encode($invoice_id) . '" />
          <input type="hidden" name="currency_code" value="' . $row_curr['currency'] . '"/>
          <input type="hidden" name="country" value="US"/>
          <p class="butSkin" style="font-size:18px;">
          <input name="submit" type="submit" class="button" value="'.$lang->get('lbl_confirm_and_continue').' &raquo;" />
          </p>
          </form>';
          break;
          case "2":
          $content = $row_gateway['gateway_id'] . "\t" . $topay . "\t" . 'USD';
          $filename = 'mass.txt';
          echo '
          <form name="massForm" id="massForm" method="post" action="' . CONFIG_PATH_SITE_USER . 'download.do" class="formSkin">
          <input type="hidden" name="content" value="' . $content . '"/>
          <input type="hidden" name="filename" value="' . $filename . '"/>
          <p class="butSkin" style="font-size:18px;">
          <input name="submit" type="submit" class="button" value="'.$lang->get('lbl_download_file').'" />
          </p>
          </form>';

          break;
          case "3":
          echo '
          <form action="https://www.moneybookers.com/app/payment.pl"  name="money_booker" method="post" class="formSkin">
          <input type="hidden" name="transaction_id" value="703">
          <input type="hidden" name="pay_to_email" value="' . $row_gateway['gateway_id'] . '">
          <input type="hidden" name="language" value="EN">
          <input type="hidden" name="merchant_fields" value="Cr Purchase">

          <input type="hidden" name="return_url" value="">
          <input type="hidden" name="cancel_url" value="">
          <input type="hidden" name="status_url" value="">
          <input type="hidden" name="amount_description" value="Cr Purchase">
          <input type="hidden" name="amount" value="' . $topay . '">
          <input type="hidden" name="currency" value="GBP">
          <input type="hidden" name="detail1_description" value="Cr Purchase">
          <input type="hidden" name="detail1_text" value="">
          <input type="hidden" name="confirmation_note" value="Thank you">

          <input type="hidden" name="customer_id" value="3234">
          <input type="hidden" name="session_id" value="">
          <input type="hidden" name="confirmation_note" value="Your payment is ok. Thank you!" />
          <p class="butSkin" style="font-size:18px;">
          <input name="submit" type="submit" class="button" value="'.$lang->get('lbl_confirm_and_continue').' &raquo;" />
          </p>

          </form>';
          break;
          case "4":
          //echo '<img src="' . CONFIG_PATH_IMAGES . 'pg_email.png" alt="Email" width="32" height="32" id="addCredits" />';
          break;
          }// End Switch
          }//End if (is there any credit type selected) */
        ?> 
</div>
</div>

</center>