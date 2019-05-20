<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$imeis = $request->PostStr('imeis');
	
	if($imeis=='')
	{
		echo 'No Record Found';
	}
	
    //$imeis = str_replace("&#13;&#10;",",",$imeis);
    $imeis = str_replace("\n",",",$imeis);
	$tempImeiList = explode(",", $imeis);
	$count=0;
	echo '<pre>';
	foreach($tempImeiList as $tempImei)
	{
		if($tempImei != "")
		{
			$tempImei = trim($tempImei);
			$tempImei = preg_replace( '/\s+/', ' ', $tempImei);
			
			$tempImei = preg_replace('/\s+/', '-----MEP2:', $tempImei, 1);
			$tempImei = preg_replace('/\s+/', '-----MEP4:', $tempImei, 1);
			
			
			$tempImei = str_replace('-',' ',$tempImei);
			
			echo $tempImei . "\n";
		}
	}
	echo '</pre>';
	
	
	

		
?>
