<?php
require_once('../../iosapi.php');

$Result = $Api->VersionApi();

echo '<pre>'.print_r($Result, true).'</pre>';

/*
    [Version] => 1
    [Result] => 0
    [Operation] => VersionApi
    [Debug] => 0  
*/
