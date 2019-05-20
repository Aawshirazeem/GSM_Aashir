<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$member->checkLogin();
$member->reject();
$amount = $request->postFloat("amount");
$credits = $request->postFloat("credits");
$creditType = $request->postFloat("creditType");
$creditType2 = $request->postFloat("creditType2");
$currency_id = $request->postFloat("currency_id");
include_once "SermepaException.php";
include_once "SermepaInterface.php";
include_once "Sermepa.php";

include_once("config.php");
use CommerceRedsys\Payment\Sermepa;

if ($credits == 0 || $amount == 0 || $creditType == 0) {
    header('location:' . CONFIG_PATH_SITE_USER . 'credits_purchase.html?reply=' . urlencode('reply_something_wrong'));
    exit();
}


$sql_curr = 'select cm.id, cm.prefix, cm.suffix, cm.rate,
    					cmd.id as defaultId, cmd.prefix as defaultPrefix, cmd.suffix as defaultSuffix, cmd.currency
    					from ' . USER_MASTER . ' um 
    					left join ' . CURRENCY_MASTER . ' cm on (cm.id = um.currency_id)
    					left join ' . CURRENCY_MASTER . ' cmd on (cmd.is_default=1)
    					where um.id=' . $member->getUserId();

$currencies = $mysql->getResult($sql_curr);
$curr = $currencies['RESULT'][0];
$cursign = $curr['currency'];


// echo '<pre>';
// var_dump($curr);exit;
$curr['rate'] = 1;
if ($curr['prefix'] == '$') {
    $curr['currency'] = 'USD';
} else if ($curr['prefix'] == '£') {
    $curr['currency'] = 'GBP';
}
$prefix = $curr['prefix'];
$suffix = $curr['suffix'];
$defaultPrefix = $curr['defaultPrefix'];
$defaultSuffix = $curr['defaultSuffix'];

$sql_gw = 'select gm.*,um.pg_paypal,um.pg_moneybookers
								from ' . GATEWAY_MASTER . ' gm
								left join ' . GATEWAY_DETAILS . ' gd on (gm.id = gd.gateway_id)
								left join ' . USER_MASTER . ' um on (um.id = gd.user_id)
					where gm.id=' . $creditType;

$query_gw = $mysql->query($sql_gw);
$rows_gw = $mysql->fetchArray($query_gw);
$row_gw = $rows_gw[0];

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


// calulcate bitcoin amount again

if ($creditType2 == 7) {

    $PNG_TEMP_DIR = CONFIG_PATH_EXTRA_ABSOLUTE . DIRECTORY_SEPARATOR . 'temp/';
    //echo $PNG_TEMP_DIR;
    $PNG_WEB_DIR = 'temp/';
    require_once CONFIG_PATH_SYSTEM_ABSOLUTE . 'classes/full/qrlib.php';
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    $filenameqrcode = $PNG_TEMP_DIR . 'test.png';
    $errorCorrectionLevel = 'Q';
    $matrixPointSize = 8;
    $filenameqrcode = $PNG_TEMP_DIR . 'test' . md5('abcd' . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
    // echo $filename;exit;
    //exit;
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
    $amount = $bitcoinamunt;
}
//-------------------------


$sql_user = 'select
							um.first_name, um.last_name, um.address, um.city, um.phone,
							cm.countries_name as country
						from ' . USER_MASTER . ' um
						left join ' . COUNTRY_MASTER . ' cm on (um.country_id = cm.id)
						where um.id=' . $member->getUserID() . '';
$query_user = $mysql->query($sql_user);
$first_name = $last_name = $address = $city = $phone = $country = '';
if ($mysql->rowCount($query_user) > 0) {
    $rows_user = $mysql->fetchArray($query_user);
    $row_user = $rows_user[0];
    $first_name = $row_user['first_name'];
    $last_name = $row_user['last_name'];
    $address = $row_user['address'];
    $city = $row_user['city'];
    $phone = $row_user['phone'];
    $country = $row_user['country'];
}
?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('doc_title')); ?></div>
    <table class="table table-striped table-hover">
        <tr>
            <th width="50%" style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_particulars')); ?></th>
            <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_details')); ?></th>
        </tr>

        <tr>
            <td style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_credit_amount')); ?></td>
            <td><b><?php
                    $tempRate = round($credits * $curr['rate'], 2);
                    echo $objCredits->printCredits($tempRate, $prefix, $suffix);
                    ?></b></td>
        </tr>
        <tr>
            <td style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_charges')); ?></td>
            <td><b><?php echo $per . '%'; ?></b></td>
        </tr>
        <tr>
            <td style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_details')); ?></td>
            <td><b><?php echo nl2br($row_gw['details']); ?></b></td>
        </tr>

        <tr>
            <td style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_payment_charges')); ?></td>
            <td><b><?php
                    $charges = round(($credits / $curr['rate']) * $per / 100, 2);
                    echo $objCredits->printCredits($charges, $prefix, $suffix);
                    ?></b></td>
        </tr>
<!--        <tr>
            <td style="text-align:right"><?php $lang->prints('lbl_amount_to_pay'); ?></td>
            <td><b><?php
        $topay = round($charges + ($credits / $curr['rate']), 2);
        echo $objCredits->printCredits($topay, $prefix, $suffix);
        ?></b></td>
        </tr>-->
        <?php
        if ($creditType2 == 7) {
            ?>
            <tr>
                <td style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_amount_to_pay')); ?></td>
                <td><b><?php
                        $topay = round($charges + ($credits / $curr['rate']), 2);
                        echo $amount . ' bitcoin ';
                        ?></b></td>
            </tr>
            <?php
        } else {
            ?>
            <tr>
                <td style="text-align:right"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_amount_to_pay')); ?></td>
                <td><big><?php
                $topay = round($charges + ($credits / $curr['rate']), 2);
                echo $objCredits->printCredits($topay, $prefix, $suffix);
                ?></big></td>
            </tr>
        <?php } ?>
    </table>
    <center>
        <?php
//Generate Invoice Number
        $sql_inv = 'insert into ' . INVOICE_REQUEST . ' (user_id, amount, credits,bitamount, gateway_id, date_time, currency_id, status) values
		    				(' . $member->getUserId() . ', ' . $mysql->getFloat($topay) . ', ' . $mysql->getFloat($credits) . ', ' . $mysql->getFloat($amount) . ',' . $mysql->getInt($creditType) . ', now(), ' . $mysql->getInt($curr['id']) . ', 0)';
        $query_inv = $mysql->query($sql_inv);
        $invoice_id = $mysql->insert_id();

        $sql_gateway = 'select * from ' . GATEWAY_MASTER . ' where id =' . $mysql->getInt($creditType);
        $query_gateway = $mysql->query($sql_gateway);
        if ($mysql->rowCount($query_gateway) > 0) {
            $rows_gateway = $mysql->fetchArray($query_gateway);
            $row_gateway = $rows_gateway[0];
            switch ($creditType2) {
                case "1":
                    if ($row_gateway['demo_mode'] == "0") {
                        echo '
								<form name="paypalIPN" id="paypalIPN" method="post" action="https://www.paypal.com/cgi-bin/webscr">';
                    } else {
                        echo '
								<form name="depositform" id="depositform" method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr" class="formSkin">';
                    }
                    echo '
							<input type="hidden" name="cmd" value="_xclick"/>
							<input type="hidden" name="business" value="' . $row_gateway['gateway_id'] . '"/>
							<input type="hidden" name="item_name" value="PAY INVOICE No ' . $invoice_id . '"/>
							<input type="hidden" name="amount" value="' . $topay . '"/>
							<input type="hidden" name="no_note" value="1">
							<input type="hidden" name="no_shipping" value="1"/>
							
							<input type="hidden" name="first_name" value="' . $first_name . '">
							<input type="hidden" name="last_name" value="' . $last_name . '">
							<input type="hidden" name="address1" value="' . $address . '">
							<input type="hidden" name="city" value="' . $city . '">
							<input type="hidden" name="country" value="' . $country . '">
							<input type="hidden" name="phone_number" value="' . $phone . '">
							<input TYPE="hidden" name="charset" value="UTF-8">
							
							<input type="hidden" name="return" value="http://' . CONFIG_DOMAIN . CONFIG_PATH_SITE_USER . 'dashboard.html"/>
							<input type="hidden" name="notify_url" value="http://' . CONFIG_DOMAIN . CONFIG_PATH_SITE_USER . 'credits_paypal_api.do"/>
							<input type="hidden" name="cancel_return" value="http://' . CONFIG_DOMAIN . CONFIG_PATH_SITE_USER . 'dashboard.html"/>
							<input type="hidden" name="custom" value="' . $invoice_id . '" />  
							<input type="hidden" name="currency_code" value="' . $curr['currency'] . '"/>
							<input type="hidden" name="bn" value="GSMMARKET"/>
							<input type="hidden" name="rm" value="2"/>
							<p style="font-size:18px;">
								<input name="submit" type="submit" class="btn btn-warning" value="' . $lang->get('lbl_pay_now') . '" />
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
								<p style="font-size:18px;">
									<input name="submit" type="submit" class="btn btn-warning" value="' . $lang->get('lbl_download_file') . '" />
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
									<input type="hidden" name="currency" value="' . $curr['currency'] . '">
									<input type="hidden" name="detail1_description" value="Cr Purchase">
									<input type="hidden" name="detail1_text" value="">
									<input type="hidden" name="confirmation_note" value="Thank you">
							
									<input type="hidden" name="customer_id" value="' . $member->getUserId() . '">
									<input type="hidden" name="session_id" value="">
									<input type="hidden" name="confirmation_note" value="Your payment is ok. Thank you!" />
									<p style="font-size:18px;">
										<input name="submit" type="submit" class="btn btn-warning" value="' . $lang->get('lbl_pay_now') . ' &raquo;" />
									</p>
  
									</form>';
                    break;
                case "7":
                    //echo '<img src="' . CONFIG_PATH_IMAGES . 'pg_email.png" alt="Email" width="32" height="32" id="addCredits" />';
                    // make the url for payment
                    // echo 'heello';
                    $api_key = $xpub = '';

                    if (sizeof(explode(':', $row_gateway['gateway_id'])) == 2) {
                        list($api_key, $xpub) = explode(':', $row_gateway['gateway_id']);
                    }

                    $api_key = $api_key;
                    $xpub = $xpub;
                    $secret = CONFIG_SALT; //this can be anything you want
                    $orderID = $invoice_id;
                    //$callback_url = $rootURL . "/callback.php?invoice=" . $orderID . "&secret=" . $secret;
                    $callback_url = 'http://' . $_SERVER['SERVER_NAME'] . CONFIG_PATH_SITE_USER . "callback.do?invoice=" . $orderID . "&secret=" . $secret;
                    $receive_url = "https://api.blockchain.info/v2/receive?key=" . $api_key . "&xpub=" . $xpub . "&callback=" . urlencode($callback_url);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_URL, $receive_url);
                    $ccc = curl_exec($ch);
                    $json = json_decode($ccc, true);
                    // var_dump($json);
                    $payTo = $json['address']; //the newly created address will be stored under 'address' in the JSON response
                    echo '<b>PAY US WITH THAT ADDRESS: </b>' . $payTo; //echo out the newly created receiving address
                    echo '<br>';
                    //echo ' <a href="https://blockchain.info/address/'.$json['address'].'" class="btn btn-success" target="_blank">Pay Now</a>';
                    //$payTo="abcdddd";
                    QRcode::png($payTo, $filenameqrcode, $errorCorrectionLevel, $matrixPointSize, 2);
                    //  QRcode::png('abcd', $filename, $errorCorrectionLevel, $matrixPointSize, 2);
                    // echo basename($filename);exit;
                    echo '<img alt="QR CODE" style="text-align: right" src="' . CONFIG_PATH_EXTRA . 'temp/' . basename($filenameqrcode) . '" /><hr/>';
                    break;
                case "6":


                    $fuc = $key = '';
                    $redsys_cur = $redsys_ter = 0;
                    if (sizeof(explode(':', $row_gateway['gateway_id'])) == 5) {
                        list($fuc, $key, $user, $redsys_ter, $redsys_cur) = explode(':', $row_gateway['gateway_id']);
                    }
                    //329874119:VM8O304S7R52293U:IMEI.pk:GsmMarket
                    /*
                     * fuc=329874119
                     * key=VM8O304S7R52293U
                     * company=IMEI.pk
                     * user=GsmMarket
                     * 
                     */
                    //----------------new------------------
                    if ($row_gateway['demo_mode'] == "0")
                        $env = 'live';
                    else
                        $env = 'test';
                    try {


                        $settings = array(
                            'name' => $user,
                            'merchantCode' => $fuc,
                            'merchantPassword' => $key,
                            'terminal' => $redsys_ter,
                            'environment' => $env,
                        );

                        // Create a new instance and initialize it.
                        $gateway = new Sermepa($settings['name'], $settings['merchantCode'], $settings['terminal'], $settings['merchantPassword'], $settings['environment']);

                        // Load the payment from ???? and set the necessary values.
                        $topay = $topay * 100;
                        $amount = (int) $topay;
                        $currency = $redsys_cur;
                        $payment_id = '000' . $invoice_id;
                        $product_description = 'Credits Purchase';
                        $consumer_language = '001';
                        $transaction_type = 0;
                        $feedback_url = 'http://' . $_SERVER['SERVER_NAME'] . CONFIG_PATH_SITE_USER . 'notificacion.do';
                        $ko_url = 'http://' . $_SERVER['SERVER_NAME'] . CONFIG_PATH_SITE_USER . 'ko.html';
                        $ok_url = 'http://' . $_SERVER['SERVER_NAME'] . CONFIG_PATH_SITE_USER . 'ok.html';

                        $gateway->setAmount($amount)
                                ->setCurrency($currency)
                                ->setOrder(substr(date('ymdHis') . 'Id' . $payment_id, -12, 12))
                                ->setProductDescription($product_description)
                                ->setConsumerLanguage($consumer_language)
                                ->setMerchantData($payment_id)
                                ->setTransactionType($transaction_type)
                                ->setMerchantURL($feedback_url)
                                ->setUrlKO($ko_url)
                                ->setUrlOK($ok_url);
//echo 'jere';
                        // Get the trasaction fields for the sermepa form.
                        $parameters = $gateway->composeMerchantParameters();
                        if ($parameters) {
                            $languages = $gateway->getAvailableConsumerLanguages();
                            $currencies = $gateway->getAvailableCurrencies();
                            $transaction_types = $gateway->getAvailableTransactionTypes();
                            $output = ' ';
                            $output .= '<form action="' . $gateway->getEnvironment() . '" method="POST" id="' . $gateway->getOrder() . '">';
                            $output .= '<input type="hidden" name="Ds_SignatureVersion" value="' . $gateway->getSignatureVersion() . '">';
                            $output .= '<input type="hidden" name="Ds_MerchantParameters" value="' . $parameters . '">';
                            $output .= '<input type="hidden" name="Ds_Signature" value="' . $gateway->composeMerchantSignature() . '">';
                            $output .= '<input  class="btn btn-success" type="submit" value="Send">';
                            $output .= '</p>';
                            $output .= '</p>';
                            $output .= '</form><br />';
                        } else {
                            $output = '        <h1>Error</h1><p>Failed collecting all information necessary to send to Sermepa.</p><p>Please check your settings and/or data.</p><br />';
                        }


                        echo $output;
                    } catch (SermepaException $e) {
                        echo $e;
                    }


                    //-----------------new end-------------
                    break;
                case "8":
                    $amount = (float) $topay;
                    $output = ' ';
                    $output .= '<form method="post" action="process.do?paypal=checkout">';
                    $output .= '<input type="hidden" name="itemname" value="Credit Buy" />';
                    $output .= '<input type="hidden" name="itemnumber" value="' . $invoice_id . '" />';
                    $output .= '<input type="hidden" name="itemdesc" value="Use For Buy Credit" /> ';
                    $output .= '<input type="hidden" name="itemprice" value="' . $amount . '" /> ';
                    $output .= '<input type="hidden" name="itemQty" value="1" /> ';
                    $output .= '<input type="image" name="submit" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" border="0" align="top" alt="Check out with PayPal"/>';
                    $output .= '</form>';
                    echo $output;
                    break;
            }// End Switch
        }//End if (is there any credit type selected) 
        ?>
    </center>
</div>