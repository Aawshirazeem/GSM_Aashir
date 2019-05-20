<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

    $objCredits = new credits();
	
	
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('user_add_59905855d2');

    $package_id = $request->PostStr('package_id');
    $package_name = $request->PostStr('package_name');
    $status = $request->PostCheck('status');

	if($package_name == "")
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "package_edit.html?package_id=" . $package_id . "&reply=".urlencode('reply_miss_package'));
		exit();
	}

	$sql = 'select package_name from ' . PACKAGE_MASTER . ' 
									where package_name=' . $mysql->quote($package_name) .
										  ' and id!=' . $package_id;
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "package_edit.html?package_id=" . $package_id . "&reply=".urlencode('reply_duplicate_package'));
		exit();
	}
	
	$keyword = new keyword();
    $key = $request->GetStr('key');
    
    $keyNew = $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4);
	$keyNew = strtoupper($keyNew);
	
    $loginKey = $keyword->generate(20);

	$sql = 'update ' . PACKAGE_MASTER . '
			set package_name=' . $mysql->quote($package_name) . ',
				status='	.  $status . ' where id=' . $package_id; 
	$mysql->query($sql);
	header("location:" . CONFIG_PATH_SITE_ADMIN . "package.html?reply=" . urlencode('reply_update_success'));
	exit();
?>