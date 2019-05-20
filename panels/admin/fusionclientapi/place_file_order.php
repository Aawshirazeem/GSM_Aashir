<?php

/**
 *	@author Dhru.com
 *	@APi kit version 2.0 March 01, 2012
 *	@Copyleft GPL 2001-2011, Dhru.com
 **/

require ('header.php');
include ('dhrufusionapi.class.php');


$api = new DhruFusion();
// Debug on
$api->debug = true;

$para['ID'] = '113';
$para['FILENAME'] = 'ORDERID31TEST.txt';
$para['FILEDATA'] = base64_encode('TESTDATA');
$request = $api->action('placefileorder',$para);


echo '<PRE>';
print_r($request);
echo '</PRE>';

?>