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

$request = new request();
$mysql = new mysql();
$posted = "";

$sql = 'select a.id,a.tool_name,a.api_rate_sync,a.api_id,a.api_service_id,b.url,c.credits

from  ' . IMEI_TOOL_MASTER . ' a

inner join  ' . API_MASTER . ' b
on a.api_id=b.id

inner join  ' . API_DETAILS . ' c

on a.api_service_id=c.service_id and a.api_id=c.api_id

where a.is_check_rate=1
';
$query = $mysql->query($sql);

$dfurl = '/api/_get_rate_update.php?toolid=';
if ($mysql->rowCount($query) > 0) {
//   $rows = $mysql->fetchArray($query);
    $rows = $mysql->fetchArray($query);

    foreach ($rows as $row) {
        $urltosend = $row['url'];
        // $api_s_id = $row['api_service_id'];
        $tool_id = $row['id'];
        $oldprice = $row['api_rate_sync'];
        $urltosend = $urltosend . $dfurl;
        $srvid = $row['api_service_id'];
        $endur = $urltosend . $srvid;
        // now send curl
        $crul = curl_init();
        curl_setopt($crul, CURLOPT_HEADER, false);
        curl_setopt($crul, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($crul, CURLOPT_MAXREDIRS, 10);
        curl_setopt($crul, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($crul, CURLOPT_URL, $endur);
        curl_setopt($crul, CURLOPT_POST, true);
        curl_setopt($crul, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($crul, CURLOPT_POSTFIELDS, $posted);
        $response = curl_exec($crul);
        if (curl_errno($crul) != CURLE_OK) {
            echo curl_error($crul);
            curl_close($crul);
        } else {

            // if resonce ok then update 
            curl_close($crul);
            $temp = json_decode($response);
            echo '<br><br>TOOL IS:' . $tool_id . ' OLD PRICE IS:' . $oldprice . ' and new price is:' . $temp->newprice . '<br><br>';

            if ($oldprice < $temp->newprice) {
                //echo '<br>off this';
                // api order off
                $sqlup = 'update ' . IMEI_TOOL_MASTER . '
										set 
											
											status = 0
										where id=' . $tool_id;
                $mysql->query($sqlup);
            } else {

                $sqlup = 'update ' . IMEI_TOOL_MASTER . '
										set 
											
											status = 1
										where id=' . $tool_id;
                //$mysql->query($sqlup);

                //echo '<br>no chanaage';
            }
        }
    }
}
//var_dump($rows);

echo '<br><br>.................all done................';
exit;
