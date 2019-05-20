<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$member->checkLogin();
	$member->reject();
	
    $id = $request->GetStr('id');
	
	$sql = 'select id, model from ' . IMEI_MODEL_MASTER . '
				where brand=' . $id . ' and status=1 order by model';
	$query = $mysql->query($sql);
	$rows = $mysql->fetchArray($query);
	echo '[ ';
	$val = '{optionValue: \'\', optionDisplay: \'Select Model\'}, ';
	foreach($rows as $row)
	{
		$val .= '{optionValue: \'' . $row['id'] . '\', optionDisplay: \'' . $row['model'] . '\'}, ';
	}
	$val = substr($val, 0, strlen($val)-2);
	echo $val;
	echo ' ]';
?>