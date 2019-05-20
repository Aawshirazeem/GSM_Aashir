<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	
	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('user_special_credit_59905845ed2');


	$user_id = $request->PostInt('user_id');
	$firstC=$request->PostStr('firstC');
	$offset=$request->PostInt('offset');
	$limit=$request->PostInt('limit');
    $ids = $_POST['ids'];
   // echo "<pre>";
    //var_dump($_POST);
      //  exit;
    
	$sql = 'delete from ' . PREPAID_LOG_SPL_CREDITS . ' where user_id=' . $user_id;
       
	$mysql->query($sql);
	
	foreach($ids as $id)
	{
		if($_POST['org_' . $id] != $_POST['spl_' . $id] && $_POST['spl_' . $id] != "" && $_POST['spl_' . $id] != "0")
		{
			$sql = 'insert into ' . PREPAID_LOG_SPL_CREDITS . '
						(user_id, log_id, amount)
						values('
							. $mysql->getInt($user_id) . ','
							. $mysql->getInt($id) . ','
							. $mysql->getFloat($_POST['spl_' . $id]) . 
						')';
                       // echo $sql;exit;
			$mysql->query($sql);
                        
                            $sql = 'delete from nxt_price_notify where user = ' . $user_id.' and tool_id='.$id;
                         $mysql->query($sql);
                            
                            $sql_a = 'insert into nxt_price_notify
						(tool_id,type, price,user)
						values(
						' . $id . ',
						4,
						' . $mysql->getFloat($_POST['spl_' . $id]) . ',
						' . $user_id . ')';
                    $mysql->query($sql_a);

		}
	}

	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "users_edit.html?id=" . $user_id . "&reply=" . urlencode('reply_success_credit'));
	exit();
?>