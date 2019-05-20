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

$admin->checkLogin();
$admin->reject();
$id = $request->GetInt('id');


if ($id != "") {

    $sql = 'select * from ' . INVOICE_MASTER . ' a
where  a.id=' . $id;

    $query = $mysql->query($sql);
    if ($mysql->rowCount($query) > 0) {

        $rows = $mysql->fetchArray($query);

        $row = $rows[0];

        $old_amout = $row['credits'];
       // echo $old_amout;




        $sql1 = "update " . INVOICE_MASTER . "  set paid_status=0,amount=" . $old_amout . ",date_time_paid='0000-00-00 00:00:00'
 
 where id=" . $id;
        $mysql->query($sql1);
        $sqldell = "delete from " . INVOICE_LOG . "  where inv_id=" . $id . " and remarks not in ('0','1')";
        $mysql->query($sqldell);

        $sql = "select * from " . INVOICE_LOG . " a
where  a.remarks='1' and a.id=" . $id;

        $query = $mysql->query($sql);
        if ($mysql->rowCount($query) > 0) {

            $sqlll = 'update ' . INVOICE_LOG . '  set remarks=0
 
 where id=' . $id;
            $mysql->query($sqlll);
        }
    }

    header('location:' . CONFIG_PATH_SITE_ADMIN . 'users_credit_invoices.html?status=0&reply=' . urlencode('lbl_Done'));
    exit();
}
