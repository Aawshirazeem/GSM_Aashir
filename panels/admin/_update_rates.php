<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

// get the default currenct id first
$sql = 'select a.id from ' . CURRENCY_MASTER . ' a where a.is_default=1';
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $defaultCurrency = $rows[0]['id'];
}
// now select all the server log services
$sql = 'select a.id from ' . SERVER_LOG_MASTER . ' a';
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    foreach ($rows as $row) {
        // now get the rates of that service
        $sql = 'select * from ' . SERVER_LOG_AMOUNT_DETAILS . ' a where a.log_id=' . $row['id'];
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

            foreach ($rows as $row) {
                $sql = 'update ' . SERVER_LOG_AMOUNT_DETAILS . ' a set a.amount=round(' . $amounttomultipy . '*(select b.rate from nxt_currency_master b where b.id=' . $row['currency_id'] . ' ),2),a.amount_purchase=round(' . $amount_purchase_tomultipy . '*(select c.rate from nxt_currency_master c where c.id=' . $row['currency_id'] . ' ),2)     where a.id=' . $row['id'];
                //  echo $sql;exit;
                $mysql->query($sql);
            }
        }
    }
}


//echo 'server log price updated';
// now select all the prepaid log services
$sql = 'select a.id from ' . PREPAID_LOG_MASTER . ' a';
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    foreach ($rows as $row) {
        // now get the rates of that service
        $sql = 'select * from ' . PREPAID_LOG_AMOUNT_DETAILS . ' a where a.log_id=' . $row['id'];
        $qrydata = $mysql->query($sql);
        if ($mysql->rowCount($qrydata) > 0) {
            $rows = $mysql->fetchArray($qrydata);

            foreach ($rows as $row) {

                if ($row['currency_id'] == $defaultCurrency) {
                    $amounttomultipy = $row['amount'];
                    $amount_purchase_tomultipy = $row['amount_purchase'];
                }
            }

            foreach ($rows as $row) {
                $sql = 'update ' . PREPAID_LOG_AMOUNT_DETAILS . ' a set a.amount=round(' . $amounttomultipy . '*(select b.rate from nxt_currency_master b where b.id=' . $row['currency_id'] . ' ),2),a.amount_purchase=round(' . $amount_purchase_tomultipy . '*(select c.rate from nxt_currency_master c where c.id=' . $row['currency_id'] . ' ),2)     where a.id=' . $row['id'];
                //   echo $sql;exit;
                $mysql->query($sql);
            }
        }
    }
}

//echo '<br>';
//echo 'prepaid log price updated';
// now select all the file servive 
$sql = 'select a.id from ' . FILE_SERVICE_MASTER . ' a';
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    foreach ($rows as $row) {
        // now get the rates of that service
        $sql = 'select * from ' . FILE_SERVICE_AMOUNT_DETAILS . ' a where a.service_id=' . $row['id'];
        $qrydata = $mysql->query($sql);
        if ($mysql->rowCount($qrydata) > 0) {
            $rows = $mysql->fetchArray($qrydata);

            foreach ($rows as $row) {

                if ($row['currency_id'] == $defaultCurrency) {
                    $amounttomultipy = $row['amount'];
                    $amount_purchase_tomultipy = $row['amount_purchase'];
                }
            }

            foreach ($rows as $row) {
                $sql = 'update ' . FILE_SERVICE_AMOUNT_DETAILS . ' a set a.amount=round(' . $amounttomultipy . '*(select b.rate from nxt_currency_master b where b.id=' . $row['currency_id'] . ' ),2),a.amount_purchase=round(' . $amount_purchase_tomultipy . '*(select c.rate from nxt_currency_master c where c.id=' . $row['currency_id'] . ' ),2)     where a.id=' . $row['id'];
                //   echo $sql;exit;
                $mysql->query($sql);
            }
        }
    }
}
//
//echo '<br>';
$dfcur = 0;
$dfcurvalsale = 0;
$dfcurvalpurc = 0;
$dftoolid = 0;
//echo 'file log price updated';
// now select all the IMEI servive 
$sql = 'select a.id from ' . IMEI_TOOL_MASTER . ' a';
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    foreach ($rows as $row) {
        // now get the rates of that service
        //  $sql = 'select * from ' . IMEI_TOOL_AMOUNT_DETAILS . ' a where a.tool_id=' . $row['id'];
        $sql = 'select * from ' . IMEI_TOOL_AMOUNT_DETAILS . ' a
where a.tool_id=' . $row['id'] . ' and a.currency_id=(SELECT cm.id from  ' . CURRENCY_MASTER . ' cm
where cm.is_default=1)
';
        $qrydata = $mysql->query($sql);
        if ($mysql->rowCount($qrydata) > 0) {
            $rowsss = $mysql->fetchArray($qrydata);

            foreach ($rowsss as $row) {

                // get the def cur id and cur val
                $dfcur = $row['currency_id'];
                $dfcurvalsale = $row['amount'];
                $dfcurvalpurc = $row['amount_purchase'];
                $dftoolid = $row['tool_id'];
            }
            //now we have the values
            $sqldelll = 'delete from ' . IMEI_TOOL_AMOUNT_DETAILS . ' where tool_id = ' . $dftoolid;
            $mysql->query($sqldelll);

            // now calculate ad new for that tool

            $sqlcur = 'select * from ' . CURRENCY_MASTER . ' v where v.`status`=1';
            $qrydatacur = $mysql->query($sqlcur);
            if ($mysql->rowCount($qrydatacur) > 0) {
                $rowscm = $mysql->fetchArray($qrydatacur);
                // echo $dfcur;
                //               var_dump($rowscm);exit;
                foreach ($rowscm as $rowcm) {

                    if ($rowcm['id'] == $dfcur) {
                        //    $amount = $request->PostFloat('amount_' . $currency_id);
                        //  $amount_purchase = $request->PostFloat('amount_purchase_' . $currency_id);
                        $amount = round($dfcurvalsale, 2);
                        $amount_purchase = round($dfcurvalpurc, 2);

                        $sql = 'insert into ' . IMEI_TOOL_AMOUNT_DETAILS . '
						(tool_id, currency_id, amount , amount_purchase)
						values(
						' . $dftoolid . ',
						' . $rowcm['id'] . ',
						' . $amount . ',
						' . $amount_purchase . ')';
                        $mysql->query($sql);
                    } else {
                        //  $amount = $request->PostFloat('amount_' . $currency_id);
                        //  $amount_purchase = $request->PostFloat('amount_purchase_' . $currency_id);

                        $dfcurvalsaleaaa = $dfcurvalsale * $rowcm['rate'];
                        $dfcurvalpurcaaa = $dfcurvalpurc * $rowcm['rate'];
                        $amount = round($dfcurvalsaleaaa, 2);
                        $amount_purchase = round($dfcurvalpurcaaa, 2);

                        $sql = 'insert into ' . IMEI_TOOL_AMOUNT_DETAILS . '
						(tool_id, currency_id, amount , amount_purchase)
						values(
						' . $dftoolid . ',
						' . $rowcm['id'] . ',
						' . $amount . ',
						' . $amount_purchase . ')';
                        $mysql->query($sql);
                    }
                }
            }
        }
    }
}

//echo '<br>';
echo 'All Prices Updated';
//header("location:" . CONFIG_PATH_SITE_ADMIN . "currency.html?reply=" . urlencode('rate_update_done'));
exit();
