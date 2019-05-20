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

//$admin->checkLogin();
//$admin->reject();


$sqll = 'SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
  WHERE table_name = ' . $mysql->quote(ORDER_IMEI_MASTER) . ' AND COLUMN_NAME =' . $mysql->quote('message') . 'and DATA_TYPE=' . $mysql->quote('text');
$query1 = $mysql->query($sqll);

$rowCount = $mysql->rowCount($query1);

if ($rowCount == 0) {



// update message col type first

    $sql11 = 'ALTER TABLE ' . ORDER_IMEI_MASTER . ' MODIFY message TEXT';
    $query = $mysql->query($sql11);

// get old data
    $sql = 'select a.id,a.message from ' . ORDER_IMEI_MASTER . ' a';

    $query = $mysql->query($sql);

    if ($mysql->rowCount($query) > 0) {

        $rows = $mysql->fetchArray($query);
        // var_dump($rows);exit;

        foreach ($rows as $row) {

            $newmsgss = base64_encode($row['message']);
            if ($newmsgss != "") {
                //update data

                $sql2 = 'update ' . ORDER_IMEI_MASTER . ' set message=' . $mysql->quote($newmsgss) . ' where id=' . $mysql->getInt($row['id']);
                $mysql->query($sql2);
            }
        }
    }

    echo 'Data Updated Done..Thanks';
} else {
    echo 'Already Done..Thanks';
}

exit;