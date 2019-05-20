<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$request = new request();
	$mysql = new mysql();
	$api = new api();
	
    $username = $request->GetStr('username');
	
    $imei = $request->GetStr('imei');
    $nck1 = $request->GetStr('nck1');
    $nck2 = $request->GetStr('nck2');
    $nck3 = $request->GetStr('nck3');
    $nck4 = $request->GetStr('nck4');
    $nck5 = $request->GetStr('nck5');
    $nck6 = $request->GetStr('nck6');
    $nck7 = $request->GetStr('nck7');
	
	$stringData = '';
	
	for($i=1; $i<=7; $i++)
	{
		$nextItem = 'nck' . $i;
		$stringData .= 'nck1 : ' . $$nextItem . "\r\n";
	}

	
	/*$fileName = $imei . ".rpl";
	$myFile = CONFIG_PATH_EXTRA_ABSOLUTE . "file_service/" . $fileName;
	$fh = fopen($myFile, 'w') or die("can't open file");
	fwrite($fh, $stringData);
	fclose($fh);*/
	

	$sql = 'update ' . ORDER_FILE_SERVICE_MASTER . ' set unlock_code=' . $mysql->quote($stringData) . ', status=1 where extern_id=' . $imei;
	$query = $mysql->query($sql);
	echo 'Done';
	
	
?>