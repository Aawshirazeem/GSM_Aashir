<?php
require_once('../../iosapi.php');

$Imei = '351536012345678';
$Result = $Api->SL3JobCheck($Imei);

echo '<pre>'.print_r($Result, true).'</pre>';

/*
    [IMEI] => 351536012345678
    [Code] => 1234567890123456  <= final unlock code
    [Result] => 0
    [Comment] =>
    [Operation] => SL3JobCheck 
    [Debug] => 0

    [IMEI] => 351536012345678
    [Code] => 
    [Result] => 0
    [Comment] => QUEUED
    [Operation] => SL3JobCheck
    [Debug] => 0

    [IMEI] => 351536012345678
    [Code] => 
    [Result] => 0
    [Comment] => EXECUTING
    [Operation] => SL3JobCheck
    [Debug] => 0

    [IMEI] => 351536012345678
    [Code] => 
    [Result] => 255
    [Comment] => job not found
    [Operation] => SL3JobCheck
    [Debug] => 0  
*/