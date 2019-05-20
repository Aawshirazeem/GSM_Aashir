<?php
defined("_VALID_ACCESS") or die("Restricted Access");


$id = $request->GetInt('id');
$type = $request->GetInt('type');
if ($type == 1) {
    $sql = 'select 
      a.`txn_id`,
      um.`username`,
      um.email,
      a.`date_time`,
       a.`date_time_paid`,
       b.`date_time` as p_date,
       c.`prefix`,
       b.`amount` as paid,
       b.`credits` as total,b.`gateway_id` as descr,
       b.`remarks`,
       a.`gateway_id`
       
      from nxt_invoice_master as a
      left join nxt_invoice_log as b
      on a.id=b.`inv_id`
      left join `nxt_currency_master` as c
      on a.`currency_id`=c.`id`
      left join `nxt_user_master` um on (a.user_id = um.id)
where a.`paid_status`=1 and inv_id=' . $id . ' and a.`user_id`=' . $member->getUserId();
} else if ($type == 0) {
    $sql = 'select 
      a.`txn_id`,
      um.`username`,
      um.email,
      a.`date_time`,
       a.`date_time_paid`,
       b.`date_time` as p_date,
       c.`prefix`,
       b.`amount` as paid,
       b.`credits` as total,b.`gateway_id`,
       b.`remarks`
      from nxt_invoice_master as a
      left join nxt_invoice_log as b
      on a.id=b.`inv_id`
      left join `nxt_currency_master` as c
      on a.`currency_id`=c.`id`
      left join `nxt_user_master` um on (a.user_id = um.id)
where a.`paid_status`=0 and inv_id=' . $id . ' and a.`user_id`=' . $member->getUserId();
}
//echo $sql;
//exit;
$query11 = $mysql->query($sql);

$get_invoice_det = 'select * from ' . INVOICE_EDIT;
$inv_detail = $mysql->query($get_invoice_det);
$inv_detail = $mysql->fetchArray($inv_detail);
//echo CONFIG_PATH_PANEL_ABSOLUTE;
// exit;



$sql = 'select * from ' . CMS_MENU_MASTER . ' where id = 1';

$query = $mysql->query($sql);

$rowCount = $mysql->rowCount($query);

$rows = $mysql->fetchArray($query);

$row = $rows[0];

if ($row['logo'] != "") {

    $logo = '<img src="' . CONFIG_PATH_THEME_NEW . 'site_logo/' . $row['logo'] . '" class="" style=""/>';
} else {

    $logo = CONFIG_SITE_TITLE;
}
?>
<link rel="stylesheet" href="http://css-spinners.com/css/spinner/throbber.css" type="text/css">
<link rel="stylesheet" href="<?php echo CONFIG_PATH_PANEL ?>assets/css/invoice_11.css" type="text/css">


<?php
if ($mysql->rowCount($query11) > 0) {
    $rows = $mysql->fetchArray($query11);
    //var_dump($rows);
    ?>


    <!doctype html>
    <html>
        <head>
            <meta charset="utf-8">
            <title><?php echo $admin->wordTrans($admin->getUserLang(), 'A simple, clean, and responsive HTML invoice template'); ?></title>

            <style>
                .invoice-box{
                    max-width:1100px;
                    margin:auto;
                    padding:30px;
                    border:1px solid #eee;
                    box-shadow:0 0 10px rgba(0, 0, 0, .15);
                    font-size:16px;
                    line-height:24px;
                    font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
                    color:#555;
                }

                .invoice-box table{
                    width:100%;
                    line-height:inherit;
                    text-align:left;
                }

                .invoice-box table td{
                    padding:5px;
                    vertical-align:top;
                }

                .invoice-box table tr td:nth-child(2){
                    text-align:right;
                }

                .invoice-box table tr.top table td{
                    padding-bottom:20px;
                }

                .invoice-box table tr.top table td.title{
                    font-size:45px;
                    line-height:45px;
                    color:#333;
                }

                .invoice-box table tr.information table td{
                    padding-bottom:40px;
                }

                .invoice-box table tr.heading td{
                    background:#eee;
                    border-bottom:1px solid #ddd;
                    font-weight:bold;
                }

                .invoice-box table tr.details td{
                    padding-bottom:20px;
                }

                .invoice-box table tr.item td{
                    border-bottom:1px solid #eee;
                }

                .invoice-box table tr.item.last td{
                    border-bottom:none;
                }

                .invoice-box table tr.total td:nth-child(2){
                    border-top:2px solid #eee;
                    font-weight:bold;
                }

                @media only screen and (max-width: 600px) {
                    .invoice-box table tr.top table td{
                        width:100%;
                        display:block;
                        text-align:center;
                    }

                    .invoice-box table tr.information table td{
                        width:100%;
                        display:block;
                        text-align:center;
                    }
                }
            </style>
        </head>

        <body>
            
            <div class="invoice-box">

                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="title" style="background:#ffffff;">
                                        <span class="logo-gsm"> <?php echo $logo; ?></span> 
                                    </td>

                                    <td style="background:#ffffff;">
                                        <h2 class="name"><?php echo $inv_detail[0]["detail"]; ?></h2><br>
                                        <?php echo $inv_detail[0]["detail2"]; ?><br>
                                        <?php echo $inv_detail[0]["detail3"]; ?><br>
                                        <?php echo $inv_detail[0]["detail4"]; ?><br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr class="information">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td>
                                        <?php echo $admin->wordTrans($admin->getUserLang(), ' INVOICE TO'); ?>: <b><?php echo $rows[0]["username"] ?></b><br>


                                        <b><?php echo $rows[0]["email"] ?></b><br>
                                        Total Credit(s): <b><?php echo $rows[0]["total"] . ' ' . $rows[0]["prefix"] ?></b><br>

                                    </td>

                                    <td>

                                        <h1><?php echo $admin->wordTrans($admin->getUserLang(), 'INVOICE'); ?> # <?php echo $id; ?></h1>

                                        <?php
                                        if ($rows[0]["gateway_id"] == "1") {
                                            ?>
                                            <div class="email"><?php echo $admin->wordTrans($admin->getUserLang(), 'Paypal Trans-ID'); ?>:<b><?php echo $rows[0]["txn_id"] ?></b></div>
                                            <?php
                                        }
                                        ?> 

                                        <?php
                                        //timezone                  
                                        $dtDateTime = new DateTime($rows[0]['date_time'], new DateTimeZone($admin->timezone()));
                                        $dtDateTime->setTimezone(new DateTimeZone($member->timezone()));
                                        $dtDateTime = $dtDateTime->format('d-M-Y H:i');
                                        //end
                                        ?>
                                        <div class="date"><?php echo $admin->wordTrans($admin->getUserLang(), 'Date of Invoice'); ?>: <?php echo $dtDateTime ?></div>
                                        <?php
                                        if ($type == 1) {
                                            //timezone paid                  
                                            $dtDateTime_paid = new DateTime($rows[0]['date_time_paid'], new DateTimeZone($admin->timezone()));
                                            $dtDateTime_paid->setTimezone(new DateTimeZone($member->timezone()));
                                            $dtDateTime_paid = $dtDateTime_paid->format('d-M-Y H:i');
                                            //end
                                            ?>
                                            <div class="date"><?php echo $admin->wordTrans($admin->getUserLang(), 'Paid Date'); ?>: <?php echo $dtDateTime_paid ?></div>
                                            <?php
                                        }
                                        ?>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                  
                </table>
  <div style="position:absolute;right:250px;opacity:0.5; z-index:11;">
                        <img src="<?php echo CONFIG_PATH_PANEL ?>/assets_1/<?php if ($type == 0) {
                                        echo 'unpaid.png';
                                    } else {
                                        echo 'paid.png';
                                    } ?>">
                    </div>
                <h4><?php echo $admin->wordTrans($admin->getUserLang(),'Invoice Summary'); ?></h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td><strong>#</strong></td>
                            <td class="text-center"><strong><?php echo $admin->wordTrans($admin->getUserLang(), 'Description'); ?></strong></td>
                            <td class="text-center"><strong><?php echo $admin->wordTrans($admin->getUserLang(), 'Date'); ?></strong></td>
                            <td class="text-center"><strong><?php echo $admin->wordTrans($admin->getUserLang(), 'Currency'); ?></strong></td>
                            <td class="text-right"><strong><?php echo $admin->wordTrans($admin->getUserLang(), 'Amount'); ?></strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $amount = 0;
                        foreach ($rows as $row) {
                            // var_dump($rows);exit;
                            if ($row["remarks"] == "0" || $row["remarks"] == "1") {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td class="text-center"><?php echo $row["descr"]; ?></td>
                                    <td class="text-center"><?php echo $row["p_date"]; ?></td>
                                    <td class="text-center"><?php echo $row["prefix"]; ?></td>
                                    <td class="text-right"><?php echo $row["paid"]; ?></td>
                                </tr>
            <?php
        } else {
            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td class="text-center"><?php echo $row["descr"]; ?></td>
                                    <td class="text-center"><?php echo $row["p_date"]; ?></td>
                                    <td class="text-center"><?php echo $row["prefix"]; ?></td>
                                    <td class="text-right"><?php echo $row["paid"]; ?></td>
                                </tr>
                                <?php
                                $amount += $row["paid"];
                            }
                            //  $amount  =$amounts - 
                            $i++;
                        }
                        ?>
                        <tr>
                            <td class="no-line" colspan="3"></td>
                            <td class="no-line text-center"><strong><?php echo $admin->wordTrans($admin->getUserLang(), 'Received'); ?></strong></td>
                            <td class="no-line text-right"><?php if ($type == 1) {
                            echo $rows[0]["total"];
                        } else {
                            echo $amount;
                        } ?></td>
                        </tr>
                    </tbody>
                </table>
                <?php
                if ($type == 0) {
                    ?>
                    <div id="thanks"><?php echo $admin->wordTrans($admin->getUserLang(), 'Remaining Balance'); ?> : <b style="color:red;"><?php echo ($rows[0]["total"] - $amount) . $rows[0]["prefix"]; ?></b> </div>
                    <?php
                } else {
                    ?>
                    <div id="thanks"><?php echo $admin->wordTrans($admin->getUserLang(), 'Thank you'); ?>!</div>
        <?php echo $pCode; ?>
        <?php
    }
}
?>
        </div>
    </body>
</html>
