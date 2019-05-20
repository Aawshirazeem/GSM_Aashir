<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	
	$firstC=$request->PostStr('firstC');
	$offset=$request->PostInt('offset');
	$limit=$request->PostInt('limit');
	$user_id = $request->PostInt('user_id');
    $ids = $_POST['ids'];
    
	$sql = 'delete from ' . IMEI_SPL_CREDITS . ' where user_id=' . $user_id;
	$mysql->query($sql);
	
	foreach($ids as $id)
	{
		if($_POST['org_' . $id] != $_POST['spl_' . $id] && $_POST['spl_' . $id] != "" /*&& $_POST['spl_' . $id] != "0"*/)
		{
			$sql = 'insert into ' . IMEI_SPL_CREDITS . '
						(user_id, tool_id, amount)
						values('
							. $mysql->getInt($user_id) . ','
							. $mysql->getInt($id) . ','
							. $mysql->getFloat($_POST['spl_' . $id]) . 
						')';
			
			$mysql->query($sql);
                        
                         $sql = 'delete from nxt_price_notify where user = ' . $user_id.' and tool_id='.$id;
                         $mysql->query($sql);
                            
                            $sql_a = 'insert into nxt_price_notify
						(tool_id,type, price,user)
						values(
						' . $id . ',
						1,
						' . $mysql->getFloat($_POST['spl_' . $id]) . ',
						' . $user_id . ')';
                    $mysql->query($sql_a);

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

	
	

	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "users_edit.html?id=" . $user_id . '&reply=' . urlencode('reply_success_credit'));
	exit();
?>