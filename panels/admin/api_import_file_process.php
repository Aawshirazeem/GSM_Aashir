<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$admin->checkLogin();
	$admin->reject();
	$validator->formValidateAdmin('service_imei_api_import_2561561');
	
	
	$id_main = $request->PostInt('id'); 
	$clear = $request->PostInt('clear');
	$submit = $request->PostStr('submit');
	$credits_add = $request->PostFloat('credits_add');
	
	if($clear == 1)
	{
		$sql = 'truncate table ' . FILE_SERVICE_MASTER;
		$mysql->query($sql);
		
	}
	if($submit == 'Import All Services')
	{
		$sql = 'select * from ' . API_DETAILS . ' where type=2 and api_id=' . $id_main; 
		$query = $mysql->query($sql);
		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				
				$cr = $crPur = 0;
				
				$crPur = $request->PostFloat('credits_org_' . $row['id']);
				$cr = $request->PostFloat('credits_' . $row['id']);
				
				if($cr <= $crPur)
				{
					$cr += $credits_add;
				}
				
				$sql = 'insert into ' . FILE_SERVICE_MASTER . ' (service_name, credits, credits_purchase, delivery_time, status)
							values(
								' . $mysql->quote($row['service_name']) . ',
								' . $cr . ',
								' . $crPur . ',
								' . $mysql->quote($row['delivery_time']) . ',
								1
							)';
				//echo $sql . '<br /><br /><br /><br /><br />';
				$mysql->query($sql);
			}
			header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_import_imei.html?id=' . $id_main . '&reply=' . urlencode('reply_success')) ;
			exit();
		}
		else
		{
			header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_import_imei.html?id=' . $id_main . '&reply=' . urlencode('no_service_selected')) ;
			exit();
		}
	}
	else
	{
	
		if(isset($_POST['ids']))
		{	
			$ids  = $_POST['ids']; 
			foreach($ids as $id)
			{
				$sql = 'select *
							from ' . API_DETAILS . ' 
							where id=' . $id;
				$query = $mysql->query($sql);
				$rows = $mysql->fetchArray($query);
				$row = $rows[0];
				

				$cr = $crPur = 0;
				
				$crPur = $request->PostInt('credits_org_' . $id);
				$cr = $request->PostInt('credits_' . $id);
				
				if($cr <= $crPur)
				{
					$cr += $credits_add;
				}
				
				$sql = 'insert into ' . FILE_SERVICE_MASTER . ' (service_name, credits, credits_purchase, delivery_time,status)
							values(
								' . $mysql->quote($row['service_name']) . ',
								' . $cr . ',
								' . $crPur . ',
								' . $mysql->quote($row['delivery_time']) . ',
								1
							)';
				//echo $sql . '<br /><br /><br /><br /><br />';
				$mysql->query($sql);
			}
			header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_import_imei.html?id=' . $id_main . '&reply=' . urlencode('reply_success')) ;
			exit();
		}
		else
		{
			header('location:' . CONFIG_PATH_SITE_ADMIN . 'api_import_imei.html?id=' . $id_main . '&reply=' . urlencode('no_service_selected')) ;
			exit();
		}
	}

?>