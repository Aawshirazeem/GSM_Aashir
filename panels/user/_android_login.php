<?php
	$response = array();
	$response["success"] = 1;
	$response["message"] = "Login successful!";
	print_r($response);
	die(json_encode($response));
?>