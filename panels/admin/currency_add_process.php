<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


$form_id = $request->PostStr('form_id');
$form_key = $request->PostStr('form_key');

$admin->checkLogin();
$admin->reject();
//$validator->formValidateAdmin('');

$currency = $request->PostStr('currency');
$prefix = $request->PostStr('prefix');
$suffix = $request->PostStr('suffix');
$rate = $request->PostStr('rate');


$sql = 'insert into ' . CURRENCY_MASTER . '
			(currency, prefix, suffix,status , rate)
			values(
			' . $mysql->quote($currency) . ',
			' . $mysql->quote($prefix) . ',
			' . $mysql->quote($suffix) . ',
                            1,
			' . $mysql->quote($rate) . ')';

$mysql->query($sql);
$newcurrencyid = $mysql->insert_id();
if ($newcurrencyid != "") {
    //echo $newcurrencyid;exit;
    // add rate to all services
    // get the default currenct id first
    $sql = 'select a.id from ' . CURRENCY_MASTER . ' a where a.is_default=1';
    $qrydata = $mysql->query($sql);
    if ($mysql->rowCount($qrydata) > 0) {
        $rows = $mysql->fetchArray($qrydata);
        $defaultCurrency = $rows[0]['id'];

        // now select all the IMEI servive ------------------------------
        $sql = 'select a.id from ' . IMEI_TOOL_MASTER . ' a';
        $qrydata = $mysql->query($sql);
        if ($mysql->rowCount($qrydata) > 0) {
            $rows = $mysql->fetchArray($qrydata);
            foreach ($rows as $row) {
                // now get the rates of that service
                $tool_id = $row['id'];
                $sql = 'select * from ' . IMEI_TOOL_AMOUNT_DETAILS . ' a where a.tool_id=' . $tool_id;
                $qrydata = $mysql->query($sql);
                if ($mysql->rowCount($qrydata) > 0) {
                    $rows = $mysql->fetchArray($qrydata);
                    foreach ($rows as $row) {
                        if ($row['currency_id'] == $defaultCurrency) {
                            $amounttomultipy = $row['amount'];
                            $amount_purchase_tomultipy = $row['amount_purchase'];
                        }
                    }
                    $amount = $rate * $amounttomultipy;
                    $amount_purchase = $rate * $amount_purchase_tomultipy;
                    // echo $amount;exit;
                    // now add new row for new currency
                    $sql = 'insert into ' . IMEI_TOOL_AMOUNT_DETAILS . '
						(tool_id, currency_id, amount , amount_purchase)
						values(
						' . $tool_id . ',
						' . $newcurrencyid . ',
						' . round($amount, 2) . ',
						' . round($amount_purchase, 2) . ')';
                    $mysql->query($sql);
                }
            }
        }

        // -----------------------------imei done-------------------------------
        // now select all the file servive 
        $sql = 'select a.id from ' . FILE_SERVICE_MASTER . ' a';
        $qrydata = $mysql->query($sql);
        if ($mysql->rowCount($qrydata) > 0) {
            $rows = $mysql->fetchArray($qrydata);
            foreach ($rows as $row) {
                // now get the rates of that service
                $tool_id = $row['id'];
                $sql = 'select * from ' . FILE_SERVICE_AMOUNT_DETAILS . ' a where a.service_id=' . $tool_id;
                $qrydata = $mysql->query($sql);
                if ($mysql->rowCount($qrydata) > 0) {
                    $rows = $mysql->fetchArray($qrydata);

                    foreach ($rows as $row) {

                        if ($row['currency_id'] == $defaultCurrency) {
                            $amounttomultipy = $row['amount'];
                            $amount_purchase_tomultipy = $row['amount_purchase'];
                        }
                    }
                    $amount = $rate * $amounttomultipy;
                    $amount_purchase = $rate * $amount_purchase_tomultipy;

                    // now add new row for new currency
                    $sql = 'insert into ' . FILE_SERVICE_AMOUNT_DETAILS . '
						(service_id, currency_id, amount , amount_purchase)
						values(
						' . $tool_id . ',
						' . $newcurrencyid . ',
						' . round($amount, 2) . ',
						' . round($amount_purchase, 2) . ')';
                    $mysql->query($sql);
                }
            }
        }
//---------------------------------------echo 'file log price updated'-----------------------;
        // now select all the prepaid log services----------------------
        $sql = 'select a.id from ' . PREPAID_LOG_MASTER . ' a';
        $qrydata = $mysql->query($sql);
        if ($mysql->rowCount($qrydata) > 0) {
            $rows = $mysql->fetchArray($qrydata);
            foreach ($rows as $row) {
                // now get the rates of that service
                $tool_id = $row['id'];
                $sql = 'select * from ' . PREPAID_LOG_AMOUNT_DETAILS . ' a where a.log_id=' . $tool_id;
                $qrydata = $mysql->query($sql);
                if ($mysql->rowCount($qrydata) > 0) {
                    $rows = $mysql->fetchArray($qrydata);
                    foreach ($rows as $row) {

                        if ($row['currency_id'] == $defaultCurrency) {
                            $amounttomultipy = $row['amount'];
                            $amount_purchase_tomultipy = $row['amount_purchase'];
                        }
                    }
                    $amount = $rate * $amounttomultipy;
                    $amount_purchase = $rate * $amount_purchase_tomultipy;
                    // now add new row for new currency
                    $sql = 'insert into ' . PREPAID_LOG_AMOUNT_DETAILS . '
						(log_id, currency_id, amount , amount_purchase)
						values(
						' . $tool_id . ',
						' . $newcurrencyid . ',
						' . round($amount, 2) . ',
						' . round($amount_purchase, 2) . ')';
                    $mysql->query($sql);
                }
            }
        }

//echo '<br>';
//--------------------------echo 'prepaid log price updated'--------------------------------;
        // now select all the server log services
        $sql = 'select a.id from ' . SERVER_LOG_MASTER . ' a';
        $qrydata = $mysql->query($sql);
        if ($mysql->rowCount($qrydata) > 0) {
            $rows = $mysql->fetchArray($qrydata);
            foreach ($rows as $row) {
                // now get the rates of that service
                $tool_id = $row['id'];
                $sql = 'select * from ' . SERVER_LOG_AMOUNT_DETAILS . ' a where a.log_id=' . $tool_id;
                // echo $sql;exit;
                $qrydata = $mysql->query($sql);
                if ($mysql->rowCount($qrydata) > 0) {
                    $rows = $mysql->fetchArray($qrydata);

                    foreach ($rows as $row) {

                        if ($row['currency_id'] == $defaultCurrency) {
                            $amounttomultipy = $row['amount'];
                            $amount_purchase_tomultipy = $row['amount_purchase'];
                        }
                    }

                    $amount = $rate * $amounttomultipy;
                    $amount_purchase = $rate * $amount_purchase_tomultipy;
                    // now add new row for new currency
                    $sql = 'insert into ' . SERVER_LOG_AMOUNT_DETAILS . '
						(log_id, currency_id, amount , amount_purchase)
						values(
						' . $tool_id . ',
						' . $newcurrencyid . ',
						' . round($amount, 2) . ',
						' . round($amount_purchase, 2) . ')';
                    $mysql->query($sql);
                }
            }
        }
    }
    //------------------------------------------done-----------------------------
}

header("location:" . CONFIG_PATH_SITE_ADMIN . "currency.html?reply=" . urlencode('lbl_currency_add'));
exit();
?>
