<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$member->checkLogin();
	$member->reject();

    $id = $request->GetStr('id');
	
	$sql = 'select id, network from ' . IMEI_NETWORK_MASTER . ' where country=' . $mysql->getInt($id) . ' and status=1 order by network';
	$query = $mysql->query($sql);
	$rows = $mysql->fetchArray($query);
	echo '[ ';
	$val = '{optionValue: \'\', optionDisplay: \'Select Network\'}, ';
	foreach($rows as $row)
	{
		$val .= '{optionValue: \'' . $row['id'] . '\', optionDisplay: \'' . $row['network'] . '\'}, ';
	}
	$val = substr($val, 0, strlen($val)-2);
	echo $val;
	echo ' ]';
?>