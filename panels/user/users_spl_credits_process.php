<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$member->checkLogin();
	$member->reject();
	
	$firstC=$request->PostStr('firstC');
	$offset=$request->PostInt('offset');
	$limit=$request->PostInt('limit');
	$user_id = $request->PostInt('user_id');
        $reseller_id = $request->PostInt('reseller_id');
        
        $ids = $_POST['ids'];
    
	$sql = 'delete from ' . IMEI_SPL_CREDITS_RESELLER . ' where user_id=' . $user_id;
	$mysql->query($sql);
	
	foreach($ids as $id)
	{
		if($_POST['org_' . $id] != $_POST['total_' . $id] && $_POST['total_' . $id] != "" && $_POST['total_' . $id] != "NaN" /*&& $_POST['spl_' . $id] != "0"*/)
		{
			$sql = 'insert into ' . IMEI_SPL_CREDITS_RESELLER . '
						(user_id, tool_id, amount,reseller_id)
						values('
							. $mysql->getInt($user_id) . ','
							. $mysql->getInt($id) . ','
							. $mysql->getFloat($_POST['total_' . $id]) . ','
                                                        .$reseller_id.
						')';
			
			$mysql->query($sql);

		}
	}
	
/*
	$sql = 'select id from ' . USER_MASTER . ' where reseller_id=' . $id;
	$query = $mysql->query($sql);
	if($mysql->rowCount($query)> 0)
	{
		$rows = $mysql->fetchArray($query);
		foreach($rows as $row)
		{
			$user_id = $row['id'];
			
			$sql = 'delete from ' . IMEI_SPL_CREDITS . ' where user_id=' . $user_id;
			$mysql->query($sql);
			
			$sql_chk ='select * from ' . IMEI_SPL_CREDITS . ' where user_id=' . $id;
			$query_chk = $mysql->query($sql_chk);
			if($mysql->rowCount($query_chk) > 0)
			{
				$rows_chk = $mysql->fetchArray($query_chk);
				foreach($rows_chk as $row_chk)
				{	
					$sql = 'insert into ' . IMEI_SPL_CREDITS . '
								(user_id, tool, credits)
								values('
									. $user_id . ','
									. $row_chk['tool'] . ','
									. $row_chk['credits'] .
								')';
					$mysql->query($sql);
				}
			}
			
			
			
		}
	}*/

	
	

	
	header("location:" . CONFIG_PATH_SITE_USER . "users.html?reply=" . urlencode('reply_success_credit'));
	exit();
?>