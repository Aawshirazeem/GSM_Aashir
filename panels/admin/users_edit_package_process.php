<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('user_edit_789971255d2');

    $id = $request->PostInt('id');
    $service_imei = $request->PostInt('service_imei');
	$firstC = $request->PostStr('firstC');
	$offset = $request->PostInt('offset');
	$limit = $request->PostInt('limit');
	
	if(isset($_POST['allot']))
	{
	    $package_id = $request->PostInt('package_id');
		
		// to delete previous alloted package
		$sql_del='delete from ' 
					. PACKAGE_USERS . ' 
								where user_id='. $id ; 
		$mysql->query($sql_del);
		
		if($package_id!=0)
		{
			//to allot package to user
			$sql_add = 'insert into ' 
							. PACKAGE_USERS . '(package_id,user_id)
											values('
												. $package_id .','
												. $id .')';
			$mysql->query($sql_add);									
		}
	}

	header("location:" . CONFIG_PATH_SITE_ADMIN . "users_edit.html?id=" . $id. "&reply=" . urlencode('reply_package_alloted'));
	exit();
?>