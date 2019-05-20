<?php
require_once('iosapi.php');

//15 chars
$Imei = '351536012345678';

//40 chars (20 bytes hash as Hex string)
$Hash = '112233445566778899AABBCCDDEEFF1122334455';

$Result = $Api->SL3JobAdd($Imei, $Hash);

echo '<pre>'.print_r($Result, true).'</pre>';

/*
    [QueuePosition] => 0
    [ExpectedRunTime] => 0
    [jobId] => 139954
    [IMEI] => 351536012345678
    [Result] => 0
    [Comment] =>
    [Operation] => SL3JobAdd 
    [Debug] => 0  

    [IMEI] => 351536012345678
    [Result] => 255
    [Comment] => QUEUED already exist
    [Operation] => SL3JobAdd
    [Debug] => 0     
*/