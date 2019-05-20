<?php
require_once('../../iosapi.php');

$Result = $Api->Balance();

echo '<pre>'.print_r($Result, true).'</pre>';

/*
    [Comment] => User       <= User name
    [id] => 100             <= Balance
    [Operation] => Balance
    [Debug] => 0  
*/